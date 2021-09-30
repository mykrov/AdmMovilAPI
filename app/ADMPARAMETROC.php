<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $MODULO
 * @property string $NOMBRECIA
 * @property int $ANIO
 * @property float $SECUENCIAL
 * @property float $NUMHOJA
 * @property string $PROVINCIA
 * @property string $CANTON
 * @property string $PARROQUIA
 * @property string $SECTOR
 * @property string $INTEGCXC
 * @property string $LETRAINI
 * @property float $NUMCLIENTE
 * @property string $AUTOCLI
 * @property string $INTCXCCON
 * @property string $FECHACXC
 * @property float $GUIACOB
 * @property float $NOCOBRO
 * @property float $VALORNCR
 * @property string $CAJAC
 * @property string $AUTOCODIGO
 * @property string $FECHACAJA
 * @property string $CAMCRE
 * @property string $CREDDEF
 * @property string $CUPOCARTERA
 * @property string $CONTROLDEPOCIERRE
 * @property string $COBRADOR
 * @property string $CONTROLRECIBO
 * @property string $GENERACUOTAS
 * @property string $IMPNCR2
 * @property string $REPETIRNRECIBO
 * @property string $CONTROLBASESRET
 * @property string $BENEDEFAULT
 * @property string $VENDEDORPOS
 * @property string $CODVENPOS
 * @property string $SUCURSALAUTO
 * @property string $TIPOCLIENTE
 * @property int $NUMPACIENTE
 * @property int $NUMCONTRATO
 * @property int $NUMENVIOLAB
 * @property int $NUMDOCTOR
 * @property int $diasgraciavencuota
 * @property string $interes
 * @property string $generandb
 * @property string $generapagondb
 * @property float $porintmoradia
 * @property string $controlcupofvd
 * @property string $posbloqueofaccre
 * @property string $CLIENTEMODELOCARRO
 * @property string $eletipointeres
 * @property string $eleusarformadepago
 */
class ADMPARAMETROC extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMPARAMETROC';

    /**
     * @var array
     */
    protected $fillable = ['MODULO', 'NOMBRECIA', 'ANIO', 'SECUENCIAL', 'NUMHOJA', 'PROVINCIA', 'CANTON', 'PARROQUIA', 'SECTOR', 'INTEGCXC', 'LETRAINI', 'NUMCLIENTE', 'AUTOCLI', 'INTCXCCON', 'FECHACXC', 'GUIACOB', 'NOCOBRO', 'VALORNCR', 'CAJAC', 'AUTOCODIGO', 'FECHACAJA', 'CAMCRE', 'CREDDEF', 'CUPOCARTERA', 'CONTROLDEPOCIERRE', 'COBRADOR', 'CONTROLRECIBO', 'GENERACUOTAS', 'IMPNCR2', 'REPETIRNRECIBO', 'CONTROLBASESRET', 'BENEDEFAULT', 'VENDEDORPOS', 'CODVENPOS', 'SUCURSALAUTO', 'TIPOCLIENTE', 'NUMPACIENTE', 'NUMCONTRATO', 'NUMENVIOLAB', 'NUMDOCTOR', 'diasgraciavencuota', 'interes', 'generandb', 'generapagondb', 'porintmoradia', 'controlcupofvd', 'posbloqueofaccre', 'CLIENTEMODELOCARRO','eletipointeres','eleusarformadepago'];

}
