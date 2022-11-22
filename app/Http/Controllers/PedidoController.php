<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\ADMBODEGA;
use \App\ADMPARAMETROV;
use \App\ADMPARAMETROBO;
use \App\Cliente;
use \App\ADMDETEGRBOD;
use \App\ADMCABEGRBOD;
use \App\ADMDEUDA;
use \App\ADMCREDITO;
use \App\Mail\FacturaInvoice;
use Barryvdh\DomPDF\Facade as PDF;


class PedidoController extends Controller
{
    // Metodo para creacion de Pedido
    public function PostPedido(Request $r)
    {
        // obtencion de cabecera y detalles
        $cabecera = $r->cabecera[0];
        $detalles = $r->detalles;
        
        
        $detallesContador = COUNT($detalles);
        
        // verifica que existan detalles
        if($detallesContador == 0){
            return response()->json(['error'=>'Cabecera Sin Detalles']);
        }

        $operador1 = '';

        
        //Datos del Operador segun vendedor
        $vendedorData = \App\ADMVENDEDOR::where('CODIGO','=',$cabecera['usuario'])->first();
        $operador1 = '';
        if($vendedorData == null || $vendedorData->operadormovil == null){
            $operador1 = 'ADM';
        }
     

       
        $parametrov = ADMPARAMETROV::first();
        $secuencialNew = $parametrov->SECUENCIAL;
        $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
        $parametrov->save();


        DB::beginTransaction();

        //En caso de Observacion.
        $observacion = "Gracias por su Compra.";
        if(trim($cabecera['observacion']) != ""){
            $observacion = $cabecera['observacion'];
        }

        $campo_adi = "Gracias por su Compra.";
        if(trim($cabecera['datos_adi']) != ""){
            $campo_adi = $cabecera['datos_adi'];
        }

        try {
        
            // optiene instancias para el pedido
            $bodega = ADMBODEGA::where('CODIGO','=',$cabecera['bodega'])->first();
            $cliente = Cliente::where('CODIGO','=',$cabecera['cliente'])->first();
            $parametrobo = ADMPARAMETROBO::first();

            
            $grabaIva = "N";
            if(floatval($cabecera['iva']) > 0){
                $grabaIva = "S";
            }
            
            $date = Carbon::now()->subHours(5);
            
            // creacion,llenado y guardado de la instancia
            $cab = new \App\ADMCABEGRESO();
            
            $cab->TIPO = $cabecera['tipo']; 
            $cab->BODEGA = intval($cabecera['bodega']); 
             
            if($cabecera['tipo'] == 'NVT'){
                $cab->NUMERO = $bodega->NONOTA + 1;
            }else{
                $cab->NUMERO = $bodega->NOFACTURA + 1;
            }

            $cab->SERIE = trim($bodega->SERIE); 
            $cab->SECUENCIAL = $secuencialNew + 1; 
            $cab->NUMPROCESO = null; 
            $cab->NUMPEDIDO = 0; 
            $cab->NUMGUIA = null; 
            $cab->CAMION = null; 
            $cab->CHOFER = null; 
            $cab->DOCREL = null; 
            $cab->NUMEROREL = null; 
            $cab->FECHA = $date->Format('Y-d-m'); 
            $cab->FECHAVEN = $date->addDays(intval($cliente->DIASCREDIT))->format('Y-d-m'); 
            $cab->FECHADES = $cabecera['fecha_ingreso']; 
            $cab->OPERADOR = $operador1; 
            $cab->CLIENTE = $cabecera['cliente']; 
            $cab->VENDEDOR = $cabecera['usuario']; 
            $cab->PROVEEDOR = null; 
            $cab->SUBTOTAL = round($cabecera['subtotal'],2); 
            $cab->DESCUENTO = round($cabecera['descuento'],2); 
            $cab->IVA = round($cabecera['iva'],2); 
            $cab->NETO = round($cabecera['neto'],2); 
            $cab->TRANSPORTE = 0; 
            $cab->RECARGO = 0; 
            $cab->BODEGADES = 0; 
            $cab->PESO = 0; 
            $cab->VOLUMEN = 0; 
            $cab->MOTIVO = null; 
            $cab->ESTADO = "P"; 
            $cab->ESTADODOC = null; 
            $cab->TIPOVTA = "BIE"; 
            $cab->INTECXC = null; 
            $cab->OBSERVA =  $observacion; 
            $cab->COMENTA = $campo_adi; 
            $cab->INTEGRADO = null; 
            $cab->SECCON = 0; //Pendiente, se Actualiza al guardar todo.
            $cab->NUMSERIE = null; 
            $cab->NOCARGA = null; 
            $cab->APLSRI = null; 
            $cab->NUMFISICO = null; 
            $cab->HORA = $cabecera['hora_ingreso']; 
            $cab->NOMBREPC = "ServidorLaravel"; 
            $cab->claseAjuEgreso = null; 
            $cab->SUBTOTAL0 = 0; 
            $cab->NUMGUIATRANS = null; 
            $cab->GRAVAIVA = $grabaIva; 
            $cab->CREDITO = "N"; 
            $cab->ESTADODESPACHO = "N"; 
            $cab->SECAUTOVENTA = null; 
            $cab->NUMCUOTAS = null; 
            $cab->NUMGUIAREMISION = $bodega->NUMGUIAREMISION + 1; 
            $cab->SBTBIENES = round($cabecera['subtotal'],2); 
            $cab->SBTSERVICIOS = 0; 
            $cab->TIPOCLIENTE = trim($cliente->TIPO); 
            $cab->SUCURSAL = ""; 
            $cab->ACT_SCT = "N"; 
            $cab->NUMPRODUCCION = null; 
            $cab->porentregar = "N"; 
            $cab->ENTREGADA = "N"; 
            $cab->REFERENCIA = null; 
            $cab->FECHA_EMBARQUE = null; 
            $cab->BUQUE = null; 
            $cab->NAVIERA = null; 
            $cab->ALMACENARA = null; 
            $cab->PRODUCTO =null; 
            $cab->CODIGORETAILPRO = null; 
            $cab->GRMFACELEC = "N"; 
            $cab->NOAUTOGRM = null; 
            $cab->mescredito = 0; 
            $cab->tipopago = ""; 
            $cab->numeropagos = 0; 
            $cab->entrada = 0; 
            $cab->valorfinanciado = 0; 
            $cab->porinteres = 0; 
            $cab->montointeres = 0; 
            $cab->totaldeuda = 0; 
            $cab->xsubtotal = 0; 
            $cab->xsubtotal0 = 0; 
            $cab->xdescuento = 0; 
            $cab->xdescuento0 = 0; 
            $cab->xiva = 0; 
            $cab->numerosolicitud = 0; 
            $cab->mesescredito = 0; 
            $cab->ENVIADONESTLE = "N"; 
            $cab->tipotienda = null; 
            $cab->CodShip = null; 
            $cab->NUMPESAJE = 0; 
            $cab->ESFOMENTO = "N";

            $lengh_str = 9;
            $secuencial_str = strval($cab->NUMERO);
            $seC_cero = substr("000000000{$secuencial_str}",-$lengh_str);
            
            // llamdo al metodo para general la clave calve de acceso
            $claveAcc = $this->GenerarClave($seC_cero,$cab->SERIE,$parametrobo->ruc,'01',1,2,$cab->FECHA);
            $cab->NUMAUTO = $claveAcc;
            $cab->save();

            //Generar Cabecera de Egreso.
            $cabEgr = new ADMCABEGRBOD();

            $cabEgr->SECUENCIAL = $cab->SECUENCIAL;
            $cabEgr->BODEGA = $cab->BODEGA;
            $cabEgr->TIPO = $cab->TIPO;
            $cabEgr->NUMERO = $cab->NUMERO;
            $cabEgr->NUMEGRESO = $bodega->NOEGR + 1;
            $cabEgr->FECHA = $date->Format('Y-d-m');
            $cabEgr->ESTADO = "P";

            $cabEgr->save();
            
            if($cabecera['tipo'] == 'NVT'){
                $bodega->NONOTA = $bodega->NONOTA + 1;
            }else{
                $bodega->NOFACTURA = $bodega->NOFACTURA + 1;
            }
            // actualizacion de los numero 
            $bodega->NUMGUIAREMISION = $cab->NUMGUIAREMISION;
            $bodega->NOEGR = $bodega->NOEGR + 1;
            

            //Procesado de los Detalles.
            foreach ($detalles as $det) {
                
                $d = new \App\ADMDETEGRESO;
                
                $grabaIvadet = "N";
                if(floatval($det['iva']) > 0){
                    $grabaIvadet = "S";
                }

                // obtiene detalles de los items
                $itemData = \App\ADMITEM::where('ITEM','=',trim($det['item']))->first();

                $d->SECUENCIAL = intval($cab->SECUENCIAL);
                $d->LINEA = intval($det['linea']);
                $d->ITEM = $det['item'];
                $d->TIPOITEM = $det['tipo_item'];
                $d->PRECIO = floatval($det['precio']);
                $d->COSTOP = $itemData->COSTOP;
                $d->COSTOU = $itemData->COSTOU;
                $d->CANTIU = intval($det['total_unidades']) % $itemData->FACTOR;
                $d->CANTIC = intval($det['total_unidades']  / $itemData->FACTOR);
                $d->CANTFUN = intval($det['total_unidades']);
                $d->CANTDEV = null;
                $d->SUBTOTAL = floatval($det['subtotal']);
                $d->DESCUENTO = floatval($det['descuento']);
                $d->IVA = floatval($det['iva']);
                $d->NETO = floatval($det['neto']);
                $d->PORDES = floatval($det['pordes']);
                $d->LINEAREL = null;
                $d->TIPOVTA = "V";
                $d->FORMAVTA = $det['forma_venta'];
                $d->GRAVAIVA = $grabaIvadet;
                $d->PORDES2 = 0;
                $d->CANTENTREGADA = 0;
                $d->CANTPORENTREGAR = null;
                $d->DETALLE = $campo_adi;
                $d->FECHAELA = null;
                $d->FECHAVEN = null;
                $d->LOTE = null;
                $d->preciox = 0;
                $d->serialitem = 0;


                //Bajar Stock del item.
                $itemData->STOCK = $itemData->STOCK - $d->CANTFUN;
                $itemData->save();
                
                //Bajar Stock de la Bodega del ITEM utilizando otro metodo ya que no posee PK.
                $itemBog = \App\ADMITEMBOD::where('ITEM',trim($d->ITEM))
                ->where('BODEGA',$cab->BODEGA)
                ->first();

                $stockBod = $itemBog->STOCK - $d->CANTFUN;

                $result = DB::table('ADMITEMBOD')
                            ->where('ITEM', trim($d->ITEM))
                            ->where('BODEGA',$cab->BODEGA)
                            ->update([
                                'STOCK' =>$stockBod,
                                'ULTFECEGR' => Carbon::now()->subHours(5)->Format('Y-d-m')
                            ]);
                
                //Generar el Detalle de Egreso.
                $detEgr = new ADMDETEGRBOD();
                $detEgr->SECUENCIAL =$cab->SECUENCIAL;
                $detEgr->ITEM = $d->ITEM;
                $detEgr->CANTIU = $d->CANTIU;
                $detEgr->CANTIC = $d->CANTIC; 
                $detEgr->COSTOP = $d->COSTOP;
                $detEgr->COSTOU = $d->COSTOU;
                $detEgr->CANTFUN = $d->CANTFUN;
                
                $d->save();
                $detEgr->save();
            }

            $bodega->save();
            
            //Generar la Deuda.
            $deuda = new ADMDEUDA();
            $deuda->SECUENCIAL = $cab->SECUENCIAL + 1;
            $deuda->BODEGA = $cab->BODEGA;
            $deuda->CLIENTE = $cab->CLIENTE;
            $deuda->TIPO = $cab->TIPO;
            $deuda->NUMERO = $cab->NUMERO;
            $deuda->SERIE = $cab->SERIE;
            $deuda->SECINV = $cab->SECUENCIAL;
            $deuda->IVA = round($cab->IVA,2);
            $deuda->MONTO = round($cab->NETO,2);
            $deuda->CREDITO = 0;
            $deuda->SALDO = round($cab->NETO,2);
            $deuda->FECHAEMI = $cab->FECHA;
            $deuda->FECHAVEN = $cab->FECHAVEN;
            $deuda->FECHADES = $cab->FECHA;
            $deuda->OPERADOR = $operador1;
            $deuda->VENDEDOR = $cab->VENDEDOR;
            $deuda->OBSERVACION =  $observacion;
            $deuda->NUMAUTO = "";
            $deuda->BODEGAFAC = 0;
            $deuda->SERIEFAC = "";
            $deuda->NUMEROFAC = 0;
            $deuda->ACT_SCT = "N";
            $deuda->montodocumento = 0;
            $deuda->tipoventa = "";
            $deuda->mesescredito = 0;
            $deuda->tipopago = "";
            $deuda->numeropagos = 0;
            $deuda->entrada =0;
            $deuda->valorfinanciado = 0;
            $deuda->porinteres = 0;
            $deuda->montointeres = 0;
            $deuda->totaldeuda = 0;
            $deuda->seccreditogen = 0;
            $deuda->secdeudagen = 0;
            $deuda->numcuotagen = 0;
            $deuda->porinteresmora = 0;
            $deuda->basecalculo = 0;
            $deuda->diasatraso = 0;
            $deuda->usuarioeli = 0;
            $deuda->EWEB = "N";
            //$deuda->ESTADOLIQ = "N";
            
            //Generar el Credito.
            $credito = new \App\ADMCREDITO();
            $credito->SECUENCIAL = $deuda->SECUENCIAL;
            $credito->BODEGA = $deuda->BODEGA;
            $credito->CLIENTE = $deuda->CLIENTE;
            $credito->TIPO = $deuda->TIPO;
            $credito->NUMERO = $deuda->NUMERO;
            $credito->SERIE = $deuda->SERIE;
            $credito->SECINV = $deuda->SECINV;
            $credito->FECHA = $deuda->FECHA;
            $credito->MONTO = round($deuda->MONTO,2) ;
            $credito->SALDO = round($deuda->MONTO,2) ;
            $credito->OPERADOR = $operador1;
            $credito->OBSERVACION =  $observacion;
            $credito->VENDEDOR = $deuda->VENDEDOR;
            $credito->estafirmado = "N";
            $credito->ACT_SCT = "N";
            $credito->seccreditogen = 0 ;

            $parametrov = ADMPARAMETROV::first();
            $deuda->save();
            $credito->save();
            $parametrov->SECUENCIAL =  $parametrov->SECUENCIAL + 1;
            $parametrov->save();
            
            //$claveAcc = $this->GenerarClave('001391200','002152','0991503102001','01','1','2','07-02-2020');
            
            //Guardado de todo en caso de exito en las operaciones.
            DB::commit();

            $order = $cab;
            $detalles = \App\ADMDETEGRESO::where('SECUENCIAL',$cab->SECUENCIAL)->get();
            $pdf = PDF::loadView('pdfs/pdffactura2',['cabecera'=>$order,'cliente'=>$cliente,'parametrobo'=>$parametrobo,'detalles'=>$detalles]);

            $vendedor = \App\ADMVENDEDOR::where('CODIGO','=',$cabecera['usuario'])->first();
            $vendEmail = $vendedor->email;

            
            //Validacion de Email del Cliente.
            $clienteEmail = trim($cliente->EMAIL);
            if(trim($cabecera['email'] != null)){
                if(filter_var(trim($cabecera['email'], FILTER_VALIDATE_EMAIL))){
                    $clienteEmail = $cabecera['email'];
                }
            }
          
            //Tipo Doc para Email
            $tipoDocumento = "Factura";
            if($cabecera['tipo'] == 'NVT'){
                $tipoDocumento = "NotaPedido";
            }
            
            $nombreMail = env("MAIL_USERNAME","facturas@sistemas.com");

            // envio del email
            if (filter_var($clienteEmail, FILTER_VALIDATE_EMAIL)) {
                //Email Valido
                try {
                    Mail::send('emails.FacPDF', ['pdf'=>$pdf,'cliente'=>trim($cliente)], function ($mail) use ($pdf,$clienteEmail,$vendEmail,$tipoDocumento,$nombreMail) {
                        $mail->from($nombreMail,$tipoDocumento.' Electronica');
                        $mail->to($clienteEmail);
                        $mail->cc($vendEmail);
                        $mail->subject($tipoDocumento.' PDF');
                        $mail->attachData($pdf->output(), $tipoDocumento.'.pdf');
                    });
                } catch (\Exception $e) {
                    return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL,"email"=>"error_enviando"]);
                    //return response()->json(["error"=>["info Email ambos"=>$e->getMessage()]]);;
                }
                return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL,"email"=>"enviado"]);
                //return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL]);
               
            } else {
                //Email No valido
                try {
                    Mail::send('emails.FacPDF', ['pdf'=>$pdf,'cliente'=>trim($cliente)], function ($mail) use ($pdf,$vendEmail,$tipoDocumento,$nombreMail) {
                        $mail->from($nombreMail, $tipoDocumento.' Electronica');
                        $mail->to($vendEmail);
                        $mail->subject($tipoDocumento.' PDF');
                        $mail->attachData($pdf->output(), $tipoDocumento.'.pdf');
                    });
                } catch (\Exception $e) {
                    return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL,"email"=>"error_enviando"]);
                    //return response()->json(["error"=>["info Email solo Vendedor:"=>$e->getMessage()]]);
                }
                return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL,"email"=>"enviado"]);
               
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>["info"=>$e->getMessage()]]);
        }
    }
    
    //Vista para ver la Generacion de un PDF desde el Navegador.
    public function Facturapdf(){
        
        //return response()->json(0);
        $order = \App\ADMCABEGRESO::where('NUMERO',104)
        ->whereIn('TIPO',['FAC','NVT'])
        ->orderby('SECUENCIAL','DESC')
        ->first();

        $parametrobo = ADMPARAMETROBO::first();
        $cliente = Cliente::where('CODIGO','=',$order->CLIENTE)->first();

        $detalles = \App\ADMDETEGRESO::where('SECUENCIAL',$order->SECUENCIAL)->get();
        return PDF::loadView('pdfs.pdffactura2',['cabecera'=>$order,'cliente'=>$cliente,'parametrobo'=>$parametrobo,'detalles'=> $detalles])->stream('archivo.pdf');
    }

    //envio de email de test
    public function TestEmail(){
        
        try {
            Mail::send('emails.TestEmailServer',[], function ($mail) {
                $mail->from(env("MAIL_USERNAME"), 'Test de Email');
                $mail->to('salvatorex89@gmail.com');
                $mail->subject('Test envio email');
            });            
            return "Mensaje enviado";
        } catch (\Throwable $th) {
            return $th->getMessage();
        }      
    }

    //Funcion para Clave de Acceso.
    public function SumaPorDigito($string5)
    {
        try {

            $pivote = 2;
            $longitudCadena = strlen($string5);
            $cantidadTotal = 0;
            $b = 1;

            $longi = $longitudCadena;
    
            for ($i = 0; $i < $longi; $i++) 
            {
               if($pivote == 8) {
                   $pivote = 2;
                   
                } 
               $substr = substr($string5,$i ,1);
               $temporal = (int)$substr;
               $b++;
               $temporal = (int)$temporal * (int)$pivote;
               $pivote++;
               $cantidadTotal = (int)$cantidadTotal + (int)$temporal;
            }
    
            $cantidadTotal = 11 - $cantidadTotal % 11;
            
            return $cantidadTotal;
        } catch (\Exception $e) {
            return response()->json(["error"=>["info"=>$e->getMessage()]]);
        }

    }

    //Funcion Principal Clave de Acceso.
    public function GenerarClave($secuencial6,$Serie6,$ruc,$codDoc,$tipoemision,$ambiente,$fecha_fac){
        try {
            $dat = Carbon::now()->subHours(5);
            $dateDMY = $dat->Format('d-m-Y');
            $obtender_fecha = str_replace('-','',$dateDMY);
            $codigo_Numerico = '12345678';
    
            $ret_claveAcceso = $obtender_fecha.''.$codDoc.''.$ruc.''.$ambiente.''.$Serie6.''.$secuencial6.''.$codigo_Numerico.''.$tipoemision;
    
            $cadenaInvertida = strrev($ret_claveAcceso);

            $res = $this->SumaPorDigito($cadenaInvertida);
           
            if (intval($res) == 11) {
                $ret_claveAcceso = $ret_claveAcceso."0";
            }elseif(intval($res) == 10){
                $ret_claveAcceso = $ret_claveAcceso."1";
            }else{
                $ret_claveAcceso = $ret_claveAcceso.strval($res);
            }
            return $ret_claveAcceso;
        } catch (\Exception $e) {
            return false;
        }

    }

}