@extends('project-security::layouts.admin')
@section('content')


<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-flag"></i> Warning Pages</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i>
                            Admin Panel</a></li>
                    <li class="breadcrumb-item active">Warning Pages</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!--Page content-->
<!--===================================================-->
<div class="content">
    <div class="container-fluid">
        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title">Warning Pages</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <ul class="nav flex-column nav-pills">
                            <li class="nav-item active">
                                <a class="nav-link active" data-toggle="tab" href="#sqli-layout"><i
                                        class="fas fa-bug"></i> SQL Injection</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#proxy-layout"><i class="fas fa-globe"></i>
                                    Proxy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#spam-layout"><i
                                        class="fas fa-keyboard"></i> Spam</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#banned-layout"><i class="fas fa-ban"></i>
                                    Banned IP</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#bannedc-layout"><i class="fas fa-flag"></i>
                                    Banned Country</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#bannedbr-layout"><i
                                        class="far fa-window-maximize"></i> Blocked Browser</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#bannedos-layout"><i
                                        class="fas fa-tablet"></i> Blocked OS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#bannedisp-layout"><i
                                        class="fas fa-wifi"></i> Blocked ISP</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#bannedrfr-layout"><i
                                        class="fas fa-link"></i> Blocked Referrer</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#adblocker-layout"><i
                                        class="fas fa-window-maximize"></i> AdBlocker</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-9">
                        <form action="{{ route('ps.admin.warning-pages') }}" method="post">
                            @csrf
                            <div class="tab-content">

                                <div id="sqli-layout" class="tab-pane fade active show">
                                    <fieldset>	  
                                        <center>
                                            <label><i class="fas fa-file-alt"></i> Page Text:</label>
                                            <input type="hidden" name="pages[0][page]" value="Blocked">
                                            <textarea name="pages[0][text]" class="form-control" rows="5" type="text" required>{{ $pages['Blocked']->text }}</textarea>
                                        </center>
                                    </fieldset>
                                </div>

                                <div id="proxy-layout" class="tab-pane fade">
                                    <fieldset>	  
                                        <center>
                                            <label><i class="fas fa-file-alt"></i> Page Text:</label>
                                            <input type="hidden" name="pages[1][page]" value="Proxy">
                                            <textarea name="pages[1][text]" class="form-control" rows="5" type="text" required>{{ $pages['Proxy']->text }}</textarea>
                                        </center>
                                    </fieldset>
                                </div>

                                <div id="spam-layout" class="tab-pane fade">
                                    <fieldset>	  
                                        <center>
                                            <label><i class="fas fa-file-alt"></i> Page Text:</label>
                                            <input type="hidden" name="pages[2][page]" value="Spam">
                                            <textarea name="pages[2][text]" class="form-control" rows="5" type="text" required>{{ $pages['Spam']->text }}</textarea>
                                        </center>
                                    </fieldset>
                                </div>

                                <div id="banned-layout" class="tab-pane fade">
                                    <fieldset>	  
                                        <center>
                                            <label><i class="fas fa-file-alt"></i> Page Text:</label>
                                            <input type="hidden" name="pages[3][page]" value="Banned">
                                            <textarea name="pages[3][text]" class="form-control" rows="5" type="text" required>{{ $pages['Banned']->text }}</textarea>
                                        </center>
                                    </fieldset>
                                </div>

                                <div id="bannedc-layout" class="tab-pane fade">
                                    <fieldset>	  
                                        <center>
                                            <label><i class="fas fa-file-alt"></i> Page Text:</label>
                                            <input type="hidden" name="pages[4][page]" value="Banned_Country">
                                            <textarea name="pages[4][text]" class="form-control" rows="5" type="text" required>{{ $pages['Banned_Country']->text }}</textarea>
                                        </center>
                                    </fieldset>
                                </div>

                                <div id="bannedbr-layout" class="tab-pane fade">
                                    <fieldset>	  
                                        <center>
                                            <label><i class="fas fa-file-alt"></i> Page Text:</label>
                                            <input type="hidden" name="pages[5][page]" value="Blocked_Browser">
                                            <textarea name="pages[5][text]" class="form-control" rows="5" type="text" required>{{ $pages['Blocked_Browser']->text }}</textarea>
                                        </center>
                                    </fieldset>
                                </div>

                                <div id="bannedos-layout" class="tab-pane fade">
                                    <fieldset>	  
                                        <center>
                                            <label><i class="fas fa-file-alt"></i> Page Text:</label>
                                            <input type="hidden" name="pages[6][page]" value="Blocked_OS">
                                            <textarea name="pages[6][text]" class="form-control" rows="5" type="text" required>{{ $pages['Blocked_OS']->text }}</textarea>
                                        </center>
                                    </fieldset>
                                </div>

                                <div id="bannedisp-layout" class="tab-pane fade">
                                    <fieldset>	  
                                        <center>
                                            <label><i class="fas fa-file-alt"></i> Page Text:</label>
                                            <input type="hidden" name="pages[7][page]" value="Blocked_ISP">
                                            <textarea name="pages[7][text]" class="form-control" rows="5" type="text" required>{{ $pages['Blocked_ISP']->text }}</textarea>
                                        </center>
                                    </fieldset>
                                </div>

                                <div id="bannedrfr-layout" class="tab-pane fade">
                                    <fieldset>	  
                                        <center>
                                            <label><i class="fas fa-file-alt"></i> Page Text:</label>
                                            <input type="hidden" name="pages[8][page]" value="Blocked_RFR">
                                            <textarea name="pages[8][text]" class="form-control" rows="5" type="text" required>{{ $pages['Blocked_RFR']->text }}</textarea>
                                        </center>
                                    </fieldset>
                                </div>

                                <div id="adblocker-layout" class="tab-pane fade">
                                    <fieldset>	  
                                        <center>
                                            <label><i class="fas fa-file-alt"></i> Page Text:</label>
                                            <input type="hidden" name="pages[9][page]" value="AdBlocker">
                                            <textarea name="pages[9][text]" class="form-control" rows="5" type="text" required>{{ $pages['AdBlocker']->text }}</textarea>
                                        </center>
                                    </fieldset>
                                </div>
                            </div>
                            <br />
                            <input type="submit" class="btn btn-flat btn-success btn-md btn-block" value="Save" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection