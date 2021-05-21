<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use \App\ADMVENDEDOR;
use \App\ADMCABPEDIDO;
use Illuminate\Support\Facades\DB;

class MapsController extends Controller
{
    public function GetClientsByDay($dia){
        
        //Correnccion del dia con respecto al ADM
        $diaMasConsul = $dia;
        if($dia == 7){
            $diaMasConsul = 0;
        }
        $diaMasConsul = $dia + 1;

        $clients = \App\Cliente::where('DIA','=',$diaMasConsul)
        ->where('latitud','<>','null')
        ->select('RUC as Ruc','VENDEDOR as Vendedor','CODIGO as Codigo','RAZONSOCIAL as Nombre',
        'latitud as Latitud','longuitud as Longitud','DIRECCION as Direccion',
        'DIA as DiaVisita',DB::raw("(SELECT 1) AS Sucursal"),'FECULTCOM as UltVisita')
        ->get();
        return response()->json($clients);
        
    }

    public function GetSucursales(){

        $sucursales = DB::table('ADMSUCURSAL')->all();
        return response()->json($sucursales);
    }

    public function GetVendedores(){

        $venderores = ADMVENDEDOR::where('ESTADO','=','A')
        ->select(DB::raw("(SELECT 1) AS Id"),'SUPERVISOR as Ruc', DB::raw("(SELECT 1) AS Sucursal"),'CODIGO as Codigo','NOMBRE as Nombre')
        ->get();
        return response()->json($venderores);

    }

    public function GetPedidos(Request $r){

        $fecha = $r['desde'];

        if($fecha == ''){
            $fecha = Carbon::now();
        }
      
        $data = DB::select(DB::raw("exec PedidosXfechaMaps :Param1"),[
            ':Param1' => $fecha
        ]);

        return response()->json($data);
        
    }


    public function NumerosLetras(String $cantida){

    }
}

