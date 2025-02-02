<x-layout.app :title="__('Dashboard')">
    <div class="divide-y">
        @include('dashboard.partials.next-widget', [
            'booking' => $next,
            'icon' => 'inbox.check',
            'title' => __('Next Booking'),
            'route' => 'booking.show',
            'more' => [
                'route' => 'booking.rota',
                'label' => __('View your rota'),
            ],
        ])
    </div>
</x-layout.app>
