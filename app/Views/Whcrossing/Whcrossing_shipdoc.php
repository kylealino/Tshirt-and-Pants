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

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$cuserrema = $mylibzdb->mysys_userrema();
$mtkn_trxno = $request->getPost('mtkn_trxno');

$pd_stats='Y';
?>

<main id="main">
  <div class="pagetitle">
      <h1>Shipdoc Cross Docking </h1>
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html">Home</a></li>
              <li class="breadcrumb-item active">Shipdoc Cross Docking Entry</li>
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
                        <h3 class="h4 mb-0"><i class="bi bi-edit"></i> Entry</h3>
                      </div>
                      <div class="row card-body">
                        <div class="col-lg-4">
                          <form action="http://localhost/mynlinks/WhCrossing/WhCrossing_recs" class="" id="myfrmsearchrec" method="post" accept-charset="utf-8" novalidate="novalidate">
                          <div class="row mb-2 ">
                            <div class="col-lg-12 col-md-12 col-sm-12 mt-4 ">
                              <div class="input-group input-group-sm mb-3">
                                <input type="file" class="form-control form-control-sm" id="mytxtsearchrec" placeholder="Search Transaction/Branch" aria-label="mytxtsearchrec" aria-describedby="basic-addon1">
                                <div class="input-group-prepend" id="basic-addon1">
                                  <button type="submit" class="btn btn-success btn-sm m-0 rounded-0 rounded-end" ><i class="bi bi-upload"></i> Upload</button>
                                </div>
                              </div>
                            </div>
                            
                            <div class="col-12">
                              <label for="exampleInputPassword1">Sysctrl no</label>
                              <input type="password" class="form-control form-control-sm" id="control-number" placeholder="Control number">
                            </div>
                            <div class="mt-2 col-12">
                              <label for="branch-name">Branch</label>
                              <input type="password" class="form-control form-control-sm" id="branch-name" placeholder="Branch">
                            </div>
                              <div class="mt-2 col-12">
                              <label for="plate-number">Plate no</label>
                              <input type="password" class="form-control form-control-sm" id="plate-number" placeholder="Plate number">
                            </div>
                            <div class="mt-2 col-12">
                              <label for="driver">Driver</label>
                              <input type="password" class="form-control form-control-sm" id="driver" placeholder="Driver">
                            </div>
                            <div class="mt-2 col-12">
                              <label for="helper-one">Helper 1</label>
                              <input type="password" class="form-control form-control-sm" id="helper-one" placeholder="Helper one">
                            </div>
                            <div class="mt-2 col-12">
                              <label for="helper-two">Helper 2</label>
                              <input type="password" class="form-control form-control-sm" id="helper-two" placeholder="Helper two">
                            </div>
                            <div class="mt-2 col-12">
                              <button class="btn btn-success btn-sm btn-block" id="btn-save"> <i class="bi bi-save"> </i> Save</button>
                            </div>
                   
                          </div>
                          </form>
                        </div>
                        <div class="col-lg-8">
                          <div class="table-reponsive mt-3">
                            <table class="table table-sm">
                              <th>Sample</th>
                              <tr>
                              <td>te</td>
                            </tr>
                            </table>
                          </div>
                          
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
                      <div class="nav-tabs-custom">
                         <nav>
                             <div class="nav nav-tabs mt-2" id="nav-tab" role="tablist">
                                 <a class="nav-item nav-link active" id="nav-pout-rec-tab" data-toggle="tab" href="#nav-pout-rec" role="tab" aria-controls="nav-pout-rec" aria-selected="true">Record Listing</a>
                                 <a class="nav-item nav-link" id="nav-dmgpromodash-tab" data-toggle="tab" href="#nav-dmgpromodash" role="tab" aria-controls="nav-po-dmgpromodash" aria-selected="false">Dashboard</a>
                             </div>
                         </nav>
                         <div class="tab-content mt-3" id="nav-tabContent">
                          <div class="tab-pane fade show active" id="nav-pout-rec" role="tabpanel" aria-labelledby="nav-pout-rec-tab">
                             <div id="mymodoutrecs">
                               <div class="table-responsive text-center">
                                            <table class="table table-bordered table-hover table-sm">
                                                <thead class="thead-light">
                                                  <tr>
                                                    <th nowrap="nowrap">JO CODE</th>
                                                    <th nowrap="nowrap">BRANCH</th>
                                                    <th nowrap="nowrap">PLANT</th>
                                                    <th nowrap="nowrap">WAREHOUSE</th>
                                                    <th nowrap="nowrap">QTY NEEDED</th>
                                                    <th nowrap="nowrap">QTY UPLOADED</th>
                                                    <th nowrap="nowrap">USER</th>
                                                            <th nowrap="nowrap">VARIANCE</th>
                                                            <th nowrap="nowrap">DATE</th>
                                                            <th nowrap="nowrap"><i class="bi bi-check"></i></th>
                                                            <th nowrap="nowrap"><i class="bi bi-folder"></i></th>
                                                            <th nowrap="nowrap"><i class="bi bi-gear"></i></th>

                                                  </tr>
                                                </thead>
                                                  <tbody>
                                                  <tr bgcolor="#EAF3F3" onmouseover="this.style.backgroundColor='#97CBFF';" onmouseout="this.style.backgroundColor='#EAF3F3';" style="background-color: rgb(234, 243, 243);">
                                                  <td nowrap="nowrap">20220000000002</td>
                                                  <td nowrap="nowrap">MONUMENTO - NCR/A</td>
                                                  <td nowrap="nowrap">PL-001</td>
                                                  <td nowrap="nowrap">W-004</td>
                                                  <td nowrap="nowrap">2</td>
                                                  <td nowrap="nowrap">1</td>
                                                  <td nowrap="nowrap">arnel</td>
                                                  <td nowrap="nowrap">LESS</td>
                                                  <td nowrap="nowrap"></td>
                                                  <td nowrap="nowrap">
                                                  <i class="bi bi-check"></i>
                                                  </td>
                                                  <td nowrap="nowrap">
                                                  <button class="btn btn-xs btn-primary" onclick="view_uploaded_summary('20220000000002')">VIEW</button>
                                                  </td>
                                                  <td nowrap="nowrap">
                                                  <button class="btn btn-xs btn-info" onclick="view_backload_summary('20220000000002')">BACKLOAD</button>
                                                  </td>
                                                  </tr>
                               
                                                  <tr bgcolor="#FFF" onmouseover="this.style.backgroundColor='#97CBFF';" onmouseout="this.style.backgroundColor='#FFF';" style="background-color: rgb(255, 255, 255);">
                                                  <td nowrap="nowrap">20220000000002</td>
                                                  <td nowrap="nowrap">MONUMENTO - NCR/A</td>
                                                  <td nowrap="nowrap">PL-001</td>
                                                  <td nowrap="nowrap">W-004</td>
                                                  <td nowrap="nowrap">2</td>
                                                  <td nowrap="nowrap">1</td>
                                                  <td nowrap="nowrap">arnel</td>
                                                  <td nowrap="nowrap">LESS</td>
                                                  <td nowrap="nowrap"></td>
                                                  <td nowrap="nowrap">
                                                  <i class="bi bi-check"></i>
                                                  </td>
                                                  <td nowrap="nowrap">
                                                  <button class="btn btn-xs btn-primary" onclick="view_uploaded_summary('20220000000002')">VIEW</button>
                                                  </td>
                                                  <td nowrap="nowrap">
                                                  <button class="btn btn-xs btn-info" onclick="view_backload_summary('20220000000002')">BACKLOAD</button>
                                                  </td>
                                                  </tr>



                                                  </tbody>
                                              </table>
                                          </div>

                             </div>
                             <div id="div_dl"> </div>
                          </div>
                          <div class="tab-pane fade" id="nav-dmgpromodash" role="tabpanel" aria-labelledby="nav-dmgpromodash-tab">
                           <div id="my-dmgpromodash"></div>
                          </div>
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

                  <div class="modal fade text-start modal-md" tabindex="-1" id="posting_modal">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                      <div class="modal-content">
                        <div class="modal-header alert-info">
                          <h5 class="modal-title">Posting</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">  <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                          <div class="container-fluid">
                          
                              <div class="row gy-2 mb-4">
                                <label class="col-sm-4 form-label" for="branchName">Branch Name</label>
                                <div class="col-sm-8">
                                  <input class="form-control form-control-sm branchName" name="postingbranchName" id="postingbranchName" type="text" placeholder="Branch Name" value="" data-mtknid="" required>
                                </div>
                              </div>
                               <div class="row gy-2 mb-4">
                                <label class="col-sm-4 form-label" for="startDate">Date Range<i>(Start date)</i></label>
                                <div class="col-sm-4">
                                  <input class="form-control form-control-sm" name="postingstartDate" id="postingstartDate" value="" type="date" required>
                                  <small class=" form-text"> From </small>
                                </div>
                                <div class="col-sm-4">
                                  <input class="form-control form-control-sm" name="postingendDate" id="postingendDate" value="" type="date" required>
                                    <small class=" form-text"> To </small>
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                           <button type="button" class="btn btn-info" id="posting_btn" data-bs-dismiss="modal">Search</button>
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
        url: "<?=site_url();?>WhCrossing/whcrossing_recs",
        type: "post",
        data: {
            mtkn_arttr: mtkn_arttr
        }
    });

      // Deal with the results of the above ajax call
      $.showLoading({name: 'line-pulse', allowHide: false });
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#mymodoutrecs').html(response);
          $.hideLoading();
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

                $.showLoading({name: 'line-pulse', allowHide: false });
                $.ajax({ // default declaration of ajax parameters
                    url: '<?=site_url()?>WhCrossing/sv_ent',
                    method:"POST",
                    context: document.body,
                    data: eval(mparam),
                    global: false,
                    cache: false,
                    success: function(data)  { //display html using divID
                        $.hideLoading();
                        jQuery('#myModalSysMsgBod').html(data);
                        jQuery('#myModSysMsg').modal('show');
                        return false;
                    },
                    error: function() { // display global error on the menu function
                        alert('error loading page...');
                        $.hideLoading();
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


$('#mbtn_promdmg_sent').on('click',function(){
  try {

    my_data = new FormData();
    var trxno_mtkn = $('#promotrxno').data('mtkn');
    var promotrxno = $('#promotrxno').val();
    var  reupload   = $('#flexSwitchCheckDefault').prop("checked") ? 'Y' : 'N';
    var filerfp       = '__upld_file_img01'; //Approved RFP
    var pd_stats = '<?=$pd_stats?>';

    my_data.append('promotrxno', $('#promotrxno').val());
    my_data.append('_hdrid_mtkn',$('#promotrxno').data('mtkn'));
    my_data.append('reupload',reupload);
    my_data.append('pd_stats',pd_stats);


    var filerfps    = $('.'+filerfp);
    var filesCount   = 0;

    var invalid = 0;
    var mearray = [];
    let checkFiles = false;
    if( (trxno_mtkn != '' && pd_stats == '') || (trxno_mtkn != '' && pd_stats == 'D' && reupload == 'Y')){
      checkFiles = true;

      $.each(filerfps, function(i,filerfp){
        if(filerfp.files.length > 0 ){
          $.each(filerfp.files, function(k,file){
            my_data.append('images[]', file);
            filesCount++;

          });
        }
      });
      if (filesCount == 0 ){
      __mysys_apps.showtoast('Please select file to upload.','toast_danger');
      return false;
      }
    }
  my_data.append('checkFiles',checkFiles);
  if(trxno_mtkn != '' ){
  $.showLoading({name: 'line-pulse', allowHide: false });
  my_data.append('mearray',mearray);
  jQuery.ajax({ 
    type: "POST",
    url: '<?=site_url()?>WhCrossing/promodmg_sent',
    context: document.body,
    data: my_data,
    contentType: false,
    global: false,
    cache: false,
    processData:false,
    success: function(data) { 
      $.hideLoading();
      jQuery('#myModalSysMsgBod').html(data);
      jQuery('#myModSysMsg').modal('show');
      return false;
    },
    error: function() { 
      $.hideLoading();
      alert('error loading page...');
      return false;
    } 
  }); 
}
else{

  $.hideLoading();
  jQuery('#myModSysMsgBod').html('<div class="alert alert-danger">Transaction not found.</div>');
  jQuery('#myModSysMsg').modal('show');
  return false;
}
}
catch (err) { 
  $.hideLoading();
  var mtxt = 'There was an error on this page.\n';
  mtxt += 'Error description: ' + err.message;
  mtxt += '\nClick OK to continue.';
  alert(mtxt);
} 
return false;
});

    function my_add_line_item(fld_ptyp_i){ 
          try {

            var fld_area_id = jQuery('#branchName').attr('data-mtknid');
            console.log(jQuery('#branchName').val(),fld_area_id);
            if(fld_area_id == ''){
               alert('Please input Area Code/Branch first!!!');
               $('#branchName').focus();
                return false;
            }
           var rowCount = jQuery('#tbl_PayData tr').length;
           var mid = __mysys_apps.__do_makeid(7) + (rowCount + 1);
           var clonedRow = jQuery('#tbl_PayData tr:eq(' + (rowCount - 1) + ')').clone(); 
           jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id','mitemrid_' + mid);
           jQuery(clonedRow).find('input[type=hidden]').eq(1).attr('id','mid_' + mid);
           jQuery(clonedRow).find('input[type=hidden]').eq(2).attr('id','__me_tag' + mid);
           jQuery(clonedRow).find('input[type=text]').eq(0).attr('id','fld_mitemcode' + mid);
           jQuery(clonedRow).find('input[type=text]').eq(1).attr('id','fld_mitemdesc' + mid);
           jQuery(clonedRow).find('input[type=text]').eq(2).attr('id','fld_mitempromo' + mid);
           jQuery(clonedRow).find('input[type=text]').eq(3).attr('id','fld_qty' + mid);
           jQuery(clonedRow).find('input[type=text]').eq(4).attr('id','fld_srp' + mid);
           jQuery(clonedRow).find('input[type=text]').eq(5).attr('id','fld_ucost' + mid);
           jQuery(clonedRow).find('input[type=text]').eq(6).attr('id','fld_promosrp' + mid);
           jQuery(clonedRow).find('input[type=text]').eq(7).attr('id','fld_promototalsrp' + mid);

           jQuery('#tbl_PayData tr').eq(1).before(clonedRow);
           jQuery(clonedRow).css({'display':''});

           __my_item_lookup();
           __my_promotim_lookup();
           __tamt_compute_totals();
           var xobjArtItem= jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
           jQuery('#' + xobjArtItem).focus();
           $( '#tbl_PayData tr').each(function(i) { 
                   $(this).find('td').eq(0).html(i);
           });
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
           jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
           event.preventDefault();
   }
   if( event.keyCode === jQuery.ui.keyCode.TAB ) {
       event.preventDefault();
   }
   })
   .autocomplete({
       minLength: 0,
       source: function( request, response ) {
          var fld_area_id = jQuery('#branchName').data('mtknid');
         
          $.ajax({
            url: "<?= site_url(); ?>mysearchdata/mat_article/",
            dataType: "json",
            data: {
              term: request.term,
              pbranchid:fld_area_id
            },
            beforeSend:function(){
              $('#item_sync').addClass('fa-spin');
            },
            complete:function(){
              $('#item_sync').removeClass('fa-spin');
            },
            success: function( data ) {
              response( data );
            },
            error: function (xhr, textStatus, errorThrown) {
              $.hideLoading();
            jQuery('#myModalSysMsgBod').html(`<div class="alert alert-danger"> <strong>${textStatus.toUpperCase()}</strong><br>${errorThrown}. Please report to administrator! </div>`);
            jQuery('#myModSysMsg').modal('show');
           }
          });
        },
       // source: '<?= site_url(); ?>mysearchdata/mat_article/',
       focus: function() {
           // prevent value inserted on focus
           return false;
       },
       search: function(oEvent, oUi){ 
           var sValue = jQuery(oEvent.target).val();
           //jQuery(oEvent.target).val('&mcocd=1' + sValue);
           //alert(sValue);
           // $(this).autocomplete('option', 'source', '<?=site_url();?>mysearchdata/mat_article/?pbranchid=' + fld_area_id);
       },
   
       select: function( event, ui ) {
           var terms = ui.item.value;
           
           jQuery(this).attr('alt', jQuery.trim(ui.item.ART_CODE));
           jQuery(this).attr('title', jQuery.trim(ui.item.ART_CODE));

          this.value = ui.item.ART_CODE;

           var clonedRow = jQuery(this).parent().parent().clone();
           var indexRow = jQuery(this).parent().parent().index();
           var xobjArtMDescId = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');
           var xobjArtMUcost= jQuery(clonedRow).find('input[type=text]').eq(5).attr('id');
           var xobjArtMSRP = jQuery(clonedRow).find('input[type=text]').eq(4).attr('id');
           var xobjArtMPrmo = jQuery(clonedRow).find('input[type=text]').eq(2).attr('id');

           var xobjArtMrid = jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id');
          

          // alert(xobjArtMSRP);
           //alert('<?= site_url(); ?>public/assets/img/thumbnail/items/' + ui.item.ART_IMG);
           jQuery('#' + xobjArtMDescId).val(ui.item.ART_DESC);
           jQuery('#' + xobjArtMUcost).val(ui.item.ART_UCOST);
           jQuery('#' + xobjArtMSRP).val(ui.item.ART_UPRICE);
           jQuery('#' + xobjArtMrid).val(ui.item.mtkn_rid);
           jQuery('#' + xobjArtMPrmo).focus();
          
           
     // console.log(ui.item.ART_NCBM);
     //jQuery('#' + xobjArtMImgId).attr('src','<?= site_url(); ?>uploads/artm/' + ui.item.ART_IMG);

     return false;
         }
     })
       .click(function() { 
           //jQuery(this).keydown(); 
           var terms = this.value.split('=>');
           //jQuery(this).autocomplete('search', '');
           jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
       });        
   }   
     
  function __my_promotim_lookup(){  
                jQuery('.promoitemcode' ) 
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
                source: function( request, response ) {
                   var fld_area_id = jQuery('#branchName').data('mtknid');
                   $.ajax({
                     url: "<?= site_url(); ?>mysearchdata/mat_article/",
                     dataType: "json",
                     data: {
                       term: request.term,
                       pbranchid:fld_area_id
                     },
                      beforeSend:function(){
                        $('#item_sync').addClass('fa-spin');
                      },
                      complete:function(){
                        $('#item_sync').removeClass('fa-spin');
                      },
                     success: function( data ) {
                       response( data );
                     },
                     error: function (xhr, textStatus, errorThrown) {
                       $.hideLoading();
                     jQuery('#myModalSysMsgBod').html(`<div class="alert alert-danger"> <strong>${textStatus.toUpperCase()}</strong><br>${errorThrown}. Please report to administrator! </div>`);
                     jQuery('#myModSysMsg').modal('show');
                    }
                   });
                 },
                // source: '<?= site_url(); ?>mysearchdata/mat_article/',
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                search: function(oEvent, oUi) { 
                  
  
                },
                select: function( event, ui ) {
                    var terms = ui.item.value;
                    
                    jQuery(this).attr('alt', jQuery.trim(ui.item.ART_CODE));
                    jQuery(this).attr('title', jQuery.trim(ui.item.ART_CODE));

                   this.value = ui.item.ART_CODE;

                    var clonedRow = jQuery(this).parent().parent().clone();
                    var indexRow = jQuery(this).parent().parent().index();

                    var xobjArtmCode = jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
                    if($(`#${xobjArtmCode}`).val() == ui.item.ART_CODE){
                      alert('Item code should not be equal to promo code!');
                      this.value = '';
                      return false;

                    }

                    var xobjArtMridFrom = jQuery(clonedRow).find('input[type=hidden]').eq(3).attr('id');
                    var xobjArtMridPromoSrp = jQuery(clonedRow).find('input[type=text]').eq(6).attr('id');
                    var xobjArtMQty = jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');
                    jQuery('#' + xobjArtMridFrom).val(ui.item.mtkn_rid);
                    jQuery('#' + xobjArtMridPromoSrp).val(ui.item.ART_UPRICE);
                    jQuery('#' + xobjArtMQty).focus();
                    

              return false;
                  }
              })
                .click(function() { 
                    //jQuery(this).keydown(); 
                    var terms = this.value.split('=>');
                    //jQuery(this).autocomplete('search', '');
                    jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
                });        
            } 

  function __tamt_compute_totals(fld_ptyp_i){ 
      try { 
          var rowCount1 = jQuery('#tbl_PayData tr').length - 1;
          var adata1 = [];
          var adata2 = [];
          var mdata = '';
          var ninc = 0;
          var nTAmount = 0;
          var nTAmountCost = 0;
          var nTQty = 0;
          var nTQtyItems = 0;
          for(aa = 1; aa < rowCount1; aa++) { 
              var clonedRow = jQuery('#tbl_PayData tr:eq(' + aa + ')').clone(); 
              var mdat1 = jQuery(clonedRow).find('input[type=text]').eq(0).val();
              var mdat2 = jQuery(clonedRow).find('input[type=text]').eq(1).val();
              var mdat3 = jQuery(clonedRow).find('input[type=text]').eq(2).val();//uom/pkg
              var mdat4 = jQuery(clonedRow).find('input[type=text]').eq(3).val();//ucost
              var mdat5 = jQuery(clonedRow).find('input[type=text]').eq(4).val();//tcost
              var mdat6 = jQuery(clonedRow).find('input[type=text]').eq(5).val();//srp
              var mdat7 = jQuery(clonedRow).find('input[type=text]').eq(6).val();//tamt

              var xTAmntCostId = jQuery(clonedRow).find('input[type=text]').eq(7).attr('id');
               var xTAmntCostIdh = jQuery(clonedRow).find('input[type=hidden]').eq(7).attr('id');
              var xTQtyId = jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');
              var xTQtyIdh = jQuery(clonedRow).find('input[type=hidden]').eq(3).attr('id');


              
              var nqty = 0;
              var nqtyc = 0;
              var nprice = 0;
              var promosrp = 0;
              if($.trim(mdat4) == '') { //ucost
                  nqty = 0;
              } else { 
                 
                  nqty = mdat4;
              }

              if($.trim(mdat7) == '') { //srp
                  promosrp = 0;
              } else { 
                 
                  promosrp = mdat7;
              }
             
              var ntqty = parseFloat(nqty);
              var tpromosrp = parseFloat(promosrp);

              //TOTAL COST AMT
              if($('#' + xTAmntCostIdh).val()==''){
                var ntCost = parseFloat(ntqty * tpromosrp);
              }
              else{

                  var ntCost = parseFloat(ntqty * tpromosrp);
              }
               
               //TOTAL AMT COST
              if(!isNaN(ntCost) || ntCost > 0) { 
                  $('#' + xTAmntCostId).val(__mysys_apps.oa_addCommas(ntCost.toFixed(2)));
                 // console.log(xTAmntId);
              }


              
              nTAmount = (nTAmount + ntCost);
              nTQty = (nTQty + ntqty);
              
          }  //end for 
  
          if (!isNaN(nTAmount) || nTAmount < 0){
              $('#totalQty').val(__mysys_apps.oa_addCommas(nTQty.toFixed(2)));
          }
          if (!isNaN(nTQty) || nTQty < 0){
              $('#totalpromoSrp').val(__mysys_apps.oa_addCommas(nTAmount.toFixed(2)));
          }

        
      } catch(err) {
          var mtxt = 'There was an error on this page.\n';
          mtxt += 'Error description: ' + err.message;
          mtxt += '\nClick OK to continue.';
          alert(mtxt);
      }  //end try
      
  } //__tamt_compute_totals
        //
  function deleteRow(cobj,muid,mhrid){
    try {

      var _rfpcf_rid = jQuery('#promotrxno').val();

      if(_rfpcf_rid === ''){

        var lcon = confirm('Record selected will permanently deleted...\nProceed anyway?');
        if(lcon) { 
          jQuery(cobj).parent().parent().remove();
          __tamt_compute_totals();
        } //end if

      }
      else {
        var lcon = confirm('Record selected will permanently deleted...\nProceed anyway?');
        if(lcon) { 
          

          var clonedRow = jQuery(cobj).parent().parent().clone();
          var xobjtermsId = jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id');
          _rid_sv = jQuery('#'+xobjtermsId).val();
          var trnstyp = 2;

          var mparam = { 
            mtkn_mmn_rid:mhrid,
            mtkn_mndt_rid:muid
          };

          jQuery.ajax({ 
            type: "POST",
            url: '<?=site_url()?>WhCrossing/dtdel_rec',
            context: document.body,
            data: eval(mparam),
            global: false,
            cache: false,
            success: function(data) { 
              // $.hideLoading();
              jQuery('#myModalSysMsgBod').html(data);
              jQuery('#myModSysMsg').modal('show');

              jQuery(cobj).parent().parent().remove();
              __tamt_compute_totals();
              
              return false;
            },
            error: function() { 
              // $.hideLoading();
              alert('error loading page...');
              return false;
            } 
          });

        } //end if
      }
    } catch (err) {
      var mtxt = 'There was an error on this page.\n';
      mtxt += 'Error description: ' + err.message;
      mtxt += '\nClick OK to continue.';
      alert(mtxt);
    } 
    return false;
  }

  jQuery('#branchName')
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
            source: function( request, response ) {
              $.ajax({
                url: "<?= site_url(); ?>mysearchdata/companybranch_v/",
                dataType: "json",
                data: {
                  term: request.term
                },
                 beforeSend:function(){
                   $('#ld_spinner').addClass('fa-spin');
                 },
                 complete:function(){
                   $('#ld_spinner').removeClass('fa-spin');
                 },
                success: function( data ) {
                  response( data );
                },
                error: function (xhr, textStatus, errorThrown) {
                  $.hideLoading();
                jQuery('#myModalSysMsgBod').html(`<div class="alert alert-danger"> <strong>${textStatus.toUpperCase()}</strong><br>${errorThrown}. Please report to administrator! </div>`);
                jQuery('#myModSysMsg').modal('show');
               }
              });
            },
            // source: '<?= site_url(); ?>mysearchdata/companybranch_v/',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) {
                var sValue = jQuery(oEvent.target).val();
   
            },
            select: function( event, ui ){

              this.value = ui.item.value

              let inputID =  $(this).attr('id');
              $('#branchName').val(ui.item.value);
              $('#branchName').attr('data-mtknid',ui.item.mtkn_brnch);
                //jQuery(this).autocomplete('search', jQuery.trim(ui.item.value));
                return false;
            }
        })
        .click(function(){
            var terms = this.value;
            jQuery(this).autocomplete('search', jQuery.trim(terms));

        });
      

  function post_trx(mtkn_itm,trxno){ 
          try{ 
           
            var mparam = {
              mtkn_itm: mtkn_itm

            }; 
            var lcon = confirm(`Are you sure you want to post ${trxno}?`);
            if(!lcon) return false ;
            $.showLoading({name: 'line-pulse', allowHide: false });
            $.ajax({ // default declaration of ajax parameters
            type: "POST",
            url: '<?=site_url();?>WhCrossing/prodmg_post',
            context: document.body,
            data: eval(mparam),
            global: false,
            cache: false,

          success: function(data)  { //display html using divID
            $.hideLoading();
            jQuery('#myModalSysMsgBod').html(data);
            jQuery('#myModSysMsg').modal('show');
            return false;
          },
          error: function() { // display global error on the menu function
            alert('error loading page...');
            $.hideLoading();
            return false;
          }   
          }); 
          } catch(err){
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            alert(mtxt);
            $.hideLoading();
            return false;
          }  //end try            
  }

  function proDamage_extract_rpt(mtkn,tag = ''){
        try { 
           
            $.showLoading({name: 'line-pulse', allowHide: false });
            var mparam = {
               mtkn: mtkn,
               tag:tag

            }; 

            $.ajax({ // default declaration of ajax parameters
                type: "POST",
                url: '<?=site_url();?>WhCrossing/prodmg_report_dl',
                context: document.body,
                data: eval(mparam),
                global: false,
                cache: false,

                success: function(data)  { //display html using divID
                    $.hideLoading();
                    jQuery('#div_dl').html(data);
                
                    return false;
                },
                error: function() { // display global error on the menu function
                    alert('error loading page...');
                    $.hideLoading();
                    return false;
                }   
            }); 
      
    } catch(err) {
        var mtxt = 'There was an error on this page.\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\nClick OK to continue.';
        alert(mtxt);
        $.hideLoading();
        return false;
    }  //end try  

  // body...
  }

  $("#posting_btn").click(function(){
    __myredirected_rsearch_dmgpromo(1);
  }); 

  function __myredirected_rsearch_dmgpromo(mobj){ 
    try { 
      //$('html,body').scrollTop(0);
      $.showLoading({name: 'line-pulse', allowHide: false });
      var branch     = $('#postingbranchName').data('mtknid');
      var branchName = $('#postingbranchName').val();
      var fromdate   = $('#postingstartDate').val();
      var todate     = $('#postingendDate').val();
      
      var mparam = {
        branch: branch,
        branchName:branchName,
        fromdate: fromdate,
        todate:todate, 
        mpages: mobj 
      };  
      $.ajax({ // default declaration of ajax parameters
      type: "POST",
      url: '<?=site_url();?>WhCrossing/posting_prodmg_recs',
      context: document.body,
      data: eval(mparam),
      global: false,
      cache: false,
        success: function(data)  { //display html using divID
            $.hideLoading();
            $('#mymodoutrecs').html(data);
            
            return false;
        },
        error: function() { // display global error on the menu function
          alert('error loading page...');
          $.hideLoading();
          return false;
        } 
      });     
                
    } catch(err) {
      var mtxt = 'There was an error on this page.\n';
      mtxt += 'Error description: ' + err.message;
      mtxt += '\nClick OK to continue.';
      alert(mtxt);
      $.hideLoading();
      return false;

    }  //end try
  } 
  
__my_item_lookup();
__my_promotim_lookup();
__tamt_compute_totals();

  function mywg_dmgpromodash(mtkn_arttr) { 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
      url: "<?=site_url();?>WhCrossing/dmgpromodash_vw",
      type: "post",
      data: {
        mtkn_arttr: mtkn_arttr
      }
    });

  ajaxRequest.done(function(response, textStatus, jqXHR) {
    jQuery('#my-dmgpromodash').html(response);

  });
  };

 $('#nav-dmgpromodash-tab').on('click',function(){
     mywg_dmgpromodash();
  });

 $(document).on('change', ':file', function() {
  var input     = $(this),
  numFiles      = input.get(0).files ? input.get(0).files.length : 1,
  label         = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  var label     = $(this).parent();
  var lblountID = $(this).data('id');
    $('#'+lblountID).css("margin","0rem");
    if(numFiles > 1 ){
       $('#'+lblountID).text (numFiles + ' files selected');
    }
    else{
       $('#'+lblountID).text (numFiles + ' file selected');
    }
 });


 $('.btn_approve').on('click',function(elem){
    approval_vw($(this).val());
 });


function approval_vw(mktn_hdrid = ''){

 try{
  
  if(mktn_hdrid == ''){
    return false;
  }

  var mparam = {
    mtkn_trxno:mktn_hdrid
  }
  if( mktn_hdrid != '' ){
  $.showLoading({name: 'line-pulse', allowHide: false });
  $.ajax({
    type:'POST',
    url:'<?=site_url();?>WhCrossing/promodmg_approval_vw',
    context:document.body,
    data:eval(mparam),
    global:false,
    cache:false,
    success:function(data){
      jQuery('#entmeapproval_bod').html(data);
      jQuery('#entmeapproval').modal('show');
      $.hideLoading();
      return false;

    },
    error:function(){
      alert('error loading page...');
      $.hideLoading();
      return false;
    }

  });
  }
 }
  catch(err){
    var mtxt = 'There was an error on this page.\n';
    mtxt += 'Error description: ' + err.message;
    mtxt += '\nClick OK to continue.';
    alert(mtxt);
  }
  return false;

}
 $('.vw_files_').click(function(){
    viewAttachment($(this).val());
 });

function viewAttachment(tkn){
 $.showLoading({name: 'circle-fade', allowHide: false });
 var ajaxRequest;
  ajaxRequest = jQuery.ajax({
    url: "<?=site_url();?>WhCrossing/promodmg_filesGet",
    type: "POST",
    data: {
      tkn: tkn
    }
  });

  // Deal with the results of the above ajax call
  ajaxRequest.done(function(response, textStatus, jqXHR) {
    $.hideLoading();
    $('#myImageModal').modal('show');
    $('#myImageModalBod').html(response);

  });
}


 function proDamage_excel_rpt(mtkn){
       try { 
          
           $.showLoading({name: 'line-pulse', allowHide: false });
           var mparam = {
              mtkn: mtkn

           }; 

           $.ajax({ // default declaration of ajax parameters
               type: "POST",
               url: '<?=site_url();?>WhCrossing/prodmg_excel_dl',
               context: document.body,
               data: eval(mparam),
               global: false,
               cache: false,

               success: function(data)  { //display html using divID
                   $.hideLoading();
                   jQuery('#div_dl').html(data);
               
                   return false;
               },
               error: function() { // display global error on the menu function
                   alert('error loading page...');
                   $.hideLoading();
                   return false;
               }   
           }); 
     
   } catch(err) {
       var mtxt = 'There was an error on this page.\n';
       mtxt += 'Error description: ' + err.message;
       mtxt += '\nClick OK to continue.';
       alert(mtxt);
       $.hideLoading();
       return false;
   }  //end try  

 // body...
 }


 $('#tbl_PayData').on('keydown', "input", function(e){ 
  __mysys_apps.tableKeysUpDown('tbl_PayData');
 });


</script>