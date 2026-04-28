<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsTbl
 *
 * @property int $o_id
 * @property int $g_id
 * @property int $g_id_old
 * @property string $name
 * @property string $name2
 * @property int $o_belong
 * @property int $price
 * @property int $tax_type
 * @property int $wax_flg
 * @property int $one_day_flg
 * @property int $insr_flg
 * @property int $sales_type
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property int $ml_add
 * @property int $ml_per_yen
 * @property int $pt_add
 * @property int $pt_per_yen
 * @property int $pt_flg
 * @property int $lsize_rate
 * @property int $for_rsv_server
 * @property int $sort
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class GoodsTbl extends Model
{
    use HasFactory;

	protected $connection = 'pgsql';
	protected $table = 'goods_tbl';
	protected $primaryKey = 'g_id';
	public $timestamps = false;

	protected $casts = [
		'o_id' => 'int',
		'g_id_old' => 'int',
		'o_belong' => 'int',
		'price' => 'int',
		'tax_type' => 'int',
		'wax_flg' => 'int',
		'one_day_flg' => 'int',
		'insr_flg' => 'int',
		'sales_type' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'ml_add' => 'int',
		'ml_per_yen' => 'int',
		'pt_add' => 'int',
		'pt_per_yen' => 'int',
		'pt_flg' => 'int',
		'lsize_rate' => 'int',
		'for_rsv_server' => 'int',
		'sort' => 'int',
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'o_id',
        'g_id',
		'g_id_old',
		'name',
		'name2',
		'o_belong',
		'price',
		'tax_type',
		'wax_flg',
		'one_day_flg',
		'insr_flg',
		'sales_type',
		'start_date',
		'end_date',
		'ml_add',
		'ml_per_yen',
		'pt_add',
		'pt_per_yen',
		'pt_flg',
		'lsize_rate',
		'for_rsv_server',
		'sort',
		'del_flg',
		'inserted',
		'modified'
	];
}
