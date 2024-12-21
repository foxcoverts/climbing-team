<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Contracts\View\View;
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
     * Display the specified document.
     */
    public function show(Document $document): StreamedResponse
    {
        Gate::authorize('view', $document);

        return Storage::response($document->file_path, $document->file_name);
    }
}
