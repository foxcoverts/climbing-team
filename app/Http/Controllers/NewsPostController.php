<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNewsPostRequest;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

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
            'authors' => User::orderBy('name')->select(['id', 'name'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsPostRequest $request, NewsPost $post)
    {
        Gate::authorize('update', $post);

        $post->update($request->validated());

        return redirect()->route('news.show', $post)
            ->with('alert.message', __('News updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsPost $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('news.index')
            ->with('alert.message', __('News deleted.'));
    }
}
