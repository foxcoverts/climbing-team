<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    /**
     * Display a listing of the document.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Document::class);

        return view('document.index', [
            'documents' => Document::orderBy('title')->get(),
        ]);
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(): View
    {
        Gate::authorize('create', Document::class);

        $categories = Document::distinct()
            ->orderBy('category')->get(['category'])->pluck('category');

        return view('document.create', [
            'category_suggestions' => $categories,
            'document' => new Document,
        ]);
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        Gate::authorize('create', Document::class);

        $file = $request->file('file');

        $document = new Document($request->safe()->except('file'));
        $document->file_path = $file->store('upload/document');
        $document->save();

        return redirect()->route('document.index')
            ->with('alert.message', __('Document uploaded.'));
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document): StreamedResponse
    {
        Gate::authorize('view', $document);

        return Storage::response($document->file_path, $document->file_name);
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document): View
    {
        Gate::authorize('update', $document);

        $categories = Document::distinct()
            ->orderBy('category')->get(['category'])->pluck('category');

        return view('document.edit', [
            'category_suggestions' => $categories,
            'document' => $document,
        ]);
    }

    /**
     * Update the specified document in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        Gate::authorize('update', $document);

        $file = $request->file('file');

        $document->fill($request->safe()->except('file'));
        if (! is_null($file)) {
            $document->file_path = $file->store('upload/document');
        }
        $document->save();

        return redirect()->route('document.index')
            ->with('alert.message', __('Document updated.'));
    }

    /**
     * Remove the specified document from view.
     */
    public function destroy(Document $document): RedirectResponse
    {
        Gate::authorize('delete', $document);

        $document->delete();

        return redirect()->route('document.index')
            ->with('alert', [
                'message' => __('Document deleted.'),
                'restore' => route('trash.document.update', $document),
            ]);
    }
}
