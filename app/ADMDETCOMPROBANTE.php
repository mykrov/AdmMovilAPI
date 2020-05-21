<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $INDICE
 * @property float $SECUENCIAL
 * @property int $TIPOCOMPROBANTE
 * @property float $NUMERO
 * @property int $LINEA
 * @property string $CUENTA
 * @property string $DETALLE
 * @property string $DBCR
 * @property float $MONTO
 * @property string $ESTADO
 * @property string $CHEQUE
 * @property string $DESCRIPCION
 * @property string $CONCILIADO
 */
class ADMDETCOMPROBANTE extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETCOMPROBANTE';

    /**
     * @var array
     */
    protected $fillable = ['INDICE', 'SECUENCIAL', 'TIPOCOMPROBANTE', 'NUMERO', 'LINEA', 'CUENTA', 'DETALLE', 'DBCR', 'MONTO', 'ESTADO', 'CHEQUE', 'DESCRIPCION', 'CONCILIADO'];

}
