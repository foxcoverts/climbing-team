<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTrashedDocumentRequest;
use App\Http\Requests\RestoreTrashedDocumentRequest;
use App\Models\Document;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TrashedDocumentController extends Controller
{
    /**
     * Display a listing of the document.
     */
    public function index()
    {
        Gate::authorize('viewTrashed', Document::class);

        $documents = Document::onlyTrashed()->orderBy('title')->get();

        return view('document.trashed.index', [
            'documents' => $documents,
        ]);
    }

    /**
     * Display the document.
     */
    public function show(Document $document): StreamedResponse
    {
        Gate::authorize('view', $document);

        return Storage::response($document->file_path, $document->file_name);
    }

    /**
     * Update the specified document in storage.
     */
    public function update(RestoreTrashedDocumentRequest $request, Document $document)
    {
        Gate::authorize('restore', $document);

        $document->restore();

        return redirect()->route('trash.document.index')
            ->with('alert.message', __('Document restored.'));
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(DestroyTrashedDocumentRequest $request, Document $document)
    {
        Gate::authorize('forceDelete');

        $file_path = $document->file_path;
        $document->forceDelete();
        Storage::delete($file_path);

        return redirect()->route('trash.document.index')
            ->with('alert.message', __('Document permanently deleted.'));
    }
}
