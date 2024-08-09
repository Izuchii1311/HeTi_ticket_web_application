@extends('templates.main')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tambah Karyawan Baru</h4>
                            <p><i>Buat data akun karyawan baru.</i></p>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('karyawan.store') }}" autocomplete="off">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Karyawan</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama karyawan..." autocomplete="off">
                                    @error('name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Karyawan</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan email karyawan..." autocomplete="off">
                                    @error('email')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi untuk akun karyawan..." autocomplete="off">
                                    @error('password')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label">Role Karyawan</label>
                                    <select class="form-control" id="role" name="role">
                                        <option value="">Pilih</option>
                                        <option value="customer-service">Customer Service</option>
                                        <option value="agent">Agent</option>
                                    </select>
                                    <p><i>*Role karyawan menentukan jenis pekerjaan dan fitur yang berbeda di dalam aplikasi</i></p>
                                    @error('role')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" class="form-control" name="nip" id="nip" placeholder="Masukkan NIP karyawan..." autocomplete="off">
                                    @error('nip')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 clearfix">
                                    <label for="status" class="form-label">Status Akun Karyawan</label>
                                    <select class="form-control" name="status">
                                        <option value="">Pilih</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>


                                <button type="submit" class="btn btn-primary w-md">Buat Akun Karyawan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
