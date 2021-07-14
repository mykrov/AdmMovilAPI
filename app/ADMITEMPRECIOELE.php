<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $item
 * @property string $frecuencia
 * @property int $tiempo
 * @property float $costo
 * @property float $costor
 * @property float $precio
 * @property float $interes
 * @property string $tieneregalo
 * @property string $observacion
 * @property string $fecha
 * @property string $operador
 * @property string $maquina
 * @property float $preciominimo
 * @property float $preciomatricula
 * @property string $serie
 * @property int $tiempoini
 * @property float $interesf
 * @property string $vcontado
 * @property float $preciotar
 * @property float $preciocre
 */
class ADMITEMPRECIOELE extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMITEMPRECIOELE';

    /**
     * @var array
     */
    protected $fillable = ['tiempo', 'costo', 'costor', 'precio', 'interes', 'tieneregalo', 'observacion', 'fecha', 'operador', 'maquina', 'preciominimo', 'preciomatricula', 'serie', 'tiempoini', 'interesf','vcontado','preciotar','preciocre'];

}
