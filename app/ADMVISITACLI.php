<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $CODVISITA
 * @property string $CLIENTE
 * @property string $FECHAVISITA
 * @property string $VISITADO
 * @property string $LATITUD
 * @property string $LONGITUD
 * @property string $VENDEDOR
 * @property integer $NUMPEDIDO
 */
class ADMVISITACLI extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMVISITACLI';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'CODVISITA';

    /**
     * @var array
     */
    protected $fillable = ['CLIENTE', 'FECHAVISITA', 'VISITADO', 'LATITUD', 'LONGITUD', 'VENDEDOR', 'NUMPEDIDO'];

}
