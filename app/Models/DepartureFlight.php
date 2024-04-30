<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartureFlight extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'flight_no',
        'name',
        'dep_airport_id',
        'arr_airport_id',
        'airline_id',
        'terminal_id',
        'depart_date',
        'depart_time',
    ];

    public function depAirport()
    {
        return $this->belongsTo(Airport::class, 'dep_airport_id');
    }

    public function arrAirport()
    {
        return $this->belongsTo(Airport::class, 'arr_airport_id');
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }
}
