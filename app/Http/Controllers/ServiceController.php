<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index()
    {
        $services = Service::orderBy('name')->get();
        return view('services', compact('services'));
    }

    /**
     * Show booking form for a specific service.
     */
    public function book(Request $request)
    {
        $serviceType = $request->get('service_type');
        $understood = $request->get('understood');
        
        $service = Service::where('slug', $serviceType)->first();
        
        if (!$service) {
            return redirect()->route('services')->with('error', 'Service not found.');
        }
        
        return view('booking.step1-booking', compact('service', 'understood'));
    }
}
