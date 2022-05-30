<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\Admin\LogInterface;
use App\Models\Admin\Log;

use DateTime;
use App\functions\Crypt;
use App\Helpers\Helpers;

class LogService
{
    protected $interface;
    protected $helpers;

    public function __construct(LogInterface $LogInterface, Helpers $helpers)
    {
        $this->helpers = $helpers;
        $this->interface = $LogInterface;
    }

    
    public function ListLogs(Request $request){
        try {
            $result = $this->interface->ListLogs($request->Page, $request->Size, $request->Search, $request->Usuario, $request->DataInicio, $request->DataFinal);

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