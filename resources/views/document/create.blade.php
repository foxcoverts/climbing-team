<x-layout.app :title="__('Upload Document')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Upload Document') }}
                </h1>
            </div>
        </header>

        <div class="p-4 sm:px-8">
            <form method="POST" action="{{ route('document.store') }}" enctype="multipart/form-data"
                x-data="{
                    submitted: false,
                    form: {{ Js::from([
                        'title' => old('title', $document->title),
                        'category' => old('category', $document->category),
                        'description' => old('description', $document->description),
                        'file' => new stdClass(),
                        'file_name' => old('file_name', $document->file_name),
                    ]) }},
                    fileChanged(event) {
                        if (this.form.title == (this.form.file.name || '').replace(/\..+$/, '')) {
                            this.form.title = '';
                        }
                
                        this.form.file = event.target.files[0] || {};
                        this.form.file_name = this.form.file.name.replace(/[^\w\-. ]/g, '');
                        if (!this.form.title) {
                            this.form.title = this.form.file.name.replace(/\..+$/, '');
                        }
                    },
                }" x-on:submit="setTimeout(() => submitted = true, 0)">
                @csrf
                <div class="max-w-prose space-y-6">
                    <div class="space-y-1">
                        <x-input-label for="file" :value="__('File')" />
                        <x-file-input id="file" name="file" class="block" required autofocus
                            accept=".pdf,application/pdf" x-on:change="fileChanged" />
                        <input type="hidden" name="file_size" x-model="form.file.size" />
                        <input type="hidden" name="file_type" x-model="form.file.type" />
                        <x-input-error class="mt-2" :messages="$errors->get('file')" />
                    </div>

                    <div class="space-y-1" x-cloak x-show="form.file_name">
                        <x-input-label for="file_name" :value="__('Filename')" />
                        <x-text-input id="file_name" name="file_name" required pattern="[\w\-. ]+"
                            title="Letters, numbers, hyphens (-), periods (.), and spaces only" class="block w-3/4"
                            x-model="form.file_name" x-bind:disabled="!form.file_name" />
                        <p class="text-sm">
                            {{ __('This is the filename that a user will see when they download the document.') }}</p>
                        <x-input-error class="mt-2" :messages="$errors->get('file_name')" />
                    </div>

                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                            maxlength="255" required x-model="form.title" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <datalist id="category-suggestions">
                            @foreach ($category_suggestions as $category)
                                <option>{{ $category }}</option>
                            @endforeach
                        </datalist>
                        <x-input-label for="category" :value="__('Category')" />
                        <x-text-input id="category" name="category" type="text" class="mt-1 block w-full"
                            maxlength="255" required autocomplete="on" list="category-suggestions"
                            x-model="form.category" />
                        <x-input-error class="mt-2" :messages="$errors->get('category')" />
                    </div>

                    <div class="space-y-1">
                        <x-input-label for="description" :value="__('Description')" />
                        <x-textarea id="description" name="description" class="block w-full" x-model="form.description"
                            x-meta-enter.prevent="$el.form.requestSubmit()" />
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>
                </div>

                <footer class="mt-6 flex items-center gap-4">
                    <x-button.primary x-bind:disabled="submitted" :label="__('Upload')"
                        x-text="submitted ? {{ Js::from(__('Please wait...')) }} : {{ Js::from(__('Upload')) }}" />

                    @can('viewAny', App\Models\Document::class)
                        <x-button.secondary :href="route('document.index')" :label="__('Back')" />
                    @endcan
                </footer>
            </form>
        </div>
    </section>
</x-layout.app>
