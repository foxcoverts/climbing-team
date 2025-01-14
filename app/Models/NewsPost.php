<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NewsPost extends Model
{
    use HasFactory, HasUlids, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'author_id',
        'body',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function summary(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->markdown)) {
                    return '';
                }

                $doc = new \DOMDocument;
                $doc->loadHTML($this->markdown);

                return $doc->saveHTML($doc->getElementsByTagName('p')->item(0));
            },
        );
    }

    protected function summaryText(): Attribute
    {
        return Attribute::make(
            get: fn () => strip_tags($this->summary),
        );
    }

    protected function markdown(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::markdown($this->body, [
                'renderer' => [
                    'block_separator' => "\n",
                    'inner_separator' => "\n",
                    'soft_break' => '<br \\>',
                ],
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]),
        );
    }
}
