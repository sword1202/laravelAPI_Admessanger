<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Lukasz Holeczek">
    <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->

    <title>AdMessage Backend Admin</title>

    <!-- Icons -->
    <link href="{{ secure_asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/simple-line-icons.css') }}" rel="stylesheet">

    <!-- Main styles for this application -->
    <link href="{{ secure_asset('css/style.css') }}" rel="stylesheet">


</head>

<body class="app flex-row align-items-center">
<div class="wrapper">
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
         
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        <h1>LOG IN</h1>
                        <p class="text-muted">Sign In to your account</p>
                        <form method="POST" action="{{ route('getlogin') }}">

                            {{ csrf_field() }}
                            <div class="input-group mb-3">
                                <span class="input-group-addon"><i class="icon-user"></i></span>
                                <input type="text" name="email" value="{{ old('email') }}" class="form-control" required autofocus placeholder="email">
                            </div>
                            <div class="input-group mb-4">
                                <span class="input-group-addon"><i class="icon-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Password"  required>
                                <input type="hidden" name="user_type" value="2">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary px-4">Login</button>
                                </div>
                               <!--  <div class="col-6 text-right">
                                    <button type="button" class="btn btn-link px-0">Forgot password?</button>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>


                <!-- <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
                    <div class="card-body text-center">
                        <div>
                            <h2>Sign up</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                            <a href="{{ route('register') }}" class="btn btn-primary active mt-3">Register Now!</a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="Reset_Password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop='static' data-keyboard='false' >
  <div class="modal-dialog modal-dialg-nw" role="document">
    <div class="modal-content modal-cnt2">
      <div class="modal-body">
        <div class="signup-frm login-mdl text-center">
          <h3>Reset Password<p class="close"><a href="https://www.admessengerapp.com/">X</a> </p></h3> 
          <div class="form-group">
             <p class="sub-title" id="forget_mail" style="display: none; color: green; font-weight: bold;">Password Successfully Changed. Redirecting to login.</p>
              <span id="pass_values" style="color:red;display:none;font-size: 18px;font-weight: bold;">Please Enter Password</span>
             <span id="pass_value" style="color:red;display:none;font-size: 18px;font-weight: bold;">Please Enter Password atleast Eight characters</span>
             <span id="confirmpass" style="color:red;display:none;font-size: 18px;font-weight: bold;">Password Does Not Match</span>
          </div>
          <div class="form-group">
            <input type="password" class="form-controler" name="password" id="pwds" placeholder="Password" autocomplete="off">
          </div>
          <div class="form-group">
            <input type="password" class="form-controler" name="confirm_password" id="confirmpwd" placeholder="Confirm Password" autocomplete="off">
            <input type="hidden" name="userid" id="userid" value="<?php if(isset($PasswordId)){ echo $PasswordId; } ?>" >
            <input type="hidden" name="useremail" id="useremail" value="<?php if(isset($PasswordEmail)){ echo $PasswordEmail; } ?>">
          </div>
           <button id="ResetButton" class="button2"><span>Change Password</span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap and necessary plugins -->
<script src="{{ secure_asset('js/vendor/jquery.min.js') }}"></script>
<script src="{{ secure_asset('js/vendor/popper.min.js') }}"></script>
<script src="{{ secure_asset('js/vendor/bootstrap.min.js') }}"></script>

<script type="text/javascript">
    $(function() 
    {
      <?php
       if(!empty($PasswordId) && isset($PasswordId))
       { ?>
        $('#Reset_Password').modal('show');
        <?php } ?>
    });

    $('#ResetButton').click(function()
    {
        var pwd         = $('#pwds').val();
        var confirmpwd  = $('#confirmpwd').val(); 
        var userId      = $('#userid').val();
        var useremail   = $('#useremail').val(); 
        $("#pass_value").hide();
        $("#confirmpass").hide();
        if(pwd == '')
        {
          $('#pwds').focus();
          $('#pwds').val('');
          $("#pass_values").show();

          return false;
        }
        else if(pwd.length < 8)
        {
          $('#pwds').focus();
          $('#pwds').val('');
          $("#pass_value").show();
          // $("#pwds").keypress(function()
          // {
          //   $("#pass_value").hide();
          // });
          return false;
        }
        if(pwd != confirmpwd)
        {
          $('#pwds').val('');
          $("#pass_values").hide();
          $('#confirmpwd').val('');
          $("#confirmpass").show();
          
          // $("#pwds").keypress(function()
          // {
          //   $("#confirmpass").hide();
          //   //$("#pass_values").hide();
          // });
          return false;
        }
   
        url = "{!! URL::to('/') !!}";
        token_id = "{!! csrf_token() !!}";  
        $.ajax(
        {
          type: "POST",
          url: url + '/api/v1/user/change-password',
          data: {
            '_token'    : token_id,
            'pwd'       : pwd,
            'userId'    : userId,
            'useremail' : useremail,
             },
            beforeSend: function() 
            {
              $('#ResetButton').attr('disabled', true);
            },
            success: function(data)
            {
              if(data.success == true)
              {
                $('#pwds').val("");
                $('#confirmpwd').val("");
                $('#forget_mail').show();
                setTimeout(function(){
                  // window.location = "{{ url('/') }}?tg=login";
                  window.location = "https://www.admessengerapp.com/";
                  
                }, 1000);
              }
            }
        });
    });
</script>

</body>
</html>