<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceFormField;
use Illuminate\Http\Request;

class ServiceFormFieldController extends Controller
{
    public function index(Service $service)
    {
        $service->load('formFields');
        return view('admin.services.form-fields', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        // Debug: Log the request data
        \Log::info('Form field store request:', $request->all());
        
        $request->validate([
            'field_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_]+$/',
            'label' => 'required|string|max:255',
            'field_type' => 'required|string|in:text,email,tel,date,time,textarea,select,checkbox,radio,number,file',
            'order' => 'nullable|integer|min:0',
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string',
            'validation_rules' => 'nullable|string',
            'required' => 'nullable|boolean',
            'is_conditional' => 'nullable|boolean',
            'condition_field' => 'nullable|string|max:255',
            'condition_value' => 'nullable|string|max:255',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
        ]);

        // Check if field name already exists for this service
        $existingField = $service->formFields()->where('field_name', $request->field_name)->first();
        if ($existingField) {
            return back()->withErrors(['field_name' => 'A field with this name already exists for this service.']);
        }

        try {
            $field = new ServiceFormField();
            $field->service_id = $service->id;
            $field->field_name = $request->field_name;
            $field->label = $request->label;
            $field->field_type = $request->field_type;
            $field->order = $request->order ?? 0;
            $field->placeholder = $request->placeholder;
            $field->help_text = $request->help_text;
            $field->validation_rules = $request->validation_rules;
            $field->required = $request->boolean('required');
            $field->is_conditional = $request->boolean('is_conditional');
            $field->condition_field = $request->condition_field;
            $field->condition_value = $request->condition_value;
            $field->options = $request->options ? array_filter($request->options) : null;
            $field->save();

            \Log::info('Form field created successfully:', $field->toArray());

            return redirect()->route('admin.services.form-fields', $service)
                ->with('success', 'Form field created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating form field:', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to create form field: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, Service $service, ServiceFormField $formField)
    {
        $request->validate([
            'field_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_]+$/',
            'label' => 'required|string|max:255',
            'field_type' => 'required|string|in:text,email,tel,date,time,textarea,select,checkbox,radio,number,file',
            'order' => 'nullable|integer|min:0',
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string',
            'validation_rules' => 'nullable|string',
            'required' => 'nullable|boolean',
            'is_conditional' => 'nullable|boolean',
            'condition_field' => 'nullable|string|max:255',
            'condition_value' => 'nullable|string|max:255',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
        ]);

        // Check if field name already exists for this service (excluding current field)
        $existingField = $service->formFields()
            ->where('field_name', $request->field_name)
            ->where('id', '!=', $formField->id)
            ->first();
        if ($existingField) {
            return back()->withErrors(['field_name' => 'A field with this name already exists for this service.']);
        }

        $formField->field_name = $request->field_name;
        $formField->label = $request->label;
        $formField->field_type = $request->field_type;
        $formField->order = $request->order ?? 0;
        $formField->placeholder = $request->placeholder;
        $formField->help_text = $request->help_text;
        $formField->validation_rules = $request->validation_rules;
        $formField->required = $request->boolean('required');
        $formField->is_conditional = $request->boolean('is_conditional');
        $formField->condition_field = $request->condition_field;
        $formField->condition_value = $request->condition_value;
        $formField->options = $request->options ? array_filter($request->options) : null;
        $formField->save();

        return redirect()->route('admin.services.form-fields', $service)
            ->with('success', 'Form field updated successfully.');
    }

    public function destroy(Service $service, ServiceFormField $formField)
    {
        $formField->delete();

        return response()->json(['success' => true]);
    }
} 