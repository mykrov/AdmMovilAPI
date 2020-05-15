<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $codigo
 * @property string $provincia
 * @property string $nombre
 * @property string $estado
 * @property string $CODIGOSAP
 * @property string $codigo_sap_provincia
 */
class ADMCANTON extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCANTON';

    /**
     * @var array
     */
    protected $fillable = ['nombre', 'estado', 'CODIGOSAP', 'codigo_sap_provincia'];

}
