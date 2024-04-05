@use('App\Enums\GirlguidingScheme')
@use('App\Enums\MountainTrainingAward')
@use('App\Enums\ScoutPermitActivity')
@use('App\Enums\ScoutPermitCategory')
@use('App\Enums\ScoutPermitType')
<x-layout.app :title="__('Edit Qualification') . ' - ' . $user->name">
    <section class="p-4 sm:px-8 max-w-xl" x-data="{
        submitted: false,
        qualification: {{ Js::from([
            'detail_type' => $qualification->detail_type,
            'expires_on' => old('expires_on', $qualification->expires_on?->format('Y-m-d')),
            ...collect($qualification->detail)->except('id', 'created_at', 'updated_at')->all(),
        ]) }},
        isGirlguiding() {
            return this.qualification.detail_type == 'App\\Models\\GirlguidingQualification';
        },
        isScoutPermit() {
            return this.qualification.detail_type == 'App\\Models\\ScoutPermit';
        },
        isMountainTraining() {
            return this.qualification.detail_type == 'App\\Models\\MountainTrainingQualification';
        },
        init() {
            if (this.qualification.detail_type == 'App\\Models\\ScoutPermit') {
                this.qualification.no_restrictions = !this.qualification.restrictions;
            }
        },
    }">
        <header>
            <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                {{ $user->name }} - @lang('Edit Qualification')
            </h1>
        </header>

        <form method="post" id="update-form" action="{{ route('user.qualification.update', [$user, $qualification]) }}"
            class="mt-6 space-y-6" x-on:submit="setTimeout(() => submitted = true, 0)">
            @csrf
            @method('PATCH')

            <div>
                <x-fake-label :value="__('Qualification Type')" />
                <x-fake-input class="mt-1 block">
                    @lang('app.qualification.type.' . $qualification->detail_type)
                </x-fake-input>
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
                                <option value="" disabled selected></option>
                            </template>
                            <template x-for="(label, value) in activities">
                                <option x-bind:value="value" x-text="value"></option>
                            </template>
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('activity')" />
                    </div>

                    <div>
                        <x-input-label for="category" :value="__('Category')" />
                        <x-select-input id="category" name="category" class="mt-1 block" required
                            x-model="qualification.category" x-bind:disabled="!qualification.activity">
                            <template x-if="!qualification.category">
                                <option value="" disabled selected></option>
                            </template>
                            <template x-for="label in categories()">
                                <option x-bind:value="label" x-text="label"
                                    x-bind:selected="label == qualification.category"></option>
                            </template>
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('category')" />
                    </div>

                    <div>
                        <x-input-label for="permit_type" :value="__('Permit Type')" />
                        <x-select-input id="permit_type" name="permit_type" class="mt-1 block" required
                            x-model="qualification.permit_type" x-bind:disabled="!qualification.category">
                            <template x-if="!qualification.permit_type">
                                <option value="" disabled selected></option>
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
                        init() {
                            this.no_restrictions = this.qualification.no_restrictions;
                            console.log({ restrictions: this.no_restrictions })
                        }
                    }">
                        <x-input-label for="restrictions" :value="__('Restrictions')" />
                        <x-textarea id="restrictions" name="restrictions" class="mt-1 w-full"
                            x-bind:required="!no_restrictions" x-bind:readonly="no_restrictions"
                            x-bind:placeholder="no_restrictions ? 'None' : ''" x-model="qualification.restrictions"
                            x-on:input='oneLineInput' />
                        <label class="mt-1 w-full flex items-center gap-2">
                            <input type="checkbox" name="__no_restrictions" x-model="no_restrictions"
                                x-on:change='checkRestrictions' />
                            <span>@lang('No restrictions')</span>
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
        </form>

        <footer class="flex flex-wrap items-center gap-4 mt-6">
            <x-button.primary x-bind:disabled="submitted" form="update-form" class="whitespace-nowrap"
                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Save') }}'" />
            @can('delete', $qualification)
                <form method="post" action="{{ route('user.qualification.destroy', [$user, $qualification]) }}"
                    x-data="{ submitted: false }" x-on:submit="setTimeout(() => submitted = true, 0)">
                    @csrf
                    @method('delete')
                    <x-button.danger x-bind:disabled="submitted" class="whitespace-nowrap"
                        x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Remove') }}'" />
                </form>
            @endcan
            @can('viewAny', [App\Models\Qualification::class, $user])
                <x-button.secondary :href="route('user.qualification.index', $user)">
                    @lang('Back')
                </x-button.secondary>
            @endcan
        </footer>
    </section>
</x-layout.app>
