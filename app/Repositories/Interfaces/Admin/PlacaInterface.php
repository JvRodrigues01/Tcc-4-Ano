<?php

namespace App\Repositories\Interfaces\Admin;

interface PlacaInterface
{
    public function ListPlacas($page, $size, $search);

    public function GetPlaca($id);

    public function SavePlaca($placa);

    public function DeletePlaca($placa);
}
