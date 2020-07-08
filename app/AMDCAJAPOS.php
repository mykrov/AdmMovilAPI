<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $codigo
 * @property string $nombre
 * @property string $responsable
 * @property int $numeroapertura
 * @property string $seriedocumento
 * @property float $numerodocumento
 * @property float $ultimocierre
 * @property int $numeroventa
 * @property string $fechaultimocierre
 * @property string $fechacreacion
 * @property string $estadocaja
 * @property string $estado
 * @property string $numauto
 * @property string $fechavenauto
 * @property int $bodega
 * @property string $ciudad
 * @property string $direccion
 * @property float $NCR
 * @property float $NCRINT
 * @property float $NORETENCION
 * @property string $POSIMPRIMEENTREGA
 * @property string $SOLOFACTURA
 * @property ADMAPERTURACAJAPO[] $aDMAPERTURACAJAPOSs
 */
class AMDCAJAPOS extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCAJAPOS';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'codigo';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['nombre', 'responsable', 'numeroapertura', 'seriedocumento', 'numerodocumento', 'ultimocierre', 'numeroventa', 'fechaultimocierre', 'fechacreacion', 'estadocaja', 'estado', 'numauto', 'fechavenauto', 'bodega', 'ciudad', 'direccion', 'NCR', 'NCRINT', 'NORETENCION', 'POSIMPRIMEENTREGA', 'SOLOFACTURA'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aDMAPERTURACAJAPOSs()
    {
        return $this->hasMany('App\ADMAPERTURACAJAPO', 'CODIGOCAJA', 'codigo');
    }
}
