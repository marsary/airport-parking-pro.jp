<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminTbl
 * 
 * @property int $admin_id
 * @property string $admin_login_id
 * @property string $admin_pw
 * @property string $admin_mail
 * @property int $admin_type
 * @property timestamp $ins_date
 * @property timestamp $up_date
 *
 * @package App\CmsModels
 */
class AdminTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'admin_tbl';
	protected $primaryKey = 'admin_id';
	public $timestamps = false;

	protected $casts = [
		'admin_type' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp'
	];

	protected $fillable = [
		'admin_login_id',
		'admin_pw',
		'admin_mail',
		'admin_type',
		'ins_date',
		'up_date'
	];
}
