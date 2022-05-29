<?php

namespace App\Http\Controllers\Admin;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\Admin\PlacaService;
use Illuminate\Http\Request;

class PlacaController extends BaseController
{
    private $service;

    public function __construct(PlacaService $placaService)
    {
        $this->service = $placaService;
    }

    public function ListPlacas(Request $request){
        return $this->service->ListPlacas($request);
    }

    public function CreateOrUpdatePlaca($id = null, Request $request){
        return $this->service->CreateOrUpdatePlaca($id, $request);
    }
    
    public function GetPlaca($id){
        return $this->service->GetPlaca($id);
    }
    
    public function DeletePlaca(Request $request, $id){
        return $this->service->DeletePlaca($request, $id);
    }

    public function Recognition(Request $request){
        return $this->service->Recognition($request);
    }
}
