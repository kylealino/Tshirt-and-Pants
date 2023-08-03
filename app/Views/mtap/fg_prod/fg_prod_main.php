<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytrxfgpack = model('App\Models\MyFGPackingModel');
$mydataz = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(0);

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$fgreq_trxno = $request->getVar('fgreq_trxno');
$req_date = '';
$process_date = '';
$pack_qty = '';
$rmng_pack ='';
$nporecs = 0;
if (!empty($fgreq_trxno)) {
    $str="
    SELECT
    a.`fgreq_trxno`,
    a.`req_date`,
    a.`process_date`,
    a.`pack_qty`,
    a.`rmng_pack`
    FROM 
    trx_fgpack_req_hd a
    JOIN
    trx_fgpack_req_dt b
    ON
    a.`fgreq_trxno` = b.`fgreq_trxno`
    WHERE
    b.`qty_perpack` != '0' AND b.`fgreq_trxno` = '{$fgreq_trxno}'
    GROUP BY a.`fgreq_trxno`
    ";
    $q =  $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    $rw = $q->getRowArray();
    $req_date = $rw['req_date'];
    $process_date = $rw['process_date'];
    $pack_qty = $rw['pack_qty'];
    $rmng_pack = $rw['rmng_pack'];
}
?>
<main id="main">

    <div class="pagetitle">
        <h1>Finish Good Pack Production</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">FGP Production Entry</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->

  <div class="row mb-3 me-form-font">
      <span id="__me_numerate_wshe__" ></span>
      <div class="col-md-12">
        <div class="card">
            <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-pencil-square"></i> Entry</h3>
            </div>
          <div class="card-body">
            <?=form_open('me-fg-packing-save','class="needs-validation" id="myfrms_customer" ');?>
            <div class="row">
              <div class="col-lg-6">
                <div class="row mb-3">
                      <label class="col-sm-3 form-label" for="fgreq_trxno">FGPR Transaction No.:</label>
                      <div class="col-sm-9">
                          <input type="text" id="fgreq_trxno" name="fgreq_trxno" class="form-control form-control-sm" value="<?=$fgreq_trxno;?>" readonly/>
                          <input type="hidden" id="__hmpacktrxnoid" name="__hmpacktrxnoid" class="form-control form-control-sm"/>
                      </div>
                  </div> <!-- end Acct No. -->
                  <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="txt_req_date">Request Date</label>
                      <div class="col-sm-9">
                          <input type="date"  id="txt_req_date" name="txt_req_date" class="txt_req_date form-control form-control-sm " value="<?=$req_date;?>"  readonly/>
                      </div>
                  </div> <!-- end Birth Date-->   
                  <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="txt_process_date">Process Date</label>
                      <div class="col-sm-9">
                          <input type="date"  id="txt_process_date" name="txt_process_date" class="txt_process_date form-control form-control-sm " value="<?=$process_date;?>"  readonly/>
                      </div>
                  </div> <!-- end Birth Date-->   
               </div>
              <div class="col-lg-6">  
              <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="txt_req_pack">Requested Packs</label>
                      <div class="col-sm-9">
                          <input type="text"  id="txt_req_pack" name="txt_req_pack" class="txt_req_pack form-control form-control-sm " value="<?=$pack_qty;?>"  readonly/>
                      </div>
                  </div>       
                <div class="row gy-2 mb-3">
                    <label class="col-sm-3 form-label" for="txt_rmng_pack">Remaining Packs</label>
                    <div class="col-sm-9">
                        <input type="text"  id="txt_rmng_pack" name="txt_rmng_pack" class="txt_rmng_pack form-control form-control-sm " value="<?=$rmng_pack;?>" readonly/>
                    </div>
                </div> 
              <div class="row gy-2 mb-3">
                <label class="col-sm-3 form-label" for="txt_process_pack">Processed Packs:</label>
                <div class="col-sm-9">
                  <input type="text" id="txt_process_pack" name="txt_process_pack" class="form-control form-control-sm" onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();"/>
                </div>
              </div>
              </div>
            </div> <!-- endrow -->

            <hr>

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
                          <button type="button" class="btn btn-dgreen btn-sm">
                            <i class="bi bi-plus"></i>
                          </button>
                        </th>
                        <th nowrap="nowrap">Item Code</th>
                        <th nowrap="nowrap">Description</th>
                        <th nowrap="nowrap">Available</th>
                        <th nowrap="nowrap">Demand</th>
                        <th nowrap="nowrap">Qty/Pack</th>
                        <th nowrap="nowrap">Total Pcs</th>
                        <th nowrap="nowrap">Total Processed</th>
                      </tr>
                    </thead>
                    <tbody id="gwpo-recs">
                    <?php
                      if (!empty($fgreq_trxno)):

                      $str = "
                        SELECT 
                        b.`mat_code`,
                        c.`ART_DESC`,
                        (SELECT `demand_qty` FROM trx_tpa_dt WHERE mat_code = b.`mat_code` AND tpa_trxno = b.`tpa_trxno`) AS demand_qty,
                        b.`qty_perpack`,
                        b.`total_pack`,
                        (SELECT SUM(po_qty) FROM fg_inv_rcv WHERE mat_code = c.`ART_CODE`) AS po_qty

                        FROM
                        trx_fgpack_req_hd a
                        JOIN
                        trx_fgpack_req_dt b
                        ON 
                        a.`fgreq_trxno` = b.`fgreq_trxno`
                        JOIN
                        mst_article c
                        ON
                        b.`mat_code` = c.`ART_CODE`
                        LEFT JOIN 
                        fg_inv_rcv e
                        ON 
                        e.`mat_code` = c.`ART_CODE`
                        JOIN
                        trx_tpa_dt d
                        ON
                        a.`tpa_trxno` = d.`tpa_trxno`
                        WHERE
                        b.`fgreq_trxno` = '{$fgreq_trxno}'
                        GROUP BY b.`mat_code`
                        ";

                      $q =  $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                      $rrec = $q->getResultArray();
                      foreach($rrec as $rdt){
                          $nporecs++;
                        
                          $mat_code = $rdt['mat_code'];
                          $ART_DESC = $rdt['ART_DESC'];
                          $demand_qty = $rdt['demand_qty'];
                          $qty_perpack = $rdt['qty_perpack'];
                          $total_pack = $rdt['total_pack'];
                          $po_qty = $rdt['po_qty'];
                      ?>
                      
                      <tr>
                        <td><?=$nporecs;?></td>
                        <td nowrap="nowrap">
                            <button type="button" class="btn btn-xs btn-danger" style="font-size:15px; padding: 2px 6px 2px 6px; " onclick="$(this).closest('tr').remove();" disabled><i class="bi bi-x"></i></button>
                            <input class="mitemrid" type="hidden" value=""/>
                            <input type="hidden" value=""/>
                        </td>
                        
                        <td nowrap="nowrap"><input type="text" id="item_code<?=$nporecs;?>" class="form-control form-control-sm mitemcode" size="20" value="<?=$rdt['mat_code'];?>"disabled readonly="readonly"></td>
                        <td nowrap="nowrap"><input type="text" id="ART_DESC<?=$nporecs;?>" class="form-control form-control-sm" size="20" value="<?=$rdt['ART_DESC'];?>" readonly="readonly"></td>
                        <td nowrap="nowrap"><input type="text" id="ART_DESC<?=$nporecs;?>" class="form-control form-control-sm" size="20" value="<?=$rdt['po_qty'];?>" readonly="readonly"></td>
                        <td nowrap="nowrap"><input type="text" id="txt_demand<?=$nporecs;?>" size="5" class="form-control form-control-sm" value="<?=$rdt['demand_qty'];?>" readonly="readonly" ></td>
                        <td nowrap="nowrap"><input type="text" id="txt_qtypack<?=$nporecs;?>" size="5" class="form-control form-control-sm" value="<?=$rdt['qty_perpack'];?>" readonly="readonly"></td>
                        <td nowrap="nowrap"><input type="text" id="total_pcs<?=$nporecs;?>" size="5" class="form-control form-control-sm" value="<?=$rdt['total_pack'];?>" readonly="readonly" ></td>
                        <td nowrap="nowrap"><input type="text" id="total_processed<?=$nporecs;?>" size="5" class="form-control form-control-sm" readonly="readonly" onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();"></td>

                      </tr>
                      <?php 
                        } 
                        endif;?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            
            <div class="row gy-2 mb-3">
              <?php if(!empty($fgreq_trxno)): ?>
              <div class="col-sm-4">
                <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
                <?=anchor('me-fg-prod-vw', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-sm" ');?>
              </div>
              <?php else:?>
              <div class="col-sm-4">
                <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm" disabled>Save</button>
                <?=anchor('me-fg-prod-vw', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-sm" ');?>
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
                <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Records</h3>
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


    function __pack_totals() { 

    try { 
        var rowCount1 = jQuery('#tbl-fgpack tr').length;

        var mdata = '';
        var total_pcs = 0;
        var total_stock = 0;
        var rmng = 0;
        for(aa = 1; aa < rowCount1; aa++) { 
            var clonedRow = jQuery('#tbl-fgpack tr:eq(' + aa + ')').clone(); 
            var txt_process_pack = jQuery('#txt_process_pack').val();
            var txt_qtypack = jQuery(clonedRow).find('input[type=text]').eq(4).val();
            var txt_total_pcs = jQuery(clonedRow).find('input[type=text]').eq(6).attr('id');

            total_pcs = (txt_qtypack * txt_process_pack);

            $('#' + txt_total_pcs).val(total_pcs);

        }  //end for 
        

    } catch(err) {
        var mtxt = 'There was an error on this page.\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\nClick OK to continue.';
        alert(mtxt);
        $.hideLoading();
        return false;
    }  //end try            
    }

  
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
          var clonedRow = jQuery('#tbl-fgpack tr:eq(' + (rowCount - 1) + ')').clone(); 

          jQuery(clonedRow).find('input[type=text]').eq(0).attr('id','mitemcode_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(1).attr('id','mitemdesc_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(2).attr('id','mitemqty_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(3).attr('id','txt-mtext-' + mid);
          
          jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id','mitemrid_' + mid);
         
          
          jQuery('#tbl-fgpack tr').eq(rowCount - 1).before(clonedRow);
          jQuery(clonedRow).css({'display':''});
          var xobjArtItem= jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
          jQuery('#' + xobjArtItem).focus();
          $( '#tbl-fgpack tr').each(function(i) { 
                  $(this).find('td').eq(0).html(i);
          });
          
          __my_item_lookup();
              
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
                  var xobjitemrid = jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id'); //ID
                  var xobjitemdesc = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');/*DESC*/
                  var xobjiteminv = jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');/*DESC*/
                  
                  $('#' + xobjitemrid).val(ui.item.mtkn_rid);
                  $('#' + xobjitemdesc).val(ui.item.ART_DESC);
                  $('#' + xobjiteminv).val(ui.item.po_qty);
                  
                 

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

    
    function __pack_totalss() { 

            try { 
                var rowCount1 = jQuery('#tbl-fgpack tr').length - 1;
            var adata1 = [];
            var adata2 = [];
            var mdata = '';
            var ninc = 0;
            var nTAmount = 0;
            var nTQty = 0;
            for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-fgpack tr:eq(' + aa + ')').clone(); 
                var qty = jQuery(clonedRow).find('input[type=text]').eq(2).val();
                var price = jQuery(clonedRow).find('input[type=text]').eq(3).val();
                var xTAmntId = jQuery(clonedRow).find('input[type=text]').eq(4).attr('id');
              
                var nqty = 0;
                var nprice = 0;
                
                if($.trim(qty) == '') { 
                    nqty = 0;
                } else { 
         
                    nqty = qty;
                }
                if($.trim(price) == '') { 
                    nprice = 0;//COST
                } else { 
                    nprice =price;
                }

                if($.trim(xTAmntId) == '') { 
                    nprice2 = 0;
                } else { 
                    nprice2 = xTAmntId;
                }
               
             

                var ntqty = parseFloat(nqty);
                if($('#' + xTAmntId).val()==''){
                  var ntprice = parseFloat(nprice * ntqty);
                }
                else{

                     var ntprice = parseFloat(nprice * ntqty);
                }

                if(!isNaN(ntprice) || ntprice > 0) { 
                    $('#' + xTAmntId).val(ntprice.toFixed(2));
                   // console.log(xTAmntId);
                }
                nTAmount = (nTAmount + ntprice);
                nTQty = (nTQty + parseFloat(nqty));
                
               

               
            }  //end for 
            
            $('#txt_total_qty').val(nTQty);
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
          var fgreq_trxno = jQuery('#fgreq_trxno').val();
          var txt_req_pack = jQuery('#txt_req_pack').val();
          var txt_rmng_pack = jQuery('#txt_rmng_pack').val();
          var txt_process_pack = jQuery('#txt_process_pack').val();
          var rowCount1 = jQuery('#tbl-fgpack tr').length;
          var adata1 = [];
          var adata2 = [];

          var mdata = '';
          var ninc = 0;
          
          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-fgpack tr:eq(' + aa + ')').clone(); 
                var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val();
                var inv = jQuery(clonedRow).find('input[type=text]').eq(2).val(); 
                var mdmd = jQuery(clonedRow).find('input[type=text]').eq(3).val(); 
                var total_processed = jQuery(clonedRow).find('input[type=text]').eq(6).val();
                var mitemc_tkn = jQuery(clonedRow).find('input[type=hidden]').eq(1).val(); 
               
                mdata = mitemc + 'x|x' + mdmd + 'x|x' + mitemc_tkn + 'x|x' + total_processed + 'x|x' + inv;
                adata1.push(mdata);
                var mdat = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
                adata2.push(mdat);

            } 
          var mparam = {
            mtkn_mntr:mtkn_mntr,
            fgreq_trxno:fgreq_trxno,
            txt_req_pack:txt_req_pack,
            txt_rmng_pack,txt_rmng_pack,
            txt_process_pack: txt_process_pack,
            adata1:adata1,
            adata2:adata2

          };  


      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-fg-prod-save',
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
        url: "<?=site_url();?>me-fg-prod-view",
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
   
</script>