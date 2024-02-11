<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SearchQuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->has('voyageur') === false || $this->has('accompagnants') === false) {
            $inputs = $this->all();

            $prix = intval($this->input('prix_voyage'), 10);
            if ($this->has('nombre_accompagnants')) {
                $numPersonnes = 1 + intval($this->input('nombre_accompagnants'), 10);
                $prix = intval($prix / $numPersonnes, 10);
            }

            // Add fake voyageur if none provided
            if ($this->has('voyageur') === false) {
                $inputs['voyageur'] = ['nom' => 'Doe', 'prenom' => 'John', 'date_naissance' => '1999-09-09', 'prix' => $prix];
            }

            // Add empty accompagnants if none provided
            if ($this->has('accompagnants') === false) {
                $inputs['accompagnants'] = [];
                if ($this->has('nombre_accompagnants')) {
                    for ($i = 0; $i < $this->input('nombre_accompagnants'); $i++) {
                        $inputs['accompagnants'][] = ['nom' => 'Doe', 'prenom' => 'John', 'date_naissance' => '1999-09-09', 'prix' => $prix];
                    }
                }
            }

            $this->replace($inputs);
        }

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
            'prix_voyage' => 'required|integer',
            'date_depart' => 'required|date_format:Y-m-d',
            'date_retour' => 'required|date_format:Y-m-d',
            'code_pays_destination' => 'required|exists:destinations,alpha2',
            'voyageur.nom' => 'sometimes',
            'voyageur.prenom' => 'sometimes',
            'voyageur.date_naissance' => 'sometimes|date_format:Y-m-d',
            'voyageur.email' => 'sometimes|email',
            'accompagnants.*.nom' => 'sometimes|min:1',
            'accompagnants.*.prenom' => 'sometimes|min:1',
            'accompagnants.*.dateNaissance' => 'sometimes|date_format:Y-m-d',
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
