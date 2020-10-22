<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $NUMGUIA
 * @property float $SECUENCIAL
 * @property string $CLIENTE
 * @property string $TIPO
 * @property float $NUMERO
 * @property string $SERIE
 * @property string $FECHAEMI
 * @property string $FECHAVEN
 * @property float $MONTO
 * @property float $SALDO
 * @property float $EFECTIVO
 * @property float $CHEQUE
 * @property float $FUENTE
 * @property float $IVA
 * @property float $DESCUENTO
 * @property float $OTRO
 * @property float $NOCOBRO
 * @property string $ESTADO
 * @property float $NRECIBO
 * @property float $DEPOSITO
 * @property string $ARTICULO
 * @property float $VALORCUOTA
 * @property string $FECULTPAG
 * @property float $VALORULTPAG
 */
class ADMDETGUIACOB extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETGUIACOB';

    /**
     * @var array
     */
    protected $fillable = ['NUMGUIA', 'SECUENCIAL', 'CLIENTE', 'TIPO', 'NUMERO', 'SERIE', 'FECHAEMI', 'FECHAVEN', 'MONTO', 'SALDO', 'EFECTIVO', 'CHEQUE', 'FUENTE', 'IVA', 'DESCUENTO', 'OTRO', 'NOCOBRO', 'ESTADO', 'NRECIBO', 'DEPOSITO', 'ARTICULO', 'VALORCUOTA', 'FECULTPAG', 'VALORULTPAG'];

}
