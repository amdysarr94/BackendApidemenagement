<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InformationsSuppUpdateRequest extends FormRequest
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
            'presentation' => 'required|string|max:255', 
            'NINEA' => 'sometimes|string|max:9',
            'nom_entreprise' => 'required|string|max:255',
            'forme_juridique' =>'sometimes|string|max:255',
            'annee_creation'=> 'required|string|max:255',
            'user_id' => 'required|unique:informations_supps',
        ];
    }
    public function messages(){
        return[
            'presentation.required'=>"La présentation de l'entreprise doit être fourni",
            'presentation.max'=>"La présentation de l'entreprise ne doit pas dépassé 255 caractères",
            'presentation.string'=>"La présentation de l'entreprise doit être une chaîne de caractères",
            
            'NINEA.string' => 'Le NINEA doit être une suite de caractères alphanumériques',
            'NINEA.max' => 'Le NINEA est invalide !',

            'nom_entreprise.required'=>"Le nom de l'entreprise doit être fourni",
            'nom_entreprise.max'=>"Le nom de l'entreprise ne doit pas dépassé 255 caractères",
            'nom_entreprise.string'=>"Le nom de l'entreprise doit être une chaîne de caractères",

            'forme_juridique.string'=>"La forme juridique fourni est invalide !!!",
            'forme_juridique.max'=>"Nombre de caractères trop élevés",
            
            'annee_creation.required'=>"L'année de création de l'entreprise doit être fourni",
            'annee_creation.string'=>"Type des données invalide !!!",
            'annee_creation.max'=>"Le champs année de création renseigné contient trop de caractères",

            'user_id.required' => "L'utilisateur doit être renseigné",
            'user_id.unique' => "Cette entreprise a déjà renseignée les informations supplémentaires le concernant",
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
