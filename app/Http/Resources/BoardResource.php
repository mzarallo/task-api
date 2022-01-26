<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'order' => $this->order,
            'is_final_stage' => $this->is_final_stage,
            'hex_color' => $this->hex_color,
            'author_id' => $this->author_id,
            'board_id' => $this->board_id,
            'author_full_name' => $this->author_full_name
        ];
    }
}
