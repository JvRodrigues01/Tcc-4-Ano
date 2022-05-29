<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\Admin\ResidenciaInterface;
use App\Models\Admin\Residencia;

use DateTime;

use App\Functions\Log;
use App\functions\Crypt;
use App\Helpers\Helpers;
use App\Repositories\Interfaces\Admin\LogInterface;
use App\Repositories\Interfaces\Admin\UsuarioInterface;

class ResidenciaService
{
    protected $interface;
    protected $usuarioInterface;
    protected $logInterface;
    protected $helpers;

    public function __construct(ResidenciaInterface $residenciaInterface, UsuarioInterface $usuarioInterface,
        LogInterface $logInterface,
        Helpers $helpers)
    {
        $this->helpers = $helpers;
        $this->interface = $residenciaInterface;
        $this->usuarioInterface = $usuarioInterface;
        $this->logInterface = $logInterface;
    }

    
    public function ListResidencias(Request $request){
        try {
            $result = $this->interface->ListResidencias($request->Page, $request->Size, $request->Search);

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

    public function CreateOrUpdateResidencia($id = null, Request $request){
        try {
            $residencia = ($id != null) ? $this->interface->GetResidencia($id) : new Residencia;
            
            $data = new DateTime();

            foreach ($request->all() as $key => $value) {
                $residencia->$key = $value;
            }

            if($id != null){
                $residencia->AtualizadoEm = $data->format("Y-m-d H:i:s");
                unset($residencia->Clientes);
            } else {
                $residencia->CriadoEm = $data->format("Y-m-d H:i:s");
            }
            
            $result = $this->interface->SaveResidencia($residencia);

            $token = $request->header('Authorization');
            
            $user = $this->usuarioInterface->GetUserByToken($token)->IdUsuario;

            $this->logInterface->SaveLogs("Residencia", $id ? $id : null, $data->format("Y-m-d H:i:s"), $user, "CreateOrUpdateResidencia", true);

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

    public function GetResidencia($id){
        try {
            $result = $this->interface->GetResidencia($id);

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

    public function DeleteResidencia(Request $request, $id){
        try {
            $data = new DateTime();

            $residencia = $this->interface->GetResidencia($id);
            
            $result = $this->interface->DeleteResidencia($residencia);
            
            $token = $request->header('Authorization');
            
            $user = $this->usuarioInterface->GetUserByToken($token)->IdUsuario;

            $this->logInterface->SaveLogs("Residencia", $id ? $id : null, $data->format("Y-m-d H:i:s"), $user, "DeleteResidencia", true);

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