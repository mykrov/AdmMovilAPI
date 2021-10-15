<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\ADMCABRETIRO;
use App\ADMDETRETIRO;
use App\ADMPARAMETROBO;

class RetiroController extends Controller
{
    public function getMotivosRetiros(){
        
        $motivos = DB::table('ADMMOTIVORETIRO')
        ->where('ESTADO','A')
        ->get();

        return response()->json($motivos);
    }

    public function GuardaRetiro(Request $r){

        $cabecera = $r['cabecera'][0];
        $detalles = $r['detalles'];

        $parametrobo = ADMPARAMETROBO::first();
        $numeroLiqu = $parametrobo->NUMLIQUIDACION + 1;   
        
        //return response()->json($detalles);

        $date = Carbon::now();
        $cabRet = new ADMCABRETIRO();
        $cabRet->NUMERO = $numeroLiqu;
        $cabRet->CLIENTE = $cabecera['cliente'];
        $cabRet->VENDEDOR = $cabecera['vendedor'];
        $cabRet->OBSERVACION = $cabecera['observacion'];
        $cabRet->OPERADOR = $cabecera['operador'];
        $cabRet->FECHA = $date->format('Y-d-m');
        $cabRet->HORA = $date->format('H:i:s');
        $cabRet->NOMBREPC = 'App Movil';
        $cabRet->ESTADO = 'P';

        DB::beginTransaction();
        try {

            $cabRet->save();
            $parametrobo->NUMLIQUIDACION = $cabRet->NUMERO;
            $parametrobo->save();

            foreach ($detalles as $det) {

                $d = new ADMDETRETIRO();
                $d->NUMERO = $cabRet->NUMERO;
                $d->LINEA = $det['linea'];
                $d->ITEM = $det['item'];
                $d->CANTC = $det['cantc'];
                $d->CANTU = $det['cantu'];
                $d->CANTRET = $det['cantret'];
                $d->MOTIVO = $det['motivo'];
                $d->CANTAPR = 0;
                $d->CANTCAPR = 0;
                $d->CANTUAPR = 0;
                $d->save();
            }
            DB::commit();
            return response()->json(["estado"=>"guardado", "Nretiro"=>$cabRet->NUMERO, "fecha"=>$cabRet->FECHA]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['error'=>["info"=>'Error en el proceso de guardado']]);
        } 
    }
}

