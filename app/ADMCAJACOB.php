<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $codigo
 * @property string $nombre
 * @property string $responsable
 * @property float $numcie
 * @property string $fechaini
 * @property string $fechafin
 * @property string $fechaultimocierre
 * @property string $fechacreacion
 * @property string $estadocaja
 * @property string $estado
 * @property float $NUMFAC
 * @property float $NUMNVT
 * @property float $NUMRET
 * @property string $SERIE
 * @property float $NUMNCR
 * @property string $CIUDAD
 * @property string $DIRECCION
 * @property float $APERTURA
 */
class ADMCAJACOB extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCAJACOB';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'codigo';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['nombre', 'responsable', 'numcie', 'fechaini', 'fechafin', 'fechaultimocierre', 'fechacreacion', 'estadocaja', 'estado', 'NUMFAC', 'NUMNVT', 'NUMRET', 'SERIE', 'NUMNCR', 'CIUDAD', 'DIRECCION', 'APERTURA'];

}
