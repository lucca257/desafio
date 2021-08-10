<?php

namespace App\Api\Transferencia\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransferenciaRequest extends FormRequest
{
    /**
     * Disable validator redirect back to use in API
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
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
        return [
            "pagador" => "required|exists:usuarios,id",
            "beneficiario" => "required|exists:usuarios,id",
            "valor" => "required|numeric"
        ];
    }
}
