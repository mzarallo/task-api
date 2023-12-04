<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'tags',
        'order',
        'stage_id',
        'author_id',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Task $model) {
            if (! $model->author_id) {
                $model->author_id = auth()->user()->id ?? null;
            }
        });
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function AuthorFullName(): Attribute
    {
        return new Attribute(get: fn () => "{$this->author->name} {$this->author->last_name}");
    }
}
