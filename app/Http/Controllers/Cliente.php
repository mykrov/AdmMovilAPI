<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Cliente extends Controller
{
    public function listado(){

        $clientes = \App\Cliente::all();
        return response()->json($clientes);

    }

    public function byID($id){

        $cliente = \App\Cliente::where('codigo',$id)->get();
        return response()->json($cliente);

    }

    public function BuscarNombre($like)
    {
        $clientes = \App\Cliente::where('RAZONSOCIAL', 'like', '%' . $like . '%')->get();
        return response()->json($clientes);
    }

    public function ClienteXVendedor($vendedor){
        
        $clientes = \App\Cliente::where('VENDEDOR','=',$vendedor)->get();
        return response()->json($clientes);
    }

    public function CreateClient(Request $request){

        $datos = $request;
        
        $check = \App\Cliente::where('RUC','=',$datos['RUC'])->count();
        //return response()->json($check);
        DB::beginTransaction();
        if ($check < 1) {

            try {

                $cliBase = \App\ADMPARAMETROC::first();
                $inicial = $cliBase->LETRAINI;
                $numeroCli = $cliBase->NUMCLIENTE + 1;

                $str_length = 6;
                $codigo = substr("000000{$numeroCli}", -$str_length);

                $standar = \App\Cliente::where('CODIGO','=',$cliBase->CLIENTEMODELOCARRO)->first();
                $ClientNew  = new \App\Cliente;
                $dt = Carbon::now();
                $dt2 = $dt->format('Y-d-m H:i:s');
                //return response()->json($dt2);
            
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
                $ClientNew->CATEGORIA = null;
                $ClientNew->PROVINCIA = $datos['PROVINCIA'];
                $ClientNew->CANTON = $datos['CANTON'];
                $ClientNew->PARROQUIA = $datos['PARROQUIA'];
                $ClientNew->SECTOR = $datos['SECTOR'];
                $ClientNew->RUTA = $datos['RUTA'];
                $ClientNew->CTACLIENTE = "";
                $ClientNew->CUPO = 0;
                $ClientNew->GRUPO = $datos['GRUPO'];
                $ClientNew->ORDEN = $standar->ORDEN;
                $ClientNew->CODFRE = $datos['CODFRE'];
                $ClientNew->CREDITO = $standar->CREDITO;
                $ClientNew->DIA = $standar->DIA;
                $ClientNew->FECDESDE = $dt2;
                $ClientNew->FECHAING = $dt2;
                $ClientNew->FECULTCOM = $standar->FECULTCOM;
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
                $ClientNew->CORREO1 = $datos['EMAIL'];
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
}
