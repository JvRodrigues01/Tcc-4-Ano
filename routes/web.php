<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function (){
    return response()->json('Unauthorized.', 401);
});

$router->group(['prefix' => env('API_VERSION', 'api/v1')], function ($router){
    $router->group(['prefix' => 'auth', 'namespace' => 'auth'], function ($router) {
        $router->post("/login", "LoginController@Login");
        $router->post("/recuperar-senha", "LoginController@RecuperarSenha");
    }); 

    $router->group(['prefix' => 'admin', 'middleware' => 'auth', 'namespace' => 'admin'], function ($router) {
        $router->group(['prefix' => 'usuario'], function ($router) {
            $router->post('/', 'UsuarioController@ListUsuarios');
            $router->post('criar[/{id}]', 'UsuarioController@CreateOrUpdateUsuario');
            $router->post('usuariotipo/', 'UsuarioController@ListUsuarioTipo');
        });
        $router->group(['prefix' => 'placa'], function ($router) {
            $router->post('/', 'PlacaController@ListPlacas');
            $router->post('criar[/{id}]', 'PlacaController@CreateOrUpdatePlaca');
            $router->get('/{id}', 'PlacaController@GetPlaca');
            $router->get('/{id}', 'PlacaController@DeletePlaca');
        });
        $router->group(['prefix' => 'residencia'], function ($router) {
            $router->post('/', 'ResidenciaController@ListResidencias');
            $router->post('criar[/{id}]', 'ResidenciaController@CreateOrUpdateResidencia');
            $router->get('/{id}', 'ResidenciaController@GetResidencia');
            $router->get('/{id}', 'ResidenciaController@DeleteResidencia');
        });
    });
});