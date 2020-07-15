<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECAUTO
 * @property string $TIPO
 * @property int $BODEGA
 * @property float $NUMERO
 * @property float $SECUENCIAL
 * @property string $CLIENTE
 * @property string $VENDEDOR
 * @property string $FECHA
 * @property string $ESTADO
 * @property float $SUBTOTAL
 * @property float $DESCUENTO
 * @property float $IVA
 * @property float $NETO
 * @property float $PESO
 * @property float $VOLUMEN
 * @property string $OPERADOR
 * @property string $COMENTARIO
 * @property string $OBSERVACION
 * @property float $FACTURA
 * @property float $GUIA
 * @property string $DOCFAC
 * @property string $DIASCRED
 * @property string $GRAVAIVA
 * @property string $CREDITO
 * @property integer $NUMCUOTAS
 * @property string $FECHALIBERACION
 * @property string $HORALIBERACION
 * @property string $OPERLIBERACION
 * @property float $TRANSPORTE
 * @property float $RECARGO
 * @property string $TIPOCLIENTE
 * @property string $SUCURSAL
 * @property string $ESMOVIL
 * @property string $SECAUTOPEDMOVIL
 * @property string $SERIEPOLOCLUB
 * @property string $CODIGORETAILPRO
 * @property float $numeroplantilla
 * @property float $CODIGOPEDIDO
 */
class ADMCABPEDIDO extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCABPEDIDO';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'SECUENCIAL';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'float';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['SECAUTO', 'TIPO', 'BODEGA', 'NUMERO', 'CLIENTE', 'VENDEDOR', 'FECHA', 'ESTADO', 'SUBTOTAL', 'DESCUENTO', 'IVA', 'NETO', 'PESO', 'VOLUMEN', 'OPERADOR', 'COMENTARIO', 'OBSERVACION', 'FACTURA', 'GUIA', 'DOCFAC', 'DIASCRED', 'GRAVAIVA', 'CREDITO', 'NUMCUOTAS', 'FECHALIBERACION', 'HORALIBERACION', 'OPERLIBERACION', 'TRANSPORTE', 'RECARGO', 'TIPOCLIENTE', 'SUCURSAL', 'ESMOVIL', 'SECAUTOPEDMOVIL', 'SERIEPOLOCLUB', 'CODIGORETAILPRO', 'numeroplantilla', 'CODIGOPEDIDO'];

}
