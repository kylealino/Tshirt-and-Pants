<?php 
/**
 *  File        : promodamage_main.php
 *  Author      : Arnel Oquien
 *  Date Created: July. 27, 2022
 */


$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$this->db_erp = $mydbname->medb(1);
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$mydatazua =  model('App\Models\MyDatauaModel');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$cuserrema = $mylibzdb->mysys_userrema();
$po_sysctrlno = $request->getVar('po_sysctrlno');


$mat_code='';

?>


<main id="main">
  <div class="pagetitle">
      <h1>FG Receiving</h1>
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html">Home</a></li>
              <li class="breadcrumb-item active">FG Receiving</li>
          </ol>
          </nav>
  </div><!-- End Page Title -->
        <div class="content-inner w-100">
          <!-- Forms Section-->
          <section class="forms"> 
          <div class="container-fluid">
              <div class="row" >
      
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-pencil-square"></i> Entry</h3>
                    </div>
                    <div class="card-body">
                    <div class="py-2 mt-3">
                    <?php if(!empty($po_sysctrlno)):?>
                      <h5 class="text-start text-dgreen fw-bold"> <span > FG CODE : </span> <?=$po_sysctrlno;?> </h5>
                      <input type="hidden" name="pono" id="pono" value="<?=$po_sysctrlno?>">
                    </div>
                    <hr class="prettyline shadow">
                    <div class="table-responsive"> 
                        <table class="table table-bordered table-hover table-sm text-center" id="tbl-items-received">
                          <thead class="thead-dark text-dgreen">
                            <tr>
                              <th nowrap="nowrap">ITEM / MATERIAL</th>
                              <th nowrap="nowrap">DESCRIPTION</th>
                              <th nowrap="nowrap">PACKAGING</th>
                              <th nowrap="nowrap">CONVF</th>
                              <th nowrap="nowrap" style="color:red;">QTY SCANNED</th>
                              <th nowrap="nowrap">TOTAL PCS</th>
                              <th nowrap="nowrap">ACTUAL QTY</th>
                              <th nowrap="nowrap">REMAINING QTY</th>
                            </tr>
                          </thead>
                          <?php endif;?>
                          <tbody id="tblItems">
                            <?php if(!empty($po_sysctrlno)):

                              $strRmng = "
                              SELECT po_sysctrlno,rmng_qty,rcv_tag, def_qty
                              FROM
                              gw_fg_po_dt
                              WHERE
                              `po_sysctrlno` = '{$po_sysctrlno}'
                              ";

                              $q1 = $mylibzdb->myoa_sql_exec($strRmng,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                              $rw = $q1->getResultArray();
                              foreach ($rw as $data) {
                                $rmng_qty = $data['rmng_qty'];
                                $def_qty = $data['def_qty'];
                              }


                                $str_itm="
                                SELECT 
                                b.`mat_code`, 
                                c.`ART_DESC`, 
                                c.`ART_UOM`, 
                                b.`convf`, 
                                b.`convf` AS `qty_scanned`,
                                b.`qty`,
                                b.`rmng_qty`
                                FROM
                                gw_fg_po_hd a
                                JOIN
                                gw_fg_po_dt b
                                ON 
                                a.`po_sysctrlno` = b.`po_sysctrlno`
                                JOIN
                                mst_article c
                                ON
                                b.`mat_code` = c.`ART_CODE`
                                WHERE
                                b.`po_sysctrlno` = '{$po_sysctrlno}' AND b.`rmng_qty` != '0.00000'
                                ";
                                $q = $mylibzdb->myoa_sql_exec($str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                $rw = $q->getResultArray();
                                foreach ($rw as $data) {
                                  $mat_code = $data['mat_code'];
                                  $ART_DESC = $data['ART_DESC'];
                                  $ART_UOM = $data['ART_UOM'];
                                  $convf = $data['convf'];
                                  $qty_scanned = $data['qty_scanned'];
                                  $qty = $data['qty'];
                                  $rmng_qty = $data['rmng_qty'];

                              ?>
  
                              <tr>
                                <td nowrap="nowrap" id="mat_code"><input type="text" name="mat_code" id="mat_code" class="mat_code text-center" value="<?=$mat_code;?>" style="border:none;" disabled></td>
                                <td nowrap="nowrap" id="ART_DESC"><?=$ART_DESC;?></td>
                                <td nowrap="nowrap" id="ART_UOM"><?=$ART_UOM;?></td>
                                <td nowrap="nowrap" id="convf"><?=$convf;?></td>
                                <td nowrap="nowrap" id="qty_scanned"><?=$qty_scanned;?></td>
                                <td nowrap="nowrap" id="qty"><?=$qty;?></td>
                                <td nowrap="nowrap" id="rcv_qty"><input type="text" name="rcv_qty" id="rcv_qty" class="rcv_qty text-center" value="<?=$rmng_qty;?>" style="border: 1px solid black; width:120px;"></td>
                                <td nowrap="nowrap" id="rmng_qty"><input type="text" name="rmng_qty" id="rmng_qty" class="rmng_qty text-center" value="<?=$rmng_qty;?>" style="border:none;  width:120px;" disabled></td>
                              </tr>
                              <?php 
                                }
                              
                          ?>
                        </tbody>
                      </table>
                      <div class="form-row py-3">
                              <button type="button" class="btn bg-dgreen btn-sm" id="btn-central-rcv">Receive</button>  
                            </div>
                      <?php else:?>  

                      <div id="mymodoutrecs">
                     <div class="text-center p-2 rounded-3  mt-4 border-dotted bg-light col-lg-6 offset-lg-3 p-4">
                        <h5><i class="bi bi-info-circle-fill text-dgreen"></i> Processing of FGPO's will display in here.</h5> 
                     </div>
                     
                    </div>
                    <?php 
                  endif;
                  ?>

                    </div>
                    </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                
                </div>


                <!-- Inline Form-->
                <div class="col-lg-12">                           
                  <div class="card ">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Records</h3>
                    </div>
                    <div class="card-body ">
                    <div class="pt-2  bg-dgreen mt-2"> 
                     <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                      <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> List</a>
                      <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> Items</a>
                     </nav>
                     </div>
                        
                      <div id="mymodoutentrecs" >
                      <div class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">

                      </div>
                      </div>
                    <!-- car body end -->
                  </div>
                </div>
              
              </div>

                </div>
              </div>
          </section>
          <?php
            echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
            echo $mylibzsys->memypreloader01('mepreloaderme');
            echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
            ?>  
          </main>


<script type="text/javascript">   



__mysys_apps.mepreloader('mepreloaderme',false);

    $('#btn-upload-wshe-rcv').click(function(){ 
      try {   

        var txtPlant     = jQuery('#txt-plant').val();
        var txtWarehouse = jQuery('#txt-warehouse').val(); 
        var txtWarehousetkn = jQuery('#txt-warehouse').attr('data-id'); 
        
        var file       = $('#rcv-upld-file').val();
        

        
        if($.trim(txtWarehouse) == ''){ 
          jQuery('#memsgtestent_danger').css({
            display: ''
          });
          jQuery('#memsgtestent_danger_bod').html('Select warehouse!');
          jQuery('#memsgtestent_danger').modal('show');
          return false;
        }

        if($.trim(file) == ''){ 
          jQuery('#myModSysMsgSubBod').css({
            display: ''
          });
          jQuery('#myModSysMsgSubBod').html('Please select file to upload!');
          jQuery('#myModSysMsgSub').modal('show');
          return false;
        }

        my_data = new FormData();
        my_data.append('rcv_file', $('#rcv-upld-file')[0].files[0]);
        my_data.append('txtWarehouse', txtWarehouse);
        my_data.append('txtWarehousetkn', txtWarehousetkn);

        __mysys_apps.mepreloader('mepreloaderme',true);
        $.ajax({ // default declaration of ajax parameters
          url: '<?=site_url()?>warehouse-rcvng-upld',
          method:"POST",
          context:document.body,
          data: my_data,
          contentType: false,
          global: false,
          cache: false,
          processData:false,
          success: function(data)  { //display html using divID
            __mysys_apps.mepreloader('mepreloaderme',false);
            jQuery('#mymodoutrecs').html(data);
            return false;
          },
          error: function() { // display global error on the menu function
            alert('error loading page...');
            
            return false;
          } 
        }); 
      } catch (err) {
        var mtxt = 'There was an error on this page.\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\nClick OK to continue.';
        
        alert(mtxt);
      } //end try
    }); 



//myrcvngcd_view_recs();
function myrcvngcd_view_recs(){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>fgpo-rcvng-recs",
        type: "post",
        data: {

        }
    });

      // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#mymodoutentrecs').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
          //$.hideLoading();
          // and do it again
          //setTimeout(get_if_stats, 5000);
      });
  };


      jQuery('#txt-plant' ) 
          // don't navigate away from the field on tab when selecting an item
          .bind( 'keydown', function( event ) {
              if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                  jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                  event.preventDefault();
          }
          if( event.keyCode === jQuery.ui.keyCode.TAB ) {
              event.preventDefault();
          }
          // if( event.keyCode === jQuery.ui.keyCode.BACKSPACE) {
          //             return false;
          // }
          var regex = new RegExp("^[a-zA-Z0-9\b]+$");
          var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
          if (!regex.test(key)) {
             event.preventDefault();
             return false;
          }
          })
          .autocomplete({
              minLength: 0,
              source: '<?=site_url();?>get-plant-list/',
              focus: function() {
          // prevent value inserted on focus
          return false;
          },
          search: function(oEvent, oUi) { 
              var sValue = jQuery(oEvent.target).val();
          //jQuery(oEvent.target).val('&mcocd=1' + sValue);
          //alert(sValue);
          },
          select: function( event, ui ) {

              var terms = ui.item.value;
              jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
              jQuery('#' + this.id).attr('title', jQuery.trim(terms));
              var plant_id = ui.item.mtkn_rid;
              jQuery('#txt-plant').attr("data-id",plant_id);
              //vw_brnchname(ui.item.mtkn_rid);
              this.value = ui.item.value;
              
              // null warehouse 
              jQuery('#txt-warehouse').attr('alt', '');
              jQuery('#txt-warehouse').attr('title', '');
              jQuery('#txt-warehouse').attr('data-id', '');
              jQuery('#txt-warehouse').val('');


             return false;
          }
          })
          .click(function() { 
          //jQuery(this).keydown(); 
          var terms = this.value.split('|');
          //jQuery(this).autocomplete('search', '');
          jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
          });
          // *****//end plant




$('#anchor-list').on('click',function(){
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    myrcvngcd_view_recs();

});

$("#btn-central-rcv").click(function(e){
       
       try { 
         //__mysys_apps.mepreloader('mepreloaderme',true);
         var mtkn_mntr = jQuery('#__hmpromotrxnoid').val();
         var pono = jQuery('#pono').val();
         var rowCount1 = jQuery('#tbl-items-received tr').length;
         var adata1 = [];
         var adata2 = [];

         var mdata = '';
         var ninc = 0;

         for(aa = 1; aa < rowCount1; aa++) { 
           var clonedRow = jQuery('#tbl-items-received tr:eq(' + aa + ')').clone(); 
           var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); 
           var act_qty = jQuery(clonedRow).find('input[type=text]').eq(1).val(); 
           var rmng_qty = jQuery(clonedRow).find('input[type=text]').eq(2).val(); 

           mdata = mitemc + 'x|x' + act_qty + 'x|x' + rmng_qty;
           adata1.push(mdata);


           }  //end for

           var mparam = {
             mtkn_mntr:mtkn_mntr,
             pono: pono,
             adata1: adata1

           };  

           console.log(mparam);
           
           $.ajax({ 
             type: "POST",
             url: '<?=site_url();?>fgpo-rcvng-store',
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

</script>