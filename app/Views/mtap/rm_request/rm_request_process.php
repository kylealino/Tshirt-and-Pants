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
$txt_plant = $request->getVar('txt_plant');
$txt_subcon = $request->getVar('txt_subcon');
$txt_remarks = $request->getVar('txt_remarks');
$txt_request_date = $request->getVar('txt_request_date');
$txt_total_qty = $request->getVar('txt_total_qty');

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
	<input type="hidden" name="txt_plant" id="txt_plant" value="<?=$txt_plant;?>">
	<input type="hidden" name="txt_subcon" id="txt_subcon" value="<?=$txt_subcon;?>">
	<input type="hidden" name="txt_remarks" id="txt_remarks" value="<?=$txt_remarks;?>">
	<input type="hidden" name="txt_request_date" id="txt_request_date" value="<?=$txt_request_date;?>">
	<input type="hidden" name="txt_total_qty" id="txt_total_qty" value="<?=$txt_total_qty;?>">
	<div class="table-responsive">
		<div class="col-md-12 col-md-12 col-md-12">
			<table class="table table-condensed table-hover table-bordered table-sm text-center" id="tbl-process-recs-fg">
				<thead>
					<tr>
						<th>FG CODE</th>
						<th>FG QTY</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if(!empty($rlistP2)):
						$nn = 1;
						foreach($rlistP2 as $row): 
						?>
						<tr>
							<td nowrap><input type="text" class="text-center rounded" name="FG_CODE" id="FG_CODE" value="<?=$row['FG_CODE'];?>" readonly></td>
							<td nowrap><input type="text" class="text-center rounded" name="FG_QTY" id="FG_QTY" value="<?=$row['FG_QTY'];?>" readonly></td>
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
	</div>
	<div class="table-responsive">
		<div class="col-md-12 col-md-12 col-md-12">
			<table class="table table-condensed table-hover table-bordered table-sm text-center" id="tbl-process-recs">
				<thead>
					<tr>
						<th>ITEMCODE</th>
						<th>DESCRIPTION</th>
						<th>REQUEST</th>
						<th>INVENTORY</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if(!empty($rlistP)):
						$nn = 1;
						foreach($rlistP as $row): 
						?>
						<tr>
							<td nowrap><input type="text" class="text-center rounded" name="RM_CODE" id="RM_CODE" value="<?=$row['RM_CODE'];?>" readonly></td>
							<td nowrap><input type="text" class="text-center rounded" name="RM_DESC" id="RM_DESC" value="<?=$row['RM_DESC'];?>" readonly></td>
							<td nowrap><input type="text" class="text-center rounded" name="RM_QTY" id="RM_QTY" value="<?=$row['RM_QTY'];?>" readonly></td>
							<td nowrap><input type="text" class="text-center rounded" name="RM_INV" id="RM_INV" value="<?=number_format($row['RM_INV'],2);?>" readonly></td>
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
	
	<div class="col-sm-4">
		<button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
		<?=anchor('me-rm-req-vw', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-sm" ');?>
    </div>
	<?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?> 
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
	
	
// $("#mbtn_mn_Save").click(function(e){

// var mtkn_mntr = jQuery('#__hmpacktrxnoid').val();
// var rmap_trxno = jQuery('#rmap_trxno').val();

// var rowCount1 = jQuery('#tbl-process-recs tr').length;
// var adata1 = [];
// var adata2 = [];

// var mdata = '';
// var ninc = 0;

// for(aa = 1; aa < rowCount1; aa++) { 
// 	  var clonedRow = jQuery('#tbl-process-recs tr:eq(' + aa + ')').clone(); 
// 	  var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //ITEM CODE
// 	  var mdesc = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //UOM

	  
// 	  mdata = mitemc + 'x|x' + mdesc;
// 	  adata1.push(mdata);

//   }

// var mparam = {
//   adata1: adata1
// };  

// ajaxRequest = jQuery.ajax({
// 	url: "<?=site_url();?>me-rm-req-process-save",
// 	type: "post",
// 	data: eval(mparam)
// });

// // Deal with the results of the above ajax call
// __mysys_apps.mepreloader('mepreloaderme',true);
//   ajaxRequest.done(function(response, textStatus, jqXHR) {
// 	  jQuery('#rmlist').html(response);
// 	  __mysys_apps.mepreloader('mepreloaderme',false);
//   });
// });


$("#mbtn_mn_Save").click(function(e){
    try { 


        var txt_plant = jQuery('#txt_plant').val();
		var txt_subcon = jQuery('#txt_subcon').val();
		var txt_remarks = jQuery('#txt_remarks').val();
		var txt_request_date = jQuery('#txt_request_date').val();
		var txt_total_qty = jQuery('#txt_total_qty').val();
        var rowCount1 = jQuery('#tbl-process-recs tr').length;
		var rowCount2 = jQuery('#tbl-process-recs-fg tr').length;
        var adata1 = [];
		var adata2 = [];
        var mdata1 = '';
		var mdata2 = '';
        var ninc = 0;

		for(aa = 1; aa < rowCount1; aa++) { 

		var clonedRow = jQuery('#tbl-process-recs tr:eq(' + aa + ')').clone(); 
		var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //ITEM CODE
		var mdesc = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //UOM
		var mqty = jQuery(clonedRow).find('input[type=text]').eq(2).val(); //UOM
		var minv = jQuery(clonedRow).find('input[type=text]').eq(3).val(); //UOM

		
		mdata1 = mitemc + 'x|x' + mdesc + 'x|x' + mqty + 'x|x' + minv;
		adata1.push(mdata1);

		}

		for(aa = 1; aa < rowCount2; aa++) { 

		var clonedRow = jQuery('#tbl-process-recs-fg tr:eq(' + aa + ')').clone(); 
		var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //ITEM CODE
		var mqty = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //UOM

		mdata2 = mitemc + 'x|x' + mqty;
		adata2.push(mdata2);

		}

		var mparam = {
			txt_plant:txt_plant,
			txt_subcon:txt_subcon,
			txt_remarks:txt_remarks,
			txt_request_date:txt_request_date,
			txt_total_qty:txt_total_qty,
			adata1: adata1,
			adata2: adata2
		};  


      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-rm-req-process-save',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { 
            $(this).prop('disabled', false);
           // $.hideLoading();
            jQuery('#memsgtestent_bod').html(data);
            jQuery('#memsgtestent').modal('show');
            return false;
        },
        error: function() {
          alert('error loading page...');
         // $.hideLoading();
          return false;
        } 
      });

    } catch(err) {
      var mtxt = 'There was an error on this page.\n';
      mtxt += 'Error description: ' + err.message;
      mtxt += '\nClick OK to continue.';
      alert(mtxt);
    }  //end try
    return false; 
  });
</script>
