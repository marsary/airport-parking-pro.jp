<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BannerTbl
 * 
 * @property int $bnn_g_id
 * @property int $bnn_id
 * @property string $bnn_name
 * @property string $bnn_url
 * @property string $bnn_img
 * @property int $bnn_sort_no
 * @property int $bnn_avl_flg
 * @property timestamp $ins_date
 * @property timestamp $up_date
 *
 * @package App\CmsModels
 */
class BannerTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'banner_tbl';
	protected $primaryKey = 'bnn_id';
	public $timestamps = false;

	protected $casts = [
		'bnn_g_id' => 'int',
		'bnn_sort_no' => 'int',
		'bnn_avl_flg' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp'
	];

	protected $fillable = [
		'bnn_g_id',
		'bnn_name',
		'bnn_url',
		'bnn_img',
		'bnn_sort_no',
		'bnn_avl_flg',
		'ins_date',
		'up_date'
	];
}
