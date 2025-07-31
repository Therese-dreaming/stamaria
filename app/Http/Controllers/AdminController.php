<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Service;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // You can add admin role middleware here if needed
        // $this->middleware('admin');
    }

    /**
     * Display the admin dashboard
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalServices = Service::count();
        $recentUsers = User::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('totalUsers', 'totalServices', 'recentUsers'));
    }

    /**
     * Display user management page
     */
    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    /**
     * Display service management page
     */
    public function services()
    {
        $services = Service::latest()->paginate(15);
        return view('admin.services', compact('services'));
    }

    /**
     * Show the form for creating a new service
     */
    public function createService()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service
     */
    public function storeService(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'requirements' => 'nullable|array',
            'requirements.*' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:0',
            'slots' => 'required|integer|min:1',
            'schedule_preset' => 'nullable|string',
            'service_time' => 'nullable|string',
            'custom_days' => 'nullable|array',
            'custom_times' => 'nullable|array',
            'schedule_description' => 'nullable|string',
            // New flexible schedule fields
            'day_specific_times' => 'nullable|array',
            'day_specific_times.*' => 'nullable|array',
            'day_specific_times.*.*' => 'nullable|string',
        ]);

        // Process requirements - convert array to string
        $requirements = null;
        if ($request->requirements) {
            // Filter out empty requirements and join with bullet points
            $filteredRequirements = array_filter($request->requirements, function($req) {
                return !empty(trim($req));
            });
            
            if (!empty($filteredRequirements)) {
                $requirements = implode("\n• ", $filteredRequirements);
                $requirements = "• " . $requirements; // Add bullet to the first item
            }
        }

        // Process schedule information with flexible day-specific times
        $scheduleData = null;
        if (!empty($request->schedule_preset)) {
            $scheduleData = [
                'preset' => $request->schedule_preset,
                'primary_time' => $request->service_time ?? null
            ];

            // Handle preset to days mapping
            switch ($request->schedule_preset) {
                case 'daily':
                    $scheduleData['days'] = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                    break;
                case 'weekdays':
                    $scheduleData['days'] = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                    break;
                case 'weekends':
                    $scheduleData['days'] = ['saturday', 'sunday'];
                    break;
                case 'sundays':
                    $scheduleData['days'] = ['sunday'];
                    break;
                case 'saturdays':
                    $scheduleData['days'] = ['saturday'];
                    break;
                case 'wed_sat':
                    $scheduleData['days'] = ['wednesday', 'saturday'];
                    break;
                case 'custom':
                    $scheduleData['days'] = $request->custom_days ?? [];
                    $scheduleData['additional_times'] = array_filter($request->custom_times ?? []);
                    break;
            }
            
            // Process day-specific times if provided
            if ($request->day_specific_times) {
                $daySpecificTimes = [];
                foreach ($request->day_specific_times as $day => $times) {
                    if (is_array($times) && !empty(array_filter($times))) {
                        $daySpecificTimes[$day] = array_filter($times);
                    }
                }
                if (!empty($daySpecificTimes)) {
                    $scheduleData['day_specific_times'] = $daySpecificTimes;
                }
            }
            
            if (!empty($request->schedule_description)) {
                $scheduleData['description'] = $request->schedule_description;
            }
        }

        // Create service data
        $serviceData = [
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'requirements' => $requirements,
            'additional_notes' => $request->additional_notes,
            'price' => $request->price,
            'duration_minutes' => $request->duration_minutes,
            'slots' => $request->slots,
            'schedules' => $scheduleData ? [$scheduleData] : null,
        ];

        Service::create($serviceData);

        return redirect()->route('admin.services')
            ->with('success', 'Service created successfully!');
    }

    /**
     * Show the form for editing a service
     */
    public function editService(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service
     */
    public function updateService(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:0',
            'slots' => 'required|integer|min:1',
            'requirements' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration_minutes' => $request->duration_minutes,
            'slots' => $request->slots,
            'requirements' => $request->requirements,
            'additional_notes' => $request->additional_notes,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.services')
            ->with('success', 'Service updated successfully!');
    }

    /**
     * Remove the specified service
     */
    public function destroyService(Service $service)
    {
        $service->delete();
        
        return redirect()->route('admin.services')
            ->with('success', 'Service deleted successfully!');
    }

    /**
     * Display settings page
     */
    public function settings()
    {
        return view('admin.settings');
    }

    public function showService(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    /**
     * Display form fields management for a service
     */
    public function formFields(Service $service)
    {
        $service->load('formFields');
        return view('admin.services.form-fields', compact('service'));
    }

    /**
     * Store a new form field
     */
    public function storeFormField(Request $request, Service $service)
    {
        $request->validate([
            'field_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_]+$/',
            'label' => 'required|string|max:255',
            'field_type' => 'required|string|in:text,email,tel,date,time,textarea,select,checkbox,radio,number,file',
            'options' => 'nullable|array',
            'required' => 'nullable|boolean',
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string',
            'validation_rules' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_conditional' => 'nullable|boolean',
            'condition_field' => 'nullable|string|max:255',
            'condition_value' => 'nullable|string|max:255',
        ]);

        $service->formFields()->create($request->all());

        return redirect()->route('admin.services.form-fields', $service)
            ->with('success', 'Form field created successfully.');
    }

    /**
     * Update a form field
     */
    public function updateFormField(Request $request, Service $service, $fieldId)
    {
        $field = $service->formFields()->findOrFail($fieldId);
        
        $request->validate([
            'field_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_]+$/',
            'label' => 'required|string|max:255',
            'field_type' => 'required|string|in:text,email,tel,date,time,textarea,select,checkbox,radio,number,file',
            'options' => 'nullable|array',
            'required' => 'nullable|boolean',
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string',
            'validation_rules' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_conditional' => 'nullable|boolean',
            'condition_field' => 'nullable|string|max:255',
            'condition_value' => 'nullable|string|max:255',
        ]);

        $field->update($request->all());

        return redirect()->route('admin.services.form-fields', $service)
            ->with('success', 'Form field updated successfully.');
    }

    /**
     * Delete a form field
     */
    public function destroyFormField(Service $service, $fieldId)
    {
        $field = $service->formFields()->findOrFail($fieldId);
        $field->delete();

        return redirect()->route('admin.services.form-fields', $service)
            ->with('success', 'Form field deleted successfully.');
    }
}
