<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CmsItemTbl
 * 
 * @property int $cms_g_id
 * @property int $cms_i_id
 * @property string $cms_i_title
 * @property string $cms_i_content
 * @property string $cms_i_icon
 * @property int $cms_i_sort_no
 * @property Carbon $cms_i_date_start
 * @property Carbon $cms_i_date_end
 * @property int $cms_i_disp_flg
 * @property timestamp $ins_date
 * @property timestamp $up_date
 *
 * @package App\CmsModels
 */
class CmsItemTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'cms_item_tbl';
	protected $primaryKey = 'cms_i_id';
	public $timestamps = false;

	protected $casts = [
		'cms_g_id' => 'int',
		'cms_i_sort_no' => 'int',
		'cms_i_date_start' => 'datetime',
		'cms_i_date_end' => 'datetime',
		'cms_i_disp_flg' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp'
	];

	protected $fillable = [
		'cms_g_id',
		'cms_i_title',
		'cms_i_content',
		'cms_i_icon',
		'cms_i_sort_no',
		'cms_i_date_start',
		'cms_i_date_end',
		'cms_i_disp_flg',
		'ins_date',
		'up_date'
	];
}
