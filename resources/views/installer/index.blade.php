@extends('main')
@section('content')
@include('installer-layouts.step')
<div class="col-lg-10 col-lg-offset-1" style="padding-top: 50px;">
					<div class="panel panel-info">
                        <div class="panel-heading">
                           Configuration Check
                        </div>
						
<div class="panel-body " id="formDiv">       
 </div>
   </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		loadForm(1,'Configuration Checking')
	});
</script>
@endsection