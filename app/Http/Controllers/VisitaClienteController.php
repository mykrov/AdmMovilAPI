<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ADMVISITACLI;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VisitaClienteController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetVisitas(Request $r)
    {
        $vendedor = $r['VENDEDOR'];
        
        $f1 = Carbon::createFromFormat('d-m-Y',$r['FECHAINI']);
        $f2 = Carbon::createFromFormat('d-m-Y',$r["FECHAFIN"]);
        
        $fecha1 = $f1->Format('Y-m-d');
        $fecha2 = $f2->Format('Y-m-d');
        
        $visitas = DB::table("ADMVISITACLI")->where("ADMVISITACLI.VENDEDOR","=",$vendedor)
        ->whereBetween("ADMVISITACLI.FECHAVISITA",[$fecha1,$fecha2])
        ->join('ADMCLIENTE','ADMCLIENTE.CODIGO','=','ADMVISITACLI.CLIENTE')
        ->select('ADMVISITACLI.CLIENTE','ADMCLIENTE.RAZONSOCIAL','ADMCLIENTE.DIRECCION','ADMCLIENTE.NEGOCIO',
        'ADMVISITACLI.FECHAVISITA','ADMVISITACLI.LATITUD','ADMVISITACLI.LONGITUD','ADMVISITACLI.VENDEDOR',
        'ADMVISITACLI.NUMPEDIDO')
        ->get();

        return response()->json($visitas);

    }

    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetVisitasRuta(Request $r)
    {
        $ruta = $r['RUTA'];
        
        $f1 = Carbon::createFromFormat('d-m-Y',$r['FECHAINI']);
        $f2 = Carbon::createFromFormat('d-m-Y',$r["FECHAFIN"]);
        
        $fecha1 = $f1->Format('Y-m-d');
        $fecha2 = $f2->Format('Y-m-d');
        
        $visitas = DB::select(DB::raw("SELECT ISNULL(a.CLIENTE,b.CODIGO) as 'CLIENTE',b.RAZONSOCIAL,ISNULL(a.LATITUD,b.latitud) as 'LATITUD',ISNULL(a.LONGITUD,b.longuitud) as 'LONGITUD',
        ISNULL(a.NUMPEDIDO,0) as 'NUMPEDIDO',RTRIM(b.DIRECCION) as 'DIRECCION',RTRIM(b.NEGOCIO)as 'NEGOCIO',ISNULL(a.VISITADO,'N') as 'VISITADO',ISNULL(a.FECHAVISITA,NULL) as 'FECHAVISITA'
        FROM ADMVISITACLI a
        RIGHT JOIN ADMCLIENTE b  ON 
        b.CODIGO = a.CLIENTE and a.FECHAVISITA between '".$f1."' and '".$f2."' 
        WHERE b.RUTA = '" .$ruta."'"));       
     
        return response()->json($visitas);

    }

    // Metodo para registrar la visita de un cliente.
    public function SaveVisita(Request $r)
    {
        $vendedor = $r['VENDEDOR'];
        $visitado = $r['VISITADO'];
        $numPedido = $r['NUMPEDIDO'];
        $lat = $r['LAT'];
        $lon = $r['LON'];
        $cliente = $r['CLIENTE'];

        $visita = new ADMVISITACLI();
        $visita->CLIENTE = $cliente;
        $visita->VENDEDOR = $vendedor;
        $visita->NUMPEDIDO = $numPedido;
        $visita->LATITUD = $lat;
        $visita->LONGITUD = $lon;
        $visita->VISITADO  = "S";
        $visita->FECHAVISITA = Carbon::now()->format('Y-m-d');
        
        try {
            $visita->save();
            return response()->json(["estado"=>"ok"]);
        } catch (\Exception $e) {
            return response()->json(["error"=>$e->getMessage()]);
        }
       

    }

}
