<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DiscountTbl2
 * 
 * @property int $dsc_g_id
 * @property int $dsc_id
 * @property string $name
 * @property int $o_id
 * @property int $dsc_rate
 * @property Carbon $date_start
 * @property Carbon $date_end
 * @property Carbon $date_start_excp
 * @property Carbon $date_end_excp
 * @property int $min_days
 * @property int $active
 * @property int $priority
 * @property int $ag_id1
 * @property int $ag_id2
 * @property int $only_sp_price_period
 * @property int $is_mon
 * @property int $is_tue
 * @property int $is_wed
 * @property int $is_thu
 * @property int $is_fri
 * @property int $is_sat
 * @property int $is_sun
 * @property int $is_holy
 * @property int $buy_wax
 * @property int $buy_insr
 * @property int $is_prepaid
 * @property int $no_mileage
 * @property string $price_note
 * @property string $rsv_note
 * @property string $email_note
 * @property string $trans_note
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class DiscountTbl2 extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'discount_tbl2';
	protected $primaryKey = 'dsc_id';
	public $timestamps = false;

	protected $casts = [
		'dsc_g_id' => 'int',
		'o_id' => 'int',
		'dsc_rate' => 'int',
		'date_start' => 'datetime',
		'date_end' => 'datetime',
		'date_start_excp' => 'datetime',
		'date_end_excp' => 'datetime',
		'min_days' => 'int',
		'active' => 'int',
		'priority' => 'int',
		'ag_id1' => 'int',
		'ag_id2' => 'int',
		'only_sp_price_period' => 'int',
		'is_mon' => 'int',
		'is_tue' => 'int',
		'is_wed' => 'int',
		'is_thu' => 'int',
		'is_fri' => 'int',
		'is_sat' => 'int',
		'is_sun' => 'int',
		'is_holy' => 'int',
		'buy_wax' => 'int',
		'buy_insr' => 'int',
		'is_prepaid' => 'int',
		'no_mileage' => 'int',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'dsc_g_id',
		'name',
		'o_id',
		'dsc_rate',
		'date_start',
		'date_end',
		'date_start_excp',
		'date_end_excp',
		'min_days',
		'active',
		'priority',
		'ag_id1',
		'ag_id2',
		'only_sp_price_period',
		'is_mon',
		'is_tue',
		'is_wed',
		'is_thu',
		'is_fri',
		'is_sat',
		'is_sun',
		'is_holy',
		'buy_wax',
		'buy_insr',
		'is_prepaid',
		'no_mileage',
		'price_note',
		'rsv_note',
		'email_note',
		'trans_note',
		'inserted',
		'modified'
	];
}
