<?php

namespace App\Http\Resources;

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
       /*

            $table->id();
            $table->string('type')->index();
            $table->string('farmer_name');
            $table->string('pf_no')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('id_no')->nullable();
            $table->string('id_photo_path');
            $table->boolean('isActive');
            $table->string('status')->nullable();
            $table->timestamps();
       */
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
                    'contacts'=>$this->whenLoaded('contacts')
               ];
    }
}
