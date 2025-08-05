@extends('layouts.main')

@section('title', 'Upload Requirements - ' . $service->name)

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-green-50 to-yellow-50 py-8 pt-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#0d5c2f] mb-2">Upload Requirements</h1>
            <p class="text-gray-600">Please upload the required documents for {{ $service->name }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Service Information -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-[#0d5c2f]/10 rounded-xl flex items-center justify-center mr-4">
                        <i class="{{ $service->icon }} text-[#0d5c2f] text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h2>
                        <p class="text-gray-500">Service ID: {{ $service->id }}</p>
                    </div>
                </div>

                <!-- Progress Indicator -->
                <div class="mb-6">
                    <div class="flex items-center justify-center">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-[#0d5c2f] text-white rounded-full flex items-center justify-center text-sm font-medium">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="w-16 h-1 bg-[#0d5c2f]"></div>
                            <div class="w-8 h-8 bg-[#0d5c2f] text-white rounded-full flex items-center justify-center text-sm font-medium">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="w-16 h-1 bg-[#0d5c2f]"></div>
                            <div class="w-8 h-8 bg-[#0d5c2f] text-white rounded-full flex items-center justify-center text-sm font-medium">
                                <i class="fas fa-upload"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-2 text-sm text-gray-600">
                        <span class="mr-8">Basic Info</span>
                        <span class="mr-8">Date & Time</span>
                        <span>Requirements</span>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-lg text-[#0d5c2f] mb-3">Booking Summary</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Date:</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($bookingData['booking_date'])->format('F j, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Time:</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($bookingData['booking_time'])->format('g:i A') }}</span>
                        </div>
                        @if(isset($bookingData['special_requests']) && !empty($bookingData['special_requests']))
                            <div class="md:col-span-2">
                                <span class="text-gray-600">Special Requests:</span>
                                <span class="font-medium text-gray-900">{{ $bookingData['special_requests'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Requirements Upload Form -->
            <div class="p-6">
                <form action="{{ route('booking.step4') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    
                    <!-- Requirements Section -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Required Documents</h3>
                        
                        @if($service->requirements)
                            <div class="space-y-4">
                                @php
                                    $requirements = explode("\n• ", $service->requirements);
                                    // Remove the first "• " if it exists
                                    if (strpos($requirements[0], '• ') === 0) {
                                        $requirements[0] = substr($requirements[0], 2);
                                    }
                                @endphp
                                
                                @foreach($requirements as $requirement)
                                    @if(!empty(trim($requirement)))
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    {{ trim($requirement) }}
                                                </label>
                                                <span class="text-xs text-red-600">*Required</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <input type="file" 
                                                       name="requirements[{{ trim($requirement) }}]" 
                                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                                       required>
                                                <button type="button" class="text-[#0d5c2f] hover:text-[#0d5c2f]/80 text-sm font-medium">
                                                    <i class="fas fa-eye mr-1"></i>Preview
                                                </button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max 10MB)
                                            </p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-info-circle text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Requirements</h3>
                                <p class="text-gray-600">This service doesn't require any additional documents.</p>
                            </div>
                        @endif
                    </div>

                    <!-- File Upload Guidelines -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-lg mb-2 text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>Upload Guidelines
                        </h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Accepted file formats: PDF, JPG, PNG, DOC, DOCX</li>
                            <li>• Maximum file size: 10MB per file</li>
                            <li>• Ensure documents are clear and legible</li>
                            <li>• All required documents must be uploaded to proceed</li>
                            <li>• You can preview uploaded files before submission</li>
                        </ul>
                    </div>

                    <!-- Error Messages -->
                    @error('requirements.*')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <a href="{{ route('booking.step2') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </a>
                        <button type="submit" 
                                class="bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Submit Booking <i class="fas fa-check ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File preview functionality
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            const previewBtn = this.parentElement.querySelector('button');
            
            if (file) {
                // Update preview button
                previewBtn.innerHTML = '<i class="fas fa-eye mr-1"></i>Preview';
                previewBtn.onclick = function() {
                    if (file.type.startsWith('image/')) {
                        // Show image preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const preview = window.open('', '_blank');
                            preview.document.write(`
                                <html>
                                    <head><title>File Preview</title></head>
                                    <body style="margin:0;padding:20px;text-align:center;">
                                        <img src="${e.target.result}" style="max-width:100%;max-height:80vh;">
                                    </body>
                                </html>
                            `);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // For non-image files, show file info
                        alert(`File: ${file.name}\nSize: ${(file.size / 1024 / 1024).toFixed(2)} MB\nType: ${file.type}`);
                    }
                };
            }
        });
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFiles = document.querySelectorAll('input[type="file"][required]');
        let hasFiles = true;
        
        requiredFiles.forEach(input => {
            if (!input.files || input.files.length === 0) {
                hasFiles = false;
                input.classList.add('border-red-500');
            } else {
                input.classList.remove('border-red-500');
            }
        });
        
        if (!hasFiles) {
            e.preventDefault();
            alert('Please upload all required documents before submitting.');
        }
    });
});
</script>
@endsection 