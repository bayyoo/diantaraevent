@extends('admin.layout')

@section('title', 'User Details')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Name</label>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Full Name</label>
                    <p class="text-gray-900">{{ $user->full_name ?? 'Not provided' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Email</label>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Phone</label>
                    <p class="text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Information</h3>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Address</label>
                    <p class="text-gray-900">{{ $user->address ?? 'Not provided' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Education</label>
                    <p class="text-gray-900">{{ $user->last_education ?? 'Not provided' }}</p>
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
                    <label class="block text-sm font-medium text-gray-600">Email Status</label>
                    @if($user->email_verified_at)
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Verified</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Pending</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Account Information -->
    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Created At</label>
                <p class="text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                <p class="text-gray-900">{{ $user->updated_at->format('d M Y, H:i') }}</p>
            </div>
            
            @if($user->email_verified_at)
            <div>
                <label class="block text-sm font-medium text-gray-600">Email Verified At</label>
                <p class="text-gray-900">{{ $user->email_verified_at->format('d M Y, H:i') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex flex-wrap gap-2">
        @if(!$user->email_verified_at)
            <form method="POST" action="{{ route('admin.users.verify-email', $user) }}" class="inline-block">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-check-circle mr-2"></i>Verify Email
                </button>
            </form>
        @endif
        
        @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline-block">
                @csrf
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-plus' }} mr-2"></i>
                    {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                </button>
            </form>
            
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Delete User
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
