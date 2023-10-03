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
							  		<th>Edit</th>
                                    <th>View</th>
									<th>Delete</th>
									<th>Transaction No.</th>
									<th>Branch</th>
									<th>Entry Date</th>
									<th>QTS</th>
									<th>Amount</th>
				          	</tr>
		            	</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									$prod_plan_trxno = $row['prod_plan_trxno'];
								?>
								<tr>
									<td class="text-center" nowrap>
										<?=anchor('me-tp-alloc-vw/?prod_plan_trxno=' . $prod_plan_trxno, '<i class="bi bi bi-eye"></i> View ',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm"');?>
									</td>
                                    <td nowrap="nowrap">
										<button title="View items" class="btn btn-dgreen-ol btn-xs tpa-btn-view-items"  data-pptrxno= "<?=$row['prod_plan_trxno'];?>"  type="button" ><i class="bi bi-eye-fill"></i> Items</button>
									</td>
									<td nowrap="nowrap">
										<button type="button" class="btn btn-xs btn-danger"><i class="bi bi-x"></i></button>
									</td>
									<td nowrap><?=$row['prod_plan_trxno'];?></td>
									<td nowrap><?=$row['brnch_name'];?></td>
									<td nowrap><?=$row['entry_date'];?></td>
									<td nowrap><?=$row['qty_serve'];?></td>
                                    <td nowrap><?=$row['amount_serve'];?></td>
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
		           
       'order':[4,'DESC'],
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
	var pptrxno = jQuery(this).attr('data-pptrxno'); 
	
	var mtkn_dt = this.value;
	$('#anchor-list').removeClass('active');
	$('#anchor-items').addClass('active');

	var mparam = {
		mtkn_dt: mtkn_dt,
		pptrxno:pptrxno,
		mpages:1
	};

	$.ajax({ // default declaration of ajax parameters
	type: "POST",
	url: '<?=site_url();?>prod-plan-items',
	context: document.body,
	data: eval(mparam),
	global: false,
	cache: false,
		success: function(data)  { //display html using divID
			__mysys_apps.mepreloader('mepreloaderme',false);
		$('#prod-vw').html(data);
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

