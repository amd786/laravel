@if(count($invoices)>0)
  @foreach($invoices as $invoice)
    <h3 class="text-left">{{App\Library\Functions::date_format2($invoice->created_at)}}</h3>
    <div class="table-responsive">
      <table class="table-bordered no-table-bg width100 table-striped">
        <thead>
          <tr>
            <th width="15%">
              Amount
            </th>
            <th width="15%">
              Date
            </th>
            <th width="20%">
              Account
            </th>
            <th width="20%">
              Type/Category
            </th>
            <th width="10%">
              Status
            </th>
            <th width="20%">
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          @if(count($invoice->getInvoicesByDate($invoice->created_at,$fk_account_id,$date_filter,$start_date,$end_date))>0)
            @foreach($invoice->getInvoicesByDate($invoice->created_at,$fk_account_id,$date_filter,$start_date,$end_date) as $date_invoice)
              <tr>
                <td class="pad8 text-left">
                  ${{round($invoice->getInvoiceCost($date_invoice->id)->total_cost,2)}}
                </td>
                <td class="pad8 text-left">
                  {{App\Library\Functions::customDateFormat($date_invoice->created_at)}}
                </td>
                <td class="pad8 text-left">
                  @if($date_invoice->getAccountDetail->fk_supplier_id>0)
                  {{ucwords($date_invoice->getAccountDetail->getSupplierDetail->supplier_name)}}
                  @elseif($date_invoice->getAccountDetail->fk_company_id>0)
                  {{ucwords($date_invoice->getAccountDetail->getCompanyDetail->company_name)}}
                  @else
                  {{ucwords($date_invoice->getAccountDetail->getAccountDetail->name)}}
                  @endif
                </td>
                <td class="pad8 text-left">
                  {{$date_invoice->getAccountDetail->getAccountCategoryDetail->category}}
                </td>
                <td class="pad8 text-left">
                  @if($date_invoice->invoice_status==0) Created @elseif($date_invoice->invoice_status==1) Approved @else Rejected @endif
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
              <td colspan="6" class="text-center pad5">No record found.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  @endforeach
  @else
  <div class="table-responsive">
    <table class="table-bordered no-table-bg width100 table-striped">
      <thead>
        <tr>
          <th width="15%">
            Amount
          </th>
          <th width="15%">
            Date
          </th>
          <th width="20%">
            Account
          </th>
          <th width="20%">
            Type/Category
          </th>
          <th width="10%">
            Status
          </th>
          <th width="20%">
            Actions
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="6" class="text-center pad5">No record found.</td>
        </tr>
      </tbody>
    </table>
  </div>
@endif
<div class="col-md-12 text-right no_padding">
  @if(count($invoices)>0)
    {{  $invoices->render() }}
  @endif
</div>
<script>
$('.pagination a').on('click', function(e){
    e.preventDefault();
    var holder  = $(this).closest('.invoice_table_holder').attr('id');
    if(holder=='invoice_table_holder'){
      var fk_account_id = $('#fk_account_id').val();
      var start_date = $('#start_date').val();
      var end_date = $('#end_date').val();
      var date_filter = $('#quick_date').val();
      var url = $(this).attr('href')+'&fk_account_id='+fk_account_id+'&start_date'+start_date+'&end_date='+end_date+'&date_filter='+date_filter;
      $.ajax({
        url :url,
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success:function(data){
          $("body").removeClass("loading");
          $('#invoice_table_holder').html(data);
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
