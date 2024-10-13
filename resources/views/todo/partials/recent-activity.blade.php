@use('App\Enums\AttendeeStatus')
@use('Carbon\Carbon')
@use('Illuminate\Support\Str')
<section>
    <div class="border-b border-gray-800 dark:border-gray-200">
        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">{{ __('Recent Activity') }}</h3>
    </div>

    <div class="space-y-2 mt-2">
        @php($changeable_link = 'change.partials.todo.this')
        @php($changed_fields = [])
        @foreach ($todo->changes as $change)
            @php($change->changeable = $todo)
            <x-recent-activity.item :id="$change->id">
                <x-slot:time>
                    <p><span x-data="{{ Js::from(['start_at' => localDate($change->created_at)]) }}" x-bind:title="dateTimeString(start_at)"
                            class="cursor-help">{{ ucfirst(localDate($change->created_at)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS])) }}</span>
                    </p>
                </x-slot:time>

                @foreach ($change->fields as $field)
                    @php($field->change = $change)
                    @can('view', $field)
                        @unless ($changed_fields[$field->name] ?? false)
                            @include('change.partials.field')
                        @endunless
                        @php($changed_fields[$field->name] = true)
                    @endcan
                @endforeach
            </x-recent-activity.item>
        @endforeach
        <div>
            <p><span x-data="{{ Js::from(['start_at' => localDate($todo->created_at)]) }}" x-bind:title="dateTimeString(start_at)"
                    class="cursor-help">{{ ucfirst(localDate($todo->created_at)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS])) }}</span>
            </p>
            <div class="border-l-2 ml-2 pl-2">{{ __('This task was created.') }}</div>
        </div>
    </div>
</section>
