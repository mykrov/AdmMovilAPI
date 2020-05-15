<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $codigo
 * @property string $provincia
 * @property string $canton
 * @property string $nombre
 * @property string $estado
 * @property string $pdomi
 * @property string $codigosap_canton
 * @property string $codigosap_parroquia
 * @property string $codigosap_provincia
 */
class ADMPARROQUIA extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMPARROQUIA';

    /**
     * @var array
     */
    protected $fillable = ['nombre', 'estado', 'pdomi', 'codigosap_canton', 'codigosap_parroquia', 'codigosap_provincia'];

}
