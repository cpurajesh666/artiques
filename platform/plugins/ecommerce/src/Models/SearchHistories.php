<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * @mixin Eloquent
 */
class SearchHistories extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'search_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
    ];

}
