<?php

namespace App\Repositories\Interfaces\Admin;

interface ResidenciaInterface
{
    public function ListResidencias($page, $size, $search);

    public function GetResidencia($id);

    public function SaveResidencia($residencia);
}
