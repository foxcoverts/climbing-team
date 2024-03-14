@use('App\Enums\BookingStatus')
<x-layout.app :title="__('Create Booking')">
    <section class="p-4 sm:px-8" x-data='{
        booking: {},
        submitted: false,
    }'>
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100 flex flex-wrap gap-2">
                <span x-text="booking.activity || 'Booking'"></span>
                -
                <span x-text="dateString(booking.start_date)"></span>
            </h2>
        </header>

        <p
            class="text-lg text-gray-800 dark:text-gray-200 border-b border-gray-800 dark:border-gray-200 my-2 flex items-center justify-between max-w-xl">
            <span class="flex items-center">
                <x-icon.location class="h-5 w-5 fill-current mr-1" />
                <span x-text="booking.location"></span>
            </span>
            <x-badge.booking-status :status="$booking->status" class="text-sm" />
        </p>

        <form method="post" action="{{ route('booking.store') }}" class="space-y-6 max-w-xl"
            x-on:submit="setTimeout(() => submitted = true, 0)">
            @csrf

            <div class="flex flex-wrap gap-6" x-data="{
                start_time: '',
                end_time: '',
                duration: 0,
            
                timeToMinutes(timeString) {
                    var time = timeString.match(/^([012]\d)[:](\d\d)/i);
                    if (time == null) return 0;
            
                    return ((parseInt(time[1], 10) || 0) * 60) +
                        (parseInt(time[2], 10) || 0);
                },
                minutesToTime(minutes) {
                    return (new String(Math.floor(minutes / 60))).padStart(2, '0') + ':' +
                        (new String(minutes % 60)).padStart(2, '0');
                },
                syncEndTime() {
                    var endMinutes = this.timeToMinutes(this.start_time) + this.duration;
                    if (endMinutes > 1440) {
                        this.end_time = '23:59';
                    } else {
                        this.end_time = this.minutesToTime(endMinutes);
                    }
                },
                syncDuration() {
                    this.duration = this.timeToMinutes(this.end_time) - this.timeToMinutes(this.start_time);
                    if (this.duration < 0) {
                        this.end_time = this.start_time;
                        this.duration = 0;
                    }
                },
            
                init() {
                    $nextTick(() => { this.syncDuration() });
                }
            }">
                <div class="space-y-1">
                    <x-input-label for="start_date" :value="__('Date')" />
                    <x-text-input id="start_date" name="start_date" type="date" :value="old('start_date', $booking->start_date)"
                        placeholder="yyyy-mm-dd" required autofocus x-model.fill="booking.start_date" />
                    <x-input-error :messages="$errors->get('start_date')" />
                </div>

                <div class="flex gap-6">
                    <div class="space-y-1">
                        <x-input-label for="start_time" :value="__('Start')" />
                        <x-text-input id="start_time" name="start_time" type="time" step="60" :value="old('start_time', $booking->start_time)"
                            placeholder="hh:mm" required x-model.fill="start_time" @change="syncEndTime" />
                        <x-input-error :messages="$errors->get('start_time')" />
                    </div>

                    <div class="space-y-1">
                        <x-input-label for="end_time" :value="__('End')" />
                        <x-text-input id="end_time" name="end_time" type="time" step="60" :value="old('end_time', $booking->end_time)"
                            placeholder="hh:mm" required x-model.fill="end_time" @blur="syncDuration" />
                        <x-input-error :messages="$errors->get('end_time')" />
                    </div>
                </div>
            </div>

            <div class="space-y-1">
                <x-input-label for="location" :value="__('Location')" />
                <x-text-input id="location" name="location" type="text" class="block w-full" :value="old('location', $booking->location)"
                    maxlength="255" required x-model.fill="booking.location" />
                <x-input-error :messages="$errors->get('location')" />
            </div>

            <div class="space-y-1">
                <datalist id="activity-suggestions">
                    @foreach ($activity_suggestions as $activity)
                        <option>{{ $activity }}</option>
                    @endforeach
                </datalist>
                <x-input-label for="activity" :value="__('Activity')" />
                <x-text-input id="activity" name="activity" type="text" class="block w-full" :value="old('activity', $booking->activity)"
                    maxlength="255" required autocomplete="on" list="activity-suggestions"
                    x-model.fill="booking.activity" />
                <x-input-error :messages="$errors->get('activity')" />
            </div>

            <div class="space-y-1">
                <x-input-label for="group_name" :value="__('Group Name')" />
                <x-text-input id="group_name" name="group_name" type="text" class="block w-full" :value="old('group_name', $booking->group_name)"
                    maxlength="255" required />
                <x-input-error :messages="$errors->get('group_name')" />
            </div>

            <div class="space-y-1">
                <x-input-label for="notes" :value="__('Notes')" />
                <x-textarea id="notes" name="notes" class="block w-full" :value="old('notes', $booking->notes)"
                    x-meta-enter.prevent="$el.form.requestSubmit()" />
                <x-input-error :messages="$errors->get('notes')" />
            </div>

            <div class="flex items-center gap-4">
                <x-button.primary x-bind:disabled="submitted"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Create') }}'" />

                <x-button.secondary :href="route('booking.calendar')">
                    @lang('Back')
                </x-button.secondary>
            </div>
        </form>
    </section>
</x-layout.app>
