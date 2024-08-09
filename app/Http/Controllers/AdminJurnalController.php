<?php

namespace App\Http\Controllers;

use App\Models\EmployeesModel;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class AdminJurnalController extends Controller
{
    public function index()
    {
        // Mendapatkan semua karyawan
        $data['employees'] = EmployeesModel::all();
        // Employee ditemukan, lanjutkan untuk mendapatkan jurnal
        $data['data'] = Jurnal::with('employee')->orderByDesc('created_at')->get();

        return view("jurnal.index-admin", $data);
    }

    public function show($employee_id)
    {
        $data['data'] = Jurnal::where('employee_id', $employee_id)->with('employee')->get();
        return view('jurnal.form-admin', $data);
    }
}
