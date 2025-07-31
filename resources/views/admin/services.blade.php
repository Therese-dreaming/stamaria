@extends('layouts.admin')

@section('title', 'Services Management')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Services Management</h1>
            <p class="text-gray-600 mt-2">Manage church services and their configurations</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Service
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'table' }">
    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Services</label>
                <div class="relative">
                    <input type="text" id="search" name="search" 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Search by name, description...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="sm:w-48">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Price Filter -->
            <div class="sm:w-48">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                <select id="price" name="price" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">All Prices</option>
                    <option value="free">Free</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Services</h2>
            <div class="flex space-x-2">
                <button @click="activeTab = 'table'" 
                        :class="activeTab === 'table' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-table mr-1"></i>Table
                </button>
                <button @click="activeTab = 'grid'" 
                        :class="activeTab === 'grid' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-th mr-1"></i>Grid
                </button>
            </div>
        </div>

        <!-- Table View -->
        <div x-show="activeTab === 'table'" x-cloak>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price & Duration</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slots</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($services as $service)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $service->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                        <i class="{{ $service->icon ?? 'fas fa-concierge-bell' }} text-primary"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 max-w-xs">{{ $service->description }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="space-y-1">
                                    @if(isset($service->price))
                                        @if($service->price > 0)
                                            <div class="font-medium text-gray-900">{{ $service->formatted_price ?? ('₱' . number_format($service->price, 2)) }}</div>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Free</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Not set</span>
                                    @endif
                                    
                                    @if($service->formatted_duration)
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>{{ $service->formatted_duration }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-users mr-1"></i>{{ $service->slots ?? 1 }} slot{{ $service->slots != 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $service->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.services.show', $service->id) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.services.edit', $service->id) }}" class="text-amber-600 hover:text-amber-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-box-open text-5xl mb-4"></i>
                                    <p class="text-lg font-semibold">No services found</p>
                                    <p class="text-sm text-gray-500 mb-4">Get started by adding a new service.</p>
                                    <a href="{{ route('admin.services.create') }}" class="inline-block bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg mt-2">
                                        <i class="fas fa-plus mr-2"></i> Add Service
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $services->links() }}
            </div>
        </div>

        <!-- Grid View -->
        <div x-show="activeTab === 'grid'" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($services as $service)
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mr-3">
                                <i class="{{ $service->icon ?? 'fas fa-concierge-bell' }} text-primary text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $service->name }}</h3>
                                <p class="text-sm text-gray-500">ID: {{ $service->id }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-1">
                            <a href="{{ route('admin.services.show', $service->id) }}" class="text-blue-600 hover:text-blue-800 p-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.services.edit', $service->id) }}" class="text-amber-600 hover:text-amber-800 p-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $service->description }}</p>
                    
                    <div class="space-y-2">
                        @if(isset($service->price))
                            @if($service->price > 0)
                                <div class="text-lg font-bold text-primary">{{ $service->formatted_price ?? ('₱' . number_format($service->price, 2)) }}</div>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Free</span>
                            @endif
                        @else
                            <span class="text-gray-400 text-sm">Price not set</span>
                        @endif
                        
                        @if($service->formatted_duration)
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i>{{ $service->formatted_duration }}
                            </div>
                        @endif
                        
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-users mr-1"></i>{{ $service->slots ?? 1 }} slot{{ $service->slots != 1 ? 's' : '' }} available
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="text-xs text-gray-500">
                            Created: {{ $service->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-box-open text-5xl mb-4"></i>
                        <p class="text-lg font-semibold">No services found</p>
                        <p class="text-sm text-gray-500 mb-4">Get started by adding a new service.</p>
                        <a href="{{ route('admin.services.create') }}" class="inline-block bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg mt-2">
                            <i class="fas fa-plus mr-2"></i> Add Service
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
            <div class="mt-6">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</div>
@endsection