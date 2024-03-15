<?php

namespace Botble\Promotions\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;
use Carbon\Carbon;

class Promotion extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'promotions';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'text',
        'image',
        'status',
        'type',
        'from',
        'never_expires',
        'to'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

     
    // public function getFromAttribute($value)
    // {
    //     return Carbon::parse($value)->format('Y-m-d');
    // }

    // public function getToAttribute($value)
    // {
    //     return Carbon::parse($value)->format('Y-m-d');
    // }

    public function setFromAttribute($value)
    {
        $this->attributes['from'] = Carbon::parse($value)->format('Y-m-d');
        // dd($this->attributes['from']);
    }

    public function setToAttribute($value)
    {
        $this->attributes['to'] = Carbon::parse($value)->format('Y-m-d');
    }

    protected static function boot()
    {
        parent::boot();
    }
}
