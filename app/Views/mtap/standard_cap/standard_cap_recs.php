<?php

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');

$cuser   = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

?>

<div class="box box-primary">
	<div class="box-body">
		<div class="row pt-3">
			<div class="col-md-12">
				<div class="table-responsive">
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-transfer-verify-items-recs">
		            	<thead class="thead-light">
				          	<tr>
                                    <th>View</th>
									<th>Transaction No.</th>
									<th>Total Items</th>
									<th>Category</th>
									<th>Transaction Type</th>
									<th>Uploading Date Time</th>
				          	</tr>
		            	</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									$stan_cap_trxno = $row['stan_cap_trxno'];
								?>
								<tr>

                                    <td nowrap="nowrap">
										<button title="View items" class="btn btn-dgreen-ol btn-xs tpa-btn-view-items"  data-stcptrxno= "<?=$row['stan_cap_trxno'];?>"  type="button" ><i class="bi bi-eye-fill"></i> Items</button>
									</td>
									<td nowrap><?=$row['stan_cap_trxno'];?></td>
									<td nowrap><?=$row['total_items'];?></td>
									<td nowrap><?=$row['opt_cat'];?></td>
									<td nowrap><?=$row['opt_type'];?></td>
                                    <td nowrap><?=$row['encd'];?></td>
								</tr>
								<?php 
								$nn++;
								endforeach;
							else:
								?>
								<tr>
									<td colspan="18">No data was found.</td>
								</tr>
							<?php 
							endif; ?>
						</tbody>
		          	</table>
	        	</div>
	        	<hr>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });
	$('#tbl-transfer-verify-items-recs').DataTable({
		           
       'order':[5,'DESC'],
       'columnDefs': [{
           "targets":[0],
           "orderable": false
       },
		{
		targets:'_all',
		className: 'dt-head-center'
		},
       ]
	});

   $('#tbl-transfer-verify-items-recs_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });

});

$('.tpa-btn-view-items').on('click',function(){
try { 
	// $('html,body').scrollTop(0);
	__mysys_apps.mepreloader('mepreloaderme',true);
	var stcptrxno = jQuery(this).attr('data-stcptrxno'); 
	
	var mtkn_dt = this.value;
	$('#anchor-list').removeClass('active');
	$('#anchor-items').addClass('active');

	var mparam = {
		mtkn_dt: mtkn_dt,
		stcptrxno:stcptrxno,
		mpages:1
	};

	$.ajax({ // default declaration of ajax parameters
	type: "POST",
	url: '<?=site_url();?>me-standard-cap-items',
	context: document.body,
	data: eval(mparam),
	global: false,
	cache: false,
		success: function(data)  { //display html using divID
			__mysys_apps.mepreloader('mepreloaderme',false);
		$('#stcp-vw').html(data);
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
</script>

