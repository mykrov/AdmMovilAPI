<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $LINEA
 * @property float $SECUENCIAL
 * @property string $ITEM
 * @property float $CANTIC
 * @property float $CANTIU
 * @property float $CANTFUN
 * @property float $PRECIO
 * @property float $SUBTOTAL
 * @property float $PORDES
 * @property float $DESCUENTO
 * @property float $IVA
 * @property float $ICE
 * @property float $NETO
 * @property float $COSTOP
 * @property float $COSTOU
 * @property string $TIPOITEM
 * @property string $FORMAVTA
 * @property string $ESTADO
 * @property string $FACTURADO
 * @property string $GRAVAIVA
 * @property string $VENDEDOR
 * @property float $PORDESADIC
 * @property string $FECHACREA
 * @property string $ESCAMBIO
 */
class ADMDETPEDIDOX extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETPEDIDOX';

    /**
     * @var array
     */
    protected $fillable = ['LINEA', 'SECUENCIAL', 'ITEM', 'CANTIC', 'CANTIU', 'CANTFUN', 'PRECIO', 'SUBTOTAL', 'PORDES', 'DESCUENTO', 'IVA', 'ICE', 'NETO', 'COSTOP', 'COSTOU', 'TIPOITEM', 'FORMAVTA', 'ESTADO', 'FACTURADO', 'GRAVAIVA', 'VENDEDOR', 'PORDESADIC', 'FECHACREA', 'ESCAMBIO'];

}
