<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECUENCIALAJI
 * @property int $BODEGAAJI
 * @property string $TIPOAJI
 * @property float $NUMEROAJI
 * @property string $ESTADO
 * @property float $SECUENCIALFAC
 * @property int $BODEGAFAC
 * @property string $TIPOFAC
 * @property float $NUMEROFAC
 * @property string $ITEM
 * @property string $SERIEFAC
 * @property int $LINEA
 * @property string $NOMBRE
 * @property string $DATOSNOMBRE
 * @property int $CANTIDAD
 * @property string $CHASIS
 */
class ADMDATOSMOTORELE extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDATOSMOTORELE';

    /**
     * @var array
     */
    protected $fillable = ['SECUENCIALAJI', 'BODEGAAJI', 'TIPOAJI', 'NUMEROAJI', 'ESTADO', 'SECUENCIALFAC', 'BODEGAFAC', 'TIPOFAC', 'NUMEROFAC', 'ITEM', 'SERIEFAC', 'LINEA', 'NOMBRE', 'DATOSNOMBRE', 'CANTIDAD', 'CHASIS'];

}
