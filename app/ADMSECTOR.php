<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $codigo
 * @property string $provincia
 * @property string $canton
 * @property string $parroquia
 * @property string $nombre
 * @property string $estado
 * @property float $indice
 * @property string $cdomi
 */
class ADMSECTOR extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMSECTOR';

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
    protected $fillable = ['provincia', 'canton', 'parroquia', 'nombre', 'estado', 'indice', 'cdomi'];

}
