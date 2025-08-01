<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Attendance;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Create a new booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBooking(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'learner_name' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
            'joining_date' => 'required|date',
            'time_slot' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create booking
        $booking = Booking::create([
            'customer_id' => $request->customer_id,
            'learner_name' => $request->learner_name,
            'package_id' => $request->package_id,
            'joining_date' => $request->joining_date,
            'time_slot' => $request->time_slot,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Booking created successfully',
            'data' =>$booking
        ], 201);
    }

    /**
     * Get all bookings.
     * If login by Customer - Only their booking list will be displayed
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBookings(Request $request)
    {
        $user = $request->user();
        $userType = null;
        
        // Check if the authenticated user is a customer
        if ($user instanceof \App\Models\Customer) {
            $userType = 'customer';
            // Filter bookings for this customer only
            $bookings = Booking::with(['customer', 'package', 'package.vehicle'])
                ->where('customer_id', $user->id)
                ->get();
        } else {
            // For admin users, return all bookings
            $bookings = Booking::with(['customer', 'package', 'package.vehicle'])->get();
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Bookings retrieved successfully',
            'data' => $bookings
        ]);
    }

    /**
     * Get a specific booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBooking($id)
    {
        $booking = Booking::with(['customer', 'package'])->find($id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Booking retrieved successfully',
            'data' =>$booking
        ]);
    }

    /**
     * Update a booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found',
                'data' => null
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes|required|exists:customers,id',
            'learner_name' => 'sometimes|required|string|max:255',
            'package_id' => 'sometimes|required|exists:packages,id',
            'joining_date' => 'sometimes|required|date',
            'time_slot' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update booking fields if provided
        if ($request->has('customer_id')) $booking->customer_id = $request->customer_id;
        if ($request->has('learner_name')) $booking->learner_name = $request->learner_name;
        if ($request->has('package_id')) $booking->package_id = $request->package_id;
        if ($request->has('joining_date')) $booking->joining_date = $request->joining_date;
        if ($request->has('time_slot')) $booking->time_slot = $request->time_slot;

        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Booking updated successfully',
            'data' =>$booking
        ]);
    }

    /**
     * Delete a booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBooking($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found',
                'data' => null
            ], 404);
        }

        $booking->delete();

        return response()->json([
            'status' => true,
            'message' => 'Booking deleted successfully',
            'data' => null
        ]);
    }

    /**
     * Get booking details with attendances and payments.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    // public function getBookingDetail($id)
    // {
    //     $booking = Booking::with([
    //         'customer', 
    //         'package', 
    //         'package.vehicle'
    //     ])->find($id);

    //     if (!$booking) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Booking not found',
    //             'data' => null
    //         ], 404);
    //     }

    //     // Get attendances for this booking
    //     $attendances = Attendance::where('booking_id', $id)
    //         ->orderBy('date', 'desc')
    //         ->orderBy('time', 'desc')
    //         ->get();

    //     // Get transactions (payments) for this booking
    //     $transactions = Transaction::where('booking_id', $id)
    //         ->orderBy('date', 'desc')
    //         ->get();

    //     // Format the response with separate sections for booking details, attendances, and transactions
    //     $response = [
    //         'booking' => $booking,
    //         'attendances' => $attendances,
    //         'transactions' => $transactions,
    //         // Calculate some summary information
    //         'summary' => [
    //             'total_attendances' => $attendances->count(),
    //             'total_paid' => $transactions->sum('amount'),
    //             'package_price' => $booking->package->price ?? 0,
    //             'remaining_balance' => ($booking->package->price ?? 0) - $transactions->sum('amount')
    //         ]
    //     ];

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Booking details retrieved successfully',
    //         'data' => $response 
    //     ]);
    // }


    public function getBookingDetail($id)
{
    $booking = Booking::with([
        'customer', 
        'package', 
        'package.vehicle'
    ])->find($id);

    if (!$booking) {
        return response()->json([
            'status' => false,
            'message' => 'Booking not found',
            'data' => null
        ], 404);
    }

    // Get attendances
    $attendances = Attendance::where('booking_id', $id)
        ->orderBy('date', 'desc')
        ->orderBy('time', 'desc')
        ->get();

    // Get transactions
    $transactions = Transaction::where('booking_id', $id)
        ->orderBy('date', 'desc')
        ->get();

    // Merge all into a single-level array
    $data = $booking->toArray(); // Convert booking (with relations) to array

    $data['attendances'] = $attendances;
    $data['transactions'] = $transactions;
    $data['summary'] = [
        'total_attendances' => $attendances->count(),
        'total_paid' => $transactions->sum('amount'),
        'package_price' => $booking->package->price ?? 0,
        'remaining_balance' => ($booking->package->price ?? 0) - $transactions->sum('amount')
    ];

    return response()->json([
        'status' => true,
        'message' => 'Booking details retrieved successfully',
        'data' => $data
    ]);
}

/**
 * Filter bookings by date or month.
 * Automatically detects format:
 * - dd-mm-yyyy: Filter by specific day
 * - mm-yyyy: Filter by month and year
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function filterBookings(Request $request)
{
    // Validate request data
    $validator = Validator::make($request->all(), [
        'date_filter' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $dateFilter = $request->date_filter;
    $user = $request->user();
    
    // Base query with relationships
    $query = Booking::with(['customer', 'package', 'package.vehicle']);
    
    // Check the format of the date_filter parameter
    if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $dateFilter)) {
        // Format: dd-mm-yyyy - Filter by specific day
        try {
            // Parse the input date
            $carbonDate = Carbon::createFromFormat('d-m-Y', $dateFilter);
            // Format for SQL query
            $date = $carbonDate->format('Y-m-d');
            // Use raw SQL for date comparison to ensure proper format matching
            $query->whereRaw('DATE(joining_date) = ?', [$date]);
            $message = 'Bookings filtered by day successfully';
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid date format. Please use dd-mm-yyyy for day filtering.',
                'data' => null
            ], 422);
        }
    } elseif (preg_match('/^\d{2}-\d{4}$/', $dateFilter)) {
        // Format: mm-yyyy - Filter by month and year
        try {
            $parts = explode('-', $dateFilter);
            $month = (int)$parts[0];
            $year = (int)$parts[1];
            
            if ($month < 1 || $month > 12) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid month. Month must be between 1 and 12.',
                    'data' => null
                ], 422);
            }
            
            // Use raw SQL for more reliable date filtering
            $query->whereRaw('YEAR(joining_date) = ? AND MONTH(joining_date) = ?', [$year, $month]);
            $message = 'Bookings filtered by month successfully';
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid date format. Please use mm-yyyy for month filtering.',
                'data' => null
            ], 422);
        }
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Invalid date format. Please use dd-mm-yyyy for day filtering or mm-yyyy for month filtering.',
            'data' => null
        ], 422);
    }
    
    // If user is a customer, only show their bookings
    if ($user instanceof \App\Models\Customer) {
        $query->where('customer_id', $user->id);
    }
    
    try {
        // Execute the query
        $bookings = $query->get();
        
        // Calculate pending amount for each booking
        $bookings->map(function($booking) {
            $booking->pending_amount = ($booking->package->price ?? 0) - $booking->transactions()->sum('amount');
            return $booking;
        });
        
        // Get booking IDs to fetch related data
        $bookingIds = $bookings->pluck('id')->toArray();
        
        // Get total payments for these bookings
        $totalPayments = Transaction::whereIn('booking_id', $bookingIds)
            ->sum('amount');
            
        // Get total attendances for these bookings
        $totalAttendances = Attendance::whereIn('booking_id', $bookingIds)
            ->count();
        
        // Calculate total package price
        $totalPackagePrice = 0;
        foreach ($bookings as $booking) {
            $totalPackagePrice += $booking->package->price ?? 0;
        }
        
        // Calculate remaining balance
        $remainingBalance = $totalPackagePrice - $totalPayments;
        
        
        // Prepare response data with summary
        $responseData = [
            'bookings' => $bookings,
            'summary' => [
                'total_bookings' => count($bookings),
                'total_attendances' => $totalAttendances,
                'total_package_price' => $totalPackagePrice,
                'total_paid' => $totalPayments,
                'remaining_balance' => $remainingBalance
            ]
        ];
        
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $responseData
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Error executing query: ' . $e->getMessage()
        ], 500);
    }
}

}