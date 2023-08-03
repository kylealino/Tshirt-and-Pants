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
      <h1>Cross Docking Allocation Guide</h1>
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html">Home</a></li>
              <li class="breadcrumb-item active">Cross Docking Entry</li>
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
                    <div class="card-body">
                    <div id="mymodoutrecs"></div>

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
                          <div class="tab-pane fade show active" id="nav-pout-rec" role="tabpanel" aria-labelledby="nav-pout-rec-tab">
                             <div id="mymodoutentrecs"></div>
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
                       <div id="my-allocreport-vw"></div>
                    </div>
                  </div>
                 </div>
                </div>
              </div>
                              <!-- Inline Form-->
                <?php 	
                $result = $mydatazua->get_Active_menus($mydbname->medb(1),$cuser,"myuatrx_id='233'","myua_trx"); 
                if($result == 1 ):
                ?>
                <div class="col-lg-12">                           
                  <div class="card">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-arrow-clockwise"></i> Reversal</h3>
                    </div>
                    <div class="card-body">
                          <div class="tab-pane fade show active" id="nav-revr-rec" role="tabpanel" aria-labelledby="nav-revr-rec-tab">
                             <div id="myreversal-vw"></div>
                          </div>
                    <!-- car body end -->
                  </div>
                </div>
              </div>
              <?php endif;?>

              </div>
          </section>
          <?php
            echo $mylibzsys->memypreloader01('mepreloaderme');
            echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
            ?>  
          </main>


<script type="text/javascript">   
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

myprodmg_view_recs();
function myprodmg_view_recs(mtkn_arttr){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>whcrossing_recs",
        type: "post",
        data: {
            mtkn_arttr: mtkn_arttr
        }
    });

      // Deal with the results of the above ajax call
      //$.showLoading({name: 'line-pulse', allowHide: false });
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#mymodoutentrecs').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
          //$.hideLoading();
          // and do it again
          //setTimeout(get_if_stats, 5000);
      });
  };

crossingmain_view_recs();
function crossingmain_view_recs(mtkn_arttr){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>whcrossing-plrecs",
        type: "post",
        data: {
            mtkn_arttr: mtkn_arttr
        }
    });

      // Deal with the results of the above ajax call
     // $.showLoading({name: 'line-pulse', allowHide: false });
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#mymodoutrecs').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
         // $.hideLoading();
          // and do it again
          //setTimeout(get_if_stats, 5000);
      });
  };


  $('#mbtn_promdmg_sv').click(function(){ 
           try {   
                var trxno_mtkn = $('#promotrxno').data('mtkn');
                var promotrxno = $('#promotrxno').val();
                var branchName = $('#branchName').val();
                var branchNameID = $('#branchName').data('mtknid');
                var startDate  = $('#startDate').val();
                var startTime  = $('#startTime').val();
                var endDate    = $('#endDate').val();
                var endTime    = $('#endTime').val();

                if(endDate < startDate){
                 jQuery('#myModalSysMsgBod').html(` <i class="bi bi-exclamation-circle text-danger"> </i> Invalid Start date and End date format.`);
                 jQuery('#myModSysMsg').modal('show');
                 return false;
                }
                
                var pesoDiscount    = ($('#pesoDiscountcb').is(':checked'))?1:0;
                var percentDiscount = ($('#percentDiscountcb').is(':checked'))?1:0;
                var totalQty      = $('#totalQty').val();
                var totalpromoSrp = $('#totalpromoSrp').val();

                //var tbl_PayData = jQuery('#tbl_PayData');
                var rowCount1 = jQuery('#tbl_PayData tr').length - 1;
                var adata1 = [];
                var adata2 = [];
                var mdata = '';
                var mdat ='';
       

                //for(aa = 1; aa < rowCount1; aa--) { 
                for(aa = rowCount1-1; aa > 0; aa--) { 
                    var clonedRow = jQuery('#tbl_PayData tr:eq(' + aa + ')').clone(); 
                    var mdata00 = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //icode
                    var mdata01 = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //desc
                    var mdata02 = jQuery(clonedRow).find('input[type=text]').eq(2).val(); //promocode
                    var mdata03 = jQuery(clonedRow).find('input[type=text]').eq(3).val(); //qty
                    var mdata04 = jQuery(clonedRow).find('input[type=text]').eq(4).val(); //srp
                    var mdata05 = jQuery(clonedRow).find('input[type=text]').eq(5).val(); //unitcost
                    var mdata06 = jQuery(clonedRow).find('input[type=text]').eq(6).val(); //promosrp
                    var mdata07 = jQuery(clonedRow).find('input[type=text]').eq(7).val(); //totam promosrp
                     mdt_tkn = $(clonedRow).find('input[type=hidden]').eq(1).val(); //dt tkn
                   
                    mdata = mdata00 + 'x|x' + mdata01 + 'x|x' + mdata02 + 'x|x' + mdata03 + 'x|x' + mdata04 + 'x|x' + mdata05 + 'x|x' + mdata06 + 'x|x' + mdata07.replace(/,/g,'')+ 'x|x' + mdt_tkn //remove comma ;
                        adata1.push(mdata);
                    mdat = $(clonedRow).find('input[type=hidden]').eq(0).val(); //icode
                    adata2.push(mdat);
                    
                }  //end forfld_supplier_po: fld_supplier_po,
                
                var mparam ={
                    trxno_mtkn:trxno_mtkn,
                    promotrxno:promotrxno,
                    branchName: branchName,
                    branchNameID:branchNameID,
                    startDate: startDate,
                    startTime:startTime,
                    endDate: endDate,
                    endTime:endTime,
                    pesoDiscount: pesoDiscount,
                    percentDiscount: percentDiscount,
                    totalQty:totalQty,
                    totalpromoSrp:totalpromoSrp,
                    adata2:adata2,
                    adata1:adata1
               }

               // $.showLoading({name: 'line-pulse', allowHide: false });
                $.ajax({ // default declaration of ajax parameters
                    url: '<?=site_url()?>WhCrossing/sv_ent',
                    method:"POST",
                    context: document.body,
                    data: eval(mparam),
                    global: false,
                    cache: false,
                    success: function(data)  { //display html using divID
                       // $.hideLoading();
                        jQuery('#myModalSysMsgBod').html(data);
                        jQuery('#myModSysMsg').modal('show');
                        return false;
                    },
                    error: function() { // display global error on the menu function
                        alert('error loading page...');
                       // $.hideLoading();
                        return false;
                    }   
                }); 
            } catch (err) {
                var mtxt = 'There was an error on this page.\n';
                mtxt += 'Error description: ' + err.message;
                mtxt += '\nClick OK to continue.';
                jQuery.hideLoading();
                alert(mtxt);
            } //end try
        }); 


  reversal_view();
  function reversal_view(mtkn_arttr){ 
      var ajaxRequest;

      ajaxRequest = jQuery.ajax({
          url: "<?=site_url();?>mycrossing-reversal",
          type: "post",
          data: {
              mtkn_arttr: mtkn_arttr
          }
      });

        // Deal with the results of the above ajax call
       // $.showLoading({name: 'line-pulse', allowHide: false });
        ajaxRequest.done(function(response, textStatus, jqXHR) {
            jQuery('#myreversal-vw').html(response);
            __mysys_apps.mepreloader('mepreloaderme',false);
           // $.hideLoading();
            // and do it again
            //setTimeout(get_if_stats, 5000);
        });
    };

    mywg_wh_report();
      function mywg_wh_report(mtkn_arttr) { 
          var ajaxRequest;

          ajaxRequest = jQuery.ajax({
              url: "<?=site_url();?>warehouse-alloc-report",
              type: "post",
              data: {
                  mtkn_arttr: mtkn_arttr
              }
          });

  // Deal with the results of the above ajax call
          ajaxRequest.done(function(response, textStatus, jqXHR) {
              jQuery('#my-allocreport-vw').html(response);
  // and do it again
  //setTimeout(get_if_stats, 5000);
          });
      };

</script>