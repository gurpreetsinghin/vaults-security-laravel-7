@extends('project-security::layouts.admin')
@section('content')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-code"></i> Protection Module</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Protection Module</li>
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
                <div class="col-md-8">

                    @if($spam->protection == 1)
                    <div class="card card-solid card-success">
                        @else
                        <div class="card card-solid card-danger">
                            @endif

                            <div class="card-header">
                                <h3 class="card-title">Spam - Protection Module</h3>
                            </div>
                            <div class="card-body jumbotron">

                                @if($spam->protection == 1 )
                                <h1 style="color: #47A447;"><i class="fas fa-check-circle"></i> Enabled</h1>
                                <p>The website is protected from <strong>Spammers</strong></p>
                                @else
                                <h1 style="color: #d2322d;"><i class="fas fa-times-circle"></i> Disabled</h1>
                                <p>The website is not protected from <strong>Spammers</strong></p>
                                @endif

                            </div>
                        </div>

                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-shield-alt"></i> Proxy Detection Methods</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <center><button data-target="#add" data-toggle="modal"
                                                class="btn btn-flat btn-primary btn-md"><i
                                                    class="fas fa-plus-circle"></i> Add
                                                Spam Database (DNSBL)</button>
                                        </center>
                                        <br />

                                        <form action="{{ route('ps.admin.add-db') }}" class="form-horizontal mb-lg" method="POST">
                                            @csrf
                                            <div class="modal fade" id="add" role="dialog" tabindex="-1"
                                                aria-labelledby="add" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-dark">
                                                            <h6 class="modal-title">Add Spam Database (DNSBL)</h6>
                                                            <button data-dismiss="modal" class="close" type="button">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label class="control-label"><i class="fas fa-database"></i>
                                                                    Spam Database (DNSBL):</label>
                                                                <input type="text" class="form-control" name="database"
                                                                    required />
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input class="btn btn-block btn-flat btn-primary" type="submit" value="Add">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="table-responsive">                
<table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><i class="fas fa-database"></i> DNSBL Database</th>
											<th><i class="fas fa-cog"></i> Actions</th>
										</tr>
									</thead>
									<tbody>
                                        @foreach($databases as $db)
										<tr>
                                            <td>{{ $db->database }}</td>
											<td>
                                            <a href="{{ route('ps.admin.delete-db', $db->id) }}" class="btn btn-flat btn-danger"><i class="fas fa-trash"></i> Delete</a>
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

                    <div class="col-md-4">
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-info-circle"></i> What is Spam & DNSBL</h3>
                            </div>
                            <div class="card-body">
                                <strong>Electronic Spamming</strong> is the use of electronic messaging systems to send
                                unsolicited messages (spam), especially advertising, as card card-body bg-light as
                                sending messages repeatedly on the same site.
                                <br /><br />
                                A <strong>DNS-based Blackhole List (DNSBL)</strong> or <strong>Real-time Blackhole List
                                    (RBL)</strong> is a list of IP addresses which are most often used to publish the
                                addresses of computers or networks linked to spamming.<br /><br />

                                All <strong>Blacklists</strong> can be found here: <strong><a
                                        href="https://www.dnsbl.info/dnsbl-list.php"
                                        target="_blank">https://www.dnsbl.info/dnsbl-list.php</a></strong>
                            </div>
                        </div>
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-cogs"></i> Module Settings</h3>
                            </div>
                            <form class="form-horizontal form-bordered" action="{{ route('ps.admin.spam') }}" method="post">
                            <div class="card-body">
                                @csrf
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <p>Protection</p>
                                        <input type="checkbox" name="protection" class="psec-switch"
                                            {{ $spam->protection == 1 ? 'checked' : '' }}/><br />
                                        <span class="text-muted">If this protection module is enabled all threats of this type will be blocked</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>Logging</p>
                                        <input type="checkbox" name="logging" value="1" class="psec-switch"
                                            {{ $spam->logging == 1 ? 'checked' : '' }} /><br />
                                        <span class="text-muted">Logs the detected threats</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>AutoBan</p>
                                        <input type="checkbox" name="autoban" value="1" class="psec-switch"
                                            {{ $spam->autoban == 1 ? 'checked' : '' }} /><br />
                                        <span class="text-muted">Automatically bans the detected threats</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>Mail Notifications</p>
                                        <input type="checkbox" name="mail" value="1" class="psec-switch"
                                            {{ $spam->mail == 1 ? 'checked' : '' }} /><br />
                                        <span class="text-muted">Receive email notifications when threat of this type is
                                            detected</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>Redirect URL</p>
                                        <input name="redirect" class="form-control" type="text"
                                            value="{{ $spam->redirect }}" required>
                                    </li>
                                </ul>
                                
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-flat btn-block btn-primary mar-top" type="submit"><i class="fas fa-save"></i> Save</button>
                            </div>
                            </form>
                        </div>

                    </div>

                </div>

            </div>
        </div>
        <!--===================================================-->
        <!--End page content-->

    </div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
<script>
var elems = Array.prototype.slice.call(document.querySelectorAll('.psec-switch'));

elems.forEach(function(html) {
    var switchery = new Switchery(html);
});

</script>

@endsection