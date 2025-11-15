@extends('admin.layout')

@section('title', 'Blog Management')
@section('page-title', 'Blog Posts')
@section('page-subtitle', 'Manage all blog posts')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">All Blog Posts</h3>
            <a href="#" class="px-4 py-2 bg-nexus text-white rounded-lg hover:bg-nexus-dark transition-colors">
                <i class="fas fa-plus mr-2"></i>New Post
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-10 h-10 rounded object-cover mr-3">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ Str::limit($post->title, 50) }}</p>
                                <p class="text-sm text-gray-500">{{ Str::limit($post->excerpt, 60) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                            {{ ucfirst($post->category) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $post->author->name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <i class="fas fa-eye text-gray-400 mr-1"></i>{{ number_format($post->views) }}
                    </td>
                    <td class="px-6 py-4">
                        @if($post->is_published)
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Published</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Draft</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-nexus hover:text-nexus-dark">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="text-gray-600 hover:text-gray-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                        <p>No blog posts yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($posts->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection
