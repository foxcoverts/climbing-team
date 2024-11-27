@props(['text' => $slot])
{!! str($text)->markdown([
    'renderer' => [
        'block_separator' => "\n",
        'inner_separator' => "\n",
        'soft_break' => '<br \\>',
    ],
    'html_input' => 'strip',
    'allow_unsafe_links' => false,
])->sanitizeHtml() !!}
