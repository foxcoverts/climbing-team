<x-mail::message>

# @lang('Incident Report')
{{-- new line --}}

**@lang('Date')**<br>
{{ $incident->dateTime->toDayDateTimeString() }}
{{-- new line --}}

**@lang('Location')**<br>
{{ $incident->locationName }}<br>
{{ $incident->locationDescription }}
{{-- new line --}}

**@lang('Was anyone injured?')**<br>
@lang($incident->injured ? 'Yes' : 'No')
{{-- new line --}}

@if ($incident->injured)
# @lang('Who was injured?')
@else
# @lang('Who was involved in this incident?')
@endif
{{-- new line --}}

@if ($incident->hasInjuredName())
**@lang('Name')**<br>
{{ $incident->injuredName }}
{{-- new line --}}
@endif

@if ($incident->hasInjuredDateOfBirth())
**@lang('Date of Birth')**<br>
{{ $incident->injuredDateOfBirth->toFormattedDateString() }}
{{-- new line --}}
@endif

@if ($incident->hasInjuredGender())
**@lang('Gender')**<br>
@lang($incident->injuredGender->value)
{{-- new line --}}
@endif

**@lang('Membership Type')**<br>
@lang($incident->membershipType->value)
{{-- new line --}}

@if ($incident->hasGroupName())
**@lang('Group Name')**<br>
{{ $incident->groupName }}
{{-- new line --}}
@endif

**@lang('Contact Name')**<br>
{{ $incident->contactName }}
{{-- new line --}}

@if ($incident->hasContactPhone())
**@lang('Contact Phone')**<br>
{{ $incident->contactPhone->formatForCountry('GB') }}
{{-- new line --}}
@endif

@if ($incident->hasContactAddress())
**@lang('Contact Address')**<br>
{{ $incident->contactAddress }}
{{-- new line --}}
@endif

# @lang('Details of accident, injury or near miss')
{{-- new line --}}

@if ($incident->hasInjuries())
**@lang('Injuries')**

@foreach ($incident->injuries as $injury)
- @lang($injury->value)
@endforeach
{{-- new line --}}
@endif

@if ($incident->hasEmergencyServices())
**@lang('Did the emergency services attend?')**<br>
@lang($incident->emergencyServices ? 'Yes' : 'No')
{{-- new line --}}
@endif

@if ($incident->hasHospital())
**@lang('Did the injured person go directly to hospital for treatment?')**<br>
@lang($incident->hospital ? 'Yes' : 'No')
{{-- new line --}}
@endif

@if ($incident->isRIDDOR())
<x-mail::panel>
**@lang('RIDDOR Reportable Incident')**<br>
@lang('From the injuries selected it is likely that this incident needs to be reported under the Reporting of Injuries, Diseases and Dangerous Occurrences Regulations (RIDDOR).')
</x-mail::panel>
@endif

**@lang('Was any property or equipment damaged?')**<br>
@lang($incident->damaged ? 'Yes' : 'No')
{{-- new line --}}

**@lang('What happened?')**<br>
<x-markdown>
{{ $incident->details }}
</x-markdown>

@if ($incident->hasFirstAid())
**@lang('What first aid was performed?')**<br>
<x-markdown>
{{ $incident->firstAid }}
</x-markdown>
@endif

# @lang('Your contact details')
{{-- new line --}}

**@lang('Reporter Name')**<br>
{{ $incident->reporterName }}

**@lang('Reporter Email')**<br>
{{ $incident->reporterEmail }}

**@lang('Reporter Phone')**<br>
{{ $incident->reporterPhone->formatForCountry('GB') }}

</x-mail::message>
