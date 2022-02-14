<?php

namespace App\Models\Admin;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class ResidenciaEndereco extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public $timestamps = false;

    protected $table = 'residenciaendereco';

    protected $primaryKey = 'IdResidenciaEndereco';

    protected $fillable = [
        "IdResidenciaEndereco",
        "Logradouro",
        "Numero",
        "Bairro",
        "Cidade",
        "Estado"
    ];
}