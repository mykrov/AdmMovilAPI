<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
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
 * @property int $CAJAC
 * @property string $ESTADO
 * @property string $NUMAUTO
 * @property int $BODEGAFAC
 * @property string $SERIEFAC
 * @property float $NUMEROFAC
 * @property string $FECHAFAC
 * @property string $ACT_SCT
 * @property float $montodocumento
 * @property string $tipoventa
 * @property int $mesescredito
 * @property string $tipopago
 * @property int $numeropagos
 * @property float $entrada
 * @property float $valorfinanciado
 * @property float $porinteres
 * @property float $montointeres
 * @property float $totaldeuda
 * @property float $seccreditogen
 * @property float $secdeudagen
 * @property int $numcuotagen
 * @property float $porinteresmora
 * @property float $basecalculo
 * @property int $diasatraso
 * @property string $fechaeli
 * @property string $usuarioeli
 * @property string $EWEB
 * @property string $ESTADOLIQ
 */
class ADMDEUDA extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDEUDA';

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
    protected $fillable = ['BODEGA', 'CLIENTE', 'TIPO', 'NUMERO', 'SERIE', 'SECINV', 'IVA', 'MONTO', 'CREDITO', 'SALDO', 'MULTA', 'MOTIVO', 'FECHAEMI', 'FECHAVEN', 'FECHADES', 'FECHADEP', 'FEVENDES', 'BANCO', 'CUENTA', 'NUMCHQ', 'ESTCHQ', 'INTEGRADO', 'SECCON', 'HORA', 'OPERADOR', 'VENDEDOR', 'OBSERVACION', 'NOMBREPC', 'CAJAC', 'ESTADO', 'NUMAUTO', 'BODEGAFAC', 'SERIEFAC', 'NUMEROFAC', 'FECHAFAC', 'ACT_SCT', 'montodocumento', 'tipoventa', 'mesescredito', 'tipopago', 'numeropagos', 'entrada', 'valorfinanciado', 'porinteres', 'montointeres', 'totaldeuda', 'seccreditogen', 'secdeudagen', 'numcuotagen', 'porinteresmora', 'basecalculo', 'diasatraso', 'fechaeli', 'usuarioeli', 'EWEB', 'ESTADOLIQ'];

}
