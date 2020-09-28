<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $CODIGOCAJA
 * @property int $NUMEROAPERTURA
 * @property float $SECUENCIAL
 * @property int $BODEGA
 * @property string $CLIENTE
 * @property string $TIPO
 * @property float $NUMERO
 * @property string $SERIE
 * @property float $SECINV
 * @property string $TIPOCR
 * @property float $NUMCRE
 * @property string $TIPOREL
 * @property float $NUMEROREL
 * @property string $SERIECRE
 * @property string $NOAUTOR
 * @property string $MOTIVO
 * @property string $FECHA
 * @property float $IVA
 * @property float $MONTO
 * @property float $SALDO
 * @property string $OPERADOR
 * @property string $OBSERVACION
 * @property string $INTEGRADO
 * @property float $SECCON
 * @property float $NOGUIA
 * @property float $NOCARGA
 * @property string $VENDEDOR
 * @property string $APLSRI
 * @property string $HORA
 * @property string $NOMBREPC
 * @property float $COSTO
 * @property string $INCDVTAS
 * @property float $SUBTOTAL
 * @property float $SUBTOTAL0
 * @property string $ORINCR
 * @property string $ESTADO
 */
class ADMCREDITOPOS extends Model
{
    public  $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCREDITOPOS';

    /**
     * @var array
     */
    protected $fillable = ['CODIGOCAJA', 'NUMEROAPERTURA', 'SECUENCIAL', 'BODEGA', 'CLIENTE', 'TIPO', 'NUMERO', 'SERIE', 'SECINV', 'TIPOCR', 'NUMCRE', 'TIPOREL', 'NUMEROREL', 'SERIECRE', 'NOAUTOR', 'MOTIVO', 'FECHA', 'IVA', 'MONTO', 'SALDO', 'OPERADOR', 'OBSERVACION', 'INTEGRADO', 'SECCON', 'NOGUIA', 'NOCARGA', 'VENDEDOR', 'APLSRI', 'HORA', 'NOMBREPC', 'COSTO', 'INCDVTAS', 'SUBTOTAL', 'SUBTOTAL0', 'ORINCR', 'ESTADO'];

}
