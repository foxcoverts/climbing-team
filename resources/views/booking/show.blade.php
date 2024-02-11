<x-layout.app :title="__('booking.title')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        @include('booking/partials/details', ['booking' => $booking])

                        <div class="mt-6 flex items-center gap-4">
                            <x-button.primary :href="route('booking.edit', $booking)">
                                {{ __('Edit') }}
                            </x-button.primary>
                            <form method="post" action="{{ route('booking.destroy', $booking) }}">
                                @csrf
                                @method('delete')

                                <x-button.danger>
                                    {{ __('Delete') }}
                                </x-button.danger>
                            </form>
                            <span class="block"></span>
                            <div x-data="{
                                open: false
                            }">
                                <div class="flex">
                                    <div class="relative">
                                        <button
                                            class="flex gap-2 px-4 py-2 border rounded-tl-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-25"
                                            :class="{ 'rounded-bl-md': !open }" x-on:click="open = true">
                                            @if (is_null($attendance))
                                                <x-icon.question-outline class="h-4 w-4 fill-current" />
                                                {{ __('Respond') }}
                                            @else
                                                <x-icon.checkmark-outline class="h-4 w-4 fill-current" />
                                                {{ __("attendee.status.{$attendance->status->value}") }}
                                            @endif
                                        </button>


                                        <div x-show="open" x-cloak x-on:click.outside="open = false"
                                            class="absolute left-0 -mt-px z-20 shadow-xl border border-gray-300 dark:border-gray-500 min-w-full">
                                            @foreach (['accepted', 'tentative', 'declined'] as $status)
                                                <form method="post"
                                                    action="{{ route('booking.attendance.update', $booking) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input name="status" value="{{ $status }}" type="hidden" />
                                                    <button
                                                        class="flex gap-2 flex-nowrap items-center min-w-full px-4 py-2 text-xs uppercase font-semibold tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-25">
                                                        @if ($status == $attendance?->status->value)
                                                            <x-icon.checkmark-outline class="h-4 w-4 fill-current" />
                                                        @else
                                                            <x-icon.empty-outline class="h-4 w-4 fill-current" />
                                                        @endif
                                                        <span
                                                            class="text-nowrap">{{ __("attendee.status.$status") }}</span>
                                                    </button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </div>

                                    <button
                                        class="px-2 py-2 border border-l-0 rounded-r-md tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-25"
                                        x-on:click.prevent="open = true">
                                        <x-icon.cheveron-down class="w-4 h-4 fill-current" x-show="!open" />
                                        <x-icon.cheveron-up class="w-4 h-4 fill-current" x-show="open" x-cloak />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
