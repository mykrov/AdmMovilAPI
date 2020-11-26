<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ADMDEUDA;
class DeudaTotalXVendedor extends Controller
{
    public function DeudaTotal(string $vendedor)
    {
        $suma = ADMDEUDA::where('SALDO','>',0.00001)
        ->where('VENDEDOR','=',$vendedor)
        ->whereIn('TIPO',array('FAC','NVT','NDB'))
        ->sum('SALDO');

        return response()->json(['deudaTotal'=>round($suma,2)]);
    }
}
