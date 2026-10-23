@props(['car'])

<div class="bg-white rounded-xl shadow hover:shadow-xl transition overflow-hidden group">
    <a href="{{ route('cars.show', $car->slug) }}">
        <div class="relative aspect-[4/3] bg-gray-200 overflow-hidden">
            @if($car->primaryImage)
                @php
                    $path = $car->primaryImage->path;
                    $src = \Illuminate\Support\Str::startsWith($path, 'http') 
                            ? $path 
                            : asset('storage/' . $path);
                @endphp
                <img src="{{ $src }}"
                    alt="{{ $car->title }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                    onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22><rect fill=%22%23e5e7eb%22 width=%22400%22 height=%22300%22/><text x=%2250%25%22 y=%2250%25%22 font-size=%2248%22 text-anchor=%22middle%22 dy=%22.3em%22>🚗</text></svg>';">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-5xl">
                    🚗
                </div>
            @endif

            @if($car->is_certified)
                <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                    ✓ CERTIFIED
                </span>
            @endif

            @if($car->status === 'reserved')
                <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">
                    RESERVED
                </span>
            @endif
        </div>
    </a>

    <div class="p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">
            {{ $car->series->name ?? 'BMW' }}
        </p>

        <a href="{{ route('cars.show', $car->slug) }}">
            <h3 class="font-bold text-gray-900 line-clamp-2 mb-2 hover:text-blue-600">
                {{ $car->title }}
            </h3>
        </a>

        <div class="flex flex-wrap gap-2 text-xs text-gray-600 mb-3">
            <span><img src="{{asset('storage/tgl.png')}}" class="w-5 h-5"> {{ $car->year }}</span>
            <span>•</span>
            <span>{{ number_format($car->mileage, 0, ',', '.') }} KM</span>
            <span>•</span>
            <span>{{ $car->transmission }}</span>
        </div>

        <div class="flex items-end justify-between">
            <div>
                <p class="text-xl font-bold text-blue-600">
                    Rp {{ number_format($car->price, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    <img src="{{asset('storage/maps.png')}}" class="w-5 h-5"> {{ $car->location_city }}
                </p>
            </div>
        </div>
    </div>
</div>