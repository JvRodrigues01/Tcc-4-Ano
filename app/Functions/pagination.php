<?php

namespace App\Functions;

//Classe que contem funções para uso da paginação
class Pagination
{
    public static function Paginate($data, $count, $page, $size)
    {
        $result = [
            'Registers' => $data,
            'Count' => $count,
            'Size' => $size,
            'Page' => $page,
            'TotalPages' => intval(ceil($count / $size))
        ];
        return $result;
    }    
}