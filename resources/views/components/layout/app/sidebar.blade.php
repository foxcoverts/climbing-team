<nav class="w-full pt-4 pb-2 border-b md:bottom-0 md:fixed md:top-16 md:z-20 lg:block lg:w-64 lg:border-b-0 lg:border-r bg-gray-100 dark:bg-gray-900 overflow-y-auto space-y-4"
    :class="{ 'hidden': !sidebarOpen }" id="main-nav">

    <div>
        <x-sidebar.heading>@lang('Main')</x-sidebar.heading>
        <x-sidebar.link route='dashboard' :label="__('Dashboard')">
            <x-slot:icon>
                <path d="M8 20H3V10H0L10 0l10 10h-3v10h-5v-6H8v6z" />
            </x-slot:icon>
        </x-sidebar.link>
        <x-sidebar.link route='booking.rota' :label="__('My Rota')">
            <x-slot:icon>
                <path
                    d="M0 2C0 .9.9 0 2 0h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm14 12h4V2H2v12h4c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2zM5 9l2-2 2 2 4-4 2 2-6 6-4-4z" />
            </x-slot:icon>
        </x-sidebar.link>
        <x-sidebar.link route='booking.invite' :match-routes="['booking.invite', 'booking.attendance.*']" :label="__('Invites')">
            <x-slot:icon>
                <path
                    d="M0 2C0 .9.9 0 2 0h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm14 12h4V2H2v12h4c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2z" />
            </x-slot:icon>
        </x-sidebar.link>
    </div>

    @canany(['viewAny', 'viewTrashed', 'create'], App\Models\Booking::class)
        <div>
            <x-sidebar.heading>@lang('Bookings')</x-sidebar.heading>
            @can('viewAny', App\Models\Booking::class)
                <x-sidebar.link route='booking.calendar' :label="__('Calendar')">
                    <x-slot:icon>
                        <path
                            d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z" />
                    </x-slot:icon>
                </x-sidebar.link>
            @endcan
            @can('viewTrashed', App\Models\Booking::class)
                <x-sidebar.link route='trash.booking.index' match-routes='trash.booking.*' :label="__('Deleted')">
                    <x-slot:icon>
                        <path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z" />
                    </x-slot:icon>
                </x-sidebar.link>
            @endcan
            @can('create', App\Models\Booking::class)
                <x-sidebar.link route='booking.create' :label="__('Add Booking')">
                    <x-slot:icon>
                        <path
                            d="M15 2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h2V0h2v2h6V0h2v2zM3 6v12h14V6H3zm6 5V9h2v2h2v2h-2v2H9v-2H7v-2h2z" />
                    </x-slot:icon>
                </x-sidebar.link>
            @endcan
        </div>
    @endcanany

    @canany(['viewAny', 'create'], App\Models\User::class)
        <div>
            <x-sidebar.heading>@lang('Users')</x-sidebar.heading>
            @can('viewAny', App\Models\User::class)
                <x-sidebar.link route='user.index' :match-routes="['user.index', 'user.show', 'user.edit']" :label="__('View Users')">
                    <x-slot:icon>
                        <path
                            d="M7 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0 1c2.15 0 4.2.4 6.1 1.09L12 16h-1.25L10 20H4l-.75-4H2L.9 10.09A17.93 17.93 0 0 1 7 9zm8.31.17c1.32.18 2.59.48 3.8.92L18 16h-1.25L16 20h-3.96l.37-2h1.25l1.65-8.83zM13 0a4 4 0 1 1-1.33 7.76 5.96 5.96 0 0 0 0-7.52C12.1.1 12.53 0 13 0z" />
                    </x-slot:icon>
                </x-sidebar.link>
            @endcan
            @can('create', App\Models\User::class)
                <x-sidebar.link route='user.create' :label="__('Add User')">
                    <x-slot:icon>
                        <path
                            d="M2 6H0v2h2v2h2V8h2V6H4V4H2v2zm7 0a3 3 0 0 1 6 0v2a3 3 0 0 1-6 0V6zm11 9.14A15.93 15.93 0 0 0 12 13c-2.91 0-5.65.78-8 2.14V18h16v-2.86z" />
                    </x-slot:icon>
                </x-sidebar.link>
            @endcan
        </div>
    @endcanany

    @can('viewAny', App\Models\MailLog::class)
        <div>
            <x-sidebar.heading>@lang('Developer')</x-sidebar.heading>
            <x-sidebar.link route='mail.index' :match-routes="['mail.*']" :label="__('Mail Log')">
                <x-slot:icon>
                    <path
                        d="M18 2a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h16zm-4.37 9.1L20 16v-2l-5.12-3.9L20 6V4l-10 8L0 4v2l5.12 4.1L0 14v2l6.37-4.9L10 14l3.63-2.9z" />
                </x-slot:icon>
            </x-sidebar.link>
        </div>
    @endcan

    <div>
        <x-sidebar.heading>@lang('Account')</x-sidebar.heading>
        <x-sidebar.link route='profile.show' :label="__('Profile')">
            <x-slot:icon>
                <path
                    d="M0 2C0 .9.9 0 2 0h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm7 4v2a3 3 0 1 0 6 0V6a3 3 0 1 0-6 0zm11 9.14A15.93 15.93 0 0 0 10 13c-2.91 0-5.65.78-8 2.14V18h16v-2.86z" />
            </x-slot:icon>
        </x-sidebar.link>
        <x-sidebar.button route='logout' method="POST" :label="__('Logout')">
            <x-slot:icon>
                <path fill-rule="evenodd"
                    d="M4.16 4.16l1.42 1.42A6.99 6.99 0 0 0 10 18a7 7 0 0 0 4.42-12.42l1.42-1.42a9 9 0 1 1-11.69 0zM9 0h2v8H9V0z" />
            </x-slot:icon>
        </x-sidebar.button>
    </div>
</nav>
