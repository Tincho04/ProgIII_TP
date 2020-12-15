<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model {

    protected $table = 'users';
    // public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const LOGIN_AT = 'last_login_at';
}



