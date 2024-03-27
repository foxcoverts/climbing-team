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
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return collect($this->filesystem->files())
            ->map(fn ($filepath) => $this->load($filepath))
            ->filter();
    }

    /**
     * Attempt to find the named NewsPost.
     *
     * @param string $filename
     * @throws ModelNotFoundException
     * @return NewsPost
     */
    public function find(string $filename): NewsPost
    {
        $post = $this->load($filename . '.md');

        if (is_null($post)) {
            dd("$filename.md");
            throw new ModelNotFoundException;
        }
        return $post;
    }

    protected function load(string $filepath): ?NewsPost
    {
        if ($this->filesystem->mimeType($filepath) != 'text/markdown') return null;

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
