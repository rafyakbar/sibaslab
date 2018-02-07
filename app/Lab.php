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

    /**
     * mendapatkan relasi user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRelasiUser()
    {
        return $this->hasMany('App\User', 'lab_id');
    }

    /**
     * mendapatkan data user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUser()
    {
        return $this->getRelasiUser()->get();
    }
}
