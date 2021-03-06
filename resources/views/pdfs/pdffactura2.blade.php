<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>FACTURA</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<style>
    .cabecera{
        background-color: rgb(177, 177, 177);
        align-items: center;
        text-align: center;
        border-bottom: 2px solid;
    }
    .detalle{
        border-bottom: 1px;
        align-items: center;
        text-align: center;
    }
    .detalle td{
        border-bottom: 1px solid;
    }

    .barcod{
        max-width: 400px;
        display: block;
    }
</style>
</head>
    <body style="margin: 0; padding: 0;" >
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr >
                            <td width="50%" height ="120px" align="center" >
                                @php
                                    if( $cabecera->TIPO == 'FAC'){
                                        echo('<img src="img/logo.jpg." alt="" style="max-height: 120px;">');
                                    }else{
                                        echo('<h1>NOTA DE PEDIDO</h1>');
                                    }
                                @endphp
                                
                            </td>
                            <td width="50%">
                                <table cellpadding="0" cellspacing="0" width="100%" style="border-radius:6px;border-collapse:separate;border:solid black 1px">
                                    <tr style="border: 2px solid black">
                                        <td  width="100%" align="center">
                                            @php
                                                $newstr = substr_replace($cabecera->SERIE,'-', 3, 0);
                                            @endphp
                                            <h5>
                                                @php
                                                    if( $cabecera->TIPO == 'FAC'){
                                                        echo('FACTURA NÚMERO:');
                                                    }else{
                                                        echo('PEDIDO NÚMERO:');
                                                    }
                                                @endphp
                                                 
                                                {{ $newstr.'-'.substr("000000000{$cabecera->NUMERO}",-9)}}</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  width="100%" align="center">
                                            <h5>RUC: {{ $parametrobo->ruc }}</h5>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <table  width ="95%" style="border-radius:6px;border-collapse:separate;border:solid black 1px; font-size:12px;padding-top:20px;padding-bottom:20px;">
                                    <tr>
                                    <td align="center"><strong>{{ $parametrobo->nombrecia }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dirección Matriz: </strong><span style="font-size: 10px">{{ $parametrobo->direccion }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sucursal: </strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @php
                                            if( $cabecera->TIPO == 'FAC'){
                                                echo('<strong>Obligado a llevar contabilidad: </strong>Si');
                                            }
                                            @endphp
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%">
                                <table style="font-size:12px;"  width ="50%">

                                    @php
                                     if($cabecera->TIPO == 'FAC'){
                                    @endphp
                                        <tr>
                                            <td><strong>Número de Autorización:</strong>
                                                {{ $cabecera->NUMAUTO }}
                                            </td>
                                        </tr>
                                        <tr >
                                            <td><strong>Ambiente: </strong>Producción</td>
                                        </tr>
                                        <tr >
                                            <td><strong>Tipo de Emisión: </strong>Normal</td>
                                        </tr>
                                        <tr style="border:solid;">
                                            <td>
                                                <strong style="margin-bottom:3px;">Clave de Acceso: </strong> 
                                                <br>                       
                                                <span style="padding-top:4px;margin-top:4px;">
                                                    @php
                                                    $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
                                                    $redColor = [255, 0, 0];
                                                    echo '<img style="max-width: 38rem;width:22rem; height:3rem;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($cabecera->NUMAUTO, $generator::TYPE_CODE_128,1,80,'black')) . '"">';
                                                    @endphp
                                                </span>
                                                <p style="font-size: 9; margin-top:4px;">{{ $cabecera->NUMAUTO }}</p>
                                            </td>
                                        </tr>
                                    @php
                                    }else{
                                        echo('<tr><td style="padding-bottom: 120px"></td></tr>');
                                    }
                                    @endphp

                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                     <table  cellpadding="0" cellspacing="0" width="100%" style="font-size:12px; border-radius:6px;border-collapse:separate;border:solid black 1px">
                        <tr>
                            <td width ="33%">
                                <table>
                                    <tr>
                                        <td>
                                            <strong>Razon Social:</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >
                                            <span style="font-size: 10px">{{ $cliente->RAZONSOCIAL }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width ="33%">
                                <table>
                                    <tr>
                                        <td>
                                            <strong>Identificación:</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:12px">
                                            {{ $cliente->RUC }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width ="33%">
                                @php
                                    $orgDate =  DateTime::createFromFormat('Y-d-m',$cabecera->FECHA);  
                                    //$newDate = date("d-m-Y", $orgDate);
                                @endphp     
                                <table>
                                    <tr>
                                        <td>
                                            <span style="font-size: 10;">Fecha de Emisión:</span><span style="font-size: 12px"> {{ $cabecera->FECHA }}</span> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 10;">Guia de Remisión:</span> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 10;">Moneda:</span> Dolar.
                                        </td>
                                    </tr>
            
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="margin-top:15px; font-size:10px;" width="100%">
                        <tr class="cabecera">
                            <td>Código</td>
                            <td>Descripción</td>
                            <td>Cantidad</td>
                            <td>Precio Unitario</td>
                            <td>Descuento</td>
                            <td>Total</td>
                        </tr> 
                        @foreach ($detalles as $item)
                        <tr class="detalle">
                            <td>{{$item->ITEM}}</td>
                            <td>{{\App\ADMITEM::where(['ITEM' => $item->ITEM])->pluck('NOMBRE')->first()}}</td>
                            <td>{{intval($item->CANTFUN)}}</td>
                            <td>
                            @php 
                                if ($cabecera->TIPO == 'FAC') {
                                    echo(number_format($item->PRECIO,2,'.',','));
                                }else{
                                    if ($item->GRAVAIVA == 'S') {
                                        echo(number_format(($item->SUBTOTAL / intval($item->CANTFUN) * 1.12),2,'.',','));
                                    } else {
                                        echo(number_format($item->SUBTOTAL / intval($item->CANTFUN),2,'.',','));
                                    }
                                }
                            @endphp
                           </td>
                            <td>{{number_format($item->DESCUENTO,2,'.',',')}}</td>
                            <td>
                            @php
                                if ($cabecera->TIPO == 'FAC') {
                                    number_format($item->SUBTOTAL,2,'.',',');
                                }else{
                                    if ($item->GRAVAIVA == 'S') {
                                       echo(number_format($item->SUBTOTAL * (1.12),2,'.',','));
                                    } else {
                                        echo(number_format($item->SUBTOTAL,2,'.',','));
                                    }
                                }
                                @endphp
                            
                            </td>
                        </tr>   
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <table border="0" width= "100%" style="padding-top:20px">
                    <tr>
                        <td width="65%">
                            <table style="font-size:11px;"  >
                                <tr>
                                    <td><strong>Información Adicional:<strong></td>
                                </tr>
                                <tr>
                                    <td>Dirección: {{ $cliente->DIRECCION}}</td>
                                </tr>
                                <tr>
                                    <td>Teléfono: {{ $cliente->TELEFONOS}}</td>
                                </tr>
                                <tr>
                                    <td>Email: {{ $cliente->EMAIL}}</td>
                                </tr>
                            </table>

                        </td>
                        @php
                            $subTotal12 = 0.0;
                            $subTotal0 = 0.0;
                            $noObjetoIVA = 0.0;
                            $excentoIVA = 0.0;
                            $sinImpuetos = 0.0;
                            $iva = 0.0;
                            
                            foreach ($detalles as $val) {
                                if ($val['GRAVAIVA'] == 'S') {
                                    $subTotal12 += $val['SUBTOTAL'];
                                    $iva += $val['IVA'];
                                }else{
                                    $subTotal0 += $val['SUBTOTAL'];
                                }
                            }
                            $sinImpuestos = $subTotal12 + $subTotal0;
                            $valorTotal = $sinImpuestos + $iva;
                        @endphp

                       
                        
                        <td width="35%">
                            <table style="border-radius:6px;border-collapse:separate;border:solid black 2px; font-size:11px;padding:10px 10px 10px 10px;">
                                @php
                                if ($cabecera->TIPO == 'FAC') { @endphp
                                <tr>
                                    <td>SubTotal 12%</td>
                                    <td>{{ number_format($cabecera->SUBTOTAL - $cabecera->SUBTOTAL0,2,'.',',') }}</td>
                                </tr>
                                <tr>
                                    <td>SubTotal 0%</td>
                                    <td>{{ number_format($cabecera->SUBTOTAL0,2,'.',',')  }}</td>
                                </tr>
                                <tr>
                                    <td>SubTotal No Objeto de IVA</td>
                                    <td>{{ number_format(0.0,2,'.',',')  }}</td>
                                </tr>
                                <tr>
                                    <td>SubTotal Excento de IVA</td>
                                    <td>{{ number_format(0.0,2,'.',',')  }}</td>
                                </tr>
                                <tr>
                                    <td>SUBTOTAL SIN IMPUESTOS</td>
                                    <td>{{ number_format($cabecera->SUBTOTAL,2,'.',',')  }}</td>
                                </tr>
                                <tr>
                                    <td>Descuentos</td>
                                    <td>{{ number_format($cabecera->DESCUENTO,2,'.',',')  }}</td>
                                </tr>
                                <tr>
                                    <td>ICE</td>
                                    <td>0.0</td>
                                </tr>
                                <tr>
                                    <td>Propina</td>
                                    <td>0.0</td>
                                </tr>
                                <tr>
                                    <td>IVA 12%</td>
                                    <td>{{ number_format($cabecera->IVA,2,'.',',') }}</td>
                                </tr>
                                
                                @php
                                }  
                                @endphp  
                                
                                <tr>
                                    <td><strong>VALOR TOTAL</strong></td>
                                    <td><strong>{{ number_format($cabecera->NETO,2,'.',',') }}</strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </tr>
        </table>

        @php
        if ($cabecera->TIPO == 'FAC') {
        @endphp
        
        <table border-top="1" class="table-forma" style="padding-top: 20px;font-size:12px;border-bottom:1pxx solid;">
            <tr style="border: 1px solid; background-color:gray;">
                <td>Forma de Pago</td>
                <td>Valor</td>
                <td>Plazo</td>
                <td>Tiempo</td>
            </tr>
            <tr style="border-top:1px solid;">
                <td>Sin utilizacion del Sistema Financiero </td>
                <td>{{ number_format($cabecera->NETO,2,'.',',') }}</td>
                {{-- <td> {{ number_format($valorTotal,2,'.',',')}}</td> --}}
                <td>0</td>
                <td>Dias</td>
            </tr>
        </table>
        @php
        }
        @endphp
        <div style="max-width: 32rem;min-height: 5rem;">
           
        </div>
    </body>
</html>

