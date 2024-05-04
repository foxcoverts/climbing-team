<footer class="w-full border-t lg:pl-64 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
    <div class="px-8 py-6 flex flex-wrap gap-6 justify-between">
        <p>© Copyright {{ date('Y') }}</p>

        <ul class="flex flex-col gap-2 sm:flex-row sm:gap-6">
            <li><a href="{{ route('privacy-policy') }}"
                    class="hover:text-gray-900 dark:hover:text-gray-50">{{ __('Privacy Policy') }}</a></li>
            <li><a href="/" class="hover:text-gray-900 dark:hover:text-gray-50">{{ __('About Us') }}</a></li>
        </ul>
    </div>
</footer>
