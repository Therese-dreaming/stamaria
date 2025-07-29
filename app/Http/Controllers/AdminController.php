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
            // General service information (for services without types)
            'general_price' => 'nullable|numeric|min:0',
            'general_duration_minutes' => 'nullable|integer|min:0',
            'general_schedule_preset' => 'nullable|string',
            'general_service_time' => 'nullable|string',
            'general_custom_days' => 'nullable|array',
            'general_custom_times' => 'nullable|array',
            'general_schedule_description' => 'nullable|string',
            // Service types
            'service_types' => 'nullable|array',
            'service_types.*.name' => 'required|string|max:255',
            'service_types.*.price' => 'nullable|numeric|min:0',
            'service_types.*.duration_minutes' => 'nullable|integer|min:0',
            'service_types.*.schedule_preset' => 'nullable|string',
            'service_types.*.service_time' => 'nullable|string',
            'service_types.*.custom_days' => 'nullable|array',
            'service_types.*.custom_times' => 'nullable|array',
            'service_types.*.schedule_description' => 'nullable|string',
        ]);

        // Process service types and schedules
        $processedTypes = [];
        
        if ($request->service_types) {
            foreach ($request->service_types as $index => $serviceType) {
            $scheduleData = null;
            
            if (!empty($serviceType['schedule_preset'])) {
                $scheduleData = [
                    'preset' => $serviceType['schedule_preset'],
                    'primary_time' => $serviceType['service_time'] ?? null
                ];

                // Handle preset to days mapping
                switch ($serviceType['schedule_preset']) {
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
                        $scheduleData['days'] = $serviceType['custom_days'] ?? [];
                        $scheduleData['additional_times'] = array_filter($serviceType['custom_times'] ?? []);
                        break;
                }
                
                if (!empty($serviceType['schedule_description'])) {
                    $scheduleData['description'] = $serviceType['schedule_description'];
                }
            }

            $processedTypes[] = [
                'name' => $serviceType['name'],
                'price' => $serviceType['price'] ?? null,
                'duration_minutes' => $serviceType['duration_minutes'] ?? null,
                'schedule' => $scheduleData
            ];
            }
        }

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

        // BASIC SERVICE DATA - Always stored at service level, regardless of whether service types exist
        $serviceData = [
            'name' => $request->name,                    // Service name (e.g., "Wedding Service")
            'description' => $request->description,      // Service description
            'icon' => $request->icon,                    // FontAwesome icon class
            'requirements' => $requirements,             // Formatted requirements string
            'additional_notes' => $request->additional_notes, // Additional notes
            'types' => $processedTypes,                  // Array of service types with their own pricing/scheduling
            'price' => null,                            // Service-level price (only used when no types exist)
            'duration_minutes' => null,                 // Service-level duration (only used when no types exist)
            'schedules' => null,                        // Service-level schedule (only used when no types exist)
        ];
        
        // Only use general service information if no service types are defined
        // If service types exist, completely disregard general service information
        if (empty($processedTypes)) {
            $serviceData['price'] = $request->general_price;
            $serviceData['duration_minutes'] = $request->general_duration_minutes;
            
            // Process general schedule information
            $generalScheduleData = null;
            if (!empty($request->general_schedule_preset)) {
                $generalScheduleData = [
                    'preset' => $request->general_schedule_preset,
                    'primary_time' => $request->general_service_time
                ];

                // Handle preset to days mapping for general schedule
                switch ($request->general_schedule_preset) {
                    case 'daily':
                        $generalScheduleData['days'] = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                        break;
                    case 'weekdays':
                        $generalScheduleData['days'] = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                        break;
                    case 'weekends':
                        $generalScheduleData['days'] = ['saturday', 'sunday'];
                        break;
                    case 'sundays':
                        $generalScheduleData['days'] = ['sunday'];
                        break;
                    case 'saturdays':
                        $generalScheduleData['days'] = ['saturday'];
                        break;
                    case 'wed_sat':
                        $generalScheduleData['days'] = ['wednesday', 'saturday'];
                        break;
                    case 'custom':
                        $generalScheduleData['days'] = $request->general_custom_days ?? [];
                        $generalScheduleData['additional_times'] = array_filter($request->general_custom_times ?? []);
                        break;
                }
                
                if (!empty($request->general_schedule_description)) {
                    $generalScheduleData['description'] = $request->general_schedule_description;
                }
            }
            
            // Store processed general schedule as JSON in schedules field
            $serviceData['schedules'] = $generalScheduleData ? [$generalScheduleData] : null;
        }
        // If service types exist, general service information is automatically disregarded
        // The service will only use the individual service type configurations (price, duration_minutes, schedules remain null)

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
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'requirements' => 'nullable|string',
            'schedule_info' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'requirements' => $request->requirements,
            'schedule_info' => $request->schedule_info,
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
}
