<?php

namespace Botble\Promotions\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;

class PromotionSchedule extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'promotion_schedule';

    /**
     * @var array
     */
    protected $fillable = [
        'date',
        'promotion_id'
    ];

    public function promotion() {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
    }
}
