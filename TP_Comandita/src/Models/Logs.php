<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model {

    protected $table = 'logs';
    // public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}



