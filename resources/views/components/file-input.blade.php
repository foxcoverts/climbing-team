@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'type' => 'file',
    'class' => 'form-input cursor-default disabled:cursor-not-allowed disabled:text-gray-400 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:disabled:text-gray-600 rounded-md shadow-sm
        file:inline-flex file:-my-1 file:-ml-2 file:py-2 file:mr-4 file:px-4 file:border-0 file:cursor-pointer file:rounded-sm file:font-semibold file:text-xs file:uppercase file:tracking-widest file:transition file:ease-in-out file:duration-150
        file:bg-gray-800 file:dark:bg-gray-200 file:text-white disabled:file:cursor-not-allowed disabled:file:bg-gray-300 disabled:hover:file:bg-gray-300 disabled:dark:file:bg-gray-500 disabled:dark:hover:file:bg-gray-500 dark:file:text-gray-800 hover:file:bg-gray-700 dark:hover:file:bg-white active:file:bg-gray-900 dark:active:file:bg-gray-300',
    'style' => 'color-scheme: light dark',
]) !!}>
