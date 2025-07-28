<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'car_no',
        'model_no',
        'color',
        'description',
        'image'
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Add any type casting if needed
        ];
    }
    
    /**
     * Get the image attribute with full URL.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getImageAttribute($value)
    {
        if ($value) {
            return url($value);
        }
        
        return null;
    }
    
    /**
     * Get the packages for the vehicle.
     */
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}