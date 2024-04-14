@aware(['title', 'header'])

<div class="lg:fixed lg:w-64 z-40 text-gray-900 bg-gray-100 dark:text-white dark:bg-gray-900 border-b lg:border-r">
    <div class="w-full h-16 sm:px-4 flex items-center">
        <div class="flex grow overflow-hidden">
            <div class="lg:hidden flex items-center mx-4">
                <button class="hover:text-blue-500 dark:text-white focus:outline-none navbar-burger"
                    @click="sidebarOpen = ! sidebarOpen">
                    <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <title>Menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </button>
            </div>

            <div class="w-full">
                <p class="font-semibold text-2xl text-blue-400 uppercase">
                    <a href="{{ route('dashboard') }}" class="block truncate" title="{{ config('app.name') }}">
                        {{ config('app.name') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
