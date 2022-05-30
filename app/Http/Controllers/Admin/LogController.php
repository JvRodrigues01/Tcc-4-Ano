<?php

namespace App\Http\Controllers\Admin;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\Admin\LogService;
use Illuminate\Http\Request;

class LogController extends BaseController
{
    private $service;

    public function __construct(LogService $LogService)
    {
        $this->service = $LogService;
    }

    public function ListLogs(Request $request){
        return $this->service->ListLogs($request);
    }
}
