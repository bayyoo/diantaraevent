@extends('admin.layout')

@section('title', 'Edit User')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>Back to Users
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">Basic Information</h3>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Additional Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">Additional Information</h3>
                
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea id="address" name="address" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="last_education" class="block text-sm font-medium text-gray-700 mb-1">Education</label>
                    <select id="last_education" name="last_education" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Education</option>
                        <option value="SD" {{ old('last_education', $user->last_education) == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ old('last_education', $user->last_education) == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA/SMK" {{ old('last_education', $user->last_education) == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                        <option value="D3" {{ old('last_education', $user->last_education) == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="S1" {{ old('last_education', $user->last_education) == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('last_education', $user->last_education) == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('last_education', $user->last_education) == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                    @error('last_education')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Admin Status -->
                @if($user->id !== auth()->id())
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_admin" value="1" 
                               {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Admin User</span>
                    </label>
                    @error('is_admin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>
        </div>
        
        <!-- Current Status -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Current Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Email Status</label>
                    @if($user->email_verified_at)
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Verified</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Pending</span>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Role</label>
                    @if($user->is_admin)
                        <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded">Admin</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">User</span>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Member Since</label>
                    <span class="text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="mt-6 flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i>Update User
            </button>
            
            <a href="{{ route('admin.users.show', $user) }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
