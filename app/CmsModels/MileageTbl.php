<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MileageTbl
 * 
 * @property int $mlg_g_id
 * @property int $mlg_id
 * @property int $mlg_d_id
 * @property int $office_id
 * @property int $mlg_priority
 * @property string $mlg_content1
 * @property string $mlg_content2
 * @property string $mlg_content3
 * @property Carbon $mlg_date_start_pub
 * @property Carbon $mlg_date_end_pub
 * @property int $mlg_i_sort_no
 * @property int $mlg_disp_flg
 * @property int $mlg_bnn_avl_flg
 * @property timestamp $ins_date
 * @property timestamp $up_date
 *
 * @package App\CmsModels
 */
class MileageTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'mileage_tbl';
	protected $primaryKey = 'mlg_id';
	public $timestamps = false;

	protected $casts = [
		'mlg_g_id' => 'int',
		'mlg_d_id' => 'int',
		'office_id' => 'int',
		'mlg_priority' => 'int',
		'mlg_date_start_pub' => 'datetime',
		'mlg_date_end_pub' => 'datetime',
		'mlg_i_sort_no' => 'int',
		'mlg_disp_flg' => 'int',
		'mlg_bnn_avl_flg' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp'
	];

	protected $fillable = [
		'mlg_g_id',
		'mlg_d_id',
		'office_id',
		'mlg_priority',
		'mlg_content1',
		'mlg_content2',
		'mlg_content3',
		'mlg_date_start_pub',
		'mlg_date_end_pub',
		'mlg_i_sort_no',
		'mlg_disp_flg',
		'mlg_bnn_avl_flg',
		'ins_date',
		'up_date'
	];
}
