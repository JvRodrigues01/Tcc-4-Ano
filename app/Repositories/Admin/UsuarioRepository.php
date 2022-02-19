<?php

namespace App\Repositories\Admin;

use App\Repositories\Interfaces\Admin\UsuarioInterface;
use App\Models\Admin\Usuario;
use App\Models\Admin\Token;
use App\Functions\Pagination;
use App\Models\Admin\UsuarioTipo;
use Illuminate\Support\Facades\DB;

use App\Models\Ecommerce\Cliente;

use DateTime;

//Camada de repositório, unica que deverá fazer buscas e alterações no banco de dados
class UsuarioRepository implements UsuarioInterface
{
    protected $model;

    public function __construct(Usuario $usuario)
    {
        $this->model = $usuario;
    }

    /**
     * Busca um único usuário
     * 
     * @param Number $id Usuario id
     * 
     * @return Usuario
     */
    public function SearchUsuario($id)
    {
        return $this->model->findOrFail($id);
    }

    public function GetUserByLoginAndEcommerce($login, $idEcommerce){
        return Usuario::where("Login", "=", $login)->where("IdEcommerce", "=", $idEcommerce)->first();
    }


    public function SearchByEmail($email)
    {
        $usuario = Usuario::where("Email", "=", $email)->first();

        if($usuario)
            return $usuario;

        return "Não existe!";
    }

    /**
     * Salva um usuario ou atualiza um existente
     * 
     * @param Usuario $usuario Usuario a ser salvo
     * 
     * @return Usuario
     */
    public function SaveUsuario($usuario)
    {
        $usuario->save();
        return $usuario;
    }

    /**
     * Deleta um usuario lógicamente
     * 
     * @param Number $id Id do usuario a ser deletado
     * 
     * @return Usuario
     */
    public function DeleteUsuario($id)
    {
        $usuario = $this->model->find($id);
        $usuario->Inativo = true;
        return $usuario->save();
    }

    /**
     * Recuperar Usuarios
     * 
     * @param int $page Qual página buscará
     * @param int $size Quantidade de registros por página
     * 
     * @return Array
     */
    public function RetrieveUsuarios($page, $size, $search)
    {
        $data = Usuario::select('usuario.*');

        $data = $data->where(function($q) use ($search) {
            $q->where('Nome', 'LIKE', "%{$search}%")
                ->orWhere('Email', 'LIKE', "%{$search}%")
                ->orWhere('CriadoEm', 'LIKE', "%{$search}%")
                ->orWhere('usuario.IdUsuario', 'LIKE', "%{$search}%")
                ->orWhere('Login', 'LIKE', "%{$search}%")
                ->orWhere('UltimoLogin', 'LIKE', "%{$search}%");
        })
        ->where('Inativo', '=', 'false')
        ->orderBy('Login', 'ASC');
        
        $count = $data->count();
        $items = $data->skip(($page - 1) * $size)->take($size)->get();
        return Pagination::Paginate($items, $count, $page, $size);
    }

    public function GetUserByLogin($login)
    {
        $user = Usuario::where("Login", "=", $login)->first();
        return $user;
    }

    public function GetUserByEmail($email)
    {
        $user = Usuario::where("Email", "=", $email)->first();
        return $user;
    }

    public function GetTokenByUser($idUsuario)
    {
        $date = new DateTime();
        $token = DB::table("token")
                    ->where("IdUsuario", $idUsuario)
                    ->where("Inativo", false)
                    ->where("ExpiraEm", ">", $date->format("Y-m-d H:i:s"))->first();
        
        if (!empty($token))
            DB::statement("UPDATE token SET UltimoLogin = NOW() WHERE IdToken = ?", [$token->IdToken]);
        
        return $token;
    }

    public function SearchToken($token)
    {
        $date = new DateTime();
        return Token::where('Token', '=', $token)->where('ExpiraEm', '>', $date->format("Y-m-d H:i:s"))->first();
    }

    public function SaveToken($token)
    {
        $token->save();
    }

    public function ListUsuarioTipo($page, $size, $search)
    {
        return UsuarioTipo::get();
    }
}