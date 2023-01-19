<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Address extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'addresses';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'addressable_type',
        'addressable_id',
        'display_name',
        'street',
        'ward_id',
        'district_id',
        'province_id',
    ];

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     * @author Tu Tran
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo('addressable', 'addressable_type', 'addressable_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy tỉnh/thành phố.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Tu Tran
     */
    public function province()
    {
        return $this->belongsTo(Area::class, 'province_id', 'code')
            ->withDefault([
                'name' => null,
                'path_with_type' => null,
            ]);
    }

    /**
     * Lấy quận/huyện.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Tu Tran
     */
    public function district()
    {
        return $this->belongsTo(Area::class, 'district_id', 'code')
            ->withDefault([
                'name' => null,
                'path_with_type' => null,
            ]);
    }

    /**
     * Lấy phường/xã.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Tu Tran
     */
    public function ward()
    {
        return $this->belongsTo(Area::class, 'ward_id', 'code')
            ->withDefault([
                'name' => null,
                'path_with_type' => null,
            ]);
    }

    /**
     * Lấy địa chỉ đầy đủ.
     *
     * @return string
     *
     */
    public function getFullAddressAttribute()
    {
        if ($this->province->code == '9999') {
            return trim($this->street);
        }

        return trim(trim(
            $this->street . ', ' .
                ($this->ward->path_with_type ?:
                    $this->district->path_with_type ?:
                    $this->province->name_with_type)
        ), ',');
    }
}