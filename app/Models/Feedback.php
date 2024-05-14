<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = ['rate','comment','image','contact','user_id'];

    protected $appends = ['user'];

    public function getUserAttribute()
    {
        return User::find($this->attributes['user_id'])->name;
    }
}
