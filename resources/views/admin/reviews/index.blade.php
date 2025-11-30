<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Reviews — Coffee Express Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    </style>
</head>
<body class="bg-cream text-gray-800">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-gradient-to-r from-coffee to-brown text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-coffee shadow">
                    <i data-feather="coffee" class="w-6 h-6"></i>
                </span>
                <div>
                    <h1 class="text-xl font-extrabold">Coffee Express Admin</h1>
                    <p class="text-xs text-white/80">Review Management</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Dashboard</a>
                    <a href="{{ route('admin.menus.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Menu</a>
                    <a href="{{ route('admin.reviews.index') }}" class="px-3 py-2 rounded-lg bg-white/20 transition">Reviews</a>
                    <a href="{{ route('admin.loyalty.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Loyalty</a>
                    <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Reports</a>
                </nav>
                <div class="h-6 w-px bg-white/20 hidden md:block"></div>
                <span class="text-sm">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium hover:text-white/80 transition flex items-center gap-1">
                        <i data-feather="log-out" class="w-4 h-4"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Reviews</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i data-feather="message-square" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Approved</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i data-feather="check-circle" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i data-feather="clock" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Avg Rating</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['average_rating'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i data-feather="star" class="w-6 h-6 text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center gap-3">
                    <i data-feather="check-circle" class="text-green-600 w-5 h-5"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="User name, email, comment..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <!-- Rating Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <select name="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
                            <option value="">All Ratings</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end gap-3">
                        <button type="submit" class="px-6 py-2 bg-coffee text-white rounded-lg hover:bg-coffee/90 transition flex items-center gap-2">
                            <i data-feather="search" class="w-4 h-4"></i>
                            Filter
                        </button>
                        <a href="{{ route('admin.reviews.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                            <i data-feather="x" class="w-4 h-4"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        <form id="bulkForm" method="POST" class="mb-4">
            @csrf
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-coffee focus:ring-coffee">
                    Select All
                </label>
                <div class="flex gap-2">
                    <button type="button" onclick="bulkAction('approve')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm flex items-center gap-2">
                        <i data-feather="check" class="w-4 h-4"></i>
                        Approve Selected
                    </button>
                    <button type="button" onclick="bulkAction('reject')" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-sm flex items-center gap-2">
                        <i data-feather="x" class="w-4 h-4"></i>
                        Reject Selected
                    </button>
                    <button type="button" onclick="bulkAction('delete')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm flex items-center gap-2">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                        Delete Selected
                    </button>
                </div>
            </div>
        </form>

        <!-- Reviews Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-coffee/5 to-brown/5">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <i data-feather="message-square" class="w-5 h-5"></i>
                    All Reviews
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                <input type="checkbox" id="headerCheckbox" class="rounded border-gray-300 text-coffee focus:ring-coffee">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Order</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Rating</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Comment</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($reviews as $review)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-4">
                                    <input type="checkbox" name="review_ids[]" value="{{ $review->id }}" class="review-checkbox rounded border-gray-300 text-coffee focus:ring-coffee">
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-coffee to-brown flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $review->user->name ?? 'Unknown' }}</div>
                                            <div class="text-sm text-gray-500">{{ $review->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <a href="#" class="text-coffee hover:underline font-medium">#{{ $review->order_id }}</a>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i data-feather="star" class="w-4 h-4 fill-yellow-400 text-yellow-400"></i>
                                            @else
                                                <i data-feather="star" class="w-4 h-4 text-gray-300"></i>
                                            @endif
                                        @endfor
                                        <span class="ml-1 text-sm text-gray-600">({{ $review->rating }})</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="max-w-xs truncate text-gray-600">
                                        {{ $review->comment ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    @if($review->is_approved)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i data-feather="check" class="w-3 h-3 mr-1"></i>
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i data-feather="clock" class="w-3 h-3 mr-1"></i>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500">
                                    {{ $review->created_at->format('d M Y') }}<br>
                                    <span class="text-xs">{{ $review->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        @if(!$review->is_approved)
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Approve">
                                                    <i data-feather="check-circle" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Reject">
                                                    <i data-feather="x-circle" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                <i data-feather="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <i data-feather="message-square" class="w-16 h-16 mx-auto mb-2 text-gray-400"></i>
                                    <p class="text-lg font-medium">No reviews found</p>
                                    <p class="text-sm">Reviews will appear here once customers submit them</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reviews->links() }}
            </div>
        </div>
    </main>

    <script>
        feather.replace();

        // Select all functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const headerCheckbox = document.getElementById('headerCheckbox');
        const reviewCheckboxes = document.querySelectorAll('.review-checkbox');

        function toggleAll(checked) {
            reviewCheckboxes.forEach(cb => cb.checked = checked);
        }

        selectAllCheckbox?.addEventListener('change', function() {
            toggleAll(this.checked);
            if (headerCheckbox) headerCheckbox.checked = this.checked;
        });

        headerCheckbox?.addEventListener('change', function() {
            toggleAll(this.checked);
            if (selectAllCheckbox) selectAllCheckbox.checked = this.checked;
        });

        // Bulk actions
        function bulkAction(action) {
            const selectedIds = Array.from(reviewCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one review.'
                });
                return;
            }

            let url, title, text, confirmButtonColor;

            switch(action) {
                case 'approve':
                    url = '{{ route("admin.reviews.bulk-approve") }}';
                    title = 'Approve Reviews?';
                    text = `Are you sure you want to approve ${selectedIds.length} review(s)?`;
                    confirmButtonColor = '#22C55E';
                    break;
                case 'reject':
                    url = '{{ route("admin.reviews.bulk-reject") }}';
                    title = 'Reject Reviews?';
                    text = `Are you sure you want to reject ${selectedIds.length} review(s)?`;
                    confirmButtonColor = '#EAB308';
                    break;
                case 'delete':
                    url = '{{ route("admin.reviews.bulk-destroy") }}';
                    title = 'Delete Reviews?';
                    text = `Are you sure you want to delete ${selectedIds.length} review(s)? This cannot be undone.`;
                    confirmButtonColor = '#EF4444';
                    break;
            }

            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('bulkForm');
                    form.action = url;

                    // Add selected IDs to form
                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'review_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    form.submit();
                }
            });
        }

        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Review?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>
