<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SupplierCreateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname'                  => 'required|max:60|min:2',
            'lastname'                  => 'required|max:60|min:1',
            'email'                 => 'required|max:60|min:6|email|unique:ec_suppliers',
            'address' => 'nullable',
            'website' => 'nullable|url',
            'company_name' => 'required|max:250:min:10'
        ];
    }
}
