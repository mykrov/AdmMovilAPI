<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuiaCobroController extends Controller
{
    public function GetGuiaCobro(int $numero)
    {
        $cabecera = \App\ADMCABGUIACOB::where('NUMGUIA','=',$numero)
        ->get();

        $detalles2 = DB::table("ADMDETGUIACOB")
        ->where('SALDO','>',0)
        ->where('NUMGUIA','=',$numero)
        ->select('NUMGUIA','SECUENCIAL',DB::raw('RTRIM(CLIENTE) as CLIENTE'),'TIPO','NUMERO','SERIE','FECHAEMI','FECHAVEN','MONTO','SALDO','EFECTIVO','CHEQUE','FUENTE','IVA','DESCUENTO','OTRO','NOCOBRO','ESTADO','NRECIBO','DEPOSITO','ARTICULO','VALORCUOTA','FECULTPAG','VALORULTPAG')
        ->get();
        
        return response()->json(['cabecera'=>$cabecera,'detalles'=>$detalles2]);
    }   

    public function GetGuiaCobroUnificada(int $numero)
    {
        $numeroGuia = $numero;
        $dataClienteFull = DB::table('ADMCLIENTE')
        ->select(DB::raw('RTRIM(ADMCLIENTE.CODIGO) as CODIGO,
                        RTRIM(ADMCLIENTE.RAZONSOCIAL) as RAZONSOCIAL,
                        RTRIM(ADMCLIENTE.NEGOCIO) as NEGOCIO,
                        RTRIM(ADMCLIENTE.RUC) as RUC,
                        RTRIM(ADMCLIENTE.DIRECCION) as DIRECCION,
                        RTRIM(ADMCLIENTE.TELEFONOS) as TELEFONOS,
                        RTRIM(ADMCLIENTE.EMAIL) as EMAIL,
                        RTRIM(ADMCLIENTE.TIPO) as TIPO,
                        RTRIM(ADMCLIENTE.GRUPO) as GRUPO,
                        RTRIM(ADMCLIENTE.ESTADO) as ESTADO'))
        ->join('ADMDETGUIACOB','ADMDETGUIACOB.CLIENTE','ADMCLIENTE.CODIGO')
        ->where('ADMDETGUIACOB.NUMGUIA','=',$numero)
        ->distinct()
        ->get();

        //return response()->json($dataClienteFull);

        $dataCuotasFull = DB::table('ADMDEUDACUOTA')
        ->select(DB::raw('ADMDEUDACUOTA.SECDEUDA,
                        ADMDEUDACUOTA.NUMCUOTA,
                        ADMDEUDACUOTA.MONTO,
                        ADMDEUDACUOTA.SALDO,
                        ADMDEUDACUOTA.FECHAVEN,
                        ADMDEUDACUOTA.INTERESACUMORA,
                        ADMDEUDACUOTA.NUMPAGO'))
        ->join('ADMDETGUIACOB','ADMDETGUIACOB.SECUENCIAL','ADMDEUDACUOTA.SECDEUDA')
        ->where('ADMDETGUIACOB.NUMGUIA','=',$numero)
        ->where('ADMDEUDACUOTA.SALDO','>',0.009)
        ->get();



        $dataDeudaFull = DB::select('SELECT DISTINCT ADMDEUDA.SECUENCIAL, ADMDEUDA.BODEGA, RTRIM(ADMDEUDA.CLIENTE) as CLIENTE, 
        ADMDEUDA.TIPO, ADMDEUDA.NUMERO, ADMDEUDA.SERIE, ADMDEUDA.IVA, ADMDEUDA.MONTO, ADMDEUDA.CREDITO,
        ADMDEUDA.SALDO, ADMDEUDA.FECHAEMI, tt.FECHAVEN, ADMDEUDA.valorfinanciado, ADMDEUDA.montointeres,
        ADMDEUDA.entrada, ADMDEUDA.OPERADOR, RTRIM(ADMDEUDA.VENDEDOR) as VENDEDOR, ADMDEUDA.mesescredito,
        ADMDEUDA.numeropagos, ADMDEUDA.diasatraso 
        from [ADMDEUDA] 
        left join [ADMDETGUIACOB] on [ADMDETGUIACOB].[SECUENCIAL] = [ADMDEUDA].[SECUENCIAL] 
        left join [ADMDEUDACUOTA] on [ADMDEUDACUOTA].[SECDEUDA] = [ADMDEUDA].[SECUENCIAL]
        left join  (select SECDEUDA,MAX(FECHAVEN) as FECHAVEN from  [ADMDEUDACUOTA] group by SECDEUDA ) tt  on tt.SECDEUDA = ADMDEUDA.SECUENCIAL 
        where [ADMDETGUIACOB].[NUMGUIA] = ? ',[$numero]);

        return response()->json(['dataDeuda'=>$dataDeudaFull,'dataCliente'=>$dataClienteFull,'dataCuotas'=>$dataCuotasFull]);
    }
}
