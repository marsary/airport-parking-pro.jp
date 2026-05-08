<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RsvAllowTbl
 * 
 * @property int $ra_id
 * @property int $o_id
 * @property int $ag_id1
 * @property int|null $ag_id2
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class RsvAllowTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'rsv_allow_tbl';
	protected $primaryKey = 'ra_id';
	public $timestamps = false;

	protected $casts = [
		'o_id' => 'int',
		'ag_id1' => 'int',
		'ag_id2' => 'int',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'o_id',
		'ag_id1',
		'ag_id2',
		'inserted',
		'modified'
	];
}
