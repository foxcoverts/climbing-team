@use('App\Enums\AttendeeStatus')
@props(['attendance' => null])
@switch($attendance)
    @case(AttendeeStatus::Declined)
        <x-icon.close-outline class="h-4 w-4 fill-current" />
    @break

    @case(null)
        <x-icon.question-outline class="h-4 w-4 fill-current" />
    @break

    @default
        <x-icon.checkmark-outline class="h-4 w-4 fill-current" />
@endswitch
