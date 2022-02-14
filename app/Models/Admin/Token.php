<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'token';

    protected $primaryKey = 'IdToken';
    
    public $timestamps = false;

    protected $fillable = [
        'IdToken',
        'IdUsuario',
        'Token',
        'CriadoEm',
        'ExpiraEm',
        'UltimoLogin',
        'Inativo',
        'Tipo',
    ];

    public function usuario()
    {
        return $this->belongsTo('App\Models\Admin\Usuario', 'IdUsuario', 'IdUsuario');
    }
}