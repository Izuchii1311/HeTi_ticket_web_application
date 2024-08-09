<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\EmployeesModel;
use App\Models\Presensi;
use App\Models\User;
use App\Notifications\ComplaintSubmitted;
use App\Notifications\ComplaintApproved;
use App\Notifications\Notif;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

         $data['data'] = Complaint:: orderByDesc('created_at')->where('employee_id', auth()->user()->id)->get();
        return view("pengaduan_public.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'employee_id' => 'required',
        'type' => 'required',
        'description' => 'required',
    ]);

    // Generate complaint code
    $mytime = Carbon::now();
    $complaint_code = '#' . $mytime->format('dmY') . Str::random(2);


    $obj = [

        'complaint_code' => $complaint_code,
        'employee_id' => $request->employee_id,
        'title' => $request->type,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => 'waiting'
    ];


    if ($request->file != null) {
        $fileName = time() . '-' . $request->type . '-.' . $request->file->extension();
        $request->file->move(public_path('upload'), $fileName);
        $obj['file'] = 'upload/' . $fileName;
    }


    $complaint = Complaint::create($obj);
    // Mendapatkan user yang sedang login
    $user = Auth::user();

    if ($user) {
        // Kirim notifikasi ke admin
        $admin = User::where('name', 'admin')->first();

        $user->notify(new Notif(
            $user,
            $user->name . ' berhasil membuat pengaduan.',
            'Klik disini untuk melihat detailnya.',
            'warning',
            url('/employee/pengaduan'),
            'bx bx-info-circle',
            $user->id,
            $complaint->status
        ));

        $admin->notify(new Notif(
            $admin,
            $user->name . ' telah membuat pengaduan.',
            'Klik disini untuk melihat detailnya.',
            'warning',
            url('/admin/pengaduan'),
            'bx bx-info-circle',
            $admin->id,
            $complaint->status
        ));
    }

    return redirect()->back()->withSuccess("Pengaduan telah dikirim dengan nomor pengaduan " . $complaint_code);
}

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['data'] = Complaint::find($id);
        $data['employees'] = EmployeesModel::all();
        $data['loggedInUserName']= auth()->user()->name; // Get the logged-in user's name




         // Mengirim notifikasi ke admin
         $user = Auth::user();
         $admin = User::where('name', 'admin')->first();
         $complaint = Complaint::where('status')->first();
           // Kirim notifikasi ke admin
           $user->notify(new Notif(
             $user,
             @$user->name . ' berhasil mengedit pengaduan.',
             'Klik disini untuk melihat detailnya.',
             'warning',
             url('/employee/pengaduan'),
             'bx bx-info-circle',
             $user->id,
             ""

            ));

        return view('pengaduan_public.form', $data);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd('Request Data:', $request->all(), 'ID:', $id);

        $request->validate([
            'status' => 'required',
            'type' => 'required',
        ]);

        Complaint::where('id', $id)->update([
           'title' => $request->type,
           'reason' => $request->reason,
           'description' => $request->description,
           'start_date' => $request->start_date,
           'end_date' => $request->end_date
        ]);

        if ($request->status == "approved") {
            $data = Complaint::where('id', $id)->first();
            $employee = EmployeesModel::where('name', $data->employee_name)->first();

        }

        return redirect()->to('employee/pengaduan')->withSuccess("Berhasil");
    }

    private function updatePresensi(Complaint $complaint)
    {
        $employee = EmployeesModel::where('name', $complaint->employee_name)->first();

        if ($employee) {
            if ($complaint->start_date) {
                $startDate = Carbon::createFromFormat('Y-m-d', $complaint->start_date);
                $endDate = Carbon::createFromFormat('Y-m-d', $complaint->end_date);

                $diff = $startDate->diffInDays($endDate, false);

                for ($i = 1; $i <= ($diff + 1); $i++) {
                    $d = $startDate->clone()->addDays($i-1);
                    Presensi::updateOrCreate(
                        [
                            'employee_id' => $employee->id,
                            'date' => $d->format('Y-m-d')
                        ],
                        [
                            'date' => $d->format('Y-m-d'),
                            'type' => $complaint->title,
                            'checkin' => $d->format('Y-m-d H:i:s'),
                            'checkout' => $d->format('Y-m-d H:i:s'),
                        ]
                    );
                }
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getComplaintCode(Request $request)
    {
        $data = Complaint::where('complaint_code', $request->code)->first();

        if ($data) {
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        }


        return response()->json([
            'status' => false,
            'data' => null
        ]);

    }
}
