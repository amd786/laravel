@if(count($upload_results)>0)
<div class="col-md-11 col-md-offset-1">
  <h3>Results</h3>
  <p class="fs20">SKU(s) affected: <b>{{$count['imported']}}</b>&#47;<b>{{$count['total']}}</b></p>
  @foreach($upload_results as $key=>$upload_result)
    <p class="fs16">{{ucfirst($upload_result[0])}}&nbsp;...&nbsp;<b>{{$upload_result[1]}}</b></p>
  @endforeach
  <div class="col-md-12 text-center">
    <a class="btn btn-default btn-success padL30 padR30" href="{{action('ProductController@index')}}">Done</a>
  </div>
</div>
@endif
