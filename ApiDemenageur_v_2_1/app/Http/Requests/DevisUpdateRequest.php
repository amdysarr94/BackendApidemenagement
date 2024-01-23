<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DevisUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prix_total' => 'required|integer', 
            'description'=> 'required|string|max:900',
        ];
    }
    public function messages(){
        return[
            'prix_total.required'=>"Le prix total doit être fourni",
            'prix_total.integer'=>"Le prix total  doit être un entier",

            'description.required'=>"La description du devis du client doit être fourni",
            'description.max'=>"La description du devis du client ne doit pas dépassé 300 caractères",
            'description.string'=>"La description du devis du client doit être une chaîne de caractères",
        ];
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'succes' => false,
            'status_code' => 422,
            'error' => true,
            'message' => 'Erreur de validation',
            'errorsList' => $validator->errors()
        ]));
    }
}
