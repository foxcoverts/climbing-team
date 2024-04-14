<x-layout.app :title="__('Report Incident')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10">
            <div class="px-4 sm:px-8">
                <div class="py-2 flex flex-wrap min-h-16 max-w-prose items-center justify-between gap-2">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                        @lang('Report Incident')
                    </h1>
                </div>
            </div>
        </header>

        <form method="post" action="{{ route('user.store') }}" class="p-4 sm:px-8" x-data="{
            submitted: false,
            today() {
                return (new Date()).toISOString().substring(0, 10);
            },
            form: {{ Js::from([
                'incident_type' => App\Enums\Incident\Type::HealthOrIllness,
                'severity' => App\Enums\Incident\Severity::Other,
                'date' => Carbon\Carbon::now()->format('Y-m-d'),
                'time' => Carbon\Carbon::now()->format('H:i'),
                'location_name' => 'Fox Coverts Scout Campsite, Newbold Road, Kirkby Mallory, LE9 7QG',
                'date_of_birth' => Carbon\Carbon::now()->format('Y-m-d'),
                'gender' => App\Enums\Incident\Gender::Unknown,
                'membership_type' => App\Enums\Incident\MembershipType::AdultVolunteer,
            ]) }},
        }"
            x-on:submit="setTimeout(() => submitted = true, 0)">
            @csrf


            <div class="space-y-6 max-w-prose">
                <fieldset class="space-y-6">
                    <legend class="text-xl font-medium">@lang('Incident details')</legend>

                    <div>
                        <x-input-label for="incident_type" :value="__('Type of Incident')" />
                        <x-select-input id="incident_type" name="incident_type" x-model="form.incident_type"
                            class="mt-1 w-full max-w-full" required>
                            <template x-if="!form.incident_type">
                                <option value="" selected disabled></option>
                            </template>
                            <x-select-input.enum :options="App\Enums\Incident\Type::class" lang=":value" />
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('incident_type')" />
                    </div>

                    <div>
                        <x-input-label for="severity" :value="__('Severity')" />
                        <x-select-input id="severity" name="severity" x-model="form.severity" class="mt-1" required>
                            <template x-if="!form.severity">
                                <option value="" selected disabled></option>
                            </template>
                            <x-select-input.enum :options="App\Enums\Incident\Severity::class" lang=":value" />
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('severity')" />
                    </div>
                </fieldset>

                <fieldset class="space-y-6">
                    <legend class="text-xl font-medium">@lang('When/Where did the incident happened?')</legend>

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
                        <p class="text-sm mt-1">@lang("Please tell us where the incident occurred. For example: back steps, warden's hut, by the picnic bench.")</p>
                        <x-textarea id="location_description" name="location_description"
                            x-model="form.location_descripition" class="mt-1 w-full" required></x-textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('location_description')" />
                    </div>
                </fieldset>

                <fieldset class="space-y-6">
                    <legend class="text-xl font-medium">@lang('Who was involved?')</legend>
                    <p>@lang('Please provide the name of the injured person. If no one was injured in this incident please use the organisation name, for example: First name: 1st Anytown Scouts / Last name: Cub section')</p>

                    <div class="grid grid-flow-col auto-cols-auto gap-6">
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" />
                            <x-text-input id="first_name" name="first_name" x-model="form.first_name"
                                class="mt-1 w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                        </div>

                        <div>
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input id="last_name" name="last_name" x-model="form.last_name" class="mt-1 w-full"
                                required />
                            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                        </div>
                    </div>

                    <p>@lang('Please provide the Date of Birth for the injured person. If no one was injured please select the date of incident, for a person related incident without confirmed Date of Birth use best guess.')</p>

                    <div>
                        <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                        <x-text-input id="date_of_birth" name="date_of_birth" type="date"
                            x-model="form.date_of_birth" class="mt-1" x-bind:max="today" required />
                        <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                    </div>

                    <p>@lang('Please provide the Gender of the injured person. If no one was injured please select Unknown.')</p>

                    <div>
                        <x-input-label for="gender" :value="__('Gender')" />
                        <x-select-input id="gender" name="gender" x-model="form.gender" class="mt-1" required>
                            <template x-if="!form.gender">
                                <option value="" selected disabled></option>
                            </template>
                            <x-select-input.enum :options="App\Enums\Incident\Gender::class" lang=":value" />
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                    </div>

                    <p>@lang('Please select the membership type or category for non-member injured. If the injured person has more than one role, please select the membership type they were when the incident occurred. If no one was injured please select the type of the group involved.')</p>

                    <div>
                        <x-input-label for="membership_type" :value="__('Membership Type')" />
                        <x-select-input id="membership_type" name="membership_type" x-model="form.membership_type"
                            class="mt-1" required>
                            <template x-if="!form.membership_type">
                                <option value="" selected disabled></option>
                            </template>
                            @foreach (App\Enums\Incident\MembershipType::groups() as $label => $options)
                                <optgroup label="@lang($label)">
                                    @foreach ($options as $option)
                                        <option value="{{ $option->value }}">@lang($option->value)</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('membership_type')" />
                    </div>
                </fieldset>

                <fieldset class="space-y-6">
                    <legend class="text-xl font-medium">@lang('Home details of injured person')</legend>
                    {{-- Country --}}
                    {{-- County/Area/Region --}}
                    {{-- District --}}
                </fieldset>

                <fieldset class="space-y-6">
                    <legend class="text-xl font-medium">@lang('What was happening when the incident occurred?')</legend>
                    {{-- Activity --}}
                    {{-- Activity - sub category --}}
                    {{-- Injury - type --}}
                    {{-- Injury - body part affected
                        // - Back
                        // - Face - ears
                        // - Face - eyes
                        // - Face - mouth
                        // - Face - nose
                        // - Head/scalp
                        // - Limbs - ankles
                        // - Limbs - elbows
                        // - Limbs - feet
                        // - Limbs - fingers/thumb
                        // - Limbs - hands
                        // - Limbs - knees
                        // - Limbs - lower arms
                        // - Limbs - lower legs
                        // - Limbs - toes
                        // - Limbs - upper arms
                        // - Limbs - upper legs
                        // - Limbs - wrists
                        // - Neck
                        // - Torso
                        // - Trunk/pelvis
                        // - Unknown
                    --}}
                    {{-- Treatment or external assistance received from
                        // - A&E
                        // - Ambulance called but person not taken to hospital
                        // - Ambulance treatment and taken to hospital
                        // - Basic first aid treatment
                        // - Dentist
                        // - Fire Service
                        // - GP or family doctor
                        // - Helicopter
                        // - Lifeboat (RNLI)
                        // - Minor injuries / walk in centre
                        // - Mountain rescue
                        // - Police
                        // - St John Ambulance or similar
                    --}}
                    {{-- Was the injured person taken directly to hospital from the scene of the incident? --}}
                    {{-- Details of incident --}}
                    <p class="mt-1">@lang('Please provide a summary of what happened. This should include a description of what led up to the incident occurring as well what happened after.')</p>
                </fieldset>
            </div>

            <footer class="mt-6 flex flex-wrap items-center gap-4">
                <x-button.primary class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Submit')"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Submit') }}'" />
            </footer>
        </form>
    </section>
</x-layout.app>
