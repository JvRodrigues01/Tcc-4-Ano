<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\Admin\ClienteInterface;
use App\Repositories\Interfaces\Admin\UsuarioInterface;
use App\Services\Admin\UsuarioService;
use App\Models\Admin\Cliente;

use DateTime;

use App\Functions\Log;
use App\functions\Crypt;
use App\Helpers\Helpers;

class ClienteService
{
    protected $interface;
    protected $helpers;
    protected $usuarioInterface;

    public function __construct(ClienteInterface $clienteInterface,
        Helpers $helpers, UsuarioService $usuarioInterface)
    {
        $this->helpers = $helpers;
        $this->interface = $clienteInterface;
        $this->usuarioInterface = $usuarioInterface;
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

            if($result && $id == null){
                $usuario = $this->usuarioInterface->CreateUserByCpfCliente($result->Cpf, $result->Cpf, $result->Nome, $result->Email, 2, $result->IdCliente);
                $result->Usuario = $usuario;
            }

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