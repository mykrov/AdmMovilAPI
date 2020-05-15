<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $codigo
 * @property string $nombre
 * @property string $estado
 * @property float $PORCOMIVEN
 * @property float $PORCOMICOB
 * @property int $DIASPLAZOMAX
 * @property int $ORDEN
 */
class ADMTIPOCLIENTE extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMTIPOCLIENTE';

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
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['nombre', 'estado', 'PORCOMIVEN', 'PORCOMICOB', 'DIASPLAZOMAX', 'ORDEN'];

}
