@use('App\Enums\BookingStatus')
<nav class="hidden w-full pt-4 pb-2 border-b lg:bottom-0 lg:fixed lg:top-16 lg:block lg:w-64 lg:border-b-0 lg:border-r bg-gray-100 dark:bg-gray-900 overflow-y-auto space-y-4"
    :class="{ 'hidden': !sidebarOpen }" id="main-nav">

    <x-sidebar.group :heading="__('Menu')">
        @auth
            <x-sidebar.link route='dashboard' :label="__('Dashboard')">
                <x-slot:icon>
                    <path d="M8 20H3V10H0L10 0l10 10h-3v10h-5v-6H8v6z" />
                </x-slot:icon>
            </x-sidebar.link>
        @endauth
        @guest
            <x-sidebar.link route='home' :label="__('Home')">
                <x-slot:icon>
                    <path d="M8 20H3V10H0L10 0l10 10h-3v10h-5v-6H8v6z" />
                </x-slot:icon>
            </x-sidebar.link>
            <x-sidebar.link route='login' :label="__('Login')">
                <x-slot:icon>
                    <path
                        d="M4 8V6a6 6 0 1 1 12 0h-3v2h4a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z" />
                </x-slot:icon>
            </x-sidebar.link>
        @endguest
        @can('viewOwn', App\Models\Booking::class)
            <x-sidebar.link route='booking.rota' :label="__('My Rota')" icon="inbox.check" />
            <x-sidebar.link route='booking.invite' :match-routes="['booking.invite', 'booking.attendance.*']" :label="__('Invites')" icon="inbox" />
        @endcan
        @can('create', App\Models\Incident::class)
            <x-sidebar.link route='incident.create' :label="__('Report Incident')" icon="incident" />
        @endcan
    </x-sidebar.group>

    <x-sidebar.group :heading="__('Bookings')">
        @can('viewAny', App\Models\Booking::class)
            <x-sidebar.link route='booking.calendar' :match-routes="['booking.calendar', 'booking.show', 'booking.edit', 'booking.attendee.*', 'booking.comment.*']" :label="__('Calendar')" icon="calendar" />
        @endcan
        @can('viewAny', [App\Models\Booking::class, BookingStatus::Confirmed])
            <x-sidebar.link route='booking.confirmed' :label="__('Confirmed')" icon="calendar.check" />
        @endcan
        @can('viewAny', [App\Models\Booking::class, BookingStatus::Tentative])
            <x-sidebar.link route='booking.tentative' :label="__('Tentative')" icon="calendar.tee" />
        @endcan
        @can('viewAny', [App\Models\Booking::class, BookingStatus::Cancelled])
            <x-sidebar.link route='booking.cancelled' :label="__('Cancelled')" icon="calendar.cross" />
        @endcan
    </x-sidebar.group>

    <x-sidebar.group :heading="__('Manage')">
        @can('viewAny', App\Models\Change::class)
            <x-sidebar.link route='change.index' :match-routes="['change.*']" :label="__('Changes')">
                <x-slot:icon>
                    <path
                        d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-7.59V4h2v5.59l3.95 3.95-1.41 1.41L9 10.41z" />
                </x-slot:icon>
            </x-sidebar.link>
        @endcan
        @can('viewAny', App\Models\Todo::class)
            <x-sidebar.link route='todo.index' match-routes='todo.*' :label="__('Tasks')" icon="outline.checkmark" />
        @endcan
    </x-sidebar.group>

    <x-sidebar.group :heading="__('Account')">
        @auth
            <x-sidebar.button route='logout' method="POST" :label="__('Logout')">
                <x-slot:icon>
                    <path
                        d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z" />
                </x-slot:icon>
            </x-sidebar.button>
        @endauth
    </x-sidebar.group>
</nav>