<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'abbreviation' => $this->abbreviation,
            'email' => $this->email,
            'img_profile' => $this->profile_img_url,
        ];
    }
}
