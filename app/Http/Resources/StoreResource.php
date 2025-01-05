<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'site' => new SiteResource($this->site),
            'translations' => $this->translations->keyBy('language')->map(function ($translation) {
                return [
                    'name' => $translation->name,
                    'description' => $translation->description,
                ];
            }),
            'tags' => TagResource::collection($this->tags),
            'icon_url' => $this->icon ? asset('storage/'.$this->icon) : null,
            /* 'created_at' => $this->created_at->toDateTimeString(), */
            /* 'updated_at' => $this->updated_at->toDateTimeString(), */
        ];
    }
}
