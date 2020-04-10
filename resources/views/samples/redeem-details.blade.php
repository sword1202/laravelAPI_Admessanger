@extends('samples')
@section('content')
<style type="text/css">
  #paymenyloading
  {
      display : none;
  }

  #paymenyloading.show
  {
      display : block;
      position : fixed;
      z-index: 100;
      background-image : url('https://admessengerappbackend.com/img/load.gif');
      background-color:#fff;
      opacity : 1;
      background-repeat : no-repeat;
      background-position : center;
      left : 0;
      bottom : 0;
      right : 0;
      top : 0;
  }
</style>
<div id="myDiv">
  <img src="https://admessengerappbackend.com/img/loadingimg.gif" id="loaderimages" style="display:none;">
</div>

<div id="paymenyloading"></div>
<div class="container-fluid">
  <div class="animated fadeIn">
  	<table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
          <tr>
              <th>Id</th>
              <th>Username</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Transaction Date</th>
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
        <h4 class="modal-title">Pay Amount Confirmation</h4>
        <button type="button" class="close" id="dontclose" data-dismiss="modal">&times;</button>
      </div>

        <div class="modal-body">
          <p style="text-align: center;font-size: 18px;font-weight: bold;">Are you sure to Redeem the Amount?</p>
          <input type="hidden" name="get_usr_id" id="userid" value="">
          <input type="hidden" name="get_req_amount" id="useramount" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="clickok" onclick="payamount();">Confirm</button>
          <button type="button" class="btn btn-default" id="cancelok"  data-dismiss="modal">Close</button>
        </div>
    </div>

  </div>
</div>

<script src="{{ secure_asset('js/vendor/jquery.min.js') }}"></script>
<script type="text/javascript">

  $(document).ready(function(){

  //$('#example').DataTable();
  url = "https://admessengerappbackend.com";
  $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": 
        {
          "url": url + '/getdata',
          "dataType": "json",
          "type": "POST",
          "data":{ _token: "{{csrf_token()}}"}
        },
        "columns":[
            { "data": "id" },
            { "data": "username" },
            { "data": "amount" },
            { "data": "status" },
            { "data": "action" },
        ]
     });  
  });

  function myFunction (identifier)
  {
    var userids        = $(identifier).data('user-id');      
    var requestamount  = $(identifier).data('requ-amount');

    if (userids != '' && requestamount != '') 
      {
        $('#userid').val(userids);
        $('#useramount').val(requestamount);
        $('#myModal').modal(
          {
            backdrop: 'static',
            keyboard: false,
          })
      }
  }

  function payamount(amount) 
  {
    
    var get_usr_id = $('#userid').val();
    var get_req_amount = $('#useramount').val();
  
    url      = "https://admessengerappbackend.com";
    token_id = "{!! csrf_token() !!}";   
    $.ajax(
    {
      type: "POST",
      url: url + '/pay-redeem-amount',
      data: {
          '_token'      : token_id,
          'userid'      : get_usr_id,   
          'redeemAmount': get_req_amount,      
         },
        beforeSend: function()
        {  
          $("#loading-image").show();
          $("div#paymenyloading").addClass('show');
          $('#clickok').attr('disabled', true);
          $('#cancelok').attr('disabled', true);
          $('#dontclose').attr('disabled', true);
        },
        success: function(data)
        {       
          //console.log(data);  
          if(data.success)
          {
            window.location = "{{ url('/redeem-details') }}";
          }
        }
    });
         
  }


</script>
@endsection