<?php
$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');
$cuser   = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
?>

<br>
<div class="box box-primary">
  
  <div class="box-body">
    <br>
    <div class="row mb-3">
   
      <div class="col-md-3">
        <div class="form-group">
          <label>FROM RACK</label>
          <input type="text" name="txt-ftransfer-rack" id="txt-ftransfer-rack" class="form-control form-control-sm frack_lookup" placeholder="FROM RACK" required="required" onkeydown="" data-type="T" data-id=""/>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>FROM BIN</label>
          <input type="text" id="txt-ftransfer-bin" name="txt-ftransfer-bin" class="form-control form-control-sm fbin_lookup" placeholder="FROM BIN" required="required" value="" data-type="T" data-id=""/>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>TARGET RACK</label>
          <input type="text" name="txt-ttransfer-rack" id="txt-ttransfer-rack" class="form-control form-control-sm frack_lookup" placeholder="TARGET RACK" required="required"  onkeydown="" value="" data-id=""/>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>TARGET BIN</label>
          <input type="text" id="txt-ttransfer-bin" name="txt-ttransfer-bin" class="form-control form-control-sm fbin_lookup" placeholder="TARGET BIN" required="required"  data-type="TT" value="" data-id=""/>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div style="padding-left: 15px;">
          <!-- insert pagination here -->
        </div>
        <div class=" table-responsive">
          <table class="table table-bordered table-hover table-sm text-center" id="tbl-outbound">
            <thead class="thead-light">
              <tr>
                <th nowrap="nowrap">
                  <button type="button" class="btn btn-primary btn-xs" onclick="javascript:my_add_line_item_outbound();" >
                    <i class="bi bi-plus"></i>
                  </button>
                </th>
                <th nowrap="nowrap">BOX BARCODE</th>
                <th nowrap="nowrap">STOCK CODE</th>
                <!-- <th nowrap="nowrap">REMARKS</th> -->
                <th nowrap="nowrap">QTY</th>
                <!-- <th nowrap="nowrap">STOCK CODE</th> -->
                <th nowrap="nowrap">ITEM CODE</th>
                <th nowrap="nowrap">ITEM DESC</th>
                <th nowrap="nowrap">PACKAGING</th>                
                <th nowrap="nowrap">CONV FACTOR</th>
                <th nowrap="nowrap">TOTAL PCS</th>
              </tr>
            </thead>
            <tbody id="outbound-recs">
              <tr style="display: none;">
                <td nowrap="nowrap">
                  <button type="button" class="btn btn-xs btn-danger" onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button>
                </td>
                <td nowrap="nowrap"><input type="text" id="txt-barcode" class="form-control form-control-sm get_wshe_item" size="20" data-id=""></td>
                <td nowrap="nowrap"><input type="text" id="txt-stock-code" class="form-control form-control-sm  wshe-inv-stock-lookup" disabled="disabled" size="20"></td>
                <!-- <td nowrap="nowrap"><textarea id="txt-remarks"></textarea></td> -->
                <td nowrap="nowrap"><input type="text" id="txt-qty" size="5" class="form-control form-control-sm" readonly="readonly" disabled="disabled"></td>
                <!-- <td nowrap="nowrap"><input type="text" id="txt-stock-code" size="20" readonly="readonly" disabled="disabled"></td> -->
                <td nowrap="nowrap"><input type="text" id="txt-item-code" size="5" class="form-control form-control-sm" readonly="readonly" disabled="disabled"></td>
                <td nowrap="nowrap"><input type="text" id="txt-item-desc" size="5" class="form-control form-control-sm" readonly="readonly" disabled="disabled"></td>
                <td nowrap="nowrap"><input type="text" id="txt-uom" size="5" class="form-control form-control-sm" readonly="readonly" disabled="disabled"></td>
                <td nowrap="nowrap"><input type="text" id="txt-convf" size="5" class="form-control form-control-sm" readonly="readonly" disabled="disabled"></td>
                <td nowrap="nowrap"><input type="text" id="txt-total-pcs" size="5" class="form-control form-control-sm" readonly="readonly" disabled="disabled"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <br>
        <div class="form-row">
          <button type="button" id="btn-save-outbound" class="btn btn-danger btn-sm">Save</button>
        </div>
        <hr class="prettyline">
      </div>
    </div>

   
</div>

<script type="text/javascript">






frack_lookup();
fbin_lookup();
  my_add_line_item_outbound();

  function __do_makeid(){
    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    for( var i=0; i < 7; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
  }


  function my_add_line_item_outbound() { 
    try {
        var rowCount = jQuery('#tbl-outbound tr').length;
        var mid = __mysys_apps.__do_makeid(7) + (rowCount + 1);
        var clonedRow = jQuery('#tbl-outbound tr:eq(' + (rowCount - 1) + ')').clone(); 

        jQuery(clonedRow).find('input[type=text]').eq(0).attr('id','txt-barcode-' + mid);
        jQuery(clonedRow).find('textarea').eq(0).attr('id','txt-remarks-' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(2).attr('id','txt-qty-' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(1).attr('id','txt-stock-code-' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(3).attr('id','txt-item-code-' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(4).attr('id','txt-item-desc-' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(5).attr('id','txt-uom-' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(6).attr('id','txt-convf-' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(7).attr('id','txt-total-pcs-' + mid);        
        
        jQuery('#tbl-outbound tr').eq(rowCount - 1).before(clonedRow);
        jQuery(clonedRow).css({'display':''});
        get_wshe_item();
            
    } catch(err) { 
        var mtxt = 'There was an error on this page.\\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\\nClick OK to continue.';
        alert(mtxt);
        return false;
    }  //end try 
  }
  
  $("#btn-save-outbound").click(function(e){
    try { 
    // $(this).prop('disabled', true);
     // $.showLoading({name: 'line-pulse', allowHide: false });
      var myTable = $("#outbound-recs");
      var mdata = '';
      var item_array = [];
      var dup_array = [];
      let itemCount = 0;

      myTable.find('tr').each(function (i, el) {
        var $tdsTx = $(this).find('input'),
          // $tdsTxtArea = $(this).find('textarea'),
          mtkn_rid = $tdsTx.eq(0).attr("data-id");
          barcode = $tdsTx.eq(0).val();

          if(mtkn_rid != ''){
            if ($.inArray(`'${barcode}'`, item_array) > -1){ //check for duplicate box barcodes
                dup_array.push(`'${barcode}'`);
              }

              item_array.push(`'${barcode}'`);
              itemCount++;
          }
       

      });

      if(dup_array.length > 0 ){
              jQuery('#memsgtestent_danger_bod').html(`Duplicate box barcode found.<br> <strong>${dup_array.join('<br>')} </strong> `);
              jQuery('#memsgtestent_danger').modal('show');
              return false;
      }

      var mwshe_id       = $('#txt-warehouse').attr("data-id");
      var to_rack_name   = $("#txt-ttransfer-rack").val();
      var to_bin_name    = $("#txt-ttransfer-bin").val();
      var from_rack_name = $("#txt-ftransfer-rack").val();
      var from_bin_name  = $("#txt-ftransfer-bin").val();

      if(to_rack_name == from_rack_name){
        jQuery('#memsgtestent_danger_bod').html('Invalid rack bin');
        jQuery('#memsgtestent_danger').modal('show');
      }
       if(to_bin_name == from_bin_name){
        jQuery('#memsgtestent_danger_bod').html('Invalid rack bin');
        jQuery('#memsgtestent_danger').modal('show');

      }

      if(item_array.length == 0){
        jQuery('#memsgtestent_danger_bod').html('No Items to save.');
        jQuery('#memsgtestent_danger').modal('show');
        return false;
      }
      var data_arr = item_array.join(',');
      var mparam = {
        txtWarehousetkn : mwshe_id,
        to_rack : $("#txt-ttransfer-rack").attr("data-id"),
        to_bin : $("#txt-ttransfer-bin").attr("data-id"),
        from_rack : $("#txt-ftransfer-rack").attr("data-id"),
        from_bin : $("#txt-ftransfer-bin").attr("data-id"),
        to_rack_name : to_rack_name,
        to_bin_name : to_bin_name,
        from_rack_name : from_rack_name,
        from_bin_name : from_bin_name,
        data_arr : data_arr,
        itemCount:itemCount

      }

  
      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>warehouse-inv-save-transfer',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { 
            $(this).prop('disabled', false);
           // $.hideLoading();
            jQuery('#memsgtestent_success_bod').html(data);
            jQuery('#memsgtestent_success').modal('show');
            return false;
        },
        error: function() {
          alert('error loading page...');
         // $.hideLoading();
          return false;
        } 
      });

    } catch(err) {
      var mtxt = 'There was an error on this page.\n';
      mtxt += 'Error description: ' + err.message;
      mtxt += '\nClick OK to continue.';
      alert(mtxt);
    }  //end try
    return false; 
  });



  function get_wshe_item() { 
    var mtkn_uid = jQuery('#txt-warehouse').attr("data-id")
    var frm_wshe_grp_id = $('#frm_wshe_grp_id').attr("data-id");
    var frm_wshe_sbin_id = $('#frm_wshe_sbin_id').attr("data-id");
    $('.get_wshe_item' ) 
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
            if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                    jQuery( this ).data( 'autocomplete' ).menu.active ) {
                event.preventDefault();
            }
            if( event.keyCode === jQuery.ui.keyCode.TAB ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            minLength: 0,
            source: '<?= site_url(); ?>warehouse-inv-getbarcodes?mtkn_uid='+mtkn_uid+'&frm_wshe_grp_id='+frm_wshe_grp_id+'&frm_wshe_sbin_id='+frm_wshe_sbin_id,
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) { 
                var sValue = jQuery(oEvent.target).val();
                var mtkn_uid = jQuery('#txt-warehouse').attr("data-id")
                var frm_wshe_grp_id = $('#txt-ftransfer-rack').attr("data-id");
                var frm_wshe_sbin_id = $('#txt-ftransfer-bin').attr("data-id");
                $(this).autocomplete('option', 'source', '<?= site_url(); ?>warehouse-inv-getbarcodes?mtkn_uid='+mtkn_uid+'&frm_wshe_grp_id='+frm_wshe_grp_id+'&frm_wshe_sbin_id='+frm_wshe_sbin_id);
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                jQuery(this).attr('title', jQuery.trim(ui.item.value));
                jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));

                this.value = ui.item.value;

                var clonedRow = jQuery(this).parent().parent().clone();
                var indexRow = jQuery(this).parent().parent().index();

                var stock_code_id = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');
                // var stock_code_id = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');
                var qty_id = jQuery(clonedRow).find('input[type=text]').eq(2).attr('id');
                var item_code_id = jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');
                var item_desc_id = jQuery(clonedRow).find('input[type=text]').eq(4).attr('id');
                var uom_id = jQuery(clonedRow).find('input[type=text]').eq(5).attr('id');
                var convf_id = jQuery(clonedRow).find('input[type=text]').eq(6).attr('id');
                var total_pcs_id = jQuery(clonedRow).find('input[type=text]').eq(7).attr('id');
                
                $('#'+qty_id).val(ui.item.qty);
                $('#'+stock_code_id).val(ui.item.stock_code);
                $('#'+item_code_id).val(ui.item.ART_CODE);
                $('#'+item_desc_id).val(ui.item.ART_DESC);
                $('#'+uom_id).val(ui.item.ART_SKU);
                $('#'+convf_id).val(ui.item.convf);
                $('#'+total_pcs_id).val(ui.item.total_pcs);


                return false;
            }
        })
        .click(function() { 
            //jQuery(this).keydown(); 
            var terms = this.value;
            //jQuery(this).autocomplete('search', '');
            jQuery(this).autocomplete('search', jQuery.trim(terms));
        });         
  }  //end __my_wshe_lkup


</script>
