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
								<th>TPA Transaction No.</th>
								<th>Branch</th>
								<th>Request Date</th>
								<th>Request Qty</th>
								<th>Proceeded Qty</th>
								<th>Remaining Qty</th>
								<th>Process</th>
								<th><i class="bi bi-gear-fill"> </i></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									//$dis_edt = ($row['post_tag'] == 'Y' ? "isDisabled" : '');
									$variance = '<span class="badge bg-dgreen"> NO DATA </span>';
									$tpa_trxno = $row['tpa_trxno'];
									$total_qty = $row['total_qty'];
									$proceeded_qty = $row['proceeded_qty'];
									$rmng_qty = ($total_qty - $proceeded_qty);

									if($total_qty == 0){
									  $variance = $variance;
									}
									elseif($total_qty > $proceeded_qty || $total_qty < $proceeded_qty){
									  $variance = '<span class="badge bg-danger"> VARIANCE OCCUR </span>';
									}
									elseif($total_qty == $proceeded_qty ){
									  $variance = '<span class="badge bg-success"> TALLY </span>';
									}
									else{
									  $variance = $variance;
									}
								?>
								<tr>
									<td nowrap="nowrap"><?=$row['tpa_trxno'];?></td>
									<td nowrap="nowrap"><?=$row['branch_name'];?></td>
									<td nowrap="nowrap"><?=$mylibzsys->mydate_mmddyyyy($row['req_date']);?></td>
									<td nowrap="nowrap"><?=$row['total_qty'];?></td>
									<td nowrap="nowrap"><?=$row['proceeded_qty'];?></td>
									<td nowrap="nowrap"><?=$rmng_qty;?></td>
									<td nowrap="nowrap">
										<?php if($total_qty != $proceeded_qty ):?>
											
									<?=anchor('me-fgpack-req-vw/?tpa_trxno=' . $tpa_trxno, 'PROCESS',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm"');?>

									<?php else: ?>
							  			<i class="bi bi-dash-lg text-dgreen"> </i>
							  		<?php endif; ?>
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

// </script>
