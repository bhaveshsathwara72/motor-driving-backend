<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Attendance;
use App\Models\AboutUs;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create SVG images for customers
        $this->createCustomerImages();
        
        // Create SVG images for vehicles
        $this->createVehicleImages();
        
        // Create SVG images for attendances
        $this->createAttendanceImages();
        
        // Create SVG images for about_us
        $this->createAboutUsImages();
    }
    
    /**
     * Create SVG images for customers and update database records
     */
    private function createCustomerImages(): void
    {
        $customers = Customer::all();
        
        foreach ($customers as $index => $customer) {
            $imageName = 'customer_' . ($index + 1) . '.svg';
            $imagePath = 'customers/' . $imageName;
            $fullPath = public_path($imagePath);
            
            // Create SVG content
            $svgContent = $this->generatePersonSvg("Customer " . ($index + 1), $index);
            
            // Save SVG file
            File::put($fullPath, $svgContent);
            
            // Update customer record
            $customer->image = $imagePath;
            $customer->save();
        }
    }
    
    /**
     * Create SVG images for vehicles and update database records
     */
    private function createVehicleImages(): void
    {
        $vehicles = Vehicle::all();
        
        foreach ($vehicles as $index => $vehicle) {
            $imageName = 'vehicle_' . ($index + 1) . '.svg';
            $imagePath = 'vehicles/' . $imageName;
            $fullPath = public_path($imagePath);
            
            // Create SVG content
            $svgContent = $this->generateVehicleSvg("Vehicle " . ($index + 1), $index);
            
            // Save SVG file
            File::put($fullPath, $svgContent);
            
            // Update vehicle record
            $vehicle->image = $imagePath;
            $vehicle->save();
        }
    }
    
    /**
     * Create SVG images for attendances and update database records
     */
    private function createAttendanceImages(): void
    {
        $attendances = Attendance::all();
        
        foreach ($attendances as $index => $attendance) {
            $imageName = 'attendance_' . ($index + 1) . '.svg';
            $imagePath = 'attendances/' . $imageName;
            $fullPath = public_path($imagePath);
            
            // Create SVG content
            $svgContent = $this->generateAttendanceSvg("Attendance " . ($index + 1), $index);
            
            // Save SVG file
            File::put($fullPath, $svgContent);
            
            // Update attendance record
            $attendance->image = $imagePath;
            $attendance->save();
        }
    }
    
    /**
     * Create SVG images for about_us and update database records
     */
    private function createAboutUsImages(): void
    {
        $aboutUs = AboutUs::all();
        
        foreach ($aboutUs as $index => $about) {
            // Create first image
            $imageName1 = 'about_image_1_' . ($index + 1) . '.svg';
            $imagePath1 = 'about_us/' . $imageName1;
            $fullPath1 = public_path($imagePath1);
            
            // Create SVG content
            $svgContent1 = $this->generateAboutUsSvg("About Us 1 " . ($index + 1), $index);
            
            // Save SVG file
            File::put($fullPath1, $svgContent1);
            
            // Create second image
            $imageName2 = 'about_image_2_' . ($index + 1) . '.svg';
            $imagePath2 = 'about_us/' . $imageName2;
            $fullPath2 = public_path($imagePath2);
            
            // Create SVG content
            $svgContent2 = $this->generateAboutUsSvg("About Us 2 " . ($index + 1), $index + 100);
            
            // Save SVG file
            File::put($fullPath2, $svgContent2);
            
            // Update about_us record
            $about->about_image_1 = $imagePath1;
            $about->about_image_2 = $imagePath2;
            $about->save();
        }
    }
    
    /**
     * Generate SVG for person/customer
     */
    private function generatePersonSvg(string $label, int $seed): string
    {
        // Use seed to generate consistent colors
        $colors = $this->getColorsFromSeed($seed);
        
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
  <rect width="200" height="200" fill="{$colors['bg']}" />
  <circle cx="100" cy="70" r="40" fill="{$colors['primary']}" />
  <rect x="60" y="120" width="80" height="80" fill="{$colors['secondary']}" />
  <text x="100" y="180" font-family="Arial" font-size="16" text-anchor="middle" fill="white">{$label}</text>
</svg>
SVG;
    }
    
    /**
     * Generate SVG for vehicle
     */
    private function generateVehicleSvg(string $label, int $seed): string
    {
        // Use seed to generate consistent colors
        $colors = $this->getColorsFromSeed($seed);
        
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
  <rect width="200" height="200" fill="{$colors['bg']}" />
  <rect x="30" y="80" width="140" height="40" fill="{$colors['primary']}" />
  <rect x="50" y="60" width="100" height="30" fill="{$colors['secondary']}" />
  <circle cx="60" cy="130" r="15" fill="black" />
  <circle cx="140" cy="130" r="15" fill="black" />
  <text x="100" y="180" font-family="Arial" font-size="16" text-anchor="middle" fill="white">{$label}</text>
</svg>
SVG;
    }
    
    /**
     * Generate SVG for attendance
     */
    private function generateAttendanceSvg(string $label, int $seed): string
    {
        // Use seed to generate consistent colors
        $colors = $this->getColorsFromSeed($seed);
        
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
  <rect width="200" height="200" fill="{$colors['bg']}" />
  <rect x="40" y="40" width="120" height="120" fill="{$colors['primary']}" />
  <polyline points="60,100 90,130 140,70" stroke="{$colors['secondary']}" stroke-width="10" fill="none" />
  <text x="100" y="180" font-family="Arial" font-size="16" text-anchor="middle" fill="white">{$label}</text>
</svg>
SVG;
    }
    
    /**
     * Generate SVG for about_us
     */
    private function generateAboutUsSvg(string $label, int $seed): string
    {
        // Use seed to generate consistent colors
        $colors = $this->getColorsFromSeed($seed);
        
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
  <rect width="200" height="200" fill="{$colors['bg']}" />
  <circle cx="100" cy="100" r="70" fill="{$colors['primary']}" />
  <rect x="70" y="70" width="60" height="60" fill="{$colors['secondary']}" />
  <text x="100" y="180" font-family="Arial" font-size="16" text-anchor="middle" fill="white">{$label}</text>
</svg>
SVG;
    }
    
    /**
     * Get colors based on seed for consistent generation
     */
    private function getColorsFromSeed(int $seed): array
    {
        $colors = [
            ['#3498db', '#2980b9', '#1e5799'],  // Blue
            ['#e74c3c', '#c0392b', '#922b21'],  // Red
            ['#2ecc71', '#27ae60', '#1e8449'],  // Green
            ['#f1c40f', '#f39c12', '#b7950b'],  // Yellow
            ['#9b59b6', '#8e44ad', '#6c3483'],  // Purple
            ['#1abc9c', '#16a085', '#117a65'],  // Turquoise
            ['#d35400', '#a04000', '#873600'],  // Orange
            ['#34495e', '#2c3e50', '#1a2530'],  // Dark Blue
        ];
        
        $index = $seed % count($colors);
        $colorSet = $colors[$index];
        
        return [
            'bg' => '#2c3e50',
            'primary' => $colorSet[0],
            'secondary' => $colorSet[1]
        ];
    }
}