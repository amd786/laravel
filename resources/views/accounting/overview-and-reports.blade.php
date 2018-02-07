@extends('layouts.app')

@section('content')
@section('title', 'Accounting Overview')
@include('accounting.sub_head')
<div class="container">
  <div class="panel-body">
    <div class="col-md-12 fs18 marginB40">
      <div class="bgGrey clearfix">
        <div class="col-md-6">
          <h4>Estimated Income for This Month (Sales): <strong>${{ round(App\Library\Functions::getEstimatedIncome(),2) }}</strong></h4>
        </div>
        <div class="col-md-6 text-right">
          <h4>Estimated Expenses for This Month (Suppliers): <strong>(${{ round(App\Library\Functions::getEstimatedExpense(),2) }})</strong></h4>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="col-md-7">
          <h4>Net Income</h4>
          <div id="area_chart" style="width:100%; height:350px;" class="marginB40 marginT30"></div>
          <h4>Expenses</h4>
          <div id="pie_chart" style="width:75%; height:350px;" class="marginT30"></div>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-4">
          @if(count($upcoming_payments)>0)
          <h4>Upcoming Payments</h4>
          <div id="upcoming_payments" class="">
          @foreach($upcoming_payments as $key=>$upcoming_payment)
            {{--*/
              if($key>0){
                if($last_date==$upcoming_payment->payment_date){
                  $display_date = "";
                }else{
                  $display_date = $upcoming_payment->payment_date;
                }
              }else{
                $display_date = $upcoming_payment->payment_date;
              }
            /*--}}
            <div class="col-sm-12 no_padding">
              <div class="col-sm-4 text-center fs20 padT10">
                <strong>{{ !empty($display_date) ? date('M j',strtotime($display_date)) :""}}</strong>
              </div>
              <div class="col-sm-8 fs18">
                <p class="no_margin {{$upcoming_payment->action}}">{{ $upcoming_payment->action=='expense' ? "(" : ""}}${{round($upcoming_payment->amount,2)}}{{ $upcoming_payment->action=='expense' ? ")" : ""}}</p>
                <p class="no_margin">
                  @if($upcoming_payment->action=='sales')
                  {{ucwords($upcoming_payment->Company->company_name)}}
                  @elseif($upcoming_payment->action=='expense')
                  {{ucwords($upcoming_payment->getSupplierDetail->supplier_name)}}
                  @endif
                </p>
                <hr class="no_margin marginTB10 group_by"></hr>
              </div>
            </div>
            {{--*/ $last_date = $upcoming_payment->payment_date /*--}}
          @endforeach
          </div>
          @endif
          <div class="col-md-12 padT40 text-center">
            <a class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" href="{{ action ('AccountingController@PaymentSchedule') }}">See Payment Calendar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- arae chart-->
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var chart_data = <?= $area_chart; ?>;
    var data = google.visualization.arrayToDataTable(chart_data);
    var options = {
      title: 'Monthly Revenue',
      legend: { position: 'top'},
      hAxis: {title: 'Months',  titleTextStyle: {color: '#333'}},
      vAxis: {minValue: 0},
      colors: ['#008000','#FF0000'],
      series: {
        0: { pointSize: 10,pointShape: 'circle' },
        1: { pointSize: 5,pointShape: 'circle' },
      }
    };
    var chart = new google.visualization.AreaChart(document.getElementById('area_chart'));
    chart.draw(data, options);
  }
</script>

<!-- pie chart-->
<script type="text/javascript">
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var chart_data = <?= $pie_chart; ?>;
    var data = google.visualization.arrayToDataTable(chart_data);
    var options = {
      title: 'Expenses',
      legend: { position: 'top'},
      pieHole: 0.45
    };
    var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
    chart.draw(data, options);
  }
</script>
@endsection
