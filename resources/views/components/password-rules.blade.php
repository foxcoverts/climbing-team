@props(['errors' => []])
@php($rules = array_unique(array_merge(App\Rules\Password::default()->getRules(), $errors)))
<ul {{ $attributes->class(['text-sm text-blue-600 dark:text-blue-400 space-y-1']) }}>
    @foreach ($rules as $rule)
        <li @class([
            'text-red-600 dark:text-red-400' =>
                count($errors) && in_array($rule, $errors),
            'text-green-600 dark:text-green-400' =>
                count($errors) && !in_array($rule, $errors),
        ])>{{ $rule }}</li>
    @endforeach
</ul>
