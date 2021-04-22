<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class VerificaFechaController extends Controller
{
    public function DiasRestantes()
    {
        $fechVenceBase = Cache::remember('fechaBase34',5, function () {
            return DB::table('ADMPARAMETROBO')->first();
        });

        $dc = $fechVenceBase->fechapedido;
        //return response()->json($dc);
        if ($dc == null){
            return response()->json(['dias'=>1000]);
        }

        $substrin = substr($dc,0,8);
        $numeroreales = [];
        $array = str_split($substrin);
        
        foreach ($array as $key => $value) {
            try {
                if(ord($value) % 2 == 0){
                    array_push($numeroreales,chr(ord($value) - 1));
                }else{
                    array_push($numeroreales,chr(ord($value) - 3));
                }
            } catch (\Exception $e) {
                return response()->json(['key'=>$key,'val'=>$value,"message"=> $e->getMessage()]);
            }
        }
        
        
        $fechVence = Carbon::createFromFormat('dmY', implode($numeroreales))->subHours(5);
        $fechActual = Carbon::now()->subHours(5);
        
        $diasRestantes = $fechVence->diffInDays($fechActual);
        return response()->json(['dias'=>$diasRestantes,'mensaje'=>'Su factura del Sistema ADM GO vence el '.$fechVence->Format('d-m-Y').', tiene '.$diasRestantes.' días para cancelar la factura.']);
        
    }
}
