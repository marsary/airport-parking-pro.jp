<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CmsGroupTbl
 * 
 * @property int $cms_g_id
 * @property int $cms_g_type
 * @property string $cms_g_name
 * @property string $cms_g_name_short
 * @property int $cms_g_sort_no
 * @property timestamp $ins_date
 * @property timestamp $up_date
 *
 * @package App\CmsModels
 */
class CmsGroupTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'cms_group_tbl';
	protected $primaryKey = 'cms_g_id';
	public $timestamps = false;

	protected $casts = [
		'cms_g_type' => 'int',
		'cms_g_sort_no' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp'
	];

	protected $fillable = [
		'cms_g_type',
		'cms_g_name',
		'cms_g_name_short',
		'cms_g_sort_no',
		'ins_date',
		'up_date'
	];
}
