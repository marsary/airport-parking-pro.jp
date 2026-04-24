<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AgencyPriceTbl
 * 
 * @property int $agp_id
 * @property int $ag_id1
 * @property int $ag_id2
 * @property int $o_id
 * @property int $dsc_rate
 * @property int $dsc_rate_season
 * @property int $margin_rate
 * @property int $ml_add
 * @property int $ml_per_yen
 * @property int $pt_add
 * @property int $pt_per_yen
 * @property int $pt_flg
 * @property int $default_flg
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 * @property int $margin_rate_mem
 *
 * @package App\CmsModels
 */
class AgencyPriceTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'agency_price_tbl';
	protected $primaryKey = 'agp_id';
	public $timestamps = false;

	protected $casts = [
		'ag_id1' => 'int',
		'ag_id2' => 'int',
		'o_id' => 'int',
		'dsc_rate' => 'int',
		'dsc_rate_season' => 'int',
		'margin_rate' => 'int',
		'ml_add' => 'int',
		'ml_per_yen' => 'int',
		'pt_add' => 'int',
		'pt_per_yen' => 'int',
		'pt_flg' => 'int',
		'default_flg' => 'int',
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp',
		'margin_rate_mem' => 'int'
	];

	protected $fillable = [
		'ag_id1',
		'ag_id2',
		'o_id',
		'dsc_rate',
		'dsc_rate_season',
		'margin_rate',
		'ml_add',
		'ml_per_yen',
		'pt_add',
		'pt_per_yen',
		'pt_flg',
		'default_flg',
		'del_flg',
		'inserted',
		'modified',
		'margin_rate_mem'
	];
}
