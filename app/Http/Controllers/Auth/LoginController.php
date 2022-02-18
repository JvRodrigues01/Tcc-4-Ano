<?php

namespace App\Http\Controllers\Auth;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\Autenticacao\AutenticacaoService;
use App\Services\Admin\UsuarioService;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
    private $service;
    private $userService;

    public function __construct(AutenticacaoService $autenticacaoService, UsuarioService $usuarioService)
    {
        $this->service = $autenticacaoService;
        $this->userService = $usuarioService;
    }

    public function RenewUserToken($IdUsuario)
    {
        return $this->service->RenewUserToken($IdUsuario);
    }

    public function Login(Request $request)
    {
        return $this->service->Login($request);
    }

    public function AlterarSenha(Request $request)
    {
        return $this->service->AlterarSenha($request);
    }

    public function AlterarSenhaAdmin(Request $request)
    {
        return $this->service->AlterarSenhaAdmin($request);
    }

    public function RecuperarSenha(Request $request)
    {
        return $this->service->RecuperarSenha($request);
    }
    
    public function Cadastrar($id = null, Request $request)
    {
        return $this->userService->CreateOrUpdateUsuario($id, $request);
    }
}
