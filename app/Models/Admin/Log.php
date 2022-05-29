<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';

    protected $primaryKey = 'Id';
    
    public $timestamps = false;

    protected $fillable = [
        'Id',
        'Entidade',
        'IdEntidade',
        'DataHora',
        'IdUsuario',
        'Acao',
        'Sucesso',
    ];

    public function usuario()
    {
        return $this->belongsTo('App\Models\Admin\Usuario', 'IdUsuario', 'IdUsuario');
    }
}