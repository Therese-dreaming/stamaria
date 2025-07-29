@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold font-poppins text-gray-900">Edit User</h1>
            <p class="text-gray-600 mt-1">Update user information and permissions</p>
        </div>
        <a href="{{ route('admin.users') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Users
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Error</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- User Avatar Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-primary text-white font-semibold text-lg">
                        Profile Picture
                    </div>
                    <div class="p-6 flex flex-col items-center">
                        <div class="w-32 h-32 mb-4 relative">
                            <img id="avatar-preview" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200" 
                                src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D5C2F&color=fff&size=256' }}" 
                                alt="{{ $user->name }}">
                            <button type="button" id="change-avatar-btn" class="absolute bottom-0 right-0 bg-primary text-white rounded-full p-2 shadow-md hover:bg-primary-dark">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*">
                        <p class="text-sm text-gray-500 text-center mt-2">Click the camera icon to change profile picture</p>
                    </div>
                </div>
                
                <!-- User Status Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-primary text-white font-semibold text-lg">
                        Account Status
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-gray-700">Status</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $user->is_active ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                <span class="ml-2 text-sm font-medium text-gray-700">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-gray-700">Email Verified</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Member Since</span>
                            <span class="text-sm text-gray-600">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- User Role Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-primary text-white font-semibold text-lg">
                        Role & Permissions
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">User Role</label>
                            <select name="role" id="role" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
                            
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions[]" value="manage_users" 
                                    {{ in_array('manage_users', $user->permissions ?? []) ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Manage Users</span>
                            </label>
                            
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions[]" value="manage_services" 
                                    {{ in_array('manage_services', $user->permissions ?? []) ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Manage Services</span>
                            </label>
                            
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions[]" value="manage_bookings" 
                                    {{ in_array('manage_bookings', $user->permissions ?? []) ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Manage Bookings</span>
                            </label>
                            
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions[]" value="manage_content" 
                                    {{ in_array('manage_content', $user->permissions ?? []) ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Manage Content</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Information Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-primary text-white font-semibold text-lg">
                    Personal Information
                </div>
                <div class="p-6 space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    
                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users') }}" 
                   class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
            </div>
        </form>
    </div>
@endsection