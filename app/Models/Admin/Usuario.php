<?php

namespace App\Models\Admin;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Usuario extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public $timestamps = false;

    protected $table = 'usuario';

    protected $primaryKey = 'IdUsuario';

    protected $fillable = [
        "IdUsuario",
        "Guid",
        "IdTipoUsuario",
        "Login",
        "Senha", 
        "Nome",  
        "Email",
        "CriadoEm", 
        "AtualizadoEm",  
        "Inativo",
        "IdResidencia"
    ];

    protected $hidden = [
        'Senha'
    ];

    public function usuarioTipo() 
    {
        return $this->hasOne('App\Models\Admin\UsuarioTipo', 'IdUsuario', 'IdUsuario');
    }
}