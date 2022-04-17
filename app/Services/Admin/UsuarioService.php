<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\Admin\UsuarioInterface;

use App\Models\Admin\Usuario;

use DateTime;

use App\Functions\Log;
use App\functions\Crypt;
use App\Helpers\Helpers;

class UsuarioService
{
    protected $interface;
    protected $helpers;
    protected $usuarioCampoInterface;
    protected $ecommerceInterface;
    protected $clienteInterface;
    protected $ecommerceInterfaceClient;

    public function __construct(UsuarioInterface $usuarioInterface,
        Helpers $helpers)
    {
        $this->helpers = $helpers;
        $this->interface = $usuarioInterface;
    }

    public function CreateOrUpdateUsuario($id = null, Request $request)
    {
        try {
            $data = new DateTime();
            $usuario = ($id != null) ? $this->interface->SearchUsuario($id) : new Usuario;

            foreach ($request->all() as $key => $value) {
                if ($key != "CriadoEm" && $key != "AtualizadoEm" && $key != "Guid")
                    $usuario->$key = $value;
            }
            
            if ($id == null) {
                $usuario->CriadoEm = $data->format("Y-m-d H:i:s");
                $usuario->Guid = $this->helpers->GerarGuid();
                $usuario->Inativo = false;
                $usuario->Senha = Crypt::hash($request->Senha);
            } else {
                $usuario->AtualizadoEm = $data->format("Y-m-d H:i:s");
            }
            
            $result = $this->interface->SaveUsuario($usuario);

            return response()->json($result, Response::HTTP_OK);
        } catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];
        
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function SearchByEmail($email)
    {
        try {
            $usuario = $this->interface->SearchByEmail($email);
            return response()->json($usuario, Response::HTTP_OK);
        } catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];

            Log::Log("Ecommerce", "Service", "Cliente/ListClientes", "Exception", $exception);
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function GetUsuario($id)
    {
        try {
            $usuario = $this->interface->SearchUsuario($id);
            
            return response()->json($usuario, Response::HTTP_OK);
        } catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];
            
            Log::Log("Sistema", "Service", "Usuario/GetUsuario", "Exception", $exception);

            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function ListUsuarios(Request $request)
    {
        try {
            $usuarios = $this->interface->RetrieveUsuarios($request->Page, $request->Size, $request->Search);

            return response()->json($usuarios, Response::HTTP_OK);
        } catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];
            
            Log::Log("Sistema", "Service", "Usuario/ListUsuarios", "Exception", $exception);
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function DesativarUsuario($id)
    {
        try {
            $usuario = $this->interface->SearchUsuario($id);
            $usuario["Inativo"] = true;
        
            $result = $this->interface->SaveUsuario($usuario, null);
            return response()->json($result, Response::HTTP_OK);
        } catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];
            
            Log::Log("Sistema", "Service", "Usuario/DesativarUsuario", "Exception", $exception);
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function ListUsuarioTipo(Request $request){
        try {
            $result = $this->interface->ListUsuarioTipo($request->Page, $request->Size, $request->Search);

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

    public function CreateUserByCpfCliente($login, $senha, $nome, $email, $tipoUsuario, $idCliente)
    {
        try {
            $data = new DateTime();

            $usuario = new Usuario;
            
            $usuario->IdTipoUsuario = $tipoUsuario;
            $usuario->Login = $login;
            $usuario->Senha = Crypt::hash($senha);
            $usuario->Nome = $nome;
            $usuario->Email = $email;
            $usuario->CriadoEm = $data->format("Y-m-d H:i:s");
            $usuario->Guid = $this->helpers->GerarGuid();
            $usuario->Inativo = false;
            $usuario->IdCliente = $idCliente;

            
            return $this->interface->SaveUsuario($usuario);
            
        } catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];
        
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}