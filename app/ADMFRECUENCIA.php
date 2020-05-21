<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $codigo
 * @property string $nombre
 * @property int $dia
 */
class ADMFRECUENCIA extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMFRECUENCIA';

    /**
     * @var array
     */
    protected $fillable = ['codigo', 'nombre', 'dia'];

}
