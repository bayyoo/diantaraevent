@extends('admin.layout')

@section('title', 'Partner Detail')
@section('page-title', 'Partner Detail')
@section('page-subtitle', 'Detail informasi partner Diantara Nexus')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $partner->name }}</h3>
            <p class="text-sm text-gray-500">Joined {{ $partner->created_at->format('M d, Y') }}</p>
        </div>
        <div>
            @if($partner->status === 'verified')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>
            @elseif($partner->status === 'pending')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
            @endif
        </div>
    </div>

    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Contact Information</h4>
            <dl class="space-y-2 text-sm text-gray-700">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Name</dt>
                    <dd class="font-medium">{{ $partner->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium">{{ $partner->email }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Phone</dt>
                    <dd class="font-medium">{{ $partner->phone }}</dd>
                </div>
            </dl>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Organization</h4>
            @if($partner->organizations->count() > 0)
                @php($org = $partner->organizations->first())
                <dl class="space-y-2 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Name</dt>
                        <dd class="font-medium">{{ $org->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Type</dt>
                        <dd class="font-medium">{{ $org->type }}</dd>
                    </div>
                    @if(!empty($org->website))
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Website</dt>
                        <dd class="font-medium">
                            <a href="{{ $org->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $org->website }}</a>
                        </dd>
                    </div>
                    @endif
                </dl>
            @else
                <p class="text-sm text-gray-400">No organization data</p>
            @endif
        </div>
    </div>

    <div class="px-6 pb-6 flex items-center justify-between border-t border-gray-100 pt-4">
        <a href="{{ route('admin.partners.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back to list</a>

        @if($partner->status === 'pending')
        <div class="flex items-center space-x-3">
            <form method="POST" action="{{ route('admin.partners.verify', $partner) }}" class="inline">
                @csrf
                @method('PUT')
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md">Verify</button>
            </form>
            <form method="POST" action="{{ route('admin.partners.reject', $partner) }}" class="inline">
                @csrf
                @method('PUT')
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">Reject</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
