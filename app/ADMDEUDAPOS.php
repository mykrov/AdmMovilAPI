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
 * @property float $IVA
 * @property float $MONTO
 * @property float $CREDITO
 * @property float $SALDO
 * @property float $MULTA
 * @property string $MOTIVO
 * @property string $FECHAEMI
 * @property string $FECHAVEN
 * @property string $FECHADES
 * @property string $FECHADEP
 * @property string $FEVENDES
 * @property string $BANCO
 * @property string $CUENTA
 * @property string $NUMCHQ
 * @property string $ESTCHQ
 * @property string $INTEGRADO
 * @property float $SECCON
 * @property string $HORA
 * @property string $OPERADOR
 * @property string $VENDEDOR
 * @property string $OBSERVACION
 * @property string $NOMBREPC
 * @property string $ESTADO
 */
class ADMDEUDAPOS extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDEUDAPOS';

    protected $primaryKey = 'SECUENCIAL';
    /**
     * @var array
     */
    protected $fillable = ['CODIGOCAJA', 'NUMEROAPERTURA', 'SECUENCIAL', 'BODEGA', 'CLIENTE', 'TIPO', 'NUMERO', 'SERIE', 'SECINV', 'IVA', 'MONTO', 'CREDITO', 'SALDO', 'MULTA', 'MOTIVO', 'FECHAEMI', 'FECHAVEN', 'FECHADES', 'FECHADEP', 'FEVENDES', 'BANCO', 'CUENTA', 'NUMCHQ', 'ESTCHQ', 'INTEGRADO', 'SECCON', 'HORA', 'OPERADOR', 'VENDEDOR', 'OBSERVACION', 'NOMBREPC', 'ESTADO'];

}
