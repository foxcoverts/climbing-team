<x-layout.app :title="__('Dashboard')">
    <div class="divide-y">
        @include('dashboard.partials.next-widget', [
            'booking' => $next,
            'title' => __('Next Booking'),
            'route' => 'booking.show',
            'more' => [
                'route' => 'booking.rota',
                'label' => __('View your rota'),
            ],
        ])

        @include('dashboard.partials.next-widget', [
            'booking' => $invite,
            'title' => __('My Invites'),
            'route' => 'booking.attendance.show',
            'more' => [
                'route' => 'booking.invite',
                'label' => __('View your invitations'),
            ],
        ])
    </div>
</x-layout.app>
