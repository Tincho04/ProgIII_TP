<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Review extends Model {

    protected $table = 'reviews';
    // public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}