<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    /**
     * Create a new attendance record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAttendance(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'driver_id' => 'required|exists:drivers,id',
            'date' => 'required|date',
            'time' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $directory = public_path('attendances');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $image->move($directory, $imageName);
            $imagePath = 'attendances/' . $imageName;
        }

        // Create attendance record
        $attendance = Attendance::create([
            'booking_id' => $request->booking_id,
            'driver_id' => $request->driver_id,
            'date' => $request->date,
            'time' => $request->time,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Attendance recorded successfully',
            'data' =>$attendance
        ], 201);
    }

    /**
     * Get all attendance records.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendances()
    {
        $attendances = Attendance::with('booking','booking.customer','booking.package','booking.package.vehicle','driver')->get();
        return response()->json([
            'status' => true,
            'message' => 'Attendances retrieved successfully',
            'data' => $attendances
        ]);
    }

    /**
     * Get a specific attendance record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendance($id)
    {
        $attendance = Attendance::with('booking', 'driver')->find($id);

        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance record not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Attendance retrieved successfully',
            'data' => $attendance
        ]);
    }

    /**
     * Update an attendance record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAttendance(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance record not found',
                'data' => null
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'booking_id' => 'sometimes|required|exists:bookings,id',
            'driver_id' => 'sometimes|required|exists:drivers,id',
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($attendance->image && file_exists(public_path($attendance->image))) {
                unlink(public_path($attendance->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $directory = public_path('attendances');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $image->move($directory, $imageName);
            $attendance->image = 'attendances/' . $imageName;
        }

        // Update attendance record
        if ($request->has('booking_id')) {
            $attendance->booking_id = $request->booking_id;
        }
        if ($request->has('driver_id')) {
            $attendance->driver_id = $request->driver_id;
        }
        if ($request->has('date')) {
            $attendance->date = $request->date;
        }
        if ($request->has('time')) {
            $attendance->time = $request->time;
        }

        $attendance->save();

        return response()->json([
            'status' => true,
            'message' => 'Attendance record updated successfully',
            'data' =>$attendance
        ]);
    }

    /**
     * Delete an attendance record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAttendance($id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance record not found',
                'data' => null
            ], 404);
        }

        // Delete image if exists
        if ($attendance->image && file_exists(public_path($attendance->image))) {
            unlink(public_path($attendance->image));
        }

        $attendance->delete();

        return response()->json([
            'status' => true,
            'message' => 'Attendance record deleted successfully',
            'data' => null
        ]);
    }
}