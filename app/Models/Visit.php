<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Visit extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function farmer()
{
    return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
}


}
