<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedSlot extends Model
{
    use HasFactory;

    protected $fillable = ['venue_id', 'start_time', 'end_time', 'reason'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'id_venue');
    }
}
