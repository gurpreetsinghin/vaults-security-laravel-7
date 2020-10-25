@extends('project-security::layouts.admin')
@section('content')
<!--Map-->
<script src="https://openlayers.org/api/OpenLayers.js"></script>
<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-lock"></i> IP Lookup</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">IP Lookup</li>
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
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> IP Details - {{ $ip }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-map"></i> Country
                                    </label>
                                    <div class="input-group mar-btm">
                                        <span class="input-group-addon">
                                            <img src="{{ asset('vendor/gurpreetsinghin/vaults-security/admin/plugins/flags/blank.png') }}"
                                                class="flag flag-{{ strtolower($data['countrycode']) }}"
                                                alt="{{ $data['country'] }}" />
                                        </span>
                                        <input type="text" class="form-control" value="{{ $data['country'] }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-map-pin"></i> Region
                                    </label>
                                    <input type="text" class="form-control" value="{{ $data['region'] }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-map"></i> City
                                    </label>
                                    <input type="text" class="form-control" value="{{ $data['city'] }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-cloud"></i> Internet Service Provider
                                    </label>
                                    <input type="text" class="form-control" value="{{ $data['isp'] }}" readonly>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <label class="control-label">
                            <i class="fas fa-location-arrow"></i> Possible Location
                        </label>
                        <center>
                            <div id="mapdiv" style="width: 99%; height:450px"></div>
                        </center>
                    </div>
                </div>

                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-align-justify"></i> Log Search</h3>
                    </div>
                    <div class="card-body">
                        @if($logs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-list-ol"></i> ID</th>
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
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->ip }}</td>
                                        <td>{{ $log->date }}</td>
                                        <td><img
                                                src="{{ url('/vendor/gurpreetsinghin/vaults-security/admin/img/icons/browser/') }}/{{ $log->browser_code }}.png" />
                                            {{ $log->browser }}</td>
                                        <td><img
                                                src="{{ url('/vendor/gurpreetsinghin/vaults-security/admin/img/icons/os/') }}/{{ $log->os_code }}.png" />
                                            {{ $log->os }}</td>
                                        <td><img src="{{ asset('vendor/gurpreetsinghin/vaults-security/admin/plugins/flags/blank.png') }}"
                                                class="flag flag-{{ strtolower($log->country_code) }}"
                                                alt="{{ $log->country }}" /> {{ $log->country }}</td>
                                        <td><i class="fas fa-user-secret"></i>{{ $log->type }}</td>
                                        <td>
                                            @if($common->isBanned($log->ip))
                                            <a href="{{ route('ps.admin.unban-ip', $log->ip) }}"
                                                class="btn btn-flat btn-success"><i class="fas fa-ban"></i> Unban</a>
                                            @else
                                            <a href="{{ route('ps.admin.ban-ip', $log->ip) }}"
                                                class="btn btn-flat btn-warning"><i class="fas fa-ban"></i> Ban</a>
                                            @endif
                                            <a href="{{ route('ps.admin.view', $log->id) }}"
                                                class="btn btn-flat btn-primary"><i class="fas fa-tasks"></i>
                                                Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-dismissible alert-info">
                            <strong>No results found for this IP Address</strong>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-ban"></i> Ban Search</h3>
                    </div>
                    <div class="card-body">
                        @if($bans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-list-ul"></i> ID</th>
                                        <th><i class="fas fa-user"></i> IP Address</th>
                                        <th><i class="fas fa-calendar"></i> Banned On</th>
                                        <th><i class="fas fa-share"></i> Redirect</th>
                                        <th><i class="fas fa-magic"></i> Autobanned</th>
                                        <th><i class="fas fa-cog"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bans as $ban)
                                    <tr>
                                        <td>{{ $ban->id }}</td>
                                        <td>{{ $ban->ip }}</td>
                                        <td>{{ $ban->date }}</td>
                                        <td>{{ $ban->redirect == 1 ? 'Yes' : 'No' }}</td>
                                        <td>{{ $ban->autoban == 1 ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <a href="{{ route('ps.admin.ip-ban.edit', $ban->id) }}"
                                                class="btn btn-flat btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="{{ route('ps.admin.ip-ban.delete', $ban->id) }}"
                                                class="btn btn-flat btn-success"><i class="fas fa-trash"></i> Unban</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-dismissible alert-info">
                            <strong>No results found for this IP Address</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

    var lonLat = new OpenLayers.LonLat(<?php
    echo $data['longitude'];
?>,<?php
    echo $data['latitude'];
?>)
          .transform(
            new OpenLayers.Projection("EPSG:4326"),
            map.getProjectionObject()
          );
          
    var zoom=18;
    var markers = new OpenLayers.Layer.Markers( "Markers" );
	
    map.addLayer(markers);
    markers.addMarker(new OpenLayers.Marker(lonLat));
    map.setCenter (lonLat, zoom);
</script>

@endsection