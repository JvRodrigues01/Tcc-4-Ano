<?php

namespace App\Repositories\Admin;

use App\Repositories\Interfaces\Admin\LogInterface;
use App\Models\Admin\Log;
use App\Functions\Pagination;
use Illuminate\Support\Facades\DB;

use DateTime;

//Camada de repositório, unica que deverá fazer buscas e alterações no banco de dados
class LogRepository implements LogInterface
{
    protected $model;

    public function __construct(Log $log)
    {
        $this->model = $log;
    }

    public function SaveLogs($entidade = null, $idEntidade = null, $data = null, $user = null, $acao = null, $sucesso = false)
    {
        $log = new Log;

        $log->Entidade = $entidade;
        $log->IdEntidade = $idEntidade;
        $log->DataHora = $data;
        $log->IdUsuario = $user;
        $log->Acao = $acao;
        $log->Sucesso = $sucesso;

        $log->save();
    }
}