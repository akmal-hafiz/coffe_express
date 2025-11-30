@props(['status'])

@php
    $classes = match (strtolower($status)) {
        'completed', 'delivered', 'ready' => 'bg-forest-500/10 text-forest-600 border-forest-500/20',
        'processing', 'preparing', 'brewing' => 'bg-coffee-500/10 text-coffee-600 border-coffee-500/20',
        'pending', 'waiting' => 'bg-yellow-500/10 text-yellow-600 border-yellow-500/20',
        'cancelled', 'failed' => 'bg-red-500/10 text-red-600 border-red-500/20',
        default => 'bg-gray-100 text-gray-600 border-gray-200',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border ' . $classes]) }}>
    <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5 animate-pulse"></span>
    {{ ucfirst($status) }}
</span>
