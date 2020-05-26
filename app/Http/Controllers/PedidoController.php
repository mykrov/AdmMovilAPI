<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\ADMBODEGA;
use \App\ADMPARAMETROV;
use \App\ADMPARAMETROBO;
use \App\Cliente;
use \App\ADMDETEGRBOD;
use \App\ADMCABEGRBOD;
use \App\ADMDEUDA;
use \App\ADMCREDITO;


class PedidoController extends Controller
{
    
    public function PostPedido(Request $r)
    {
        $cabecera = $r->cabecera[0];
        $detalles = $r->detalles;
                
        DB::beginTransaction();
        
        try {
        
            $bodega = ADMBODEGA::where('CODIGO','=',$cabecera['bodega'])->first();
            $parametrov = ADMPARAMETROV::first();
            $cliente = Cliente::where('CODIGO','=',$cabecera['cliente'])->first();
            $parametrobo = ADMPARAMETROBO::first();

            $grabaIva = "N";
            if(floatval($cabecera['iva']) > 0){
                $grabaIva = "S";
            }
            
            $date = Carbon::createFromFormat('d-m-Y',$cabecera['fecha_ingreso']);
            
            $cab = new \App\ADMCABEGRESO();
            
            $cab->TIPO = $cabecera['tipo']; 
            $cab->BODEGA = intval($cabecera['bodega']); 
            $cab->NUMERO = $bodega->NOFACTURA + 1; 
            $cab->SERIE = trim($bodega->SERIE); 
            $cab->SECUENCIAL = $parametrov->SECUENCIAL + 1; 
            $cab->NUMPROCESO = null; 
            $cab->NUMPEDIDO = 0; 
            $cab->NUMGUIA = null; 
            $cab->CAMION = null; 
            $cab->CHOFER = null; 
            $cab->DOCREL = null; 
            $cab->NUMEROREL = null; 
            $cab->FECHA = $date->Format('Y-d-m H:i:s'); 
            $cab->FECHAVEN = $date->addDays(intval($cliente->DIASCREDIT))->format('Y-d-m H:i:s'); 
            $cab->FECHADES = $cabecera['fecha_ingreso']; 
            $cab->OPERADOR = "ADM"; 
            $cab->CLIENTE = $cabecera['cliente']; 
            $cab->VENDEDOR = $cabecera['operador']; 
            $cab->PROVEEDOR = null; 
            $cab->SUBTOTAL = $cabecera['subtotal']; 
            $cab->DESCUENTO = $cabecera['descuento']; 
            $cab->IVA = $cabecera['iva']; 
            $cab->NETO = $cabecera['neto']; 
            $cab->TRANSPORTE = 0; 
            $cab->RECARGO = 0; 
            $cab->BODEGADES = 0; 
            $cab->PESO = 0; 
            $cab->VOLUMEN = 0; 
            $cab->MOTIVO = null; 
            $cab->ESTADO = "P"; 
            $cab->ESTADODOC = null; 
            $cab->TIPOVTA = "BIE"; 
            $cab->INTECXC = null; 
            $cab->OBSERVA = $cabecera['observacion']; 
            $cab->COMENTA = ""; 
            $cab->INTEGRADO = null; 
            $cab->SECCON = 0; //Pendiente, se Actualiza al guardar todo.
            $cab->NUMSERIE = null; 
            $cab->NOCARGA = null; 
            $cab->APLSRI = null; 
            $cab->NUMAUTO = null; 
            $cab->NUMFISICO = null; 
            $cab->HORA = $cabecera['hora_ingreso']; 
            $cab->NOMBREPC = "ServidorLaravel"; 
            $cab->claseAjuEgreso = null; 
            $cab->SUBTOTAL0 = 0; 
            $cab->NUMGUIATRANS = null; 
            $cab->GRAVAIVA = $grabaIva; 
            $cab->CREDITO = "N"; 
            $cab->ESTADODESPACHO = "N"; 
            $cab->SECAUTOVENTA = null; 
            $cab->NUMCUOTAS = null; 
            $cab->NUMGUIAREMISION = $bodega->NUMGUIAREMISION + 1; 
            $cab->SBTBIENES = $cabecera['subtotal']; 
            $cab->SBTSERVICIOS = 0; 
            $cab->TIPOCLIENTE = trim($cliente->TIPO); 
            $cab->SUCURSAL = ""; 
            $cab->ACT_SCT = "N"; 
            $cab->NUMPRODUCCION = null; 
            $cab->porentregar = "N"; 
            $cab->ENTREGADA = "N"; 
            $cab->REFERENCIA = null; 
            $cab->FECHA_EMBARQUE = null; 
            $cab->BUQUE = null; 
            $cab->NAVIERA = null; 
            $cab->ALMACENARA = null; 
            $cab->PRODUCTO =null; 
            $cab->CODIGORETAILPRO = null; 
            $cab->GRMFACELEC = "N"; 
            $cab->NOAUTOGRM = null; 
            $cab->mescredito = 0; 
            $cab->tipopago = ""; 
            $cab->numeropagos = 0; 
            $cab->entrada = 0; 
            $cab->valorfinanciado = 0; 
            $cab->porinteres = 0; 
            $cab->montointeres = 0; 
            $cab->totaldeuda = 0; 
            $cab->xsubtotal = 0; 
            $cab->xsubtotal0 = 0; 
            $cab->xdescuento = 0; 
            $cab->xdescuento0 = 0; 
            $cab->xiva = 0; 
            $cab->numerosolicitud = 0; 
            $cab->mesescredito = 0; 
            $cab->ENVIADONESTLE = "N"; 
            $cab->tipotienda = null; 
            $cab->CodShip = null; 
            $cab->NUMPESAJE = 0; 
            $cab->ESFOMENTO = "N";

            $cab->save();
            //Generar Cabecera de Egreso.
            $cabEgr = new ADMCABEGRBOD();

            $cabEgr->SECUENCIAL = $cab->SECUENCIAL;
            $cabEgr->BODEGA = $cab->BODEGA;
            $cabEgr->TIPO = "FAC";
            $cabEgr->NUMERO = $cab->NUMERO;
            $cabEgr->NUMEGRESO = $bodega->NOEGR + 1;
            $cabEgr->FECHA = $cabecera['fecha_ingreso'];
            $cabEgr->ESTADO = "P";

            $cabEgr->save();

            $bodega->NOFACTURA = $cab->NUMERO;
            $bodega->NUMGUIAREMISION = $cab->NUMGUIAREMISION;
            $bodega->NOEGR = $bodega->NOEGR + 1;
            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;

            //Procesado de los Detalles.
            foreach ($detalles as $det) {
                
                $d = new \App\ADMDETEGRESO;
                
                $grabaIvadet = "N";
                if(floatval($det['iva']) > 0){
                    $grabaIvadet = "S";
                }

                $itemData = \App\ADMITEM::where('ITEM','=',trim($det['item']))->first();

                $d->SECUENCIAL = intval($cab->SECUENCIAL);
                $d->LINEA = intval($det['linea']);
                $d->ITEM = $det['item'];
                $d->TIPOITEM = $det['tipo_item'];
                $d->PRECIO = floatval($det['precio']);
                $d->COSTOP = $itemData->COSTOP;
                $d->COSTOU = $itemData->COSTOU;
                $d->CANTIU = intval($det['unidades'])  % $itemData->FACTOR;
                $d->CANTIC = intval($det['cajas']  / $itemData->FACTOR);
                $d->CANTFUN = intval($det['total_unidades']);
                $d->CANTDEV = null;
                $d->SUBTOTAL = floatval($det['subtotal']);
                $d->DESCUENTO = floatval($det['descuento']);
                $d->IVA = floatval($det['iva']);
                $d->NETO = floatval($det['neto']);
                $d->PORDES = floatval($det['pordes']);
                $d->LINEAREL = null;
                $d->TIPOVTA = "V";
                $d->FORMAVTA = $det['forma_venta'];
                $d->GRAVAIVA = $grabaIvadet;
                $d->PORDES2 = 0;
                $d->CANTENTREGADA = 0;
                $d->CANTPORENTREGAR = null;
                $d->DETALLE = "";
                $d->FECHAELA = null;
                $d->FECHAVEN = null;
                $d->LOTE = null;
                $d->preciox = 0;
                $d->serialitem = 0;

                $d->save();

                //Bajar Stock del item.
                $itemData->STOCK = $itemData->STOCK - $d->CANTFUN;
                $itemData->save();
                
                //Bajar Stock de la Bodega.
                $itemBog = \App\ADMITEMBOD::where('ITEM',trim($d->ITEM))
                ->where('BODEGA',$cab->BODEGA)
                ->first();

                $stockBod = $itemBog->STOCK - $d->CANTFUN;

                $result = DB::table('ADMITEMBOD')
                            ->where('ITEM', trim($d->ITEM))
                            ->where('BODEGA',$cab->BODEGA)
                            ->update([
                                'STOCK' =>$stockBod,
                                'ULTFECEGR' => Carbon::now()->Format('Y-d-m')
                            ]);
                
                //Generar el Detalle de Egreso.
                $detEgr = new ADMDETEGRBOD();
                $detEgr->SECUENCIAL =$cab->SECUENCIAL;
                $detEgr->ITEM = $d->ITEM;
                $detEgr->CANTIU = $d->CANTIU;
                $detEgr->CANTIC = $d->CANTIC; 
                $detEgr->COSTOP = $d->COSTOP;
                $detEgr->COSTOU = $d->COSTOU;
                
                $detEgr->save();
            }

            $bodega->save();
            $parametrov->save();
            
            //Generar la Deuda.
            $deuda = new ADMDEUDA();
            $deuda->SECUENCIAL = $cab->SECUENCIAL + 1;
            $deuda->BODEGA = $cab->BODEGA;
            $deuda->CLIENTE = $cab->CLIENTE;
            $deuda->TIPO = $cab->TIPO;
            $deuda->NUMERO = $cab->NUMERO;
            $deuda->SERIE = $cab->SERIE;
            $deuda->SECINV = $cab->SECUENCIAL;
            $deuda->IVA = $cab->IVA;
            $deuda->MONTO = $cab->NETO;
            $deuda->CREDITO = $cab->NETO;
            $deuda->SALDO = 0;
            $deuda->FECHAEMI = $cab->FECHA;
            $deuda->FECHAVEN = $cab->FECHAVEN;
            $deuda->FECHADES = $cab->FECHA;
            $deuda->OPERADOR = "ADM";
            $deuda->VENDEDOR = $cab->VENDEDOR;
            $deuda->OBSERVACION = "Deuda FACELE-App";
            $deuda->NUMAUTO = "";
            $deuda->BODEGAFAC = 0;
            $deuda->SERIEFAC = "";
            $deuda->NUMEROFAC = 0;
            $deuda->ACT_SCT = "N";
            $deuda->montodocumento = 0;
            $deuda->tipoventa = "";
            $deuda->mesescredito = 0;
            $deuda->tipopago = "";
            $deuda->numeropagos = 0;
            $deuda->entrada =0;
            $deuda->valorfinanciado = 0;
            $deuda->porinteres = 0;
            $deuda->montointeres = 0;
            $deuda->totaldeuda = 0;
            $deuda->seccreditogen = 0;
            $deuda->secdeudagen = 0;
            $deuda->numcuotagen = 0;
            $deuda->porinteresmora = 0;
            $deuda->basecalculo = 0;
            $deuda->diasatraso = 0;
            $deuda->usuarioeli = 0;
            $deuda->EWEB = "N";
            $deuda->ESTADOLIQ = "N";
            
            //Generar el Credito.
            $credito = new \App\ADMCREDITO();
            $credito->SECUENCIAL = $deuda->SECUENCIAL;
            $credito->BODEGA = $deuda->BODEGA;
            $credito->CLIENTE = $deuda->CLIENTE;
            $credito->TIPO = $deuda->TIPO;
            $credito->NUMERO = $deuda->NUMERO;
            $credito->SERIE = $deuda->SERIE;
            $credito->SECINV = $deuda->SECINV;
            $credito->FECHA = $deuda->FECHA;
            $credito->MONTO = $deuda->MONTO ;
            $credito->SALDO = $deuda->MONTO ;
            $credito->OPERADOR = "ADM";
            $credito->OBSERVACION = "Credito FACELE-App" ;
            $credito->VENDEDOR = $deuda->VENDEDOR;
            $credito->estafirmado = "N";
            $credito->ACT_SCT = "N";
            $credito->seccreditogen = 0 ;

            $parametrov = ADMPARAMETROV::first();
            $deuda->save();
            $credito->save();
            $parametrov->SECUENCIAL =  $parametrov->SECUENCIAL + 1;
            $parametrov->save();

            //Guardado de todo en caso de exito en las operaciones.
            DB::commit();
            return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL]);
            

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>["info"=>$e->getMessage()]]);
        }

        
    }

}


// ADMCABEGRESO
// ADMDETEGRESO

// ADMCABEGRBOD
// ADMDETEGRBOD

// ADMDEUDA
// ADMCREDITO

// ADMCABCOMPROBANTE
// ADMDETCOMPROBANTE

// ADMITEM
// ADMITEMBO



