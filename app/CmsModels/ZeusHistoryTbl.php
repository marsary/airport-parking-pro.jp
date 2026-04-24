<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ZeusHistoryTbl
 * 
 * @property int $zh_id
 * @property int $rsv_id
 * @property string $z_order_id
 * @property int $result_ok
 * @property string $send_param
 * @property timestamp $ins_date
 *
 * @package App\CmsModels
 */
class ZeusHistoryTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'zeus_history_tbl';
	protected $primaryKey = 'zh_id';
	public $timestamps = false;

	protected $casts = [
		'rsv_id' => 'int',
		'result_ok' => 'int',
		'ins_date' => 'timestamp'
	];

	protected $fillable = [
		'rsv_id',
		'z_order_id',
		'result_ok',
		'send_param',
		'ins_date'
	];
}
