<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SunInfoTbl
 * 
 * @property int $ne_kanrino
 * @property int $ne_open
 * @property int $ne_nen
 * @property int $ne_gatu
 * @property int $ne_niti
 * @property string|null $ne_title
 * @property string|null $ne_naiyou
 * @property int $ne_cate01
 * @property int $ne_cate02
 * @property int $ne_cate03
 * @property int $ne_cate04
 * @property int $ne_cate05
 * @property int|null $ne_select
 * @property int|null $ne_check
 *
 * @package App\CmsModels
 */
class SunInfoTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'sun_info_tbl';
	protected $primaryKey = 'ne_kanrino';
	public $timestamps = false;

	protected $casts = [
		'ne_open' => 'int',
		'ne_nen' => 'int',
		'ne_gatu' => 'int',
		'ne_niti' => 'int',
		'ne_cate01' => 'int',
		'ne_cate02' => 'int',
		'ne_cate03' => 'int',
		'ne_cate04' => 'int',
		'ne_cate05' => 'int',
		'ne_select' => 'int',
		'ne_check' => 'int'
	];

	protected $fillable = [
		'ne_open',
		'ne_nen',
		'ne_gatu',
		'ne_niti',
		'ne_title',
		'ne_naiyou',
		'ne_cate01',
		'ne_cate02',
		'ne_cate03',
		'ne_cate04',
		'ne_cate05',
		'ne_select',
		'ne_check'
	];
}
