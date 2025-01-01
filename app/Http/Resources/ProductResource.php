<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store->id,
            'price' => $this->price,
            'translations' => $this->translations->keyBy('language')->map(function ($translation) {
                return [
                    'name' => $translation->name,
                    'description' => $translation->description,
                ];
            }),
            'icon_url' => $this->icon ? asset('storage/'.$this->icon) : null,
            'tags' => TagResource::collection($this->tags),

            /* 'created_at' => $this->created_at->toDateTimeString(), */
            /* 'updated_at' => $this->updated_at->toDateTimeString(), */
        ];
    }
}
