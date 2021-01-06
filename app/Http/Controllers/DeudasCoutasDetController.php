<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\ADMDEUDACUOTADET;
use Illuminate\Support\Facades\DB;

class DeudasCoutasDetController extends Controller
{
    public function CuotasAfectadas($pago)
    {
        $cuotas = ADMDEUDACUOTADET::where('NUMPAGO',$pago)->get();

        return response()->json($cuotas);
    }
}
