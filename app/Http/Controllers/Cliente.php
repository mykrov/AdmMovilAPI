<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Cliente extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/cliente",
    *     tags={"Cliente"},
    *     summary="Listado de Clientes  - Paginado 100.",
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con ESTADO = 'A'."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function listado(){
        
        $clientes = \App\Cliente::where('ESTADO','=','A')
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO'])
        ->simplePaginate(100);

        return response()->json($clientes);

    }

    /**
    * @OA\Get(
    *     path="/api/clientetodos",
    *     tags={"Cliente"},
    *     summary="Listado de todos los Clientes  - Sin Paginado.",
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con ESTADO = 'A'."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function listado2(){
        
        $clientes2 = \App\Cliente::where('ESTADO','=','A')
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO'])
        ->get();
        return response()->json($clientes2);

    }

    /**
    * @OA\Get(
    *     path="/api/cliente/{codigo}",
    *     tags={"Cliente"},
    *     operationId="buscarCliente",
    *     summary="Busqueda de Cliente por Codigo.",
    *     description = "Bucar Cliente por el codigo dado basado en un like '%codigo%' de SQL.",
    * @OA\Parameter(
    *          name="codigo",
    *          description="Codigo Cliente",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con CODIGO 'Igual o similar' al suministrado y estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function byID($id){

        $cliente = \App\Cliente::where('codigo','like', '%' . $id . '%')
        ->where('ESTADO','=','A')
        ->get();
        return response()->json($cliente);

    }

     /**
    * @OA\Get(
    *     path="/api/clientelike/{nombre}",
    *     tags={"Cliente"},
    *     operationId="buscarCliente like",
    *     summary="Busqueda de Cliente por Nombre.",
    *     description = "Bucar Cliente por el nombre dado basado en un like '%nombre%' de SQL.",
    * @OA\Parameter(
    *          name="nombre",
    *          description="Razon Social del Cliente",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con RAZONSOCIAL 'Igual o similar' al suministrado y estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function BuscarNombre($like)
    {
        $clientes = \App\Cliente::where('RAZONSOCIAL', 'like', '%' . $like . '%')
        ->where('ESTADO','=','A')
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO','latitud','longuitud'])
        ->get();
        return response()->json($clientes);
    }

    /**
    * @OA\Get(
    *     path="/api/clientelike/{nombre}/{vendedor}",
    *     tags={"Cliente"},
    *     operationId="buscarCliente like vendedor",
    *     summary="Busqueda de Cliente por Nombre y Vendedor.",
    *     description = "Bucar Cliente por el nombre dado basado en un like '%nombre%' de SQL y pertenezca al codigo del vendedor dado.",
    * @OA\Parameter(
    *          name="nombre",
    *          description="Razon Social del Cliente",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    * @OA\Parameter(
    *          name="vendedor",
    *          description="Codigo vendedor",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con RAZONSOCIAL 'Igual o similar' al suministrado y estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function BuscarNombreXVendedor($like,$vendedor)
    {
        $clientes = \App\Cliente::where('RAZONSOCIAL', 'like', '%' . $like . '%')
        ->where('ESTADO','=','A')
        ->where('VENDEDOR','=',$vendedor)
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO','latitud','longuitud'])
        ->get();
        return response()->json($clientes);
    }

    /**
    * @OA\Get(
    *     path="/api/clientelikexdia/{nombre}",
    *     tags={"Cliente"},
    *     operationId="buscarCliente like - dia",
    *     summary="Busqueda de Cliente por Nombre del dia actual.",
    *     description = "Bucar Cliente por el nombre dado basado en un like '%nombre%' de SQL y que el dia de visita sea el actual.",
    * @OA\Parameter(
    *          name="nombre",
    *          description="Razon Social del Cliente",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con RAZONSOCIAL 'Igual o similar' al suministrado y estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function BuscarNombreXDia($like)
    {
        $fecha_actual = Carbon::now();
        $diaSemana = $fecha_actual->dayOfWeek; 

        if($diaSemana == 7){
            $diaSemana = 0;
        }

        $clientes = \App\Cliente::where('RAZONSOCIAL', 'like', '%' . $like . '%')
        ->where('ESTADO','=','A')
        ->where('DIA','=',$diaSemana + 1)
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO','latitud','longuitud'])
        ->get();
        return response()->json($clientes);
    }

    /**
    * @OA\Get(
    *     path="/api/clientexvcod/{codigo}/{vendedor}",
    *     tags={"Cliente"},
    *     operationId="buscarCliente like Codigo - dia",
    *     summary="Busqueda de Cliente por codigo que pertenezcan al vendedor indicado.",
    *     description = "Bucar Cliente por el nombre dado basado en un like '%codigo%' de SQL y el vendedor sea el dado.",
    * @OA\Parameter(
    *          name="codigo",
    *          description="Codigo del Cliente",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    * @OA\Parameter(
    *          name="vendedor",
    *          description="Codigo del vendedor",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con RAZONSOCIAL 'Igual o similar' al suministrado y estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function BuscarIdXVendedor($id,$vendedor){
        $clientes = \App\Cliente::where('CODIGO','=',$id)
        ->where('ESTADO','=','A')
        ->where('VENDEDOR','=',$vendedor)
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO'])
        ->get();
        return response()->json($clientes);
    }

    /**
    * @OA\Get(
    *     path="/api/clientexv/{vendedor}",
    *     tags={"Cliente"},
    *     operationId="buscarCliente por vendedor",
    *     summary="Busqueda de Clientes en donde en VENDEDOR ó VENDEDORAUX es el indicado.",
    *     description = "Retorna los Clientes en los que el vendedor o vendedor auxiliar es el indicado.",
    * @OA\Parameter(
    *          name="vendedor",
    *          description="Codigo del vendedor",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function ClienteXVendedor($vendedor){
        
        $clientes = \App\Cliente::where('VENDEDOR','=',$vendedor)
        ->orWhere('VENDEDORAUX','=', $vendedor)
        ->where('ESTADO','=','A')
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO'])
        ->get();
        return response()->json($clientes);
    }


    /**
    * @OA\Get(
    *     path="/api/clientelikexvd/{nombre}/{vendedor}",
    *     tags={"Cliente"},
    *     operationId="buscarCliente like nombre - dia - vende",
    *     summary="Busqueda de Cliente por nombre que pertenezcan al vendedor indicado y del dia actual.",
    *     description = "Bucar Cliente por el nombre dado basado en un like '%nombre%' de SQL con vendedor y dia actual.",
    * @OA\Parameter(
    *          name="nombre",
    *          description="Nombre del Cliente",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    * @OA\Parameter(
    *          name="vendedor",
    *          description="Codigo del vendedor",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con RAZONSOCIAL 'Igual o similar' al suministrado y estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function ClienteLikeDiaVende($nombre,$vendedor){
        $fecha_actual = Carbon::now();
        $diaSemana = $fecha_actual->dayOfWeek;  

        if($diaSemana == 7){
            $diaSemana = 0;
        }

        $clientes = \App\Cliente::where('RAZONSOCIAL', 'like', '%' . $nombre . '%')
        ->where('VENDEDOR','=',$vendedor)
        ->where('DIA','=',$diaSemana + 1)
        ->where('ESTADO','=','A')
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO','latitud','longuitud'])
        ->get();
        return response()->json($clientes);
    }

    /**
    * @OA\Get(
    *     path="/api/clientelikexvc/{codigo}/{vendedor}",
    *     tags={"Cliente"},
    *     operationId="buscarCliente like codigo - dia - vende",
    *     summary="Busqueda de Cliente por codigo que pertenezcan al vendedor indicado y del dia actual.",
    *     description = "Bucar Cliente por el nombre dado basado en un like '%codigo%' de SQL con vendedor y dia actual.",
    * @OA\Parameter(
    *          name="codigo",
    *          description="codigo del Cliente",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    * @OA\Parameter(
    *          name="vendedor",
    *          description="Codigo del vendedor",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con RAZONSOCIAL 'Igual o similar' al suministrado y estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    
    public function ClienteLikeCodVende($codigo,$vendedor){
       
        $fecha_actual = Carbon::now();
        $diaSemana = $fecha_actual->dayOfWeek;  
        if($diaSemana == 7){
            $diaSemana = 0;
        }

        $clientes = \App\Cliente::where('CODIGO','=',$codigo)
        ->where('VENDEDOR','=',$vendedor)
        ->where('DIA','=',$diaSemana + 1)
        ->where('ESTADO','=','A')
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO'])
        ->get();
        return response()->json($clientes);
    }

    /**
    * @OA\Get(
    *     path="/api/clientexcd/{codigo}",
    *     tags={"Cliente"},
    *     operationId="buscarCliente like codigo - dia",
    *     summary="Busqueda de Cliente por codigo que y del dia actual.",
    *     description = "Bucar Cliente por el nombre dado basado en un like '%codigo%' y dia actual.",
    * @OA\Parameter(
    *          name="codigo",
    *          description="codigo del Cliente",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con RAZONSOCIAL 'Igual o similar' al suministrado y estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    
    public function ClienteCodDia($codigo){
        $fecha_actual = Carbon::now();
        $diaSemana = $fecha_actual->dayOfWeek;  
        if($diaSemana == 7){
            $diaSemana = 0;
        }

        $clientes = \App\Cliente::where('CODIGO','=',$codigo)
        ->where('DIA','=',$diaSemana + 1)
        ->where('ESTADO','=','A')
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO'])
        ->get();
        return response()->json($clientes);
    }

    /**
    * @OA\Get(
    *     path="/api/clientexvdia/{vendedor}",
    *     tags={"Cliente"},
    *     operationId="buscarCliente like vendedor - dia",
    *     summary="Busqueda de Cliente por vendedor que y del dia actual.",
    *     description = "Bucar Clientes por el vendedor y dia actual.",
    * @OA\Parameter(
    *          name="vendedor",
    *          description="Codigo del Vendedor",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE con Vendedor 'Igual o similar' al suministrado y estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    
    public function ClienteXVendedorDia($vendedor){
        $fecha_actual = Carbon::now();
        $diaSemana = $fecha_actual->dayOfWeek;  
        if($diaSemana == 7){
            $diaSemana = 0;
        }

        $clientes = \App\Cliente::where('VENDEDOR','=',$vendedor)
        ->where('ESTADO','=','A')
        ->where('DIA','=',$diaSemana + 1)
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO'])
        ->get();
        return response()->json($clientes);
    }
    

    /**
    * @OA\Get(
    *     path="/api/clientedia",
    *     tags={"Cliente"},
    *     operationId="buscarCliente por dia",
    *     summary="Busqueda de Cliente por dia actual.",
    *     description = "Bucar Clientes por el dia actual.",
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCLIENTE del dia actual y estado activo."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function ClienteXDia(){
        $fecha_actual = Carbon::now();
        $diaSemana = $fecha_actual->dayOfWeek;  
        if($diaSemana == 7){
            $diaSemana = 0;
        }

        $clientes = \App\Cliente::where('DIA','=',$diaSemana + 1)
        ->select(['CODIGO','ORDEN','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO'])
        ->get();
        return response()->json($clientes);
    }


    /**
     * @OA\Post(
     *      path="/cliente",
     *      operationId="GuardaCliente",
     *      tags={"Cliente"},
     *      summary="Guarda un nuevo Cliente",
     *      description="Nuevo Registro en ADMCLIENTE",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"RAZONSOCIAL","NEGOCIO","DIRECCION","TELEFONOS","FAX","TIPODOC","RUC","EMAIL","REFERENCIA","OBSERVACION","TIPONEGO","TIPO","grupocliente","PROVINCIA","CANTON","PARROQUIA","SECTOR","RUTA","CODFRE","FECHAING","DIASCREDIT","TDCREDITO","VENDEDOR","ZONA"},
     *              @OA\Property(property="RAZONSOCIAL", type="string", format="string", example="Manuel Rangel"),
    *               @OA\Property(property="NEGOCIO", type="string", format="string", example="BAZAR"),
    *               @OA\Property(property="DIRECCION", type="string", format="string", example="AV Lara"),
    *               @OA\Property(property="TELEFONOS", type="string", format="string", example="1547896"),
    *               @OA\Property(property="FAX", type="string", format="string", example="1547896"),
    *               @OA\Property(property="TIPODOC", type="string", format="string", example="C"),
    *               @OA\Property(property="RUC", type="string", format="string", example="123456789"),
    *               @OA\Property(property="EMAIL", type="string", format="string", example="abcd@abc.com"),
    *               @OA\Property(property="REFERENCIA", type="string", format="string", example="Casa azul"),
    *               @OA\Property(property="OBSERVACION", type="string", format="string", example="abcd"),
    *               @OA\Property(property="TIPONEGO", type="string", format="string", example="FAR"),
    *               @OA\Property(property="TIPO", type="string", format="string", example="DET"),
    *               @OA\Property(property="grupocliente", type="string", format="string", example="G01"),
    *               @OA\Property(property="PROVINCIA", type="string", format="string", example="P0001"),
    *               @OA\Property(property="CANTON", type="string", format="string", example="C0018"),
    *               @OA\Property(property="PARROQUIA", type="string", format="string", example="P0017"),
    *               @OA\Property(property="SECTOR", type="string", format="string", example="CEN"),
    *               @OA\Property(property="RUTA", type="string", format="string", example="abcd"),
    *               @OA\Property(property="CODFRE", type="int", format="number", example=12),
    *               @OA\Property(property="FECHAING", type="string", format="string", example="2020-05-26 09:14:00"),
    *               @OA\Property(property="DIASCREDIT", type="int", format="number", example=7),
    *               @OA\Property(property="TDCREDITO", type="string", format="string", example="15"),
    *               @OA\Property(property="VENDEDOR", type="string", format="string", example="VEN001"),
    *               @OA\Property(property="ZONA", type="string", format="string", example="CEN")
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Cliente Creado",
     *         @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="ok"),
     *              @OA\Property(property="codCliente", type="string", example="C0002")
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Número de identificación ya registrado.",
     *         @OA\JsonContent(
     *              @OA\Property(property="result", type="string", example="Identificacionexistente")
     *          )
     *       ),
     * )
     */


    public function CreateClient(Request $request){

        $fecha_actual = Carbon::now();
        $diaSemana = $fecha_actual->dayOfWeek; 

        if($diaSemana == 7){
            $diaSemana = 0;
        }

        $datos = $request;
        
        $check = \App\Cliente::where('RUC','=',$datos['RUC'])->count();
        //return response()->json($datos);
        
        DB::beginTransaction();
        if ($check < 1) {

            if ($datos['REFERENCIA'] == null ) {
                $datos['REFERENCIA'] = '';
            }

            if ($datos['OBSERVACION'] == null ) {
                $datos['OBSERVACION'] = '';
            }

            if ($datos['GRUPO'] == null ) {
                $datos['GRUPO'] = '';
            }

            if ($datos['NEGOCIO'] == null ) {
                $datos['NEGOCIO'] = '';
            }


            try {

                $cliBase = \App\ADMPARAMETROC::first();
                $inicial = $cliBase->LETRAINI;
                $numeroCli = $cliBase->NUMCLIENTE + 1;
                //return response()->json($cliBase);
                $str_length = 6;
                $codigo = substr("000000{$numeroCli}", -$str_length);

                $standar = \App\Cliente::where('CODIGO','=',$cliBase->CLIENTEMODELOCARRO)->first();
                //return response()->json($standar);
                $ClientNew  = new \App\Cliente;
                $dt = Carbon::now();
                $dt2 = $dt->format('Y-d-m');
                //return response()->json($datos['RAZONSOCIAL']);
            
                $ClientNew->CODIGO = $inicial.$codigo;
                $ClientNew->RAZONSOCIAL = $datos['RAZONSOCIAL'];
                $ClientNew->NEGOCIO = $datos['NEGOCIO'];
                $ClientNew->REPRESENTA = $datos['RAZONSOCIAL'];
                $ClientNew->RUC = $datos['RUC'];
                $ClientNew->DIRECCION = $datos['DIRECCION'];
                $ClientNew->TELEFONOS = $datos['TELEFONOS'];
                $ClientNew->FAX = $datos['FAX'];
                $ClientNew->EMAIL = $datos['EMAIL'];
                $ClientNew->TIPO = $datos['TIPO'];
                $ClientNew->CATEGORIA = $standar->CATEGORIA;
                $ClientNew->PROVINCIA = $datos['PROVINCIA'];
                $ClientNew->CANTON = $datos['CANTON'];
                $ClientNew->PARROQUIA = $datos['PARROQUIA'];
                $ClientNew->SECTOR = $datos['SECTOR'];
                $ClientNew->RUTA = $datos['RUTA'];
                $ClientNew->CTACLIENTE = "";
                $ClientNew->CUPO = 0;
                $ClientNew->GRUPO = $datos['GRUPO'];
                $ClientNew->ORDEN = $datos["ORDEN"];
                $ClientNew->CODFRE = $datos['CODFRE'];
                $ClientNew->CREDITO = $standar->CREDITO;
                $ClientNew->DIA = $diaSemana + 1;
                $ClientNew->FECDESDE = $dt2;
                $ClientNew->FECHAING = $dt2;
                $ClientNew->FECULTCOM = $dt2;
                $ClientNew->FECELIMINA = $standar->FECELIMINA;
                $ClientNew->ESTADO = $standar->ESTADO;
                $ClientNew->TDCREDITO = $datos['TDCREDITO'];
                $ClientNew->DIASCREDIT = $datos['DIASCREDIT'];
                $ClientNew->VENDEDOR = $datos['VENDEDOR'];
                $ClientNew->FORMAPAG = $standar->FORMAPAG;
                $ClientNew->CREDITOA = $standar->CREDITOA;
                $ClientNew->IVA = $standar->IVA;
                $ClientNew->BACKORDER = $standar->BACKORDER;
                $ClientNew->RETENPED = $standar->RETENPED;
                $ClientNew->CONFINAL = $standar->CONFINAL;
                $ClientNew->CLASE = $standar->CLASE;
                $ClientNew->OBSERVACION = $datos['OBSERVACION'];
                $ClientNew->TIPONEGO = $datos['TIPONEGO'];
                $ClientNew->CODTMP = $standar->CODTMP;
                $ClientNew->TIPODOC = $datos['TIPODOC'];
                $ClientNew->TIPOCUENTA = $standar->TIPOCUENTA;
                $ClientNew->CLIENTEWEB = $standar->CLIENTEWEB;
                $ClientNew->CODCLIENTEDOMI = $standar->CODCLIENTEDOMI;
                $ClientNew->CLIENTEDOMI = $standar->CLIENTEDOMI;
                $ClientNew->REFERENCIA = $datos['REFERENCIA'];
                $ClientNew->TIPOCONTRIBUYENTE = $standar->TIPOCONTRIBUYENTE;
                $ClientNew->RETIENEFUENTE = $standar->RETIENEFUENTE;
                $ClientNew->RETIENEIVA = $standar->RETIENEIVA;
                $ClientNew->ZONA = $datos['ZONA'];
                $ClientNew->FECNAC = $dt2;
                $ClientNew->FECMOD = $dt2;
                $ClientNew->OPEMOD = $standar->OPEMOD;
                $ClientNew->PORDESSUGERIDO = $standar->PORDESSUGERIDO;
                $ClientNew->EMAILSECUNDARIO = $standar->EMAILSECUNDARIO;
                $ClientNew->fechaenvioweb = $standar->fechaenvioweb;
                $ClientNew->FACTURAELECTRONICA = $standar->FACTURAELECTRONICA;
                $ClientNew->CLAVEFE = $standar->CLAVEFE;
                $ClientNew->SUBIRWEB = $standar->SUBIRWEB;
                $ClientNew->TIPOPERSONA = $standar->TIPOPERSONA;
                $ClientNew->SEXO = $standar->SEXO;
                $ClientNew->ESTADOCIVIL = $standar->ESTADOCIVIL;
                $ClientNew->ORIGENINGRESO = $standar->ORIGENINGRESO;
                $ClientNew->TIPOPAGO = $standar->TIPOPAGO;
                $ClientNew->FECHADESDE = $dt2;
                $ClientNew->FECHAHASTA = $dt2;
                $ClientNew->BENEFICIARIO = $standar->BENEFICIARIO;
                $ClientNew->NOCONTRATO = $standar->NOCONTRATO;
                $ClientNew->TIPOPERSONAADICIONAL = $standar->TIPOPERSONAADICIONAL;
                $ClientNew->GERENTE = $standar->GERENTE;
                $ClientNew->TELEFONO = $standar->TELEFONO;
                $ClientNew->CONTACTOPAGO = $standar->CONTACTOPAGO;
                $ClientNew->CORREO1 = $standar->CORREO1;
                $ClientNew->CORREO2 = $standar->CORREO2;
                $ClientNew->CARGO1 = $standar->CARGO1;
                $ClientNew->CARGO2 = $standar->CARGO2;
                $ClientNew->OBSERVACIONADICIONAL = $standar->OBSERVACIONADICIONAL;
                $ClientNew->PAGOCUOTAS = $standar->PAGOCUOTAS;
                $ClientNew->NUMCUOTAS = $standar->NUMCUOTAS;
                $ClientNew->CUOTA1 = $standar->CUOTA1;
                $ClientNew->CUOTA2 = $standar->CUOTA2;
                $ClientNew->CUOTA3 = $standar->CUOTA3;
                $ClientNew->CUOTA4 = $standar->CUOTA4;
                $ClientNew->CUOTA5 = $standar->CUOTA5;
                $ClientNew->EJEX = $standar->EJEX;
                $ClientNew->EJEY = $standar->EJEY;
                $ClientNew->CLIENTEMOVIL = $standar->CLIENTEMOVIL;
                $ClientNew->ESTADOPARAWEB = $standar->ESTADOPARAWEB;
                $ClientNew->CLIRELACIONADO = $standar->CLIRELACIONADO;
                $ClientNew->VENDEDORAUX = $standar->VENDEDORAUX;
                $ClientNew->CANAL = $standar->CANAL;
                $ClientNew->SUBCANAL = $standar->SUBCANAL;
                $ClientNew->grupocliente = $standar->grupocliente;
                $ClientNew->grupocredito = $standar->grupocredito;
                $ClientNew->EWEB = $standar->EWEB;
                $ClientNew->nombre = $standar->nombre;
                $ClientNew->FACTURABLE = $standar->FACTURABLE;
                $ClientNew->DEUDAS = $standar->DEUDAS;
                $ClientNew->ANTICIPO = $standar->ANTICIPO;
                $ClientNew->CIUDAD = $standar->CIUDAD;
                $ClientNew->coordenada = $standar->coordenada;
                $ClientNew->barrio = $standar->barrio;
                $ClientNew->tipodocumento = $standar->tipodocumento;
                $ClientNew->CODIGOSAP = $standar->CODIGOSAP;
                $ClientNew->CodShip = $standar->CodShip;
                $ClientNew->longuitud = $standar->longuitud;
                $ClientNew->latitud = $standar->latitud;
                $ClientNew->TipoLocalidad = $standar->TipoLocalidad;
                $ClientNew->numero = $standar->numero;
                $ClientNew->CORREOCARRO = $standar->CORREOCARRO;
                $ClientNew->CLAVECARRO = $standar->CLAVECARRO;
                //return response()->json($ClientNew);
                $ClientNew->save();
                
                $cliBase->NUMCLIENTE = $cliBase->NUMCLIENTE +1;
                $cliBase->save();
                DB::commit();

                return response()->json(['status'=>'ok','codCliente'=>$inicial.$codigo],200);
            } catch (\Exception $e) {
                
                DB::rollback();
                return response()->json(["error"=>["info"=>$e->getMessage()]],503);
            }
        } else {
            return response()->json(['result'=>'IdentificacionExistente'],200);
        }
        
    }


        /**
     * @OA\Post(
     *      path="/clienteven",
     *      operationId="GuardaCliente Basico para Ventas",
     *      tags={"Cliente"},
     *      summary="Guarda un nuevo Cliente con datos basicos para ventas.",
     *      description="Nuevo Registro en ADMCLIENTE con datos basicos para ventas.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"RAZONSOCIAL","RUC","DIRECCION","TELEFONOS","FAX","EMAIL","VENDEDOR","OBSERVACION","TIPODOC","REFERENCIA"},
     *              @OA\Property(property="RAZONSOCIAL", type="string", format="string", example="Manuel Rangel"),
     *              @OA\Property(property="RUC", type="string", format="string", example="123456789"),
    *               @OA\Property(property="DIRECCION", type="string", format="string", example="AV Lara"),
    *               @OA\Property(property="TELEFONOS", type="string", format="string", example="1547896"),
    *               @OA\Property(property="FAX", type="string", format="string", example="1547896"),
    *               @OA\Property(property="EMAIL", type="string", format="string", example="abcd@abc.com"),
    *               @OA\Property(property="VENDEDOR", type="string", format="string", example="VEN001"),
    *               @OA\Property(property="OBSERVACION", type="string", format="string", example="abcd"),
    *               @OA\Property(property="TIPODOC", type="string", format="string", example="C"),
    *               @OA\Property(property="REFERENCIA", type="string", format="string", example="Casa azul")
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Cliente Creado",
     *         @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="ok"),
     *              @OA\Property(property="codCliente", type="string", example="C0002")
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Número de identificación ya registrado.",
     *         @OA\JsonContent(
     *              @OA\Property(property="result", type="string", example="Identificacionexistente")
     *          )
     *       ),
     * )
     */

    public function CreateClientVen(Request $request){

        $fecha_actual = Carbon::now();
        $diaSemana = $fecha_actual->dayOfWeek; 

        if($diaSemana == 7){
            $diaSemana = 0;
        }

        $datos = $request;

        Log::info("Datos de Request",['req'=>$datos]);
        
        $check = \App\Cliente::where('RUC','=',$datos['RUC'])->count();
        //return response()->json($standar);
        //return response()->json($datos);

        DB::beginTransaction();
        if ($check < 1) {

            if ($datos['REFERENCIA'] == null ) {
                $datos['REFERENCIA'] = '';
            }

            if ($datos['OBSERVACION'] == null ) {
                $datos['OBSERVACION'] = '';
            }

            if ($datos['GRUPO'] == null ) {
                $datos['GRUPO'] = '';
            }
          
            try {

                $cliBase = \App\ADMPARAMETROC::first();
                $standar = \App\Cliente::where('CODIGO','=',$cliBase->CLIENTEMODELOCARRO)->first();
               
                $inicial = $cliBase->LETRAINI;
                $numeroCli = $cliBase->NUMCLIENTE + 1;

                $str_length = 6;
                $codigo = substr("000000{$numeroCli}", -$str_length);

                if ($datos['RUTA'] == null ) {
                    $datos['RUTA'] = trim($standar->RUTA);
                }
              
                $ClientNew  = new \App\Cliente;
                $dt = Carbon::now();
                $dt2 = $dt->format('Y-d-m');
                //return response()->json($dt2);
                
                $ClientNew->CODIGO = $inicial.$codigo;
                $ClientNew->RAZONSOCIAL = $datos['RAZONSOCIAL'];
                $ClientNew->NEGOCIO = $standar->NEGOCIO;
                $ClientNew->REPRESENTA = $datos['RAZONSOCIAL'];
                $ClientNew->RUC = $datos['RUC'];
                $ClientNew->DIRECCION = $datos['DIRECCION'];
                $ClientNew->TELEFONOS = $datos['TELEFONOS'];
                $ClientNew->FAX = $datos['FAX'];
                $ClientNew->EMAIL = $datos['EMAIL'];
                $ClientNew->TIPO = trim($standar->TIPO);
                $ClientNew->CATEGORIA = trim($standar->CATEGORIA);
                $ClientNew->PROVINCIA = trim($standar->PROVINCIA);
                $ClientNew->CANTON = trim($standar->CANTON);
                $ClientNew->PARROQUIA = trim($standar->PARROQUIA);
                
                //Sector y zona opcional en el request
                if ($datos['SECTOR'] == null or $datos['SECTOR'] == 'NA') {
                    $datos['SECTOR'] = trim($standar->SECTOR);
                }else{
                    $ClientNew->SECTOR = $datos['SECTOR'];
                }

                if ($datos['ZONA'] == null or $datos['ZONA'] == 'NA') {
                    $datos['ZONA'] = trim($standar->ZONA);
                }else{
                    $ClientNew->ZONA = $datos['ZONA'];
                }

                $ClientNew->RUTA = $datos['RUTA'];
                $ClientNew->CTACLIENTE = "";
                $ClientNew->CUPO = 0;
                $ClientNew->GRUPO = '';
                $ClientNew->ORDEN = $standar->ORDEN;
                $ClientNew->CODFRE = $standar->CODFRE;
                $ClientNew->CREDITO = $standar->CREDITO;
                $ClientNew->DIA = $diaSemana + 1;
                $ClientNew->FECDESDE = $dt2;
                $ClientNew->FECHAING = $dt2;
                $ClientNew->FECULTCOM = $dt2;
                $ClientNew->FECELIMINA = $standar->FECELIMINA;
                $ClientNew->ESTADO = $standar->ESTADO;
                $ClientNew->TDCREDITO = $standar->TDCREDITO;
                $ClientNew->DIASCREDIT = $standar->DIASCREDIT;
                $ClientNew->VENDEDOR = $datos['VENDEDOR'];
                $ClientNew->FORMAPAG = trim($standar->FORMAPAG);
                $ClientNew->CREDITOA = $standar->CREDITOA;
                $ClientNew->IVA = $standar->IVA;
                $ClientNew->BACKORDER = $standar->BACKORDER;
                $ClientNew->RETENPED = $standar->RETENPED;
                $ClientNew->CONFINAL = $standar->CONFINAL;
                $ClientNew->CLASE = $standar->CLASE;
                $ClientNew->OBSERVACION = $datos['OBSERVACION'];
                $ClientNew->TIPONEGO = $standar->TIPONEGO;
                $ClientNew->CODTMP = $standar->CODTMP;
                $ClientNew->TIPODOC = $datos['TIPODOC'];
                $ClientNew->TIPOCUENTA = $standar->TIPOCUENTA;
                $ClientNew->CLIENTEWEB = $standar->CLIENTEWEB;
                $ClientNew->CODCLIENTEDOMI = $standar->CODCLIENTEDOMI;
                $ClientNew->CLIENTEDOMI = $standar->CLIENTEDOMI;
                $ClientNew->REFERENCIA = $datos['REFERENCIA'];
                $ClientNew->TIPOCONTRIBUYENTE = $standar->TIPOCONTRIBUYENTE;
                $ClientNew->RETIENEFUENTE = $standar->RETIENEFUENTE;
                $ClientNew->RETIENEIVA = $standar->RETIENEIVA;
                $ClientNew->FECNAC = $dt2;
                $ClientNew->FECMOD = $dt2;
                $ClientNew->OPEMOD = $standar->OPEMOD;
                $ClientNew->PORDESSUGERIDO = $standar->PORDESSUGERIDO;
                $ClientNew->EMAILSECUNDARIO = $standar->EMAILSECUNDARIO;
                $ClientNew->fechaenvioweb = $standar->fechaenvioweb;
                $ClientNew->FACTURAELECTRONICA = $standar->FACTURAELECTRONICA;
                $ClientNew->CLAVEFE = $standar->CLAVEFE;
                $ClientNew->SUBIRWEB = $standar->SUBIRWEB;
                $ClientNew->TIPOPERSONA = $standar->TIPOPERSONA;
                $ClientNew->SEXO = $standar->SEXO;
                $ClientNew->ESTADOCIVIL = $standar->ESTADOCIVIL;
                $ClientNew->ORIGENINGRESO = $standar->ORIGENINGRESO;
                $ClientNew->TIPOPAGO = $standar->TIPOPAGO;
                $ClientNew->FECHADESDE = $dt2;
                $ClientNew->FECHAHASTA = $dt2;
                $ClientNew->BENEFICIARIO = $standar->BENEFICIARIO;
                $ClientNew->NOCONTRATO = $standar->NOCONTRATO;
                $ClientNew->TIPOPERSONAADICIONAL = $standar->TIPOPERSONAADICIONAL;
                $ClientNew->GERENTE = $standar->GERENTE;
                $ClientNew->TELEFONO = $standar->TELEFONO;
                $ClientNew->CONTACTOPAGO = $standar->CONTACTOPAGO;
                $ClientNew->CORREO1 = $standar->CORREO1;
                $ClientNew->CORREO2 = $standar->CORREO2;
                $ClientNew->CARGO1 = $standar->CARGO1;
                $ClientNew->CARGO2 = $standar->CARGO2;
                $ClientNew->OBSERVACIONADICIONAL = $standar->OBSERVACIONADICIONAL;
                $ClientNew->PAGOCUOTAS = $standar->PAGOCUOTAS;
                $ClientNew->NUMCUOTAS = $standar->NUMCUOTAS;
                $ClientNew->CUOTA1 = $standar->CUOTA1;
                $ClientNew->CUOTA2 = $standar->CUOTA2;
                $ClientNew->CUOTA3 = $standar->CUOTA3;
                $ClientNew->CUOTA4 = $standar->CUOTA4;
                $ClientNew->CUOTA5 = $standar->CUOTA5;
                $ClientNew->EJEX = $standar->EJEX;
                $ClientNew->EJEY = $standar->EJEY;
                $ClientNew->CLIENTEMOVIL = $standar->CLIENTEMOVIL;
                $ClientNew->ESTADOPARAWEB = $standar->ESTADOPARAWEB;
                $ClientNew->CLIRELACIONADO = $standar->CLIRELACIONADO;
                $ClientNew->VENDEDORAUX = $standar->VENDEDORAUX;
                $ClientNew->CANAL = $standar->CANAL;
                $ClientNew->SUBCANAL = $standar->SUBCANAL;
                $ClientNew->grupocliente = $standar->grupocliente;
                $ClientNew->grupocredito = $standar->grupocredito;
                $ClientNew->EWEB = $standar->EWEB;
                $ClientNew->nombre = $standar->nombre;
                $ClientNew->FACTURABLE = $standar->FACTURABLE;
                $ClientNew->DEUDAS = $standar->DEUDAS;
                $ClientNew->ANTICIPO = $standar->ANTICIPO;
                $ClientNew->CIUDAD = $standar->CIUDAD;
                $ClientNew->coordenada = $standar->coordenada;
                $ClientNew->barrio = $standar->barrio;
                $ClientNew->tipodocumento = $standar->tipodocumento;
                $ClientNew->CODIGOSAP = $standar->CODIGOSAP;
                $ClientNew->CodShip = $standar->CodShip;
                $ClientNew->longuitud = $standar->longuitud;
                $ClientNew->latitud = $standar->latitud;
                $ClientNew->TipoLocalidad = $standar->TipoLocalidad;
                $ClientNew->numero = $standar->numero;
                $ClientNew->CORREOCARRO = $standar->CORREOCARRO;
                $ClientNew->CLAVECARRO = $standar->CLAVECARRO;

                $ClientNew->save();

                $cliBase->NUMCLIENTE = $cliBase->NUMCLIENTE +1;
                $cliBase->save();
                DB::commit();

                return response()->json(['status'=>'ok','codCliente'=>$inicial.$codigo]);
            } catch (\Exception $e) {
                
                DB::rollback();
                return response()->json(["error"=>["info"=>$e->getMessage()]]);
            }
        } else {
            return response()->json(['result'=>'IdentificacionExistente']);
        }
    }

    /**
     * @OA\Post(
     *      path="/clientebasico",
     *      operationId="GuardaCliente Basico",
     *      tags={"Cliente"},
     *      summary="Guarda un nuevo Cliente con datos basicos.",
     *      description="Nuevo Registro en ADMCLIENTE con datos basicos.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"RAZONSOCIAL","NEGOCIO","DIRECCION","TELEFONOS","FAX","TIPODOC","RUC","EMAIL","REFERENCIA","OBSERVACION","TIPONEGO","TIPO","PROVINCIA","CANTON","PARROQUIA","SECTOR","RUTA","VENDEDOR","ZONA"},
     *              @OA\Property(property="RAZONSOCIAL", type="string", format="string", example="Manuel Rangel"),
    *               @OA\Property(property="NEGOCIO", type="string", format="string", example="BAZAR"),
    *               @OA\Property(property="DIRECCION", type="string", format="string", example="AV Lara"),
    *               @OA\Property(property="TELEFONOS", type="string", format="string", example="1547896"),
    *               @OA\Property(property="FAX", type="string", format="string", example="1547896"),
    *               @OA\Property(property="TIPODOC", type="string", format="string", example="C"),
    *               @OA\Property(property="RUC", type="string", format="string", example="123456789"),
    *               @OA\Property(property="EMAIL", type="string", format="string", example="abcd@abc.com"),
    *               @OA\Property(property="REFERENCIA", type="string", format="string", example="Casa azul"),
    *               @OA\Property(property="OBSERVACION", type="string", format="string", example="abcd"),
    *               @OA\Property(property="TIPONEGO", type="string", format="string", example="FAR"),
    *               @OA\Property(property="TIPO", type="string", format="string", example="DET"),
    *               @OA\Property(property="PROVINCIA", type="string", format="string", example="P0001"),
    *               @OA\Property(property="CANTON", type="string", format="string", example="C0018"),
    *               @OA\Property(property="PARROQUIA", type="string", format="string", example="P0017"),
    *               @OA\Property(property="SECTOR", type="string", format="string", example="CEN"),
    *               @OA\Property(property="RUTA", type="string", format="string", example="abcd"),
    *               @OA\Property(property="VENDEDOR", type="string", format="string", example="VEN001"),
    *               @OA\Property(property="ZONA", type="string", format="string", example="CEN")
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Cliente Creado",
     *         @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="ok"),
     *              @OA\Property(property="codCliente", type="string", example="C0002")
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Número de identificación ya registrado.",
     *         @OA\JsonContent(
     *              @OA\Property(property="result", type="string", example="Identificacionexistente")
     *          )
     *       ),
     * )
     */


    public function CreateClientBasic(Request $request){
        
        $fecha_actual = Carbon::now();
        $diaSemana = $fecha_actual->dayOfWeek;

        if($diaSemana == 7){
            $diaSemana = 0;
        }

        $datos = $request;

        $check = \App\Cliente::where('RUC','=',$datos['RUC'])->count();

        //return response()->json($datos);

        DB::beginTransaction();
        if ($check < 1) {

            $cliBase = \App\ADMPARAMETROC::first();
            $standar = \App\Cliente::where('CODIGO','=',$cliBase->CLIENTEMODELOCARRO)->first();

            if ($datos['REFERENCIA'] == null ) {
                $datos['REFERENCIA'] = '';
            }

            if ($datos['OBSERVACION'] == null ) {
                $datos['OBSERVACION'] = '';
            }

            if ($datos['GRUPO'] == null ) {
                $datos['GRUPO'] = trim($standar->GRUPO);
            }

            if ($datos['CANTON'] == null ) {
                $datos['CANTON'] = trim($standar->CANTON);
            }

            if ($datos['GRUPO'] == null ) {
                $datos['GRUPO'] = trim($standar->GRUPO);
            }

            if ($datos['PARROQUIA'] == null ) {
                $datos['PARROQUIA'] = trim($standar->PARROQUIA);
            }

            if ($datos['PROVINCIA'] == null ) {
                $datos['PROVINCIA'] = trim($standar->PROVINCIA);
            }

            if ($datos['ZONA'] == null ) {
                $datos['ZONA'] = trim($standar->ZONA);
            }

            if ($datos['SECTOR'] == null ) {
                $datos['SECTOR'] = trim($standar->SECTOR);
            }

            
            if ($datos['RUTA'] == null ) {
                $datos['RUTA'] = trim($standar->RUTA);
            }


            try {
               
                $inicial = $cliBase->LETRAINI;
                $numeroCli = $cliBase->NUMCLIENTE + 1;

                $str_length = 6;
                $codigo = substr("000000{$numeroCli}", -$str_length);

                $standar = \App\Cliente::where('CODIGO','=',$cliBase->CLIENTEMODELOCARRO)->first();
                //return response()->json($standar);
                $ClientNew  = new \App\Cliente;
                $dt = Carbon::now();
                $dt2 = $dt->format('Y-d-m');
                //return response()->json($dt2);
            
                $ClientNew->CODIGO = $inicial.$codigo;
                $ClientNew->RAZONSOCIAL = $datos['RAZONSOCIAL'];
                $ClientNew->NEGOCIO = $standar->NEGOCIO;
                $ClientNew->REPRESENTA = $datos['RAZONSOCIAL'];
                $ClientNew->RUC = $datos['RUC'];
                $ClientNew->DIRECCION = $datos['DIRECCION'];
                $ClientNew->TELEFONOS = $datos['TELEFONOS'];
                $ClientNew->FAX = $datos['FAX'];
                $ClientNew->EMAIL = $datos['EMAIL'];
                $ClientNew->TIPO = $datos['TIPO'];
                $ClientNew->CATEGORIA = trim($standar->CATEGORIA);
                $ClientNew->PROVINCIA = $datos['PROVINCIA'];
                $ClientNew->CANTON = $datos['CANTON'];
                $ClientNew->PARROQUIA = $datos['PARROQUIA'];
                $ClientNew->SECTOR = $datos['SECTOR'];
                $ClientNew->RUTA = $datos['RUTA'];
                $ClientNew->CTACLIENTE = "";
                $ClientNew->CUPO = 0;
                $ClientNew->GRUPO = '';
                $ClientNew->ORDEN = $standar->ORDEN;
                $ClientNew->CODFRE = $standar->CODFRE;
                $ClientNew->CREDITO = $standar->CREDITO;
                $ClientNew->DIA = $diaSemana + 1;
                $ClientNew->FECDESDE = $dt2;
                $ClientNew->FECHAING = $dt2;
                $ClientNew->FECULTCOM = $dt2;
                $ClientNew->FECELIMINA = $standar->FECELIMINA;
                $ClientNew->ESTADO = $standar->ESTADO;
                $ClientNew->TDCREDITO = $standar->TDCREDITO;
                $ClientNew->DIASCREDIT = $standar->DIASCREDIT;
                $ClientNew->VENDEDOR = $datos['VENDEDOR'];
                $ClientNew->FORMAPAG = trim($standar->FORMAPAG);
                $ClientNew->CREDITOA = $standar->CREDITOA;
                $ClientNew->IVA = $standar->IVA;
                $ClientNew->BACKORDER = $standar->BACKORDER;
                $ClientNew->RETENPED = $standar->RETENPED;
                $ClientNew->CONFINAL = $standar->CONFINAL;
                $ClientNew->CLASE = $standar->CLASE;
                $ClientNew->OBSERVACION = '';
                $ClientNew->TIPONEGO = $datos['TIPONEGO'];
                $ClientNew->CODTMP = $standar->CODTMP;
                $ClientNew->TIPODOC = $datos['TIPODOC'];
                $ClientNew->TIPOCUENTA = $standar->TIPOCUENTA;
                $ClientNew->CLIENTEWEB = $standar->CLIENTEWEB;
                $ClientNew->CODCLIENTEDOMI = $standar->CODCLIENTEDOMI;
                $ClientNew->CLIENTEDOMI = $standar->CLIENTEDOMI;
                $ClientNew->REFERENCIA = $datos['REFERENCIA'];
                $ClientNew->TIPOCONTRIBUYENTE = $standar->TIPOCONTRIBUYENTE;
                $ClientNew->RETIENEFUENTE = $standar->RETIENEFUENTE;
                $ClientNew->RETIENEIVA = $standar->RETIENEIVA;
                $ClientNew->ZONA = $datos['ZONA'];
                $ClientNew->FECNAC = $dt2;
                $ClientNew->FECMOD = $dt2;
                $ClientNew->OPEMOD = $standar->OPEMOD;
                $ClientNew->PORDESSUGERIDO = $standar->PORDESSUGERIDO;
                $ClientNew->EMAILSECUNDARIO = $standar->EMAILSECUNDARIO;
                $ClientNew->fechaenvioweb = $standar->fechaenvioweb;
                $ClientNew->FACTURAELECTRONICA = $standar->FACTURAELECTRONICA;
                $ClientNew->CLAVEFE = $standar->CLAVEFE;
                $ClientNew->SUBIRWEB = $standar->SUBIRWEB;
                $ClientNew->TIPOPERSONA = $standar->TIPOPERSONA;
                $ClientNew->SEXO = $standar->SEXO;
                $ClientNew->ESTADOCIVIL = $standar->ESTADOCIVIL;
                $ClientNew->ORIGENINGRESO = $standar->ORIGENINGRESO;
                $ClientNew->TIPOPAGO = $standar->TIPOPAGO;
                $ClientNew->FECHADESDE = $dt2;
                $ClientNew->FECHAHASTA = $dt2;
                $ClientNew->BENEFICIARIO = $standar->BENEFICIARIO;
                $ClientNew->NOCONTRATO = $standar->NOCONTRATO;
                $ClientNew->TIPOPERSONAADICIONAL = $standar->TIPOPERSONAADICIONAL;
                $ClientNew->GERENTE = $standar->GERENTE;
                $ClientNew->TELEFONO = $standar->TELEFONO;
                $ClientNew->CONTACTOPAGO = $standar->CONTACTOPAGO;
                $ClientNew->CORREO1 = $standar->CORREO1;
                $ClientNew->CORREO2 = $standar->CORREO2;
                $ClientNew->CARGO1 = $standar->CARGO1;
                $ClientNew->CARGO2 = $standar->CARGO2;
                $ClientNew->OBSERVACIONADICIONAL = $standar->OBSERVACIONADICIONAL;
                $ClientNew->PAGOCUOTAS = $standar->PAGOCUOTAS;
                $ClientNew->NUMCUOTAS = $standar->NUMCUOTAS;
                $ClientNew->CUOTA1 = $standar->CUOTA1;
                $ClientNew->CUOTA2 = $standar->CUOTA2;
                $ClientNew->CUOTA3 = $standar->CUOTA3;
                $ClientNew->CUOTA4 = $standar->CUOTA4;
                $ClientNew->CUOTA5 = $standar->CUOTA5;
                $ClientNew->EJEX = $standar->EJEX;
                $ClientNew->EJEY = $standar->EJEY;
                $ClientNew->CLIENTEMOVIL = $standar->CLIENTEMOVIL;
                $ClientNew->ESTADOPARAWEB = $standar->ESTADOPARAWEB;
                $ClientNew->CLIRELACIONADO = $standar->CLIRELACIONADO;
                $ClientNew->VENDEDORAUX = $standar->VENDEDORAUX;
                $ClientNew->CANAL = $standar->CANAL;
                $ClientNew->SUBCANAL = $standar->SUBCANAL;
                $ClientNew->grupocliente = $standar->grupocliente;
                $ClientNew->grupocredito = $standar->grupocredito;
                $ClientNew->EWEB = $standar->EWEB;
                $ClientNew->nombre = $standar->nombre;
                $ClientNew->FACTURABLE = $standar->FACTURABLE;
                $ClientNew->DEUDAS = $standar->DEUDAS;
                $ClientNew->ANTICIPO = $standar->ANTICIPO;
                $ClientNew->CIUDAD = $standar->CIUDAD;
                $ClientNew->coordenada = $standar->coordenada;
                $ClientNew->barrio = $standar->barrio;
                $ClientNew->tipodocumento = $standar->tipodocumento;
                $ClientNew->CODIGOSAP = $standar->CODIGOSAP;
                $ClientNew->CodShip = $standar->CodShip;
                $ClientNew->longuitud = $standar->longuitud;
                $ClientNew->latitud = $standar->latitud;
                $ClientNew->TipoLocalidad = $standar->TipoLocalidad;
                $ClientNew->numero = $standar->numero;
                $ClientNew->CORREOCARRO = $standar->CORREOCARRO;
                $ClientNew->CLAVECARRO = $standar->CLAVECARRO;

                $ClientNew->save();

                $cliBase->NUMCLIENTE = $cliBase->NUMCLIENTE +1;
                $cliBase->save();
                DB::commit();

                return response()->json(['status'=>'ok','codCliente'=>$inicial.$codigo],200);
            } catch (\Exception $e) {
                
                DB::rollback();
                return response()->json(["error"=>["info"=>$e->getMessage()]],503);
            }
        } else {
            return response()->json(['result'=>'IdentificacionExistente'],200);
        }
    }


    public function ClientesPorRuta($ruta)
    {
        $clientes = \App\Cliente::where('RUTA','=',$ruta)
        ->where('ESTADO','=','A')
        ->select(['CODIGO','RAZONSOCIAL','NEGOCIO','DIRECCION','TELEFONOS','FAX','EMAIL','RUC','GRUPO','TIPONEGO','TIPO','latitud','longuitud'])
        ->get();
        return response()->json($clientes);
    }
}
