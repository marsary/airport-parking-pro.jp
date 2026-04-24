<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AgencyOfficeTbl
 * 
 * @property int $ago_id
 * @property int $agency_id
 * @property int $o_id
 * @property timestamp $inserted
 *
 * @package App\CmsModels
 */
class AgencyOfficeTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'agency_office_tbl';
	protected $primaryKey = 'ago_id';
	public $timestamps = false;

	protected $casts = [
		'agency_id' => 'int',
		'o_id' => 'int',
		'inserted' => 'timestamp'
	];

	protected $fillable = [
		'agency_id',
		'o_id',
		'inserted'
	];
}
