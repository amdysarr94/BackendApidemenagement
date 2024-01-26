<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UserUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'telephone' => ['sometimes', 'regex:/^\+221(77|78|76|70)\d{7}$/'],
            // 'role' => ['required'], 
            // 'exists:roles,nom_role' dans le tableau après | 'regex:/^(Client|Demenageur)$/i'
            'localite' => 'required|string|max:255',
            'photo_profile' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string|min:5|max:10',
        ];
    }
    public function messages()
    {
        return[
            'name.required' => 'Desolé! Veuillez choisir une image svp',

            'email.required' => 'Desolé! le champ email est Obligatoire',
            
            'telephone.regex' => 'Desolé! veuillez saisir un numéro de téléphone correcte',

            'role.required' => 'Desolé! veuillez choisir un status svp',

            'localite.required' => 'Desolé! veuillez choisir une categorie svp',

            'photo_profile.mimes' => 'Desolé! la photo est au mauvais format',
            
            'password.required'  => 'Désolé ! Le mot de passe est requis'
            
        ];
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success'=>false,
            'error'=>true,
            'message'=>'Erreur de validation',
            'errorsList'=>$validator->errors()
    
        ]));
    }
}
