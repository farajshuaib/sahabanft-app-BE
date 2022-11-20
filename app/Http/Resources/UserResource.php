<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request): array
    {
        $currentUser = User::where('id', $this->id)->first();
        return [
            'id' => $this->id,
            'first_name' => $this->first_name ?? "",
            'last_name' => $this->last_name ?? "",
            'username' => $this->username ?? "",
            'email' => $this->email ?? "",
            'bio' => $this->bio ?? "",
            'wallet_address' => $this->wallet_address,
            'profile_photo' => !!$this->getFirstMedia('users_profile') ? $this->getFirstMedia('users_profile')->getUrl() : null,
            'banner_photo' => !!$this->getFirstMedia('users_banner') ? $this->getFirstMedia('users_banner')->getUrl() : null,
            'created_nfts_count' => $this->whenLoaded('created_nfts_count'),
            'owned_nfts_count' => $this->whenLoaded('owned_nfts_count'),
            'collections_count' => $this->whenLoaded('collections_count'),
            'followings_count' => $this->whenLoaded('followings_count'),
            'followers_count' => $this->whenLoaded('followers_count'),
            'is_subscribed' => $this->subscribe()->exists(),
            'kyc_form' => $this->whenLoaded('kyc'),
            "status" => $this->status,
            'social_links' => SocialLinkResource::make($this->whenLoaded('socialLinks')),
            $this->mergeWhen(auth()->check() && (!auth()->user()->hasRole('admin') || !auth()->user()->hasRole('super-admin')), function () use ($currentUser) {
                return ['is_followed' => auth()->user()->isFollowing($currentUser)];
            }),
        ];
    }
}
