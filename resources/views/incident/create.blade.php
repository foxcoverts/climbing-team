<x-layout.app :title="__('Report Incident')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10">
            <div class="px-4 sm:px-8">
                <div class="py-2 flex flex-wrap min-h-16 max-w-prose items-center justify-between gap-2">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Report Incident') }}
                    </h1>
                </div>
            </div>
        </header>

        @unless ($errors->isEmpty())
            @dump($errors)
        @endunless

        <form method="post" action="{{ route('incident.create') }}" class="p-4 sm:px-8" x-data="{
            submitted: false,
            today() {
                return (new Date()).toISOString().substring(0, 10);
            },
            form: {{ Js::from([
                'date' => old('date', localDate(Carbon\Carbon::now())->format('Y-m-d')),
                'time' => old('time', localDate(Carbon\Carbon::now())->format('H:i')),
                'location_name' => old('location_name', 'Fox Coverts Scout Campsite, Newbold Road, Kirkby Mallory, LE9 7QG'),
                'location_description' => old('location_description', ''),
            
                'injured' => old('injured'),
            
                'injured_name' => old('injured_name', ''),
                'injured_dob' => old('injured_dob', localDate(Carbon\Carbon::now())->format('Y-m-d')),
                'injured_gender' => old('injured_gender'),
            
                'membership_type' => old('membership_type', App\Enums\Incident\MembershipType::AdultVolunteer),
                'group_name' => old('group_name', ''), // type != public
                'contact_name' => old('contact_name', ''), // any type
                'contact_phone' => old('contact_phone', ''), // type == public
                'contact_address' => old('contact_address', ''), // type == public
            
                'injuries' => old('injuries', []),
                'emergency_services' => old('emergency_services', 'no'),
                'hospital' => old('hospital', 'no'),
                'damaged' => old('damaged', 'no'),
            
                'details' => old('details', ''),
                'first_aid' => old('first_aid', ''),
            
                'reporter_name' => old('reporter_name', $currentUser->name),
                'reporter_email' => old('reporter_email', $currentUser->email),
                'reporter_phone' => old('reporter_phone', $currentUser->phone?->formatForCountry('GB')),
            ]) }},
            checkInjuries(value) {
                var injuriesEl = document.getElementById('injuries');
                if (injuriesEl) {
                    el = injuriesEl.getElementsByTagName('input').item(0);
                    if (value.length > 0) {
                        el.setCustomValidity('');
                    } else {
                        el.setCustomValidity('You must select at least one injury.');
                    }
                }
            },
            isPublic(membership) {
                return (membership == {{ Js::from(App\Enums\Incident\MembershipType::YouthPublic->value) }}) ||
                    (membership == {{ Js::from(App\Enums\Incident\MembershipType::AdultPublic->value) }});
            },
            init() {
                this.$watch('form.injuries', this.checkInjuries);
            },
        }"
            x-on:submit="setTimeout(() => submitted = true, 0)">
            @csrf

            <div class="space-y-6 max-w-prose">
                <fieldset class="space-y-4">
                    <legend class="text-xl font-medium">{{ __('When/Where did the incident happened?') }}</legend>

                    <div class="flex flex-wrap gap-6">
                        <div>
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" name="date" type="date" x-model="form.date"
                                class="mt-1" required />
                            <x-input-error class="mt-2" :messages="$errors->get('date')" />
                        </div>

                        <div>
                            <x-input-label for="time" :value="__('Time')" />
                            <x-text-input id="time" name="time" type="time" x-model="form.time"
                                class="mt-1" required />
                            <x-input-error class="mt-2" :messages="$errors->get('time')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="location_name" :value="__('Location Name')" />
                        <x-text-input id="location_name" name="location_name" x-model="form.location_name"
                            class="mt-1 w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('location_name')" />
                    </div>

                    <div>
                        <x-input-label for="location_description" :value="__('Location Description')" />
                        <p class="text-sm mt-1">{{ __('Please tell us where the incident occurred.') }}</p>
                        <x-text-input id="location_description" name="location_description"
                            x-model="form.location_description" class="mt-1 w-full" required
                            :placeholder="__('For example: back steps, warden\'s hut, by the picnic bench.')"></x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('location_description')" />
                    </div>
                </fieldset>

                <fieldset class="space-y-2">
                    <legend class="text-xl font-medium">{{ __('Was anyone injured?') }}</legend>

                    <x-input-label>
                        <input type="radio" name="injured" value="yes" required x-model="form.injured" />
                        {{ __('Yes') }}
                    </x-input-label>
                    <x-input-label>
                        <input type="radio" name="injured" value="no" required x-model="form.injured" />
                        {{ __('No') }}
                    </x-input-label>
                </fieldset>

                <fieldset class="space-y-4">
                    <template x-if="form.injured != 'yes'">
                        <legend class="text-xl font-medium">{{ __('Who was involved in this incident?') }}</legend>
                    </template>
                    <template x-if="form.injured == 'yes'">
                        <legend class="text-xl font-medium">{{ __('Who was injured?') }}</legend>
                    </template>

                    <template x-if="form.injured == 'yes'">
                        <div>
                            <x-input-label for="injured_name" :value="__('Name')" />
                            <x-text-input id="injured_name" name="injured_name" required autocomplete="off"
                                x-model="form.injured_name" class="mt-1 w-full" />
                        </div>
                    </template>

                    <template x-if="form.injured == 'yes'">
                        <div>
                            <x-input-label for="injured_dob" :value="__('Date of Birth')" />
                            <x-text-input id="injured_dob" name="injured_dob" type="date" x-model="form.injured_dob"
                                class="mt-1" x-bind:max="today" required />
                            <x-input-error class="mt-2" :messages="$errors->get('injured_dob')" />
                        </div>
                    </template>

                    <template x-if="form.injured == 'yes'">
                        <div>
                            <x-input-label for="injured_gender" :value="__('Gender')" />
                            <x-select-input id="injured_gender" name="injured_gender" x-model="form.injured_gender"
                                class="mt-1" required>
                                <template x-if="!form.injured_gender">
                                    <option value="" selected disabled></option>
                                </template>
                                <x-select-input.enum :options="App\Enums\Incident\Gender::class" lang=":value" />
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('injured_gender')" />
                        </div>
                    </template>

                    <div>
                        <x-input-label for="membership_type" :value="__('Membership Type')" />
                        <x-select-input id="membership_type" name="membership_type" x-model="form.membership_type"
                            class="mt-1" required>
                            <template x-if="!form.membership_type">
                                <option value="" selected disabled></option>
                            </template>
                            @foreach (App\Enums\Incident\MembershipType::groups() as $label => $options)
                                <optgroup label="{{ __($label) }}">
                                    @foreach ($options as $option)
                                        <option value="{{ $option->value }}">{{ __($option->value) }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('membership_type')" />
                    </div>

                    <template x-if="!isPublic(form.membership_type)">
                        <div>
                            <x-input-label for="group_name" :value="__('Group Name')" />
                            <x-text-input id="group_name" name="group_name" class="mt-1" required
                                x-model="form.group_name" />
                            <x-input-error class="mt-2" :messages="$errors->get('group_name')" />
                        </div>
                    </template>

                    <template x-if="form.membership_type">
                        <div>
                            <x-input-label for="contact_name" :value="__('Contact Name')" />
                            <x-text-input id="contact_name" name="contact_name" class="mt-1 w-full" required
                                x-model="form.contact_name" />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_name')" />
                        </div>
                    </template>

                    <template x-if="isPublic(form.membership_type)">
                        <div>
                            <x-input-label for="contact_phone" :value="__('Contact Phone')" />
                            <x-text-input type="tel" id="contact_phone" name="contact_phone" class="mt-1 w-full"
                                required x-model="form.contact_phone" />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_phone')" />
                        </div>
                    </template>

                    <template x-if="isPublic(form.membership_type)">
                        <div>
                            <x-input-label for="contact_address" :value="__('Contact Address & Postcode')" />
                            <x-text-input id="contact_address" name="contact_address" class="mt-1 w-full" required
                                x-model="form.contact_address" />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_address')" />
                        </div>
                    </template>
                </fieldset>

                <template x-if="form.injured == 'yes'">
                    <fieldset id="injuries" class="space-y-2" x-init="checkInjuries(form.injuries)">
                        <legend class="text-xl font-medium">{{ __('What were the injuries?') }}</legend>

                        @foreach (App\Enums\Incident\Injury::cases() as $option)
                            <x-input-label>
                                <x-input-checkbox name="injuries[]" :value="$option->value" x-model="form.injuries" />
                                {{ __($option->value) }}
                            </x-input-label>
                        @endforeach
                    </fieldset>
                </template>

                <template x-if="form.injured == 'yes'">
                    <fieldset class="space-y-2">
                        <legend class="text-xl font-medium">{{ __('Did the emergency services attend?') }}</legend>

                        <x-input-label for="emergency_services_yes">
                            <input type="radio" id="emergency_services_yes" name="emergency_services"
                                value="yes" x-model="form.emergency_services" />
                            {{ __('Yes') }}
                        </x-input-label>
                        <x-input-label for="emergency_services_no">
                            <input type="radio" id="emergency_services_no" name="emergency_services"
                                value="no" x-model="form.emergency_services" />
                            {{ __('No') }}
                        </x-input-label>
                    </fieldset>
                </template>

                <template x-if="form.injured == 'yes'">
                    <fieldset class="space-y-2">
                        <legend class="text-xl font-medium">
                            {{ __('Did the injured person go directly to hospital for treatment?') }}</legend>

                        <x-input-label for="hospital_yes">
                            <input type="radio" id="hospital_yes" name="hospital" value="yes"
                                x-model="form.hospital" />
                            {{ __('Yes') }}
                        </x-input-label>
                        <x-input-label for="hospital_no">
                            <input type="radio" id="hospital_no" name="hospital" value="no"
                                x-model="form.hospital" />
                            {{ __('No') }}
                        </x-input-label>
                    </fieldset>
                </template>

                <fieldset class="space-y-2">
                    <legend class="text-xl font-medium">{{ __('Was any property or equipment damaged?') }}</legend>

                    <x-input-label for="damaged_yes">
                        <input type="radio" id="damaged_yes" name="damaged" value="yes"
                            x-model="form.damaged" />
                        {{ __('Yes') }}
                    </x-input-label>
                    <x-input-label for="damaged_no">
                        <input type="radio" id="damaged_no" name="damaged" value="no"
                            x-model="form.damaged" />
                        {{ __('No') }}
                    </x-input-label>
                </fieldset>

                <fieldset class="space-y-4">
                    <legend class="text-xl font-medium">{{ __('Details of accident, injury or near miss') }}</legend>

                    <div>
                        <x-input-label for="details" :value="__('What happened?')" />
                        <x-textarea id="details" name="details" required :placeholder="__('Please give as much detail as possible, including any contributing factors.')" x-model="form.details"
                            class="mt-1 w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('details')" />
                    </div>

                    <template x-if="form.injured == 'yes'">
                        <div>
                            <x-input-label for="first_aid" :value="__('What first aid was performed?')" />
                            <x-textarea id="first_aid" name="first_aid" required x-model="form.first_aid"
                                class="mt-1 w-full" />
                            <x-input-error class="mt-2" :messages="$errors->get('first_aid')" />
                        </div>
                    </template>
                </fieldset>

                <fieldset class="space-y-4">
                    <legend class="text-xl font-medium">{{ __('Your contact details') }}</legend>

                    <div>
                        <x-input-label for="reporter_name" :value="__('Your Name')" />
                        <x-text-input id="reporter_name" name="reporter_name" required autocomplete="name"
                            x-model="form.reporter_name" class="mt-1 w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('reporter_name')" />
                    </div>

                    <div>
                        <x-input-label for="reporter_email" :value="__('Your Email Address')" />
                        <x-text-input type="email" id="reporter_email" name="reporter_email" required
                            autocomplete="email" x-model="form.reporter_email" class="mt-1 w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('reporter_email')" />
                    </div>

                    <div>
                        <x-input-label for="reporter_phone" :value="__('Your Phone Number')" />
                        <x-text-input type="tel" id="reporter_phone" name="reporter_phone" required
                            autocomplete="tel" x-model="form.reporter_phone" class="mt-1 w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('reporter_phone')" />
                    </div>
                </fieldset>
            </div>

            <footer class="mt-6 flex flex-wrap items-center gap-4">
                <x-button.primary class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Submit')"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Submit') }}'" />
            </footer>
        </form>
    </section>
</x-layout.app>
