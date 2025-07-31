@extends('layouts.admin')

@section('title', $priest->name)

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $priest->name }}</h1>
            <p class="text-gray-600 mt-2">Priest Details</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.priests.edit', $priest->id) }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Priest
            </a>
            <a href="{{ route('admin.priests.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Priests
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Priest Profile -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-start space-x-6">
            <div class="flex-shrink-0">
                <img class="h-32 w-32 rounded-full object-cover" src="{{ $priest->image_url }}" alt="{{ $priest->name }}">
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $priest->name }}</h2>
                        <p class="text-lg text-gray-600">{{ $priest->title ?? 'Priest' }}</p>
                    </div>
                    <div class="text-right">
                        @if($priest->status === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i>Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-2"></i>Inactive
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($priest->email)
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">{{ $priest->email }}</span>
                        </div>
                    @endif
                    
                    @if($priest->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">{{ $priest->phone }}</span>
                        </div>
                    @endif
                    
                    @if($priest->ordination_date)
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">Ordained: {{ $priest->ordination_date->format('F d, Y') }}</span>
                        </div>
                    @endif
                    
                    @if($priest->assignment_date)
                        <div class="flex items-center">
                            <i class="fas fa-church text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">Assigned: {{ $priest->assignment_date->format('F d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Biography -->
    @if($priest->bio)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Biography</h3>
        <div class="prose max-w-none">
            <p class="text-gray-700 leading-relaxed">{{ $priest->bio }}</p>
        </div>
    </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Days Since Assignment</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        @if($priest->assignment_date)
                            {{ $priest->assignment_date->diffInDays(now()) }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cross text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Years Since Ordination</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        @if($priest->ordination_date)
                            {{ $priest->ordination_date->diffInYears(now()) }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tie text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <p class="text-2xl font-semibold text-gray-900 capitalize">{{ $priest->status }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
        <div class="flex space-x-4">
            <a href="{{ route('admin.priests.edit', $priest->id) }}" 
               class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Priest
            </a>
            <form action="{{ route('admin.priests.destroy', $priest->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this priest?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete Priest
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 