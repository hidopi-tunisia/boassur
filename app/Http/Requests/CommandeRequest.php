<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CommandeRequest extends FormRequest
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
        return [
            'site_id' => 'required|exists:sites,id',
            'assurance.id' => 'required',
            'assurance.prix' => 'required|integer',
            'prix_voyage' => 'required|integer',
            'date_depart' => 'required|date_format:Y-m-d',
            'date_retour' => 'required|date_format:Y-m-d',
            // 'code_pays_depart' => 'required|exists:destinations,alpha2',
            'code_pays_destination' => 'required|exists:destinations,alpha2',
            'voyageur.numero' => [
                Rule::requiredIf($this->input('site_id') === 2),
                'regex:/^(CDE|HOT|VOY|VHF|VAF|FAM)\-?\d{14}$/i',
            ],
            'voyageur.civilite' => 'required|in:1,2',
            'voyageur.nom' => 'required',
            'voyageur.prenom' => 'required',
            'voyageur.email' => 'required|email|confirmed',
            'voyageur.date_naissance' => 'required|date_format:Y-m-d',
            'voyageur.telephone' => 'required|min:10',
            'voyageur.cp' => 'required',
            'voyageur.ville' => 'required',
            'voyageur.adresse' => 'required',
            'voyageur.pays' => 'required',
            'accompagnants.*.nom' => 'sometimes|min:1',
            'accompagnants.*.prenom' => 'sometimes|min:1',
            'accompagnants.*.date_naissance' => 'sometimes|date_format:Y-m-d',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['success' => false, 'errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
