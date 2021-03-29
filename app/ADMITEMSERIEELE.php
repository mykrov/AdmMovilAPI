<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECUENCIAL
 * @property int $BODEGA
 * @property string $TIPO
 * @property float $NUMERO
 * @property string $ITEM
 * @property string $SERIE
 * @property float $SECUENCIALFAC
 * @property string $SERIEFAC
 * @property float $NUMEROFAC
 * @property string $FECHAFAC
 * @property string $VENDIDO
 * @property string $TIPOFAC
 */
class ADMITEMSERIEELE extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMITEMSERIEELE';

    /**
     * @var array
     */
    protected $fillable = ['SECUENCIAL', 'BODEGA', 'TIPO', 'NUMERO', 'ITEM', 'SERIE', 'SECUENCIALFAC', 'SERIEFAC', 'NUMEROFAC', 'FECHAFAC', 'VENDIDO', 'TIPOFAC'];

}
