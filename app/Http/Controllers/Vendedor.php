<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ADMVENDEDOR;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Validator;


class Vendedor extends Controller
{
    public $successStatus = 200;

    
    public function login(Request $request){
        
        $credenciales = $request->validate([
            'codigo' => 'required|string',
            'password' => 'required|string'
        ]);

        //Logueo para Operadores
        if(strlen(request('codigo')) == 3 ){
            
            $operador = \App\ADMOPERADOR::where('codigo',request('codigo'))
            ->where('estado','A')
            ->first();
            
            $userInput = str_split($request['password']);
            $UpDown = []; 
            $sig = 1;
            foreach ($userInput as $key => $value) {
                if($sig == 1){
                    array_push($UpDown, chr(ord($value) - 1)); 
                    $sig = 0; 
                }else{
                    array_push($UpDown, chr(ord($value) + 1)); 
                    $sig = 1;
                }
            }
            //return(implode($UpDown));
          
            
            if ($operador && implode($UpDown) == trim($operador->clave)){
                
                $vendedorRel = \App\ADMVENDEDOR::where('operadormovil',$operador->codigo)
                ->first();

                try {
                    Auth::loginUsingId($operador->codigo, TRUE);
                    $user = Auth::user();
                    $token = $operador->createToken('authToken')->accessToken;
                    return response()->json(['operador'=>$operador,'token'=>$token,'ID'=>$user,'dataVendedor'=>$vendedorRel]);
                } catch (\Throwable $th) {
                    return response()->json(['message'=>$th->getMessage()]);
                }
            }
            else{
                return response()->json(['message'=>'ErrorLogin']);
            }    

        }else{
                 
            $vendedor = \App\ADMVENDEDOR::where('CODIGO',request('codigo'))
            ->where('ESTADO','A')
            ->first();
            
            //return response()->json($vendedorData);
            //if ($vendedor &&  \Hash::check(request('password'), trim($vendedor->HASH))){
            if ($vendedor && trim($request['password']) == trim($vendedor->CLAVEWEB)){
                try {
                    Auth::loginUsingId($vendedor->CODIGO, TRUE);
                    $user = Auth::user();
                    $token = $vendedor->createToken('authToken')->accessToken;
                    return response()->json(['vendedor'=>$vendedor,'token'=>$token,'ID'=>$user]);
                } catch (\Throwable $th) {
                    return response()->json(['message'=>$th->getMessage()]);
                }
            }
            else{
                return response()->json(['message'=>'ErrorLogin']);
            }
        }

    }

    public function listado(){

        $vendedores = \App\ADMVENDEDOR::all();
        return response()->json($vendedores);
    }

    public function SetearPasswordHash(){

        $venderores = \App\ADMVENDEDOR::all();
        $contador = 0;

        foreach ($venderores as $ven) {
            if (trim($ven['HASH']) == ''){
                
                $claveWeb = $ven['CLAVEWEB'];

                if(trim($claveWeb != '' )){
                    $hashed = Hash::make(trim($claveWeb));
                    $affected = DB::table('ADMVENDEDOR')
                    ->where('CODIGO',$ven['CODIGO'])
                    ->update(['HASH' => $hashed]);
                    $contador++;
                } 
            }
        } 
        return response()->json($contador);
    }
}
