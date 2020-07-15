<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $secuencial
 * @property string $tipo
 * @property float $numero
 * @property string $tipopag
 * @property float $monto
 * @property string $banco
 * @property string $cuenta
 * @property string $numchq
 * @property string $fechaven
 * @property string $estchq
 * @property string $docrel
 * @property float $numerorel
 * @property float $noguia
 * @property float $nogui
 * @property float $INDICE
 * @property string $vendedor
 * @property string $intregrado
 */
class ADMDETPAGO extends Model
{
    public  $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETPAGO';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'INDICE';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'float';

    /**
     * @var array
     */
    protected $fillable = ['secuencial', 'tipo', 'numero', 'tipopag', 'monto', 'banco', 'cuenta', 'numchq', 'fechaven', 'estchq', 'docrel', 'numerorel', 'noguia', 'nogui', 'vendedor', 'intregrado'];

}
