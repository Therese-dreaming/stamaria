@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold font-poppins text-gray-900">Service Management</h1>
            <p class="text-gray-600 mt-1">Manage church services and ceremonies</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Add New Service
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-6" x-data="{
        activeTab: localStorage.getItem('adminServiceViewTab') || 'table',
        setTab(tab) {
            this.activeTab = tab;
            localStorage.setItem('adminServiceViewTab', tab);
        }
    }">
        <!-- Search and Filter -->
        <div class="flex flex-col sm:flex-row justify-between mb-6 space-y-4 sm:space-y-0">
            <div class="relative w-full sm:w-64">
                <input type="text" placeholder="Search services..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option>All Types</option>
                    <option>Sacraments</option>
                    <option>Ceremonies</option>
                    <option>Counseling</option>
                </select>
                <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option>Sort by</option>
                    <option>Name</option>
                    <option>Newest</option>
                    <option>Price</option>
                </select>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <ul class="flex flex-wrap -mb-px">
                <li class="mr-2">
                    <button @click="setTab('table')" 
                        :class="{'border-primary text-primary': activeTab === 'table', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'table'}"
                        class="inline-block py-3 px-4 font-medium text-sm border-b-2 focus:outline-none">
                        <i class="fas fa-table mr-2"></i> Table View
                    </button>
                </li>
                <li class="mr-2">
                    <button @click="setTab('cards')" 
                        :class="{'border-primary text-primary': activeTab === 'cards', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'cards'}"
                        class="inline-block py-3 px-4 font-medium text-sm border-b-2 focus:outline-none">
                        <i class="fas fa-th-large mr-2"></i> Card View
                    </button>
                </li>
            </ul>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Types & Pricing</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($services as $service)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $service->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                        <i class="{{ $service->icon ?? 'fas fa-concierge-bell' }} text-primary"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                        @if(isset($service->type))
                                        <div class="text-xs text-gray-500">{{ $service->type }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 max-w-xs">{{ $service->description }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if(!empty($service->types) && count($service->types) > 0)
                                    <div class="space-y-1">
                                    @foreach($service->types as $type)
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-800">{{ $type['name'] }}</span>
                                            @if(isset($type['price']) && $type['price'] > 0)
                                                <span class="text-primary font-semibold">₱{{ number_format($type['price'], 2) }}</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Free</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    </div>
                                @else
                                    @if(isset($service->price))
                                        @if($service->price > 0)
                                            <span class="font-medium text-gray-900">{{ $service->formatted_price ?? ('₱' . number_format($service->price, 2)) }}</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Free</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Not set</span>
                                    @endif
                                @endif
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $services->links() }}
            </div>
        </div>
        
        <!-- Card View -->
        <div x-show="activeTab === 'cards'" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <div class="p-5">
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 bg-primary/10 rounded-lg flex items-center justify-center mr-4">
                                <i class="{{ $service->icon ?? 'fas fa-concierge-bell' }} text-primary text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $service->name }}</h3>
                                @if(isset($service->type))
                                <p class="text-gray-500 text-sm">{{ $service->type }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-4">{{ $service->description }}</p>
                        
                        <div class="mb-4">
                            @if(!empty($service->types) && count($service->types) > 0)
                                <div class="space-y-2">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Service Types:</h4>
                                    @foreach($service->types as $type)
                                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                            <span class="font-medium text-gray-800 text-sm">{{ $type['name'] }}</span>
                                            @if(isset($type['price']) && $type['price'] > 0)
                                                <span class="text-primary font-semibold">₱{{ number_format($type['price'], 2) }}</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Free</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex justify-between items-center">
                                    @if(isset($service->price))
                                        @if($service->price > 0)
                                            <span class="text-lg font-bold text-primary">{{ $service->formatted_price ?? ('₱' . number_format($service->price, 2)) }}</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Free</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Price not set</span>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="flex justify-end mt-2">
                                <span class="text-sm text-gray-500">{{ $service->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            <span class="text-xs text-gray-500">ID: {{ $service->id }}</span>
                            <div class="flex space-x-2">
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
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $services->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Any additional JavaScript can go here
</script>
@endsection