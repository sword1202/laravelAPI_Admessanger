@extends('samples')
@section('content')

<div class="container-fluid">
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-6 col-lg-6">
        <div class="card text-white bg-primary">
          <div class="card-body pb-0">
            <h4 class="mb-0">{{ $getUserDetailsCount }}</h4>
            <p>Total User</p>
          </div>
          <div class="chart-wrapper px-3" style="height:70px;">
            <canvas id="" class="chart" height="70"></canvas>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-6">
        <div class="card text-white bg-info">
          <div class="card-body pb-0">
            <h4 class="mb-0">{{$getredeemdetailsCount}}</h4>
            <p>Redeem Request</p>
          </div>
          <div class="chart-wrapper px-3" style="height:70px;">
            <canvas id="" class="chart" height="70"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<!-- /.conainer-fluid -->

@section('myscript')
  <script src="{{ secure_asset('js/views/main.js') }}"></script>
@endsection