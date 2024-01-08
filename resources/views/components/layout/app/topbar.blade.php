@aware(['title', 'header'])

<div class="md:sticky md:top-0 z-40">
    <div class="w-full h-20 px-4 bg-gray-100 border-b flex items-center justify-between">
        <div class="flex grow">

            <div class="lg:hidden flex items-center mx-4">
                <button class="hover:text-blue-500 hover:border-white focus:outline-none navbar-burger"
                    @click="sidebarOpen = ! sidebarOpen">
                    <svg class="h-5 w-5" v-bind:style="{ fill: 'black' }" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <title>Menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </button>
            </div>

            <div class="w-full flex px-4 items-center">
                <p class="font-semibold text-2xl text-blue-400 pl-4 uppercase">{{ config('app.name', 'Climbing Team') }}</p>
            </div>

        </div>
    </div>
</div>
