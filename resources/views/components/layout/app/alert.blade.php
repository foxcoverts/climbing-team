@if (session('status'))
    <div class="bg-blue-100 mb-2 sm:mb-0 border-blue-200 text-black px-4 py-3 flex items-center"
        :class="{ 'hidden': !alertOpen }" role="alert">
        <p class="text-sm flex-grow">
            {{ session('status') }}

            @if (session('restore'))
                <form action="{{ session('restore') }}" method="post">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="deleted_at" value="0">
                    <button class="text-gray-400 hover:text-gray-900 underline">{{ __('Undo') }}</button>
                </form>
            @endif
        </p>

        <svg class="fill-current h-6 w-6 text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20" @click="alertOpen = false">
            <title>Close</title>
            <path
                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
        </svg>
    </div>
@endif
