@if(count($records)>0)
{{--*/ $i = 0; /*--}}
<table class="table-bordered table-striped no-table-bg fs16 width100 marginB10">
  <thead>
    <tr>
      <th width="25%">
        <b>Product Name</b> (Product Sku)
      </th>
      <th width="25%">
        <b>Product Name</b> (Product Sku)
      </th>
      <th width="25%">
        <b>Product Name</b> (Product Sku)
      </th>
      <th width="25%">
        <b>Product Name</b> (Product Sku)
      </th>
    </tr>
  </thead>
  <tbody>
    <tr>
    @foreach($records as $record)
      <td class="pad8"><b>{{$record['name']}}</b>&nbsp;&nbsp;({{$record['sku']}})</td>
      {{--*/
        $i++;
        if($i % 4 == 0){
          echo '</tr><tr>';
        }
      /*--}}
    @endforeach
    </tr>
  </tbody>
</table>
@endif
