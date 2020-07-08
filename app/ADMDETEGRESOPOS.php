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
 * @property float $CANTDEV
 * @property float $SUBTOTAL
 * @property float $DESCUENTO
 * @property float $IVA
 * @property float $NETO
 * @property float $PORDES
 * @property int $LINEAREL
 * @property string $TIPOVTA
 * @property string $FORMAVTA
 * @property string $GRAVAIVA
 * @property string $lote
 * @property string $fechaven
 * @property string $escambio
 * @property string $vendedor
 * @property string $hora
 * @property string $fecha
 * @property string $estado
 * @property ADMCABEGRESOPO $aDMCABEGRESOPO
 */
class ADMDETEGRESOPOS extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETEGRESOPOS';

    /**
     * @var array
     */
    protected $fillable = ['ITEM', 'TIPOITEM', 'PRECIO', 'COSTOP', 'COSTOU', 'CANTIU', 'CANTIC', 'CANTFUN', 'CANTDEV', 'SUBTOTAL', 'DESCUENTO', 'IVA', 'NETO', 'PORDES', 'LINEAREL', 'TIPOVTA', 'FORMAVTA', 'GRAVAIVA', 'lote', 'fechaven', 'escambio', 'vendedor', 'hora', 'fecha', 'estado'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aDMCABEGRESOPO()
    {
        return $this->belongsTo('App\ADMCABEGRESOPO', 'SECUENCIAL', 'SECUENCIAL');
    }
}
