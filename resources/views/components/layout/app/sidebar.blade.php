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
        @can('viewAny', App\Models\Document::class)
            <x-sidebar.link route='document.index' :match-routes="['document.*', 'trash.document.*']" :label="__('Documents')" icon="document" />
        @endcan
        @can('viewAny', App\Models\NewsPost::class)
            <x-sidebar.link route='news.index' match-routes="news.*" :label="__('News')" icon="news" />
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
        @can('create', App\Models\Booking::class)
            <x-sidebar.link route='booking.create' :label="__('Add Booking')" icon="calendar.plus" />
        @endcan
        @can('viewTrashed', App\Models\Booking::class)
            <x-sidebar.link route='trash.booking.index' match-routes='trash.booking.*' :label="__('Deleted')" icon="trash" />
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
        @if (auth()->user()->keys()->exists())
            <x-sidebar.link route='key.index' match-routes="key.*" :label="__('Keys')" icon="key" />
        @endif
        @can('viewAny', App\Models\KitCheck::class)
            <x-sidebar.link route='kit-check.index' match-routes="kit-check.*" :label="__('Kit Checks')" icon="shield" />
        @endcan
        @can('viewAny', App\Models\MailLog::class)
            <x-sidebar.link route='mail.index' match-routes="mail.*" :label="__('Mail Log')">
                <x-slot:icon>
                    <path
                        d="M18 2a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h16zm-4.37 9.1L20 16v-2l-5.12-3.9L20 6V4l-10 8L0 4v2l5.12 4.1L0 14v2l6.37-4.9L10 14l3.63-2.9z" />
                </x-slot:icon>
            </x-sidebar.link>
        @endcan
        @can('viewAny', App\Models\Todo::class)
            <x-sidebar.link route='todo.index' match-routes='todo.*' :label="__('Tasks')" icon="outline.checkmark" />
        @endcan
        @can('viewAny', App\Models\User::class)
            <x-sidebar.link route='user.index' :match-routes="['user.*', 'user.booking.*', 'user.qualification.*']" :label="__('Users')">
                <x-slot:icon>
                    <path
                        d="M7 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0 1c2.15 0 4.2.4 6.1 1.09L12 16h-1.25L10 20H4l-.75-4H2L.9 10.09A17.93 17.93 0 0 1 7 9zm8.31.17c1.32.18 2.59.48 3.8.92L18 16h-1.25L16 20h-3.96l.37-2h1.25l1.65-8.83zM13 0a4 4 0 1 1-1.33 7.76 5.96 5.96 0 0 0 0-7.52C12.1.1 12.53 0 13 0z" />
                </x-slot:icon>
            </x-sidebar.link>
        @endcan
    </x-sidebar.group>

    <x-sidebar.group :heading="__('Account')">
        @auth
            <x-sidebar.link route='profile.edit' :label="__('Profile')" icon="user-solid-square" />
            <x-sidebar.link route='booking.links' :label="__('Calendar Links')" icon="calendar.download" />
            <x-sidebar.button route='logout' method="POST" :label="__('Logout')">
                <x-slot:icon>
                    <path
                        d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z" />
                </x-slot:icon>
            </x-sidebar.button>
        @endauth
    </x-sidebar.group>
</nav>
