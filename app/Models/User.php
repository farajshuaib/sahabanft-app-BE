<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\QueryFilters\Users\Search;
use App\QueryFilters\Users\SortBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pipeline\Pipeline;
use Laravel\Sanctum\HasApiTokens;
use Overtrue\LaravelFollow\Traits\Followable;
use Overtrue\LaravelFollow\Traits\Follower;
use Overtrue\LaravelLike\Traits\Liker;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, Liker, HasRoles, Follower, Followable, InteractsWithMedia, SoftDeletes;

    protected $guarded = [];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    public static function withFilters()
    {
        return app(Pipeline::class)
            ->send(User::query())
            ->through([
                Search::class,
                SortBy::class,
            ])
            ->thenReturn()
            ->withCount('created_nfts')
            ->withCount('owned_nfts')
            ->with(['collections', 'followers', 'followings', 'likes.likeable', 'followables.follower'])
            ->orderBy('id', 'DESC')
            ->paginate(15);
    }

    public function guardName()
    {
        return 'web';
    }
    

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_collaborators', 'user_id', 'collection_id');
    }

    public function created_nfts(): HasMany
    {
        return $this->hasMany(Nft::class, 'creator_id');
    }

    public function owned_nfts(): HasMany
    {
        return $this->hasMany(Nft::class, 'owner_id');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function watching(): HasMany
    {
        return $this->hasMany(Watch::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('users_profile')->singleFile();
    }

    public function subscribe(): HasOne
    {
        return $this->hasOne(Subscribe::class);
    }

    public function kyc(): HasOne
    {
        return $this->hasOne(Kyc::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function scopeIsEnabled($query)
    {
        return $query->where('status', 'enabled');
    }

}
