<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $table = 'lab';

    public $timestamps = false;

    protected $fillable = [
        'nama', 'kelas'
    ];
}
