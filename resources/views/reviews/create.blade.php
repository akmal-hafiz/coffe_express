<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Write a Review — Coffee Express</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        coffee: '#6F4E37',
                        cream: '#F5EBDD',
                        brown: '#6B4F4F',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .star-rating input[type="radio"] { display: none; }
        .star-rating label { cursor: pointer; transition: all 0.2s; }
        .star-rating label:hover svg,
        .star-rating label:hover ~ label svg,
        .star-rating input[type="radio"]:checked ~ label svg {
            fill: #FBBF24;
            color: #FBBF24;
        }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; }
    </style>
</head>
<body class="bg-cream min-h-screen">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-coffee to-brown text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="text-2xl">☕</span>
                    <span class="text-xl font-bold">Coffee Express</span>
                </a>
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="hover:text-cream transition">Home</a>
                    <a href="{{ route('menu') }}" class="hover:text-cream transition">Menu</a>
                    <a href="{{ route('reviews.index') }}" class="hover:text-cream transition">Reviews</a>
                    <a href="{{ route('order.history') }}" class="hover:text-cream transition">My Orders</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <a href="{{ route('order.history') }}" class="inline-flex items-center gap-2 text-coffee hover:text-coffee/80 mb-6 transition">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Back to Order History
        </a>

        <!-- Review Form Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-coffee to-brown p-6 text-white">
                <h1 class="text-2xl font-bold mb-2">Write a Review</h1>
                <p class="text-white/80">Share your experience with Order #{{ $order->id }}</p>
            </div>

            <!-- Order Summary -->
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i data-feather="shopping-bag" class="w-5 h-5 text-coffee"></i>
                    Order Summary
                </h3>
                <div class="bg-cream/50 rounded-xl p-4">
                    <div class="space-y-2">
                        @foreach($order->items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $item['qty'] }}x {{ $item['name'] }}</span>
                                <span class="text-gray-700 font-medium">Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between">
                        <span class="font-semibold text-gray-700">Total</span>
                        <span class="font-bold text-coffee">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        Ordered on {{ $order->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <form action="{{ route('reviews.store', $order) }}" method="POST" class="p-6">
                @csrf

                <!-- Rating -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-3">
                        Your Rating <span class="text-red-500">*</span>
                    </label>
                    <div class="star-rating flex gap-2" id="starRating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                            <label for="star{{ $i }}" class="text-gray-300 hover:text-yellow-400" title="{{ $i }} stars">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </label>
                        @endfor
                    </div>
                    <div id="ratingText" class="mt-2 text-sm text-gray-500">Click to rate</div>
                    @error('rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div class="mb-6">
                    <label for="comment" class="block text-gray-700 font-semibold mb-3">
                        Your Review
                    </label>
                    <textarea
                        name="comment"
                        id="comment"
                        rows="5"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-coffee focus:border-transparent resize-none transition"
                        placeholder="Tell us about your experience... (optional)"
                        maxlength="1000"
                    >{{ old('comment') }}</textarea>
                    <div class="flex justify-between mt-1">
                        <span class="text-sm text-gray-500">Share details about the taste, service, or packaging</span>
                        <span class="text-sm text-gray-400"><span id="charCount">0</span>/1000</span>
                    </div>
                    @error('comment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guidelines -->
                <div class="bg-blue-50 rounded-xl p-4 mb-6">
                    <h4 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
                        <i data-feather="info" class="w-4 h-4"></i>
                        Review Guidelines
                    </h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Be honest and constructive in your feedback</li>
                        <li>• Focus on your personal experience</li>
                        <li>• Avoid inappropriate language or personal attacks</li>
                        <li>• Your review will be visible after approval</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-coffee to-brown text-white py-4 rounded-xl font-semibold hover:opacity-90 transition flex items-center justify-center gap-2">
                        <i data-feather="send" class="w-5 h-5"></i>
                        Submit Review
                    </button>
                    <a href="{{ route('order.history') }}" class="px-6 py-4 border-2 border-gray-300 rounded-xl font-semibold hover:bg-gray-50 transition flex items-center justify-center gap-2">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Encouragement -->
        <div class="mt-8 text-center text-gray-500">
            <p>Thank you for taking the time to share your feedback! ☕</p>
            <p class="text-sm mt-1">Your review helps other customers make better choices.</p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-coffee to-brown text-white mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-lg font-semibold mb-2">☕ Coffee Express</p>
            <p class="text-white/70">Bringing quality coffee to your doorstep</p>
            <p class="text-white/50 text-sm mt-4">© {{ date('Y') }} Coffee Express. All rights reserved.</p>
        </div>
    </footer>

    <script>
        feather.replace();

        // Character count for comment
        const commentInput = document.getElementById('comment');
        const charCount = document.getElementById('charCount');

        commentInput.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Initial count
        charCount.textContent = commentInput.value.length;

        // Rating text update
        const ratingInputs = document.querySelectorAll('input[name="rating"]');
        const ratingText = document.getElementById('ratingText');
        const ratingTexts = {
            5: '⭐ Excellent! You loved it!',
            4: '⭐ Very Good! Almost perfect!',
            3: '⭐ Good! It was okay.',
            2: '⭐ Fair. Could be better.',
            1: '⭐ Poor. Not satisfied.'
        };

        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                ratingText.textContent = ratingTexts[this.value];
                ratingText.classList.remove('text-gray-500');
                ratingText.classList.add('text-coffee', 'font-medium');
            });
        });

        // Check if there's a pre-selected rating
        const checkedRating = document.querySelector('input[name="rating"]:checked');
        if (checkedRating) {
            ratingText.textContent = ratingTexts[checkedRating.value];
            ratingText.classList.remove('text-gray-500');
            ratingText.classList.add('text-coffee', 'font-medium');
        }
    </script>
</body>
</html>
