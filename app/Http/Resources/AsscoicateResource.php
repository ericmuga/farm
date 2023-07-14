<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsscoicateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                  'associate_name'=>$this->associate_name,
                  'associate_type'=>$this->associate_type,
                  'farmer_id'=>$this->farmer_id,
                  'contacts'=>$this->contacts()

        ];
    }
}
