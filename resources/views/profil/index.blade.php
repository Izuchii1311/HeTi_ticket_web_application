@extends('templates.main')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="container mb-2">
                <h1>Update Profil Akun</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }} <br>
                            @endforeach
                        </div>
                    @endif

                    <form method="post" action="{{ route('profil.update', Auth::id()) }}" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                                <div class="text-center">
                                    <div>
                                        <img id="previewImage" class="my-2 img-fluid img-thumbnail rounded-circle"
                                             src="{{ asset($data->profile_picture ? 'storage/images/users/'.$data->profile_picture : 'images/users/avatar-1.jpg') }}"
                                             style="width: 300px; height: 250px;" />
                                    </div>
                                    <input accept="image/*" type="file" name="profile_picture" id="photo" onchange="loadFile(event)" hidden />
                                    <button type="button" class="btn btn-sm btn-info mt-2" onclick="openDialog()">Unggah Foto</button>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Nama</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="formrow-firstname-input" placeholder="Nama" value="{{ old('name', $data->name) }}" autocomplete="off">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="formrow-email" placeholder="Email" value="{{ old('email', $data->email) }}" autocomplete="off">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" autocomplete="off">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" autocomplete="off">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- end card body -->
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function openDialog() {
            document.getElementById('photo').click();
        }

        function loadFile(event) {
            var output = document.getElementById('previewImage');
            output.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endpush
