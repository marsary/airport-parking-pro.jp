<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CouponTbl
 * 
 * @property int $cpn_g_id
 * @property int $cpn_id
 * @property int $cpn_d_id
 * @property Carbon $cpn_date_start
 * @property Carbon $cpn_date_end
 * @property Carbon $cpn_date_start_excp
 * @property Carbon $cpn_date_end_excp
 * @property Carbon $cpn_date_start_pub
 * @property Carbon $cpn_date_end_pub
 * @property int $cpn_sort_no
 * @property int $cpn_avl_flg
 * @property timestamp $ins_date
 * @property timestamp $up_date
 * @property int $cpn_dsp_flg
 *
 * @package App\CmsModels
 */
class CouponTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'coupon_tbl';
	protected $primaryKey = 'cpn_id';
	public $timestamps = false;

	protected $casts = [
		'cpn_g_id' => 'int',
		'cpn_d_id' => 'int',
		'cpn_date_start' => 'datetime',
		'cpn_date_end' => 'datetime',
		'cpn_date_start_excp' => 'datetime',
		'cpn_date_end_excp' => 'datetime',
		'cpn_date_start_pub' => 'datetime',
		'cpn_date_end_pub' => 'datetime',
		'cpn_sort_no' => 'int',
		'cpn_avl_flg' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp',
		'cpn_dsp_flg' => 'int'
	];

	protected $fillable = [
		'cpn_g_id',
		'cpn_d_id',
		'cpn_date_start',
		'cpn_date_end',
		'cpn_date_start_excp',
		'cpn_date_end_excp',
		'cpn_date_start_pub',
		'cpn_date_end_pub',
		'cpn_sort_no',
		'cpn_avl_flg',
		'ins_date',
		'up_date',
		'cpn_dsp_flg'
	];
}
