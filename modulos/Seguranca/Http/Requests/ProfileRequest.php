<?php

namespace Modulos\Seguranca\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class ProfileRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'pes_nome' => 'required|min:6',
            'pes_email' => 'required|email',
            'pes_telefone' => 'required|min:8',
            'pes_sexo' => 'required',
            'pes_nascimento' => 'required',
            'pes_estado_civil' => 'required',
            'pes_mae' => 'required|min:6',
            'pes_pai' => 'min:6',
            'pes_naturalidade' => 'required|min:3',
            'pes_nacionalidade' => 'required|min:3',
            'pes_raca' => 'required',
            'pes_necessidade_especial' => 'required'
        ];

        return $rules;
    }
}
