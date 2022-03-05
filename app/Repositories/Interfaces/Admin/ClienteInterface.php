<?php

namespace App\Repositories\Interfaces\Admin;

interface ClienteInterface
{
    public function ListClientes($page, $size, $search);

    public function GetCliente($id);

    public function SaveCliente($cliente);

    public function DeleteCliente($cliente);
}
