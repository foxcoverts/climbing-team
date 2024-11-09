<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsPostRequest;
use App\Http\Requests\UpdateNewsPostRequest;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\RedirectResponse;

class NewsPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', NewsPost::class);

        $posts = NewsPost::orderByDesc('created_at')->with('author')->get();

        return view('news.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', NewsPost::class);

        return view('news.create', [
            'currentUser' => $request->user(),
            'authors' => User::ordered()->select('id', 'name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsPostRequest $request)
    {
        Gate::authorize('create', NewsPost::class);

        $post = NewsPost::create($request->validated());

        return redirect()->route('news.show', $post)
            ->with('alert.message', __('News added.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(NewsPost $post): View
    {
        if (auth()->guest()) {
            return view('news.preview', [
                'post' => $post,
            ]);
        }

        Gate::authorize('view', $post);

        return view('news.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewsPost $post): View
    {
        Gate::authorize('update', $post);

        return view('news.edit', [
            'post' => $post,
            'authors' => User::ordered()->select(['id', 'name'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsPostRequest $request, NewsPost $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $post->update($request->validated());

        return redirect()->route('news.show', $post)
            ->with('alert.message', __('News updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsPost $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('news.index')
            ->with('alert.message', __('News deleted.'));
    }
}
