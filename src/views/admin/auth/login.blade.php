@extends('project-security::layouts.admin-login')
@section('content')

<div class="login-box">

    @if(Session::has('error'))
    <div class="alert alert-danger">
        {{ Session::get('error') }}
    </div>
    @endif

    @if(Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif

    <form action="{{ route('ps.admin.login') }}" method="post">
        @csrf
        <div class="login-logo">
            <a href="#"><img src="{{ asset('vendor/gurpreetsinghin/vaults-security/logos/vaults-logo.png') }}" style="width: 80%;"></a>
        </div>

        <div class="card">
            <div class="card-body text-white bg-dark">
                <div class="form-group has-feedback ">
                    <div class="input-group mb-3">
                        <input type="username" name="username" class="form-control " placeholder="Username" required="">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        @if ($errors->has('username'))
                        <span class="error" role="alert">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required="">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" name="signin" class="btn btn-md btn-primary btn-block btn-flat"><i
                                class="fas fa-sign-in-alt"></i>
                            &nbsp;Sign In</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection