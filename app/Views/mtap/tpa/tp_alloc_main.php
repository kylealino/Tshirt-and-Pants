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
$branch_name = '';
$req_date = '';
$total_qty='';
$tpa_trxno = $request->getVar('tpa_trxno');
$prod_plan_trxno = $request->getVar('prod_plan_trxno');
$nporecs = 0;
$txtactive_plnt_id = "";
$req_date = date('Y-m-d');
$plnt_id = "";
$brnch_name = '';
$entry_date = '';
if(!empty($tpa_trxno)) {
$str = "
    SELECT
    a.`tpa_trxno`,
    a.`branch_name`,
    a.`req_date`,
    a.`total_qty`,
    a.`plnt_id`
    FROM
    trx_tpa_hd a
    WHERE a.`tpa_trxno` = '$tpa_trxno' 
        ";

$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();
//$mtkn_trxno = $rw['mtkn_trxno'];

$tpa_trxno = $rw['tpa_trxno'];
$branch_name = $rw['branch_name'];
$req_date = $rw['req_date'];
$total_qty = $rw['total_qty'];
$plnt_id = $rw['plnt_id'];

}

if(!empty($prod_plan_trxno)) {
  $str = "
    SELECT
    a.`brnch_name`,
    a.`entry_date`
    FROM
    prod_plan_hd a
    WHERE
    prod_plan_trxno = '$prod_plan_trxno'
    ";

$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();
$brnch_name = $rw['brnch_name'];
$entry_date = $rw['entry_date'];
$date = DateTime::createFromFormat('Y-m-d H:i:s', $entry_date)->format('Y-m-d');
}



?>
<main id="main">

    <div class="pagetitle">
        <h1>Tshirt & Pants Allocation Entry</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">TPA Entry</li>
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
                      <label class="col-sm-3 form-label" for="tpa_trxno">TPA Transaction No.:</label>
                      <div class="col-sm-9">
                          <input type="text" id="tpa_trxno" name="tpa_trxno" class="form-control form-control-sm" value="<?=$tpa_trxno;?>" readonly/>
                          <input type="hidden" id="__hmpacktrxnoid" name="__hmpacktrxnoid" class="form-control form-control-sm"/>
                      </div>
                  </div> <!-- end Acct No. -->
                  <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="branch_name">Branch:</label>
                      <div class="col-sm-9">
                      <input type="text"  placeholder="Branch Name" id="branch_name" name="branch_name" class="branch_name form-control form-control-sm "  value="<?=$brnch_name;?>" required/>
                      <input type="hidden"  placeholder="Branch Name" id="branch_code" name="branch_code" class="branch_code form-control form-control-sm " required/>  
                      </div>
                  </div> <!-- end Birth Date--> 
                  <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="active_plnt_id">Plant:</label>
                      <div class="col-sm-9">
                          <input type="text" data-id-plant="?=$mtkn_active_plnt_id;?>" id="active_plnt_id" name="active_plnt_id" class="active_plnt_id form-control form-control-sm " value="<?=$plnt_id;?>" required/>
                      </div>
                  </div> <!-- end Birth Date--> 
               </div>
              <div class="col-lg-6">  
              <div class="row gy-2 mb-3">
                  <label class="col-sm-3 form-label" for="prod_plan_trxno">PRPL Transaction No.:</label>
                      <div class="col-sm-9">
                          <input type="text" id="prod_plan_trxno" name="prod_plan_trxno" class="form-control form-control-sm" value="<?=$prod_plan_trxno;?>" readonly/>
                      </div>
                  </div> 
              <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="txt_request_date">Request Date</label>
                      <div class="col-sm-9">
                          <?php if(!empty($prod_plan_trxno)):?>
                          <input type="date"  id="txt_request_date" name="txt_request_date" class="txt_request_date form-control form-control-sm " value="<?=$date;?>" required readonly/>
                          <input type="hidden" name="entry_date" id="entry_date" value="<?=$entry_date;?>">
                          <?php else:?>
                            <input type="date"  id="txt_request_date" name="txt_request_date" class="txt_request_date form-control form-control-sm " value="<?=$req_date;?>" required readonly/>
                            <input type="hidden" name="entry_date" id="entry_date" value="<?=$entry_date;?>">
                          <?php endif;?>
                      </div>
                  </div>  
       
              <div class="row gy-2 mb-3">
                <label class="col-sm-3 form-label" for="txt_total_qty">Total Qty:</label>
                <div class="col-sm-9">
                  <input type="text" id="txt_total_qty" name="txt_total_qty" class="form-control form-control-sm" readonly/>
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
                                <th></th>
                                <th nowrap="nowrap" style="color:red;">Itemcode</th>
                                <th nowrap="nowrap">Description</th>
                                <th nowrap="nowrap">Standard Capacity</th>
                                <th nowrap="nowrap">SRP</th>
                                <th nowrap="nowrap">Store Balance</th>
                                <th nowrap="nowrap">Sales</th>
                                <th nowrap="nowrap">Intransit</th>
                                <th nowrap="nowrap">For Packing</th>
                                <th nowrap="nowrap">Lacking/Over</th>
                                <th nowrap="nowrap">Qty To Serve</th>
                                <th nowrap="nowrap">Amount</th>
                        
                      </tr>
                    </thead>
                    <tbody id="gwpo-recs">
                    <?php
                      if (!empty($prod_plan_trxno)):
                      $nn=1;

                      $str = "
                      SELECT
                      a.`mat_code`,
                      b.`ART_DESC`,
                      a.`stdrd_cap`,
                      b.`ART_UPRICE`,
                      a.`store_bal`,
                      a.`sales`,
                      a.`intransit`,
                      a.`for_packing`,
                      a.`lacking`,
                      a.`qty_serve`,
                      a.`amount_serve`
                      FROM
                          `prod_plan_dt` a
                      JOIN
                      mst_article b
                      ON
                      a.`mat_code` = b.`ART_CODE`
                      WHERE 
                      a.`prod_plan_trxno` = '{$prod_plan_trxno}' AND a.`amount_serve` != 0;
                      ";

                      $q =  $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                      $rrec = $q->getResultArray();
                      foreach($rrec as $rdt){
                          $nporecs++;
                        
                          $mat_code = $rdt['mat_code'];
                          $ART_DESC = $rdt['ART_DESC'];
                          $stdrd_cap = $rdt['stdrd_cap'];
                          $ART_UPRICE = $rdt['ART_UPRICE'];
                          $store_bal = $rdt['store_bal'];
                          $sales = $rdt['sales'];
                          $intransit = $rdt['intransit'];
                          $for_packing = $rdt['for_packing'];
                          $lacking = $rdt['lacking'];
                          $qty_serve = $rdt['qty_serve'];
                          $amount_serve = $rdt['amount_serve'];

                      ?>
                      <tr>
                        <td nowrap="nowrap"><?=$nporecs;?></td>
                        <td nowrap="nowrap"><input type="text" id="mat_code<?=$nporecs;?>" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$rdt['mat_code'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="ART_DESC<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white" size="30" value="<?=$rdt['ART_DESC'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="stdrd_cap<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white" size="10" value="<?=$rdt['stdrd_cap'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="ART_UPRICE<?=$nporecs;?>" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$rdt['ART_UPRICE'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="store_bal<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white" size="10" value="<?=$rdt['store_bal'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="sales<?=$nporecs;?>" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$rdt['sales'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="intransit<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white" size="10" value="<?=$rdt['intransit'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="for_packing<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white" size="10" value="<?=$rdt['for_packing'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="lacking<?=$nporecs;?>" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$rdt['lacking'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="qty_serve<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white" size="10" value="<?=$rdt['qty_serve'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="amount_serve<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white" size="10" value="<?=$rdt['amount_serve'];?>" disabled></td>

                      </tr>
                      <?php 
                        $nn++;
                          } 
                        endif;
                      ?> 
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            
            <div class="row gy-2 mb-3">
              <?php if(!empty($prod_plan_trxno)): ?>
              <div class="col-sm-4">
                <button id="mbtn_mn_Update" type="submit" class="btn btn-dgreen btn-sm">Update</button>
                <!-- <button id="mbtn_mn_NTRX" type="button" class="btn btn-primary btn-sm">New Trx</button> -->
              </div>
              <?php else:?>
              <div class="col-sm-4">
                <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
                <!-- <button id="mbtn_mn_NTRX" type="button" class="btn btn-primary btn-sm">New Trx</button> -->
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
            <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Records</h3>
          </div>
          <div class="card-body">
            <div class="pt-2 bg-dgreen mt-2"> 
               <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                <a id="anchor-request" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> Request</a>
                <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item  p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> List</a>
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

    jQuery('#branch_name')
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
			source: '<?= site_url(); ?>search-tpa-branch/',  //mysearchdata/companybranch_v
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			search: function(oEvent, oUi) {
				var sValue = jQuery(oEvent.target).val();

			},
			select: function( event, ui ) {
				var terms = ui.item.value;
				jQuery('#branch_name').val(terms);
				jQuery(this).autocomplete('search', jQuery.trim(terms));
				return false;
			}
		})
		.click(function() {
			var terms = this.value;
			jQuery(this).autocomplete('search', jQuery.trim(terms));
	});	//end branch_name
    
    // jQuery('.branch_name')
    //     // don't navigate away from the field on tab when selecting an item
    //     .bind( 'keydown', function( event ) {
    //       if ( event.keyCode === jQuery.ui.keyCode.TAB &&
    //         jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
    //         event.preventDefault();
    //     }
    //     if( event.keyCode === jQuery.ui.keyCode.TAB ) {
    //       event.preventDefault();
    //     }
    //   })PRPL2305290000000005
    //     .autocomplete({
    //       minLength: 0,
    //       source: '<?= site_url(); ?>get-branch-list',
    //       focus: function() {
    //             // prevent value inserted on focus
    //             return false;
    //           },
    //           search: function(oEvent, oUi) {
    //               var sValue = jQuery(oEvent.target).val();
    //       //jQuery(oEvent.target).val('&mcocd=1' + sValue);
    //       //alert(sValue);
    //           },                       
    //           select: function( event, ui ) {
    //             var terms = ui.item.value;
                
    //             jQuery('#branch_name').val(terms);
    //             jQuery('#branch_name').attr("data-id-brnch-name",ui.item.mtkn_rid);
    //             jQuery('#branch_code').val(ui.item.BRNCH_OCODE2);

    //             jQuery(this).autocomplete('search', jQuery.trim(terms));

    //             return false;

                
    //           }
    //         })
    //     .click(function() {
    //       var terms = this.value;
    //       jQuery(this).autocomplete('search', jQuery.trim(terms));
          
    // }); //whse
    
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
            window.location = '<?=site_url();?>me-tp-alloc-vw';
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

    
    function __pack_totals() { 

            try { 
                var rowCount1 = jQuery('#tbl-fgpack tr').length;
            var adata1 = [];
            var adata2 = [];
            var mdata = '';
            var ninc = 0;
            var total = 0;
            for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-fgpack tr:eq(' + aa + ')').clone(); 
                var qty = jQuery(clonedRow).find('input[type=text]').eq(9).val();
                var QTY_TOTAL = parseFloat(qty);

                total = total + QTY_TOTAL;
               
            } 
            
            $('#txt_total_qty').val(total);
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
          var tpa_trxno = jQuery('#tpa_trxno').val();
          var prod_plan_trxno = jQuery('#prod_plan_trxno').val();
          var branch_name = jQuery('#branch_name').val();
          var active_plnt_id = jQuery('#active_plnt_id').val();
          var txt_request_date = jQuery('#txt_request_date').val();
          var entry_date = jQuery('#entry_date').val();
          var txt_total_qty = jQuery('#txt_total_qty').val();
          var edate = jQuery(this).attr('data-edate'); 
          var rowCount1 = jQuery('#tbl-fgpack tr').length;
          var adata1 = [];
          var adata2 = [];

          var mdata = '';
          var ninc = 0;

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-fgpack tr:eq(' + aa + ')').clone(); 
                var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //ITEM CODE
                var mdesc = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //UOM
                var mdmd = jQuery(clonedRow).find('input[type=text]').eq(9).val(); //QTY
                var mitemc_tkn = jQuery(clonedRow).find('input[type=hidden]').eq(1).val(); 
               
                mdata = mitemc + 'x|x' + mdesc + 'x|x' + mdmd + 'x|x' + mitemc_tkn;
                adata1.push(mdata);
                var mdat = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
                adata2.push(mdat);


            }  //end for

          var mparam = {
            mtkn_mntr:mtkn_mntr,
            tpa_trxno:tpa_trxno,
            prod_plan_trxno:prod_plan_trxno,
            branch_name: branch_name,
            active_plnt_id:active_plnt_id,
            txt_request_date:txt_request_date,
            entry_date:entry_date,
            txt_total_qty: txt_total_qty,
            adata1: adata1,
            adata2: adata2
          };  


      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-tp-alloc-save',
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

  $("#mbtn_mn_Update").click(function(e){
    try { 
          //__mysys_apps.mepreloader('mepreloaderme',true);
          var mtkn_mntr = jQuery('#__hmpacktrxnoid').val();
          var tpa_trxno = jQuery('#tpa_trxno').val();
          var branch_name = jQuery('#branch_name').val();
          var active_plnt_id = jQuery('#active_plnt_id').val();
          var txt_request_date = jQuery('#txt_request_date').val();
          var txt_total_qty = jQuery('#txt_total_qty').val();
          var rowCount1 = jQuery('#tbl-fgpack tr').length - 1;
          var adata1 = [];
          var adata2 = [];

          var mdata = '';
          var ninc = 0;

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-fgpack tr:eq(' + aa + ')').clone(); 
                var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //ITEM CODE
                var mdesc = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //UOM
                var mdmd = jQuery(clonedRow).find('input[type=text]').eq(2).val(); //QTY
                var mitemc_tkn = jQuery(clonedRow).find('input[type=hidden]').eq(1).val(); 
               
                mdata = mitemc + 'x|x' + mdesc + 'x|x' + mdmd + 'x|x' + mitemc_tkn;
                adata1.push(mdata);
                var mdat = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
                adata2.push(mdat);


            }  //end for

          var mparam = {
            mtkn_mntr:mtkn_mntr,
            tpa_trxno:tpa_trxno,
            branch_name: branch_name,
            active_plnt_id:active_plnt_id,
            txt_request_date:txt_request_date,
            txt_total_qty: txt_total_qty,
            adata1: adata1,
            adata2: adata2
          };  


      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-tp-alloc-update',
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
    $('#anchor-request').removeClass('active');
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    var mtkn_whse = '';
    rm_req_view_recs(mtkn_whse);

});

function rm_req_view_recs(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>me-tp-alloc-view",
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

  $('#anchor-request').on('click',function(){
    $('#anchor-request').addClass('active');
    $('#anchor-list').removeClass('active');
    $('#anchor-items').removeClass('active');
    var mtkn_whse = '';
    prpl_view_recs(mtkn_whse);

});

function prpl_view_recs(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>tp-alloc-req-view",
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