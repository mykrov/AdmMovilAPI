<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Closure;

class VerificaPago
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $fechVenceBase = Cache::remember('fechaBase5',180, function () {
            return DB::table('ADMPARAMETROBO')->first();
        });

        $dc = $fechVenceBase->fechapedido;

         if ($dc == null){
             return $next($request);
         }


         
        //if (true){
        //    return $next($request);
        //}

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
        //return response()->json(implode($numeroreales));
        $fechVence = Carbon::createFromFormat('dmY', implode($numeroreales));
        $fechActual = Carbon::now()->subHours(5);

        if($fechActual <= $fechVence){
            return $next($request);
        }else{
            return response()->json(['error'=>['info'=>'Empresa Inactiva por pago.']]);
        } 
    }
}
