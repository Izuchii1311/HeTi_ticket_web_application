<?php

namespace App\Http\Controllers;

use App\Exports\PresensiExport;
use App\Models\Complaint;
use App\Models\EmployeesModel;
use App\Models\Jurnal;
use App\Models\Presensi;
use App\Models\User;
use App\Notifications\Notif;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        if ($year == null) {
            $year = Carbon::now()->year;
        }

        if ($month == null) {
            $month = Carbon::now()->month;
        }

        $data['currentMonth'] = $month;
        $data['currentYear'] = $year;

        $data['employees'] = EmployeesModel::all();
        $data['months'] = getListOfMonth();

        $start = Carbon::now()->setMonth($month)->setYear($year)->startOfMonth();
        $end = Carbon::now()->setMonth($month)->setYear($year)->endOfMonth();
        $diff = $start->diffInDays($end, false);

        for ($i = 1; $i <= ($diff + 1); $i++) {
            $d = Carbon::now()->setMonth($month)->setYear($year)->startOfMonth()->addDays($i-1);
            $now = Carbon::now();

            $diffWithNow = $d->diffInDays($now, false);
            $employee = EmployeesModel::where('name', )->first();
            $jurnal = new Jurnal();



            if ($d->isWeekday()) {
                $dates[] = [
                    'day' => $i,
                    'date' => $d->format('Y-m-d'),
                    'presensi' => Presensi::where('date', $d->format('Y-m-d'))->get()->toArray(),
                    'absent' => $diffWithNow > 0,
                    'jurnal' => Jurnal::where('created_at', 'LIKE', $d->format('Y-m-d') . '%')->orderBy('created_at', 'asc')->get()->toArray()

                ];
            }


        }

        $data['dates'] = $dates;
        // return($data);
        return view('presensi.index', $data);
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
        $request->validate([
            'tanggal' => 'required',
            'employee_id' => 'required',
            'type' => 'required',
            'checkin' => 'required',
            'checkout' => 'required',
        ]);

        $checkin = $request->checkin;
        $checkout = $request->checkout;
        $tanggal = \Carbon\Carbon::createFromFormat('Y-m-d', $request->tanggal);

        $checkinInput = $tanggal->copy();
        $checkinInput->setHours(explode(':', $checkin)[0]);
        $checkinInput->setMinutes(explode(':', $checkin)[1]);

        $checkoutInput = $tanggal->copy();
        $checkoutInput->setHours(explode(':', $checkout)[0]);
        $checkoutInput->setMinutes(explode(':', $checkout)[1]);



        Presensi::create([
            'employee_id' => $request->employee_id,
            'date' => $tanggal,
            'checkin' => $checkinInput,
            'checkout' => $checkoutInput,
            'type' => $request->type,
        ]);

          // Mengirim notifikasi ke admin
          $user = Auth::user();
          $admin = User::where('name', 'admin')->first();

            // Kirim notifikasi ke admin
            $user->notify(new Notif(
              $user,
              @$user->name . ' berhasil mengajukan presensi.',
              'Klik disini untuk melihat detailnya.',
              'warning',
              url('/employee/presensi'),
              'bx bx-info-circle',
              $user->id,
                ""
          ));


          $admin->notify(new Notif(
            $admin,
            @$user->name . ' telah mengajukan presensi.',
            'Klik disini untuk melihat detailnya.',
            'warning',
            url('/admin/presensi'),
            'bx bx-info-circle',
            $admin->id,
            ""

        ));

        return redirect()->to('admin/presensi')->withSuccess("Berhasil diubah");
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
        //
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generate(Request $request) {
        $month = $request->month;
        $year = $request->year;

        if ($year == null) {
            $year = Carbon::now()->year;
        }

        if ($month == null) {
            $month = Carbon::now()->month;
        }

        $start = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start);
        $end = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end);

        $data['start'] = $start->format('d-m-Y');
        $data['end'] = $end->format("d-m-Y");

        $data['employees'] = EmployeesModel::all();
        $data['months'] = getListOfMonth();

//        $start = Carbon::now()->setMonth($month)->startOfMonth();
//        $end = Carbon::now()->setMonth($month)->endOfMonth();
        $diff = $start->diffInDays($end, false);

        for ($i = 1; $i <= ($diff + 1); $i++) {
            $d = $start->clone()->addDays($i-1);
            $now = Carbon::now();

            $diffWithNow = $d->diffInDays($now, false);

            if ($d->isWeekday()) {
                $dates[] = [
                    'day' => $d->day,
                    'date' => $d->format('Y-m-d'),
                    'presensi' => Presensi::where('date', $d->format('Y-m-d'))->get()->toArray(),
                    'absent' => $diffWithNow > 0,
                ];
            }
        }

        $data['dates'] = $dates;

        return Excel::download(new PresensiExport($data), 'export.xlsx');
    }

}
