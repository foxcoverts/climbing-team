{{ html()->p()->class('py-4')->open() }}
{{ html()->a(route('user.show', $user), $user->name)->class(['block', 'px-6']) }}
{{ html()->p()->close() }}