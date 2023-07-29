<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farmer extends Model
{

    use HasFactory;
    use SoftDeletes;


    protected $guarded =['id'];

    public function contacts()
    {
        return $this->morphMany(Contact::class,'contactable');
    }

    public function associates()
    {
        return $this->hasMany(Associate::class,'farmer_id','id');
    }

    public function media()
    {
        return $this->morphMany(Medium::class,'recordable');
    }

    public function farms()
    {
        return $this->hasMany(Farm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'created_by_user_id');
    }
}
