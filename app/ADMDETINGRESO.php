<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECUENCIAL
 * @property int $LINEA
 * @property string $ITEM
 * @property float $PRECIO
 * @property float $COSTO
 * @property float $CANTIU
 * @property float $CANTIC
 * @property float $CANTFUN
 * @property int $CANTDEV
 * @property float $SUBTOTAL
 * @property float $DESCUENTO
 * @property float $IVA
 * @property float $NETO
 * @property float $PORDES
 * @property int $LINEAREL
 * @property string $TIPOVTA
 * @property string $FORMAVTA
 * @property string $GRAVAIVA
 * @property float $SECAUTOVENTA
 * @property float $porcentajeproteina
 * @property string $lote
 * @property string $fechaven
 * @property string $FECHAELA
 * @property float $CANTOC
 * @property ADMCABINGRESO $aDMCABINGRESO
 */
class ADMDETINGRESO extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETINGRESO';

    /**
     * @var array
     */
    protected $fillable = ['PRECIO', 'COSTO', 'CANTIU', 'CANTIC', 'CANTFUN', 'CANTDEV', 'SUBTOTAL', 'DESCUENTO', 'IVA', 'NETO', 'PORDES', 'LINEAREL', 'TIPOVTA', 'FORMAVTA', 'GRAVAIVA', 'SECAUTOVENTA', 'porcentajeproteina', 'lote', 'fechaven', 'FECHAELA', 'CANTOC'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aDMCABINGRESO()
    {
        return $this->belongsTo('App\ADMCABINGRESO', 'SECUENCIAL', 'SECUENCIAL');
    }
}
