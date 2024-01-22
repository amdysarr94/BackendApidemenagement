<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticleEditRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string|max:900',
           
        ];
    }
    public function messages(){
        return[
            'titre.required'=>'Le titre doit être fourni',
            'titre.max'=>'Le titre ne doit pas dépassé 255 caractères',
            'titre.string'=>'Le titre doit être une chaîne de caractères',
            'contenu.required'=>'Le contenu doit être fourni',
            'contenu.max'=>'Le contenu ne doit pas dépassé 900 caractères',
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
