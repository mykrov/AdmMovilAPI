<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $secuencial
 * @property float $indice
 * @property string $fecha
 * @property int $tipoComprobante
 * @property float $numero
 * @property string $cliente
 * @property string $detalle
 * @property float $debito
 * @property float $credito
 * @property string $estado
 * @property string $fechao
 * @property string $banco
 * @property float $cheque
 * @property string $retencion
 * @property string $operador
 * @property string $modulo
 * @property string $nocuenta
 */
class ADMCABCOMPROBANTE extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCABCOMPROBANTE';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'secuencial';

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
    protected $fillable = ['indice', 'fecha', 'tipoComprobante', 'numero', 'cliente', 'detalle', 'debito', 'credito', 'estado', 'fechao', 'banco', 'cheque', 'retencion', 'operador', 'modulo', 'nocuenta'];

}
