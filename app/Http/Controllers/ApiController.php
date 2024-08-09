<?php

namespace App\Http\Controllers;

use App\Models\EmployeesModel;
use App\Models\Presensi;
use App\Models\StatusEmployeeModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function attendance(Request $request) {
        $rfid = $request->rfid_uid;
        $date = $request->date;
        $token = $request->_token;

//        if ($token != env('TOKEN_API')) {
        if ($token != "WAHIWAHIWAHIWA") {
            return response()->json([
                'status' => false,
                'message' => 'TOKEN TIDAK SESUAI'
            ]);
        }

        $data = EmployeesModel::where('rfid', $rfid)->first();


        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'KARTU TIDAK DIKENAL'
            ]);
        }

        $presensi = Presensi::where('employee_id', $data->id)->where('date', Carbon::createFromFormat('Y-m-d H:i', $date)->format('Y-m-d'))->first();

        $now = Carbon::now();
        $startCheckin = Carbon::now();
        $startCheckin->hour(6);
        $startCheckin->minute(0);
        $startCheckin->second(0);

        if ($startCheckin <= $now) {

            if($presensi != null){
                $presensi->checkout = date('Y-m-d H:i:s');
                $presensi->save();

                $message = 'GOODBYE';

            } else {
                $presensi = new Presensi;
                $presensi->employee_id = $data->id;
                $presensi->date = $date;
                $presensi->checkin = date('Y-m-d H:i:s');
                $presensi->save();

                $message = 'WELCOME';
            }

            return response()->json([
                'status' => true,
                'message' =>  $message.' '. strtoupper($data->name)
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' =>  "KAMANA ATUH?"
            ]);
        }

    }
}
