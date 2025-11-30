{{-- Language Switcher Component --}}
{{-- Usage: <x-language-switcher /> or <x-language-switcher style="dropdown" /> --}}

@props(['style' => 'buttons'])

@if($style === 'dropdown')
    {{-- Dropdown Style --}}
    <div class="relative group">
        <button class="flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <span class="text-lg">{{ app()->getLocale() == 'id' ? '🇮🇩' : '🇬🇧' }}</span>
            <span class="text-sm font-medium">{{ app()->getLocale() == 'id' ? 'ID' : 'EN' }}</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
            <a href="{{ route('lang.switch', 'id') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition {{ app()->getLocale() == 'id' ? 'bg-coffee/5 text-coffee' : 'text-gray-700' }} rounded-t-xl">
                <span class="text-xl">🇮🇩</span>
                <div>
                    <p class="font-medium text-sm">Bahasa Indonesia</p>
                    <p class="text-xs text-gray-400">Indonesian</p>
                </div>
                @if(app()->getLocale() == 'id')
                    <svg class="w-4 h-4 ml-auto text-coffee" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </a>
            <a href="{{ route('lang.switch', 'en') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition {{ app()->getLocale() == 'en' ? 'bg-coffee/5 text-coffee' : 'text-gray-700' }} rounded-b-xl">
                <span class="text-xl">🇬🇧</span>
                <div>
                    <p class="font-medium text-sm">English</p>
                    <p class="text-xs text-gray-400">English</p>
                </div>
                @if(app()->getLocale() == 'en')
                    <svg class="w-4 h-4 ml-auto text-coffee" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </a>
        </div>
    </div>
@elseif($style === 'minimal')
    {{-- Minimal Style --}}
    <div class="flex items-center gap-1">
        <a href="{{ route('lang.switch', 'id') }}"
           class="px-2 py-1 text-xs font-medium rounded {{ app()->getLocale() == 'id' ? 'bg-coffee text-white' : 'text-gray-500 hover:text-gray-700' }} transition">
            ID
        </a>
        <span class="text-gray-300">|</span>
        <a href="{{ route('lang.switch', 'en') }}"
           class="px-2 py-1 text-xs font-medium rounded {{ app()->getLocale() == 'en' ? 'bg-coffee text-white' : 'text-gray-500 hover:text-gray-700' }} transition">
            EN
        </a>
    </div>
@else
    {{-- Default Button Style --}}
    <div class="flex items-center gap-1 border border-gray-200 rounded-lg overflow-hidden">
        <a href="{{ route('lang.switch', 'id') }}"
           class="flex items-center gap-1 px-2 py-1.5 text-xs font-medium {{ app()->getLocale() == 'id' ? 'bg-coffee text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition"
           title="Bahasa Indonesia">
            <span>🇮🇩</span>
            <span class="hidden sm:inline">ID</span>
        </a>
        <a href="{{ route('lang.switch', 'en') }}"
           class="flex items-center gap-1 px-2 py-1.5 text-xs font-medium {{ app()->getLocale() == 'en' ? 'bg-coffee text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition"
           title="English">
            <span>🇬🇧</span>
            <span class="hidden sm:inline">EN</span>
        </a>
    </div>
@endif
