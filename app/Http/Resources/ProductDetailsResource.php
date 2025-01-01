<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'price' => $this->price,
            'discount' => $this->discount,
            'description' => $this->description,
            'rating' => $this->rating,
            'status' => $this->status,
            'quantity' => $this->quantity,
            'image_url' => $this->image_url,
        ];
    }
}
