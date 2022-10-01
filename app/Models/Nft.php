<?php

namespace App\Models;

use App\QueryFilters\Nfts\Search;
use App\QueryFilters\Nfts\Type;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;
use Overtrue\LaravelLike\Traits\Likeable;

class Nft extends Model
{
    use Likeable, HasFactory, SoftDeletes;


    protected $fillable = ['title', 'description', 'collection_id', 'creator_id', 'owner_id', 'file_path', 'price', 'is_for_sale', 'sale_end_at', 'file_type', 'token_id'];

    protected $casts = ['is_for_sale' => 'boolean',
        'price' => 'float',
    ];

    public static function withFilters()
    {
        return app(Pipeline::class)
            ->send(Nft::query())
            ->through([
                Search::class,
                Type::class,
                \App\QueryFilters\Nfts\Collection::class,
                \App\QueryFilters\Nfts\Category::class,
            ])
            ->thenReturn()
            ->with('collection', 'creator', 'owner')
            ->orderBy('id', 'DESC');

    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }


    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function category(): HasOneThrough
    {
        return $this->hasOneThrough(Collection::class, Category::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class,);
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function scopeIsPublished($query): Builder
    {
        return $query->where('status', 'published');
    }

}
