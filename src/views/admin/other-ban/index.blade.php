@extends('project-security::layouts.admin')
@section('content')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-desktop"></i> Other Bans</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Other Bans</li>
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

            <div class="col-md-6">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Ban Browser, OS, ISP or Referrer</h3>
                    </div>
                    <form class="form-horizontal" action="{{ route('ps.admin.other-ban.add') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label class="control-label">Browser, OS, ISP or Referrer: </label>
                                <input name="value" class="form-control" type="text" value="{{ old('value') }}" required>
                                @if ($errors->has('value'))
			                        <label class="error">{{ $errors->first('value') }}</label>
			                    @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Type: </label>
                                <select name="type" class="form-control" required>
                                    <option value="browser" selected>Browser</option>
                                    <option value="os">Operating System</option>
                                    <option value="isp">Internet Service Provider</option>
                                    <option value="referrer">Referrer</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-flat btn-block btn-danger" type="submit">Ban</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Blocked <strong>Internet Service Providers</strong></h3>
                    </div>
                    <div class="card-body">
                        <table id="dt-basic3" class="table table-striped table-bordered table-hover dt-basic" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-cloud"></i> ISP</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bans['isp'] as $isp)
                                <tr>
                                    <td>{{ $isp->value }}</td>
                                    <td>
                                        <a href="{{ route('ps.admin.other-ban.delete', $isp->id) }}"
                                            class="btn btn-flat btn-success"><i class="fas fa-unlock"></i> Unblock</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Blocked <strong>Browsers</strong></h3>
                    </div>
                    <div class="card-body">
                        <table id="dt-basic3" class="table table-striped table-bordered table-hover dt-basic" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-cloud"></i> Browser</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bans['browser'] as $browser)
                                <tr>
                                    <td>{{ $browser->value }}</td>
                                    <td>
                                        <a href="{{ route('ps.admin.other-ban.delete', $browser->id) }}"
                                            class="btn btn-flat btn-success"><i class="fas fa-unlock"></i> Unblock</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Blocked <strong>Operating Systems</strong></h3>
                    </div>
                    <div class="card-body">
                        <table id="dt-basic3" class="table table-striped table-bordered table-hover dt-basic" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-cloud"></i> Operating System</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bans['os'] as $os)
                                <tr>
                                    <td>{{ $os->value }}</td>
                                    <td>
                                        <a href="{{ route('ps.admin.other-ban.delete', $os->id) }}"
                                            class="btn btn-flat btn-success"><i class="fas fa-unlock"></i> Unblock</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Blocked <strong>Referrers</strong></h3>
                    </div>
                    <div class="card-body">
                        <table id="dt-basic3" class="table table-striped table-bordered table-hover dt-basic" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-cloud"></i> Referrer</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bans['referrer'] as $referrer)
                                <tr>
                                    <td>{{ $referrer->value }}</td>
                                    <td>
                                        <a href="{{ route('ps.admin.other-ban.delete', $referrer->id) }}"
                                            class="btn btn-flat btn-success"><i class="fas fa-unlock"></i> Unblock</a>
                                    </td>
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
$(document).ready(function() {
	$('.dt-basic').dataTable( {
		"responsive": true,
		"language": {
			"paginate": {
			  "previous": '<i class="fas fa-angle-left"></i>',
			  "next": '<i class="fas fa-angle-right"></i>'
			}
		}
	} );
});
</script>

@endsection