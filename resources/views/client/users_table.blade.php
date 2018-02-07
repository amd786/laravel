<div class="table-responsive">
  <table class="table-bordered no-table-bg width100 table-striped border1Grey fs16">
    <thead>
      <tr>
        <th width="10%">
          ID
        </th>
        <th width="20%">
          First Name
        </th>
        <th width="20%">
          Last Name
        </th>
        <th width="25%">
          Email
        </th>
        <th width="25%">
          Actions
        </th>
      </tr>
    </thead>
    <tbody>
      @if(count($clients)>0)
      @foreach($clients as $client)
      <tr>
        <td class="pad8">{{$client->id}}</td>
        <td class="pad8">{{$client->first_name}}</td>
        <td class="pad8">{{$client->last_name}}</td>
        <td class="pad8">{{$client->email}}</td>
        <td class="pad8">
          <div class="col-md-12">
            <div class="col-md-2 col-sm-4 no_padding">
              <a title="Edit User" href="{{ action('ClientController@EditUser',['id'=>$client->id]) }}"><i class="fa fa-pencil fa-lg colorDarkGrey" aria-hidden="true"></i></a>
            </div>
            <div class="col-md-2 col-sm-4 no_padding">
              <a class="colorDarkGrey" href="{{ action('ClientController@DisableUser',['id'=>$client->id]) }}" onclick="return confirm('Are you sure to {{$client->status==0 ? 'enable' : 'disable'}} this user?')" title="{{$client->status==0 ? 'Enable User' : 'Disable User'}}"><i class="{{$client->status==0 ? 'fa fa-check-circle fa-lg' : 'fa fa-ban fa-lg'}}" aria-hidden="true"></i></a>
              <!--<a class="{{$client->status==0 ? 'disabled' : ''}}" href="{{ action('ClientController@DisableUser',['id'=>$client->id]) }}" onclick="return confirm('Are you sure to disable this user?')"><i class="fa fa-ban fa-lg {{$client->status==0 ? 'colorLightGrey' : 'colorDarkGrey'}}" aria-hidden="true"></i></a>-->
            </div>
            <div class="col-md-8">
            </div>
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
<div class="col-md-12 text-right no_padding">
  @if(count($clients)>0)
    {{  $clients->render() }}
  @endif
</div>
<script>
$('.pagination a').on('click', function(e){
    e.preventDefault();
    var holder  = $(this).closest('.client_users_table').attr('id');
    if(holder=='client_users_table'){
      var url = $(this).attr('href');
      $.ajax({
        url :url,
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success:function(data){
          $("body").removeClass("loading");
          $('.external_users_table').html('');
          $('.external_users_table').html(data);
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
