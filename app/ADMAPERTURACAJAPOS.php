<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $CODIGOCAJA
 * @property int $NUMEROAPERTURA
 * @property string $OPERADOR
 * @property string $ESTADO
 * @property integer $BODEGA
 * @property float $MONTOAPERTURA
 * @property float $MONTOVENTAS
 * @property int $NUMEROVENTAS
 * @property float $EFECTIVOVENTAS
 * @property float $CHEQUEVENTAS
 * @property float $CREDITOVENTAS
 * @property float $MONTOGASTO
 * @property float $SECDOCREL
 * @property string $TIPODOCREL
 * @property string $SERIEDOCREL
 * @property float $NUMDOCREL
 * @property string $OPERAPERTURA
 * @property string $PCAPERTURA
 * @property string $FECHADESDE
 * @property string $FECHAHASTA
 * @property string $FECHAAPERTURA
 * @property string $HORAAPERTURA
 * @property integer $CAJACIERRE
 * @property string $OPERCIERRE
 * @property string $PCCIERRE
 * @property string $FECHACIERRE
 * @property string $HORACIERRE
 * @property float $SECCON
 * @property int $NUMPAGPROV
 * @property float $EFEPAGPROV
 * @property float $CHQPAGPROV
 * @property float $EFEPAGCLI
 * @property float $CHQPAGCLI
 * @property float $DIFERENCIAEFECCONTRA
 * @property float $DIFERENCIAEFECFAVOR
 * @property float $RECARGOVENTAS
 * @property float $MONTOCIERRE
 * @property int $NUMREGGASTO
 * @property float $MONTODEVOLUCION
 * @property float $DINEROCONTADO
 * @property float $RETEFECTIVO
 * @property float $RETCHEQUES
 * @property float $buno
 * @property float $bdos
 * @property float $bcinco
 * @property float $bdiez
 * @property float $bveinte
 * @property float $bcincuenta
 * @property float $bcien
 * @property float $botros
 * @property float $muno
 * @property float $mcinco
 * @property float $mdiez
 * @property float $mveinteycinco
 * @property float $mcincuenta
 * @property float $munocentavo
 * @property float $motros
 * @property ADMCAJAPO $aDMCAJAPO
 */
class ADMAPERTURACAJAPOS extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMAPERTURACAJAPOS';

    /**
     * @var array
     */
    protected $fillable = ['OPERADOR', 'ESTADO', 'BODEGA', 'MONTOAPERTURA', 'MONTOVENTAS', 'NUMEROVENTAS', 'EFECTIVOVENTAS', 'CHEQUEVENTAS', 'CREDITOVENTAS', 'MONTOGASTO', 'SECDOCREL', 'TIPODOCREL', 'SERIEDOCREL', 'NUMDOCREL', 'OPERAPERTURA', 'PCAPERTURA', 'FECHADESDE', 'FECHAHASTA', 'FECHAAPERTURA', 'HORAAPERTURA', 'CAJACIERRE', 'OPERCIERRE', 'PCCIERRE', 'FECHACIERRE', 'HORACIERRE', 'SECCON', 'NUMPAGPROV', 'EFEPAGPROV', 'CHQPAGPROV', 'EFEPAGCLI', 'CHQPAGCLI', 'DIFERENCIAEFECCONTRA', 'DIFERENCIAEFECFAVOR', 'RECARGOVENTAS', 'MONTOCIERRE', 'NUMREGGASTO', 'MONTODEVOLUCION', 'DINEROCONTADO', 'RETEFECTIVO', 'RETCHEQUES', 'buno', 'bdos', 'bcinco', 'bdiez', 'bveinte', 'bcincuenta', 'bcien', 'botros', 'muno', 'mcinco', 'mdiez', 'mveinteycinco', 'mcincuenta', 'munocentavo', 'motros'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aDMCAJAPO()
    {
        return $this->belongsTo('App\ADMCAJAPO', 'CODIGOCAJA', 'codigo');
    }
}
