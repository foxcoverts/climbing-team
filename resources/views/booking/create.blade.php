@use('App\Enums\BookingStatus')
<x-layout.app :title="__('Create Booking')">
    <section x-data="{
        booking: {},
        submitted: false,
    }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    <span x-text="booking.activity || 'Booking'">{{ $form->activity }}</span>
                    -
                    <span x-text="dateString(booking.start_date)">&nbsp;</span>
                </h1>
                <div class="flex items-center gap-4 justify-end grow">
                    <x-badge.booking-status :status="BookingStatus::Tentative" />
                </div>
            </div>
        </header>

        <form method="post" action="{{ route('booking.store') }}" x-on:submit="setTimeout(() => submitted = true, 0)"
            class="p-4 sm:px-8">
            @csrf

            <div class="space-y-6 max-w-prose">
                <div class="border-b border-gray-800 dark:border-gray-200">
                    <h2 class="text-xl font-medium text-gray-800 dark:text-gray-200">@lang('Add Booking')</h2>
                </div>

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
                        <x-text-input id="start_date" name="start_date" type="date" :value="old('start_date', $form->start_date)"
                            placeholder="yyyy-mm-dd" required autofocus x-model.fill="booking.start_date" />
                        <x-input-error :messages="$errors->get('start_date')" />
                    </div>

                    <div class="flex gap-6">
                        <div class="space-y-1">
                            <x-input-label for="start_time" :value="__('Start')" />
                            <x-text-input id="start_time" name="start_time" type="time" step="60"
                                :value="old('start_time', $form->start_time)" placeholder="hh:mm" required x-model.fill="start_time"
                                @change="syncEndTime" />
                            <x-input-error :messages="$errors->get('start_time')" />
                        </div>

                        <div class="space-y-1">
                            <x-input-label for="end_time" :value="__('End')" />
                            <x-text-input id="end_time" name="end_time" type="time" step="60" :value="old('end_time', $form->end_time)"
                                placeholder="hh:mm" required x-model.fill="end_time" @blur="syncDuration" />
                            <x-input-error :messages="$errors->get('end_time')" />
                        </div>
                    </div>
                </div>

                <div class="space-y-1">
                    <x-input-label for="location" :value="__('Location')" />
                    <x-text-input id="location" name="location" type="text" class="block w-full" :value="old('location', $form->location)"
                        maxlength="255" required x-model.fill="booking.location" />
                    <x-input-error :messages="$errors->get('location')" />
                </div>

                <div class="space-y-1">
                    <datalist id="activity-suggestions">
                        @foreach ($form->activity_suggestions as $activity)
                            <option>{{ $activity }}</option>
                        @endforeach
                    </datalist>
                    <x-input-label for="activity" :value="__('Activity')" />
                    <x-text-input id="activity" name="activity" type="text" class="block w-full" :value="old('activity', $form->activity)"
                        maxlength="255" required autocomplete="on" list="activity-suggestions"
                        x-model.fill="booking.activity" />
                    <x-input-error :messages="$errors->get('activity')" />
                </div>

                <div class="space-y-1">
                    <x-input-label for="group_name" :value="__('Group Name')" />
                    <x-text-input id="group_name" name="group_name" type="text" class="block w-full"
                        :value="old('group_name', $form->group_name)" maxlength="255" required />
                    <x-input-error :messages="$errors->get('group_name')" />
                </div>

                <div class="space-y-1">
                    <x-input-label for="notes" :value="__('Notes')" />
                    <x-textarea id="notes" name="notes" class="block w-full" :value="old('notes', $form->notes)"
                        x-meta-enter.prevent="$el.form.requestSubmit()" />
                    <x-input-error :messages="$errors->get('notes')" />
                </div>

                <div class="space-y-1">
                    <x-input-label for="lead_instructor_notes" :value="__('Lead Instructor Notes')" />
                    <p class="text-sm">@lang('The Lead Instructor Notes will only be visible to the Lead Instructor. You can use these to share access arrangements, gate codes, etc.')</p>
                    <x-textarea id="lead_instructor_notes" name="lead_instructor_notes" class="block w-full"
                        :value="old('lead_instructor_notes', $form->lead_instructor_notes)" x-bind:disabled="booking.cancelled"
                        x-meta-enter.prevent="$el.form.requestSubmit()" />
                    <x-input-error :messages="$errors->get('lead_instructor_notes')" />
                </div>
            </div>

            <footer class="mt-6 flex items-center gap-4">
                <x-button.primary x-bind:disabled="submitted" :label="__('Create')"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Create') }}'" />

                @can('viewAny', App\Models\Booking::class)
                    <x-button.secondary :href="route('booking.calendar')">
                        @lang('Back')
                    </x-button.secondary>
                @endcan
            </footer>
        </form>
    </section>
</x-layout.app>
