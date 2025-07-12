<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? '-',
            'name' => $this->name ?? '-',
            'company_name' => $this->company_name ?? '-',
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
