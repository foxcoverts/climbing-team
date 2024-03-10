<x-layout.app :title="__('Mail')">
    <section class="p-4 sm:p-8 space-y-4">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium">@lang('Mail')</h2>
        </header>

        @if ($mail->isValid())
            <div class="space-y-2 my-2 w-full flex-grow">
                <p><dfn class="block not-italic font-medium after:content-[':']">@lang('Sent')</dfn>
                    <x-text-input name="sent_at" :value="localDate($mail->sent_at)" type="datetime-local" readonly />
                <p><dfn class="block not-italic font-medium after:content-[':']">@lang('To')</dfn>
                    <x-text-input name="to" :value="$mail->to" class="w-full" readonly />
                </p>
                <p><dfn class="block not-italic font-medium after:content-[':']">@lang('From')</dfn>
                    @if ($mail->fromUser)
                        <x-fake-input class="w-full">
                            <a class="flex-grow flex gap-1 items-center text-black hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                href="{{ route('user.show', $mail->fromUser) }}">
                                <x-icon.user-solid-square class="w-5 h-5 fill-current" />
                                <span>{{ $mail->fromUser->name }} &lt;{{ $mail->fromUser->email }}&gt;</span>
                            </a>
                        </x-fake-input>
                    @else
                        <x-text-input name="from" :value="$mail->from" class="w-full" readonly />
                    @endif
                </p>
                <p><dfn class="block not-italic font-medium after:content-[':']">@lang('Subject')</dfn>
                    <x-text-input name='subject' :value="$mail->subject" class="w-full" readonly />
                </p>
                <div x-data="{
                    body: {{ json_encode($mail->bodyHtml) }},
                    init() {
                        this.$refs.body.contentWindow.document.open('text/html', 'replace');
                        this.$refs.body.contentWindow.document.write(this.body);
                        this.$refs.body.contentWindow.document.close();
                    },
                }">
                    <dfn class="block not-italic font-medium after:content-[':']">@lang('Message')</dfn>
                    <iframe
                        class="w-full h-dvh border border-gray-300 dark:border-gray-700 bg-white rounded-md shadow-sm"
                        x-ref="body" src="about:blank"></iframe>
                </div>
            </div>
        @else
            <div class="space-y-2 my-2 w-full flex-grow">
                <h3 class="text-xl font-medium">@lang('Error')</h3>
                <p>@lang('This does not appear to be an email sent by the ForwardEmail system. You should delete this email.')</p>
            </div>
        @endif

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
