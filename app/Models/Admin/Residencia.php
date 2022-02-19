<?php

namespace App\Models\Admin;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Residencia extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public $timestamps = false;

    protected $table = 'residencia';

    protected $primaryKey = 'IdResidencia';

    protected $fillable = [
        "IdResidencia",
        "Descricao",
        "Telefone",
        "Logradouro",
        "Numero",
        "Bairro",
        "Cidade",
        "Estado",
        "Cep",
        "CriadoEm",
        "AtualizadoEm"
    ];

    public function residenciaEndereco() 
    {
        return $this->hasOne('App\Models\Admin\ResidenciaEndereco', 'IdResidencia', 'IdResidencia');
    }
}