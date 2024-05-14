<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    use HasFactory;

    protected $table = 'cuisine';

    protected $fillable = ['name','status','image'];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
