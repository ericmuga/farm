<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Associate extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function contacts()
    {
        return $this->morphMany(Contact::class,'contactable');
    }
    protected $guarded = ['id'];


    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}
