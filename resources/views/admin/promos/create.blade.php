@extends('layouts.admin')

@section('title', 'Create Promo')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-coffee/5 to-brown/5">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i data-feather="plus-circle" class="w-5 h-5"></i>
            Create New Promo
        </h2>
    </div>

    <form action="{{ route('admin.promos.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
            <input type="file" name="image" id="image-upload" required accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent" onchange="previewImage(event)">
            <div id="image-preview" class="mt-4 hidden">
                <img id="preview" src="" alt="Preview" class="h-48 w-auto rounded-lg object-cover border-2 border-gray-200">
            </div>
            @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }} class="h-4 w-4 text-coffee focus:ring-coffee border-gray-300 rounded">
            <label for="active" class="ml-2 block text-sm text-gray-900">Active</label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.promos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-coffee text-white rounded-lg hover:bg-coffee/90 transition">Create Promo</button>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
