<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TaxTbl
 * 
 * @property int $tax_id
 * @property int $tax_rate
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class TaxTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'tax_tbl';
	protected $primaryKey = 'tax_id';
	public $timestamps = false;

	protected $casts = [
		'tax_rate' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'tax_rate',
		'start_date',
		'end_date',
		'del_flg',
		'inserted',
		'modified'
	];
}
