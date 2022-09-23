<?php

namespace Sarga\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the Configuraion is authorized to make this request.
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
        $locale = core()->getRequestedLocaleCode();

        if ($id = request('id')) {
            return [
                $locale . '.name' => 'required',
            ];
        }

        return [
            'name'        => 'required',
            'status' => 'required',
            'position' => 'required',
        ];
    }
}