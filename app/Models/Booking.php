<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'customer_id',
        'learner_name',
        'package_id',
        'joining_date',
        'time_slot'
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
        ];
    }

    /**
     * Get the customer that owns the booking.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the package that is booked.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the attendances for the booking.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
    
    /**
     * Get the transactions for the booking.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}