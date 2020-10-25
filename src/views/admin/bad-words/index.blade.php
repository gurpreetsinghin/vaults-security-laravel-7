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

                @if($words->count() > 0)
                <div class="card card-solid card-success">
                @else
                <div class="card card-solid card-primary">
                @endif

                <div class="card-header">
                    <h3 class="card-title">Bad Words - Protection Module</h3>
                </div>
                <div class="card-body jumbotron">
                    @if($words->count() > 0)
                    <h1 style="color: #47A447;"><i class="fas fa-check-circle"></i> Enabled</h1>
                    <p>The bad words are <strong>Filtered</strong></p>
                    @else
                    <h1 style="color: #007bff;"><i class="fas fa-times-circle"></i> Disabled</h1>
                    <p>The bad words are not <strong>Filtered</strong></p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bad Words</h3>
                </div>
                <div class="card-body">

                    <form action="{{ route('ps.admin.bad-words.replace') }}" method="post" class="form-horizontal form-bordered">
                        @csrf
                        <div class="form-group">
                            <label class="control-label"><i class="fas fa-pen-square"></i> Replacement Word</label>
                            <input type="text" name="badword_replace" value="{{ Auth::user()->badword_replace }}" class="form-control">
                        </div>

                        <button type="submit" class="mb-xs mt-xs mr-xs btn btn-flat btn-success btn-md btn-block"><i class="fas fa-save"></i>&nbsp;&nbsp;Save</button>
                    </form>

                    <hr />

                    @if ($errors->has('word'))
                    <div class="alert alert-danger">{{ $errors->first('word') }}</div>
                    @endif

                    <center><button data-target="#add" data-toggle="modal" class="btn btn-flat btn-primary btn-md"><i
                                class="fas fa-plus-circle"></i> Add Bad Word</button></center>
                    <br />

                    <form action="{{ route('ps.admin.bad-words.add') }}" class="form-horizontal mb-lg" method="POST">
                        @csrf
                        <div class="modal fade" id="add" role="dialog" tabindex="-1" aria-labelledby="add"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark">
                                        <h6 class="modal-title">Add Bad Word</h6>
                                        <button data-dismiss="modal" class="close" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="control-label">Bad Word:</label>
                                            <input type="text" class="form-control" name="word" required />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input class="btn btn-block btn-flat btn-primary" type="submit" value="Add">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th>Bad Word</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($words as $word)
                            <tr>
                                <td>{{ $word->word }}</td>
                                <td>
                                <a href="{{ route('ps.admin.bad-words.delete', $word->id) }}" class="btn btn-flat btn-danger"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> About Bad Words Filtering</h3>
                </div>
                <div class="card-body">
                    This module can be used to censore (hide, replace) bad words, links and sentences.
                    <br /><br />
                    If there are no bad words added the module is automatically disabled.
                    <br /><br />
                    The module is working in two ways:
                    <ul>
                        <li>Filtering bad words in real-time before Output (Page Rendering)</li>
                        <li>Filtering bad words after POST data is submitted</li>
                    </ul>
                    <strong>Replacement Word</strong> - Text (Word) that will be displayed instead the bad word
                </div>
            </div>
        </div>

    </div>

</div>
</div>
<!--===================================================-->
<!--End page content-->


@endsection