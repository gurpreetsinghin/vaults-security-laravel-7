@extends('project-security::layouts.admin')
@section('content')

@inject('common', 'Gurpreetsinghin\VaultsSecurity\Services\CommonService')


<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-align-justify"></i> Visitor Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Visitor Details</li>
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
                                        <i class="fas fa-map-pin"></i> Country Code
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->country_code }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-map"></i> Device Type
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->device_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-cloud"></i> Visited Page
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->request_uri }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-bomb"></i> Bot
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->bot == 1 ? 'Yes' : 'No' }}" readonly>
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

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">
                                        <i class="fas fa-reply"></i> Referer URL
                                    </label>
                                    <input type="text" class="form-control" value="{{ $log->referer }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection