<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkShiftOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $amount_for_all = 0;

        foreach ($this->orders as $order) {
            $amount_for_all += $order->price;
        }

        return [
            'id' => $this->id,
            'start' => $this->start,
            'end' => $this->end,
            'active' => $this->active,
            'orders' => OrderResource::collection($this->orders),
            'amount_for_all' => $amount_for_all
        ];
    }
}
