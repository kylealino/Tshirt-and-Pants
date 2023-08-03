
<?php 
/**
 *	File        : maintenance/mycomp_BU-recs.php
 *  Auhtor      : Arnel L. Oquien
 *  Date Created: Sept 05, 2018
 * 	last update : Sept 05, 2018
 * 	description : Business unit records
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

$data = array();
$mpages = (empty($mylibzsys->oa_nospchar($request->getVar('mpages'))) ? 0 : $mylibzsys->oa_nospchar($request->getVar('mpages')));
$mpages = ($mpages > 0 ? $mpages : 1);
$apages = array();
$mpages = $npage_curr;
$npage_count = $npage_count;
for($aa = 1; $aa <= $npage_count; $aa++) {
	$apages[] = $aa . "xOx" . $aa;
}
$txtsearchrec = $request->getVar('txtsearchedrec_rl');

$search_cb = $request->getVar('search_cb');
$ch_checked = ($search_cb == 'Y')?'checked':'';

?>

<div class="table-responsive mt-4 text-center ">
<table class="table table-condensed table-hover table-bordered table-sm align-middle">
<thead>
	<tr class="text-dgreen"> <!---BUTTON FOR ADDING Product Line---->
		<th class="text-center">
			<?=anchor('whcrossing', '<i class="bi bi-plus text-white fs-5"></i>',' class="btn btn-sm" ');?>
		</th>
		<!---BUTTON FOR ADDING Product Line end ---->
		<th>Allocation Guide Trx. No</th>
		<th>Ref. PO Number</th>
        <th><i class="bi bi-gear"></i></th>
    
	</tr>
</thead>
<tbody>
	<?php 
	if($rlist !== ''):
		$nn = 1; //use for changin the bgcolor of table
		foreach($rlist as $row): 
			$bgcolor = ($nn % 2) ? "#EAF3F3" : "#FFF";
			$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
			//giving data for $txt_mtknr
			$txt_mtknr = hash('sha384', $row['recid'] . $mpw_tkn);
			
		?>
		<!---EDIT START BUTTON--->
		<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
			<td nowrap="nowrap"><?=$nn;?></td>	
			<td nowrap="nowrap"><?=$row['agpo_sysctrlno'];?></td>
			<td ><?=$row['__poref'];?></td>
			<td class="text-center" nowrap="nowrap">
			<button  class="btn-revert-ag btn btn-danger" value="<?=$txt_mtknr?>" > <i class="bi bi-x-circle"></i> Revert</button>
			<!-- <button  class="btn btn-dgreen btn-sm  btn-sm"> MKG</button> -->
			</td>
		</tr>
		<?php 
		$nn++;
		endforeach;
	endif; 
		?>
</tbody>
</table>
</div>


<script type="text/javascript"> 

$('.btn-revert-ag').on('click',function(){
	try{
		var mtkn = $(this).val();

		var mparam = {
		  mtkn_trxno:mtkn,
		}


		__mysys_apps.mepreloader('mepreloaderme',true);
		$.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url()?>mycrossing-revert',
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,
		success: function(data)  { //display html using divID
			__mysys_apps.mepreloader('mepreloaderme',false);

			jQuery('#myModalSysMsgBod').html(data);
			jQuery('#myModSysMsg').modal('show');
	
			return false;
		},
		error: function() { // display global error on the menu function
			alert('error loading page...');
			__mysys_apps.mepreloader('mepreloaderme',false);
			return false;
		} 
		}); 

	}
	catch(err){
		var mtxt = 'There was an error on this page.\n';
		mtxt += 'Error description: ' + err.message;
		mtxt += '\nClick OK to continue.';
		alert(mtxt);
		__mysys_apps.mepreloader('mepreloaderme',false);
		return false;
	}
});





	
</script>
