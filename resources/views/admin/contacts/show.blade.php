@extends('layouts.admin')

@section('title', 'View Message')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-coffee/5 to-brown/5 flex justify-between items-center">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i data-feather="mail" class="w-5 h-5"></i>
                Message Details
            </h2>
            <a href="{{ route('admin.contacts.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                Back to Messages
            </a>
        </div>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <p class="text-gray-900 font-semibold">{{ $contact->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-gray-900">
                        <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:underline">{{ $contact->email }}</a>
                    </p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <p class="text-gray-900 font-semibold">{{ $contact->subject ?? 'No subject' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $contact->message }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    Received: {{ $contact->created_at->format('d M Y, H:i') }}
                </div>
                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                        Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelector('.delete-form').addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this message?')) {
            this.submit();
        }
    });
</script>
@endsection
