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
								<th>GR No</th>
								<th>Plant</th>
								<th>Warehouse</th>
								<th>Type</th>
								<th>Remarks</th>
								<th>User</th>
								<th>GR Date</th>
								<th>Status</th>
								<th>Print</th>
								<th><i class="bi bi-gear-fill"> </i></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									
									$mtkn_trxno = hash('sha384', $row['recid'] . $mpw_tkn);
									$txt_mtknr = hash('sha384', $row['header'] . $mpw_tkn);
									//$dis_edt = ($row['post_tag'] == 'Y' ? "isDisabled" : '');
								
									 $mtype = ($row['type'] == 'OUT')?'<span class="badge bg-dgreen"> OUT </span>':'<span class="badge bg-warning"> DAMAGE </span>';
									 $mstatus = ($row['is_approved'] == 'N')?'<span class="badge bg-danger"> N </span>':'<span class="badge bg-success"> Y </span>';

								?>
								<tr>
									<td nowrap="nowrap"><?=$row['header'];?></td>
									<td nowrap="nowrap"><?=$row['plnt_code'];?></td>
									<td nowrap="nowrap"><?=$row['wshe_code'];?></td>
									<td nowrap="nowrap"><?=$mtype;?></td>
									<td nowrap="nowrap"><?=$row['remarks'];?></td>
									<td nowrap="nowrap"><?=$row['muser'];?></td>
									<td nowrap="nowrap"><?=$mylibzsys->mydate_mmddyyyy($row['encd']);?></td>
									<td nowrap="nowrap">
							  			<?=$mstatus?>
									</td>
									<td>
										<?php if($row['is_approved'] == 'Y'):?>
										<button onclick="window.open('<?= site_url() ?>gi-print-show?mtkn_trans_rid=<?=$mtkn_trxno?>')" class=" btn btn-primary"  title="View pdf"><i class="bi bi-file-pdf"></i></button>
										<?php else:?>
											<i class="bi bi-dash-lg text-dgreen"> </i>
										<?php endif;?>
									</td>
									<td nowrap="nowrap">
										<button title="View items" class="btn btn-dgreen-ol btn-xs gr-btn-view-items"  data-gino= "<?=$row['header'];?>" value="<?=$txt_mtknr?>" type="button" ><i class="bi bi-eye-fill"></i> View</button>
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
	


	
	function gr_upld_recs(grNo){ 
    try { 

         __mysys_apps.mepreloader('mepreloaderme',true);
         var txtWarehousetkn = jQuery('#txt-warehouse').attr('data-id'); 
        
        var mparam = {
           grNo: grNo,
           txtWarehousetkn:txtWarehousetkn

        }; 

    $.ajax({ // default declaration of ajax parameters
        type: "POST",
        url: '<?=site_url();?>good-receive-rcvng-upld',
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
			var gino = jQuery(this).attr('data-gino'); 
			
			var mtkn_dt = this.value;
			$('#anchor-list').removeClass('active');
			$('#anchor-items').addClass('active');
		
			var mparam = {
				mtkn_whse:mtkn_whse,
				mtkn_dt: mtkn_dt,
				gino:gino,
				mpages:1
			};

			$.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>gi-entry-items',
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

// </script>
