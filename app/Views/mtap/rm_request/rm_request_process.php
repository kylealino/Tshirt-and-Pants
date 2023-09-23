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
						<th>CODE</th>
						<th>DESC</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if($rlistP !== ''):
						$nn = 1;
						foreach($rlistP as $row): 
						?>
						<tr>
							<td nowrap><?=$row['ART_CODE'];?></td>
							<td nowrap><?=$row['ART_DESC'];?></td>

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
			<div class="col-sm-4">
                <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
                <button id="mbtn_mn_NTRX" type="button" class="btn btn-primary btn-sm">New Entry</button>
              </div>
		</div>
	</div> <!-- end table-reponsive -->
	
<script type="text/javascript"> 


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
	
	
</script>
