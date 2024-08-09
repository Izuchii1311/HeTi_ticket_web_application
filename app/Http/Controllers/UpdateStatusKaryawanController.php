<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class UpdateStatusKaryawanController extends Controller
{
    public function updateStatus(Request $request)
    {
        $userId = $request->input('id');
        $status = $request->input('status');

        // Find the related employee record and update the status
        $employee = Employee::where('user_id', $userId)->first();

        if ($employee) {
            $employee->is_active = $status;
            $employee->save();

            return response()->json(['success' => true, 'message' => 'Status berhasil diubah']);
        }

        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengubah status']);
    }
}
