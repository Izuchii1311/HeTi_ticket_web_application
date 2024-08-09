<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::where('role', '!=', 'admin')
        ->with('employee')
        ->get();
        // dd($users);
        return view('karyawan.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('karyawan.form', );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:customer-service,agent',
            'nip' => 'required|string|max:20|unique:employees,nip',
            'status' => 'required|boolean',
        ]);

        // Buat user baru
        $user = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "role" => $data["role"],
            "password" => bcrypt($data["password"]),
        ]);

        // Buat data karyawan terkait
        Employee::create([
            'user_id' => $user->id,
            'name' => $data["name"],
            'nip' => $data["nip"],
            'is_active' => $data["status"]
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Data berhasil ditambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::findOrFail($id);
        $employee = Employee::where('user_id', $id)->first();

        return view('karyawan.form_edit', compact('user', 'employee'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $employee = Employee::where('user_id', $id)->firstOrFail(); // Ensure the employee exists

        // Validate the request
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:costumer-service,agent',
            'nip' => 'required|string|max:20',
            'status' => 'required|boolean',
        ]);

        // Check if the `nip` is different from the existing value and if it needs to be unique
        if ($data['nip'] !== $employee->nip) {
            // Add unique validation for `nip`
            $request->validate([
                'nip' => 'unique:employees,nip'
            ]);
        }

        // Update user data
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        // Update employee data
        $employee->update([
            'name' => $data['name'],
            'nip' => $data['nip'],
            'is_active' => $data['status']
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Data berhasil diperbarui');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
