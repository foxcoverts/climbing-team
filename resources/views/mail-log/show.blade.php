<x-layout.app :title="__('Mail')">
    <section class="p-4 sm:p-8 max-w-xl space-y-4">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium">@lang('Mail')</h2>
        </header>

        <div class="space-y-2 my-2 w-full max-w-xl flex-grow">
            <p><dfn class="block not-italic font-medium after:content-[':']">@lang('ID')</dfn>
                {{ $mail->id }}</p>
            <p><dfn class="block not-italic font-medium after:content-[':']">@lang('Created')</dfn>
                {{ localDate($mail->created_at) }}</p>
            <div><dfn class="block not-italic font-medium after:content-[':']">@lang('Body')</dfn>
                <div class="font-mono whitespace-pre">{{ $mail->body }}</div>
            </div>
        </div>

        <footer class="flex items-center gap-4">
            @can('delete', $mail)
                <form method="post" action="{{ route('mail.destroy', $mail) }}">
                    @method('DELETE')
                    @csrf
                    <x-button.danger>
                        @lang('Delete')
                    </x-button.danger>
                </form>
            @endcan
            <x-button.secondary href="{{ route('mail.index') }}">
                @lang('Back')
            </x-button.secondary>
        </footer>
    </section>
</x-layout.app>
