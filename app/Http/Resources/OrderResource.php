<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'total_price' => $this->total_price,
            'site' => new SiteResource($this->site),
            'status' => $this->status,
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'quantity' => $product->pivot->quantity,
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            /* 'updated_at' => $this->updated_at->toDateTimeString(), */
        ];
    }
}
