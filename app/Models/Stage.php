<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'hex_color',
        'is_final_stage',
        'order',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating( fn ($model) => $model->slug = Str::slug($model->name));
        static::creating( fn ($model) => $model->slug = Str::slug($model->name));
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function AuthorFullName(): Attribute
    {
        return new Attribute(get: fn () => "{$this->author->name} {$this->author->last_name}");
    }
}
