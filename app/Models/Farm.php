<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $guarded=['id'];
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function media()
    {
        return $this->morphMany(Medium::class,'recordable');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
