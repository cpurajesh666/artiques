<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SupplierEditRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'firstname'  => 'required|max:60|min:2',
            'lastname'  => 'required|max:60|min:1',
            'email' => 'required|max:60|min:6|email|unique:ec_suppliers,email,' . $this->route('supplier'),
            'address' => 'nullable',
            'website' => 'nullable|url',
            'company_name' => 'required|max:250:min:10'
        ];

        return $rules;
    }
}
