<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'stock' => $this->stock,
            'condition' => $this->condition,
            'status' => $this->status,
            'description' => $this->description,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
