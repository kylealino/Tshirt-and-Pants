<?php 
/**
 *	File        : masterdata/md-customer-profile.php
 *  Auhtor      : Joana Rocacorba
 *  Date Created: May 6, 2022
 * 	last update : May 6, 2022
 * 	description : Customer Records
 */
 
$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytrxfgpack = model('App\Models\MyFGPackingModel');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

$mytxtsearchrec = $request->getVar('txtsearchedrec');


$data = array();
$mpages = (empty($mylibzsys->oa_nospchar($request->getVar('mpages'))) ? 0 : $mylibzsys->oa_nospchar($request->getVar('mpages')));
$mpages = ($mpages > 0 ? $mpages : 1);
$apages = array();
$mpages = $npage_curr;
$npage_count = $npage_count;
for($aa = 1; $aa <= $npage_count; $aa++) {
	$apages[] = $aa . "xOx" . $aa;
}

?>
<style>
table.memetable, th.memetable, td.memetable {
  border: 1px solid #F6F5F4;
  border-collapse: collapse;
}
thead.memetable, th.memetable, td.memetable {
  padding: 6px;
}
</style>
<div class="col-md-8">
	</div>
	<div class="table-responsive">
		<div class="col-md-12 col-md-12 col-md-12">
			<table class="table table-condensed table-hover table-bordered table-sm " id="tbl-transfer-verify-items-recs">
				<thead>
					<tr>
						<th class="text-center">
							
						</th>
						<th>RMAP Transaction No.</th>
						<th>Sub Contractor</th>
						<th>Plant</th>
						<th>Remarks</th>
						<th>Request Date</th>
						<th>Total Qty</th>
						<th><i class="bi bi-check2-circle"></th>
						<th><i class="bi bi-file-pdf bi-sm"></i></th>
						<th><i class="bi bi-file-pdf bi-sm"></i></th>
						<th><i class="bi bi-link"></i></th>

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
							$is_processed = $row['is_processed'];
							$fg_release = $row['fg_release'];
							$fg_qty = $row['fg_qty'];
						?>
						<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
							<?php if($is_processed == 1):?>
								<td class="text-center" nowrap>
									<?=anchor('me-rm-req-vw/?rmap_trxno=' . $rmap_trxno, '<i class="bi bi bi-pencil"></i> Edit ',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm disabled"');?>
								</td>
							<?php else:?>
								<td class="text-center" nowrap>
									<?=anchor('me-rm-req-vw/?rmap_trxno=' . $rmap_trxno, '<i class="bi bi bi-pencil"></i> Edit ',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm"');?>
								</td>
							<?php endif;?>
							<td nowrap><?=$row['rmap_trxno'];?></td>
							<td nowrap><?=$row['subcon'];?></td>
							<td nowrap><?=$row['plnt_id'];?></td>
							<td nowrap><?=$row['remarks'];?></td>
							<td nowrap><?=$row['request_date'];?></td>
							<td nowrap><?=$row['total_qty'];?></td>
							<?php if($is_processed == 1):?>
								<td nowrap="nowrap">
									<button class="btn btn-success btn-xs mbtn_recs_Process disabled"  data-rmapno= "<?=$row['rmap_trxno'];?>" value="<?=$txt_mtknr?>"  type="button"> Process </button>
								</td> 
								<td>
									<button onclick="window.open('<?= site_url() ?>rm-req-rm-print?rmap_trxno=<?=$rmap_trxno?>')" class=" btn btn-primary btn-xs"  title="View pdf" > RM Print </button>
								</td>
								<td>
									<button onclick="window.open('<?= site_url() ?>rm-req-fg-print?rmap_trxno=<?=$rmap_trxno?>')" class=" btn btn-primary btn-xs"  title="View pdf" > FG Print </button>
								</td>
								<?php if($fg_qty != $fg_release):?>
									<td nowrap="nowrap">
										<?=anchor('rm-prod/?rmap_trxno=' . $rmap_trxno, '<i class="bi bi bi-send"></i> ',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm "');?>
									</td>
								<?php else:?> 
									<td nowrap="nowrap">
										<?=anchor('rm-prod/?rmap_trxno=' . $rmap_trxno, '<i class="bi bi bi-send"></i> ',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm disabled"');?>
									</td>
								<?php endif;?>	

							<?php else:?>
								<td nowrap="nowrap">
									<button class="btn btn-success btn-xs mbtn_recs_Process"  data-rmapno= "<?=$row['rmap_trxno'];?>" value="<?=$txt_mtknr?>"  type="button"> Process </button>
								</td> 
								<td>
									<button onclick="window.open('<?= site_url() ?>rm-req-rm-print?rmap_trxno=<?=$rmap_trxno?>')" class=" btn btn-primary btn-xs disabled"  title="View pdf" > RM Print </button>
								</td>
								<td>
									<button onclick="window.open('<?= site_url() ?>rm-req-fg-print?rmap_trxno=<?=$rmap_trxno?>')" class=" btn btn-primary btn-xs disabled"  title="View pdf" > FG Print </button>
								</td>
								<td nowrap="nowrap">
									<?=anchor('rm-prod/?rmap_trxno=' . $rmap_trxno, '<i class="bi bi bi-send"></i> ',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm disabled"');?>
								</td> 
							<?php endif;?>
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
	</div> <!-- end table-reponsive -->
<?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
?> 
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

$('.rm-req-btn-view-items').on('click',function(){
try { 
	// $('html,body').scrollTop(0);
	__mysys_apps.mepreloader('mepreloaderme',true);
	var mtkn_whse = jQuery('#txt-warehouse').attr('data-id'); 
	var rmapno = jQuery(this).attr('data-rmapno'); 
	
	var mtkn_dt = this.value;
	$('#anchor-list').removeClass('active');
	$('#anchor-items').addClass('active');

	var mparam = {
		mtkn_whse:mtkn_whse,
		mtkn_dt: mtkn_dt,
		rmapno:rmapno,
		mpages:1
	};

	$.ajax({ // default declaration of ajax parameters
	type: "POST",
	url: '<?=site_url();?>rm-req-items',
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

__mysys_apps.mepreloader('mepreloaderme',false);


$('.mbtn_recs_Process').on('click',function(){
try { 
	// $('html,body').scrollTop(0);
	__mysys_apps.mepreloader('mepreloaderme',true);

	var rmapno = jQuery(this).attr('data-rmapno'); 

	var mparam = {

		rmapno:rmapno

	};

	$.ajax({ // default declaration of ajax parameters
	type: "POST",
	url: '<?=site_url();?>me-rm-rec-process-save',
	context: document.body,
	data: eval(mparam),
	global: false,
	cache: false,
		success: function(data)  { //display html using divID
			__mysys_apps.mepreloader('mepreloaderme',false);
			$(this).prop('disabled', false);
           // $.hideLoading();
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

});

$('.mbtn_recs_Process').on('click',function(){
try { 
	// $('html,body').scrollTop(0);
	__mysys_apps.mepreloader('mepreloaderme',true);

	var rmapno = jQuery(this).attr('data-rmapno'); 

	var mparam = {

		rmapno:rmapno

	};

	$.ajax({ // default declaration of ajax parameters
	type: "POST",
	url: '<?=site_url();?>me-rm-rec-process-save',
	context: document.body,
	data: eval(mparam),
	global: false,
	cache: false,
		success: function(data)  { //display html using divID
			__mysys_apps.mepreloader('mepreloaderme',false);
			$(this).prop('disabled', false);
           // $.hideLoading();
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

});

</script>
