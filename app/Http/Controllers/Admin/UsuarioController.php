<?php

namespace App\Http\Controllers\Admin;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\Admin\UsuarioService;
use Illuminate\Http\Request;

class UsuarioController extends BaseController
{
    private $service;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->service = $usuarioService;
    }

    public function ListUsuarioTipo(Request $request){
        return $this->service->ListUsuarioTipo($request);
    }
}
