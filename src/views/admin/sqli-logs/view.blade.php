@extends('project-security::layouts.admin')
@section('content')

@inject('common', 'Gurpreetsinghin\VaultsSecurity\Services\CommonService')


<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-align-justify"></i> SQL Injection Log Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">SQL Injection Log Details</li>
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
                        <h3 class="card-title">Details for Log #{{ $log->id }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-user"></i> IP Address
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->ip }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-calendar"></i> Date & Time
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->date }} at {{ $log->time }}"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-globe"></i> Browser
                                    </label>
                                    <div class="input-group mar-btm">
                                        <span class="input-group-addon">
                                            <img
                                                src="{{ url('/vendor/gurpreetsinghin/vaults-security/admin/img/icons/browser/') }}/{{ $log->browser_code }}.png" />
                                        </span>
                                        <input type="text" class="form-control" value="{{ $log->browser }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-desktop"></i> Operating System
                                    </label>
                                    <div class="input-group mar-btm">
                                        <span class="input-group-addon">
                                            <img
                                                src="{{ url('/vendor/gurpreetsinghin/vaults-security/admin/img/icons/os/') }}/{{ $log->os_code }}.png" />
                                        </span>
                                        <input type="text" class="form-control" value="{{ $log->os }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-map"></i> Country
                                    </label>
                                    <div class="input-group mar-btm">
                                        <span class="input-group-addon">
                                            <img src="{{ asset('vendor/gurpreetsinghin/vaults-security/admin/plugins/flags/blank.png') }}"
                                                class="flag flag-{{ strtolower($log->country_code) }}"
                                                alt="{{ $log->country }}" />
                                        </span>
                                        <input type="text" class="form-control" value="{{ $log->country }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-map-pin"></i> Region
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->region }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-map"></i> City
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->city }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-cloud"></i> Internet Service Provider
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->isp }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-bomb"></i> Threat Type
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-ban"></i> Banned
                                    </label>
                                    <input type="text" class="form-control"
                                        value="{{ ($common->isBanned($log->ip)) ? 'Yes' : 'No' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-reply"></i> Referer URL
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->referer_url }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-user-secret"></i> User Agent
                                    </label>
                                    <textarea placeholder="User Agent" rows="2" class="form-control"
                                        readonly>{{ $log->useragent }}</textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-file-alt"></i> Attacked Page
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->page }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-code"></i> Query used for the attack
                                    </label>
                                    <textarea placeholder="Query" rows="2" class="form-control"
                                        readonly>{{ $log->query }}</textarea>
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
                    <div class="card-footer">
                        @if($common->isBanned($log->ip))
                        <a href="{{ route('ps.admin.sqli-logs.unban-ip', $log->ip) }}" class="btn btn-flat btn-success"><i class="fas fa-ban"></i> Unban</a>
                        @else
                        <a href="{{ route('ps.admin.sqli-logs.ban-ip', $log->ip) }}" class="btn btn-flat btn-warning"><i class="fas fa-ban"></i> Ban</a>
                        @endif
                        <a href="{{ route('ps.admin.sqli-logs.delete', $log->id) }}" class="btn btn-flat btn-danger"><i class="fas fa-times"></i> Delete</a>
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
    echo $log->longitude;
?>, <?php
    echo $log->latitude;
?>)
    .transform(
        new OpenLayers.Projection("EPSG:4326"),
        map.getProjectionObject()
    );

var zoom = 18;
var markers = new OpenLayers.Layer.Markers("Markers");

map.addLayer(markers);
markers.addMarker(new OpenLayers.Marker(lonLat));
map.setCenter(lonLat, zoom);
</script>

@endsection