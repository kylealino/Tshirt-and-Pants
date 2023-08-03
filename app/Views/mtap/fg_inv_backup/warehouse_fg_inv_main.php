<?php 
/**
 *  File        : warehouse_inv_main.php
 *  Author      : Arnel Oquien
 *  Date Created: Dec. 02, 2022
 */


$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$this->db_erp = $mydbname->medb(1);
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$mydatazua =  model('App\Models\MyDatauaModel');
$mymelibsys =  model('App\Models\Mymelibsys_Model');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$cuserrema = $mylibzdb->mysys_userrema();


?>


<main id="main">
  <?php $active_warehouse_data = $mymelibsys->getCDActivePlantWarehouse();?>
  <div class="pagetitle">
      <h1>Finish Goods Inventory</h1>
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php site_url() ?>warehouse-inv">Home</a></li>
              <li class="breadcrumb-item active">FG Inventory</li>
              <!-- <li class="breadcrumb-item"><a href="<?php site_url() ?>warehouse-inv#reports-div">Reports</a></li>
              <li class="breadcrumb-item"><a href="<?php site_url() ?>warehouse-inv#incoming-div">Incoming Items</a></li>
              <li class="breadcrumb-item"><a href="<?php site_url() ?>warehouse-inv#outbound-div">Outbound Items</a></li> -->
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
                      <h3 class="h4 mb-0"><i class="bi bi-pencil-square"></i> Plant and Warehouse</h3>
                    </div>
                    <div class="card-body">
                      <div class=" col-lg-6 offset-lg-3">
                      <div class="mt-2 col-12">
                        <label for="branch-name">Plant</label>
                        <div class="input-group"> 
                        <input type="text" id="txt-plant" name="txt-plant" class="form-control form-control-sm" value="<?=$active_warehouse_data['plant_code']?>" data-id="<?=$active_warehouse_data['mtkn_plant']?>" >
                        <div class="input-group-text px-1 py-0"> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                        </div>
                      </div>
                       <div class="mt-2 col-12">
                        <label for="branch-name">Warehouse</label>
                        <div class="input-group">
                        <input type="text" id="txt-warehouse" name="txt-warehouse" class="form-control form-control-sm" value="<?=$active_warehouse_data['wshe_code']?>"  data-id="<?=$active_warehouse_data['mtkn_whse']?>" >
                        <div class="input-group-text px-1 py-0"> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                        </div>
                      </div>
                     </div> 
                    </div>
                  </div>
                </div>
                <!-- Inline Form-->
                <div class="col-lg-12">                           
                  <div class="card ">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Raw Materials Stock code list</h3>
                    </div>
                    <div class="card-body ">
                      <div id="mymodoutentrecs" >
                      <div class="text-center p-2 rounded-3  mt-3 border-dotted bg-light shadow-sm ">
                         <h5> <i class="bi bi-info-circle-fill  text-dgreen"></i> Select plant and warehouse to display records.</h5> 
                      </div>
                      </div>
                    <!-- car body end -->
                  </div>
                </div>
              </div>
                <!-- REPORTS -->
                <!-- <div class="col-lg-12" id="reports-div">                           
                  <div class="card ">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-graph-up-arrow"></i> Reports</h3>
                    </div>
                    <div class="card-body ">
                      <div id="mymodoutrpt" >
                
                      </div> -->
                    <!-- car body end -->
                  <!-- </div>
                </div>
              </div>  -->
              <!-- REPORTS END -->

              <!-- INCOMING -->
  <!--               <div class="col-lg-12" id="incoming-div">                           
                  <div class="card ">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-box-arrow-in-down"></i> Incoming Items</h3>
                    </div>
                    <div class="card-body ">
                      <div id="mymodoutincoming" >
                
                      </div>
                  </div>
                </div>
              </div>  -->
              <!-- INCOMING END -->

              <!-- OUTBOUND -->
    <!--             <div class="col-lg-12" id="outbound-div">                           
                  <div class="card ">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-box-arrow-in-up"></i> Outbound Items</h3>
                    </div>
                    <div class="card-body ">
                      <div id="mymodoutoutbound" >
                
                      </div>
                  </div>
                </div>
              </div>  -->
              <!-- OUTBOUND END -->

              <!-- RAK/BIN TRANSFER -->
<!--                 <div class="col-lg-12" id="rackbintrans-div">                           
                  <div class="card ">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-box-arrow-in-up"></i>Rack/Bin Transfer </h3>
                    </div>
                    <div class="card-body ">
                      <div id="mymodoutrackbintrans" >
                       <div class="text-center p-2 rounded-3  mt-4 border-dotted bg-light shadow-sm ">
                          <h5><i class="bi bi-info-circle-fill  text-dgreen"></i> Click <a class="transfer-manual-anchor text-dgreen" href="<?php site_url() ?>warehouse-inv#rackbintrans-div">here</a> to load.</h5> 
                       </div>
                      </div>
                  </div>
                </div>
              </div>  -->
              <!-- RAK/BIN TRANSFER END -->

              <!-- RAK/BIN TRANSFER UPLOAD-->
<!--                 <div class="col-lg-12" id="rackbintrans-upload-div">                           
                  <div class="card ">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-box-arrow-in-up"></i>Rack/Bin Transfer Upload </h3>
                    </div>
                    <div class="card-body ">
                      <div id="myrackbintrans-upload" >
                      <div class="text-center p-2 rounded-3  mt-4 border-dotted bg-light shadow-sm ">
                         <h5><i class="bi bi-info-circle-fill  text-dgreen"></i> Click <a class="transfer-upld-anchor text-dgreen" href="<?php site_url() ?>warehouse-inv#rackbintrans-div">here</a> to load.</h5> 
                      </div>
                      </div>
                  </div>
                </div>
              </div>  -->
              <!-- RAK/BIN TRANSFER UPLOAD END -->

              </div>
              </div>
          </section>
          <?php
            echo $mylibzsys->memypreloader01('mepreloaderme');
            echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
            echo $mylibzsys->memsgbox1('memsgtestent_success','<i class="bi bi-check-circle"></i> System Alert','...','bg-psuccess');
            echo $mylibzsys->memsgbox2('boxcontent_success','<i class="bi bi-ui-radios"></i> Box content','...','bg-psuccess','modal-xl');
            ?>  
          </main>


<script type="text/javascript"  >   

__mysys_apps.mepreloader('mepreloaderme',false);

myrcvngcd_view_recs(jQuery('#txt-warehouse').attr("data-id"));
function myrcvngcd_view_recs(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>fgpo-inv-item-recs",
        type: "POST",
        data: {
            mtkn_whse: mtkn_whse
        }
    });

      // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
      jQuery('#mymodoutentrecs').html(response);
          //$.hideLoading();
          // and do it again
          //setTimeout(get_if_stats, 5000);
      });

  };

myinv_reportview(jQuery('#txt-warehouse').attr("data-id"));
function myinv_reportview(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>warehouse-inv-reports",
        type: "POST",
        data: {
            mtkn_whse: mtkn_whse
        }
    });

      // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#mymodoutrpt').html(response);
       __mysys_apps.mepreloader('mepreloaderme',false);
          //$.hideLoading();
          // and do it again
          //setTimeout(get_if_stats, 5000);
      });
  };

//myinv_incoming(jQuery('#txt-warehouse').attr("data-id"));
function myinv_incoming(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>warehouse-inv-incoming",
        type: "POST",
        data: {
            mtkn_whse: mtkn_whse
        }
    });

      // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#mymodoutincoming').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
          //$.hideLoading();
          // and do it again
          //setTimeout(get_if_stats, 5000);
      });
  };

 // myinv_outbound(jQuery('#txt-warehouse').attr("data-id"));
  function myinv_outbound(mtkn_whse){ 
      var ajaxRequest;

      ajaxRequest = jQuery.ajax({
          url: "<?=site_url();?>warehouse-inv-outbound",
          type: "POST",
          data: {
              mtkn_whse: mtkn_whse
          }
      });

        // Deal with the results of the above ajax call
      __mysys_apps.mepreloader('mepreloaderme',true);
        ajaxRequest.done(function(response, textStatus, jqXHR) {
            jQuery('#mymodoutoutbound').html(response);
            __mysys_apps.mepreloader('mepreloaderme',false);
            //$.hideLoading();
            // and do it again
            //setTimeout(get_if_stats, 5000);
        });
    };


  $('.transfer-manual-anchor').on('click',function(){
    myinv_rackbintrans(jQuery('#txt-warehouse').attr("data-id"));
  });

  $('.transfer-upld-anchor').on('click',function(){
    myinv_rackbintrans_upload(jQuery('#txt-warehouse').attr("data-id"));
  });


    
    function myinv_rackbintrans(mtkn_whse){ 
        var ajaxRequest;

        ajaxRequest = jQuery.ajax({
            url: "<?=site_url();?>warehouse-inv-rackbintrans",
            type: "POST",
            data: {
                mtkn_whse: mtkn_whse
            }
        });

          // Deal with the results of the above ajax call
        __mysys_apps.mepreloader('mepreloaderme',true);
          ajaxRequest.done(function(response, textStatus, jqXHR) {
              jQuery('#mymodoutrackbintrans').html(response);
              __mysys_apps.mepreloader('mepreloaderme',false);
              //$.hideLoading();
              // and do it again
              //setTimeout(get_if_stats, 5000);
          });
      };


   
    function myinv_rackbintrans_upload(mtkn_whse){ 
        var ajaxRequest;

        ajaxRequest = jQuery.ajax({
            url: "<?=site_url();?>warehouse-inv-rackbintrans-upload",
            type: "POST",
            data: {
                mtkn_whse: mtkn_whse
            }
        });

          // Deal with the results of the above ajax call
        __mysys_apps.mepreloader('mepreloaderme',true);
          ajaxRequest.done(function(response, textStatus, jqXHR) {
              jQuery('#myrackbintrans-upload').html(response);
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
             // myinv_incoming(wshe_id);
             // myinv_outbound(wshe_id);
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

         


  function frack_lookup(){
        jQuery('.frack_lookup' ) 
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
              source: '<?= site_url(); ?>get-warehouse-group/?',
              focus: function() {
                  // prevent value inserted on focus
                  return false;
              },
              search: function(oEvent, oUi) { 
                  var sValue = jQuery(oEvent.target).val();
                  var mtkn_uid = '';
                  var type = $(this).data('type'); 
                  if(type == 'R'){
                     mtkn_uid = jQuery('#txt-report-wshe').attr('data-id');
                     console.log('R',mtkn_uid);
                  }
                  else{
                     mtkn_uid = jQuery('#txt-warehouse').attr("data-id");
                     console.log('T',mtkn_uid);
                  }

                  $(this).autocomplete('option', 'source', '<?=site_url();?>get-warehouse-group?mtkn_uid='+mtkn_uid);
              },
              select: function( event, ui ) {
                  var terms = ui.item.value;
                  
                  jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                  jQuery(this).attr('title', jQuery.trim(ui.item.value));
                  jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));
                  this.value = ui.item.value;

                  $('#txt-rtransfer-bin').val('');
                  $('#txt-rtransfer-bin').attr("data-id","");

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



    function fbin_lookup( ) { 
      $('.fbin_lookup' ) 
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
              source: '<?= site_url(); ?>get-warehouse-sbin',
              focus: function() {
                  // prevent value inserted on focus
                  return false;
              },
              search: function(oEvent, oUi) { 
                  var sValue = jQuery(oEvent.target).val();
              
                  var mtkn_uid = '';
                  var mtkn_wshe_grp = '';
                  var type = $(this).data('type'); 
              
                  if(type == 'R'){
                    mtkn_uid = jQuery('#txt-report-wshe').attr('data-id');
                    mtkn_wshe_grp = $('#txt-rtransfer-rack').attr("data-id");
                  }
                  else if(type == 'TT'){
                    mtkn_uid = jQuery('#txt-warehouse').attr("data-id")
                    mtkn_wshe_grp = $('#txt-ttransfer-rack').attr("data-id");
                  }
                  else if(type == 'TU'){
                     mtkn_uid = jQuery('#txt-warehouse').attr("data-id")
                     mtkn_wshe_grp = $('#txt-ftransfer-rack-upload').attr("data-id");

                  }
                 else if(type == 'TTU'){
                    mtkn_uid = jQuery('#txt-warehouse').attr("data-id")
                    mtkn_wshe_grp = $('#txt-ttransfer-rack-upload').attr("data-id");
                  }
                  else{
                    mtkn_uid = jQuery('#txt-warehouse').attr("data-id")
                    mtkn_wshe_grp = $('#txt-ftransfer-rack').attr("data-id");
                  }
                  

                  $(this).autocomplete('option', 'source', '<?=site_url();?>get-warehouse-sbin?mtkn_uid='+mtkn_uid+'&mtkn_wshe_grp='+mtkn_wshe_grp);
              },
              select: function( event, ui ) {
                  var terms = ui.item.value;
                  
                  jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                  jQuery(this).attr('title', jQuery.trim(ui.item.value));
                  jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));

                  this.value = ui.item.value;

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