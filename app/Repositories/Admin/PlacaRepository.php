<?php

namespace App\Repositories\Admin;

use App\Repositories\Interfaces\Admin\PlacaInterface;
use App\Models\Admin\Placa;
use App\Models\Admin\Token;
use App\Functions\Pagination;
use Illuminate\Support\Facades\DB;

use App\Models\Ecommerce\Cliente;

use DateTime;

//Camada de repositório, unica que deverá fazer buscas e alterações no banco de dados
class PlacaRepository implements PlacaInterface
{
    protected $model;

    public function __construct(Placa $placa)
    {
        $this->model = $placa;
    }

    public function ListPlacas($page, $size, $search)
    {
        $data = $this->model;

        if($search){
            $data = $data->where(function($q) use ($search) {
                $q->where('Numero', 'LIKE', "%{$search}%");
            });
        }

        $count = $data->count();
        $items = $data->skip(($page - 1) * $size)->take($size)->orderBy('Numero', 'ASC')->get();

        return Pagination::Paginate($items, $count, $page, $size);
    }

    public function GetPlaca($id)
    {
        return $this->model->where("IdPlaca", "=", $id)->first();
    }

    public function SavePlaca($placa)
    {
        $placa->save();

        return $placa;
    }
}