<?php

namespace App\Models;

use App\Repositories\NewsPostRepository;
use Illuminate\Support\Arr;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\Output\RenderedContentInterface;
use Illuminate\Contracts\Routing\UrlRoutable;

/**
 * File-based news system. Some functions added to feel like eloquent.
 */
class NewsPost implements UrlRoutable
{
    protected string $filepath;

    protected RenderedContentInterface $markdown;

    public function __construct(
        array $attributes = []
    ) {
        if (Arr::has($attributes, 'filepath')) {
            $this->filepath = Arr::get($attributes, 'filepath');
        }
        if (Arr::has($attributes, 'markdown')) {
            $this->markdown = Arr::get($attributes, 'markdown');
        }
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        if (Arr::has($this->getAttributes(), $key)) {
            return Arr::get($this->getAttributes(), $key);
        }

        if ($key === 'filename') {
            return pathinfo($this->filepath, PATHINFO_FILENAME);
        }
        if ($key === 'content') {
            return $this->markdown->getContent();
        }

        throw new \DomainException("Cannot get unknown property '$key' on " . get_class($this));
    }

    /**
     * Dynamically check attributes exist on the model.
     *
     * @param string $key
     * @return boolean
     */
    public function __isset(string $key): bool
    {
        return (Arr::has($this->getAttributes(), $key) || $key === 'filename' || $key === 'content');
    }

    /**
     * Get front matter for this news post.
     */
    public function getAttributes(): array
    {
        if ($this->markdown instanceof RenderedContentWithFrontMatter) {
            return $this->markdown->getFrontMatter();
        }
        return [
            'title' => $this->filepath,
        ];
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->filename;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'post';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null): ?static
    {
        return app()->make(NewsPostRepository::class)->find($value);
    }

    /**
     * Retrieve the child model for a bound value.
     *
     * @param  string  $childType
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        return null;
    }
}
