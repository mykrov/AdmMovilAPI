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
 */
class ADMDETPAGOPOS extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETPAGOPOS';

    
    /**
     * @var array
     */
    protected $fillable = ['secuencial', 'tipo', 'numero', 'tipopag', 'monto', 'banco', 'cuenta', 'numchq', 'fechaven', 'estchq', 'docrel', 'numerorel', 'noguia', 'nogui'];

}
