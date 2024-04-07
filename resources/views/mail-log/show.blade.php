<x-layout.app :title="__('Mail')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10 px-4 sm:px-8">
            <div class="max-w-prose flex flex-wrap py-2 min-h-16 items-center justify-between gap-2">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    @lang('Mail')
                </h1>
            </div>
        </header>

        <div class="p-4 sm:px-8">
            @if ($mail->isValid())
                <div class="space-y-2 my-2 w-full max-w-prose flex-grow">
                    @if ($mail->calendar)
                        <h3 class="text-xl font-medium">@lang('Calendar')</h3>

                        <div>
                            <x-input-label for="method" :value="__('Method')" />
                            <x-text-input id="method" name="method" class="w-full mt-1" :value="$mail->calendar->getMethod()" />
                        </div>

                        @foreach ($mail->calendar->getEvents() as $event)
                            <div>
                                <x-input-label for="sent_at" :value="__('Sent')" />
                                <x-text-input id="sent_at" name="sent_at" class="mt-1" readonly
                                    type="datetime-local" :value="localDate($event->getSentAt())" />
                            </div>

                            <div>
                                @if ($booking = $event->getBooking())
                                    <x-fake-label :value="__('Booking')" />
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
                                    <x-text-input id="to" name="to" class="w-full mt-1" :value="$event->getUid()"
                                        readonly />
                                @endif
                            </div>

                            @foreach ($event->getAttendees() as $attendee)
                                <div x-id="['attendee']">
                                    @if ($user = $attendee->getUser())
                                        <x-fake-label :value="__('Attendee')" />
                                        <x-fake-input class="w-full mt-1">
                                            <a class="flex-grow flex gap-1 items-center text-black hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                                href="{{ route('user.show', $user) }}">
                                                <x-icon.user-solid-square class="w-5 h-5 fill-current" />
                                                <span>{{ $user->name }} &lt;{{ $user->email }}&gt;</span>
                                            </a>
                                        </x-fake-input>
                                    @else
                                        <x-input-label ::for="$id('attendee')">@lang('Attendee')</x-input-label>
                                        <x-text-input ::id="$id('attendee')" name="attendee[]" class="w-full mt-1" readonly
                                            :value="$attendee->getEmail()" />
                                    @endif
                                </div>

                                <div x-id="['attendee_status']">
                                    <x-input-label ::for="$id('attendee_status')">@lang('Status')</x-input-label>
                                    <x-fake-input
                                        class="w-full mt-1 flex-grow flex gap-1 items-center text-black dark:text-white">
                                        <x-icon.attendance :attendance="$attendee->getStatus()" class="w-5 h-5 fill-current" />
                                        <span>@lang('app.attendee.status.' . $attendee->getStatus()->value)</span>
                                    </x-fake-input>
                                </div>

                                @if ($attendee->getComment())
                                    <div x-id="['attendee_comment']">
                                        <x-input-label ::for="$id('attendee_comment')">@lang('Comment')</x-input-label>
                                        <x-text-input ::id="$id('attendee_comment')" name="attendee[][comment]" class="w-full mt-1"
                                            readonly :value="$attendee->getComment()" />
                                    </div>
                                @endif
                            @endforeach
                        @endforeach

                        <div>
                            <x-input-label for="ics" :value="__('Raw')" />
                            <x-textarea id="ics" name="ics" class="w-full mt-1"
                                readonly>{{ $mail->calendar->getRaw() }}</x-textarea>
                        </div>
                    @else
                        <div>
                            <x-input-label for="sent_at" :value="__('Sent')" />
                            <x-text-input id="sent_at" name="sent_at" class="mt-1" readonly type="datetime-local"
                                :value="localDate($mail->sent_at)" />
                        </div>

                        <div>
                            @if ($mail->toBooking)
                                <x-fake-label :value="__('To')" />
                                <x-fake-input class="w-full mt-1">
                                    <a class="flex-grow flex gap-1 items-center text-black hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                        href="{{ route('booking.show', $mail->toBooking) }}">
                                        <x-icon.calendar class="w-5 h-5 fill-current" />
                                        <span>{{ $mail->toBooking->activity }} -
                                            {{ localDate($mail->toBooking->start_at)->toFormattedDayDateString() }}</span>
                                    </a>
                                </x-fake-input>
                            @else
                                <x-input-label for="to" :value="__('To')" />
                                <x-text-input id="to" name="to" :value="$mail->to" class="w-full mt-1"
                                    readonly />
                            @endif
                        </div>

                        <div>
                            @if ($mail->fromUser)
                                <x-fake-label :value="__('From')" />
                                <x-fake-input class="w-full mt-1">
                                    <a class="flex-grow flex gap-1 items-center text-black hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                        href="{{ route('user.show', $mail->fromUser) }}">
                                        <x-icon.user-solid-square class="w-5 h-5 fill-current" />
                                        <span>{{ $mail->fromUser->name }} &lt;{{ $mail->fromUser->email }}&gt;</span>
                                    </a>
                                </x-fake-input>
                            @else
                                <x-input-label for="from" :value="__('From')" />
                                <x-text-input id="from" name="from" class="w-full mt-1" readonly
                                    :value="$mail->from" />
                            @endif
                        </div>

                        <div>
                            <x-input-label for="subject" :value="__('Subject')" />
                            <x-text-input id="subject" name="subject" class="w-full mt-1" readonly
                                :value="$mail->subject" />
                        </div>

                        <div x-data="{
                            body: {{ Js::from($mail->bodyHtml) }},
                            init() {
                                this.$refs.body.contentWindow.document.open('text/html', 'replace');
                                this.$refs.body.contentWindow.document.write(this.body);
                                this.$refs.body.contentWindow.document.close();
                            },
                        }">
                            <x-fake-label :value="__('Message')" />
                            <iframe
                                class="w-full mt-1 h-dvh border border-gray-300 dark:border-gray-700 bg-white rounded-md shadow-sm"
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

            <footer class="flex flex-wrap items-center gap-4">
                @can('delete', $mail)
                    <form method="post" action="{{ route('mail.destroy', $mail) }}" x-data="{ submitted: false }"
                        x-on:submit="setTimeout(() => submitted = true, 0)">
                        @method('DELETE')
                        @csrf
                        <x-button.danger x-bind:disabled="submitted" class="whitespace-nowrap"
                            x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Delete') }}'" />
                    </form>
                @endcan
                <x-button.secondary href="{{ route('mail.index') }}">
                    @lang('Back')
                </x-button.secondary>
            </footer>
        </div>

    </section>
</x-layout.app>
