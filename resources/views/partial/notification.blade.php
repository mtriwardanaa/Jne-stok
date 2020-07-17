@if($errors->any())
  	<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:20px">
   		<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
   		<strong>Error!</strong> <br/>
   		@foreach($errors->all() as $e)
    		- {{$e}} <br/>
   		@endforeach
 	</div>
@endif

@if(Session::has('error'))
  	<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:20px">
   		<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
   		<strong>Error!</strong> <br/>
   		@foreach(Session::get('error') as $s)
     		- {{$s}} <br/>
   		@endforeach
 	</div>
	<?php Session::forget('error'); ?>
@endif

@if(Session::has('success'))
  	<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:20px">
   		<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
   		<strong>Success!</strong><br/>
   		@foreach(Session::get('success') as $s)
     		- {{$s}} <br/>
   		@endforeach
 	</div>
 	<?php Session::forget('success'); ?>
@endif

@if(Session::has('warning'))
  	<div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top:20px">
   		<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
    	<strong>Warning!</strong> <br/>
    	@foreach(Session::get('warning') as $w)
        	- {{$w}} <br/>
    	@endforeach
 	</div>
 	<?php Session::forget('warning'); ?>
@endif
