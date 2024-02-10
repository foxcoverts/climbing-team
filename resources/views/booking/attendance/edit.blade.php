@use('App\Enums\AttendeeStatus')
<x-layout.app :title="__('Edit Attendance')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $booking->activity }}
                            </h2>
                        </header>

                        <div class="space-y-2 my-2">
                            <p><strong>{{ __('Start') }}:</strong> {{ $booking->start_at }}</p>
                            <p><strong>{{ __('End') }}:</strong> {{ $booking->end_at }}</p>
                            <p><strong>{{ __('Location') }}:</strong> {{ $booking->location }}</p>
                        </div>

                        <form method="post" action="{{ route('booking.attendance.update', $booking) }}"
                            class="mt-6 space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="status" :value="__('Attendance')" />
                                <x-select-input id="status" name="status" :options="AttendeeStatus::class" :except="[AttendeeStatus::NeedsAction]"
                                    lang="attendee.status.:value" class="mt-1 block" :value="old('status', $status)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-button.primary>
                                    {{ __('Update') }}
                                </x-button.primary>

                                <x-button.secondary :href="route('booking.show', $booking)">
                                    {{ __('Cancel') }}
                                </x-button.secondary>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
