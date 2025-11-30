@props(['label', 'id'])

<div class="relative">
    <input
        id="{{ $id }}"
        placeholder=" "
        {{ $attributes->merge(['class' => 'block px-4 pb-2.5 pt-5 w-full text-base text-coffee-900 bg-white/40 backdrop-blur-sm rounded-xl border border-coffee-200 appearance-none focus:outline-none focus:ring-2 focus:ring-coffee-400 focus:border-transparent peer transition-all duration-300']) }}
    />
    <label
        for="{{ $id }}"
        class="absolute text-sm text-coffee-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-coffee-700 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 cursor-text font-medium"
    >
        {{ $label }}
    </label>
</div>
