<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutUs extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'about_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'licence_price',
        'about_phone_no_1',
        'about_phone_no_2',
        'address',
        'opening_hours',
        'about_image_1',
        'about_image_2'
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'licence_price' => 'decimal:2',
        ];
    }
    
    /**
     * Get the about_image_1 attribute with full URL.
     *
     * @param  string  $value   
     * @return string|null
     */
    public function getAboutImage1Attribute($value)
    {
        if ($value) {
            return url($value);
        }
        
        return null;
    }
    
    /**
     * Get the about_image_2 attribute with full URL.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getAboutImage2Attribute($value)
    {
        if ($value) {
            return url($value);
        }
        
        return null;
    }
}