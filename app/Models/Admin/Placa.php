<?php

namespace App\Models\Admin;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Placa extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public $timestamps = false;

    protected $table = 'placa';

    protected $primaryKey = 'IdPlaca';

    protected $fillable = [
        "IdPlaca",
        "Numero",
        "IdResidencia"
    ];

    public function residencia() 
    {
        return $this->hasOne('App\Models\Admin\Residencia', 'IdResidencia', 'IdResidencia');
    }
}