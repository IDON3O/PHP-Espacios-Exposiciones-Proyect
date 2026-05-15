<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'venue_id', 'start_time', 'end_time',
        'status', 'user_name', 'user_email', 'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Reservation $reservation) {
            if (empty($reservation->slug)) {
                $reservation->slug = Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'id_venue');
    }
}
