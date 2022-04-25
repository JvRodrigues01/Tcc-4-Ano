<?php

namespace App\Repositories\Admin;

use App\Repositories\Interfaces\Admin\PlacaInterface;
use App\Models\Admin\Placa;
use App\Models\Admin\Residencia;
use App\Models\Admin\Token;
use App\Functions\Pagination;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
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
        $data = $this->model::select('placa.*', 'residencia.Descricao as ResidenciaDescricao')
                            ->join('residencia','residencia.IdResidencia', '=', 'placa.IdResidencia');

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
        $data = $this->model->where("IdPlaca", "=", $id)->first();
        $data->Residencia = Residencia::where("IdResidencia", "=", $data->IdResidencia)->first();

        return $data;
    }

    public function DeletePlaca($placa)
    {
        $placa->delete();

        return $placa;
    }

    public function SavePlaca($placa)
    {
        $placa->save();

        return $placa;
    }

    public function Recognition($image)
    {
        $client = new Client();

		$response = $client->request('POST', "http://127.0.0.1:5000/recognition",
		[
			'headers' => [
				'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'image' => $image
            ])
		]);

        return json_decode($response->getBody());
    }
}