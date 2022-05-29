<?php

namespace App\Http\Controllers\Admin;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\Admin\ResidenciaService;
use Illuminate\Http\Request;

class ResidenciaController extends BaseController
{
    private $service;

    public function __construct(ResidenciaService $residenciaService)
    {
        $this->service = $residenciaService;
    }

    public function ListResidencias(Request $request){
        return $this->service->ListResidencias($request);
    }

    public function CreateOrUpdateResidencia($id = null, Request $request){
        return $this->service->CreateOrUpdateResidencia($id, $request);
    }
    
    public function GetResidencia($id){
        return $this->service->GetResidencia($id);
    }
    
    public function DeleteResidencia(Request $request, $id){
        return $this->service->DeleteResidencia($request, $id);
    }
}
