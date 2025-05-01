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
            'id' => $this->id,
            'transaction' => $this->transaction_code,
            'food' => new FoodResource($this->food),
            'price' => number_format($this->price, 0, ",", "."),
            'quantity' => $this->quantity,
            'sum' => number_format($this->sum, 0, ",", "."),
            'status' => $this->status,
            'user' => $this->user->name,
        ];
    }
}
