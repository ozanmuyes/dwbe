<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;

/**
 * @property int id
 * @property string first_name
 * @property string last_name
 * @property string username
 * @property string email
 * @property string password_set_token
 * @property string role
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 * @property string verification_token
 * @property \Carbon\Carbon verified_at
 * @property bool is_enabled
 * @property \Carbon\Carbon deleted_at
 */
class User extends Model implements AuthorizableContract
{
    use Authorizable, SoftDeletes;

    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password_set_token',
        'password',
        'verification_token',
        'verified_at',
        'is_enabled',
        'deleted_at',
        //
    ];

    protected $dates = [
        'verified_at',
        'deleted_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('verified', function (Builder $builder) {
            $builder->where('verified_at', '<>', null);
        });

        static::addGlobalScope('enabled', function (Builder $builder) {
            $builder->where('is_enabled', '=', true);
        });

        //
    }

    public function setPasswordAttribute(string $plainPassword)
    {
        $this->attributes['password'] = Hash::make($plainPassword);
    }
}
