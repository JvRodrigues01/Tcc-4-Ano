<?php

namespace App\Repositories\Interfaces\Admin;

interface LogInterface
{
    public function SaveLogs($entidade = null, $idEntidade = null, $data = null, $user = null, $acao = null, $sucesso = false);

    public function ListLogs($page, $size, $search = null, $usuario = null, $dataInicio = null, $dataFim = null);
}
