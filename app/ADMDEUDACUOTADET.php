<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECDEUDA
 * @property float $SECINV
 * @property int $NUMCUOTA
 * @property float $VALORCUOTA
 * @property float $MONTO
 * @property float $SALDO
 * @property float $NUMPAGO
 * @property string $FECHACANCELA
 * @property string $FECHAVENCE
 * @property string $OBSERVACION
 * @property string $OPERADOR
 * @property string $MAQUINA
 * @property string $HORA
 */
class ADMDEUDACUOTADET extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDEUDACUOTADET';

    /**
     * @var array
     */
    protected $fillable = ['SECDEUDA', 'SECINV', 'NUMCUOTA', 'VALORCUOTA', 'MONTO', 'SALDO', 'NUMPAGO', 'FECHACANCELA', 'FECHAVENCE', 'OBSERVACION', 'OPERADOR', 'MAQUINA', 'HORA'];

}
