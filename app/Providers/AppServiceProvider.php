<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Create symbolic link for customer images if directory doesn't exist
        $targetFolder = public_path('customers');
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0755, true);
        }
        
        // Create directory for vehicle images if it doesn't exist
        $vehiclesFolder = public_path('vehicles');
        if (!file_exists($vehiclesFolder)) {
            mkdir($vehiclesFolder, 0755, true);
        }
        
        // Create directory for attendance images if it doesn't exist
        $attendancesFolder = public_path('attendances');
        if (!file_exists($attendancesFolder)) {
            mkdir($attendancesFolder, 0755, true);
        }
        
        // Create directory for about us images if it doesn't exist
        $aboutUsFolder = public_path('about_us');
        if (!file_exists($aboutUsFolder)) {
            mkdir($aboutUsFolder, 0755, true);
        }
    }
}
