<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\Admin\PlacaInterface;
use App\Models\Admin\Placa;

use DateTime;

use App\Functions\Log;
use App\functions\Crypt;
use App\Helpers\Helpers;
use App\Repositories\Interfaces\Admin\LogInterface;
use App\Repositories\Interfaces\Admin\UsuarioInterface;

class PlacaService
{
    protected $interface;
    protected $usuarioInterface;
    protected $logInterface;
    protected $helpers;

    public function __construct(PlacaInterface $placaInterface, UsuarioInterface $usuarioInterface, LogInterface $logInterface, 
        Helpers $helpers)
    {
        $this->helpers = $helpers;
        $this->interface = $placaInterface;
        $this->usuarioInterface = $usuarioInterface;
        $this->logInterface = $logInterface;
    }

    
    public function ListPlacas(Request $request){
        try {
            $result = $this->interface->ListPlacas($request->Page, $request->Size, $request->Search);

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

    public function CreateOrUpdatePlaca($id = null, Request $request){
        try {
            $data = new DateTime();

            $placa = ($id != null) ? $this->interface->GetPlaca($id) : new Placa;

            foreach ($request->all() as $key => $value) {
                $placa->$key = $value;
            }

            if($id != null) unset($placa->Residencia);
            
            $result = $this->interface->SavePlaca($placa);

            $token = $request->header('Authorization');
            
            $user = $this->usuarioInterface->GetUserByToken($token)->IdUsuario;

            $this->logInterface->SaveLogs("Placa", $id ? $id : null, $data->format("Y-m-d H:i:s"), $user, "CreateOrUpdatePlaca", true);

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

    public function GetPlaca($id){
        try {
            $result = $this->interface->GetPlaca($id);

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

    public function DeletePlaca(Request $request, $id){
        try {
            $data = new DateTime();

            $placa = $this->interface->GetPlaca($id);
            
            $result = $this->interface->DeletePlaca($placa);
            
            $token = $request->header('Authorization');
            
            $user = $this->usuarioInterface->GetUserByToken($token)->IdUsuario;

            $this->logInterface->SaveLogs("Placa", $id ? $id : null, $data->format("Y-m-d H:i:s"), $user, "DeletePlaca", true);

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

    public function Recognition(Request $request){
        try {
            $result = $this->interface->Recognition($request->Image);

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