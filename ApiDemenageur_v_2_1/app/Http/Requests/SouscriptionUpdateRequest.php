<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SouscriptionUpdateRequest extends FormRequest
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
            
            'adresse_actuelle' => 'required|string|max:300',
            'nouvelle_adresse' => 'required|string|max:300',
            'date_demenagement' => 'required|date'
        ];
    }
    public function messages(){
        return[
           

            'adresse_actuelle.required'=>"L'adresse actuelle du client doit être fourni",
            'adresse_actuelle.max'=>"L'adresse actuelle du client ne doit pas dépassé 300 caractères",
            'adresse_actuelle.string'=>"L'adresse actuelle du client doit être une chaîne de caractères",

            'nouvelle_adresse.required'=>"La nouvelle adresse du client doit être fourni",
            'nouvelle_adresse.max'=>"La nouvelle adresse du client ne doit pas dépassé 300 caractères",
            'nouvelle_adresse.string'=>"La nouvelle adresse du client doit être une chaîne de caractères",

            'date_demenagement.required' =>"La date du déménagement doit être renseignée",
            'date_demenagement.date' =>"Format de date invalide ! ",
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
