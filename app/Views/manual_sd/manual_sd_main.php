<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytrxfgpack = model('App\Models\MyFGPackingModel');
$mydataz = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(0);

?>
<main id="main">
    <div class="pagetitle">
        <h1>Manual SD Entry</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Manual SD Entry</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->

  <div class="row mb-3 me-form-font">
      <span id="__me_numerate_wshe__" ></span>
      <div class="col-md-12">
        <div class="card">
            <div class="card-header mb-3">
                <h3 class="h4 mb-0"> <i class="bi bi-pencil-square"></i> Manual SD Entry</h3>
            </div>
          <div class="card-body">
            <?=form_open('me-fg-packing-save','class="needs-validation" id="myfrms_customer" ');?>
            <div class="row">
              <div class="col-lg-6">
                <div class="row mb-3">
                      <label class="col-sm-3 form-label" for="rmap_trxno">Transaction No.:</label>
                      <div class="col-sm-9">
                          <input type="text" id="rmap_trxno" name="rmap_trxno" class="form-control form-control-sm"  readonly/>
                          <input type="hidden" id="__hmpacktrxnoid" name="__hmpacktrxnoid" class="form-control form-control-sm"/>
                      </div>
                  </div> 
                  <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="comp_name">Company Name:</label>
                      <div class="col-sm-9">
                          <input type="text" id="comp_name" name="comp_name" class="comp_name form-control form-control-sm "  required/>
                      </div>
                  </div> 
                  <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="branch_name">Branch Name:</label>
                      <div class="col-sm-9">
                          <input type="text" id="branch_name" name="branch_name" class="branch_name form-control form-control-sm "  required/>
                      </div>
                  </div> 
                  <div class="row gy-2 mb-3">
                    <label class="col-sm-3 form-label" for="date_needed">Date Needed:</label>
                    <div class="col-sm-9">
                    <input type="date" id="date_needed" name="date_needed" class="form-control form-control-sm"  />
                    </div>
                  </div>
                  <div class="row gy-2 mb-3">
                    <label class="col-sm-3 form-label" for="remarks">Remarks:</label>
                    <div class="col-sm-9">
                    <input type="text" id="remarks" name="remarks" class="form-control form-control-sm"  />
                    </div>
                </div>
               </div>
              <div class="col-lg-6">  
              <div class="row gy-2 mb-3">
                <label class="col-sm-3 form-label" for="transac">Request Date</label>
                <div class="col-sm-9">
                    <input type="date"  id="transac" name="transac" class="transac form-control form-control-sm "  readonly/>
                </div>
              </div>      
              <div class="row gy-2 mb-3">
                <label class="col-sm-3 form-label" for="txt_total_qty">Total Qty:</label>
                <div class="col-sm-9">
                  <input type="text" id="txt_total_qty" name="txt_total_qty" class="form-control form-control-sm" readonly/>
                </div>
              </div>

              <div class="row gy-2 mb-3">
                <label class="col-sm-3 form-label" for="txt_total_amount">Total Amount:</label>
                <div class="col-sm-9">
                  <input type="text" id="txt_total_amount" name="txt_total_amount" class="form-control form-control-sm"  readonly/>
                </div>
              </div>

              <div class="row gy-2 mb-3">
                <label class="col-sm-3 form-label" for="txt_total_rm_qty">Total RM Qty:</label>
                <div class="col-sm-9">
                  <input type="text" id="txt_total_rm_qty" name="txt_total_rm_qty" class="form-control form-control-sm" readonly/>
                </div>
              </div>

              </div>
            </div> <!-- endrow -->

            <div class="row">
              <div class="col-md-12">
                <div style="padding-left: 15px;">
                  <!-- insert pagination here -->
                </div>
                <div class=" table-responsive">
                  <table class="table table-bordered table-hover table-sm text-center" id="tbl-fgpack">
                    <thead class="thead-light">
                      <tr>
                        <th nowrap="nowrap"></th>
                        <th nowrap="nowrap">
                          <button type="button" class="btn btn-dgreen btn-sm" onclick="javascript:my_add_line_item_fgpack();" >
                            <i class="bi bi-plus"></i>
                          </button>
                        </th>
                        <th nowrap="nowrap">Item Code</th>
                        <th nowrap="nowrap">Description</th>
                        <th nowrap="nowrap">Qty</th>
                        <th nowrap="nowrap">Srp</th>
                        <th nowrap="nowrap">T.Amount</th>
                        <th nowrap="nowrap">RM Qty</th>
                        <th nowrap="nowrap">RM Total Qty</th>
                        
                      </tr>
                    </thead>
                    <tbody id="gwpo-recs">
                      <tr>
                        <td></td>
                        <td nowrap="nowrap">
                            <button type="button" class="btn btn-xs btn-danger" style="font-size:15px; padding: 2px 6px 2px 6px; " onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button>
                            <input class="mitemrid" type="hidden" value=""/>
                            <input type="hidden" value=""/>
                        </td>
                        
                        <td nowrap="nowrap"><input type="text" id="item_code" class="form-control form-control-sm mitemcode" size="20" ></td>
                        <td nowrap="nowrap"><input type="text" id="ART_DESC" class="form-control form-control-sm" size="20" readonly="readonly"></td>
                        <td nowrap="nowrap"><input type="text" id="item_qty" size="5" class="form-control form-control-sm" readonly="readonly" ></td>
                        <td nowrap="nowrap"><input type="text" id="srp" size="5" class="form-control form-control-sm" ></td>
                        <td nowrap="nowrap"><input type="text" id="tamount" size="5" class="form-control form-control-sm" ></td>
                        <td nowrap="nowrap"><input type="text" id="rm_qty" size="5" class="form-control form-control-sm"></td>
                        <td nowrap="nowrap"><input type="text" id="rm_tqty" size="5" class="form-control form-control-sm"></td>

                      </tr>
                      <tr style="display: none;">
                        <td></td>
                        <td nowrap="nowrap">
                          <button type="button" class="btn btn-xs btn-danger" onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button>
                          <input class="mitemrid" type="hidden" value=""/>
                          <input type="hidden" value=""/>
                         
                          
                        </td>
                        <td nowrap="nowrap"><input type="text" class="form-control form-control-sm mitemcode" size="20"></td> <!--0 ITEMC -->
                        <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" size="20" readonly="readonly"></td> <!--1 DESC -->
                        <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm" onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();"></td> <!--3 QTY -->
                        <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm" readonly="readonly"></td> 
                        <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm" readonly="readonly"></td>
                        <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm" readonly="readonly"></td> 
                        <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm" readonly="readonly"></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            
            <div class="row gy-2 mb-3">
              <?php if(!empty($rmap_trxno)): ?>
              <div class="col-sm-4">
                <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm" disabled>Posted</button>
                <!-- <button id="mbtn_mn_NTRX" type="button" class="btn btn-primary btn-sm">New Trx</button> -->
              </div>
              <?php else:?>
              <div class="col-sm-4">
                <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
                <button id="mbtn_mn_NTRX" type="button" class="btn btn-primary btn-sm">New Entry</button>
              </div>
              <?php endif;?>
            </div> <!-- end Save Records -->
            <?=form_close();?> <!-- end of ./form -->
            </div> <!-- end card-body -->
          </div>

          
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header mb-3">
              <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i>Records</h3>
            </div>
          <div class="card-body">
            <div class="pt-2 bg-dgreen mt-2"> 
               <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> List</a>
                <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> Items</a>
               </nav>
               </div>
                  
               <div id="packlist" class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">
                    <?php
                       // $data = $mytrxfgpack->purch_rec_view(1,20);
                       // echo view('mtap/trx-fg-packing-order-recs',$data);
                    ?> 
                </div> 
          </div> 
          </div>
    </div>
  </div> <!-- end row -->
  <?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>  
</main>    

<script type="text/javascript">
    __my_item_lookup();
    
     __pack_totals();
    
    //PARA SA TIMER NG TAMT TOTALS
    var tid = setInterval(myTamtTimer, 30000);
    function myTamtTimer() {
      __pack_totals();
      // do some stuff...
      // no need to recall the function (it's an interval, it'll loop forever)
    }
    jQuery('.meform_date').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true
    });
    
    
    jQuery('.active_plnt_id')
        // don't navigate away from the field on tab when selecting an item
            .bind( 'keydown', function( event ) {
                if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                    jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                    event.preventDefault();
            }
            if( event.keyCode === jQuery.ui.keyCode.TAB ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            minLength: 0,
            source: '<?= site_url(); ?>get-plant-list',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
        
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#active_plnt_id').val(terms);
                jQuery('#active_plnt_id').attr("data-id-plant",ui.item.mtkn_rid);

                jQuery(this).autocomplete('search', jQuery.trim(terms));
                
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //plant


    jQuery('.active_wshe_id')
        // don't navigate away from the field on tab when selecting an item
            .bind( 'keydown', function( event ) {
                if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                    jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                    event.preventDefault();
            }
            if( event.keyCode === jQuery.ui.keyCode.TAB ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            minLength: 0,
            source: '<?= site_url(); ?>get-warehouse-list',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) {
                var sValue = jQuery(oEvent.target).val();
                var mtkn_plnt = jQuery('#active_plnt_id').attr("data-id-plant");
                jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-warehouse-list/?mtkn_plnt=' + mtkn_plnt); 
                
               
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#active_wshe_id').val(terms);
                jQuery('#active_wshe_id').attr("data-id-whse",ui.item.mtkn_rid);

                jQuery(this).autocomplete('search', jQuery.trim(terms));
                
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //whse

    jQuery('.txt_branch')
        // don't navigate away from the field on tab when selecting an item
            .bind( 'keydown', function( event ) {
                if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                    jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                    event.preventDefault();
            }
            if( event.keyCode === jQuery.ui.keyCode.TAB ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            minLength: 0,
            source: '<?= site_url(); ?>get-branch-list',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_branch').val(terms);
                jQuery('#txt_branch').attr("data-id-brnch",ui.item.mtkn_rid);

                jQuery(this).autocomplete('search', jQuery.trim(terms));
                
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //whse

  
    $('#mbtn_mn_NTRX').click(function() { 
        var userselection = confirm("Are you sure you want to new transaction?");
        if (userselection == true){
            window.location = '<?=site_url();?>me-rm-req-vw';
         }
        else{
            $.hideLoading();
            return false;
        } 
    });

    function __do_makeid(){
    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    for( var i=0; i < 7; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

      return text;
    }


    function my_add_line_item_fgpack() {  
      try {
          
          var rowCount = jQuery('#tbl-fgpack tr').length;
          var mid = __mysys_apps.__do_makeid() + (rowCount + 1);
          var clonedRow = jQuery('#tbl-fgpack tr:eq(' + (rowCount -1) + ')').clone(); 

          jQuery(clonedRow).find('input[type=text]').eq(0).attr('id','mitemcode_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(1).attr('id','mitemdesc_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(2).attr('id','mitemqty_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(3).attr('id','txt-mtext-' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(4).attr('id','txt-test-' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(5).attr('id','txt-rm-qty' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(6).attr('id','txt-rm-tqty' + mid);
          
          jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id','mitemrid_' + mid);
         
          
          jQuery('#tbl-fgpack tr').eq(rowCount - 1).before(clonedRow);
          jQuery(clonedRow).css({'display':''});
          var xobjArtItem= jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
          jQuery('#' + xobjArtItem).focus();
          $( '#tbl-fgpack tr').each(function(i) { 
                  $(this).find('td').eq(0).html(i);
          });
          
          __my_item_lookup();
          __pack_totals();
              
      } catch(err) { 
          var mtxt = 'There was an error on this page.\\n';
          mtxt += 'Error description: ' + err.message;
          mtxt += '\\nClick OK to continue.';
          alert(mtxt);
          return false;
      }  //end try 
    }
    
    
    function __my_item_lookup(){
        jQuery('.mitemcode' ) 
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
              source: '<?= site_url(); ?>get-rm-fg-code-list',
              focus: function() {
                  // prevent value inserted on focus
                  return false;
              },
              select: function( event, ui ) {
                  var terms = ui.item.value;
                  
                  jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                  jQuery(this).attr('title', jQuery.trim(ui.item.value));
               
                  this.value = ui.item.value;
              

                  var clonedRow = jQuery(this).parent().parent().clone();
                  var indexRow = jQuery(this).parent().parent().index();
                  var xobjitemrid = jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id');
                  var xobjitemdesc = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');
                  var xobjitemsrp = jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');
                  var xobjrmqty = jQuery(clonedRow).find('input[type=text]').eq(5).attr('id');

                  $('#' + xobjitemrid).val(ui.item.mtkn_rid);
                  $('#' + xobjitemdesc).val(ui.item.ART_DESC);
                  $('#' + xobjitemsrp).val(ui.item.ART_UPRICE);
                  $('#' + xobjrmqty).val(ui.item.rm_qty);
                 

                  return false;
              }
          })
          .click(function() { 

              //jQuery(this).keydown(); 
              var terms = this.value;
              //jQuery(this).autocomplete('search', '');
              jQuery(this).autocomplete('search', jQuery.trim(terms));
          });  
        }

    
    function __pack_totals() { 

            try { 
                var rowCount1 = jQuery('#tbl-fgpack tr').length -1;
            var adata1 = [];
            var adata2 = [];
            var mdata = '';
            var ninc = 0;
            var total_amount = 0;
            var total_qty = 0;
            var total_rmqty = 0;
            var total_rmtqty = 0;
            
            for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-fgpack tr:eq(' + aa + ')').clone(); 
                var qty = jQuery(clonedRow).find('input[type=text]').eq(2).val();
                var srp = jQuery(clonedRow).find('input[type=text]').eq(3).val();
                var TAMOUNT = jQuery(clonedRow).find('input[type=text]').eq(4).attr('id');
                var rmqty = jQuery(clonedRow).find('input[type=text]').eq(5).val();
                var RMTQTY = jQuery(clonedRow).find('input[type=text]').eq(6).attr('id');
                var fqty = parseFloat(qty);
                var fsrp = parseFloat(srp);
                var ftamount = parseFloat(ftamount);

                var total_sa = (fqty * fsrp);
                total_qty += fqty;

                total_rmqty = (qty * rmqty);


                $('#' + TAMOUNT).val(total_sa);
                $('#' + RMTQTY).val(total_rmqty);

                total_amount += total_sa;
                total_rmtqty +=total_rmqty;
                
            } 
            $('#txt_total_amount').val(total_amount);
            $('#txt_total_qty').val(total_qty);
            $('#txt_total_rm_qty').val(total_rmtqty);

        } catch(err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            alert(mtxt);
            $.hideLoading();
            return false;
        }  //end try            
    }

     $('#tbl-fgpack').on('keydown', "input", function(e) { 
      switch(e.which) {
          case 37: // left 
          break;

          case 38: // up
              var nidx_rw = jQuery(this).parent().parent().index();
              var nidx_td = $(this).parent().index();
              if(nidx_td == 3) { 
              } else { 
                  var clonedRow = jQuery('#tbl-fgpack  tr:eq(' + (nidx_rw) + ')').clone(); 
                  var el_id = jQuery(clonedRow).find('td').eq(nidx_td).find('input[type=text]').eq(0).attr('id');
                  $('#' + el_id).focus();
              }
              
              break;

          case 39: // right
              break;

          case 40: // down
              var nidx_rw = jQuery(this).parent().parent().index();
              var nidx_td = $(this).parent().index();
              if(nidx_td == 3) { 
              } else { 
                  var clonedRow = jQuery('#tbl-fgpack  tr:eq(' + (nidx_rw + 2) + ')').clone(); 
                  var el_id = jQuery(clonedRow).find('td').eq(nidx_td).find('input[type=text]').eq(0).attr('id');
                  //alert(nidx_rw + ':' + nidx_td + ':' + el_id);
                  $('#' + el_id).focus();
              }
              
              break;
          default: return; // exit this handler for other keys
      }
      //e.preventDefault(); // prevent the default action (scroll / move caret)
  });

 

  $("#mbtn_mn_Save").click(function(e){
    try { 
          //__mysys_apps.mepreloader('mepreloaderme',true);
          var mtkn_mntr = jQuery('#__hmpacktrxnoid').val();
          var rmap_trxno = jQuery('#rmap_trxno').val();
          var active_plnt_id = jQuery('#active_plnt_id').val();
          var txt_request_date = jQuery('#txt_request_date').val();
          var txt_total_qty = jQuery('#txt_total_qty').val();
          var remarks = jQuery('#remarks').val();
          var rowCount1 = jQuery('#tbl-fgpack tr').length - 1;
          var adata1 = [];
          var adata2 = [];

          var mdata = '';
          var ninc = 0;

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-fgpack tr:eq(' + aa + ')').clone(); 
                var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //ITEM CODE
                var mdesc = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //UOM
                var mqty = jQuery(clonedRow).find('input[type=text]').eq(2).val(); //QTY
                var mremks = jQuery(clonedRow).find('input[type=text]').eq(3).val(); //STEXT
                var mamount = jQuery(clonedRow).find('input[type=text]').eq(3).val(); //STEXT
                var mitemc_tkn = jQuery(clonedRow).find('input[type=hidden]').eq(1).val(); 
               
                mdata = mitemc + 'x|x' + mdesc + 'x|x' + mqty + 'x|x' + mremks + 'x|x' + mamount + 'x|x' + mitemc_tkn;
                adata1.push(mdata);
                var mdat = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
                adata2.push(mdat);


            }  //end for

          var mparam = {
            mtkn_mntr:mtkn_mntr,
            rmap_trxno:rmap_trxno,
            remarks:remarks,
            active_plnt_id: active_plnt_id,
            txt_request_date:txt_request_date,
            txt_total_qty: txt_total_qty,
            adata1: adata1,
            adata2: adata2
          };  


      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-rm-req-save',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { 
            $(this).prop('disabled', false);
           // $.hideLoading();
            jQuery('#memsgtestent_bod').html(data);
            jQuery('#memsgtestent').modal('show');
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

  __mysys_apps.mepreloader('mepreloaderme',false);

$('#anchor-list').on('click',function(){
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    var mtkn_whse = '';
    rm_req_view_recs(mtkn_whse);

});

function rm_req_view_recs(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>me-rm-req-view",
        type: "post",
        data: {
            mtkn_whse: mtkn_whse
        }
    });

    // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#packlist').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
      });
  };


function mypack_view_appr(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>me-fg-packing-view-appr",
        type: "post",
        data: {
            mtkn_whse: mtkn_whse
        }
    });

    // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#packlist').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
      });
  };
   
  jQuery('#txt_subcon')
		// don't navigate away from the field on tab when selecting an item
		.bind( 'keydown', function( event ) {
			if ( event.keyCode === jQuery.ui.keyCode.TAB &&
				jQuery( this ).data( 'ui-autocomplete' ).menu.active ) { 
				event.preventDefault();
			}
			if( event.keyCode === jQuery.ui.keyCode.TAB ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			minLength: 0,
			source: '<?= site_url(); ?>search-rmap-subcon/', 
			focus: function() {
				return false;
			},
			search: function(oEvent, oUi) {
				var sValue = jQuery(oEvent.target).val();

			},
			select: function( event, ui ) {
				var terms = ui.item.value;
				jQuery('#txt_subcon').val(terms);
				jQuery(this).autocomplete('search', jQuery.trim(terms));
				return false;
			}
		})
		.click(function() {
			var terms = this.value;
			jQuery(this).autocomplete('search', jQuery.trim(terms));
	});	//end txt_subcon

    __mysys_apps.mepreloader('mepreloaderme',false);
</script>