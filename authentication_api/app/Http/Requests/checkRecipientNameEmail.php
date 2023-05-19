<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class checkRecipientNameEmail extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function check(array $data): string 
    {
        if (empty($data->data->recipient->name) || isset($data->data->recipient->name) == FALSE ) {
            return 'invalid_recipient';
        }
        if (empty($data->data->recipient->email) || isset($data->data->recipient->email) == FALSE ) {
            return 'invalid_recipient';
        }
        
        return 'condition 1 verified';
    }

}
