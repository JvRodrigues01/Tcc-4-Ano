<?php

namespace App\Functions;

use DateTime;
use App\Functions\Storage;
use Illuminate\Support\Facades\DB;

//Classe que contém funções para uso em logs do sistema, desde exceptions até mesmo trace
class Log
{

    /**
     * Método genérico de log do sistema
     * 
     * @param string $login Login do usuário a ser inserido no log
     * @param string $camada Camada do projeto de onde a ação foi executada
     * @param string $acao determinante de metodo ou ação executada
     * @param string $tipoLog tipo de log a ser persistido, podendo ser Exception ou Trace
     * @param array $conteudo array com o conteudo a ser logado
     * 
     * @return void
     */
    public static function Log($login = "Sistema", $camada, $acao, $tipoLog = "Exception", array $conteudo = null)
    {
        if(env("USE_LOCAL_LOG")==1){
            try{
                $date = new DateTime();
                $date->format("Y-m-d H:i:s");
                
                DB::connection('log')->insert('insert into log (Login, Data, Camada, Acao, TipoLog, Conteudo) values (?, ?, ?, ?, ?, ?)', [$login, $date, $camada, $acao, $tipoLog, json_encode($conteudo)]);
                
            }catch (\Exception $ex){
                throw $ex;
            }
        }
    }

}