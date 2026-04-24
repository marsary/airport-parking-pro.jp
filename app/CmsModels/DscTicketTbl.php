<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DscTicketTbl
 * 
 * @property int $dt_id
 * @property string $name
 * @property int $price
 * @property int $days
 * @property int $sort
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 * @property string $name_s
 * @property int $dt_for_mem
 *
 * @package App\CmsModels
 */
class DscTicketTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'dsc_ticket_tbl';
	protected $primaryKey = 'dt_id';
	public $timestamps = false;

	protected $casts = [
		'price' => 'int',
		'days' => 'int',
		'sort' => 'int',
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp',
		'dt_for_mem' => 'int'
	];

	protected $fillable = [
		'name',
		'price',
		'days',
		'sort',
		'del_flg',
		'inserted',
		'modified',
		'name_s',
		'dt_for_mem'
	];
}
