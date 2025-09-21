<x-layouts.app :title="__('Dashboard')">
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Bem-vindo de volta, {{ auth()->user()->name }}!</h1>
                    <p class="text-blue-100">Aqui está um resumo do seu sistema de gestão de pedidos.</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chart-line text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @include('components.Dashboard.stats-card', [
                'title' => 'Total de Produtos',
                'value' => $data['stats']['total_products']['value'],
                'growth' => $data['stats']['total_products']['growth'],
                'growthType' => $data['stats']['total_products']['growth_type'],
                'icon' => 'fas fa-box',
                'iconColor' => 'blue'
            ])

            @include('components.Dashboard.stats-card', [
                'title' => 'Pedidos Hoje',
                'value' => $data['stats']['orders_today']['value'],
                'growth' => $data['stats']['orders_today']['growth'],
                'growthType' => $data['stats']['orders_today']['growth_type'],
                'icon' => 'fas fa-shopping-bag',
                'iconColor' => 'green'
            ])

            @include('components.Dashboard.stats-card', [
                'title' => 'Receita Hoje',
                'value' => $data['stats']['revenue_today']['value'],
                'growth' => $data['stats']['revenue_today']['growth'],
                'growthType' => $data['stats']['revenue_today']['growth_type'],
                'icon' => 'fas fa-dollar-sign',
                'iconColor' => 'yellow',
                'valueFormat' => 'currency'
            ])

            @include('components.Dashboard.stats-card', [
                'title' => 'Usuários Ativos',
                'value' => $data['stats']['active_users']['value'],
                'growth' => $data['stats']['active_users']['growth'],
                'growthType' => $data['stats']['active_users']['growth_type'],
                'icon' => 'fas fa-users',
                'iconColor' => 'purple'
            ])
        </div>

        <!-- Charts and Recent Data -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revenue Chart -->
            @include('components.Dashboard.revenue-chart', ['monthlyRevenue' => $data['monthly_revenue']])

            <!-- Top Products -->
            @include('components.Dashboard.top-products', ['topProducts' => $data['top_products']])
        </div>

        <!-- Recent Orders -->
        <div class="grid grid-cols-1 gap-6">
            @include('components.Dashboard.recent-orders', ['orders' => $data['recent_orders']])
        </div>
    </div>
</x-layouts.app>
