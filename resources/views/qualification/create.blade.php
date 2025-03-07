@use('App\Enums\GirlguidingScheme')
@use('App\Enums\MountainTrainingAward')
@use('App\Enums\ScoutPermitActivity')
@use('App\Enums\ScoutPermitCategory')
@use('App\Enums\ScoutPermitType')
<x-layout.app :title="__('Add Qualification - :Name', ['name' => $user->name])">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __(':Name - Add Qualification', ['name' => $user->name]) }}
                </h1>
            </div>
        </header>

        <form method="post" action="{{ route('user.qualification.store', $user) }}" x-data="{
            submitted: false,
            qualification: {{ Js::from([
                'detail_type' => old('detail_type', $qualification->detail_type),
                'expires_on' => old('expires_on', $qualification->expires_on?->format('Y-m-d')),
            ]) }},
            isGirlguiding() {
                return this.qualification.detail_type == 'App\\Models\\GirlguidingQualification';
            },
            isMountainTraining() {
                return this.qualification.detail_type == 'App\\Models\\MountainTrainingQualification';
            },
            isScoutPermit() {
                return this.qualification.detail_type == 'App\\Models\\ScoutPermit';
            },
            freshDetail() {
                if (this.isGirlguiding()) {
                    this.qualification = {
                        detail_type: this.qualification.detail_type,
                        expires_on: this.qualification.expires_on,
        
                        scheme: 'climbing',
                        level: 1,
                    };
                } else if (this.isMountainTraining()) {
                    this.qualification = {
                        detail_type: this.qualification.detail_type,
                        expires_on: this.qualification.expires_on,
        
                        award: 'CWI',
                    };
                } else if (this.isScoutPermit()) {
                    this.qualification = {
                        detail_type: this.qualification.detail_type,
                        expires_on: this.qualification.expires_on,
        
                        activity: 'Climbing and Abseiling',
                        category: 'Artificial Top Rope',
                        permit_type: 'leadership',
                        restrictions: '',
                    };
                } else {
                    this.qualification = {
                        detail_type: this.qualification.detail_type,
                        expires_on: this.qualification.expires_on,
                    };
                }
            },
        }"
            x-on:submit="setTimeout(() => submitted = true, 0)">
            @csrf

            <div class="p-4 sm:px-8 max-w-prose space-y-6">
                <div>
                    <x-input-label for="detail_type" :value="__('Qualification Type')" />
                    <x-select-input id="detail_type" name="detail_type" class="mt-1 block" required
                        x-model="qualification.detail_type" @change="freshDetail" autocomplete="off">
                        <template x-if="!qualification.detail_type">
                            <option value="" selected></option>
                        </template>
                        @foreach ([\App\Models\GirlguidingQualification::class, \App\Models\MountainTrainingQualification::class, \App\Models\ScoutPermit::class] as $qualification_type)
                            <option value="{{ $qualification_type }}">
                                {{ __("app.qualification.type.$qualification_type") }}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error class="mt-2" :messages="$errors->get('detail_type')" />
                </div>

                <template x-if="isGirlguiding">
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="scheme" :value="__('Scheme')" />
                            <x-select-input id="scheme" name="scheme" class="mt-1 block" required
                                x-model="qualification.scheme">
                                <template x-if="!qualification.scheme">
                                    <option value="" selected></option>
                                </template>
                                <x-select-input.enum :options="GirlguidingScheme::class" lang="app.girlguiding.scheme.:value" />
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('scheme')" />
                        </div>

                        <div>
                            <x-input-label for="level" :value="__('Level')" />
                            <x-text-input type="number" id="level" name="level" min="1" max="2"
                                x-model="qualification.level" class="mt-1" />
                            <x-input-error class="mt-2" :messages="$errors->get('level')" />
                        </div>

                        <div>
                            <x-input-label for="expires_on" :value="__('Expires')" />
                            <x-text-input type="date" id="expires_on" name="expires_on" class="mt-1" required
                                x-model="qualification.expires_on" />
                            <x-input-error class="mt-2" :messages="$errors->get('expires_on')" />
                        </div>
                    </div>
                </template>

                <template x-if="isMountainTraining">
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="award" :value="__('Award')" />
                            <x-select-input id="award" name="award" class="mt-1 block" required
                                x-model="qualification.award">
                                <template x-if="!qualification.award">
                                    <option value="" selected></option>
                                </template>
                                <x-select-input.enum :options="MountainTrainingAward::class" lang="app.mountain-training.award.:value" />
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('award')" />
                        </div>
                    </div>
                </template>

                <template x-if="isScoutPermit" x-data="{
                    activities: {{ Js::from([
                        ScoutPermitActivity::ClimbingAndAbseiling->value => [
                            ScoutPermitCategory::ArtificialTopRope,
                            ScoutPermitCategory::NaturalTopRope,
                            ScoutPermitCategory::ArtificialLeadClimbing,
                            ScoutPermitCategory::NaturalLeadClimbing,
                        ],
                    ]) }},
                    categories() {
                        if (this.qualification.activity && this.activities[this.qualification.activity]) {
                            return this.activities[this.qualification.activity];
                        }
                        return [];
                    },
                }">
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="activity" :value="__('Activity')" />
                            <x-select-input id="activity" name="activity" class="mt-1 block" required
                                x-model="qualification.activity">
                                <template x-if="!qualification.activity">
                                    <option value="" selected></option>
                                </template>
                                <x-select-input.enum :options="ScoutPermitActivity::class" lang="app.scout-permit.activity.:value" />
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('activity')" />
                        </div>

                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <x-select-input id="category" name="category" class="mt-1 block" required
                                x-model="qualification.category" x-bind:disabled="!qualification.activity">
                                <template x-if="!qualification.category">
                                    <option value="" selected></option>
                                </template>
                                <template x-for="label in categories()">
                                    <option x-bind:value="label" x-text="label"
                                        x-bind:selected="label == qualification.activity"></option>
                                </template>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('category')" />
                        </div>

                        <div>
                            <x-input-label for="permit_type" :value="__('Permit Type')" />
                            <x-select-input id="permit_type" name="permit_type" class="mt-1 block" required
                                x-model="qualification.permit_type" x-bind:disabled="!qualification.category">
                                <template x-if="!qualification.permit_type">
                                    <option value="" selected></option>
                                </template>
                                <x-select-input.enum :options="ScoutPermitType::class" lang="app.scout-permit.permit-type.:value" />
                                <x-input-error class="mt-2" :messages="$errors->get('permit_type')" />
                            </x-select-input>
                        </div>

                        <div x-data="{
                            no_restrictions: false,
                            ___restrictions: '',
                            checkRestrictions() {
                                if (this.no_restrictions) {
                                    this.___restrictions = this.qualification.restrictions;
                                    this.qualification.restrictions = '';
                                } else {
                                    this.qualification.restrictions = this.___restrictions;
                                }
                            },
                            oneLineInput(event) {
                                const selectionStart = event.target.selectionStart;
                                const value = event.target.value;
                        
                                event.target.value =
                                    event.target.value
                                    .replace(/[ ]*[ \r\n]+[ ]*/g, ' ')
                                    .replace(/^[ ]+/, '');
                        
                                if (event.target.value != value) {
                                    var diff = value.length - event.target.value.length;
                        
                                    if ((event.data == ' ' || event.inputType == 'deleteContentForward') &&
                                        (event.target.value.charAt(selectionStart - 1) == ' ')) {
                                        diff = diff - 1;
                                    }
                        
                                    event.target.selectionStart = Math.max(selectionStart - diff, 0);
                                    event.target.selectionEnd = Math.max(selectionStart - diff, 0);
                                }
                        
                                this.qualification.restrictions = event.target.value;
                            },
                        }">
                            <x-input-label for="restrictions" :value="__('Restrictions')" />
                            <x-textarea id="restrictions" name="restrictions" class="mt-1 w-full"
                                x-bind:required="!no_restrictions" x-bind:readonly="no_restrictions"
                                x-bind:placeholder="no_restrictions ? 'None' : ''" x-model="qualification.restrictions"
                                x-on:input="oneLineInput" />
                            <label class="mt-1 block">
                                <x-input-checkbox name="__no_restrictions" x-model="no_restrictions"
                                    x-on:change='checkRestrictions' />
                                {{ __('No restrictions') }}
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('restrictions')" />
                        </div>

                        <div>
                            <x-input-label for="expires_on" :value="__('Expires')" />
                            <x-text-input type="date" id="expires_on" name="expires_on" class="mt-1" required
                                x-model="qualification.expires_on" />
                            <x-input-error class="mt-2" :messages="$errors->get('expires_on')" />
                        </div>
                    </div>
                </template>
            </div>

            <footer class="p-4 sm:px-8 flex flex-wrap items-center gap-4">
                <x-button.primary class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Save')"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Save') }}'" />

                @can('viewAny', [App\Models\Qualification::class, $user])
                    <x-button.secondary :href="route('user.qualification.index', $user)" :label="__('Back')" />
                @endcan
            </footer>
        </form>
    </section>
</x-layout.app>
