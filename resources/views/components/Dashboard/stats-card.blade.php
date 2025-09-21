@props([
    'title' => '',
    'value' => 0,
    'growth' => 0,
    'growthType' => 'positive',
    'icon' => 'fas fa-chart-line',
    'iconColor' => 'blue',
    'valueFormat' => 'number'
])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900">
                @if($valueFormat === 'currency')
                    R$ {{ number_format($value, 2, ',', '.') }}
                @else
                    {{ number_format($value) }}
                @endif
            </p>
            {{-- @if($growth !== 0)
                <p class="text-xs flex items-center mt-1 {{ $growthType === 'positive' ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas {{ $growthType === 'positive' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                    {{ abs($growth) }}% {{ $growthType === 'positive' ? 'este mês' : 'este mês' }}
                </p>
            @endif --}}
        </div>
        <div class="w-12 h-12 rounded-lg flex items-center justify-center {{ $iconColor === 'blue' ? 'bg-blue-100' : ($iconColor === 'green' ? 'bg-green-100' : ($iconColor === 'yellow' ? 'bg-yellow-100' : 'bg-purple-100')) }}">
            <i class="{{ $icon }} text-xl {{ $iconColor === 'blue' ? 'text-blue-600' : ($iconColor === 'green' ? 'text-green-600' : ($iconColor === 'yellow' ? 'text-yellow-600' : 'text-purple-600')) }}"></i>
        </div>
    </div>
</div>
