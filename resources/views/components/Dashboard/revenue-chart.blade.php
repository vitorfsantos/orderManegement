@props(['monthlyRevenue' => []])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Receita Mensal</h2>
        <div class="text-sm text-gray-500">Últimos 12 meses</div>
    </div>
    @php
        $maxRevenue = count($monthlyRevenue) > 0 ? max(array_column($monthlyRevenue, 'revenue')) : 0;
        $chartHeight = 200; // altura fixa do gráfico
    @endphp
    <div class="h-64 flex items-end justify-between space-x-1">
        @forelse($monthlyRevenue as $month)
            @php
                $height = $maxRevenue > 0 ? ($month['revenue'] / $maxRevenue) * $chartHeight : 0;
            @endphp
            <div class="flex flex-col items-center flex-1 min-w-0">
                <div class="w-full bg-gray-100 rounded-t relative mb-2" style="height: {{ $chartHeight }}px;">
                    @if($month['revenue'] > 0)
                        <div class="w-full bg-gradient-to-t from-indigo-500 to-indigo-400 rounded-t absolute bottom-0 transition-all duration-300 hover:from-indigo-600 hover:to-indigo-500" 
                             style="height: {{ $height }}px;" 
                             title="R$ {{ number_format($month['revenue'], 2, ',', '.') }}">
                        </div>
                    @endif
                </div>
                <div class="text-xs text-gray-500 font-medium">{{ $month['month'] }}</div>
                <div class="text-xs text-gray-700 font-semibold">R$ {{ number_format($month['revenue'], 0, ',', '.') }}</div>
            </div>
        @empty
            <div class="flex items-center justify-center h-full w-full text-gray-500">
                <div class="text-center">
                    <i class="fas fa-chart-bar text-4xl mb-2"></i>
                    <p>Nenhum dado disponível</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
