<?php

namespace App\Functions;

//Classe que contem funções para uso da paginação
class Pagination
{
    public static function Paginate($data, $count, $page, $size)
    {
        $result = [
            'Registros' => $data,
            'Contagem' => $count,
            'Tamanho' => $size,
            'Pagina' => $page,
            'TotalPaginas' => intval(ceil($count / $size))
        ];
        return $result;
    }    
}