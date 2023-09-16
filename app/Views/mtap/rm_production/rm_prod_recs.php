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
									<th>RMAP Transaction No.</th>
									<th>Plant</th>
									<th>Request Date</th>
									<th>Request</th>
									<th>Proceeded</th>
									<th>Produce</th>
                                    <th>Remaining</th>
                                    <th>Process</th>
                                    <th>Action</th>
				          	</tr>
		            	</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									$txt_mtknr = hash('sha384', $row['rmap_trxno'] . $mpw_tkn);
									$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									$rmap_trxno = $row['rmap_trxno'];
									$produce_release = $row['produce_release'];
									$item_qty = $row['item_qty'];
									$produce_qty = $row['produce_qty'];
								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td nowrap><?=$row['rmap_trxno'];?></td>
									<td nowrap><?=$row['plnt_id'];?></td>
									<td nowrap><?=$row['request_date'];?></td>
									<td nowrap><?=$row['item_qty'];?></td>
									<td nowrap><?=$row['produce_qty'];?></td>
                                    <td nowrap><?=$row['produce_release'];?></td>
                                    <td nowrap><?=$row['produce_rmng'];?></td>
									
                                    <td nowrap>
									<?php if($produce_qty != $produce_release):?>
                                        <?=anchor('rm-prod/?rmap_trxno=' . $rmap_trxno, 'PROCESS',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm"');?>
                                    
									<?php else:?>
										<i class="bi bi-dash-lg text-dgreen"> </i>
									<?php endif;?>
									</td>
                                    <td nowrap>
                                    <button title="View items" class="btn btn-dgreen-ol btn-xs rm-req-btn-view-items"  data-rmapno= "<?=$row['rmap_trxno'];?>" value="<?=$rmap_trxno?>"  type="button" ><i class="bi bi-eye-fill"></i> Items</button>
                                    </td>
									
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
		           
       'order':[2,'DESC'],
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

$('.rm-req-btn-view-items').on('click',function(){
try { 
	__mysys_apps.mepreloader('mepreloaderme',true);
	var rmapno = jQuery(this).attr('data-rmapno'); 
	
	var mtkn_dt = this.value;
	$('#anchor-list').removeClass('active');
	$('#anchor-items').addClass('active');

	var mparam = {
		mtkn_dt: mtkn_dt,
		rmapno:rmapno,
		mpages:1
	};

	$.ajax({ // default declaration of ajax parameters
	type: "POST",
	url: '<?=site_url();?>rm-out-items',
	context: document.body,
	data: eval(mparam),
	global: false,
	cache: false,
		success: function(data)  { //display html using divID
			__mysys_apps.mepreloader('mepreloaderme',false);
		$('#packlist').html(data);
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

