<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FltCorpTbl
 * 
 * @property string $flt_corp_id
 * @property string $name
 * @property string $name_k
 * @property int $japan_flg
 * @property string $memo
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class FltCorpTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'flt_corp_tbl';
	protected $primaryKey = 'flt_corp_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'japan_flg' => 'int',
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'name',
		'name_k',
		'japan_flg',
		'memo',
		'del_flg',
		'inserted',
		'modified'
	];
}
