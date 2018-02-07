
// javascript function
function select_all(source,target,module_id) {
  checkboxes = document.getElementsByClassName(target);
  allcheckboxes  =  document.getElementsByClassName(module_id);
  for(var i=0, n=allcheckboxes.length;i<n;i++) {
    allcheckboxes[i].checked = false;
  }
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = true;
  }
}
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if ((charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) || charCode == 46) {
        return false;
    } else {
        return true;
    }
}
var dbFormatDate = function(usDate) {
  var dateParts = usDate.split(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);
  return dateParts[3] + "-" + dateParts[1] + "-" + dateParts[2];
}
$(function() {

  var date = new Date();
  var currentMonth = date.getMonth();
  var currentDate = date.getDate();
  var currentYear = date.getFullYear();
  $('.select_date_range').daterangepicker({
    maxDate: new Date(currentYear, currentMonth, currentDate)
  });


  //$(".phone_mask").mask("(999) 999-9999999");
  // datatables functions
  /*$('#users-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: baseURL + '/anyData',
    "oLanguage": {
      "sSearch": "<i class='fa fa-search fs20'></i>"
    },
    columns: [
      {data: 'rownum', name: 'rownum'},
      { data: 'first_name', name: 'first_name' },
      { data: 'last_name', name: 'last_name' },
      { data: 'phone_no', name: 'phone_no' },
      { data: 'email', name: 'email' },
      {data: 'action', name: 'action', orderable: false, searchable: false}
    ]
  });*/

  $('#addressbook').DataTable({
    processing: true,
    serverSide: true,
    ajax: baseURL + '/address-data',
    "oLanguage": {
      "sSearch": "<i class='fa fa-search fs20'></i>"
    },
    columns: [
      {data: 'rownum', name: 'rownum'},
      { data: 'company_name', name: 'company_name' },
      { data: 'lead_contact', name: 'lead_contact' },
      { data: 'street_name', name: 'street_name' },
      { data: 'phone', name: 'phone' ,width:'13%'},
      {data: 'action', name: 'action', orderable: false, searchable: false}
    ]
  });

  $('#pla').DataTable({
    processing: true,
    serverSide: true,
    ajax: baseURL + '/showpla',
    "oLanguage": {
      "sSearch": "<i class='fa fa-search fs20'></i>"
    },
    columns: [
      {data: 'rownum', name: 'rownum'},
      { data: 'company_name', name: 'company_name' },
      { data: 'price_list_adjustment', name: 'price_list_adjustment' },
      { data: 'PLA_approved_by', name: 'PLA_approved_by' },
      {data: 'delete', name: 'delete', orderable: false, searchable: false}
    ],
    drawCallback:function(){
      bindSignBtn();
    }
  });

  var oTable = $('#prod-unapp-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
            url: baseURL + '/product-without-appr',
            data:  function(d){
                d.attr1 = $('.attr_dd_count_1').val();
                d.attr2 = $('.attr_dd_count_2').val();
                d.attr3 = $('.attr_dd_count_3').val();
                d.attr4 = $('.attr_dd_count_4').val();
                d.attr5 = $('.attr_dd_count_5').val();
                d.attr6 = $('.attr_dd_count_6').val();
                d.attr7 = $('.attr_dd_count_7').val();
                d.attr8 = $('.attr_dd_count_8').val();
                d.attr9 = $('.attr_dd_count_9').val();
                d.attr10 = $('.attr_dd_count_10').val();
                d.product_class = $('.product_class option:selected').val();
                d.product_name = $('.product_name option:selected').val();
            }
        },
    "oLanguage": {
      "sSearch": "<i class='fa fa-search fs20'></i>"
    },
        "columnDefs": [ {
          "targets": [0],
          "orderable": false,
    }],
    "sPaginationType": "input",
    columns: [
      {data: 'check', name: 'check' , orderable: false, searchable: false},
      {data: 'rownum', name: 'rownum' , orderable: false, searchable: false},
      { data: 'fk_product_id', name: 'fk_product_id' },
      { data: 'sel_attr_val_id', name: 'sel_attr_val_id' },
      { data: 'price', name: 'price' },
      { data: 'tolerance', name: 'tolerance' },
      { data: 'retail_price', name: 'retail_price', searchable: false},
      {data: 'action', name: 'action', orderable: false, searchable: false}
    ],
    drawCallback:function(){
      //turn to popup mode
      $.fn.editable.defaults.mode = 'popup';
      //apply editable methods to all anchor tags
      $(document).ready(function() {
          $('.editable').editable();
          bindChangePLA();
      });


      oTable.column(1).nodes().each( function (cell, i) {
        var info = oTable.page.info();
        cell.innerHTML = i+info.start+1;
      } );
    }
  });
  // select all checkbox code
  var allPages = oTable.cells( ).nodes( );
  $(document).on('click','#selectAll' ,function () {
      if ($(this).hasClass('allChecked')) {
          $('.table-responsive').find('input[type="checkbox"]').prop('checked', false);
      } else {
          $('.table-responsive').find('input[type="checkbox"]').prop('checked', true);
      }
      $(this).toggleClass('allChecked');
  });
  // end here
  oTable.on( 'order.dt search.dt', function () {
        oTable.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

  $(document).on('change','.attr_dd',(function(e) {
    e.preventDefault();
    oTable.draw();
  }));

  // ends here datatables functions
  function bindSignBtn(){
    $('.pla_ajax_btn').click(function(){
      var _this = $(this);
      $body = $("body");
      var value = _this.parent().siblings('.pla_number').val();
      if(value == 0){
        alert('Please Enter some value.');
        return false;
      }
      var cid = $(this).attr('data-cid');
      var sign = $(this).attr('data-sign');
      $.ajax({
        url:baseURL+'/change-sign-pla',
        method:'GET',
        data:{cid:cid,sign:sign},
        beforeSend:function(){
          $body.addClass("loading");
        },
        success:function(result){
          $body.removeClass("loading");
          try {
            data = $.parseJSON(result);
          } catch (e) {
            data = '';
          }

          if(data == ''){
            $.growl.error({title:"Error", message: "You do not have permission to perform this action.",size:'large',duration:6000});
            return false;
          }
          if(data.class == 'red'){
            _this.next().attr('src',baseURL+'/img/grey-plus.png');
            _this.attr('src',baseURL+'/img/green-minus.png');
            // _this.next().removeClass('green');
            // _this.addClass('red');
          }
          if(data.class == 'green'){
            _this.prev().attr('src',baseURL+'/img/grey-minus.png');
            _this.attr('src',baseURL+'/img/red-plus.png');
            // _this.prev().removeClass('red');
            // _this.addClass('green');
          }
          $('.pla_app'+cid).html(data.name);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $body.removeClass("loading");
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    });

    $('.pla_number').change(function(){
      var _this = $(this);
      $body = $("body");
      var value = $(this).val();
      var cid = $(this).attr('data-cid');
      $.ajax({
        url:baseURL+'/save-pla-value',
        method:'GET',
        data:{cid:cid,value:value},
        beforeSend:function(){
          $body.addClass("loading");
        },
        success:function(result){
          $body.removeClass("loading");
          try {
            data = $.parseJSON(result);
          } catch (e) {
            data = '';
          }
          if(data == ''){
            $.growl.error({title:"Error", message: "You do not have permission to perform this action.",size:'large',duration:6000});
            return false;
          }
          $('.pla_app'+cid).html(data.name);
          if(data.zero == 'zero'){
            console.log(_this.prev().find('.pla_ajax_btn'));
            _this.parent().prev().find('.pla_ajax_btn').removeClass('green');
            _this.parent().prev().find('.pla_ajax_btn').removeClass('red');
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $body.removeClass("loading");
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    });
  }
  $('body').on('focus', ".datepicker_dd", function() {
    $('.datepicker_dd').datepicker({
      dateFormat: "yyyy-mm-dd",
      autoclose:true,
      changeMonth: true,
      changeYear: true,
      todayHighlight: true
    });
  });

  $('body').on('focus', ".dateformat_datepicker", function() {
    $('.dateformat_datepicker').datepicker({
      format: 'dd/mm/yyyy',
      autoclose:true,
      changeMonth: true,
      changeYear: true,
      todayHighlight: true
    });
  });

  // for select all checkbox
  $("#grant_all").click(function(){
    $(".write_checkbox").prop('checked', $(this).prop("checked"));
  });

  $(document).on("click", ".assignrole , .assignusers", function () {
    var role_id = $(this).data('roleid');
    $(".role_id_modal").val( role_id );
  });

  var temp_street_name;var temp_city;var temp_country;var temp_postal_code;
  $('#billing_checkbox').click(function(){
    if($(this).is(":checked")){
      temp_street_name= $('input[name=billing_street_name]').val();
      temp_city= $('input[name=billing_city]').val();
      temp_country= $('input[name=billing_country]').val();
      temp_postal_code= $('input[name=billing_postal_code]').val();

      $('input[name=billing_street_name]').val($('input[name=street_name]').val());
      $('input[name=billing_city]').val($('input[name=city]').val());
      $('input[name=billing_country]').val($('input[name=country]').val());
      $('input[name=billing_postal_code]').val($('input[name=postal_code]').val());
    }else{
      $('input[name=billing_street_name]').val(temp_street_name);
      $('input[name=billing_city]').val(temp_city);
      $('input[name=billing_country]').val(temp_country);
      $('input[name=billing_postal_code]').val(temp_postal_code);
    }
  });

  $('#contact_company_id').change(function(){
    var token = $('input[name=_token]').val();
    var company_id = $(this).val();
    $.ajax({
      url:baseURL+'/check-lead-contact',
      method:'POST',
      data:{'company_id':company_id,'_token':token},
      success:function(r){
        var result = $.parseJSON(r);
        if(result.status=='yes'){
          $('.lead_contact_box').html(result.result);
        }else{
          var lead_checkbox = '<label for="lead_contact">Lead Contact</label><div class="checkbox abc-checkbox abc-checkbox-primary lead_contact_check"><input type="checkbox" name="lead_contact" id="lead_contact" value="1"> <label for="lead_contact"></label></div>';
          $('.lead_contact_box').html(lead_checkbox);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.add_product_class').click(function(){
    $('#product_class').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });

  $('.add_product').click(function(){
    $('#product').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });

  $('.add_attribute').click(function(){
    $('#attributes').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });

  $('.copy_attr').click(function(){
    $('#copy_modal').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });

  $('.add_attr_value').click(function(){
    $('#attr_value').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });

  $('.open_attr_select').click(function(){
    $('#attr_value_sel').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });

  // change logo size when clicking
  $('.menu-toggler').click(function(){
    $('.page-logo-img').toggle();
    $('.page-small-logo').toggle();
  });

  // product class dropdown
  $('#product_class_dd').on('change',function(e){
    var p_class_id = e.target.value;
    var token = $('input[name=_token]').val();
    var $body = $("body");
    var save = $(this).attr('data-save');
    $.ajax({
      url:baseURL+'/get-products',
      method:'POST',
      data:{p_class_id:p_class_id,_token:token,save:save},
      beforeSend:function(){
        $body.addClass("loading");
      },
      success:function(result){
        $body.removeClass("loading");
        $('#product_name_dd').empty();
        $('#product_name_dd').append('<option value>Select option...</option>');
        $.each(result, function(index, Obj){
          $('#product_name_dd').append('<option value='+Obj.id+'>'+ Obj.product_name + '</option');
        });
        // uncheck all attributes checkboxes
        $('.all_attr_check').each(function(){
          $(this).removeAttr('checked');
        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $body.removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });
  // product name dropdown save
  $('#product_name_dd').on('change',function(e){
    var p_id = e.target.value;
    var token = $('input[name=_token]').val();
    var $body = $("body");
    var class_id = $('#fk_product_class_id').val();
    $.ajax({
      url:baseURL+'/save-product-id',
      method:'POST',
      data:{p_id:p_id,class_id:class_id,_token:token},
      beforeSend:function(){
        $('#generate_prod').attr('disabled','disabled');
      },
      success:function(result){
        $('#generate_prod').removeAttr('disabled');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#generate_prod').removeAttr('disabled');
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });
  // generate product
  $('#generate_prod').on('click',function(e){
    e.preventDefault();
    var pid = $('#product_name_dd').val();
    $.ajax({
      url:baseURL+'/generate-products',
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        location.reload(true);
        // var data = $.parseJSON(result);
        //  if(data.status == 'error'){
        //    $.growl.error({title:"Error", message: data.message, size:'large',duration:2000});
        //    location.reload(true);
        //  }else if(data.status == 'success'){
        //    $.growl.notice({title:"Success", message: data.message, size:'large',duration:2000});
        //    location.reload(true);
        //  }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        //$("body").removeClass("loading");
        console.log(textStatus, errorThrown);
        // reverse the action
        $.ajax({
          url:baseURL+'/reverse-action',
          data:{pid:pid},
          beforeSend:function(){
            //$("body").addClass("loading");
          },
          success:function(result){
            $("body").removeClass("loading");
            console.log('reversed');
          }
        });
        //alert('Something went wrong.');
      }
    });
  });

  $('.all_attr_check').on('click',function(){
    if($(this).is(":checked")){
      var attr_id = $(this).val();
      //send all selected values in ajax
      $.ajax({
        url:baseURL+'/sel-all-attr-val',
        data:{attr_id:attr_id},
        beforeSend:function(){
          $('#generate_prod').attr('disabled','disabled');
        },
        success:function(result){
          $('#generate_prod').removeAttr('disabled');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $('#generate_prod').removeAttr('disabled');
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }
  });

  // delete all unchecked items from db
  $('.all_attr_check').on('click',function(){
    if($(this).is(":not(:checked)")){
      var id = $(this).val();
      $.ajax({
        url:baseURL+'/sel-none-attr-val',
        data:{id:id},
        beforeSend:function(){
          $('#generate_prod').attr('disabled','disabled');
        },
        success:function(result){
          $('#generate_prod').removeAttr('disabled');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $('#generate_prod').removeAttr('disabled');
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }
  });

  // save data
  $('#notes_assgn_p_val').on('change',function(){
    var id = $(this).attr('data-assignid');
    var notes = $(this).val();
    var token = $('input[name=_token]').val();
    $.ajax({
      url:baseURL+'/save-notes-assign',
      data:{id:id,notes:notes,_token:token},
      method:'post',
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        $.growl.notice({title:"Success", message: "Note Saved Successfully..",size:'large',duration:2000});
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.delete_assign_val').on('click',function(){
    var id = $(this).attr('data-assignid');
    var url = $(this).attr('data-return');
    var result = confirm("Are you sure you want to delete this?");
    if (!result) {
      return false;
    }
    $.ajax({
      url:baseURL+'/delete-assign-val',
      data:{id:id,url:url},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        var data = $.parseJSON(result);
        if(data.status == 'ok'){
          window.location.href = data.url;
        }
        //$.growl.notice({title:"Success", message: "Note Saved Successfully..",size:'large',duration:2000});
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.delete_assign_val_view_all').on('click',function(){
    var id = $(this).attr('data-assignid');
    var url = $(this).attr('data-return');
    var result = confirm("Are you sure you want to delete this?");
    if (!result) {
      return false;
    }
    $.ajax({
      url:baseURL+'/delete-assign-val-view-all',
      data:{id:id,url:url},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        var data = $.parseJSON(result);
        if(data.status == 'ok'){
          window.location.href = data.url;
        }
        //$.growl.notice({title:"Success", message: "Note Saved Successfully..",size:'large',duration:2000});
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });


function bindChangePLA(){

  $('.add_price_modal').click(function(){
    //$($(this).attr('data-target')).find('.modal-content').load($(this).attr('data-url'));
    $('#modal_without_approval').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });


  $('.change_tol_sign').on('click',function(){
    var _this = $(this);
    var id = $(this).attr('data-id');
    var value = $(this).attr('data-value');
    $.ajax({
      url:baseURL+'/change-tolerance',
      data:{id:id,value:value},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        if(value==1){
          _this.next().removeClass('red');
          _this.addClass('green');
        }else{
          _this.prev().removeClass('green');
          _this.addClass('red');
        }
        $('.retail'+id).html(result);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });


  $('.approval_price').on('click',function(){
    var selected = [];
    var empty = 'yes';
    $('.check_prods').each(function() {
       if ($(this).is(":checked")) {
           selected.push($(this).attr('name'));
       }
    });
    if(selected.length != 0){
      empty = 'no';
    }else{
      empty = 'yes';
    }

    if(empty == 'yes'){
      var id = $(this).attr('data-id');
    }else{
      var id = selected;
    }

    var _this = $(this);
    var value = $(this).attr('data-value');
    $.ajax({
      url:baseURL+'/approve-price',
      data:{id:id,value:value},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });
}


  $('.add_class_tole').click(function(){
    $('#modal_class_tolerance').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });

  $('.approval_class_tole').on('click',function(){
    var _this = $(this);
    var id = $(this).attr('data-id');
    var value = $(this).attr('data-value');
    $.ajax({
      url:baseURL+'/approve-class-tole',
      data:{id:id,value:value},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.fk_product_class , .product , .show_order, .has_bom').on('change',function(){
    var _this = $(this);
    var class_id = $('.fk_product_class').val();
    var p_id = $('.product').val();
    var show_order = $('.show_order').val();
    var has_bom = $('.has_bom').val();
    var search_text = $('#search_text').val();
    $.ajax({
      url:baseURL+'/search-products',
      data:{class_id:class_id,p_id:p_id,show_order:show_order,has_bom:has_bom,search:search_text},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        $('.block_container').html(result);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.client_select').change(function(){
    var id = $(this).val();
    var quote_id = $('input[name=quote]').val();
    if(id == ''){
      $('.client_address').val('');
      $('.client_tel').val('');
      $('.contact_select').empty();
      $('.contact_select').append('<option value>Select Contact...</option>');
      return false;
    }
    $.ajax({
      url:baseURL+'/select-client',
      data:{id:id,quote_id:quote_id},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        var data = $.parseJSON(result);
        // redirect to url
        window.location.href = data.url;
        // $('.client_address').val(data.address);
        // $('.client_tel').val(data.tel);
        // // for contacts dropdown
        // $('.contact_select').empty();
        // $('.contact_select').append('<option value>Select Contact...</option>');
        // $.each(data.contacts, function(index, Obj){
        //    $('.contact_select').append('<option value='+Obj.contact_id+'>'+ Obj.first_name + '</option');
        // });

      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  // load add new product module in sales module
  $('.add_product_btn').click(function(){
    if($(this).attr('data-url') == ''){
      $.growl.error({title:"Error", message: "Please select client first",size:'large',duration:3000});
    }else{
      $('#add_product_modal').modal('show').find('.modal-content').load($(this).attr('data-url'));
    }
  });

  // $('#add_product_modal').on('show.bs.modal', function(){
  //   // code here
  //   $('#product-submit').bind('submit');
  //
  // });

  $('.contact_select').change(function(){
    var id = $(this).val();
    var quote_id = $('input[name=quote]').val();
    $('input[name=contact_telephone]').val('');
    $.ajax({
      url:baseURL+'/select-contact',
      data:{id:id,quote_id:quote_id},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        var data = $.parseJSON(result);
        $('input[name=contact_telephone]').val(data.contacts.phone);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  // load payment terms modal
  $('.add_payment_terms').click(function(){
    $('#payment_terms').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });

  $('.add_tax').click(function(){
    $('#tax').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });

  $('.add_excuse').click(function(){
    $('#excuse').modal('show').find('.modal-content').load($(this).attr('data-url'));
  });

  $('.save_ajax_quote').change(function(){
    var value = $(this).val();
    var quote_id = $('input[name=quote]').val();
    var data_model = $(this).attr('data-model');
    $.ajax({
      url:baseURL+'/save-ajax-quote',
      data:{value:value,quote_id:quote_id,data_model:data_model},
      //async:false,
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        var data = $.parseJSON(result);
        if(data_model == 'fk_tax_id'){
          location.reload();
        }
        if(data_model == 'shipping_cost'){
          if(data.final_price != null){
            $('.tot_bal').text("$"+data.final_price);
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.bottom_quote_btn').click(function(){
    var quote_id = $(this).attr('data-id');
    var action = $(this).attr('data-action');
    var bal = $('.tot_bal').text();
    var tax = $('select[name=fk_tax_id]').val();
    var terms = $('select[name=fk_term_id]').val();
    if(bal == '-'){
      $.growl.error({title:"Error", message: "Please complete the quote",size:'large',duration:3000});
      return false;
    }else if($("input[name=quote_no]").val()==''){
      $.growl.error({title:"Error", message: "Please enter quote number",size:'large',duration:3000});
      return false;
    }else if($("input[name=po]").val()==''){
      $.growl.error({title:"Error", message: "Please enter PO number",size:'large',duration:3000});
      return false;
    }else if($('select[name=fk_tax_id]').val()==''){
      $.growl.error({title:"Error", message: "Please select tax",size:'large',duration:3000});
      return false;
    }else if($('select[name=fk_term_id]').val()==''){
      $.growl.error({title:"Error", message: "Please select terms",size:'large',duration:3000});
      return false;
    }

    $.ajax({
      url:baseURL+'/sales/save-full-quote',
      data:{quote_id:quote_id,action:action},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        var data = $.parseJSON(result);
        if(data['msg'] == 'save'){
          $.growl.notice({title:"Success", message: "Saved to draft Successfully.",size:'large',duration:3000});
        }
        if(data['msg'] == 'download'){
          window.location.href = data['url'];
          $.growl.notice({title:"Success", message: "Downloading will start soon...",size:'large',duration:3000});
        }
        if(data['msg'] == 'email'){
          window.location.href = data['url'];
          //$.growl.notice({title:"Success", message: "Mail sent Successfully.",size:'large',duration:3000});
        }
        if(data['msg'] == null){
          $.growl.error({title:"Error", message: "Please fill the form",size:'large',duration:3000});
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.sales_person , .client_dd , .salesreport_status').on('change',function(){
    var _this = $(this);
    var sales_person = $('.sales_person').val();
    var company_id = $('.client_dd').val();
    var status = $('.salesreport_status').val();
    var page = $(this).attr('data-page');
    if(page ==  'commission'){
      var url = baseURL+'/search-commission';
    }else{
      var url = baseURL+'/search-sales-report';
    }

    $.ajax({
      url:url,
      data:{sales_person:sales_person,company_id:company_id,status:status},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        $('.sales_report_block').html(result);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.order_left_section').on('click',function(){
    // make blank
    $('.status_msg').html('');
    $('.order_msg').html('');
    $('.notes').html('');
    var quote_id = $(this).attr('data-id');
    // change background
    $('.order_left_section').each(function(){
      $(this).removeClass('order_active');
    });
    $(this).addClass('order_active');

    $('.pdf_dynamic').html('<a class="media" href=""></a>');
    $('a.media').attr('href',baseURL+'/uploads/pdf/document_'+quote_id+'.pdf');
    $('a.media').media({width:950, height:800});

    // get order info
    var quote_id = $('.order_active').attr('data-id');
    $.ajax({
      url:baseURL+'/get-order-info',
      data:{quote_id:quote_id},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        var data = $.parseJSON(result);
        if(data.order_status == 'won'){
          $('.status_msg').removeClass('order_lost_text');
          $('.status_msg').addClass('order_won_text');
        }else if(data.order_status == 'lost'){
          $('.status_msg').removeClass('order_won_text');
          $('.status_msg').addClass('order_lost_text');
        }
        if(data.order_status == 'won' || data.order_status == 'lost'){
          $('.status_msg').text(data.status_msg);
          $('.order_msg').text(data.order_msg);
          if(data.notes != ''){
            $('.notes').html("Note : "+data.notes);
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.flag_order').on('click',function(){
    var flag_status;
    var quote_id = $('.order_active').attr('data-id');
    if(typeof quote_id == 'undefined'){
      alert('Please select order.');
      return false;
    }

    if ($('.order_active .flag_div').find('img').length) {
      $('.order_active .flag_div').html("");
      flag_status = 'open';
    }else{
      $('.order_active .flag_div').html("<img class='cursor' src="+baseURL+"/img/flag-single.png>");
      flag_status = 'flag';
    }

    $.ajax({
      url:baseURL+'/save-flag-status',
      data:{quote_id:quote_id,flag_status:flag_status},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        if(flag_status == 'open'){
          $.growl.notice({title:"Success", message: "Order Unflagged Successfully.",size:'large',duration:3000});
        }
        if(flag_status == 'flag'){
          $.growl.notice({title:"Success", message: "Order Flagged Successfully.",size:'large',duration:3000});
        }
        changeOrderCss(val = 'flag');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.cancel_order').click(function(){
    var quote_id = $('.order_active').attr('data-id');
    if(typeof quote_id == 'undefined'){
      alert('Please select order.');
      return false;
    }
    var review = $(this).attr('data-review');
    var url = baseURL+'/sales/modal-cancel-order/'+quote_id+'/'+review;
    $('#cancel_order_modal').modal('show').find('.modal-content').load(url);
  });

  $('.approve_order').click(function(){
    var quote_id = $('.order_active').attr('data-id');
    if(typeof quote_id == 'undefined'){
      alert('Please select order.');
      return false;
    }
    var review = $(this).attr('data-review');
    var url = baseURL+'/sales/modal-approve-order/'+quote_id+'/'+review;
    $('#approve_order_modal').modal('show').find('.modal-content').load(url);
  });

  // when order is confirmed
  // $('.approve_order').click(function(){
  //   var quote_id = $('.order_active').attr('data-id');
  //   if(typeof quote_id == 'undefined'){
  //     alert('Please select order.');
  //     return false;
  //   }
  //
  //   var r = confirm("Do you want to confirm order?");
  //   if (r == true) {
  //     $.ajax({
  //       url:baseURL+'/save-confirm-status',
  //       data:{quote_id:quote_id},
  //       beforeSend:function(){
  //         $("body").addClass("loading");
  //       },
  //       success:function(result){
  //         $("body").removeClass("loading");
  //         $('.order_active .orderpage_price').addClass('order_won');
  //         $.growl.notice({title:"Success", message: "Order Confirmed Successfully.",size:'large',duration:3000});
  //         changeOrderCss(val = 'won');
  //       },
  //       error: function(jqXHR, textStatus, errorThrown) {
  //         $("body").removeClass("loading");
  //         alert('Something went wrong.')
  //         console.log(textStatus, errorThrown);
  //       }
  //     });
  //   }
  // });

  $('.order_postpone').click(function(){
    $('.time_options').toggle();
  });

  $('.order_postpone_li').click(function(){
    var time = $(this).attr('data-time');
    var quote_id = $('.order_active').attr('data-id');
    if(typeof quote_id == 'undefined'){
      alert('Please select order.');
      return false;
    }

    $.ajax({
      url:baseURL+'/save-postpone-status',
      data:{quote_id:quote_id,time:time},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        $('.order_active .orderpage_price').addClass('order_delay');
        $.growl.notice({title:"Success", message: "Order Delayed Successfully.",size:'large',duration:3000});
        changeOrderCss(val = 'delay');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  function changeOrderCss(val){
    if(val == 'delay'){
      //if($(".order_active .orderpage_price").hasClass("order_won") || $(".order_active .orderpage_price").hasClass("order_lost")){
      $(".order_active .orderpage_price").removeClass("order_won");
      $(".order_active .orderpage_price").removeClass("order_lost");
      //}
      //if ($('.order_active .flag_div').find('img').length) {
      $('.order_active .flag_div').html("");
      $('.order_active .review_div').html("");
      //  }
    }else if(val == 'won'){
      //if($(".order_active .orderpage_price").hasClass("order_delay") || $(".order_active .orderpage_price").hasClass("order_lost")){
      $(".order_active .orderpage_price").removeClass("order_delay");
      $(".order_active .orderpage_price").removeClass("order_lost");
      //}
      //if ($('.order_active .flag_div').find('img').length) {
      $('.order_active .flag_div').html("");
      $('.order_active .review_div').html("");
      //}
    }else if(val == 'flag'){
      //if($(".order_active .orderpage_price").hasClass("order_delay") || $(".order_active .orderpage_price").hasClass("order_won") || $(".order_active .orderpage_price").hasClass("order_lost")){
      $(".order_active .orderpage_price").removeClass("order_delay");
      $(".order_active .orderpage_price").removeClass("order_won");
      $(".order_active .orderpage_price").removeClass("order_lost");
      $('.order_active .review_div').html("");
      //}
    }

  }

  $('.sales_person_o , .client_dd_o , .salesreport_status_o').on('change',function(){
    var _this = $(this);
    var sales_person = $('.sales_person_o').val();
    var company_id = $('.client_dd_o').val();
    var status = $('.salesreport_status_o').val();

    $.ajax({
      url:baseURL+'/search-order',
      data:{sales_person:sales_person,company_id:company_id,status:status},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        $('.order_block').html(result);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.sort_order').change(function(){
    var value = $(this).val();
    var attr_id = $(this).attr('data-id');
    $.ajax({
      url:baseURL+'/save-sort-order',
      data:{value:value,attr_id:attr_id},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        console.log(result);
        $("body").removeClass("loading");
        $.growl.notice({title:"Success", message: "Sort order saved..",size:'large',duration:2000});
        location.reload(true);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        $.growl.error({title:"Error", message: "Something went wrong..",size:'large',duration:2000});
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('#fk_product_class_id').on('change',function(e){
    var p_class_id = e.target.value;
    var token = $('input[name=_token]').val();
    var $body = $("body");
    $.ajax({
      url:baseURL+'/select-product-class',
      method:'POST',
      data:{p_class_id:p_class_id,_token:token},
      beforeSend:function(){
        $body.addClass("loading");
      },
      success:function(result){
        $body.removeClass("loading");
        var data = $.parseJSON(result);
        window.location.href = data.url;
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $body.removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });


  $(document).on('change','.category_select',function(e){
    var p_class_id = e.target.value;
    var token = $('input[name=_token]').val();
    var save = $(this).attr('data-save');
    $.ajax({
      url:baseURL+'/get-products',
      method:'POST',
      data:{p_class_id:p_class_id,_token:token,save:save},
      beforeSend:function(){
        $("body").addClass('loading');
      },
      success:function(result){
          $("body").removeClass('loading');
          $('.product_name_dd_modal').empty();
          $('.product_name_dd_modal').append('<option value>Select option...</option>');
          $.each(result[0], function(index, Obj){
             $('.product_name_dd_modal').append('<option value='+Obj.id+'>'+ Obj.product_name + '</option');
          });
          $('.fk_attribute_value_id_dd_modal').empty();
          $('.fk_attribute_value_id_dd_modal').append('<option value>Select option...</option>');
          $.each(result[1], function(index, Obj){
             $('.fk_attribute_value_id_dd_modal').append('<option value='+Obj.id+'>'+ Obj.attribute_code + '</option');
          });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.salesreport_status_eng').on('change',function(){
    var _this = $(this);
    var status = $('.salesreport_status_eng').val();

    $.ajax({
      url:baseURL+'/eng-search-order',
      data:{status:status},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        $('.order_block').html(result);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  $('.eng_get_dd_range').on('apply.daterangepicker', function(ev, picker) {
    var start_date  = picker.startDate.format('YYYY-MM-DD');
    var end_date = picker.endDate.format('YYYY-MM-DD');
    var status = $('.salesreport_status_eng option:selected').val();

    $.ajax({
      url:baseURL+'/eng-search-order',
      data:{start_date:start_date,end_date:end_date,status:status},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");
        $('.order_block').html(result);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.');
        console.log(textStatus, errorThrown);
      }
    });
  });
  if($('.autosku_workorder').length){
    $(".autosku_workorder").autocomplete({
      search: function(event, ui) {
           $('#work_order_loader').show();
         },
         open: function(event, ui) {
           $('#work_order_loader').hide();
         },
      html:true,
      source: function( request, response ) {
        var url = $(".autosku_workorder").attr('data-value');
        $.ajax({
         url:url,
         type:"post",
         data: {keyword: request.term},
         success: function( result) {
           if (result.length>0) {
            response( $.map( result, function( item ) {
                return {
                  value: item.label,
                  label: item.label,
                  id: item.id,
                }
            }));
            }
          else{
            response([{ label: 'No result found.', val: -1}]);
          }
        }
        });
      },
      change: function(event,ui){
        if (ui.item==null){
          $(this).val('');
          $(this).focus();
        }
      },
      select: function(event, ui) {
        var sku  = ui.item.label;
        $('#work_order_selected').val(sku);
        var url = $(".autosku_workorder").attr('data-id');
        var id = $(".autosku_workorder").attr('id');
        if(url!==''){
          if(id=='work_order_no'){
            $.ajax({
              url:url,
              method:'POST',
              data:{work_order:sku},
              success:function(result){
                if(result==0){
                  $.growl.error({title:"Error", message: "Work order does not exists." ,size:'large',duration:6000});
                  $('#work_order_no').addClass('BorderRed');
                  $('#sequence_detail').html("");
                  $('#move_inventory_btn').prop('disabled', true);
                  $('#move_inventory_btn').addClass('noCursor');
                }else{
                  $('#sequence_detail').html(result);
                  $('#work_order_no').removeClass('BorderRed');
                  $('#move_inventory_btn').prop('disabled', false);
                  $('#move_inventory_btn').removeClass('noCursor');
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
              }
            });
          }
        }
      }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
           return $("<li></li>")
               .data("item.autocomplete", item)
               .append("<a>" + item.label + "</a>")
               .appendTo(ul);
       };
  }
  if($('.autosku').length){
    $(".autosku").autocomplete({
      search: function(event, ui) {
           $('#autosku_loader').show();
         },
         open: function(event, ui) {
           $('#autosku_loader').hide();
         },
      html:true,
      source: function( request, response ) {
        var url = $(".autosku").attr('data-value');
        $.ajax({
         url:url,
         type:"post",
         data: {keyword: request.term},
         success: function( result) {
           if (result.length>0) {
            response( $.map( result, function( item ) {
                return {
                  value: item.label,
                  label: item.label,
                  id: item.id,
                }
            }));
            }
          else{
            $('#manual_add').prop('disabled', false);
            $('#manual_add').removeClass('noCursor');
            response([{ label: 'No result found.', val: -1}]);
          }
        }
        });
      },
      change: function(event,ui){
        if (ui.item==null || ui.item.val==-1){
          $(this).val('');
          $(this).focus();
        }
      },
      select: function(event, ui) {
        var sku  = ui.item.label;
        var url = $(".autosku").attr('data-id');
        var id = $(".autosku").attr('id');
        if(url!==''){
          if(id=='work_order_no'){
            $.ajax({
              url:url,
              method:'POST',
              data:{work_order:sku},
              success:function(result){
                if(result==0){
                  $.growl.error({title:"Error", message: "Work order does not exists." ,size:'large',duration:6000});
                  $('#work_order_no').addClass('BorderRed');
                  $('#sequence_detail').html("");
                  $('#add_all_items').prop('disabled', true);
                  $('#add_all_items').addClass('noCursor');
                  $('#move_inventory_btn').prop('disabled', true);
                  $('#move_inventory_btn').addClass('noCursor');
                }else{
                  $('#sequence_detail').html(result);
                  $('#work_order_selected').val(sku);
                  $('#work_order_no').removeClass('BorderRed');
                  $('#move_inventory_btn').prop('disabled', false);
                  $('#move_inventory_btn').removeClass('noCursor');
                  $('#add_all_items').prop('disabled', false);
                  $('#add_all_items').removeClass('noCursor');
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
              }
            });
          }else{
            $.ajax({
              method:'POST',
              url :url,
              data: {'sku':sku},
              success:function(data){
                if(data){
                  //var result  = $.parseJSON(data);
                  if(data==0){
                    $('#manual_add').attr("disabled","disabled");
                    $('#manual_add').addClass('noCursor');
                    $.growl.error({title:"Error", message: "Product Sku is invalid." ,size:'large',duration:6000});
                  }else{
                    if($('#fk_assign_product_id').length){
                      $('#fk_assign_product_id').val(data);
                    }
                    $('#manual_add').removeAttr("disabled");
                    $('#manual_add').removeClass("noCursor");
                  }
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                alert('Something went wrong.');
                console.log(textStatus, errorThrown);
              }
            });
          }
        }
      }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
           return $("<li></li>")
               .data("item.autocomplete", item)
               .append("<a>" + item.label + "</a>")
               .appendTo(ul);
       };
  }
  if($('.autosku_quotes').length){
    $(".autosku_quotes").autocomplete({
      search: function(event, ui) {
           $('#work_order_loader').show();
         },
         open: function(event, ui) {
           $('#work_order_loader').hide();
         },
      html:true,
      source: function( request, response ) {
        var url = $(".autosku_quotes").attr('data-value');
        $.ajax({
         url:url,
         type:"post",
         data: {keyword: request.term},
         success: function( result) {
           if (result.length>0) {
            response( $.map( result, function( item ) {
                return {
                  value: item.label,
                  label: item.label,
                  id: item.id,
                }
            }));
            }
          else{
            response([{ label: 'No result found.', val: -1}]);
          }
        }
        });
      },
      change: function(event,ui){
        if (ui.item==null){
          $(this).val('');
          $(this).focus();
        }
      },
      select: function(event, ui) {
        var sku  = ui.item.label;
        var assign_prod_id = ui.item.id;
        var url = $(".autosku_quotes").attr('data-id');
        if(url!==''){
          $.ajax({
            url:url,
            method:'POST',
            data:{sku:sku,assign_prod_id:assign_prod_id},
            success:function(result){
              if(result==0){
                $.growl.error({title:"Error", message: "Product SKU does not exists." ,size:'large',duration:6000});
                $('.autosku_quotes').addClass('BorderRed');
                $('#add_item').prop('disabled', true);
                $('#add_item').addClass('noCursor');
              }else{
                if(result!=1){
                  $('.multiple_work_orders').html(result);
                  $('.multiple_work_orders').removeClass('hide');
                }
                $('#assign_prod_id').val(assign_prod_id);
                $('.autosku_quotes').removeClass('BorderRed');
                $('#add_item').prop('disabled', false);
                $('#add_item').removeClass('noCursor');
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.log(textStatus, errorThrown);
            }
          });
        }
      }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
           return $("<li></li>")
               .data("item.autocomplete", item)
               .append("<a>" + item.label + "</a>")
               .appendTo(ul);
       };
  }
  $('.disabled').click(function(e){
     e.preventDefault();
  })
  $(document).on('click','#submit_approve_checkout', function(){
    var form = $('#approve_checkout_form');
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize(),
      success: function (data) {
        if(data==1){
          window.top.location.reload();
        }
      },
      error: function(data)
      {
        if(data){
          var parsed  = data.responseJSON;
          if(parsed[Object.keys(parsed)[0]] == false){
            var a = parsed[Object.keys(parsed)[1]]
            $.growl.error({title:"Error", message: a[Object.keys(a)[0]] ,size:'large',duration:8000});
          }
        }
      }
    });
  });
  $(document).on('click','#submit_reject_checkout', function(){
    var form = $('#reject_checkout_form');
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize(),
      success: function (data) {
        if(data==1){
          window.top.location.reload();
        }
      },
      error: function(data)
      {
        if(data){
          var parsed  = data.responseJSON;
          if(parsed[Object.keys(parsed)[0]] == false){
            var a = parsed[Object.keys(parsed)[1]]
            $.growl.error({title:"Error", message: a[Object.keys(a)[0]] ,size:'large',duration:8000});
          }
        }
      }
    });
  });
  $(document).on('click','.approve_checkout', function(){
    var id = $(this).attr('data-id');
    var table  =  $(this).attr('data-value');
    $("#approve_checkout_form #id").val(id);
    $('#approve_checkout_form #table').val(table);
    $("#approve_checkout").modal('show');
  });
  $(document).on('click','.reject_checkout', function(){
    var id = $(this).attr('data-id');
    var table  =  $(this).attr('data-value');
    $("#reject_checkout_form #id").val(id);
    $('#reject_checkout_form #table').val(table);
    $("#reject_checkout").modal('show');
  });
  $(document).on('click','.open_approval_details', function(){
    var id = $(this).attr('data-id');
    var table  =  $(this).attr('data-value');
    var url =  baseURL + '/inventory/approval-details';
    $.ajax({
        url: url,
        method: 'POST',
        data: {'id':id,'table':table},
        success: function(result) {
            if (result) {
                $('#approval_details_body').html(result);
                $("#approval_details").modal('show');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Something went wrong.')
            console.log(textStatus, errorThrown);
        }
    });
  });

  //$('#client').select2();
});
