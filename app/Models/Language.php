<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'language';

    protected $fillable = ['name','file','image','direction','status'];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return url('images/upload').'/'.$this->attributes['image'];
    }
}
