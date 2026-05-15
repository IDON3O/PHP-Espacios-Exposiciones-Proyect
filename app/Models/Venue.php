<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Venue extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_venue';

    protected $fillable = [
        'venue_name', 'slug', 'venue_type', 'venue_description',
        'venue_rules', 'price_per_hour', 'is_active',
        'venue_address', 'venue_max_capacity', 'venue_image',
        'deleted_by_venue',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_per_hour' => 'decimal:2',
    ];

    // Auto-genera slug al crear
    protected static function booted(): void
    {
        static::creating(function (Venue $venue) {
            if (empty($venue->slug)) {
                $venue->slug = Str::slug($venue->venue_name);
            }
        });
    }

    // Model Binding por slug
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'venue_id', 'id_venue');
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'venue_id', 'id_venue');
    }

    public function blockedSlots()
    {
        return $this->hasMany(BlockedSlot::class, 'venue_id', 'id_venue');
    }

    // Relación vieja — la mantenemos para no romper nada
    public function events()
    {
        return $this->hasMany(Event::class, 'fk_venue_event', 'id_venue');
    }
}
