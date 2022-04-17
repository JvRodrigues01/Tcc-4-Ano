<?php

namespace App\Repositories\Admin;

use App\Repositories\Interfaces\Admin\ClienteInterface;
use App\Models\Admin\Cliente;
use App\Functions\Pagination;
use Illuminate\Support\Facades\DB;

use DateTime;

//Camada de repositório, unica que deverá fazer buscas e alterações no banco de dados
class ClienteRepository implements ClienteInterface
{
    protected $model;

    public function __construct(Cliente $cliente)
    {
        $this->model = $cliente;
    }

    public function ListClientes($page, $size, $search)
    {
        $data = $this->model::select('cliente.*', 'residencia.Descricao as ResidenciaDescricao')
                            ->join('residencia','residencia.IdResidencia', '=', 'cliente.IdResidencia');

        if($search){
            $data = $data->where(function($q) use ($search) {
                $q->where('Nome', 'LIKE', "%{$search}%");
                $q->where('Email', 'LIKE', "%{$search}%");
                $q->where('Cpf', 'LIKE', "%{$search}%");
                $q->where('Telefone', 'LIKE', "%{$search}%");
            });
        }

        $count = $data->count();
        $items = $data->skip(($page - 1) * $size)->take($size)->orderBy('Nome', 'ASC')->get();

        return Pagination::Paginate($items, $count, $page, $size);
    }

    public function GetCliente($id)
    {
        return $this->model->where("IdCliente", "=", $id)->first();
    }

    public function DeleteCliente($cliente)
    {
        $cliente->delete();

        return $cliente;
    }

    public function SaveCliente($cliente)
    {
        $cliente->save();

        return $cliente;
    }
}