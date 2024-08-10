<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArrivalFlight extends Model
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
        'arrive_date',
        'arrive_time',
        'is_delayed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'arrive_date' => 'date',
        ];
    }

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

    public function airportTerminal()
    {
        return $this->belongsTo(AirportTerminal::class, 'terminal_id');
    }
}
