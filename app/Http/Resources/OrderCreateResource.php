<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCreateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "table" => $this->table->title,
            "shift_workers" => $this->shift_workers,
            "create_at" => $this->created_at,
            "status" => $this->status,
            "price" => $this->price
        ];
    }
}
