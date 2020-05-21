<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/login','Vendedor@login');
Route::get('/setpassword','Vendedor@SetearPasswordHash');

//Rutas Clientes
Route::get('/cliente','Cliente@listado');
Route::get('/clientexv/{vendedor}','Cliente@ClienteXVendedor');
Route::get('/cliente/{id?}','Cliente@byID');
Route::post('/cliente','Cliente@CreateClient');

Route::get('/items/{bod}','Item@ItemXBodega');
Route::get('/categoria','CategoriaController@GetCategorias');

//Rutas Ubicacion
Route::get('/canton','CantonController@GetCantones');
Route::get('/provincia','ProvinciaController@GetProvincias');
Route::get('/parroquia','ParroquiaController@GetParroquias');
Route::get('/zona','ZonaController@GetZonas');
Route::get('/sector','SectorController@GetSector');
Route::get('/ruta','RutaController@GetRutas');

//Rutas Configuraciones
Route::get('/parametro','ParametrosController@GetParametros');
Route::get('/grupocli','GrupoCliController@GetGrupoCli');
Route::get('/tiposcli','TipoClienteController@GetTipoClientes');
Route::get('/grupospro','GrupoProductoController@GetGrupoProductos');
Route::get('/condicomer','CondiComercialesController@GetCondiComer');
Route::get('/frecuencia','FrecuenciaController@GetFrecuencias');
Route::get('/tiponego','TipoNegocioController@GetTipoNegocios');

Route::get('/itemtop','ItemTopController@GetItemTop');
Route::get('/itemxcliente','ItemXClienteController@GetItemXCliente');
Route::get('/diascredito','DiasCreditoController@GetDiasCredito');
Route::get('/formapago','FormaPagoController@GetFormaPago');
Route::get('/cliprecio','CliPrecioController@GetCliPrecio');

Route::middleware('auth:api')->group(function () {
    Route::get('/vendedores','Vendedor@listado');
});