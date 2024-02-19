<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DemandeDevisUpdateRequest extends FormRequest
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
            'nom_entreprise' => 'required|string|max:255', 
            // 'nom_client' => 'required|string|max:255',
            'adresse_actuelle' => 'required|string|max:300',
            'nouvelle_adresse' => 'required|string|max:300',
            'informations_bagages'=> 'required|string|max:900',
            'date_demenagement' => 'required|date'
        ];
    }
    public function messages(){
        return[
            'nom_entreprise.required'=>"Le nom de l'entreprise doit être fourni",
            'nom_entreprise.max'=>"Le nom de l'entreprise ne doit pas dépassé 255 caractères",
            'nom_entreprise.string'=>"Le nom de l'entreprise doit être une chaîne de caractères",

            'nom_client.required'=>"Le nom du client doit être fourni",
            'nom_client.max'=>"Le nom du client ne doit pas dépassé 255 caractères",
            'nom_client.string'=>"Le nom du client doit être une chaîne de caractères",

            'adresse_actuelle.required'=>"L'adresse actuelle du client doit être fourni",
            'adresse_actuelle.max'=>"L'adresse actuelle du client ne doit pas dépassé 300 caractères",
            'adresse_actuelle.string'=>"L'adresse actuelle du client doit être une chaîne de caractères",

            'nouvelle_adresse.required'=>"La nouvelle adresse du client doit être fourni",
            'nouvelle_adresse.max'=>"La nouvelle adresse du client ne doit pas dépassé 300 caractères",
            'nouvelle_adresse.string'=>"La nouvelle adresse du client doit être une chaîne de caractères",

            'informations_bagages.required'=>"Les informations sur les bagages du client doit être fourni",
            'informations_bagages.max'=>"Les informations sur les bagages du client ne doit pas dépassé 900 caractères",
            'informations_bagages.string'=>"Les informations sur les bagages du client doit être une chaîne de caractères",
            
            'date_demenagement.required' =>"La date du déménagement doit être renseignée",
            'date_demenagement.date' =>"format de date invalide",
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
