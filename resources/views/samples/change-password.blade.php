@extends('samples')
@section('content')

<div class="container-fluid">
	<div class="animated fadeIn"> 
		
		<div class="row">
			<div class="col-md-12">
				@if(Session::has('success')) 
					<div class="alert alert-info">
					  	{{ Session::get('success') }}
					</div>
				@endif
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
			  <div class="card">
			    <div class="card-header">
			      <strong>Change Password</strong>
			    </div>
			    <form action="{{ url('/update_password') }}" method="POST" class="form-horizontal">			    	
			    		<div class="card-body">
				      	{{ csrf_field() }}
				      </div>
				        <div class="form-group">
				          <label class="col-md-3 col-form-label" for="text-input">New Password</label>
				          <div class="col-md-3">
				            <input type="Password" name="new_password" required="" class="form-control" placeholder="New Password" value="">
				          </div>
				        </div>
				        <div class="form-group">
				          <label class="col-md-3 col-form-label" for="text-input">Confirm Password</label>
				          <div class="col-md-3">
				            <input type="Password" name="confirm_password" required="" class="form-control" placeholder="Confirm Password" value="">
				          </div>
				        </div>
				       <!--  <div class="form-group row">
				          <label class="col-md-3 col-form-label" for="text-input">Confirm New Password</label>
				          <div class="col-md-3">
				            <input type="Password" name="confirm_new_password" required="" class="form-control" placeholder="Confirm New Password" value="">
				          </div>
				        </div>  -->
				        <!-- 
				        <div class="form-group row">
				          <label class="col-md-3 col-form-label" for="email-input">Confirm Password</label>
				          <div class="col-md-9">
				            <input type="password" class="form-control" placeholder="Confirm Password" value="">
				          </div>
				        </div> -->
				      	<input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
				        
				    </div>
				    <div class="card-footer">
				      <button type="submit" id="newpassword" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
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