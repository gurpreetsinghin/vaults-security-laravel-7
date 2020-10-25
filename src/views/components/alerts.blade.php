@if(Session::has('warning'))
<div class="alert alert-warning" role="alert"><strong class="text-capitalize">Warning!</strong> {!! Session::get('warning') !!}
<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success" role="alert"><strong class="text-capitalize">Success!</strong> {!! Session::get('success') !!}
<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
@endif

@if(Session::has('error'))
<div class="alert alert-danger" role="alert"><strong class="text-capitalize"></strong> {!! Session::get('error') !!}
<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
@endif