@extends('auth.layouts.main')

@section('title', $pageTitle ?? 'HeTi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card overflow-hidden">

            <div class="bg-primary bg-soft">
                {{-- Header --}}
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-4">
                            <h5 class="text-primary">LOGIN AREA</h5>
                            <p>One more step to continue!</p>
                        </div>
                    </div>
                    {{-- Image background --}}
                    <div class="col-5 align-self-end">
                        <img src="{{asset("assets")}}/images/profile-img.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                {{-- Logo --}}
                <div class="auth-logo">
                    <a href="" class="auth-logo-dark">
                        <div class="avatar-md profile-user-wid mb-4">
                            <span class="avatar-title rounded-circle bg-light">
                                <img src="{{ asset('assets/img/HeTi_Logo.png') }}" alt="" class="rounded-circle" height="60">
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Form Login --}}
                <div class="p-2">
                    <form class="form-horizontal" action="{{ route('action-login') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" autocomplete="off" placeholder="Masukan email anda..." required>
                            @if ($errors->has('email'))
                                <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group auth-pass-inputgroup">
                                <input type="password" name="password" class="form-control" autocomplete="off" placeholder="Masukkan password anda..." required>
                                <button class="btn btn-light " type="button" id="password-addon">
                                    <i class="mdi mdi-eye-outline"></i>
                                </button>
                            </div>
                            @if ($errors->has('password'))
                                <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="mt-4 d-grid">
                            <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection



