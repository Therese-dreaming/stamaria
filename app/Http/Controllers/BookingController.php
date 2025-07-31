<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class BookingController extends Controller
{
    public function step1(Service $service)
    {
        return view('booking.step1-booking', compact('service'));
    }

    public function step2(Request $request)
    {
        // Validate step 1 data
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'required',
            'special_requests' => 'nullable|string',
            'terms_accepted' => 'required|accepted',
        ]);

        // Store step 1 data in session
        $request->session()->put('booking.step1', $request->all());

        // Load service with form fields
        $service = Service::with('formFields')->findOrFail($request->service_id);

        return view('booking.step2-booking', compact('service'));
    }

    public function step3(Request $request)
    {
        // Load service with form fields
        $service = Service::with('formFields')->findOrFail($request->service_id);

        // Get step 1 data from session
        $step1Data = $request->session()->get('booking.step1');
        if (!$step1Data) {
            return redirect()->route('booking.step1', $service);
        }

        // Build validation rules for custom fields
        $validationRules = [];
        $customFields = [];

        foreach ($service->formFields as $field) {
            $fieldName = 'custom_field_' . $field->field_name;
            $customFields[$fieldName] = $field;

            // Base validation rules from field configuration
            if ($field->validation_rules) {
                $validationRules[$fieldName] = $field->validation_rules;
            }

            // Handle conditional validation
            if ($field->is_conditional && $field->required) {
                $conditionField = 'custom_field_' . $field->condition_field;
                $conditionValue = $field->condition_value;

                // Check if the condition is met
                $conditionMet = false;
                if ($request->has($conditionField)) {
                    $conditionFieldValue = $request->input($conditionField);
                    $conditionMet = ($conditionFieldValue == $conditionValue);
                }

                // If condition is met, make the field required
                if ($conditionMet) {
                    if (isset($validationRules[$fieldName])) {
                        $validationRules[$fieldName] = 'required|' . $validationRules[$fieldName];
                    } else {
                        $validationRules[$fieldName] = 'required';
                    }
                }
            } elseif ($field->required && !$field->is_conditional) {
                // Always required field
                if (isset($validationRules[$fieldName])) {
                    $validationRules[$fieldName] = 'required|' . $validationRules[$fieldName];
                } else {
                    $validationRules[$fieldName] = 'required';
                }
            }
        }

        // Validate the request
        $request->validate($validationRules);

        // Store step 2 data in session
        $step2Data = $request->except(['_token', 'service_id']);
        $request->session()->put('booking.step2', $step2Data);

        // Combine all booking data
        $bookingData = array_merge($step1Data, $step2Data);
        $bookingData['service'] = $service;
        $bookingData['custom_fields'] = $customFields;

        return view('booking.success', compact('service', 'bookingData'));
    }
}