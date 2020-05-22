<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\ADMBODEGA;
use \App\ADMPARAMETROV;
use \App\Cliente;


class PedidoController extends Controller
{
    public function PostPedido(Request $r)
    {
        $cabecera = $r->cabecera[0];
        $detalles = $r->detalles;
        
        //return response()->json($cabecera);
        $bodega = ADMBODEGA::where('CODIGO','=',$cabecera['bodega'])->first();
        $parametrov = ADMPARAMETROV::first();
        $cliente = Cliente::where('CODIGO','=',$cabecera['cliente'])->first();

        //return response()->json($bodega->NOFACTURA);

        $grabaIva = "N";
        if(floatval($cabecera['iva']) > 0){
            $grabaIva = "S";
        }
       
        $cab = new \App\ADMCABEGRESO();
        
        $cab->TIPO = $cabecera['tipo']; 
        $cab->BODEGA = intval($cabecera['bodega']); 
        $cab->NUMERO = $bodega->NOFACTURA + 1; //Actualizar el final
        $cab->SERIE = trim($bodega->SERIE); 
        $cab->SECUENCIAL = $parametrov->SECUENCIAL + 1; //Actualizar el final
        $cab->NUMPROCESO = null; 
        $cab->NUMPEDIDO = 0; 
        $cab->NUMGUIA = null; 
        $cab->CAMION = null; 
        $cab->CHOFER = null; 
        $cab->DOCREL = null; 
        $cab->NUMEROREL = null; 
        $cab->FECHA = $cabecera['fecha_ingreso']; 
        $cab->FECHAVEN = $cabecera['fecha_ingreso']; //Sumar los dias de credito del Cliente.
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
        $cab->NUMGUIAREMISION = $bodega->NUMGUIAREMISION + 1; //Actualizar al final
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

        $bodega->NOFACTURA = $cab->NUMERO;
        $bodega->NUMGUIAREMISION = $cab->NUMGUIAREMISION;
        $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;

        $cab->save();
        $bodega->save();
        $parametrov->save();
        return response()->json($cab);
    }
}
