<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RsvGoodsTbl
 * 
 * @property int $rsv_goods_id
 * @property int $rsv_id
 * @property int $g_id
 * @property int $price
 * @property int $num
 * @property int $total_price
 *
 * @package App\CmsModels
 */
class RsvGoodsTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'rsv_goods_tbl';
	protected $primaryKey = 'rsv_goods_id';
	public $timestamps = false;

	protected $casts = [
		'rsv_id' => 'int',
		'g_id' => 'int',
		'price' => 'int',
		'num' => 'int',
		'total_price' => 'int'
	];

	protected $fillable = [
		'rsv_id',
		'g_id',
		'price',
		'num',
		'total_price'
	];
}
