<?php

namespace App\Http\Controllers\Admin;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\Admin\ClienteService;
use Illuminate\Http\Request;

class ClienteController extends BaseController
{
    private $service;

    public function __construct(ClienteService $clienteService)
    {
        $this->service = $clienteService;
    }

    public function ListClientes(Request $request){
        return $this->service->ListClientes($request);
    }

    public function CreateOrUpdateCliente($id = null, Request $request){
        return $this->service->CreateOrUpdateCliente($id, $request);
    }
    
    public function GetCliente($id){
        return $this->service->GetCliente($id);
    }
    
    public function DeleteCliente($id){
        return $this->service->DeleteCliente($id);
    }
}
