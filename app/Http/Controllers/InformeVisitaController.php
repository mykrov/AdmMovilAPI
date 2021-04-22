<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InformeVisitaController extends Controller
{
    public function GetClientesDiaVendedor(Request $r)
    {
        $diaR = $r->DIA;
        $vendedor = $r->VENDEDOR;

        if($diaR != 7){
            $dia = $diaR + 1;
        }else {
            $dia = 1;
        }

        $clientesDelDia = DB::table('ADMCLIENTE')
        ->select(DB::raw('RTRIM(ADMCLIENTE.CODIGO) as CODIGO,
                        RTRIM(ADMCLIENTE.RAZONSOCIAL) as RAZONSOCIAL,
                        RTRIM(ADMCLIENTE.NEGOCIO) as NEGOCIO,
                        RTRIM(ADMCLIENTE.RUC) as RUC,
                        RTRIM(ADMCLIENTE.DIRECCION) as DIRECCION,
                        RTRIM(ADMCLIENTE.TELEFONOS) as TELEFONOS,
                        RTRIM(ADMCLIENTE.EMAIL) as EMAIL,
                        RTRIM(ADMCLIENTE.TIPO) as TIPO,
                        RTRIM(ADMCLIENTE.GRUPO) as GRUPO,
                        RTRIM(ADMCLIENTE.ESTADO) as ESTADO,
                        RTRIM(ADMCLIENTE.longuitud) as longuitud,
                        RTRIM(ADMCLIENTE.latitud) as latitud'))
        ->where('DIA','=',$dia)
        ->where('VENDEDOR','=',$vendedor)
        ->where('ESTADO','=','A')
        ->where(DB::raw('RTRIM(latitud)'),'<>','')
        ->where(DB::raw('RTRIM(latitud)'),'<>','0')
        ->get();

        return response()->json($clientesDelDia);
    }
}
