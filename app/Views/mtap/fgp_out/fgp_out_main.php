<?php 
/**
 *  File        : promodamage_main.php
 *  Author      : Arnel Oquien
 *  Date Created: July. 27, 2022
 */

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$mywhout = model('App\Models\MyFgpOutgoing');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$cuserrema = $mylibzdb->mysys_userrema();
$mtkn_trxno = $request->getVar('mtkn_trxno');
$mymelibsys =  model('App\Models\Mymelibsys_Model');
$astore_mem     = $mydataz->lk_Active_Store_or_Mem_np();
$txtstore_memhd = '';
$chk_by = '';
$sysCtrl        = '';
$branch         = '';
$plate_no       = '';
$driver         = '';
$helper_one     = '';
$helper_two     = '';
$mkg_check      = '';
$trucktype = '';
$remk = '';
$whseID = '';
$pd_stats='Y';
$done_stats='0';
$refno = '';
$isdone = '';
if(!empty($mtkn_trxno)):
$wshoutData = $mywhout->get_entry_data($mtkn_trxno);
$hdData = $wshoutData['hdData'];
$dtData = array('result' => $wshoutData['dtData'],'isdone' => $hdData['done'],'count' => 0) ;
$sysCtrl        = $hdData['crpl_code'];
$branch         = $hdData['brnch'];
$plate_no       = $hdData['plate_no'];
$driver         = $hdData['driver'];
$refno         = $hdData['refno'];
$helper_one     = $hdData['helper_1'];
$helper_two     = $hdData['helper_2'];
$chk_by    = $hdData['chk_by'];
$txtstore_memhd = $hdData['sm_tag'];
$mkg_check      = ($hdData['mkg_tag'] == 'Y')?'checked':'';
$whseID = $hdData['frm_wshe_id'];
$trucktype = $hdData['truck_type'];
$remk = $hdData['me_remk'];
$isdone = $hdData['done'];
endif;

?>

<main id="main">
<?php $active_warehouse_data = $mymelibsys->getCDActivePlantWarehouse($whseID);
  ?>
  <div class="pagetitle">
      <h1>FG Pack Outgoing </h1>
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html">Home</a></li>
              <li class="breadcrumb-item active">FG Packing Outgoing Entry</li>
          </ol>
          </nav>
  </div><!-- End Page Title -->
        <div class="content-inner w-100">
          <!-- Forms Section-->
          <section class="forms"> 
          <div class="container-fluid">
              <div class="row">
             
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="h4 mb-0"><i class="bi bi-pencil-square"></i> Entry</h3>
                      </div>
                      <div class="row card-body">
                        <div class="col-lg-4 ">
                            <div class="mt-2 col-12">
                              <label for="branch-name">Plant</label>
                              <div class="input-group"> 
                              <input type="text" id="txt-plant-out" name="txt-plant-out" class="disable_input form-control form-control-sm" value="<?=$active_warehouse_data['plant_code']?>" data-id="<?=$active_warehouse_data['mtkn_plant']?>">
                              <div class="input-group-text px-1 py-0 "> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                              </div>
                            </div>
                            <div class="mt-2 col-12">
                              <label for="branch-name">Warehouse</label>
                              <div class="input-group">
                              <input type="text" id="txt-warehouse" name="txt-warehouse" class=" disable_input form-control form-control-sm" value="<?=$active_warehouse_data['wshe_code']?>"  data-id="<?=$active_warehouse_data['mtkn_whse']?>">
                              <div class="input-group-text px-1 py-0"> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                              </div>
                            </div>
                            <div class="mt-2 col-12">
                              <label for="branch-name">Prefix</label>
                              <input type="text" id="txt-prefix" name="txt-prefix" class=" disable_input form-control form-control-sm" value=""  disabled >
                            </div>
                           <div class="mt-2 col-12">
                              <label for="txt-drlist">DR/Packinglist</label>
                              <input type="text" id="txt-drlist" name="txt-drlist" class=" disable_input form-control form-control-sm" value=""  placeholder="Based on SText/remarks in Warehouse inventory" >
                            </div>

                            <?php if(empty($mtkn_trxno)): ?>
                              <div class="mt-2 col-12 text-end">
                              <button class="btn bg-dgreen btn-sm" id="btn-out-search">Search</button>
                              </div>
<!--                            <div class="input-group input-group-sm mb-3 mt-3">
                             <input type="file" class="form-control form-control-sm" id="rcv-upld-file" placeholder="Search Transaction/Branch" aria-label="mytxtsearchrec" aria-describedby="basic-addon1">
                             <div class="input-group-prepend" id="basic-addon1">
                               <button type="button" id="btn-upload-wshe-out" class="btn btn-dgreen btn-sm m-0 rounded-0 rounded-end" ><i class="bi bi-upload"></i> Upload</button>
                             </div>
                           </div> -->
                            <?php endif;?>
                  
                        </div>
                        <div class="col-lg-8 "> 
                             <div class="row rounded p-2">
                              <div class="col-lg-6 ">
                                <label for="exampleInputPassword1">Sysctrl no</label>
                                <input type="text" class="form-control form-control-sm" id="control-number" placeholder="Control number" value="<?=$sysCtrl?>" readonly data-mtkn ="<?=$mtkn_trxno?>">
                              </div>
                              <div class=" col-lg-6 ">
                                <label for="branch-name">Branch</label>
                                <input type="text" class="disable_input form-control form-control-sm" id="branch-name" placeholder="Branch" value="<?=$branch?>">
                              </div>
                              <div class="mt-2 col-lg-6 ">
                                <label for="plate-number">Plate no</label>
                                <input type="text" class=" form-control form-control-sm" id="plate-number" placeholder="Plate number" value="<?=$plate_no?>">
                              </div>
                              <div class="mt-2 col-lg-6 ">
                                <label for="helper-two">Truck Type</label>
                                <input type="text" class=" form-control form-control-sm" id="truck-type" placeholder="Truck type" value="<?=$trucktype?>">
                              </div>
                              <div class="mt-2 col-lg-6 ">
                                <label for="driver">Driver</label>
                                <input type="text" class=" form-control form-control-sm" id="driver" placeholder="Driver" value="<?=$driver?>">
                              </div>
                              <div class="mt-2 col-lg-6 ">
                                <label for="helper-one">Helper 1</label>
                                <input type="text" class=" form-control form-control-sm" id="helper-one" placeholder="Helper one" value="<?=$helper_one?>">
                              </div>
                              <div class="mt-2 col-lg-6 ">
                                <label for="helper-two">Helper 2</label>
                                <input type="text" class=" form-control form-control-sm" id="helper-two" placeholder="Helper two" value="<?=$helper_two?>">
                              </div>
                              <div class="mt-2 col-lg-6 ">
                                <label for="helper-two">Ref. No</label>
                                <input type="text" class=" form-control form-control-sm" id="ref-no" placeholder="Reference number" value="<?=$refno?>">
                              </div>
                              <div class="mt-2 col-lg-6 ">
                                <label for="helper-two">Check by</label>
                                <input type="text" class=" form-control form-control-sm" id="chk_by" placeholder="Check by" value="<?=$chk_by?>">
                              </div>
                               <div class="mt-2 col-lg-6 ">
                                <label for="helper-two">S/M</label>
                                <div class="input-group">
                               <?=$mylibzsys->mypopulist_2($astore_mem,$txtstore_memhd,'sm-tag','class="disable_input form-control form-control-sm" ','','');?>
                               <div class="input-group-text px-1 py-0 "> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                               </div>
                              </div>
                              <div class="mt-2 form-group col-lg-6 col-md-12">
                                <label for="rems_">Remarks</label>
                                <textarea name="remarks" class=" form-control form-control-sm" id="rems_" cols="30" rows="5"><?=$remk?></textarea>
                              </div>
                              <div class="mt-2  col-lg-2 col-md-12">
                               <label for="btn-update-header"></label>
                                <?php if(!empty($mtkn_trxno) && $isdone == 0 ): ?>
                               <button class="btn btn-dgreen btn-sm col-lg-12" id="btn-update-header"> Update header </button>   
                               <?php endif; ?> 
                              </div>
                            </div>
                        </div>
                        <div id="out-list">
                          <?php 
                          if(!empty($mtkn_trxno)):

                              echo view('mtap/fgp_out/fgp_out_item_scanned',$dtData);
                          endif;
                           ?>
                        </div>

                      </div>
                    </div>
                  </div>
                <!-- Inline Form-->
                <div class="col-lg-12">                           
                  <div class="card">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Records</h3>
                    </div>
                    <div class="card-body">
                      <div class="pt-2  bg-dgreen mt-2"> 
                       <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                         <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> List</a>
                         <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> Backload Items</a>
                       </nav>
                       </div>

                       <div id="myoutdrecs"> </div>
                  
                    <!-- car body end -->
                  </div>
                </div>
              </div>
                </div>
                <div class="col-lg-12">
                <div class="card">   
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <h3 class="h4 mb-0"> <i class="bi bi-graph-up-arrow"></i> Report</h3>
                      </button>
                    </h2>
                  <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                       <div id="my-shipreport-vw"></div>
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
            echo $mylibzsys->memsgbox2('memsgtestent_gen','<i class="bi bi-receipt"></i> Generate Shipping document','...','bg-psuccess','modal-xl');
          ?>  
  </main>

<script type="text/javascript">   
  __mysys_apps.mepreloader('mepreloaderme',false);

$('#btn-gen-shipdoc').on('click',function(){
  var ajaxRequest;
  var mtkn_whse = jQuery('#txt-warehouse').attr('data-id'); 
  ajaxRequest = jQuery.ajax({
      url: "<?=site_url();?>warehouse-gen-sd",
      type: "post",
      data: {
          mtkn_whse: mtkn_whse
      }
  });
    __mysys_apps.mepreloader('mepreloaderme',true);
    ajaxRequest.done(function(response, textStatus, jqXHR) {
         jQuery('#memsgtestent_gen_bod').html(response);
      jQuery('#memsgtestent_gen').modal('show');
        __mysys_apps.mepreloader('mepreloaderme',false);
 
    });

     
});

  $('#btn-out-search').on('click',function(){
    try{
        var txtsearchedrec = $('#mytxtsearchrec_verify').val();
        var mtkn_whse = jQuery('#txt-warehouse').attr("data-id");
        var branch = jQuery('#branch-name').val();
        var prefix = jQuery('#txt-prefix').val();
        var txt_drlist = jQuery('#txt-drlist').val();


        var mbranch = branch;
        var index   = branch.indexOf('-');
        if(index > 0 ){
            mbranch = branch.substr(0,index);
            
        }

        mbranch = prefix+mbranch.trim();

        var mparam = {
          txtWarehousetkn:mtkn_whse,
          prefix:mbranch,
          txt_drlist:txt_drlist
        }
        __mysys_apps.mepreloader('mepreloaderme',true);
        $.ajax({ // default declaration of ajax parameters
              type: "POST",
              url: '<?=site_url()?>fgp-out-show',
              context: document.body,
              data: eval(mparam),
              global: false,
              cache: false,
              success: function(data)  { //display html using divID
                   __mysys_apps.mepreloader('mepreloaderme',false);
             $('#out-list').html(data);
                  return false;
              },
              error: function() { // display global error on the menu function
                  alert('error loading page...');
                   __mysys_apps.mepreloader('mepreloaderme',false);
                  return false;
              } 
        });  


    }
    catch (err) {
          var mtxt = 'There was an error on this page.\n';
          mtxt += 'Error description: ' + err.message;
          mtxt += '\nClick OK to continue.';
          
          alert(mtxt);
        } //end try


  });

  $('#btn-upload-wshe-out').click(function(){ 
    try {   

      var txtPlant     = jQuery('#txt-plant-out').val();
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
        url: '<?=site_url()?>warehouse-out-upld',
        method:"POST",
        context:document.body,
        data: my_data,
        contentType: false,
        global: false,
        cache: false,
        processData:false,
        success: function(data)  { //display html using divID
          __mysys_apps.mepreloader('mepreloaderme',false);
          jQuery('#out-list').html(data);
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

  
// //select one checkboc only
// const checkBox =  document.querySelectorAll('.cb_discount');
// checkBox.forEach((cb)=>{
// cb.addEventListener('click',()=>{
//   checkBox.forEach((cb)=>{
//     cb.checked = false;
//   });
//  cb.checked = true;
// });
// });

$('#anchor-list').on('click',function(){
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    var mtkn_whse = '';
    fgp_out_req_view_recs(mtkn_whse);

});

function fgp_out_req_view_recs(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>fgp-out-recs",
        type: "post",
        data: {
            mtkn_whse: mtkn_whse
        }
    });

    // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#myoutdrecs').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
      });
  };

      jQuery('#txt-plant-out' ) 
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
              jQuery('#txt-plant-out').attr("data-id",plant_id);
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

          jQuery('#branch-name' ) 
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
               source: '<?=site_url();?>get-branch-list/',
               focus: function() {
           // prevent value inserted on focus
           return false;
           },
           search: function(oEvent, oUi) { 
               var sValue = jQuery(oEvent.target).val();
               var plant = jQuery('#txt-plant-out').attr("data-id");
               jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-branch-list'); 
           },
           select: function( event, ui ) {

               var terms = ui.item.value;
               jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
               jQuery('#' + this.id).attr('title', jQuery.trim(terms));
               jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));
               var wshe_id = ui.item.mtkn_rid;
               this.value = ui.item.value; 

               $('#anchor-list').addClass('active');
               $('#anchor-items').removeClass('active');

               $('#txt-prefix').attr('disabled',false);

              return false;
           }
           })
           .click(function() { 
           //jQuery(this).keydown(); 
           var terms = this.value.split('|');
           //jQuery(this).autocomplete('search', '');
           jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
           });


//branch on change

$('#branch-name').on('change',function(){

  if($(this).val() == ''){
      $('#txt-prefix').attr('disabled',true);
  }

});

disabled_inputs();
function disabled_inputs(){
 var mtkn_hd = '<?=$mtkn_trxno?>';
 if(mtkn_hd != ''){
  $('.disable_input').attr('readonly',true);
 }

}

$('#btn-update-header').on('click',function(){
  try { 

    var txtsearchedrec = $('#mytxtsearchrec_verify').val();
    var mtkn_whse = jQuery('#txt-warehouse').attr("data-id");
  
      var control_number = $('#control-number').val();
      var branch_name    = $('#branch-name').val();
      var plate_number   = $('#plate-number').val();
      var driver         = $('#driver').val();
      var helper_one     = $('#helper-one').val();
      var helper_two     = $('#helper-two').val();
      var helper_two     = $('#helper-two').val();
      var ref_no         = $('#ref-no').val();
      var sm_tag         = $('#sm-tag').val();
      var truck_type     = $('#truck-type').val(); 
      var rems_ =  $('#rems_').val(); 

    var mparam = {
      control_number:control_number,
      branch_name:branch_name,
      plate_number:plate_number,
      driver:driver,
      helper_one:helper_one,
      helper_two:helper_two,
      ref_no:ref_no,
      sm_tag:sm_tag,
      txtWarehousetkn:mtkn_whse,
      truck_type:truck_type,
      rems_:rems_
    }

  __mysys_apps.mepreloader('mepreloaderme',true);
  $.ajax({ // default declaration of ajax parameters
    type: "POST",
    url: '<?=site_url()?>fgp-out-hd-updt',
    context: document.body,
    data: eval(mparam),
    global: false,
    cache: false,
  success: function(data)  { //display html using divID
    __mysys_apps.mepreloader('mepreloaderme',false);

    $('#memsgtestent_success_bod').html(data);
    $('#memsgtestent_success').modal('show');

    return false;
  },
  error: function() { // display global error on the menu function
    alert('error loading page...');
    __mysys_apps.mepreloader('mepreloaderme',false);
    return false;
  } 
  });     

  } catch(err) {
    var mtxt = 'There was an error on this page.\n';
    mtxt += 'Error description: ' + err.message;
    mtxt += '\nClick OK to continue.';
    alert(mtxt);
    __mysys_apps.mepreloader('mepreloaderme',false);
    return false;
  }  //end try
});

mywg_gr_summ();
      function mywg_gr_summ(mtkn_arttr) { 
          var ajaxRequest;

          ajaxRequest = jQuery.ajax({
              url: "<?=site_url();?>warehouse-out-report",
              type: "post",
              data: {
                  mtkn_arttr: mtkn_arttr
              }
          });

  // Deal with the results of the above ajax call
          ajaxRequest.done(function(response, textStatus, jqXHR) {
              jQuery('#my-shipreport-vw').html(response);
  // and do it again
  //setTimeout(get_if_stats, 5000);
          });
      };

</script>
