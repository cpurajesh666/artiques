<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Notifications\ResetPasswordNotification;
use Botble\Marketplace\Models\Revenue;
use Botble\Marketplace\Models\VendorInfo;
use Botble\Marketplace\Models\Withdrawal;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use RvMedia;
use MacroableModels;
use Illuminate\Support\Str;

/**
 * @mixin Eloquent
 */
class Supplier extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'ec_suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'phone',
        'status',
        'firstname',
        'lastname',
        'notes',
        'address',
        'company_name',
        'website'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status' => CustomerStatusEnum::class,
    ];


    /**
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return RvMedia::getImageUrl($this->avatar, 'thumb');
        }

        try {
            return (new Avatar)->create($this->name)->toBase64();
        } catch (Exception $exception) {
            return RvMedia::getDefaultImage();
        }
    }
    
    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Supplier $customer) {
            
        });
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (class_exists('MacroableModels')) {
            $method = 'get' . Str::studly($key) . 'Attribute';
            if (MacroableModels::modelHasMacro(get_class($this), $method)) {
                return call_user_func([$this, $method]);
            }
        }

        return parent::__get($key);
    }
}
