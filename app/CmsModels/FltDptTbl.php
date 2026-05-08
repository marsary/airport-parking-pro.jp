<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FltDptTbl
 * 
 * @property string $flt_dpt_id
 * @property string $name
 * @property string $name_k
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 * @property int $japan_flg
 *
 * @package App\CmsModels
 */
class FltDptTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'flt_dpt_tbl';
	protected $primaryKey = 'flt_dpt_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp',
		'japan_flg' => 'int'
	];

	protected $fillable = [
		'name',
		'name_k',
		'del_flg',
		'inserted',
		'modified',
		'japan_flg'
	];
}
