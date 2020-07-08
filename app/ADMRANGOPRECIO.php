<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $codigorango
 * @property string $codigoitem
 * @property float $minimorango
 * @property float $maximorango
 * @property float $preciorango
 * @property string $fecharango
 * @property string $horarango
 * @property string $maquinarango
 * @property float $precioivarango
 */
class ADMRANGOPRECIO extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMRANGOPRECIO';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'codigorango';

    /**
     * @var array
     */
    protected $fillable = ['codigoitem', 'minimorango', 'maximorango', 'preciorango', 'fecharango', 'horarango', 'maquinarango', 'precioivarango'];

}
