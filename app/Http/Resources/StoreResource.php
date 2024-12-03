<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'site' => $this->site,
            'translations' => $this->translations->map(function ($translation) {
                return [
                    'language' => $translation->language,
                    'name' => $translation->name,
                    'description' => $translation->description,
                ];
            }),
            'tags' => $this->tags,
            'icon_url' => $this->icon ? asset('storage/' . $this->icon) : null,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}

