<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\Admin\ClienteInterface;
use App\Models\Admin\Cliente;

use DateTime;

use App\Functions\Log;
use App\functions\Crypt;
use App\Helpers\Helpers;

class ClienteService
{
    protected $interface;
    protected $helpers;

    public function __construct(ClienteInterface $clienteInterface,
        Helpers $helpers)
    {
        $this->helpers = $helpers;
        $this->interface = $clienteInterface;
    }

    
    public function ListClientes(Request $request){
        try {
            $result = $this->interface->ListClientes($request->Page, $request->Size, $request->Search);

            return response()->json($result, Response::HTTP_OK);
        }  catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];
            
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function CreateOrUpdateCliente($id = null, Request $request){
        try {
            $cliente = ($id != null) ? $this->interface->GetCliente($id) : new Cliente;

            foreach ($request->all() as $key => $value) {
                $cliente->$key = $value;
            }
            
            $result = $this->interface->SaveCliente($cliente);

            return response()->json($result, Response::HTTP_OK);
        }  catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];
            
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function GetCliente($id){
        try {
            $result = $this->interface->GetCliente($id);

            return response()->json($result, Response::HTTP_OK);
        }  catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];
            
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function DeleteCliente($id){
        try {

            $cliente = $this->interface->GetCliente($id);
            
            $result = $this->interface->DeleteCliente($cliente);

            return response()->json($result, Response::HTTP_OK);
        }  catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];
            
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}