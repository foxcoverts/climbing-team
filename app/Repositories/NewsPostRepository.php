<?php

namespace App\Repositories;

use App\Models\NewsPost;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\MarkdownConverter;

class NewsPostRepository
{
    public Filesystem $filesystem;

    public function __construct()
    {
        $this->filesystem = Storage::disk('news');
    }

    /**
     * List all new posts.
     */
    public function all(): Collection
    {
        return collect($this->filesystem->files())
            ->reverse()
            ->map(fn ($filepath) => $this->load($filepath))
            ->filter();
    }

    public function first(): ?NewsPost
    {
        return
            collect($this->filesystem->files())
                ->reverse()
                ->filter(fn ($filepath) => $this->filesystem->mimeType($filepath) == 'text/markdown')
                ->take(1)
                ->map(fn ($filepath) => $this->load($filepath))
                ->first();
    }

    /**
     * Attempt to find the named NewsPost.
     *
     * @throws ModelNotFoundException
     */
    public function find(string $filename): NewsPost
    {
        $post = $this->load($filename.'.md');

        if (is_null($post)) {
            throw new ModelNotFoundException;
        }

        return $post;
    }

    protected function load(string $filepath): ?NewsPost
    {
        if ($this->filesystem->mimeType($filepath) != 'text/markdown') {
            return null;
        }

        $markdown = $this->filesystem->get($filepath);

        $config = [];
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new FrontMatterExtension());
        $converter = new MarkdownConverter($environment);
        $result = $converter->convert($markdown);

        return new NewsPost([
            'filepath' => $filepath,
            'markdown' => $result,
        ]);
    }
}
