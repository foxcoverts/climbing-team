<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use App\Repositories\NewsPostRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NewsPostController extends Controller
{
    public function __construct(
        public NewsPostRepository $posts,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', NewsPost::class);

        $posts = $this->posts->all();

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
}
