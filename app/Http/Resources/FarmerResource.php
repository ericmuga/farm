<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
                    'farmer_name'=>$this->farmer_name,
                    'gender'=>$this->gender,
                    'date_of_birth'=>$this->date_of_birth,
                    'id_no'=>$this->id_no,
                    'id'=>$this->id,
                    'pf_no'=>$this->pf_no,
                    'registration_no'=>$this->registration_no,
                    'id_photo_path'=>$this->id_photo_path,
                    'isActive'=>$this->isActive,
                    'status'=>$this->status,
                    'type'=>$this->type,
                    'contacts_count'=>$this->whenCounted('contacts'),
                    'contacts'=>$this->whenLoaded('contacts'),
                    'associates'=>$this->whenLoaded('associates'),
                    'media'=>$this->whenLoaded('media'),
                    'farms'=>$this->whenLoaded('farms'),
                    'created_at'=>Carbon::parse($this->created_at)->diffForHumans(),
                    'updated_at'=>Carbon::parse($this->updated_at)->diffForHumans(),
                    'created_geolocation'=>$this->created_geolocation,
                    'updated_geolocation'=>$this->updated_geolocation

               ];
    }
}
