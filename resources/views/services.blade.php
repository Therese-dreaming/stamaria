@extends('layouts.main')

@section('title', 'Services - Sta. Marta Parish')

@section('content')
    <!-- Hero Section -->
    <div class="relative h-[40vh]">
        <img src="{{ asset('images/church-bg.jpg') }}" alt="Church Background" class="absolute inset-0 w-full h-full object-cover brightness-50" />
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Our Services</h1>
            <p class="text-xl">Serving the community through faith and devotion</p>
        </div>
    </div>

    <!-- Services Section -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-4">
            <!-- Services Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 mb-4 flex items-center justify-center text-[#0d5c2f] bg-[#0d5c2f]/10 rounded-xl">
                        <i class="{{ $service->icon }} text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">{{ $service->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                    <!-- Types or Price/Duration -->
                    @if(is_array($service->types) && count($service->types) > 0)
                        <div class="mb-4">
                            <div class="font-semibold text-gray-700 mb-1">Types:</div>
                            <ul class="space-y-1">
                                @foreach($service->types as $type)
                                    <li class="flex justify-between items-center font-bold">
                                        <span>{{ is_array($type) ? ($type['name'] ?? $type[0] ?? '') : $type }}</span>
                                        @if(is_array($type) && isset($type['price']))
                                            <span class="text-[#0d5c2f] font-bold">₱{{ number_format($type['price'], 2) }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex flex-col">
                                <span class="text-2xl font-bold text-[#0d5c2f]">
                                    @if($service->price > 0)
                                        {{ $service->formatted_price }}
                                    @else
                                        <span class="text-green-600">Free</span>
                                    @endif
                                </span>
                                @if($service->formatted_duration)
                                    <span class="text-sm text-gray-500">Duration: {{ $service->formatted_duration }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                    @auth
                    <a href="{{ route('booking.step1', $service) }}" class="inline-flex items-center text-[#0d5c2f] font-semibold hover:text-[#b8860b] transition-colors">
                        Book Now <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="inline-flex items-center text-[#0d5c2f] font-semibold hover:text-[#b8860b] transition-colors">
                        Login to Book <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    @endauth
                </div>
                @endforeach
            </div>
            
            <!-- Services Pricing Table -->
            <div class="mt-16">
                <h2 class="text-3xl font-bold text-center mb-8 text-[#0d5c2f]">Service Pricing</h2>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-[#0d5c2f] text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold">Service</th>
                                    <th class="px-6 py-4 text-left font-semibold">Description</th>
                                    <th class="px-6 py-4 text-left font-semibold">Duration</th>
                                    <th class="px-6 py-4 text-left font-semibold">Price</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($services as $service)
                                    @if(is_array($service->types) && count($service->types) > 0)
                                        @foreach($service->types as $type)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <i class="{{ $service->icon }} text-[#0d5c2f] mr-3 text-lg"></i>
                                                        <span class="font-semibold text-gray-900">{{ is_array($type) ? ($type['name'] ?? $type[0] ?? '') : $type }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-gray-600 max-w-xs">
                                                    {{ Str::limit($service->description, 100) }}
                                                </td>
                                                <td class="px-6 py-4 text-gray-600">
                                                    @if(is_array($type) && isset($type['duration']))
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ $type['duration'] }}
                                                        </span>
                                                    @elseif($service->formatted_duration)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ $service->formatted_duration }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">Varies</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if(is_array($type) && isset($type['price']))
                                                        <span class="text-xl font-bold text-[#0d5c2f]">₱{{ number_format($type['price'], 2) }}</span>
                                                    @elseif($service->price > 0)
                                                        <span class="text-xl font-bold text-[#0d5c2f]">{{ $service->formatted_price }}</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-heart mr-1"></i>
                                                            Free
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <i class="{{ $service->icon }} text-[#0d5c2f] mr-3 text-lg"></i>
                                                    <span class="font-semibold text-gray-900">{{ $service->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600 max-w-xs">
                                                {{ Str::limit($service->description, 100) }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">
                                                @if($service->formatted_duration)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $service->formatted_duration }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">Varies</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($service->price > 0)
                                                    <span class="text-xl font-bold text-[#0d5c2f]">{{ $service->formatted_price }}</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-heart mr-1"></i>
                                                        Free
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Information Modal -->
    <div id="serviceInfoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg w-full max-w-3xl mx-auto flex flex-col max-h-[90vh]">
            <div class="flex justify-between items-center p-6 sticky top-0 bg-[#0d5c2f] border-b z-10">
                <h3 class="text-xl font-semibold text-white" id="modalTitle">Service Information</h3>
                <button onclick="closeServiceInfo()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <div id="serviceContent">
                    <!-- Service information will be loaded here -->
                </div>
            </div>
            <div class="sticky bottom-0 bg-[#0d5c2f] border-t p-6">
                <div class="flex items-start gap-3">
                    <input type="checkbox" id="understandCheckbox" class="mt-1.5 h-5 w-5 rounded border-gray-300 text-[#18421F] focus:ring-[#18421F]">
                    <div class="flex-grow">
                        <label for="understandCheckbox" class="text-sm font-medium text-white block">I understand and agree to provide all the required documents and follow the scheduling guidelines.</label>
                        <div id="checkboxError" class="hidden text-red-300 text-sm mt-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Please acknowledge that you understand the requirements before proceeding.</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="handleUnderstand()" class="bg-white text-[#0d5c2f] px-6 py-2 rounded-lg hover:bg-gray-100 font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        I Understand
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedService = '';
        const services = @json($services);

        function showServiceInfo(serviceSlug) {
            selectedService = serviceSlug;
            const modal = document.getElementById('serviceInfoModal');
            const title = document.getElementById('modalTitle');
            // Find the service data
            const service = services.find(s => s.slug === serviceSlug);
            if (service) {
                title.textContent = service.name + ' Service Information';
                // Create service information content
                const content = document.getElementById('serviceContent');
                let html = `<div class="space-y-6">`;
                html += `<div><h4 class="text-lg font-semibold text-gray-900 mb-2">Service Details</h4><p class="text-gray-600">${service.description}</p></div>`;
                if (service.types && service.types.length > 0) {
                    html += `<div><h5 class="font-semibold text-gray-900 mb-2">Types</h5><ul class="list-disc list-inside text-gray-600">${service.types.map(t => `<li>${t}</li>`).join('')}</ul></div>`;
                }
                if (service.schedules) {
                    html += `<div><h5 class="font-semibold text-gray-900 mb-2">Schedules</h5><p class="text-gray-600">${service.schedules}</p></div>`;
                }
                if (service.requirements && service.requirements.length > 0) {
                    html += `<div><h5 class="font-semibold text-gray-900 mb-2">Requirements</h5><ul class="list-decimal list-inside text-gray-600">${service.requirements.map(r => `<li>${r}</li>`).join('')}</ul></div>`;
                }
                if (service.additional_notes) {
                    html += `<div><h5 class="font-semibold text-gray-900 mb-2">Additional Notes</h5><p class="text-gray-600">${service.additional_notes}</p></div>`;
                }
                html += `</div>`;
                content.innerHTML = html;
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeServiceInfo() {
            const modal = document.getElementById('serviceInfoModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function handleUnderstand() {
            const checkbox = document.getElementById('understandCheckbox');
            const checkboxError = document.getElementById('checkboxError');

            if (checkbox.checked) {
                checkboxError.classList.add('hidden');
                // Redirect to booking page with service type and understood parameters
                window.location.href = `{{ route('services.book') }}?service_type=${selectedService}&understood=1`;
            } else {
                checkboxError.classList.remove('hidden');
            }
        }
    </script>
@endsection

