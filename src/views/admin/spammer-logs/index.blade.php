@extends('project-security::layouts.admin')
@section('content')

@inject('common', 'Gurpreetsinghin\VaultsSecurity\Services\CommonService')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-align-justify"></i> Spammer Logs</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Spammer Logs</li>
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
                        <h3 class="card-title">Spammer Logs</h3>
                    </div>
                    <div class="card-body">
                        <center><a href="{{ route('ps.admin.spammer-logs.delete-all') }}" class="btn btn-flat btn-danger" title="Delete all logs"><i
                                    class="fas fa-trash"></i> Delete All</a></center>

                        <table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user"></i> IP Address</th>
                                    <th><i class="fas fa-calendar"></i> Date</th>
                                    <th><i class="fas fa-globe"></i> Browser</th>
                                    <th><i class="fas fa-desktop"></i> OS</th>
                                    <th><i class="fas fa-map"></i> Country</th>
                                    <th><i class="fas fa-bomb"></i> Type</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->ip }}</td>
                                    <td>{{ $log->date }}</td>
                                    <td><img src="{{ url('/vendor/gurpreetsinghin/vaults-security/admin/img/icons/browser/') }}/{{ $log->browser_code }}.png" />
                                        {{ $log->browser }}</td>
                                    <td><img src="{{ url('/vendor/gurpreetsinghin/vaults-security/admin/img/icons/os/') }}/{{ $log->os_code }}.png" />
                                        {{ $log->os }}</td>
                                    <td><img src="{{ asset('vendor/gurpreetsinghin/vaults-security/admin/plugins/flags/blank.png') }}"
                                            class="flag flag-{{ strtolower($log->country_code) }}"
                                            alt="{{ $log->country }}" /> {{ $log->country }}</td>
                                    <td>
                                        @if ($log->type == 'SQLi')
                                        <i class="fas fa-code"></i>{{ $log->type }}
                                        @elseif($log->type == 'Proxy')
                                        <i class="fas fa-globe"></i>{{ $log->type }}
                                        @elseif($log->type == 'Spammer')
                                        <i class="fas fa-keyboard"></i>{{ $log->type }}
                                        @else
                                        <i class="fas fa-user-secret"></i>{{ $log->type }}
                                        @endif
                                    </td>
                                    <td>
                                    @if($common->isBanned($log->ip))
                                    <a href="{{ route('ps.admin.spammer-logs.unban-ip', $log->ip) }}" class="btn btn-flat btn-success"><i class="fas fa-ban"></i> Unban</a>
                                    @else
                                    <a href="{{ route('ps.admin.spammer-logs.ban-ip', $log->ip) }}" class="btn btn-flat btn-warning"><i class="fas fa-ban"></i> Ban</a>
                                    @endif
                                    <a href="{{ route('ps.admin.spammer-logs.delete', $log->id) }}" class="btn btn-flat btn-danger"><i class="fas fa-times"></i> Delete</a>
                                    <a href="{{ route('ps.admin.spammer-logs.view', $log->id) }}" class="btn btn-flat btn-primary"><i class="fas fa-tasks"></i> Details</a>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection