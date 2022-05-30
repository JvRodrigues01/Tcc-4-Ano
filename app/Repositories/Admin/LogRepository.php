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

    public function SaveLogs($entidade = null, $idEntidade = null, $data = null, $user = null, $acao = null, $sucesso = false, $mensagem = null)
    {
        $log = new Log;

        $log->Entidade = $entidade;
        $log->IdEntidade = $idEntidade;
        $log->DataHora = $data;
        $log->IdUsuario = $user;
        $log->Acao = $acao;
        $log->Sucesso = $sucesso;
        $log->Mensagem = $mensagem;

        $log->save();
    }

    public function ListLogs($page, $size, $search = null, $usuario = null, $dataInicio = null, $dataFim = null)
    {
        $data = $this->model::select('log.*');

        if(isset($usuario)){
            $data = $data->where('IdUsuario', $usuario);
        }

        if(isset($dataInicio)){
            $data = $data->where('DataHora', ">=", $dataInicio);
        }

        if(isset($dataFim)){
            $data = $data->where('DataHora', "<=", $dataFim);
        }

        if($search){
            $data = $data->where(function($q) use ($search) {
                $q->where('Entidade', 'LIKE', "%{$search}%");
            });
        }

        $count = $data->count();
        $items = $data->skip(($page - 1) * $size)->take($size)->orderBy('DataHora', 'ASC')->get();

        return Pagination::Paginate($items, $count, $page, $size);
    }
}