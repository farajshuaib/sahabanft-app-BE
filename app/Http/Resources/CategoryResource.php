<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CategoryResource extends JsonResource
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
            'icon' => $this->getFirstMedia('category_icon') ? $this->getFirstMedia('category_icon')->getUrl() : null,
            'collections_count' => $this->collections()->count(),
            'nfts_count' => $this->nfts()->count(),
        ];
    }
}
