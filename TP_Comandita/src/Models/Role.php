<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $id;
    public $name;

    const ADMIN = 1;
    const SOCIO = 2;
    const MOZO = 3;
    const BARTENDER = 4;
    const CERVECERO = 5;
    const CHEF = 6;
    const MAESTRO_PASTELERO = 7;
}
