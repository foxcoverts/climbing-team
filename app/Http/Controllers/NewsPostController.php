<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::guest()) {
            return view('news.preview', [
                'post' => $post,
            ]);
        }

        Gate::authorize('view', $post);

        return view('news.show', [
            'post' => $post,
        ]);
    }
}
