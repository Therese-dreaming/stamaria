@extends('layouts.admin')

@section('title', 'Priests Management')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Priests Management</h1>
            <p class="text-gray-600 mt-2">Manage parish priests and their information</p>
        </div>
        <a href="{{ route('admin.priests.create') }}" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Priest
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
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Priests</label>
                <div class="relative">
                    <input type="text" id="search" name="search" 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Search by name, title, email...">
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
        </div>
    </div>

    <!-- View Toggle -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Priests</h2>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priest</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment Date</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($priests as $priest)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $priest->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <img class="h-12 w-12 rounded-full object-cover" src="{{ $priest->image_url }}" alt="{{ $priest->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $priest->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $priest->title ?? 'Priest' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($priest->email)
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                            {{ $priest->email }}
                                        </div>
                                    @endif
                                    @if($priest->phone)
                                        <div class="flex items-center mt-1">
                                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                                            {{ $priest->phone }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($priest->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($priest->assignment_date)
                                    {{ $priest->assignment_date->format('M d, Y') }}
                                @else
                                    <span class="text-gray-400">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.priests.show', $priest->id) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.priests.edit', $priest->id) }}" class="text-amber-600 hover:text-amber-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.priests.destroy', $priest->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this priest?');" class="inline">
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
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-user-tie text-5xl mb-4"></i>
                                    <p class="text-lg font-semibold">No priests found</p>
                                    <p class="text-sm text-gray-500 mb-4">Get started by adding a new priest.</p>
                                    <a href="{{ route('admin.priests.create') }}" class="inline-block bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg mt-2">
                                        <i class="fas fa-plus mr-2"></i> Add Priest
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $priests->links() }}
            </div>
        </div>

        <!-- Grid View -->
        <div x-show="activeTab === 'grid'" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($priests as $priest)
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <img class="h-16 w-16 rounded-full object-cover mr-4" src="{{ $priest->image_url }}" alt="{{ $priest->name }}">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $priest->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $priest->title ?? 'Priest' }}</p>
                                <p class="text-xs text-gray-400">ID: {{ $priest->id }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-1">
                            <a href="{{ route('admin.priests.show', $priest->id) }}" class="text-blue-600 hover:text-blue-800 p-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.priests.edit', $priest->id) }}" class="text-amber-600 hover:text-amber-800 p-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.priests.destroy', $priest->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this priest?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    @if($priest->bio)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($priest->bio, 100) }}</p>
                    @endif
                    
                    <div class="space-y-2">
                        @if($priest->email)
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-envelope mr-1"></i>{{ Str::limit($priest->email, 25) }}
                            </div>
                        @endif
                        
                        @if($priest->phone)
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-phone mr-1"></i>{{ $priest->phone }}
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            @if($priest->status === 'active')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Inactive
                                </span>
                            @endif
                            
                            @if($priest->assignment_date)
                                <div class="text-xs text-gray-500">
                                    Assigned: {{ $priest->assignment_date->format('M d, Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-user-tie text-5xl mb-4"></i>
                        <p class="text-lg font-semibold">No priests found</p>
                        <p class="text-sm text-gray-500 mb-4">Get started by adding a new priest.</p>
                        <a href="{{ route('admin.priests.create') }}" class="inline-block bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg mt-2">
                            <i class="fas fa-plus mr-2"></i> Add Priest
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
            <div class="mt-6">
                {{ $priests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 