<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Humano extends Model {    // Este modelo lo único q me permite es crear instancias de esta clase para
                                //guardarlas en la base de datos ($h = new Humano / $h->save())
                                //no tiene relaciones con otras tablas de momento
    use HasFactory;

    protected $table = 'humanos';
    public $incrementing = false;
}
