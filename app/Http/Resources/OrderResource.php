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
            'id' => $this?->id,
            'store' => (string)$this?->store?->name,
            'total_price' => (string)$this?->total_price,
            'items' => new OrderItemCollection($this?->items)
        ];
    }
}
