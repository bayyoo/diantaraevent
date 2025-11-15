<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Public: List all published blog posts
    public function index(Request $request)
    {
        $query = BlogPost::with('author')->published();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')->paginate(9);
        $categories = BlogPost::published()
                              ->select('category')
                              ->distinct()
                              ->pluck('category');

        // Get upcoming events for "Exciting Events" section
        $upcomingEvents = \App\Models\Event::where('event_date', '>=', now())
                                           ->orderBy('event_date', 'asc')
                                           ->take(4)
                                           ->get();

        return view('blog.index', compact('posts', 'categories', 'upcomingEvents'));
    }

    // Public: Show single blog post
    public function show($slug)
    {
        $post = BlogPost::with('author')
                        ->where('slug', $slug)
                        ->published()
                        ->firstOrFail();

        // Increment views
        $post->increment('views');

        // Get related posts
        $relatedPosts = BlogPost::published()
                                ->where('category', $post->category)
                                ->where('id', '!=', $post->id)
                                ->latest('published_at')
                                ->take(3)
                                ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
