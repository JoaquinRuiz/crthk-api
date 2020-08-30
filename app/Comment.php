<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['comment','user_id', 'name', 'email'];

    public function post()
    {
        return $this->hasMany('\App\Post');
    }
}
