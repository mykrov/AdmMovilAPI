<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $VENDEDOR
 * @property string $ITEM
 * @property string $OPERADOR
 * @property string $FECHAREG
 */
class ADMITEMTOP extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMITEMTOP';

    /**
     * @var array
     */
    protected $fillable = ['OPERADOR', 'FECHAREG'];

}
