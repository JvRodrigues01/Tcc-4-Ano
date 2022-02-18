<?php

namespace App\Repositories\Admin;

use App\Repositories\Interfaces\Admin\ResidenciaInterface;
use App\Models\Admin\Residencia;
use App\Models\Admin\Token;
use App\Functions\Pagination;
use Illuminate\Support\Facades\DB;

use App\Models\Ecommerce\Cliente;

use DateTime;

//Camada de repositório, unica que deverá fazer buscas e alterações no banco de dados
class ResidenciaRepository implements ResidenciaInterface
{
    protected $model;

    public function __construct(Residencia $residencia)
    {
        $this->model = $residencia;
    }

    public function ListResidencias($page, $size, $search)
    {
        $data = $this->model;

        if($search){
            $data = $data->where(function($q) use ($search) {
                $q->where('Descricao', 'LIKE', "%{$search}%")
                ->orWhere('Telefone', 'LIKE', "%{$search}%")
                ->orWhere('Logradouro', 'LIKE', "%{$search}%")
                ->orWhere('Numero', 'LIKE', "%{$search}%")
                ->orWhere('Bairro', 'LIKE', "%{$search}%")
                ->orWhere('Cidade', 'LIKE', "%{$search}%")
                ->orWhere('Estado', 'LIKE', "%{$search}%");
            });
        }

        $count = $data->count();
        $items = $data->skip(($page - 1) * $size)->take($size)->orderBy('Descricao', 'ASC')->get();

        return Pagination::Paginate($items, $count, $page, $size);
    }

    public function GetResidencia($id)
    {
        return $this->model->where("IdResidencia", "=", $id)->first();
    }

    public function SaveResidencia($residencia)
    {
        $residencia->save();

        return $residencia;
    }
}