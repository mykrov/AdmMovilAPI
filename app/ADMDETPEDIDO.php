<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $LINEA
 * @property float $SECUENCIAL
 * @property string $ITEM
 * @property int $CANTIC
 * @property int $CANTIU
 * @property int $CANTFUN
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
 * @property int $LINGENCONDICION
 * @property float $PORDES2
 * @property string $TIPOPEDIDO
 * @property string $DETALLE
 * @property string $fechaela
 * @property string $fechaven
 * @property string $lote
 * @property string $codigo_doc
 * @property string $tipoproducto
 * @property string $lista
 */
class ADMDETPEDIDO extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETPEDIDO';

    /**
     * @var array
     */
    protected $fillable = ['LINEA', 'SECUENCIAL', 'ITEM', 'CANTIC', 'CANTIU', 'CANTFUN', 'PRECIO', 'SUBTOTAL', 'PORDES', 'DESCUENTO', 'IVA', 'ICE', 'NETO', 'COSTOP', 'COSTOU', 'TIPOITEM', 'FORMAVTA', 'ESTADO', 'FACTURADO', 'GRAVAIVA', 'LINGENCONDICION', 'PORDES2', 'TIPOPEDIDO', 'DETALLE', 'fechaela', 'fechaven', 'lote', 'codigo_doc', 'tipoproducto', 'lista'];

}
