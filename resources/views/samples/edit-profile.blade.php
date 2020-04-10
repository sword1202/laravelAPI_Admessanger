@extends('samples')
@section('content')
<div class="container-fluid">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-md-12">
			  <div class="card">
			    <div class="card-header">
			      <strong>Edit User Details</strong>
			    </div>
			    
			    <form action="{{ url('/update-user-details') }}" method="POST" class="form-horizontal">
				    <div class="card-body">
				      	{{ csrf_field() }}
				        <div class="form-group row">
				          <label class="col-md-3 col-form-label" for="text-input">User Name</label>
				          <div class="col-md-9">
				            <input type="text" id="text-input" name="username" disabled="" class="form-control" placeholder="Text" value="<?php echo $EditUser->name; ?>">
				            <input type="hidden" name="userid" class="form-control" placeholder="Text"  value="<?php echo $EditUser->id; ?>">
				          </div>
				        </div>
				        <div class="form-group row">
				          <label class="col-md-3 col-form-label" for="email-input">User Email</label>
				          <div class="col-md-9">
				            <input type="email" name="user_email" class="form-control" placeholder="Enter Email" value="<?php echo $EditUser->email; ?>">
				          </div>
				        </div>
				        <div class="form-group row">
				          <label class="col-md-3 col-form-label" for="email-input">User Paypal Email</label>
				          <div class="col-md-9">
				            <input type="email" name="paypal_email" class="form-control" placeholder="Enter paypal Email" value="<?php echo $EditUser->paypal_email; ?>">
				          </div>
				        </div>
				        <div class="form-group row">
				          <label class="col-md-3 col-form-label" for="email-input">User Type</label>
				          <div class="col-md-9">

				            <select id="select" name="user_type" class="form-control">
	                        	<option value="2" <?php if ($userType == 'Admin') { ?> selected="selected"<?php } ?> > Admin </option>
	                          	<option value="1" <?php if ($userType == 'User') { ?> selected="selected"<?php } ?>> User </option>
	                        </select>

				          </div>
				        </div>
				        <div class="form-group row">
				          	<label class="col-md-3 col-form-label" for="email-input">User Status</label>
				          	<div class="col-md-9">
		                        <select id="select" name="user_status" class="form-control">
		                        	<option value="1" <?php if ($userStatus == 'Active') { ?> selected="selected"<?php } ?> > Active </option>
		                          	<option value="2" <?php if ($userStatus == 'Inactive') { ?> selected="selected"<?php } ?>> Inactive </option>
		                        </select>                   
				          	</div>
				        </div>
				    </div>
				    <div class="card-footer">
				      <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
				      <a class="btn btn-sm btn-danger" href="{{ URL::previous() }}"><i class="fa fa-ban"></i> Cancel</a>
				      <!-- <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Cancel</button> -->
				    </div>
			    </form>
			  </div>


			</div>
			
		</div>
	</div>
</div>

@endsection