@props(['disabled' => false, 'type' => 'password', 'copy' => false])

<div x-modelable="text" x-data="{
    text: '',
    show: false,
    copied: false,
    timeout: null,
    copy() {
        $clipboard(this.text);
        this.copied = true;
        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => {
            this.copied = false;
        }, 3000);
    },
}"
    {{ $attributes->only('x-model', 'class')->class(['flex items-stretch']) }}>
    <input x-model="text" x-bind:type="show ? 'text' : 'password'" @disabled($disabled) {!! $attributes->except('class', 'x-model')->merge([
        'class' =>
            'flex-grow border-gray-300 disabled:cursor-not-allowed disabled:text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:disabled:text-gray-600 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm rounded-l-md',
        'style' => 'color-scheme: light dark',
    ]) !!}>
    <button type="button" @class([
        'px-2 outline-none border border-l-0 border-gray-300 disabled:cursor-not-allowed disabled:text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:disabled:text-gray-600 hover:text-indigo-500 dark:hover:text-indigo-600 focus:ring-1 focus:border-indigo-500 dark:focus:border-indigo-600 focus:text-indigo-500 dark:focus:text-indigo-600 active:text-indigo-600 dark:active:text-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm',
        'rounded-r-md' => !$copy,
    ]) @click="show = !show" @disabled($disabled)>
        <x-icon.view-hide class="w-5 h-5 fill-current" x-cloak x-show="show" />
        <x-icon.view-show class="w-5 h-5 fill-current" x-show="!show" />
    </button>
    @if ($copy)
        <button type="button"
            class="px-2 outline-none border border-l-0 border-gray-300 disabled:cursor-not-allowed disabled:text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:disabled:text-gray-600 hover:text-indigo-500 dark:hover:text-indigo-600 focus:ring-1 focus:border-indigo-500 dark:focus:border-indigo-600 focus:text-indigo-500 dark:focus:text-indigo-600 active:text-indigo-600 dark:active:text-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm rounded-r-md"
            @click="copy" @disabled($disabled) x-cloak>
            <x-icon.clipboard class="w-5 h-5 fill-current" x-show="!copied" />
            <x-icon.clipboard.check class="w-5 h-5 fill-gray-400 dark:fill-gray-600" x-show="copied" />
        </button>
    @endif
</div>
