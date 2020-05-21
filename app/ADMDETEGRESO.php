<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECUENCIAL
 * @property int $LINEA
 * @property string $ITEM
 * @property string $TIPOITEM
 * @property float $PRECIO
 * @property float $COSTOP
 * @property float $COSTOU
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
 * @property float $PORDES2
 * @property float $CANTENTREGADA
 * @property float $CANTPORENTREGAR
 * @property string $DETALLE
 * @property string $FECHAELA
 * @property string $FECHAVEN
 * @property string $LOTE
 * @property float $preciox
 * @property string $serialitem
 * @property ADMCABEGRESO $aDMCABEGRESO
 */
class ADMDETEGRESO extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETEGRESO';

    /**
     * @var array
     */
    protected $fillable = ['SECUENCIAL', 'LINEA', 'ITEM', 'TIPOITEM', 'PRECIO', 'COSTOP', 'COSTOU', 'CANTIU', 'CANTIC', 'CANTFUN', 'CANTDEV', 'SUBTOTAL', 'DESCUENTO', 'IVA', 'NETO', 'PORDES', 'LINEAREL', 'TIPOVTA', 'FORMAVTA', 'GRAVAIVA', 'PORDES2', 'CANTENTREGADA', 'CANTPORENTREGAR', 'DETALLE', 'FECHAELA', 'FECHAVEN', 'LOTE', 'preciox', 'serialitem'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aDMCABEGRESO()
    {
        return $this->belongsTo('App\ADMCABEGRESO', 'SECUENCIAL', 'SECUENCIAL');
    }
}
