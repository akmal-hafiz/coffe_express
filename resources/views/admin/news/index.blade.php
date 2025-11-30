@extends('layouts.admin')

@section('title', 'Manage News')

@section('content')
<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-coffee/5 to-brown/5 flex justify-between items-center">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i data-feather="file-text" class="w-5 h-5"></i>
            All News
        </h2>
        <a href="{{ route('admin.news.create') }}" class="px-4 py-2 bg-coffee text-white rounded-lg hover:bg-coffee/90 transition flex items-center gap-2">
            <i data-feather="plus" class="w-4 h-4"></i>
            Add News
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Published At</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($news as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-16 w-24 object-cover rounded-lg">
                            @else
                                <div class="h-16 w-24 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                    <i data-feather="image" class="w-6 h-6"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->title }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($item->content, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->published_at ? $item->published_at->format('d M Y') : 'Draft' }}</div>
                            <div class="text-xs text-gray-500">{{ $item->author }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.news.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 inline-block">Edit</a>
                            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <p>No news found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200">
        {{ $news->links() }}
    </div>
</div>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this news?')) {
                this.submit();
            }
        });
    });
</script>
@endsection
