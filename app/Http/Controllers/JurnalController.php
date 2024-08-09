<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeesModel;
use App\Models\Jurnal;
use App\Models\Presensi;
use App\Models\User;
use App\Notifications\Notif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function index()
    {
        $data['employees'] = EmployeesModel::all();
        // $employee = EmployeesModel::where('name', auth()->user()->name)->first();
        $data['data'] = Jurnal::orderByDesc('created_at')->where('employee_id', @auth()->user()->employee->id)->get();

        $data['employeeId'] = EmployeesModel::where('users_id', auth()->id())->value('id');
        $data['hasFilledPresensis'] = Presensi::where('employee_id', $data['employeeId'])->where('date', date ('Y-m-d'))->exists();

        return view("jurnal.index", $data);
    }

    public function create()
    {
        $employees = EmployeesModel::all();
        return view('jurnal.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            // 'employee_id' => 'required',

            ]);

        $judul = $request->input('judul');
        $deskripsi = $request->input('deskripsi');


        $employee = EmployeesModel::where('users_id', Auth::user()->id)->first();


        // return  $employee->users_id;
        // $presensi = Presensi::find('employee_id');

        Jurnal::create([
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'employee_id' =>  $employee->id,
            // 'presensi_id' =>  $presensi,
        ]);


        $user = Auth::user();
        $admin = User::where('name', 'admin')->first();

        $user->notify(new Notif(
            $user,
            @$user->name . ' berhasil membuat jurnal.',
            'Klik disini untuk melihat detailnya.',
            'warning',
            url('/employee/jurnal'),
            'bx bx-info-circle',
            $user->id,
            ""
        ));

        $admin->notify(new Notif(
            $admin,
            @$user->name . ' telah membuat jurnal.',
            'Klik disini untuk melihat detailnya.',
            'warning',
            url('/admin/jurnal'),
            'bx bx-info-circle',
            $admin->id,
            ""
        ));


        return redirect()->back()->withSuccess('Data jurnal berhasil disimpan');
    }
}
