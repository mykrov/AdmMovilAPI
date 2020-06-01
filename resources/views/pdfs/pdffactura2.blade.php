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
</style>
</head>
    <body style="margin: 0; padding: 0;" >
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr >
                            <td width="50%" height ="120px" align="center" >
                                <img src="img/descarga.png." alt="" style="max-height: 80px;">
                            </td>
                            <td width="50%">
                                <table cellpadding="0" cellspacing="0" width="100%" style="border-radius:6px;border-collapse:separate;border:solid black 1px">
                                    <tr style="border: 2px solid black">
                                        <td  width="100%" align="center">
                                            <h3>FACTURA NÚMERO:{{ $cabecera->NUMERO }}</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  width="100%" align="center">
                                            <h5>RUC: {{ $cliente->RUC }}</h5>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table  width ="95%" style="border-radius:6px;border-collapse:separate;border:solid black 1px; font-size:12px;padding-top:20px;padding-bottom:20px;">
                                    <tr>
                                    <td align="center"><strong>BIROBID S.A</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dirección Matriz: </strong>asdasdasdasd</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sucursal: </strong>asdasdasdasd</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Obligado a llevar contabilidad: </strong>asdasdasdasd</td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table style="font-size:12px;" >
                                    <tr>
                                        <td><strong>Número de Autorización:</strong>
                                            <p style="font-size: 9; margin-top:2px; paddin-botton:2px;">87978797987878977797979797979</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ambiente: </strong>Producción</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo de Emisión: </strong>Normal</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Clave de Acceso: </strong>
                                            <p style="font-size: 9; margin-top:0px;">38192318029389012830810283910283091823981283109</p>
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
                                            {{ $cliente->RAZONSOCIAL }}
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
                                <table>
                                    <tr>
                                        <td>
                                            <span style="font-size: 10;">Fecha de Emisión:</span> 11-06-2020
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
                        <tr class="detalle">
                            <td>C00001</td>
                            <td>Arroz</td>
                            <td>37</td>
                            <td>1.2</td>
                            <td>0</td>
                            <td>123</td>
                        </tr>
                        <tr class="detalle">
                            <td>C00001</td>
                            <td>Arroz</td>
                            <td>37</td>
                            <td>1.2</td>
                            <td>0</td>
                            <td>123</td>
                        </tr>
                        <tr class="detalle">
                            <td>C00001</td>
                            <td>Arroz</td>
                            <td>37</td>
                            <td>1.2</td>
                            <td>0</td>
                            <td>123</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <table border="0" width= "100%" style="padding-top:20px">
                    <tr>
                        <td width="65%">
                            <table style="font-size:11px;"  >
                                <tr>
                                    <td><strong>Información Adicional<strong></td>
                                </tr>
                                <tr>
                                    <td>Dirección:</td>
                                </tr>
                                <tr>
                                    <td>Teléfono:</td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                </tr>
                            </table>

                        </td>
                        <td width="35%">
                            <table style="border-radius:6px;border-collapse:separate;border:solid black 1px; font-size:11px;padding:10px 10px 10px 10px;">
                                <tr>
                                    <td>SubTotal 12%</td>
                                    <td>01231230</td>
                                </tr>
                                <tr>
                                    <td>SubTotal 0%</td>
                                    <td>01231230</td>
                                </tr>
                                <tr>
                                    <td>SubTotal No Objeto de IVA</td>
                                    <td>01231230</td>
                                </tr>
                                <tr>
                                    <td>SubTotal Excento de IVA</td>
                                    <td>01231230</td>
                                </tr>
                                <tr>
                                    <td>SUBTOTAL SIN IMPUESTOS</td>
                                    <td>0123120</td>
                                </tr>
                                <tr>
                                    <td>ICE</td>
                                    <td>01231230</td>
                                </tr>
                                <tr>
                                    <td>Propina</td>
                                    <td>0012312</td>
                                </tr>
                                <tr>
                                    <td>IVA 12%</td>
                                    <td>0123123</td>
                                </tr>
                                <tr>
                                    <td><strong>VALOR TOTAL</strong></td>
                                    <td>01231230</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </tr>
        </table>
        <table border-top="1" class="table-forma" style="padding-top: 20px;font-size:12px;border-bottom:1pxx solid;">
            <tr style="border: 1px solid; background-color:gray;">
                <td>Forma de Pago</td>
                <td>Valor</td>
                <td>Plazo</td>
                <td>Tiempo</td>
            </tr>
            <tr style="border-top:1px solid;">
                <td>Sin utilizacion del Sistema Financiero</td>
                <td>34</td>
                <td>0</td>
                <td>Dias</td>
            </tr>
        </table>
    </body>
</html>

