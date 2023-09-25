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
					if($rlistP !== ''):
						$nn = 1;
						foreach($rlistP as $row): 
						?>
						<tr>
							<td nowrap><input type="text" class="text-center rounded" name="RM_CODE" id="RM_CODE" value="<?=$row['RM_CODE'];?>" readonly></td>
							<td nowrap><input type="text" class="text-center rounded" name="RM_DESC" id="RM_DESC" value="<?=$row['RM_DESC'];?>" readonly></td>
							<td nowrap><input type="text" class="text-center rounded" name="RM_QTY" id="RM_QTY" value="<?=number_format($row['RM_QTY'],2);?>" readonly></td>
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

          var prod_plan_trxno = jQuery('#prod_plan_trxno').val();
          var branch_name = jQuery('#branch_name').val();
          var opt_df = jQuery('#opt_df').val();
          var txt_request_date = jQuery('#txt_request_date').val();
          var txt_total_qty = jQuery('#txt_total_qty').val();
		  var txt_qty_serve = jQuery('#txt_qty_serve').val();
          var txt_amount_serve = jQuery('#txt_amount_serve').val();

          var rowCount1 = jQuery('#tbl-process-recs tr').length;
          var adata1 = [];
          var mdata = '';
          var ninc = 0;

		  for(aa = 1; aa < rowCount1; aa++) { 
			var clonedRow = jQuery('#tbl-process-recs tr:eq(' + aa + ')').clone(); 
			var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //ITEM CODE
			var mdesc = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //UOM
			var mqty = jQuery(clonedRow).find('input[type=text]').eq(2).val(); //UOM
			var minv = jQuery(clonedRow).find('input[type=text]').eq(3).val(); //UOM

			
			mdata = mitemc + 'x|x' + mdesc + 'x|x' + mqty + 'x|x' + minv;
			adata1.push(mdata);

		}

		var mparam = {
		adata1: adata1
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
