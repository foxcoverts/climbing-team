<x-layout.app :title="__('Edit News')">
    <section x-data="{ submitted: false }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    @lang('Edit News')
                </h1>
            </div>
        </header>

        <form method="post" action="{{ route('news.update', $post) }}" id="update-news" class="p-4 sm:px-8"
            x-data="{
                post: {{ Js::from([
                    'date' => $post->created_at->format('Y-m-d'),
                    'title' => old('title', $post->title),
                    'slug' => old('slug', $post->slug),
                    'author_id' => old('author_id', $post->author_id),
                    'body' => old('body', $post->body),
                ]) }},
                makeSlug(date, title) {
                    var cleanTitle = (title || '')
                        .toLowerCase()
                        .replace(/&/g, ' and ')
                        .replace(/[^\-\w\s]+/g, ' ')
                        .replace(/^\s+|\s+$/g, '');
            
                    return [date, ...(cleanTitle || 'untitled').split(/[\-\s_]+/)]
                        .join('-');
                },
                watchTitle(title, oldTitle) {
                    oldSlug = $data.makeSlug($data.post.date, oldTitle);
                    if ($data.post.slug == oldSlug) {
                        $data.post.slug = $data.makeSlug($data.post.date, title);
                    }
                },
                init() {
                    if (!this.post.slug) {
                        this.post.slug = this.makeSlug(this.post.date, this.post.title);
                    }
                    this.$watch('post.title', this.watchTitle);
                },
            }" x-on:submit="setTimeout(() => submitted = 'update-news', 0)">
            @csrf @method('PATCH')

            <div class="space-y-6 max-w-prose">
                <div>
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" class="mt-1 w-full" x-model="post.title" />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <div>
                    <x-input-label for="slug" :value="__('Slug')" />
                    <div class="flex items-stretch mt-1">
                        <x-fake-input class="rounded-r-none border-r-0">/news/</x-fake-input>
                        <x-text-input id="slug" name="slug" x-model.fill="post.slug" pattern="[\-\w_]+"
                            inputmode="url" class="flex-grow flex-shrink rounded-l-none border-l-0" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                </div>

                <div>
                    <x-input-label for="author_id" :value="__('Author')" />
                    <x-select-input id="author_id" name="author_id" class="mt-1" required x-model="post.author_id">
                        <template x-if="!post.author_id">
                            <option value="" selected></option>
                        </template>
                        <x-select-input.collection :options="$authors" label_key="name" />
                    </x-select-input>
                    <x-input-error class="mt-2" :messages="$errors->get('author_id')" />
                </div>

                <div>
                    <x-input-label for="body" :value="__('Body')" />
                    <x-textarea id="body" name="body" class="mt-1 w-full" required x-model="post.body"
                        x-meta-enter.prevent="$el.form.requestSubmit()" />
                    <x-input-error class="mt-2" :messages="$errors->get('body')" />
                </div>
            </div>
        </form>

        @can('delete', $post)
            <form method="POST" action="{{ route('news.destroy', $post) }}" id="delete-news"
                x-on:submit="setTimeout(() => submitted = 'delete-news', 0)">
                @csrf @method('DELETE')
            </form>
        @endcan

        <footer class="pb-4 px-4 sm:px-8 flex flex-wrap items-center gap-4">
            <x-button.primary class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Save')"
                x-text="submitted == 'update-news' ? '{{ __('Please wait...') }}' : '{{ __('Save') }}'"
                form="update-news" />

            @can('delete', $post)
                <x-button.danger class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Delete')"
                    x-text="submitted == 'delete-news' ? '{{ __('Please wait...') }}' : '{{ __('Delete') }}'"
                    form="delete-news" />
            @endcan

            @can('view', $post)
                <x-button.secondary :href="route('news.show', $post)">
                    @lang('Back')
                </x-button.secondary>
            @endcan
        </footer>
    </section>
</x-layout.app>
