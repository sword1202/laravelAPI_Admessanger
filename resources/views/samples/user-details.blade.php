@extends('samples')
@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-warning alert-block">
  <button type="button" class="close" data-dismiss="alert">×</button> 
  <strong>{{ $message }}</strong>
</div>
@endif
<div class="container-fluid">
  <div class="animated fadeIn">
  	<table id="example" class="table table-striped table-bordered " style="width:100%">
      <thead>
          <tr>
              <th>Id</th>
              <th>Name</th>
              <th>Email</th>
              <th>Paypal Email</th>
              <th>User Type</th>
              <th>Status</th>
              <th>Action</th>
          </tr>
      </thead>
    </table>
  </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ url('/delete-user') }}" method="post">
        {{ csrf_field() }}
        <div class="modal-body">
          <p style="text-align: center;font-size: 18px;font-weight: bold;">Are you sure want to delete user?</p>
          <input type="hidden" name="userdeleteid" id="userdeleteid" value="">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Confirm</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>

  </div>
</div>

  <script src="{{ secure_asset('js/vendor/jquery.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function() 
    {
      //$('#example').DataTable();

      url = "https://admessengerappbackend.com";
      $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": 
        {
          "url": url + '/getuserdata',
          "dataType": "json",
          "type": "POST",
          "data":{ _token: "{{csrf_token()}}"}
        },
        "columns":[
            { "data": "id" },
            { "data": "name" },
            { "data": "email" },
            { "data": "paypal_email" },
            { "data": "user_type" },
            { "data": "status" },
            { "data": "action" },
        ]
      });
    });

    function fun(id)
    {
      if (id != '') 
      {
        $('#userdeleteid').val(id);
        $('#myModal').modal('show');
 
      }
    }

    $("document").ready(function()
    {
      setTimeout(function()
      {
        $(".alert").remove();
      }, 5000 ); // 5 secs
    });

    
      

  </script>
@endsection