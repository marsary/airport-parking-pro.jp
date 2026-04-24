<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GarageRemainTbl
 * 
 * @property int $gr_id
 * @property Carbon $park_date
 * @property int $remain_num
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class GarageRemainTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'garage_remain_tbl';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'gr_id' => 'int',
		'park_date' => 'datetime',
		'remain_num' => 'int',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'remain_num',
		'inserted',
		'modified'
	];
}
