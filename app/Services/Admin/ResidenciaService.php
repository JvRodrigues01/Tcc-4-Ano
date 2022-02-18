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

class ResidenciaService
{
    protected $interface;
    protected $helpers;

    public function __construct(ResidenciaInterface $residenciaInterface,
        Helpers $helpers)
    {
        $this->helpers = $helpers;
        $this->interface = $residenciaInterface;
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

            foreach ($request->all() as $key => $value) {
                $residencia->$key = $value;
            }
            
            $result = $this->interface->SaveResidencia($residencia);

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
}