<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECUENCIAL
 * @property string $NOMBRE
 * @property string $FECDESDE
 * @property string $FECHASTA
 * @property string $FECHACRE
 * @property int $TIPO
 * @property int $APLICACION
 * @property int $ALCANCE
 * @property float $CANTDESDE
 * @property float $CANTHASTA
 * @property float $VALORDESDE
 * @property float $VALORHASTA
 * @property float $MODO
 * @property float $PORDES
 * @property string $ITEMREG
 * @property float $CANREG
 * @property string $CLIENTE
 * @property string $ITEM
 * @property string $GRUPOCLI
 * @property string $GRUPOPRO
 * @property string $TIPOAPL
 * @property string $TODOS
 * @property string $cuenta
 * @property string $cuenta0
 * @property string $ptoventa
 */
class ADMCONDICOMER extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCONDICOMER';

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
    protected $fillable = ['NOMBRE', 'FECDESDE', 'FECHASTA', 'FECHACRE', 'TIPO', 'APLICACION', 'ALCANCE', 'CANTDESDE', 'CANTHASTA', 'VALORDESDE', 'VALORHASTA', 'MODO', 'PORDES', 'ITEMREG', 'CANREG', 'CLIENTE', 'ITEM', 'GRUPOCLI', 'GRUPOPRO', 'TIPOAPL', 'TODOS', 'cuenta', 'cuenta0', 'ptoventa'];

}
