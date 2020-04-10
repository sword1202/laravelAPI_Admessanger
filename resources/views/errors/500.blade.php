<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="CoreUI Bootstrap 4 Admin Template">
  <meta name="author" content="Lukasz Holeczek">
  <meta name="keyword" content="CoreUI Bootstrap 4 Admin Template">
  <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->

  <title>CoreUI Bootstrap 4 Admin Template</title>

  <!-- Icons -->
  <link href="{{ secure_asset('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('css/simple-line-icons.css') }}" rel="stylesheet">
  <!-- Main styles for this application -->
  <link href="{{ secure_asset('css/style.css') }}" rel="stylesheet">
  <!-- Styles required by this views -->
  <link href="{{ secure_asset('css/custom.css') }}" rel="stylesheet">

</head>

<body class="app flex-row align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="clearfix">
          <h1 class="float-left display-3 mr-4">500</h1>
          <h4 class="pt-3">Houston, we have a problem!</h4>
          <p class="text-muted">The page you are looking for is temporarily unavailable.</p>
        </div>
        <div class="input-prepend input-group">
          <span class="input-group-addon"><i class="fa fa-search"></i></span>
          <input id="prependedInput" class="form-control" size="16" type="text" placeholder="What are you looking for?">
          <span class="input-group-btn">
            <button class="btn btn-info" type="button">Search</button>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap and necessary plugins -->
  <script src="{{ secure_asset('js/jquery.min.js') }}"></script>
  <script src="{{ secure_asset('js/popper.min.js') }}"></script>
  <script src="{{ secure_asset('js/bootstrap.min.js') }}"></script>
</body>
</html>