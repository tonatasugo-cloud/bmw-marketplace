@props(['title', 'subtitle' => null, 'link' => null, 'linkText' => 'Lihat Semua'])

<div class="flex items-end justify-between mb-6">
    <div>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-gray-600 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    @if($link)
        <a href="{{ $link }}" class="text-blue-600 font-medium hover:underline text-sm">
            {{ $linkText }} →
        </a>
    @endif
</div>