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

        @include('dashboard.partials.next-widget', [
            'booking' => $invite,
            'icon' => 'inbox',
            'title' => __('My Invites'),
            'route' => 'booking.attendance.edit',
            'more' => [
                'route' => 'booking.invite',
                'label' => __('View your invitations'),
            ],
        ])
    </div>
</x-layout.app>
