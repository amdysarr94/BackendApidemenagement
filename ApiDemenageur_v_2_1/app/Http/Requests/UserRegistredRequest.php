<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UserRegistredRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => ['sometimes', 'regex:/^\+221(77|78|76|70)\d{7}$/', 'unique:users'],
            'role' => ['required'], 
            // 'exists:roles,nom_role' dans le tableau après | 'regex:/^(Client|Demenageur)$/i'
            'localite' => 'required|string|max:255',
            'photo_profile' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string|min:5|max:10',
        ];
    }
    public function messages()
    {
        return[
            'name.required' => 'Veuillez saisir  votre nom',
            'name.string' => 'Format de nom invalide',
            'name.max' => 'Le nom saisie est trop long',

            'email.required' => "L'adresse email est obligatoire",
            'email.email'=>'Adresse mail invalide',
            'email.unique' => 'Cet adresse mail existe déjà',

            'telephone.regex' => 'Veuillez saisir un numéro de téléphone correcte',
            'telephone.unique'=> 'Ce numéro de téléphone existe déjà',

            'role.required' => 'Veuillez choisir un role',
            
            'localite.required' => 'Veuillez saisir votre localité',

            'photo_profile.mimes' => 'Desolé! la photo est au mauvais format',
            
            'password.required'  => 'Le mot de passe est requis',
            'password.string'  => 'Format de mot de passe incorrecte',
            'password.min'=>'Le mot de passe doit contenir plus de 5 caractères',
            'password.max'=>'Le mot de passe ne doit pas dépasser plus de 10 caractères'
            
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
