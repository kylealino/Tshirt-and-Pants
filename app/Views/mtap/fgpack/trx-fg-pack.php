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

$txt_branch = '';
$mtkn_txt_branch = '';

$txtactive_plnt_id = "";
$txtactive_wshe_id = "";
$mtkn_active_plnt_id ='';
$mtkn_active_wshe_id ='';
$splnt_id = '';
$swshe_id = '';
$mencd_date       = date("Y-m-d");  

$mtkn_trxno = $request->getVar('mtkn_trxno');

$txt_packtrxno = '';
$noofpacks = '';
$txt_remk = '';
$txtpack_totals = '';
$txtpack_qty = '';
$mpohd_rid = '';
$nporecs = 0;

if(!empty($mtkn_trxno)) {
$str = "
    select aa.*,
    
    e.plnt_code,
    d.wshe_code,
    f.`BRNCH_NAME`,
    sha2(concat(aa.`plnt_id`,'{$mpw_tkn}'),384) plnt_id,
    sha2(concat(aa.`wshe_id`,'{$mpw_tkn}'),384) wshe_id
    from ((({$this->db_erp}.`gw_fg_pack_hd` aa 
    join  {$this->db_erp}.`mst_plant`  e
    ON (aa.`plnt_id` = e.`recid`))
    join  {$this->db_erp}.`mst_wshe`  d
    ON (aa.`wshe_id` = d.`recid`))
    join  {$this->db_erp}.`mst_companyBranch`  f
    ON (aa.`branch_rid` = d.`recid`))
     where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_trxno' 
        ";

$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();
//$mtkn_trxno = $rw['mtkn_trxno'];
$mpohd_rid = $rw['recid'];
$txt_packtrxno = $rw['fgpack_trxno'];
$noofpacks = $rw['noofpack'];
$txt_remk = $rw['rmks'];

$txtactive_plnt_id = $rw['plnt_code'];
$txtactive_wshe_id = $rw['wshe_code'];
$mtkn_active_plnt_id = $rw['plnt_id'];
$mtkn_active_wshe_id = $rw['wshe_id'];

$splnt_id = $rw['plnt_id'];
$swshe_id = $rw['wshe_id'];

$txt_branch = $rw['BRNCH_NAME'];
$mtkn_txt_branch = $rw['branch_rid'];
}

?>
<main id="main">

    <div class="pagetitle">
        <h1>FG Packing Production</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">FG Packing Production</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->

  <div class="row mb-3 me-form-font">
      <span id="__me_numerate_wshe__" ></span>
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title">FG Packing Production</h6>
            <?=form_open('me-fg-packing-save','class="needs-validation" id="myfrms_customer" ');?>
            <div class="row">
              <div class="col-lg-6">
                <div class="row mb-3">
                      <label class="col-sm-3 form-label" for="txt_packtrxno">Pack Series:</label>
                      <div class="col-sm-9">
                          <input type="text" id="txt_packtrxno" name="txt_packtrxno" class="form-control form-control-sm" value="<?=$txt_packtrxno;?>" readonly/>
                          <input type="hidden" id="__hmpacktrxnoid" name="__hmpacktrxnoid" class="form-control form-control-sm" value="<?=$mtkn_trxno;?>"/>
                      </div>
                  </div> <!-- end Acct No. -->
                  <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="active_plnt_id">Plant:</label>
                      <div class="col-sm-9">
                          <input type="text" data-id-plant="?=$mtkn_active_plnt_id;?>" id="active_plnt_id" name="active_plnt_id" class="active_plnt_id form-control form-control-sm " value="<?=$txtactive_plnt_id;?>" required/>
                      </div>
                  </div> <!-- end Birth Date--> 

                   <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="active_wshe_id">Warehouse:</label>
                      <div class="col-sm-9">
                          <input type="text" data-id-whse="<?=$mtkn_active_wshe_id;?>" id="active_wshe_id" name="active_wshe_id" class="active_wshe_id form-control form-control-sm " value="<?=$txtactive_wshe_id;?>" required/>
                      </div>
                  </div> <!-- end Birth Date--> 
                  <div class="row gy-2 mb-3">
                      <label class="col-sm-3 form-label" for="txt_branch">Branch:</label>
                      <div class="col-sm-9">
                          <input type="text" data-id-brnch="<?=$mtkn_txt_branch;?>" id="txt_branch" name="txt_branch" class="txt_branch form-control form-control-sm " value="<?=$txt_branch;?>" required/>
                      </div>
                  </div> <!-- end Birth Date--> 
               </div>
              <div class="col-lg-6">            
              
              <div class="row gy-2 mb-3">
                <label class="col-sm-3 form-label" for="noofpacks">No. of Packs:</label>
                <div class="col-sm-9">
                  <input type="text" id="noofpacks" name="noofpacks" class="form-control form-control-sm" value="<?=$noofpacks;?>" required/>
                  
                </div>
              </div> <!-- end Barangay -->
              <div class="row gy-2 mb-3">
                <label class="col-sm-3 form-label" for="txt_remk">Remarks</label>
                <div class="col-sm-9">
                  <input type="text" id="txt_remk" name="txt_remk" class="form-control form-control-sm" value="<?=$txt_remk;?>" required/>
                  
                </div>
              </div> <!-- end Barangay -->
            
              <div class="row gy-2 mb-3">
                <label class="col-sm-3 form-label" for="txtpack_totals">Total Amount:</label>
                <div class="col-sm-9">
                  <input type="text" id="txtpack_totals" name="txtpack_totals" class="form-control form-control-sm" value="<?=$txtpack_totals;?>" readonly/>
                </div>
              </div>
              <div class="row gy-2 mb-3">
                <label class="col-sm-3 form-label" for="txtpack_qty">Total Qty:</label>
                <div class="col-sm-9">
                  <input type="text" id="txtpack_qty" name="txtpack_qty" class="form-control form-control-sm" value="<?=$txtpack_qty;?>" readonly/>
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
                        <th nowrap="nowrap">UOM</th>
                        <th nowrap="nowrap">Qty</th>
                        <th nowrap="nowrap">Price</th>
                        <th nowrap="nowrap">Total Amount</th>
                        <th nowrap="nowrap">Remarks</th>
                        
                      </tr>
                    </thead>
                    <tbody id="gwpo-recs">
                      <?php
                      $nn=1;

                      $str = "
                          SELECT
                              a.*,
                              SHA2(CONCAT(a.`recid`,'{$mpw_tkn}'),384) mtkn_podttr,
                              SHA2(CONCAT(b.`recid`,'{$mpw_tkn}'),384) mtkn_artmtr,
                              b.`ART_DESC`,
                              b.`ART_NCBM`,
                              b.`ART_UOM`,
                              b.`ART_NCONVF`,
                              SHA2(CONCAT(c.`recid`,'{$mpw_tkn}'),384) mtkn_plnt,
                              SHA2(CONCAT(d.`recid`,'{$mpw_tkn}'),384) mtkn_wshe,
                              c.`plnt_code` AS `wshe_plant`,
                              d.`wshe_code` AS `wshe_loc`
                          FROM
                              {$this->db_erp}.`gw_fg_pack_dt` a
                          JOIN
                              {$this->db_erp}.`mst_article` b
                          ON
                              a.`mat_rid` = b.`recid`
                          JOIN
                              {$this->db_erp}.`mst_plant` c
                          ON
                              a.`plnt_id` = c.`recid`
                          JOIN
                              {$this->db_erp}.`mst_wshe` d
                          ON
                              a.`wshe_id` = d.`recid` AND c.`recid` = d.`plnt_id`
             
                          JOIN
                              {$this->db_erp}.`gw_fg_pack_hd` h
                          ON
                              a.`fgpackhd_rid` = h.`recid`
                          WHERE
                             a.`fgpackhd_rid` = '{$mpohd_rid}'
                          ORDER BY 
                              a.`recid`
                      ";
                      //var_dump($str);
                      //die();
                      $q =  $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                      //var_dump($str);
                      $bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
                      $on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\""; 
                      $rrec = $q->getResultArray();
                      foreach($rrec as $rdt){
                          $bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
                          $on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";
                          $nporecs++;
                        
                          $nqty = $rdt['qty'];
                          $nprice = $rdt['uprice'];
                            
                      ?>

                      <tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
                        <td><?=$nporecs;?></td>
                        <td nowrap="nowrap">
                          <!-- <button type="button" class="btn btn-xs btn-danger" onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button> -->
                          <input class = "mitemrid" type="hidden" value="<?=$rdt['mtkn_artmtr'];?>"/>
                          <input type="hidden" value="<?=$rdt['mtkn_podttr'];?>"/>
                         
                          

                        </td>
                        
                        <td nowrap="nowrap"><input type="text" id="fld_mitemcode_<?=$nporecs;?>" class="form-control form-control-sm mitemcode" size="20" value="<?=$rdt['mat_code'];?>" ></td><!--0 ITEMC -->
                        <td nowrap="nowrap"><input type="text" id="mitemdesc_<?=$nporecs;?>" class="form-control form-control-sm" size="20" value="<?=$rdt['ART_DESC'];?>" readonly="readonly"></td><!--1 DESC -->
                        <td nowrap="nowrap"><input type="text" id="mitemuom_<?=$nporecs;?>" size="5" class="form-control form-control-sm" value="<?=$rdt['ART_UOM'];?>" readonly="readonly" ></td><!--2 UOM -->
                        <td nowrap="nowrap"><input type="text" id="mqty<?=$nporecs;?>" size="5" class="form-control form-control-sm" value="<?=$nqty;?>" ></td><!--3 QTY -->
                        <td nowrap="nowrap"><input type="text" id="mitemprice_<?=$nporecs;?>" onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();" onblur="javascript:__pack_totals();" size="5" class="form-control form-control-sm" value="<?=$nprice;?>" readonly="readonly" ></td><!--4 price -->
                        <td nowrap="nowrap"><input type="text" id="mitemtamt_<?=$nporecs;?>" size="5" class="form-control form-control-sm" value="<?=$rdt['tamt'];?>" readonly="readonly" ></td><!--5 TAMT -->
                        <td nowrap="nowrap"><input type="text" id="me_text<?=$nporecs;?>" size="5" class="form-control form-control-sm" value="<?=$rdt['rems'];?>" ></td><!--6 REMARKS -->
                      </tr>
                      <?php 
                        $nn++;
                          } //end foreach 
                        //}//endif
                        $q->freeResult();
                      ?>
                      <tr style="display: none;">
                        <td></td>
                        <td nowrap="nowrap">
                          <button type="button" class="btn btn-xs btn-danger" onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button>
                          <input class="mitemrid" type="hidden" value=""/>
                          <input type="hidden" value=""/>
                         
                          
                        </td>
                        <td nowrap="nowrap"><input type="text" class="form-control form-control-sm mitemcode" size="20"></td> <!--0 ITEMC -->
                        <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" size="20" readonly="readonly"></td> <!--1 DESC -->
                        <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm" readonly="readonly" ></td> <!--2 UOM -->
                        <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm" ></td> <!--3 QTY -->
                        <td nowrap="nowrap"><input type="text" onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();" onblur="javascript:__pack_totals();" size="5" class="form-control form-control-sm" readonly="readonly" ></td> <!--4 Price -->
                        <td nowrap="nowrap"><input type="text" onmouseover="javascript:__pack_totals();" size="5" class="form-control form-control-sm" readonly="readonly" ></td> <!--5 TAMT -->
                        <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm"></td> <!--6 REMARKS -->
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="row gy-2 mb-3">
              <div class="col-sm-4">
                <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
                <button id="mbtn_mn_NTRX" type="button" class="btn btn-primary btn-sm">New Trx</button>
              </div>
            </div> <!-- end Save Records -->
            <?=form_close();?> <!-- end of ./form -->
            </div> <!-- end card-body -->
          </div>
    </div>

    <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title">Records</h6>
            <div class="pt-2 bg-dgreen mt-2"> 
               <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> Records</a>
                <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> For Approval</a>
               </nav>
               </div>
                  
               <div id="packlist" class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">
                    <?php
                       // $data = $mytrxfgpack->purch_rec_view(1,20);
                       // echo view('mtap/trx-fg-packing-order-recs',$data);
                    ?> 
                </div> 
          </div> <!-- end card-body -->
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
            window.location = '<?=site_url();?>me-fg-packing-vw';
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
          jQuery(clonedRow).find('input[type=text]').eq(2).attr('id','mitemuom_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(3).attr('id','mitemqty_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(4).attr('id','mitemprice_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(5).attr('id','mitemtamt_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(6).attr('id','txt-mtext-' + mid);
          
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
              source: '<?= site_url(); ?>get-itemc',
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
                  var xobjitemuom = jQuery(clonedRow).find('input[type=text]').eq(2).attr('id');/*PACKAGING*/
                  var xobjitemprice = jQuery(clonedRow).find('input[type=text]').eq(4).attr('id');/*price*/
                  
                  $('#' + xobjitemrid).val(ui.item.mtkn_rid);
                  $('#' + xobjitemdesc).val(ui.item.ART_DESC);
                  $('#' + xobjitemuom).val(ui.item.ART_UOM);
                  $('#' + xobjitemprice).val(ui.item.ART_UPRICE);
                 

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
                var rowCount1 = jQuery('#tbl-fgpack tr').length - 1;
            var adata1 = [];
            var adata2 = [];
            var mdata = '';
            var ninc = 0;
            var nTAmount = 0;
            var nTQty = 0;
            for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-fgpack tr:eq(' + aa + ')').clone(); 
                var qty = jQuery(clonedRow).find('input[type=text]').eq(3).val();
                var price = jQuery(clonedRow).find('input[type=text]').eq(4).val();
                var xTAmntId = jQuery(clonedRow).find('input[type=text]').eq(5).attr('id');
              
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
            
            $('#txtpack_qty').val(__mysys_apps.oa_addCommas(nTQty));
            $('#txtpack_totals').val(__mysys_apps.oa_addCommas(nTAmount));
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
          var txt_packtrxno = jQuery('#txt_packtrxno').val();
          var active_plnt_id = jQuery('#active_plnt_id').val();
          var active_wshe_id = jQuery('#active_wshe_id').val();
          var noofpacks = jQuery('#noofpacks').val();
          var txt_remk = jQuery('#txt_remk').val();
          
          var txtpack_totals = jQuery('#txtpack_totals').val();
          var txtpack_qty = jQuery('#txtpack_qty').val();

          var mtkn_plnt = jQuery('#active_plnt_id').attr("data-id-plant");
          var mtkn_whse = jQuery('#active_wshe_id').attr("data-id-whse");

          var txt_branch = jQuery('#txt_branch').val();
          var mtkn_branch = jQuery('#txt_branch').attr("data-id-brnch");
          var rowCount1 = jQuery('#tbl-fgpack tr').length - 1;
          var adata1 = [];
          var adata2 = [];

          var mdata = '';
          var ninc = 0;

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-fgpack tr:eq(' + aa + ')').clone(); 
                var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //ITEM CODE
                var muom = jQuery(clonedRow).find('input[type=text]').eq(2).val(); //UOM
                var mqty = jQuery(clonedRow).find('input[type=text]').eq(3).val(); //QTY
                var mprice = jQuery(clonedRow).find('input[type=text]').eq(4).val(); //PRICE
                var mtamt = jQuery(clonedRow).find('input[type=text]').eq(5).val(); //TAMT
                var mremks = jQuery(clonedRow).find('input[type=text]').eq(6).val(); //STEXT
                var mitemc_tkn = jQuery(clonedRow).find('input[type=hidden]').eq(1).val(); 
               
                
               
                mdata = mitemc + 'x|x' + muom + 'x|x' + mqty + 'x|x' + mprice + 'x|x' + mtamt + 'x|x' + mremks + 'x|x' + mitemc_tkn;
                adata1.push(mdata);
                var mdat = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
                adata2.push(mdat);


            }  //end for

          var mparam = {
            mtkn_mntr:mtkn_mntr,
            txt_packtrxno:txt_packtrxno,
            active_plnt_id: active_plnt_id,
            active_wshe_id: active_wshe_id,
            noofpacks: noofpacks,
            txt_remk: txt_remk,
            txtpack_totals: txtpack_totals,
            txtpack_qty: txtpack_qty,
            txt_branch:txt_branch,
            mtkn_branch:mtkn_branch,
            adata1: adata1,
            adata2: adata2
          };  

  
      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-fg-packing-save',
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
    mypack_view_recs(mtkn_whse);

});

function mypack_view_recs(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>me-fg-packing-view",
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

  $('#anchor-items').on('click',function(){
    $('#anchor-items').addClass('active');
    $('#anchor-list').removeClass('active');
    var mtkn_whse = '';
    mypack_view_appr(mtkn_whse);

});

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