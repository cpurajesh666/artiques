<?php

namespace Botble\Promotions\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PromotionRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required',
            'text'         => 'required',
            'type' => 'required|in:daily,permanant',
            'from' => 'required_if:type,permanant',
            // 'image'        => 'required_without:text',
            'status'      => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
