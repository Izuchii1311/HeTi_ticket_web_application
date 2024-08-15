@extends('templates.main')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Data User</h4>
                            <p><i>Edit data akun user.</i></p>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('karyawan.update', $user->id) }}" autocomplete="off">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama User</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama karyawan..." autocomplete="off">
                                    @error('name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email User</label>
                                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $user->email) }}" placeholder="Masukkan email karyawan..." autocomplete="off">
                                    @error('email')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label">Role User</label>
                                    <select class="form-control" id="role" name="role">
                                        <option value="costumer-service" {{ old('role', $user->role) == 'costumer-service' ? 'selected' : '' }}>Customer Service</option>
                                        <option value="agent" {{ old('role', $user->role) == 'agent' ? 'selected' : '' }}>Agent</option>
                                    </select>
                                    <p><i>*Role user menentukan jenis pekerjaan dan fitur yang berbeda di dalam aplikasi</i></p>
                                    @error('role')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" class="form-control" name="nip" id="nip" value="{{ old('nip', $employee->nip) }}" placeholder="Masukkan NIP karyawan..." autocomplete="off">
                                    @error('nip')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 clearfix">
                                    <label for="status" class="form-label">Status Akun User</label>
                                    <select class="form-control" name="status">
                                        <option value="1" {{ old('status', $employee->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $employee->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-md">Update Akun User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
