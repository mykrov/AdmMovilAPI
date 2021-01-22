<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreaCliente extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'RAZONSOCIAL' => 'require|max:60',
            'NEGOCIO' => 'require',
            'DIRECCION' => 'require',
            'TELEFONOS' => 'require',
            'FAX' => 'require',
            'TIPODOC' => 'require',
            'RUC' => 'require',
            'EMAIL' => 'require',
            'REFERENCIA' => 'require',
            'OBSERVACION' => 'require',
            'TIPONEGO' => 'require',
            'TIPO' => 'require',
            'grupocliente' => 'require',
            'PROVINCIA' => 'require',
            'CANTON' => 'require',
            'PARROQUIA' => 'require',
            'SECTOR' => 'require',
            'RUTA' => 'require',
            'CODFRE' => 'require',
            'FECHAING' => 'require',
            'DIASCREDIT' => 'require',
            'TDCREDITO' => 'require',
            'VENDEDOR' => 'require',
            'ZONA' => 'require'
        ];
    }
}
