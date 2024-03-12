<x-layout.app :title="__('Mail')">
    <section class="p-4 sm:p-8 space-y-4">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium">@lang('Mail')</h2>
        </header>

        @if ($mail->isValid())
            <div class="space-y-2 my-2 w-full flex-grow">
                @if ($mail->calendar)
                    <h3 class="text-xl font-medium">@lang('Calendar')</h3>

                    <div>
                        <x-input-label for="method">@lang('Method')</x-input-label>
                        <x-text-input id="method" name="method" :value="$mail->calendar->getMethod()" class="w-full mt-1" />
                    </div>

                    @foreach ($mail->calendar->getEvents() as $event)
                        <div>
                            <x-input-label for="sent_at">@lang('Sent')</x-input-label>
                            <x-text-input id="sent_at" name="sent_at" :value="localDate($event->getSentAt())" type="datetime-local"
                                class="mt-1" readonly />
                        </div>

                        <div>
                            @if ($booking = $event->getBooking())
                                <dfn class="block not-italic font-medium">@lang('Booking')</dfn>
                                <x-fake-input class="w-full mt-1">
                                    <a class="flex-grow flex gap-1 items-center text-black hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                        href="{{ route('booking.show', $booking) }}">
                                        <x-icon.calendar class="w-5 h-5 fill-current" />
                                        <span>{{ $booking->activity }} -
                                            {{ localDate($booking->start_at)->toFormattedDayDateString() }}</span>
                                    </a>
                                </x-fake-input>
                            @else
                                <x-input-label for="to">@lang('Booking')</x-input-label>
                                <x-text-input id="to" name="to" :value="$event->getUid()" class="w-full mt-1"
                                    readonly />
                            @endif
                        </div>

                        @foreach ($event->getAttendees() as $attendee)
                            <div x-id="['attendee']">
                                @if ($user = $attendee->getUser())
                                    <dfn class="block not-italic font-medium">@lang('Attendee')</dfn>
                                    <x-fake-input class="w-full mt-1">
                                        <a class="flex-grow flex gap-1 items-center text-black hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                            href="{{ route('user.show', $user) }}">
                                            <x-icon.user-solid-square class="w-5 h-5 fill-current" />
                                            <span>{{ $user->name }} &lt;{{ $user->email }}&gt;</span>
                                        </a>
                                    </x-fake-input>
                                @else
                                    <x-input-label :for="$id('attendee')">@lang('Attendee')</x-input-label>
                                    <x-text-input :id="$id('attendee')" name="attendee[]" :value="$attendee->getEmail()"
                                        class="w-full mt-1" readonly />
                                @endif
                            </div>

                            <div x-id="['attendee_status']">
                                <x-input-label ::for="$id('attendee_status')">@lang('Status')</x-input-label>
                                <x-text-input ::id="$id('attendee_status')" name="attendee[][status]" :value="__('app.attendee.status.' . $attendee->getStatus()->value)"
                                    class="w-full mt-1" readonly />
                            </div>

                            @if ($attendee->getComment())
                                <div x-id="['attendee_comment']">
                                    <x-input-label ::for="$id('attendee_comment')">@lang('Comment')</x-input-label>
                                    <x-text-input ::id="$id('attendee_comment')" name="attendee[][comment]" :value="$attendee->getComment()"
                                        class="w-full mt-1" readonly />
                                </div>
                            @endif
                        @endforeach
                    @endforeach

                    <div><dfn class="block not-italic font-medium">@lang('Raw')</dfn>
                        <x-textarea name="ics" class="w-full mt-1">{{ $mail->calendar->getRaw() }}</x-textarea>
                    </div>
                @else
                    <div><dfn class="block not-italic font-medium">@lang('Sent')</dfn>
                        <x-text-input name="sent_at" :value="localDate($mail->sent_at)" type="datetime-local" class="mt-1" readonly />
                    </div>

                    <div><dfn class="block not-italic font-medium">@lang('To')</dfn>
                        @if ($mail->toBooking)
                            <x-fake-input class="w-full">
                                <a class="flex-grow flex gap-1 items-center text-black hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                    href="{{ route('booking.show', $mail->toBooking) }}">
                                    <x-icon.calendar class="w-5 h-5 fill-current" />
                                    <span>{{ $mail->toBooking->activity }} -
                                        {{ localDate($mail->toBooking->start_at)->toFormattedDayDateString() }}</span>
                                </a>
                            </x-fake-input>
                        @else
                            <x-text-input name="to" :value="$mail->to" class="w-full mt-1" readonly />
                        @endif
                    </div>

                    <div><dfn class="block not-italic font-medium">@lang('From')</dfn>
                        @if ($mail->fromUser)
                            <x-fake-input class="w-full">
                                <a class="flex-grow flex gap-1 items-center text-black hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                    href="{{ route('user.show', $mail->fromUser) }}">
                                    <x-icon.user-solid-square class="w-5 h-5 fill-current" />
                                    <span>{{ $mail->fromUser->name }} &lt;{{ $mail->fromUser->email }}&gt;</span>
                                </a>
                            </x-fake-input>
                        @else
                            <x-text-input name="from" :value="$mail->from" class="w-full mt-1" readonly />
                        @endif
                    </div>

                    <div><dfn class="block not-italic font-medium">@lang('Subject')</dfn>
                        <x-text-input name='subject' :value="$mail->subject" class="w-full mt-1" readonly />
                    </div>

                    <div x-data="{
                        body: {{ json_encode($mail->bodyHtml) }},
                        init() {
                            this.$refs.body.contentWindow.document.open('text/html', 'replace');
                            this.$refs.body.contentWindow.document.write(this.body);
                            this.$refs.body.contentWindow.document.close();
                        },
                    }">
                        <dfn class="block not-italic font-medium">@lang('Message')</dfn>
                        <iframe
                            class="w-full h-dvh border border-gray-300 dark:border-gray-700 bg-white rounded-md shadow-sm"
                            x-ref="body" src="about:blank"></iframe>
                    </div>
                @endif
            </div>
        @else
            <div class="space-y-2 my-2 w-full flex-grow">
                <h3 class="text-xl font-medium">@lang('Error')</h3>
                <p>@lang('This does not look like an encoded email.')</p>
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
