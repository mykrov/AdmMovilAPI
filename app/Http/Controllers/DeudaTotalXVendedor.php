<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ADMDEUDA;
class DeudaTotalXVendedor extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/deudatotal/{vendedor}",
    *     tags={"Deudas"},
    *     summary="Retorna la Sumatoria de SALDO en ADMDEUDA del Vendedor indicado, y el TIPO in ('FAC','NVT','NDB').",
    * @OA\Parameter(
    *          name="vendedor",
    *          description="vendedor",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMDEUDACUOTADET con pago indicado."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function DeudaTotal(string $vendedor)
    {
        $suma = ADMDEUDA::where('SALDO','>',0.00001)
        ->where('VENDEDOR','=',$vendedor)
        ->whereIn('TIPO',array('FAC','NVT','NDB'))
        ->sum('SALDO');

        return response()->json(['deudaTotal'=>round($suma,2)]);
    }
}
