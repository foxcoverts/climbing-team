@use('App\Enums\AttendeeStatus')
<x-layout.app :title="__('Edit Attendance')">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header')
        <div class="md:flex md:space-x-4">
            <div class="w-full max-w-xl">
                @include('booking.partials.details')

                <form method="post" action="{{ route('booking.attendance.update', $booking) }}">
                    @csrf
                    @method('PUT')
                    <div
                        class="my-4 space-y-4 p-4 border text-black bg-slate-100 border-slate-400 dark:text-white dark:bg-slate-900 dark:border-slate-600">
                        <p class="text-lg text-center">{{ __('Can you attend this event?') }}</p>
                        <div class="flex justify-center gap-4">
                            <x-button.primary type="submit" name="status"
                                :value="AttendeeStatus::Accepted->value">{{ __('Yes') }}</x-button.primary>
                            <x-button.secondary type="submit" name="status"
                                :value="AttendeeStatus::Declined->value">{{ __('No') }}</x-button.secondary>
                            <x-button.secondary type="submit" name="status"
                                :value="AttendeeStatus::Tentative->value">{{ __('Maybe') }}</x-button.secondary>
                        </div>
                        <p class="text-sm text-center">{{ __('Replying for :name.', ['name' => auth()->user()->name]) }}
                        </p>
                    </div>
                </form>
            </div>
            @include('booking.partials.guest-list', ['showTools' => false])
        </div>
    </section>
</x-layout.app>
