<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PriceTbl
 * 
 * @property int $o_id
 * @property int $p_id
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property int $base_price
 * @property int $d1
 * @property int $d2
 * @property int $d3
 * @property int $d4
 * @property int $d5
 * @property int $d6
 * @property int $d7
 * @property int $d8
 * @property int $d9
 * @property int $d10
 * @property int $d11
 * @property int $d12
 * @property int $d13
 * @property int $d14
 * @property int $d15
 * @property int $price_per_day
 * @property int $ml_add
 * @property int $ml_per_yen
 * @property int $pt_add
 * @property int $pt_per_yen
 * @property int $pt_flg
 * @property int $lsize_rate
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 * @property int $ag_id1
 * @property int $ag_id2
 * @property int $no_discount_flg
 * @property int $rev_tax_flg
 *
 * @package App\CmsModels
 */
class PriceTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'price_tbl';
	protected $primaryKey = 'p_id';
	public $timestamps = false;

	protected $casts = [
		'o_id' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'base_price' => 'int',
		'd1' => 'int',
		'd2' => 'int',
		'd3' => 'int',
		'd4' => 'int',
		'd5' => 'int',
		'd6' => 'int',
		'd7' => 'int',
		'd8' => 'int',
		'd9' => 'int',
		'd10' => 'int',
		'd11' => 'int',
		'd12' => 'int',
		'd13' => 'int',
		'd14' => 'int',
		'd15' => 'int',
		'price_per_day' => 'int',
		'ml_add' => 'int',
		'ml_per_yen' => 'int',
		'pt_add' => 'int',
		'pt_per_yen' => 'int',
		'pt_flg' => 'int',
		'lsize_rate' => 'int',
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp',
		'ag_id1' => 'int',
		'ag_id2' => 'int',
		'no_discount_flg' => 'int',
		'rev_tax_flg' => 'int'
	];

	protected $fillable = [
		'o_id',
		'start_date',
		'end_date',
		'base_price',
		'd1',
		'd2',
		'd3',
		'd4',
		'd5',
		'd6',
		'd7',
		'd8',
		'd9',
		'd10',
		'd11',
		'd12',
		'd13',
		'd14',
		'd15',
		'price_per_day',
		'ml_add',
		'ml_per_yen',
		'pt_add',
		'pt_per_yen',
		'pt_flg',
		'lsize_rate',
		'del_flg',
		'inserted',
		'modified',
		'ag_id1',
		'ag_id2',
		'no_discount_flg',
		'rev_tax_flg'
	];
}
