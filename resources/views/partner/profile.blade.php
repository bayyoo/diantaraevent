@extends('partner.layout')

@section('title', 'Profile')
@section('page-title', 'Profile Settings')
@section('page-subtitle', 'Manage your partner account information')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg alert-dismissible">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <div class="text-center">
                    <!-- Avatar -->
                    <div class="w-24 h-24 mx-auto mb-4 nexus-gradient rounded-full flex items-center justify-center">
                        <span class="text-3xl font-bold text-white">
                            {{ substr($partner->name, 0, 2) }}
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $partner->name }}</h3>
                    <p class="text-gray-600 mb-2">{{ $partner->organization_name }}</p>
                    
                    <!-- Status Badge -->
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($partner->status === 'verified') bg-green-100 text-green-800
                        @elseif($partner->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        <i class="fas fa-circle text-xs mr-2"></i>
                        {{ ucfirst($partner->status) }}
                    </span>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-envelope w-5 mr-3"></i>
                        <span class="truncate">{{ $partner->email }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-phone w-5 mr-3"></i>
                        <span>{{ $partner->phone }}</span>
                    </div>
                    <div class="flex items-start text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-5 mr-3 mt-0.5"></i>
                        <span class="flex-1">{{ $partner->address }}</span>
                    </div>
                    @if($partner->verified_at)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle w-5 mr-3"></i>
                            <span>Verified {{ $partner->verified_at->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Stats -->
            <div class="mt-6 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <h4 class="font-semibold text-gray-900 mb-4">Account Statistics</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Member Since</span>
                        <span class="text-sm font-medium text-gray-900">{{ $partner->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Events</span>
                        <span class="text-sm font-medium text-gray-900">{{ $partner->events()->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Active Events</span>
                        <span class="text-sm font-medium text-gray-900">{{ $partner->events()->where('status', 'published')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Edit Profile Information</h3>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('diantaranexus.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $partner->name) }}"
                                   required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $partner->phone) }}"
                                   required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('phone') border-red-500 @enderror">
                        </div>
                    </div>

                    <!-- Organization Name -->
                    <div class="mt-6">
                        <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-2">Organization/Company Name</label>
                        <input type="text" 
                               id="organization_name" 
                               name="organization_name" 
                               value="{{ old('organization_name', $partner->organization_name) }}"
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('organization_name') border-red-500 @enderror">
                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Complete Address</label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3" 
                                  required 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all @error('address') border-red-500 @enderror">{{ old('address', $partner->address) }}</textarea>
                    </div>

                    <!-- Email (Read-only) -->
                    <div class="mt-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" 
                               id="email" 
                               value="{{ $partner->email }}"
                               readonly 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Email cannot be changed. Contact support if you need to update your email.</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                class="nexus-gradient text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition-all transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="mt-6 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Change Password</h3>
                
                <form method="POST" action="#" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nexus focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition-all">
                            <i class="fas fa-key mr-2"></i>
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
