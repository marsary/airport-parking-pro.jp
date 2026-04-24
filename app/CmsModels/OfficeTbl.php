<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OfficeTbl
 * 
 * @property int $office_id
 * @property string $office_name
 * @property string $office_name_short
 * @property int $office_sort_no
 * @property string|null $office_mail
 * @property string|null $office_full_msg1
 * @property string|null $office_full_msg2
 * @property timestamp $ins_date
 * @property timestamp|null $up_date
 * @property int $office_grg_num
 * @property int $office_rsv_rate
 * @property string $tel
 * @property string $fax
 *
 * @package App\CmsModels
 */
class OfficeTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'office_tbl';
	protected $primaryKey = 'office_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'office_id' => 'int',
		'office_sort_no' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp',
		'office_grg_num' => 'int',
		'office_rsv_rate' => 'int'
	];

	protected $fillable = [
		'office_name',
		'office_name_short',
		'office_sort_no',
		'office_mail',
		'office_full_msg1',
		'office_full_msg2',
		'ins_date',
		'up_date',
		'office_grg_num',
		'office_rsv_rate',
		'tel',
		'fax'
	];
}
