<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */


    public function toArray($request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'logo_image' => $this->getFirstMedia('collection_logo_image') ? $this->getFirstMedia('collection_logo_image')->getUrl() : null,
            'banner_image' => $this->getFirstMedia('collection_banner_image') ? $this->getFirstMedia('collection_banner_image')->getUrl() : null,
            'website_url' => $this->website_url,
            'facebook_url' => $this->facebook_url,
            'twitter_url' => $this->twitter_url,
            'instagram_url' => $this->instagram_url,
            'telegram_url' => $this->telegram_url,
            'is_sensitive_content' => $this->is_sensitive_content,
            'collection_token_id' => $this->collection_token_id,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'nfts' => NftResource::collection($this->whenLoaded('nfts')),
            'created_by' => UserResource::make($this->whenLoaded('user')),
            'nfts_count' => $this->nfts()->count(),
            'volume' => $this->nfts()->sum('price'),
            'min_price' => $this->nfts()->min('price'),
            'max_price' => $this->nfts()->max('price'),
        ];
    }
}
