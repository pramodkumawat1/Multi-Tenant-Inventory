<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function($data) {
            return [
                'id'                 => $data?->id,
                'product_id'         => $data?->product_id ?? '',
                'product_name'       => (string) $data?->product?->name ?? '',
                'price'              => number_format((float)$data?->price, 2, '.', ''),
                'quantity'           => $data?->quantity ?? '',
                'total_price'        => number_format((float)(($data?->price)*($data?->quantity)), 2, '.', ''),
            ];
        });
    }
}
