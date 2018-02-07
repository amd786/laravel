@if(count($attributes) > 0)
  <?php $count_dd = 1; ?>
  @foreach($attributes as $attribute)
    <div class="col-md-2" style="padding-bottom:15px;">
      <label class="bold">{{ $attribute->attribute_name }}</label><br>
      <select class="form-control attr_dd attr_dd_count_{{$count_dd}}" name="attr[{{ $attribute->id }}]" style="width:100%">
        <?php
          $count_dd++;
          $attr_values = App\Models\AttributeValue::where([['fk_attribute_id',$attribute->id],['status',1]])->get();
        ?>
        @if(count($attr_values) > 0)
          <option value="">Please select</option>
          @foreach($attr_values as $attr_value)
            <option value="{{ $attr_value->id }}">{{ $attr_value->attribute_value }}</option>
          @endforeach
        @endif
      </select>
    </div>
  @endforeach
@endif


