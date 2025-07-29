@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-800">User Management</h1>
        <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Add User
        </button>
    </div>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-6" x-data="{
        activeTab: localStorage.getItem('userViewTab') || 'table',
        setTab(tab) {
            this.activeTab = tab;
            localStorage.setItem('userViewTab', tab);
        }
    }">
        <!-- Search and Filter -->
        <div class="flex flex-col sm:flex-row justify-between mb-6 space-y-4 sm:space-y-0">
            <div class="relative w-full sm:w-64">
                <input type="text" placeholder="Search users..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option>All Roles</option>
                    <option>Admin</option>
                    <option>User</option>
                </select>
                <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option>Sort by</option>
                    <option>Name</option>
                    <option>Email</option>
                    <option>Newest</option>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D5C2F&color=fff" alt="{{ $user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $user->role ?? 'User' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button class="text-primary hover:text-primary-dark">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
        
        <!-- Card View -->
        <div x-show="activeTab === 'cards'" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($users as $user)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <div class="p-5">
                        <div class="flex items-center mb-4">
                            <img class="h-12 w-12 rounded-full object-cover mr-4" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D5C2F&color=fff" alt="{{ $user->name }}">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
                                <p class="text-gray-600 text-sm">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $user->role ?? 'User' }}
                            </span>
                            <div class="flex space-x-2">
                                <button class="text-primary hover:text-primary-dark p-1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 p-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection