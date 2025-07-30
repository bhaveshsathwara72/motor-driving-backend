<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\AboutUsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LicenceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']); // Unified login endpoint for both admin and customer
Route::post('/register', [CustomerController::class, 'register']); // Customer registration

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Auth API Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Customer API Routes
    Route::post('/customers', [CustomerController::class, 'createCustomer']);
    Route::get('/customers', [CustomerController::class, 'getCustomers']);
    Route::get('/customers/{id}', [CustomerController::class, 'getCustomer']);
    Route::put('/customers/{id}', [CustomerController::class, 'updateCustomer']);
    Route::delete('/customers/{id}', [CustomerController::class, 'deleteCustomer']);
    
    // Driver API Routes
    Route::post('/drivers', [DriverController::class, 'createDriver']);
    Route::get('/drivers', [DriverController::class, 'getDrivers']);
    Route::get('/drivers/{id}', [DriverController::class, 'getDriver']);
    Route::put('/drivers/{id}', [DriverController::class, 'updateDriver']);
    Route::delete('/drivers/{id}', [DriverController::class, 'deleteDriver']);
    
    // Vehicle API Routes
    Route::post('/vehicles', [VehicleController::class, 'createVehicle']);
    Route::get('/vehicles', [VehicleController::class, 'getVehicles']);
    Route::get('/vehicles/{id}', [VehicleController::class, 'getVehicle']);
    Route::put('/vehicles/{id}', [VehicleController::class, 'updateVehicle']);
    Route::delete('/vehicles/{id}', [VehicleController::class, 'deleteVehicle']);
    
    // Package API Routes
    Route::post('/packages', [PackageController::class, 'createPackage']);
    Route::get('/packages', [PackageController::class, 'getPackages']);
    Route::get('/packages/vehicle/{vehicleId}', [PackageController::class, 'getPackagesByVehicle']);
    Route::get('/packages/{id}', [PackageController::class, 'getPackage']);
    Route::put('/packages/{id}', [PackageController::class, 'updatePackage']);
    Route::delete('/packages/{id}', [PackageController::class, 'deletePackage']);
    
    // Booking API Routes
    Route::post('/bookings', [BookingController::class, 'createBooking']);
    Route::get('/bookings', [BookingController::class, 'getBookings']);
    Route::get('/bookings/{id}', [BookingController::class, 'getBooking']);
    Route::get('/bookings/{id}/detail', [BookingController::class, 'getBookingDetail']);
    Route::put('/bookings/{id}', [BookingController::class, 'updateBooking']);
    Route::delete('/bookings/{id}', [BookingController::class, 'deleteBooking']);
    
    // Attendance API Routes
    Route::post('/attendances', [AttendanceController::class, 'createAttendance']);
    Route::get('/attendances', [AttendanceController::class, 'getAttendances']);
    Route::get('/attendances/{id}', [AttendanceController::class, 'getAttendance']);
    Route::put('/attendances/{id}', [AttendanceController::class, 'updateAttendance']);
    Route::delete('/attendances/{id}', [AttendanceController::class, 'deleteAttendance']);
    
    // Transaction API Routes
    Route::post('/transactions', [TransactionController::class, 'createTransaction']);
    Route::get('/transactions', [TransactionController::class, 'getTransactions']);
    Route::get('/transactions/{id}', [TransactionController::class, 'getTransaction']);
    Route::put('/transactions/{id}', [TransactionController::class, 'updateTransaction']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'deleteTransaction']);
    
    // About Us API Routes
    Route::post('/about-us', [AboutUsController::class, 'createAboutUs']);
    Route::get('/about-us', [AboutUsController::class, 'getAboutUs']);
    Route::get('/about-us/{id}', [AboutUsController::class, 'getAboutUsById']);
    Route::put('/about-us/{id}', [AboutUsController::class, 'updateAboutUs']);
    Route::delete('/about-us/{id}', [AboutUsController::class, 'deleteAboutUs']);
    
    // Licence API Routes
    Route::post('/licences', [LicenceController::class, 'createLicence']);
    Route::get('/licences', [LicenceController::class, 'getLicences']);
    Route::get('/licences/{id}', [LicenceController::class, 'getLicence']);
    Route::put('/licences/{id}', [LicenceController::class, 'updateLicence']);
    Route::delete('/licences/{id}', [LicenceController::class, 'deleteLicence']);
});
