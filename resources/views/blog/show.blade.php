<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - Blog Diantara</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: '#7681FF',
                        'primary-dark': '#5A67D8',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
        .prose {
            max-width: 65ch;
        }
        .prose p {
            margin-bottom: 1.25em;
            line-height: 1.75;
        }
        .prose h2 {
            font-size: 1.5em;
            font-weight: 700;
            margin-top: 2em;
            margin-bottom: 1em;
        }
        .prose h3 {
            font-size: 1.25em;
            font-weight: 600;
            margin-top: 1.6em;
            margin-bottom: 0.6em;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('components.navigation')

    <!-- Article Header -->
    <article class="pt-24 pb-16">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-primary">Blog</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li class="text-gray-900">{{ Str::limit($post->title, 50) }}</li>
                </ol>
            </nav>

            <!-- Category Badge -->
            <div class="mb-4">
                <a href="{{ route('blog.index', ['category' => $post->category]) }}" 
                   class="inline-block bg-primary/10 text-primary px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary/20 transition-colors">
                    {{ ucfirst($post->category) }}
                </a>
            </div>

            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                {{ $post->title }}
            </h1>

            <!-- Meta Info -->
            <div class="flex items-center space-x-6 text-gray-600 mb-8 pb-8 border-b">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-semibold mr-3">
                        {{ strtoupper(substr($post->author->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">{{ $post->author->name }}</div>
                        <div class="text-sm text-gray-500">Author</div>
                    </div>
                </div>
                <div class="flex items-center space-x-4 text-sm">
                    <span><i class="fas fa-calendar mr-2"></i>{{ $post->published_at->format('d M Y') }}</span>
                    <span><i class="fas fa-clock mr-2"></i>{{ $post->reading_time }} min read</span>
                    <span><i class="fas fa-eye mr-2"></i>{{ $post->views }} views</span>
                </div>
            </div>

            <!-- Featured Image -->
            @if($post->featured_image)
            <div class="mb-12 rounded-xl overflow-hidden">
                <img src="{{ asset('storage/' . $post->featured_image) }}" 
                     alt="{{ $post->title }}" 
                     class="w-full h-auto">
            </div>
            @endif

            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                {!! $post->content !!}
            </div>

            <!-- Share Buttons -->
            <div class="mt-12 pt-8 border-t">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Share this article</h3>
                <div class="flex space-x-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" 
                       target="_blank"
                       class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" 
                       target="_blank"
                       class="flex items-center justify-center w-10 h-10 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('blog.show', $post->slug)) }}" 
                       target="_blank"
                       class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <button onclick="copyLink()" 
                            class="flex items-center justify-center w-10 h-10 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>
    </article>

    <!-- Related Posts -->
    @if($relatedPosts->count() > 0)
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Artikel Terkait</h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $related)
                <a href="{{ route('blog.show', $related->slug) }}" 
                   class="block bg-white rounded-lg overflow-hidden border border-gray-200 hover:border-primary transition-colors">
                    @if($related->featured_image)
                        <img src="{{ asset('storage/' . $related->featured_image) }}" 
                             alt="{{ $related->title }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=400&h=250&fit=crop" 
                             alt="{{ $related->title }}" 
                             class="w-full h-48 object-cover">
                    @endif
                    <div class="p-5">
                        <span class="text-xs text-primary font-medium">{{ ucfirst($related->category) }}</span>
                        <h3 class="font-bold text-gray-900 mt-1 mb-2 line-clamp-2">
                            {{ $related->title }}
                        </h3>
                        <div class="text-xs text-gray-500">
                            {{ $related->published_at->format('d M Y') }}
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('components.footer')

    <script>
        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert('Link copied to clipboard!');
            });
        }
    </script>
</body>
</html>
