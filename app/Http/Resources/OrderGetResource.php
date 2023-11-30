<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderGetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $price_all = 0;

        foreach ($this->positions as $position) {
            $price_all += $position->price;
        }

        return [
            'id' => $this->id,
            'table' => $this->table->title,
            'shift_workers' => $this->shift_workers,
            'create_at' => $this->created_at,
            'status' => $this->status,
            'positions' => PostionResource::collection($this->positions),
            'price_all' => $price_all
        ];
    }
}
