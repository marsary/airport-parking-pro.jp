<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyPrice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_id',
        'agency_id',
        'start_date',
        'end_date',
        'base_price',
        'd1',
        'd2',
        'd3',
        'd4',
        'd5',
        'd6',
        'd7',
        'd8',
        'd9',
        'd10',
        'd11',
        'd12',
        'd13',
        'd14',
        'd15',
        'price_per_day',
        'late_fee',
        'lsize_rate',
        'no_discount_flg',
        'memo',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
    * 配列/JSONシリアル化の日付を準備
    *
    * @param  \DateTimeInterface  $date
    * @return string
    */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }


    public function getPriceAt(int $dayNum)
    {
        if(isset($this->{'d' . $dayNum})) {
            return $this->{'d' . $dayNum};
        }
        return $this->price_per_day;
    }
}
