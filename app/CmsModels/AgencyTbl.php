<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AgencyTbl
 * 
 * @property int $agency_id
 * @property int $agency_sun_id
 * @property string $agency_name
 * @property string $agency_name_login
 * @property string $agency_pass
 * @property string $agency_logo
 * @property string $agency_logo_text
 * @property string $agency_email
 * @property string $agency_url
 * @property string $mileage_name
 * @property string $mileage_remarks
 * @property string $agency_org_id_name
 * @property int $hp_template_id
 * @property int $state_flg
 * @property timestamp $ins_date
 * @property timestamp $up_date
 * @property int $mileage_maxlen
 * @property int $nda_flg
 * @property int $uname_roman_flg
 * @property string $agency_name_disp
 * @property int $cmp_g_id
 * @property int $dsc_g_id
 * @property int $mlg_g_id
 * @property int $cpn_g_id
 * @property int $bnn_g_id
 * @property Carbon $agency_lastlogin
 * @property int $prepaid_flg
 * @property int $ag_id1
 * @property int $ag_id2
 * @property Carbon $loadable_date_start
 * @property Carbon $loadable_date_end
 * @property int $use_ago_tbl
 * @property string $rsv_note
 * @property string $email_note
 * @property string $trans_note
 *
 * @package App\CmsModels
 */
class AgencyTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'agency_tbl';
	protected $primaryKey = 'agency_id';
	public $timestamps = false;

	protected $casts = [
		'agency_sun_id' => 'int',
		'hp_template_id' => 'int',
		'state_flg' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp',
		'mileage_maxlen' => 'int',
		'nda_flg' => 'int',
		'uname_roman_flg' => 'int',
		'cmp_g_id' => 'int',
		'dsc_g_id' => 'int',
		'mlg_g_id' => 'int',
		'cpn_g_id' => 'int',
		'bnn_g_id' => 'int',
		'agency_lastlogin' => 'datetime',
		'prepaid_flg' => 'int',
		'ag_id1' => 'int',
		'ag_id2' => 'int',
		'loadable_date_start' => 'datetime',
		'loadable_date_end' => 'datetime',
		'use_ago_tbl' => 'int'
	];

	protected $fillable = [
		'agency_sun_id',
		'agency_name',
		'agency_name_login',
		'agency_pass',
		'agency_logo',
		'agency_logo_text',
		'agency_email',
		'agency_url',
		'mileage_name',
		'mileage_remarks',
		'agency_org_id_name',
		'hp_template_id',
		'state_flg',
		'ins_date',
		'up_date',
		'mileage_maxlen',
		'nda_flg',
		'uname_roman_flg',
		'agency_name_disp',
		'cmp_g_id',
		'dsc_g_id',
		'mlg_g_id',
		'cpn_g_id',
		'bnn_g_id',
		'agency_lastlogin',
		'prepaid_flg',
		'ag_id1',
		'ag_id2',
		'loadable_date_start',
		'loadable_date_end',
		'use_ago_tbl',
		'rsv_note',
		'email_note',
		'trans_note'
	];
}
