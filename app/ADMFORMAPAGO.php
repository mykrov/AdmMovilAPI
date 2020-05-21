<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $codigo
 * @property string $nombre
 * @property string $domi
 */
class ADMFORMAPAGO extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMFORMAPAGO';

    /**
     * @var array
     */
    protected $fillable = ['codigo', 'nombre', 'domi'];

}
