<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RsvTbl
 * 
 * @property int $rsv_id1
 * @property int $rsv_id2
 * @property string $rsv_crpt_id
 * @property int $rsv_state
 * @property Carbon $rsv_date
 * @property int $office_id
 * @property string|null $agency_org_id
 * @property int $agency_id
 * @property int $user_id
 * @property int $flt_id
 * @property Carbon $car_load_date
 * @property Carbon $car_unload_date
 * @property int $car_id
 * @property string $car_number
 * @property string $car_color
 * @property int $user_num
 * @property int $park_price
 * @property timestamp $ins_date
 * @property timestamp $up_date
 * @property int $rsv_type
 * @property int $dsc_id
 * @property int $wax_flg
 * @property int $insurance_flg
 * @property int $sync_flg
 * @property int $dsc_rate
 * @property int $key_treat
 * @property int $rsv_id_group
 * @property int $cancel_flg
 * @property Carbon|null $modified_user
 * @property int $car_col_id
 * @property int $lsize_flg
 * @property string $flt_corp_id
 * @property string $flt_code
 * @property string $flt_dpt_id
 * @property time without time zone|null $car_unload_time
 * @property int $dt_id
 * @property int $dt_price
 * @property int $prepaid_state
 * @property Carbon $car_load_date_plan
 * @property time without time zone $car_load_time_plan
 * @property int $get_mileage
 * @property int $roof_type
 * @property int $pickup
 * @property int $is_red_premium
 * @property int $prepaid2_pay
 * @property int $prepaid2_jcb
 * @property int $prepaid2_state
 * @property int $season_flg
 *
 * @package App\CmsModels
 */
class RsvTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'rsv_tbl';
	public $timestamps = false;

	protected $casts = [
		'rsv_id2' => 'int',
		'rsv_state' => 'int',
		'rsv_date' => 'datetime',
		'office_id' => 'int',
		'agency_id' => 'int',
		'user_id' => 'int',
		'flt_id' => 'int',
		'car_load_date' => 'datetime',
		'car_unload_date' => 'datetime',
		'car_id' => 'int',
		'user_num' => 'int',
		'park_price' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp',
		'rsv_type' => 'int',
		'dsc_id' => 'int',
		'wax_flg' => 'int',
		'insurance_flg' => 'int',
		'sync_flg' => 'int',
		'dsc_rate' => 'int',
		'key_treat' => 'int',
		'rsv_id_group' => 'int',
		'cancel_flg' => 'int',
		'modified_user' => 'datetime',
		'car_col_id' => 'int',
		'lsize_flg' => 'int',
		'car_unload_time' => 'time without time zone',
		'dt_id' => 'int',
		'dt_price' => 'int',
		'prepaid_state' => 'int',
		'car_load_date_plan' => 'datetime',
		'car_load_time_plan' => 'time without time zone',
		'get_mileage' => 'int',
		'roof_type' => 'int',
		'pickup' => 'int',
		'is_red_premium' => 'int',
		'prepaid2_pay' => 'int',
		'prepaid2_jcb' => 'int',
		'prepaid2_state' => 'int',
		'season_flg' => 'int'
	];

	protected $fillable = [
		'rsv_crpt_id',
		'rsv_state',
		'rsv_date',
		'office_id',
		'agency_org_id',
		'agency_id',
		'user_id',
		'flt_id',
		'car_load_date',
		'car_unload_date',
		'car_id',
		'car_number',
		'car_color',
		'user_num',
		'park_price',
		'ins_date',
		'up_date',
		'rsv_type',
		'dsc_id',
		'wax_flg',
		'insurance_flg',
		'sync_flg',
		'dsc_rate',
		'key_treat',
		'rsv_id_group',
		'cancel_flg',
		'modified_user',
		'car_col_id',
		'lsize_flg',
		'flt_corp_id',
		'flt_code',
		'flt_dpt_id',
		'car_unload_time',
		'dt_id',
		'dt_price',
		'prepaid_state',
		'car_load_date_plan',
		'car_load_time_plan',
		'get_mileage',
		'roof_type',
		'pickup',
		'is_red_premium',
		'prepaid2_pay',
		'prepaid2_jcb',
		'prepaid2_state',
		'season_flg'
	];
}
