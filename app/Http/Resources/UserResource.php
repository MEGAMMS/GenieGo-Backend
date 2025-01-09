<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $additionalDetails = [];
    
    if ($this->getRole() === 'Owner') {
        $additionalDetails = [
            'store_id' => $this->store,
        ];
    }

    return array_merge([
        'id' => $this->id,
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'username' => $this->username,
        'email' => $this->email,
        'phone' => $this->phone,
        'icon' => $this->icon,
        'role' => $this->getRole(),
        /* 'created_at' => $this->created_at, */
        /* 'updated_at' => $this->updated_at, */
    ], $additionalDetails);
    }
}
