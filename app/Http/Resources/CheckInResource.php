<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckInResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'borrowing' => new BorrowingResource($this->whenLoaded('borrowing') ?: $this->borrowing),
            'equipment' => new EquipmentResource($this->whenLoaded('equipment') ?: $this->equipment),
            'user' => new UserResource($this->whenLoaded('user') ?: $this->user),
            'status' => $this->status,
            'checked_in_at' => $this->checked_in_at?->toISOString(),
            'checked_out_at' => $this->checked_out_at?->toISOString(),
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
