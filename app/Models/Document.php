<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'category',
        'description',
        'file_name',
        'file_path',
    ];

    protected static function booted(): void
    {
        self::updated(function (Document $model) {
            if ($model->wasChanged('file_path')) {
                Storage::disk('local')->delete($model->getOriginal('file_path'));
            }
        });

        self::forceDeleted(function (Document $model) {
            Storage::disk('local')->delete($model->file_path);
        });
    }
}
