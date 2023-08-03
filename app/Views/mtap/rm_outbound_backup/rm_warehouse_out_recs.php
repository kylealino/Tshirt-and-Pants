<?php

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$mydatazua = model('App\Models\MyDatauaModel');
$cuser   = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$barcodes = '';
$revert = $mydatazua->get_Active_menus($mydbname->medb(1),$cuser,"myuatrx_id='205'","myua_trx");
?>


<div class="box box-primary">
	<div class="box-body">
		<div class="row pt-3">
			<div class="col-md-12">
				<div class="table-responsive">
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-out-recs">
		            	<thead class="thead-light">
				          	<tr class="text-dgreen">
				          	  <th nowrap="nowrap"><a href="<?= site_url() ?>warehouse-out" class="text-dgreen" > <i class="bi bi-plus-lg"></i></a></th>
					            <th nowrap="nowrap">CRPL CODE</th>
					            <th nowrap="nowrap">PLATE NO.</th>
					            <th nowrap="nowrap">BRANCH</th>
					            <th nowrap="nowrap">PLANT</th>
					            <th nowrap="nowrap">WAREHOUSE</th>
					            <th nowrap="nowrap">QTY</th>
					            <th nowrap="nowrap">ACTUAL QTY</th>
					            <th nowrap="nowrap">PRE PRINT</th>
					            <th nowrap="nowrap">POST PRINT</th>
					            <th nowrap="nowrap">DONE</th>
					            <th nowrap="nowrap">BACKLOAD</th>

				          	</tr>
		            	</thead>
			            <tbody id="tbody-inv-items-recs">
			              	<?php 
			              		if($rlist != ""):
			              			$nn = 1;
			              		
			              			foreach($rlist as $row):
						              $bgcolor = ($nn % 2) ? "#EAF3F3" : "#FFF";
						              $on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
					           	$txt_mtknr = hash('sha384', $row['recid'] . $mpw_tkn);
					           	$print = ($row['is_mkg'] == 'Y')?'rm-out-print-mkg':'rm-out-print';
					           	$fprint = ($row['is_mkg'] == 'Y')?'rm-out-fprint-mkg':'rm-out-fprint';

			              	?>
			              	<tr bgcolor="<?= $bgcolor ?>" <?=$on_mouse?> >
			              		<td>
			              		<?=anchor('warehouse-out/?mtkn_trxno=' . $txt_mtknr, '<i class="bi bi-pencil-square"></i>',' class="btn bg-dgreen btn-sm" ');?>	
			              		</td>
			              		<td nowrap="nowrap"><?=$row['crpl_code']?></td>
			              		<td nowrap="nowrap"><?=$row['plate_no']?></td>
			              		<td nowrap="nowrap"><?=$row['brnch']?></td>
			              		<td nowrap="nowrap"><?=$row['plnt_code']?></td>
							<td nowrap="nowrap"><?=$row['wshe_code']?></td>
							<td nowrap="nowrap"><?=$row['total_qty']?></td>
							<td nowrap="nowrap"><?=$row['actual_qty']?></td>
							<td><button onclick="window.open('<?= site_url().$print ?>?mtkn_whout=<?=$txt_mtknr?>')" class="btn bg-psuccess"> <i class="bi bi-printer"></i> Print</button>
							</td>
						
							<td>
							<?php if($row['done'] == 1): ?>
							<button  onclick="window.open('<?= site_url().$fprint ?>?mtkn_whout=<?=$txt_mtknr?>')" class="btn btn-dgreen"> <i class="bi bi-printer"></i> Post Print</button>
							<?php else: ?>
								<i class="bi bi-dash-lg text-success"></i>
							<?php endif; ?>
							</td>
							<td>	
							<?php if($row['done'] == 0): ?>
								<button  class="btn-whout-done btn btn-success" value="<?=$txt_mtknr?>" data-type="D"> <i class="bi bi-check-circle"></i> Done</button>
							<?php else: 
								if($revert == 1 ): ?>
								<button  class="btn-whout-done btn btn-danger" value="<?=$txt_mtknr?>" data-type="R" > <i class="bi bi-x-circle"></i> Revert</button>
								<?php else:?>	
								<i class="bi bi-check-lg text-success"></i>
								<?php endif; ?>
							<?php endif; ?>
							</td>
							<td>
							<?php if($row['done'] == 1 && $row['total_qty']!=  $row['actual_qty'] ): ?>
								<button class="btn bg-dgreen btn-backload" value="<?=$txt_mtknr?>"> <i class="bi bi-view-list"></i> View </button>
							<?php else: ?>
								<i class="bi bi-dash-lg text-success"></i>
							<?php endif; ?>
							</td>

			              	</tr>
			              	<?php
							$nn++;
							endforeach;
			              		else:
			              	?>
			              	<tr>
			              		<td nowrap="nowrap" colspan="13">No data was found.</td>
			              	</tr>
			              	<?php 
			              		endif;
			              
			              	?>
			            </tbody>
		          	</table>
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

  var tbl_pl_items = $('#tbl-out-recs').DataTable({               
       'order':[],
       'columnDefs': [{
           "targets":[0,6,7,8,9,10,11],
           "orderable": false
       },
       {
        targets:'_all' ,
        className: 'dt-head-center'
   	 }
       ]
   });

   $('#tbl-out-recs_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });

function getBoxcontent(mtkn_dt){

    	try { 
 	
      	var txtsearchedrec = $('#mytxtsearchrec_verify').val();

      	var mtkn_whse = jQuery('#txt-warehouse').attr("data-id");
      	var mparam = {
	        mtkn_dt: mtkn_dt,
	        mtkn_whse:mtkn_whse
      	}
      	__mysys_apps.mepreloader('mepreloaderme',true);
 		$.ajax({ // default declaration of ajax parameters
	        type: "POST",
	        url: '<?=site_url()?>warehouse-inv-box-content',
	        context: document.body,
	        data: eval(mparam),
	        global: false,
	        cache: false,
	        success: function(data)  { //display html using divID
	          	 __mysys_apps.mepreloader('mepreloaderme',false);
	   
			   $('#boxcontent_success_bod').html(data);
			   $('#boxcontent_success').modal('show');
				          
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




// $('#btn-pl-sv').on('click',function(){
//  var item_array = [];
// 	var trs =  tbl_pl_items.$('tr', {"page": "all"});
//   	trs.each(function(index,elem){
//      $(this).find('td input:checked').each(function(idx,elem){
//      		mdata = $(elem).attr('data-id');
// 				item_array.push(mdata);
//      });

//     	});
	
// console.table(item_array)
// });


$('#btn-pl-sv').on('click',function(){
	try { 

		var txtsearchedrec = $('#mytxtsearchrec_verify').val();

		var mtkn_whse = jQuery('#txt-warehouse').attr("data-id");
	

	    var control_number = $('#control-number').val();
	    var branch_name    = $('#branch-name').val();
	    var plate_number   = $('#plate-number').val();
	    var driver         = $('#driver').val();
	    var helper_one     = $('#helper-one').val();
	    var helper_two     = $('#helper-two').val();
	    var helper_two     = $('#helper-two').val();
	    var ref_no         = $('#ref-no').val();
	    var sm_tag         = $('#sm-tag').val();
	    var is_mkg         = ($('#is-mkg').is(':checked'))?'Y':'N';

	
		// var item_array = [];
		// // var trs =  tbl_pl_items.$('input.mycheckbox:checkbox:checked', {"page": "all"});
		// // trs.each(function(index,elem){
		// // 	mdata = $(elem).attr('data-id');
		// // 	item_array.push(`'${mdata}'`);

		// // });
		// // var adata1 = item_array.join(',');

		var mparam = {
		  control_number:control_number,
		  branch_name:branch_name,
		  plate_number:plate_number,
		  driver:driver,
		  helper_one:helper_one,
		  helper_two:helper_two,
		  ref_no:ref_no,
		  sm_tag:sm_tag,
		  is_mkg:is_mkg,
		  adata1:`<?=$barcodes?>`,
		  txtWarehousetkn:mtkn_whse
		}

	__mysys_apps.mepreloader('mepreloaderme',true);
	$.ajax({ // default declaration of ajax parameters
		type: "POST",
		url: '<?=site_url()?>warehouse-out-sv',
		context: document.body,
		data: eval(mparam),
		global: false,
		cache: false,
	success: function(data)  { //display html using divID
		__mysys_apps.mepreloader('mepreloaderme',false);

		$('#memsgtestent_success_bod').html(data);
		$('#memsgtestent_success').modal('show');

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



}); //document ready end


$('.btn-backload').on('click',function(){

try { 

  var txtsearchedrec = $('#mytxtsearchrec_verify').val();
  var mtkn_whse = jQuery('#txt-warehouse').attr("data-id");
  const mktn_hd = $(this).val();

  var mparam = {
	mktn_hd:mktn_hd
  }
  __mysys_apps.mepreloader('mepreloaderme',true);
 $.ajax({ // default declaration of ajax parameters
	type: "POST",
	url: '<?=site_url()?>warehouse-out-backload',
	context: document.body,
	data: eval(mparam),
	global: false,
	cache: false,
	success: function(data)  { //display html using divID
		   __mysys_apps.mepreloader('mepreloaderme',false);
	   $('#myoutdrecs').html(data);
	   $('#anchor-items').addClass('active');
	   $('#anchor-list').removeClass('active');
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


//this function is used to Done and Revert SD OUT
$('.btn-whout-done').on('click',function(){
	try{
		var mtkn = $(this).val();
		var mtkn_whse = jQuery('#txt-warehouse').attr("data-id");
		var btn_type = jQuery(this).attr("data-type");
		let url = (btn_type == 'D')?'<?=site_url()?>warehouse-out-done':'<?=site_url()?>warehouse-out-revert';

		var mparam = {
		  mtkn_trxno:mtkn,
		  txtWarehousetkn:mtkn_whse,
		  btn_type:btn_type
		}


		__mysys_apps.mepreloader('mepreloaderme',true);
		$.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: url,
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,
		success: function(data)  { //display html using divID
			__mysys_apps.mepreloader('mepreloaderme',false);

			$('#memsgtestent_success_bod').html(data);
			$('#memsgtestent_success').modal('show');
			whseout_ent_recs(jQuery('#txt-warehouse').attr("data-id"));

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