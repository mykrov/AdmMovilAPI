<?php

namespace App\Http\Controllers;

use App\ADMOPERADOR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ValInstallController extends Controller
{
    public function CheckCode(Request $r){
        
        $operador = $r['operador'];
        $code = $r['codigo'];

        $dataOpe = ADMOPERADOR::where('CODIGO', $operador)
        ->where('ESTADO','A')
        ->where('CLAVEALE',$code)
        ->count();

        if($dataOpe == 1 && $code != '0'){
            
            $dataOpe2 = ADMOPERADOR::where('CODIGO', $operador)
            ->where('ESTADO','A')
            ->where('CLAVEALE',$code)
            ->first();

            $dataOpe2->CLAVEALE = 0;
            $dataOpe2->save();

            return response()->json(['status' => 'ok','message' => 'Operador Autorizado']);

        }else{
            return response()->json(['status' => 'error','message' => 'Operador No Autorizado']);
        }

    }
}
