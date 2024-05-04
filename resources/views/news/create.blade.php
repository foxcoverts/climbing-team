<x-layout.app :title="__('Add News')">
    <section x-data="{ submitted: false }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Add News') }}
                </h1>
            </div>
        </header>

        <form method="post" action="{{ route('news.store') }}" id="create-news" class="p-4 sm:px-8" x-data="{
            post: {{ Js::from([
                'date' => Carbon\Carbon::now()->format('Y-m-d'),
                'title' => old('title', 'Untitled'),
                'slug' => old('slug'),
                'author_id' => old('author_id', $currentUser->id),
                'body' => old('body'),
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
        }"
            x-on:submit="setTimeout(() => submitted = 'create-news', 0)">
            @csrf

            <div class="space-y-6 max-w-prose">
                <div>
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" x-model="post.title" required class="mt-1 w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <div>
                    <x-input-label for="slug" :value="__('Slug')" />
                    <div class="flex items-stretch mt-1">
                        <x-fake-input class="rounded-r-none border-r-0">/news/</x-fake-input>
                        <x-text-input id="slug" name="slug" x-model.fill="post.slug" required pattern="[\-\w_]+"
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

        <footer class="pb-4 px-4 sm:px-8 flex flex-wrap items-center gap-4">
            <x-button.primary class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Save')"
                x-text="submitted == 'create-news' ? '{{ __('Please wait...') }}' : '{{ __('Save') }}'"
                form="create-news" />

            @can('viewAny', App\Models\NewsPost::class)
                <x-button.secondary :href="route('news.index')" :label="__('Back')" />
            @endcan
        </footer>
    </section>
</x-layout.app>
