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
$mtkn_trxno = $request->getVar('mtkn_trxno');

$mmnhd_rid   ='';
$txtpout_typ = '';
$str_style   ='';
$str_dis     = "";
$txt_branch  = '';
$trx_no      = '';
$startDate   = '';
$endDate     = '';
$percentDisc = '';
$pesoDisc    ='';
$txt_branchID = '';
$str_style = '';
$btn_save = 'Save';
$post_tag = '';
$pd_stats = '';
$intText  = ''; //to disabled text
$endTime = '';
$startTime = '';


?>


<main id="main">
  <div class="pagetitle">
      <h1>Warehouse Receiving</h1>
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html">Home</a></li>
              <li class="breadcrumb-item active">Warehouse Receiving</li>
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
                      <h3 class="h4 mb-0"><i class="bi bi-pencil-square"></i> Entry</h3>
                    </div>
                    <div class="card-body">
                      <div class=" col-lg-6 offset-lg-3">
                      <div class="mt-2 col-12">
                        <label for="branch-name">Plant</label>
                        <div class="input-group"> 
                        <input type="text" id="txt-plant" name="txt-plant" class="form-control form-control-sm">
                        <div class="input-group-text px-1 py-0"> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                        </div>
                      </div>
                       <div class="mt-2 col-12">
                        <label for="branch-name">Warehouse</label>
                        <div class="input-group">
                        <input type="text" id="txt-warehouse" name="txt-warehouse" class="form-control form-control-sm">
                        <div class="input-group-text px-1 py-0"> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                        </div>
                      </div>
                     <div class="input-group input-group-sm mb-3 mt-3">
                       <input type="file" class="form-control form-control-sm" id="rcv-upld-file" placeholder="Search Transaction/Branch" aria-label="mytxtsearchrec" aria-describedby="basic-addon1">
                       <div class="input-group-prepend" id="basic-addon1">
                         <button type="button" id="btn-upload-wshe-rcv" class="btn btn-dgreen btn-sm m-0 rounded-0 rounded-end" ><i class="bi bi-upload"></i> Upload</button>
                       </div>
                     </div>
                     </div> 
                    <div id="mymodoutrecs">
                     <div class="text-center p-2 rounded-3  mt-2 border-dotted bg-light col-lg-6 offset-lg-3 p-4">
                        <h5><i class="bi bi-info-circle-fill text-dgreen"></i> Uploaded barcodes will display in here.</h5> 
                     </div>
                     
                    </div>

                    </div>
                  </div>
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
                         <h5> <i class="bi bi-info-circle-fill  text-dgreen"></i> Select plant and warehouse to display records.</h5> 
                      </div>
                      </div>
                    <!-- car body end -->
                  </div>
                </div>
              
              </div>
          
              <div class="modal fade text-start" id="myModSysMsg" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="myModalLabel">System Message</h5>
                          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body"  id="myModalSysMsgBod">
                          
                        </div>
                        <div class="modal-footer">
                          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                          <!-- <button class="btn btn-primary" type="button">Save changes</button> -->
                        </div>
                      </div>
                    </div>
                  </div>


                  <div class="modal fade text-start" id="entmeapproval" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                     <div class="modal-dialog">
                       <div class="modal-content">
                         <div class="modal-header">
                          <h6 class="modal-title">Promo Damage Approval</h6>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">  <span aria-hidden="true">&times;</span></button>
                         </div>
                         <div class="modal-body" id="entmeapproval_bod">
                           
                         </div>
                         <div class="modal-footer">
                           <!-- <button class="btn btn-secondary" type="button" onclick='OnModalReload()' data-bs-dismiss="modal">Close</button> -->
                         </div>
                       </div>
                     </div>
                   </div>

                   <div class="modal fade text-start" id="myImageModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                     <div class="modal-dialog modal-lg">
                       <div class="modal-content">
                         <div class="modal-header">
                           <h5 class="modal-title" id="myModalLabel">Files Uploaded</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">  <span aria-hidden="true">&times;</span></button>
                         </div>
                         <div class="modal-body" id="myImageModalBod">
                           
                         </div>
                         <div class="modal-footer">
                           <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                         </div>
                       </div>
                     </div>
                   </div>

                </div>
              </div>
          </section>
          <?php
            echo $mylibzsys->memypreloader01('mepreloaderme');
            echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
            echo $mylibzsys->memsgbox1('memsgtestent_success','<i class="bi bi-check-circle"></i> System Alert','...','bg-psuccess');
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
function myrcvngcd_view_recs(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>warehouse-rcvng-recs",
        type: "post",
        data: {
            mtkn_whse: mtkn_whse
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

  jQuery('#txt-warehouse' ) 
          // don't navigate away from the field on tab when selecting an item
          .bind( 'keypress', function( event ) {
              if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                  jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                  event.preventDefault();
          }
          if( event.keyCode === jQuery.ui.keyCode.TAB ) {
              event.preventDefault();
          }

          var regex = new RegExp("^[a-zA-Z0-9\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
          })
         
          .autocomplete({
              minLength: 0,
              source: '<?=site_url();?>get-cdwarehouse-list/',
              focus: function() {
          // prevent value inserted on focus
          return false;
          },
          search: function(oEvent, oUi) { 
              var sValue = jQuery(oEvent.target).val();
              var plant = jQuery('#txt-plant').attr("data-id");
              jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-cdwarehouse-list/?mtkn_plnt=' + plant); 
          },
          select: function( event, ui ) {

              var terms = ui.item.value;
              jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
              jQuery('#' + this.id).attr('title', jQuery.trim(terms));
              jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));
              var wshe_id = ui.item.mtkn_rid;
              jQuery('#txt-warehouse').attr("data-id",wshe_id);
              myrcvngcd_view_recs(wshe_id);
              this.value = ui.item.value; 

              $('#anchor-list').addClass('active');
              $('#anchor-items').removeClass('active');

             return false;
          }
          })
          .click(function() { 
          //jQuery(this).keydown(); 
          var terms = this.value.split('|');
          //jQuery(this).autocomplete('search', '');
          jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
          });



$('#anchor-list').on('click',function(){
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    myrcvngcd_view_recs($('#txt-warehouse').attr("data-id"));

});

</script>