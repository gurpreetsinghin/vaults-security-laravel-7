@extends('project-security::layouts.admin')
@section('content')

@inject('common', 'Gurpreetsinghin\VaultsSecurity\Services\CommonService')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-align-justify"></i> Live Traffic</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Live Traffic</li>
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
                <div class="card card-dark collapsed-card">
                    <div class="card-header" data-card-widget="collapse">
                        <h3 class="card-title"><i class="fas fa-cogs"></i> Settings</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="display: none;">
                        <form method="post" action="{{ route('ps.admin.live-traffic.change-status') }}">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">Live Traffic - Monitoring</label><br>
                                <input type="checkbox" name="live_traffic" value="1" class="psec-switch" {{ $setting->live_traffic == 1 ? 'checked' : '' }}></small></span>
                                <br><i><b>Note:</b> This module can have a small impact on the performance on some
                                    websites.</i><br>
                            </div>
                            <hr>
                            <button class="btn btn-flat btn-block btn-primary" type="submit">Save</button>
                        </form>
                    </div>
                </div>
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Live Traffic</h3>
                    </div>
                    <div class="card-body">
                        <center><a href="javascript:window.location.href=window.location.href" class="btn btn-flat btn-primary"><i class="fas fa-sync-alt"></i> Refresh</a>
                        <a href="{{ route('ps.admin.live-traffic.delete-all') }}"
                                class="btn btn-flat btn-danger" title="Delete all logs"><i class="fas fa-trash"></i>
                                Delete All</a></center>

                        <table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-address-card"></i> IP Address</th>
                                    <th><i class="fas fa-map"></i> Country</th>
                                    <th><i class="fas fa-globe"></i> Browser</th>
                                    <th><i class="fas fa-desktop"></i> OS</th>
                                    <th><i class="fas fa-mobile-alt"></i> Device Type</th>
                                    <th><i class="fas fa-link"></i> Page</th>
                                    <th><i class="far fa-calendar-alt"></i> Date & Time</th>
                                    <th><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($traffic as $data)
                                <tr>
                                    <td>
                                        {{ $data->ip }}
                                        @if($data->bot == 1)
                                        <span class="badge badge-primary">Bot</span>
                                        @endif
                                    </td>
                                    <td><img src="{{ asset('vendor/gurpreetsinghin/vaults-security/admin/plugins/flags/blank.png') }}"
                                            class="flag flag-{{ strtolower($data->country_code) }}"
                                            alt="{{ $data->country }}" /> {{ $data->country }}</td>
                                    <td><img
                                            src="{{ url('/vendor/gurpreetsinghin/vaults-security/admin/img/icons/browser/') }}/{{ $data->browser_code }}.png" />
                                        {{ $data->browser }}</td>
                                    <td><img
                                            src="{{ url('/vendor/gurpreetsinghin/vaults-security/admin/img/icons/os/') }}/{{ $data->os_code }}.png" />
                                        {{ $data->os }}</td>
                                    <td>{{ $data->device_type }}</td>
                                    <td>{{ $data->request_uri }}</td>
                                    <td>{{ $data->date.' at '.$data->time }}</td>
                                    <td>
                                        <a href="{{ route('ps.admin.live-traffic.view', $data->id) }}"
                                            class="btn btn-flat btn-primary"><i class="fas fa-tasks"></i> Details</a>
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

<script>

var elems = Array.prototype.slice.call(document.querySelectorAll('.psec-switch'));

elems.forEach(function(html) {
  var switchery = new Switchery(html);
});
</script>

@endsection