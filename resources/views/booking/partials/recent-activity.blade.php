@if ($booking->changes->count())
    <h3 class="text-xl font-medium">@lang('Recent Activity')</h3>

    <div class="space-y-2">
        @foreach ($booking->changes as $change)
            <div id="{{ $change->id }}">
                <p>{{ localDate($change->created_at)->ago() }}</p>
                @foreach ($change->comments as $comment)
                    <div class="border-l-2 ml-2 pl-2" id="{{ $comment->id }}">
                        <div><a href="{{ route('user.show', $change->author) }}"
                                class="font-medium">{{ $change->author->name }}</a> commented:</div>
                        <p class="border-l-2 ml-2 pl-2">{{ $comment->body }}</p>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endif
