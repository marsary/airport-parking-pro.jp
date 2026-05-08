<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SeasonTbl
 * 
 * @property int $o_id
 * @property Carbon $date
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class SeasonTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'season_tbl';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'o_id' => 'int',
		'date' => 'datetime',
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'del_flg',
		'inserted',
		'modified'
	];
}
