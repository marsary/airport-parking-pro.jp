<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CmsItemOfficeRelTbl
 * 
 * @property int $cms_i_id
 * @property int $office_id
 * @property timestamp $ins_date
 *
 * @package App\CmsModels
 */
class CmsItemOfficeRelTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'cms_item_office_rel_tbl';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'cms_i_id' => 'int',
		'office_id' => 'int',
		'ins_date' => 'timestamp'
	];

	protected $fillable = [
		'cms_i_id',
		'office_id',
		'ins_date'
	];
}
