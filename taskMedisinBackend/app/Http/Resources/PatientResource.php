<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'medical_record_number' => $this->medical_record_number,
            'name' => $this->name,
            'id_number' => $this->id_number,
            'address' => $this->address,
            'visit' => $this->visit
        ];
    }
}
