<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

      /**
       *  $table->id();
            $table->date('visit_date');
            $table->foreignIdFor(Farm::class);
            $table->json('ready_by_dates')->nullable();
            $table->integer('ready_by_count')->nullable();
            $table->json('herd_inventory');
            $table->timestamps();
       */

        return [  'visit_date'=>Carbon::parse($this->visit_date)->toDateString(),
                   'farm'=>$this->whenLoaded('farm'),
                   'user'=>$this->whenLoaded('user'),
                   'ready_by_dates'=> $this->ready_by_dates, //?Carbon::parse(json_decode($this->ready_by_dates)[0])->toDateString().'__'.Carbon::parse(json_decode($this->ready_by_dates)[0])->toDateString():'',
                   'ready_by_count'=>$this->ready_by_count,
                   'farm'=>$this->whenLoaded('farm'),
                   'herd_inventory'=>$this->herd_inventory,
                   'done_at'=>$this->created_geolocation,
                   'updated_at_location'=>$this->created_geolocation,
                   'created_at'=>Carbon::parse($this->created_at)->diffForHumans(),
                   'last_updated'=>Carbon::parse($this->update_at)->diffForHumans(),
                   'id'=>$this->id,


               ];
    }
}
