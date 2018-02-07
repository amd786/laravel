@if(count($transactions)>0)
  @foreach($transactions as $transaction)
    <h3 class="text-left">{{App\Library\Functions::date_format2($transaction->created_at)}}</h3>
    <div class="table-responsive">
      <table class="table-bordered no-table-bg width100 table-striped">
        <thead>
          <tr>
            <th width="20%">
              Date
            </th>
            <th width="20%">
              Account
            </th>
            <th width="20%">
              Type/Category
            </th>
            <th width="20%">
              Amount
            </th>
            <th width="20%">
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          @if(count($transaction->getTransactionsByDate($transaction->created_at,$fk_account_id,$date_filter,$start_date,$end_date,$search))>0)
            @foreach($transaction->getTransactionsByDate($transaction->created_at,$fk_account_id,$date_filter,$start_date,$end_date,$search) as $date_transaction)
              <tr>
                <td class="pad8 text-left">
                  {{App\Library\Functions::customDateFormat($date_transaction->created_at)}}
                </td>
                <td class="pad8 text-left">
                  @if($date_transaction->getAccountDetail->fk_supplier_id>0)
                  {{ucwords($date_transaction->getAccountDetail->getSupplierDetail->supplier_name)}}
                  @elseif($date_transaction->getAccountDetail->fk_company_id>0)
                  {{ucwords($date_transaction->getAccountDetail->getCompanyDetail->company_name)}}
                  @else
                  {{ucwords($date_transaction->getAccountDetail->getAccountDetail->name)}}
                  @endif
                </td>
                <td class="pad8 text-left">
                  {{$date_transaction->getAccountDetail->getAccountCategoryDetail->category}}
                </td>
                <td class="pad8 text-left">
                  (${{round($date_transaction->amount,2)}})
                </td>
                <td class="pad8 text-left">
                  <div class="col-md-12 no_padding">
                    <img src="{{ url('/img/action-icon.png') }}" alt="">
                  </div>
                </td>
              </tr>
            @endforeach
            @else
            <tr>
              <td colspan="5" class="text-center pad5">No record found.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  @endforeach
  @else
  <div class="table-responsive">
    <table class="table table-bordered no-table-bg width100 table-striped">
      <thead>
        <tr>
          <th width="20%">
            Date
          </th>
          <th width="20%">
            Account
          </th>
          <th width="20%">
            Type/Category
          </th>
          <th width="20%">
            Amount
          </th>
          <th width="20%">
            Actions
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="5" class="text-center pad5">No record found.</td>
        </tr>
      </tbody>
    </table>
  </div>
@endif
<div class="col-md-12 text-right no_padding">
  @if(count($transactions)>0)
    {{  $transactions->render() }}
  @endif
</div>
<script>
$('.pagination a').on('click', function(e){
    e.preventDefault();
    var holder  = $(this).closest('.transaction_table_holder').attr('id');
    if(holder=='transaction_table_holder'){
      var search = $('#search_transactions').val();
      var fk_account_id = $('#fk_account_id').val();
      var start_date = $('#start_date').val();
      var end_date = $('#end_date').val();
      var date_filter = $('#quick_date').val();
      var url = $(this).attr('href')+'&fk_account_id='+fk_account_id+'&start_date'+start_date+'&end_date='+end_date+'&date_filter='+date_filter+'&search='+search;
      $.ajax({
        url :url,
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success:function(data){
          $("body").removeClass("loading");
          $('#transaction_table_holder').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $("body").removeClass("loading");
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }
});
</script>
