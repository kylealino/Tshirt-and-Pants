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
					<table class="table table-striped table-bordered table-condensed text-center" id="tbl-gr-recs">
						<thead class="text-dgreen">
							<tr>
								<th > <?=anchor('good-receive', '<i class="bi bi-plus"></i>',' class="btn btn-dgreen-ol btn-xs" ');?></th>
								<th>
									<button class="btn btn-dgreen-ol" id="refresh-recs"><i class="bi bi-arrow-repeat"> </i> </button>
								</th>
								<th>GR No</th>
								<th>Company</th>
								<th>Plant</th>
								<th>Warehouse</th>
								<th>Total Actual Qty</th>
								<th>Total Actual Cost</th>
								<th>Total Actual SRP</th>
								<th>User</th>
								<th>Remarks</th>
								<th>GR Date</th>
								<th>Y/N IsApproved</th>
								<th>Print Form</th>
								<th>Generate Barcode</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									
									$bgcolor = ($nn % 2) ? "#EAF3F3" : "#FFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									$mtkn_trxno = hash('sha384', $row['recid'] . $mpw_tkn);
									$dis = ($row['post_tag'] == 'Y' || $row['df_tag'] == 'D' ? "disabled" : '');
									$dis2 = ($row['is_apprvd'] == 'N' ? "style=\"display:none\" " : '');
									$dis3 = (($row['is_apprvd'] == 'N' && $row['is_bcodegen'] == 'N') || (($row['is_apprvd'] == 'Y' && $row['is_bcodegen'] == 'Y')) ? "disabled" : '');
									
									//$dis_edt = ($row['post_tag'] == 'Y' ? "isDisabled" : '');
								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td class="text-center" nowrap="nowrap">
										<?=anchor('good-receive/?mtkn_trxno=' . $mtkn_trxno, '<i class="bi bi-pencil-square"></i>',' class="btn btn-primary btn-xs"');?>
										
									</td>
								
									<!-- <td class="text-center" nowrap="nowrap">
										<button title="Hint: Ready for Posting when tag is Final.Draft or Already posted is disabled." id="post_<?=$row['recid'];?>" class="btn btn-info btn-xs" type="button" onclick="javascript:__sv_post_po('<?=$mtkn_trxno;?>','<?=$row['recid'];?>');" <?=$dis;?>><i class="bi bi-paper-plane"></i></button>
									</td> -->
									<td nowrap="nowrap"><button class="btn btn-danger btn-xs" type="button" onclick="javascript:__mndt_invent_crecs('<?=$mtkn_trxno;?>');"><i class="bi bi-x"></i></button></td>
									<td nowrap="nowrap"><?=$row['grtrx_no'];?></td>
									<td nowrap="nowrap"><?=$row['COMP_NAME'];?></td>
									<td nowrap="nowrap"><?=$row['plnt_code'];?></td>
									<td nowrap="nowrap"><?=$row['wshe_code'];?></td>
									<td nowrap="nowrap"><?=number_format($row['hd_subtqty'],2,'.',',');?></td>
									<td <?=$str_style;?> nowrap="nowrap"><?=number_format($row['hd_subtcost'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['hd_subtamt'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=$row['muser'];?></td>
									<td nowrap="nowrap"><?=$row['remk'];?></td>
									<td nowrap="nowrap"><?=$mylibzsys->mydate_mmddyyyy($row['encd_date']);?></td>
									<td nowrap="nowrap"><?=$row['is_apprvd'];?></td>
									<td nowrap="nowrap">
										<button onclick="window.open('<?= site_url() ?>gr-print?mtkn_trans_rid=<?=$mtkn_trxno?>')" class=" btn btn-primary" <?=$dis2;?> title="View pdf"><i class="bi bi-file-pdf"></i></button>
									</td>
									<td nowrap="nowrap">
										<button title="Hint:Barcode Generation will done once." class="btn btn-danger btn-xs" type="button" onclick="javascript:gr_bcode_gen('<?=$mtkn_trxno;?>');" <?=$dis3;?>><i class="bi bi-printer"></i></button>
									</td>
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
	           "targets":[0,1,13,14],
	           "orderable": false
	       },
	       {
	        targets:[0,1,2,3,4,5,9,10,11,12,13,14],
	        className: 'dt-head-center'
	   		 },
	   		     {
      	 	 targets: [6,7,8],
        	 className: 'dt-body-right'
    		}
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
	
// 	function __myredirected_rsearch(mobj) { 
// 		try { 
// 			//$('html,body').scrollTop(0);
// 			$.showLoading({name: 'line-pulse', allowHide: false });
// 			var txtsearchedrec = $('#mytxtsearchrec').val();
			
// 			var mparam = {
// 				txtsearchedrec: txtsearchedrec,
// 				mpages: mobj 
// 			};	
// 			$.ajax({ // default declaration of ajax parameters
// 			type: "POST",
// 			url: '<?=site_url();?>mytrx_gr/mndt_invent_gr_recs',
// 			context: document.body,
// 			data: eval(mparam),
// 			global: false,
// 			cache: false,
// 				success: function(data)  { //display html using divID
// 						  __mysys_apps.mepreloader('mepreloaderme',false);
// 						$('#mymodoutrecs').html(data);
						
// 						return false;
// 				},
// 				error: function() { // display global error on the menu function
// 					alert('error loading page...');
// 					  __mysys_apps.mepreloader('mepreloaderme',false);
// 					return false;
// 				}	
// 			});			
								
// 		} catch(err) {
// 			var mtxt = 'There was an error on this page.\n';
// 			mtxt += 'Error description: ' + err.message;
// 			mtxt += '\nClick OK to continue.';
// 			alert(mtxt);
// 			  __mysys_apps.mepreloader('mepreloaderme',false);
// 			return false;

// 		}  //end try
// 	}	
// 	$('#mytxtsearchrec').keypress(function(event) { 
// 		if(event.which == 13) { 
// 			event.preventDefault(); 
// 			try { 
// 				$('html,body').scrollTop(0);
// 				$.showLoading({name: 'line-pulse', allowHide: false });
// 				var txtsearchedrec = $('#mytxtsearchrec').val();
// 				var mparam = {
// 					txtsearchedrec: txtsearchedrec,
// 					mpages: 1 
// 				};	
// 				$.ajax({ // default declaration of ajax parameters
// 				type: "POST",
// 				url: '<?=site_url();?>mytrx_gr/mndt_invent_gr_recs',
// 				context: document.body,
// 				data: eval(mparam),
// 				global: false,
// 				cache: false,
// 					success: function(data)  { //display html using divID
// 							  __mysys_apps.mepreloader('mepreloaderme',false);
// 							$('#mymodoutrecs').html(data);
							
// 							return false;
// 					},
// 					error: function() { // display global error on the menu function
// 						alert('error loading page...');
// 						  __mysys_apps.mepreloader('mepreloaderme',false);
// 						return false;
// 					}	
// 				});	
// 			} catch(err) {
// 				var mtxt = 'There was an error on this page.\n';
// 				mtxt += 'Error description: ' + err.message;
// 				mtxt += '\nClick OK to continue.';
// 				alert(mtxt);
// 				  __mysys_apps.mepreloader('mepreloaderme',false);
// 				return false;
// 			}  //end try	
			
// 		}
// 	});
	

	
function gr_bcode_gen(mtkn_grtr) { 

                try { 
    
                    /**/
                     __mysys_apps.mepreloader('mepreloaderme',true);
                    
                    var mparam = {
                       mtkn_grtr: mtkn_grtr

                    }; 

                $.ajax({ // default declaration of ajax parameters
                    type: "POST",
                    url: '<?=site_url();?>gr-barcode-gen',
                    context: document.body,
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
            } catch(err) {
                var mtxt = 'There was an error on this page.\n';
                mtxt += 'Error description: ' + err.message;
                mtxt += '\nClick OK to continue.';
                alert(mtxt);
                  __mysys_apps.mepreloader('mepreloaderme',false);
                return false;
            }  //end try            
       }

// </script>
