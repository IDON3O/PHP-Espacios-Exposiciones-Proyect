<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = ['venue_id', 'day_of_week', 'start_time', 'end_time'];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'id_venue');
    }
}
