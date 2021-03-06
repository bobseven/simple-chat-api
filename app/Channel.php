<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public $guarded = [];
    public function messages() {
        return $this->hasMany('App\Message');
    }

    public function users() {
        return $this->belongsToMany('App\User', 'channel_users');
    }
}
