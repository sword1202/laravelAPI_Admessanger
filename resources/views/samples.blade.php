<!--
 * CoreUI - Open Source Bootstrap Admin Template
 * @version v1.0.6
 * @link http://coreui.io
 * Copyright (c) 2017 creativeLabs Łukasz Holeczek
 * @license MIT
 -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" href="{{ secure_asset('img/favicon.png') }}">
  <title>AdMessage Admin Panel</title>

  <!-- Icons -->
  <link href="{{ secure_asset('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('css/simple-line-icons.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('css/datatables.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('css/dataTables.bootstrap4.css') }}" rel="stylesheet">
  <!-- Main styles for this application -->
  <link href="{{ secure_asset('css/style.css') }}" rel="stylesheet">
  <!-- Styles required by this views -->
  <link rel="stylesheet" href="{{ secure_asset('css/custom.css') }}">  
</head>


<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
  @include('core.navbar')
  
  <div class="app-body">
    @include('core.sidebar')
    <!-- Main content -->
    <main class="main">

      <!-- Breadcrumb -->
      @include('core.breadcrumb')

      @yield('content')
      <!-- /.container-fluid -->
    </main>

    @include('core.asidemenu')

  </div>

  @include('core.footer')

  @include('core.scripts')
  @yield('myscript')

</body>
</html>