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
Route::get('/clientexvdia/{vendedor}','Cliente@ClienteXVendedorDia');
Route::get('/cliente/{id?}','Cliente@byID');
Route::get('/clienteruc/{ruc?}','Cliente@byRUC');
Route::post('/cliente','Cliente@CreateClient')->middleware('EmpresaPago');
Route::get('/clientedia','Cliente@ClienteXDia'); //Cliente por del dia actual del server
Route::post('/clienteven','Cliente@CreateClientVen')->middleware('EmpresaPago');
Route::post('/clientebasico','Cliente@CreateClientBasic')->middleware('EmpresaPago');
Route::get('/clientelike/{nombre}','Cliente@BuscarNombre');
Route::get('/clientelikexv/{nombre}/{vendedor}','Cliente@BuscarNombreXVendedor');
Route::get('/clientelikexdia/{nombre}','Cliente@BuscarNombreXDia');
Route::get('/clientexcd/{codigo}','Cliente@ClienteCodDia');
Route::get('/clientexvcod/{id}/{vendedor}','Cliente@BuscarIdXVendedor');
Route::get('/clientelikexvd/{nombre}/{vendedor}','Cliente@ClienteLikeDiaVende');
Route::get('/clientelikexvc/{codigo}/{vendedor}','Cliente@ClienteLikeCodVende');
Route::get('/clixruta/{ruta}','Cliente@ClientesPorRuta');
Route::get('/clisucursal/{cliente}','SucursalController@GetSucursales');

//Items
Route::get('/items/{bod}','Item@ItemXBodega');
Route::get('/itemstodos/{bod}','Item@ItemTodosBodega');
Route::get('/items/{bod}/{categoria}','Item@ItemBodegaXCategoria');
Route::get('/item/{codigo}/{bod}','Item@ItemXCodigo');
Route::get('/itemslike/{like}/{bodega}','Item@ItemsXNombre');
Route::get('/itemsnostock/{bod}','Item@ItemsSinStock');
Route::get('/categoria','CategoriaController@GetCategorias');
Route::get('/itemregalo/{item}','ItemRegaloController@ItemRegalo');
Route::get('/itemregalotodos','ItemRegaloController@TodosItemsRegalo');
Route::get('/itemselec/{bodega}','ItemElectroController@GetItemsElectro');
Route::get('/eleclike/{bodega}/{nombre}','ItemElectroController@GetItemsElectroNombre');
Route::get('/eleccod/{bodega}/{cod}','ItemElectroController@GetItemsElectroCod');
Route::post('/codbarra','Item@ItemBarCode');

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
Route::get('/condicomerxi/{item}','CondiComercialesController@GetCondiComerPorProducto');
Route::get('/frecuencia','FrecuenciaController@GetFrecuencias');
Route::get('/tiponego','TipoNegocioController@GetTipoNegocios');
Route::get('/itemtop','ItemTopController@GetItemTop');
Route::get('/itemxcliente','ItemXClienteController@GetItemXCliente');
Route::get('/bancos','Banco@GetBancos');
Route::get('/bacoscia','BancoCiaController@GetBancoCia');
Route::get('/puntosemi','PuntosEmisionController@GetPuntos');

Route::get('/diascredito','DiasCreditoController@GetDiasCredito');
Route::get('/formapago','FormaPagoController@GetFormaPago');
Route::get('/cliprecio','CliPrecioController@GetCliPrecio');

//Deudas
Route::get('/deuda','DeudaController@GetDeudas');
Route::get('/deudaxv/{vendedor}','DeudaController@GetDeudasXVendedor');
Route::get('/deuda/{codigo}','DeudaController@GetDeudaXCliente');
Route::get('deudatotal/{vendedor}','DeudaTotalXVendedor@DeudaTotal');

//Deudas POS
Route::get('/deudapos','DeudaPosController@GetDeudas');
Route::get('/deudapos/{codigo}','DeudaPosController@GetDeudaXCliente');


//Rutas Pedidos
Route::post('/pedido','PedidoController@PostPedido')->middleware('EmpresaPago');
Route::post('/proformaoff','PedidoXController@PostPedidoProformaOff');
Route::post('/proforma','PedidoProformaController@PostPedidoProforma')->middleware('EmpresaPago');
Route::post('/proformaedit','ProformaEdicionController@EditarProforma');
Route::post('/proformadelete','ProformaEdicionController@EliminarPedido');

//Ruta Ventas POS
Route::post('/ventapos','VentaPos@Pedido')->middleware('EmpresaPago');
Route::get('/rangopre/{it}','RangoPrecio@RangoPrecioItem');
Route::get('/rangoprepunto/{it}/{punto}','RangoPrecio@RangoPrecioItemPunto');

//Facturas por vendedor
Route::post('/detxvende','FacturasController@GetDetalles');
Route::post('/factxvende','FacturasController@GetCabeceras');

//FacturasPOS por vendedor
Route::post('/detxvendepos','VentasController@GetDetalles');
Route::post('/factxvendepos','VentasController@GetCabeceras');
Route::post('/factxvendepos2','VentasController@GetCabeceras2');

//Proformas Detalles
Route::post('/detxproforma','ProformasController@GetDetalles');
Route::post('/cabxproforma','ProformasController@GetCabecera');

//Pagos
Route::post('/pago','PagosController@Pago')->middleware('EmpresaPago');
Route::get('/mediospago','MedioPagoController@GetMedioPago');
Route::post('/pagosposa','PagosPosController@Pagopos')->middleware('EmpresaPago');

Route::post('/pagopostcab','PagPosConsultaController@GetCabeceras');
Route::post('/pagopostdet','PagPosConsultaController@GetDetalles');
Route::post('/pagoanticipo','GenerarAnticipo@PagoAnticipo');

//Credito
Route::get('/credito/{num}','CreditoController@GetCredito');
Route::get('/creditopos/{num}','CreditoController@GetCreditoPos');

//Informe de Pagos
Route::post('/detpagos','InfoPagoController@GetDetallesPagos');
Route::post('/cabpagos','InfoPagoController@GetPagoCab');
//InformePago por operador
Route::post('/opecabpagos','InfoPagoOperadorController@GetPagoOperadorCab');

//Actualizar Ubicacion del Cliente
Route::post('/actubicacion','UbicacionController@UpdateCoordinates');

//Test
Route::get('/invertir/{cadena}','PedidoController@InvertirCadena');
Route::get('/tipoitem/{item}','VentaElectroController@tipoItemElec');
Route::get('/testemail','VentaElectroController@TestEmail');

//Mapas
Route::get('/vendemaps','MapsController@GetVendedores');
Route::post('/pedmaps','MapsController@GetPedidos');
Route::get('/cli/{dia}','MapsController@GetClientsByDay');

//Numeros a letras
Route::post('/numeroletra','NumLetrasController@ConvertirNumeros');

//NotoficacionPush
Route::get('/notipush/{item}','PushNotificacionController@NewItem');
Route::get('/notipushtest','PushNotificacionController@NewItem2');

//Guias de Cobro
Route::get('/guiacobro/{numero}','GuiaCobroController@GetGuiaCobro');
Route::get('/guiacobrouni/{numero}','GuiaCobroController@GetGuiaCobroUnificada');
Route::get('/deudacuotas/{sec}','DeudaCuotasController@GetDeudaCuotas');
Route::post('/pagocuota','PagoCuotasController@PagoCuota')->middleware('EmpresaPago');

//DeudasCuotasDet
Route::get('/deudacuotasdet/{pago}','DeudasCoutasDetController@CuotasAfectadas');
Route::get('/detguiacob/{guia}/{secuencial}','DeudasCoutasDetController@ItemDetGuia');

Route::post('/historicopre','HistPrecioController@GetHistorico');
Route::post('/clidiaveninfo','InformeVisitaController@GetClientesDiaVendedor');

//Items de Electrodomesticos
Route::get('/itemelec/{item}/{bodega}','ElectroController@GetItem');
Route::get('/itemsserieele/{bod}','ElectroController@GetItemTodos');
Route::get('/itemregalobod/{bod}','ElectroController@GetItemRegalosTodos');
Route::get('/itemliquida/{item}/{bodega}','ElectroController@GetItemLiquidacion');
Route::get('/itemliquidatodos/{bodega}','ElectroController@GetItemLiquidacionTodos');
Route::post('/ventaelec','VentaElectroController@PostPedidoElectro');
Route::get('/itemregalo','Item@itemsDeRegalo');

//Dias restantes
Route::get('/diasrestantes','VerificaFechaController@DiasRestantes');
Route::get('/vendedores','Vendedor@listado');

//Visitas
Route::post('/visitaxvende','VisitaClienteController@GetVisitas');
Route::post('/visitaxruta','VisitaClienteController@GetVisitasRuta');
Route::post('/visitamarca','VisitaClienteController@SaveVisita');

//Ventas Electrodomésticos
Route::post('/ventaelectro','VentaCreditoController@PostVentaCre');
Route::get('/gencuotas','VentaCreditoController@CrearCuotas');
Route::post('/proformacred','ProformaElectroController@PostProformaCredito');

//Retios
Route::post('retiro','RetiroController@GuardaRetiro');
Route::get('motretiro','RetiroController@getMotivosRetiros');

//Orden Retiro
Route::post('ordenretcab','OrdenRetiroController@getCabOrders');
Route::post('ordenretdet','OrdenRetiroController@getDetOrders');

//Validar instalacion
Route::post('validarinst','ValInstallController@CheckCode');

Route::middleware('auth:api')->group(function () {
    Route::get('/vendedorinfo',function (Request $request) {
        return $request->user();
    });
});