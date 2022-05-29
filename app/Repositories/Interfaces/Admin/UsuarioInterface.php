<?php

namespace App\Repositories\Interfaces\Admin;

interface UsuarioInterface
{
    public function SearchUsuario($id);

    public function SaveUsuario($usuario);

    public function DeleteUsuario($id);

    public function RetrieveUsuarios($page, $size, $search);
    
    public function GetUserByLogin($login);

    public function GetUserByEmail($email);
    
    public function GetTokenByUser($idUsuario);
    
    public function SearchToken($token);
    
    public function SaveToken($token);

    public function SearchByEmail($email);
    
    public function ListUsuarioTipo($page, $size, $search);

    public function GetUserByToken($token);
}
