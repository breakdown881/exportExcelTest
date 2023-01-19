<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    public const EMPLOYER_TYPE = 'employer';

    /**
     * Scope get type employer
     *
     * @param $query
     * @return void
     */
    public function scopeTypeEmployer($query): void
    {
        $query->where('type', self::EMPLOYER_TYPE);
    }

    /**
     * Lấy tất cả address gắn vào model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function getNameAttribute()
    {
        return $this->display_name;
    }
}