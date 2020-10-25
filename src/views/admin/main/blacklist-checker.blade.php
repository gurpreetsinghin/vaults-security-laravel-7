@extends('project-security::layouts.admin')
@section('content')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-list"></i> IP Blacklist Checker</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">IP Blacklist Checker</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<!--Page content-->
<!--===================================================-->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-search"></i> IP Blacklist Checker</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->has('ip'))
                            <div class="alert alert-danger">{{ $errors->first('ip') }}</div>
                        @endif
                        <form method="post" action="{{ route('ps.admin.blacklist-checker') }}">
                            @csrf
                            IP Address:
                            <input type="text" class="form-control" name="ip" placeholder="1.2.3.4" value="{{ request()->ip }}" required /><br />
                            <input type="submit" class="btn btn-primary btn-block btn-flat" value="Lookup" />
                        </form>
                    </div>
                </div>

                @if(!empty($data))
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-th-list"></i> Results for <b>{{ request()->ip }}</b>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-database"></i> DNSBL</th>
                                        <th><i class="fas fa-cogs"></i> Reverse IP</th>
                                        <th><i class="fas fa-info-circle"></i> Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['dnsbl_lookup'] as $host)
                                    <tr>
                                        <td>{{ $host }}</td>
                                        <td>{{ $data['reverse_ip'] }}.{{ $host }}</td>
                                        @if (@checkdnsrr($data['reverse_ip'] . "." . $host . ".", "A"))
                                        <td><font class="badge badge-danger" style="font-size: 13px;"><i class="fas fa-times-circle"></i> Listed</font></td></tr>
                                        @php $data['BadCount']++; @endphp
                                        @else
                                        <td><font class="badge badge-success" style="font-size: 13px;"><i class="fas fa-check-circle"></i> Not Listed</font></td></tr>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        Thе IP Address is listed in <b>"{{ $data['BadCount'] }}" blacklists</b> of <b>"{{ $data['AllCount'] }}" total</b><br/>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection