<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Employee;
use App\Models\EmployeesModel;
use App\Models\Presensi;
use App\Models\User;
use App\Notifications\Notif;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ComplaintAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['employees'] = EmployeesModel::all();
        $data['data'] = Complaint::orderByDesc('created_at')->get();
        return view('pengaduan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['data'] = Complaint::find($id);
        $data['complaints'] = Complaint::all();

        $data['employees'] = EmployeesModel::all();
        // dd($data);

        // // Mengirim notifikasi ke admin
        // $user = User::where('name', $data['data']->employee_name)->first();

        // $admin = User::where('email', 'admin@gmail.com')->first();
        // //   Kirim notifikasi ke admin
        //   $user->notify(new Notif(
        //     $user,
        //     @$admin->email . ' telah '. $data['data']->status . ' pengaduan.',
        //     'Klik disini untuk melihat detailnya.',
        //     'warning',
        //     url('/employee/pengaduan'),
        //     'bx bx-info-circle',
        //     $user->id,
        //     $data['data']->status
        //    ));

        // $admin->notify(new Notif(
        //     $admin,
        //     @$user->email . ' telah '. $data['data']->status .'pengaduan',
        //     'Klik disini untuk melihat detailnya.',
        //     'warning',
        //     url('/admin/pengaduan'),

        //     'bx bx-info-circle',

        //     $admin->id,
        //     $data['data']->status



        // ));

        return view('pengaduan.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        Complaint::where('id', $id)->update([
            'reason' => $request->reason,
            'status' => $request->status
        ]);

        $data = Complaint::where('id', $id)->first();
        if ($request->status == "approved") {
            $employee = EmployeesModel::where('users_id', $data->employee_id)->first();

            if ($employee) {
                if ($data->start_date) {
                    $startDate = Carbon::createFromFormat('Y-m-d', $data->start_date);
                    $endDate = Carbon::createFromFormat('Y-m-d', $data->end_date);

                    $diff = $startDate->diffInDays($endDate, false);

                    for ($i = 1; $i <= ($diff + 1); $i++) {
                        $d = $startDate->clone()->addDays($i - 1);
                        Presensi::updateOrCreate(
                            [
                                'employee_id' => $employee->id,
                                'date' => $d->format('Y-m-d')
                            ],
                            [
                                'date' => $d->format('Y-m-d'),
                                'type' => $data->title,
                                'checkin' => $d->format('Y-m-d H:i:s'),
                                'checkout' => $d->format('Y-m-d H:i:s'),
                                ]
                            );
                        }
                }
            }
        } else if ($request->status == "reject") {
            $employee = EmployeesModel::where('users_id', $data->employee_id)->first();

            if ($employee) {
                if ($data->start_date) {
                    $startDate = $data->start_date;
                    $endDate = $data->end_date;
                    Presensi::where("employee_id", $employee->id)->whereBetween('date', [$startDate, $endDate])->delete();
                }
            }
        }

        // Mengirim notifikasi ke admin
        $user = User::where('name', $data->user->name)->first();
        $admin = User::where('name', 'admin')->first();
        // Kirim notifikasi ke admin
        $user->notify(new Notif(
            $user,
            @$admin->name . ' telah ' . $data->status . ' pengaduan.',
            'Klik disini untuk melihat detailnya.',
            'warning',
            url('/employee/pengaduan'),
            'bx bx-info-circle',
            $user->id,
            $data->status
        ));
        return redirect()->to('admin/pengaduan')->withSuccess("Berhasil");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {

            $pengaduan = Complaint::find($id);
            $employee = EmployeesModel::where('users_id', $pengaduan->employee_id)->first();

            $startDate = ($pengaduan->start_date);
            $endDate = ($pengaduan->end_date);






        //    $presensi =
            Presensi::where("employee_id", $employee->id)->whereBetween('date', [$startDate, $endDate])->delete();
           $pengaduan->delete();

        //    dd($presensi->first());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            error_log($e);
        }
    }
}
