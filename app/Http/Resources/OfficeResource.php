<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->);
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'approval_status' => $this->approval_status,
            'hidden' => $this->hidden,
            'price_per_day' => $this->price_per_day,
            'monthly_discount' => $this->monthly_discount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tags'=>$this->tags,
            'user'=>$this->user,
            'images'=>$this->images,
            'reservations_count' => $this->reservations_count,

            // You can add more attributes or customize as needed
        ];
    }
}
