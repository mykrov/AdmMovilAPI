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
Route::get('/clientetodos','Cliente@listado2');
Route::get('/clientexv/{vendedor}','Cliente@ClienteXVendedor');
Route::get('/cliente/{id?}','Cliente@byID');
Route::post('/cliente','Cliente@CreateClient');
Route::post('/clienteven','Cliente@CreateClientVen');
Route::get('/clientelike/{nombre}','Cliente@BuscarNombre');

Route::get('/items/{bod}','Item@ItemXBodega');
Route::get('/items/{bod}/{categoria}','Item@ItemBodegaXCategoria');
Route::get('/item/{codigo}','Item@ItemXCodigo');
Route::get('/itemslike/{like}','Item@ItemsXNombre');
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
Route::get('/parambasico','ParametrosController@GetParametrosLite');
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

//Deudas
Route::get('/deuda','DeudaController@GetDeudas');
Route::get('/deuda/{codigo}','DeudaController@GetDeudaXCliente');

//Deudas POS
Route::get('/deudapos','DeudaPosController@GetDeudas');
Route::get('/deudapos/{codigo}','DeudaPosController@GetDeudaXCliente');


//Rutas Pedidos
Route::post('/pedido','PedidoController@PostPedido');

//Ruta Ventas POS
Route::post('/ventapos','VentaPos@Pedido');
Route::get('/rangopre/{it}','RangoPrecio@RangoPrecioItem');

//Facturas por vendedor
Route::post('/detxvende','FacturasController@GetDetalles');
Route::post('/factxvende','FacturasController@GetCabeceras');

//FacturasPOS por vendedor
Route::post('/detxvendepos','VentasController@GetDetalles');
Route::post('/factxvendepos','VentasController@GetCabeceras');


//Test
Route::get('/invertir/{cadena}','PedidoController@InvertirCadena');

Route::middleware('auth:api')->group(function () {
    Route::get('/vendedores','Vendedor@listado');
    Route::get('/vendedorinfo',function (Request $request) {
        return $request->user();
    });
});

