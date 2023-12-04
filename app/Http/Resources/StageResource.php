<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'hex_color' => $this->hex_color,
            'order' => $this->order,
            'is_final_stage' => $this->is_final_stage,
            'board_id' => $this->board_id,
            'author_id' => $this->author_id,
            'author_full_name' => $this->author_full_name,
        ];
    }
}
