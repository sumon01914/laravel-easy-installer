<form action="{{ route('installer.application.process') }}" method="POST" name="databaseSettingForm" id="databaseSettingForm">
<input type="hidden" name="step" value="3" id="step"/>  
<div class="row">
	<div class="col-sm-6 col-lg-6 col-md-6">
		<div class="form-group ">
			<label for="pwd">Database Host Name</label> <small class="req"> *</small>
			<input type="text" class="form-control" value="{{session('step3.DatabaseHostName')}}" name="DatabaseHostName" id="DatabaseHostName" placeholder="i.e localhost">
			<div class="message">
				<span id="checking-text"></span>
				<span id="message-text"></span>
			</div>
		</div>
	</div>
</div> 
<div class="row">
	<div class="col-sm-6 col-lg-6 col-md-6">
		<div class="form-group ">
			<label for="pwd">Port Number</label> <small class="req"> *</small>
			<input type="number" class="form-control" name="PortNumber" id="PortNumber" placeholder="" value="{{ session('step3.PortNumber') ? session('step3.PortNumber') : 3306 }}" min="3000" max="5000">
			<div class="message">
				<span id="checking-text"></span>
				<span id="message-text"></span>
			</div>
		</div>
	</div>
</div> 
<div class="row">
	<div class="col-sm-6 col-lg-6 col-md-6">
		<div class="form-group ">
			<label for="pwd">Database User Name</label> <small class="req"> *</small>
			<input type="text" class="form-control" value="{{session('step3.DatabaseUserName')}}" name="DatabaseUserName" id="DatabaseUserName" placeholder="i.e root" >
			<div class="message">
				<span id="checking-text"></span>
				<span id="message-text"></span>
			</div>
		</div>
	</div>
</div> 
<div class="row">
	<div class="col-sm-6 col-lg-6 col-md-6">
		<div class="form-group ">
			<label for="pwd">Database Password</label> <small class="req"> *</small>
			<input type="text" class="form-control" name="DatabasePassword" id="DatabasePassword" placeholder="" >
			<div class="message">
				<span id="checking-text"></span>
				<span id="message-text"></span>
			</div>
		</div>
	</div>
</div> 
<div class="row">
	<div class="col-sm-6 col-lg-6 col-md-6">
		<div class="form-group ">
			<label for="pwd">Database Name</label> <small class="req"> *</small>
			<input type="text" class="form-control" value="{{session('step3.DatabaseName')}}" name="DatabaseName" id="DatabaseName" placeholder="" >
			<div class="message">
				<span id="checking-text"></span>
				<span id="message-text"></span>
			</div>
		</div>
	</div>
</div> 
<div class="row">
	<div class="col-sm-6 col-lg-6 col-md-6">
		<div class="form-group ">
			<label for="pwd">Socket Name</label> <small class="req"> *</small>
			<input type="text" class="form-control" value="/tmp/mysql.sock" name="SocketName" id="SocketName" placeholder="" >
			<div class="message">
				<span id="checking-text"></span>
				<span id="message-text"></span>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6 col-lg-6 col-md-6">
		<button onclick="loadForm(2)" type="button" class="btn btn-warning"><i class="fas fa-angle-double-left"> </i>Back</button>
		<button type="submit" class="btn btn-primary"><i class="fas fa-angle-double-right"></i> Next</button>
	</div>
</div>
</form>