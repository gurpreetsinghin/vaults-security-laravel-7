@extends('project-security::layouts.site')
@section('content')

<br />
<div class="row d-flex justify-content-center">
    <div class="col-lg-10">
        <div class="jumbotron">
            <center>
                <div class="alert alert-danger" style="background-color: #d9534f; color: white;">
                    <h5 class="alert-heading">{!! $page->text !!}</h5>
                </div><br />

                <p style="font-size: 35px;">
                    <span class="fa-stack fa-lg">
                        <i class="fas fa-user fa-stack-1x"></i>
                        <i class="fas fa-ban fa-stack-2x text-danger"></i>
                    </span>
                </p>

                @if (!empty($bans))
                <p>Reason: <strong>{{ $bans->reason }}</strong></p>
                @if ($bans->redirect == 1)
                <br />
                <center>You will be redirected</center><br />
                <meta http-equiv="refresh" content="4;url={{ $bans->url }}">
                @endif
                @endif

                <h6>Please contact with the webmaster of the website if you think something is wrong.</h6>

                <br />
                <a href="mailto:{{ $setting->email }}" class="btn btn-primary btn-block" target="_blank"><i
                        class="fas fa-envelope"></i> Contact</a>

            </center>
        </div>
    </div>
</div>

@endsection