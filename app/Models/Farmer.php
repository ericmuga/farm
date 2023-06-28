<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{

    use HasFactory;


    protected $guarded =['id'];
    public function contacts()
    {
        return $this->morphMany(Contact::class,'contactable');
    }
}
