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

        @include('dashboard.partials.news-widget', [
            'post' => $post,
            'icon' => 'news',
            'title' => __('Recent News'),
            'route' => 'news.show',
            'more' => [
                'route' => 'news.index',
                'label' => __('View all news'),
            ],
        ])
    </div>
</x-layout.app>
