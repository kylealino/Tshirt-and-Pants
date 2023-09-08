<?php 
/**
*  File        : good_receive/gr_main.php
*  Author      : Arnel L. Oquien
*  Date Created: Nov 22,2022
*  last update : Nov 22,2022
*  description : Good receive entry crossdocking
*/

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$mywhout = model('App\Models\MyWarehouseoutModel');
$db_erp =$mydbname->medb(1);

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$cusergrp = $mylibzdb->mysys_usergrp();


$str_style = '';
$cuserrema = $mylibzdb->mysys_userrema();
if($cuserrema ==='B'){
    //$this->load->view('template/novo/header_br');
    $str_style=" style=\"display:none;\"";
}
else{
    //$this->load->view('template/novo/header');
    $str_style='';
}
?>


	<div class="box box-primary">
		<div class="box-body">
			<div class="row pt-3">
			<div class="col-md-12 col-md-12 col-md-12">
			<div class="table-responsive">
					<table class="table table-bordered table-condensed text-center" id="tbl-gr-recs">
						<thead class="text-dgreen">
							<tr>	
								<th>FGPR Transaction No.</th>
								<th>TPA Transaction No.</th>
								<th>Plant</th>
								<th>Branch</th>
								<th>Entry Date</th>
								<th>Process</th>
								<th>FGP Print</th>
								<th>BOM Print</th>
								<th>Generate</th>
								<th>Download</th>
								<th><i class="bi bi-gear-fill"> </i></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									
									$mtkn_trxno = hash('sha384', $row['recid'] . $mpw_tkn);
									$txt_mtknr = hash('sha384', $row['fgreq_trxno'] . $mpw_tkn);
									$fgreq_trxno = $row['fgreq_trxno'];
									$tpa_trxno = $row['tpa_trxno'];
									$variance = '<span class="badge bg-dgreen"> NO DATA </span>';
									$pack_qty =  $row['pack_qty'];
									$is_packed = $row['is_packed'];
									$processed_pack = $row['processed_pack'];
									if($processed_pack == 0){
									  $variance = $variance;
									}
									elseif($pack_qty > $processed_pack || $pack_qty < $processed_pack){
									  $variance = '<span class="badge bg-danger"> VARIANCE OCCUR </span>';
									}
									elseif($pack_qty == $processed_pack ){
									  $variance = '<span class="badge bg-success"> TALLY </span>';
									}
									else{
									  $variance = $variance;
									}
								?>
								<tr>
									<td nowrap="nowrap"><?=$row['fgreq_trxno'];?></td>
									<td nowrap="nowrap"><?=$row['tpa_trxno'];?></td>
									<td nowrap="nowrap"><?=$row['plnt_id'];?></td>
									<td nowrap="nowrap"><?=$row['branch_name'];?></td>
									<td nowrap="nowrap"><?=$row['req_date'];?></td>
									<td nowrap="nowrap">
									<?php if($pack_qty != $processed_pack ):?>
											
									<?=anchor('me-fg-prod-vw/?fgreq_trxno=' . $fgreq_trxno, 'PROCESS',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm"');?>

									<?php else: ?>
							  			<i class="bi bi-dash-lg text-dgreen"> </i>
							  		<?php endif; ?>
									</td>
									
									<td nowrap="nowrap">
										<button onclick="window.open('<?= site_url() ?>fg-prod-print?fgreq_trxno=<?=$fgreq_trxno?>')" class=" btn btn-primary btn-xs"  title="View pdf"><i class="bi bi-file-pdf bi-sm"></i> Print</button>
									</td>
									<?php if($is_packed === '1'):?>
									<td nowrap="nowrap">
										<button onclick="window.open('<?= site_url() ?>fg-prod-bom-print?fgreq_trxno=<?=$fgreq_trxno?>&tpa_trxno=<?=$tpa_trxno;?>')" class=" btn btn-primary btn-xs"  title="View pdf"><i class="bi bi-file-pdf bi-sm"></i>BOM Print</button>
									</td>
									<td nowrap="nowrap">
										<button title="Hint:Barcode Generation will done once." class="btn btn-danger btn-xs" type="button" onclick="javascript:fg_prod_bcode_gen('<?=$fgreq_trxno;?>');"><i class="bi bi-printer"></i> Generate</button>
									</td>
									<?php else:?>
									<td nowrap="nowrap">
										<button onclick="window.open('<?= site_url() ?>fg-prod-bom-print?fgreq_trxno=<?=$fgreq_trxno?>&tpa_trxno=<?=$tpa_trxno;?>')" class=" btn btn-primary btn-xs"  title="View pdf" disabled><i class="bi bi-file-pdf bi-sm"></i> BOM Print</button>
									</td>
									<td nowrap="nowrap">
										<button title="Hint:Barcode Generation will done once." class="btn btn-danger btn-xs" type="button" onclick="javascript:fg_prod_bcode_gen('<?=$fgreq_trxno;?>');" disabled><i class="bi bi-printer"></i> Generate</button>
									</td>
									
									<?php endif;?>
									<td>
					                  <button onclick="javascript:__mbtn_fgprod_bdownload('<?=$fgreq_trxno;?>');" class="btn btn-sm btn-primary"><i class="bi bi-printer"> Download</i></button>
					                </td>
									<td nowrap="nowrap"><?=$variance;?></td>
								</tr>
								<?php 
								$nn++;
								endforeach;
								else:
								?>
								<tr>
									<td colspan="9">No data was found.</td>
								</tr>
							<?php 
							endif; ?>
						</tbody>
					</table>

				</div>
			</div>
			</div>
		</div> <!-- end box body -->
	</div> <!-- end box --> 
	
<?php
  echo $mylibzsys->memsgbox1('memsgtestent_success','<i class="bi bi-check-circle"></i> System Alert','...','bg-psuccess');
?>  	
<script type="text/javascript"> 

$(document).ready(function(){
	 $.extend(true, $.fn.dataTable.defaults,{
	      language: {
	          search: ""
	      }
	  });

	  var tbl_pl_items = $('#tbl-gr-recs').DataTable({  
            
	       'order':[],
	       'columnDefs': [{
	           "targets":[6,7],
	           "orderable": false
	       },
			{
			targets:'_all',
			className: 'dt-head-center'
			},
			// 	     {
			// 	 targets: [6,7,8],
			// 	 className: 'dt-body-right'
			// 	}

	       ]
	   });

	   $('#tbl-gr-recs_filter.dataTables_filter [type=search]').each(function () {
	        $(this).attr(`placeholder`, `Search...`);
	        $(this).before('<span class="bi bi-search text-dgreen"></span>');
	    });

	});


$('#refresh-recs').on('click',function(){
  gr_ent_recs();
});


// 	function __sv_post_po(mtkn_trxno,id_post){
// 		var ajaxRequest;

// 			ajaxRequest = jQuery.ajax({
// 				url: "<?=site_url();?>mytrx_gr/gr_posting",
// 				type: "post",
// 				data: {
// 					mtkn_trxno: mtkn_trxno,
// 					id_post: id_post
// 				}
// 			});

// 			// Deal with the results of the above ajax call
// 			ajaxRequest.done(function(response, textStatus, jqXHR) {
// 				jQuery('#myMod_gr_post_Bod').html(response);
//                 jQuery('#myMod_gr_post').modal('show');
// 				// and do it again
// 				//setTimeout(get_if_stats, 5000);
// 			});
// 	}

	function __mndt_invent_crecs(mtkn_itm) { 

                try { 
                    $('html,body').scrollTop(0);
                    var cusergrp ='<?=$cusergrp;?>';
                    if (cusergrp !='SA'){
                    	var mtxt = 'You dont have authorized to delete this data.\n';
		                alert(mtxt);
		                return false;
                    }
                     __mysys_apps.mepreloader('mepreloaderme',true);
                    var mparam = {
                       mtkn_itm: mtkn_itm

                    }; 

                $.ajax({ // default declaration of ajax parameters
                    type: "POST",
                    url: '<?=site_url();?>gr-ent-cancel',
                    context: document.body,
                    data: eval(mparam),
                    global: false,
                    cache: false,

                    success: function(data)  { //display html using divID
                          __mysys_apps.mepreloader('mepreloaderme',false);
	                       jQuery('#memsgtestent_success_bod').html(data);
	                       jQuery('#memsgtestent_success').modal('show');


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
        }
	


	
	function gr_upld_recs(rmNo){ 
    try { 

         __mysys_apps.mepreloader('mepreloaderme',true);
         var txtWarehousetkn = jQuery('#txt-warehouse').attr('data-id'); 
        
        var mparam = {
           rmNo: rmNo,
           txtWarehousetkn:txtWarehousetkn

        }; 

    $.ajax({ // default declaration of ajax parameters
        type: "POST",
        url: '<?=site_url();?>rm-rcvng-upld',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,

        success: function(data)  { //display html using divID
              __mysys_apps.mepreloader('mepreloaderme',false);
            // jQuery('#myModSysMsgBod').html(data);
        	// jQuery('#myModSysMsg').modal('show');
        	jQuery('#mymodoutrecs').html(data);
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
	}

	$('.gr-btn-view-items').on('click',function(){


		try { 
			// $('html,body').scrollTop(0);
			__mysys_apps.mepreloader('mepreloaderme',true);
			var mtkn_whse = jQuery('#txt-warehouse').attr('data-id'); 
			var grno = jQuery(this).attr('data-grno'); 
			
			var mtkn_dt = this.value;
			$('#anchor-list').removeClass('active');
			$('#anchor-items').addClass('active');
		
			var mparam = {
				mtkn_whse:mtkn_whse,
				mtkn_dt: mtkn_dt,
				grno:grno,
				mpages:1
			};

			$.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>rm-rcvng-items',
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,
				success: function(data)  { //display html using divID
					__mysys_apps.mepreloader('mepreloaderme',false);
				$('#mymodoutentrecs').html(data);
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
	function fg_prod_bcode_gen(fgreq_trxno) { 

		try { 

			/**/
			__mysys_apps.mepreloader('mepreloaderme',true);
			
			var mparam = {
				fgreq_trxno: fgreq_trxno

			}; 

		$.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>fg-prod-barcode-gen',
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,

			success: function(data)  { //display html using divID
				__mysys_apps.mepreloader('mepreloaderme',false);
				// jQuery('#myModSysMsgBod').html(data);
				// jQuery('#myModSysMsg').modal('show');
				jQuery('#memsgtestent_bod').html(data);
				jQuery('#memsgtestent').modal('show');

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
		}

		function __mbtn_fgprod_bdownload(fgreq_trxno) { 
        try {   
            // var txt_ponumb = $('#txt_ponumb').val();
            // var active_wshe_id = $('#active_wshe_id').val();//jQuery('#active_wshe_id').attr("data-id");
            __mysys_apps.mepreloader('mepreloaderme',true);
            var mparam ={
                fgreq_trxno: fgreq_trxno
            }
            
            jQuery.ajax({ // default declaration of ajax parameters
                url: '<?=site_url()?>fg-prod-boxbarcode-dl',
                method:"POST",
                context:document.body,
                data: eval(mparam),
                global: false,
                cache: false,
                success: function(data)  { //display html using divID
                    __mysys_apps.mepreloader('mepreloaderme',false);
                    // jQuery('#myModSysMsgBod').html(data);
                    // jQuery('#myModSysMsg').modal('show');
					jQuery('#memsgtestent_success_bod').html(data);
					jQuery('#memsgtestent_success').modal('show');
                    return false;
                },
                error: function() { // display global error on the menu function
                    alert('error loading page...');
                    __mysys_apps.mepreloader('mepreloaderme',false);
                    return false;
                }   
            }); 
        } catch (err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
         __mysys_apps.mepreloader('mepreloaderme',false);
            alert(mtxt);
        } //end try
    }
// </script>
