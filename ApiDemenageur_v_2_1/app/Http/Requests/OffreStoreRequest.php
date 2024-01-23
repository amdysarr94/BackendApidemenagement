<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OffreStoreRequest extends FormRequest
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
            'nom_offre' => 'required|string|max:255', 
            'description_offre' => 'required|string|max:900',
            'prix_offre' => 'required|integer',
            
        ];
    }
    public function messages(){
        return[
            'nom_offre.required'=>"Le nom de l'offre doit être fourni",
            'nom_offre.max'=>"Le nom de l'offre ne doit pas dépassé 255 caractères",
            'nom_offre.string'=>"Le nom de l'offre doit être une chaîne de caractères",
            'description_offre.required'=>"La description de l'offre doit être fourni",
            'description_offre.max'=>"La description de l'offre ne doit pas dépassé 900 caractères",
            'description_offre.string'=>"La description de l'offre doit être une chaîne de caractères",
            'prix_offre.required'=>"Le prix de l'offre doit être fourni",
            'prix_offre.integer'=>"Le prix de l'offre doit être un entier",
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
