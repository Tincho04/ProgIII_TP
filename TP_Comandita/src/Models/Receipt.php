<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Receipt extends Model {

    protected $table = 'receipts';
    // public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}