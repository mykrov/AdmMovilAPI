<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECDEUDA
 * @property integer $NUMCUOTA
 * @property float $MONTO
 * @property float $INTERES
 * @property float $SALDOPROGRAMADO
 * @property float $CREDITO
 * @property float $SALDO
 * @property string $FECHAVEN
 * @property string $FECHACANCELACUOTA
 * @property float $INTERESACUMORA
 * @property string $INTERESPAGADO
 * @property string $OBSERVACION
 * @property float $NUMPAGO
 */
class ADMDEUDACUOTA extends Model
{
    public $timestamps = false;
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDEUDACUOTA';

    /**
     * @var array
     */
    protected $fillable = ['MONTO', 'INTERES', 'SALDOPROGRAMADO', 'CREDITO', 'SALDO', 'FECHAVEN', 'FECHACANCELACUOTA', 'INTERESACUMORA', 'INTERESPAGADO', 'OBSERVACION', 'NUMPAGO'];

}
