<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class DepartamentoRequest extends BaseRequest
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
            'dep_cen_id' => 'required',
            'dep_prf_diretor' => 'required',
            'dep_nome' => 'required|min:3|max:45'
        ];

        return $rules;
    }
}
