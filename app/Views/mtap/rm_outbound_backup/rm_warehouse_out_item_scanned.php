<?php

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$mtkn_trxno = $request->getVar('mtkn_trxno');
$cuser   = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$barcodes = '';

?>

<hr class="prettyline">
<div class="box box-primary">
	<div class="box-body">
		<div class="row pt-3">
			<div class="col-md-12">
				<?php if($mtkn_trxno != ""): ?>
				<div class="rounded  mb-4 text-left col-lg-4 ">
					<p class="fw-bold fst-italic mt-1 "> <i class="bi bi-exclamation-circle-fill fw-bold text-danger"></i> Select only those that have not been released. </p>
				</div>
				 <?php endif; ?>
				<div class="table-responsive">
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-whout-items-recs">
		            	<thead class="thead-light">
				          	<tr class="text-dgreen">
					            <th nowrap="nowrap" data-orderable="false">BOX CONTENT</th>
					            <th nowrap="nowrap"><input type="checkbox" title="Check all" id="checkAll" class="green-cb fs-2" style="scale:1.3" ></th>
					            <th nowrap="nowrap">STOCK CODE</th>
					            <th nowrap="nowrap">ITEM CODE</th>
					            <th nowrap="nowrap">ITEM DESC</th>
					            <th nowrap="nowrap">PACKAGING</th>
					            <th nowrap="nowrap">QTY</th>
					            <th nowrap="nowrap">CONVF</th>
							  <th nowrap="nowrap">TOTAL PCS</th>
							<!--   <th nowrap="nowrap">UNIT PRICE</th> -->
							  <th nowrap="nowrap">TOTAL AMT</th>
							  <th nowrap="nowrap">BRANCH</th>
							  <th nowrap="nowrap">PACKING LIST</th>
					            <th nowrap="nowrap">BARCODE</th>
					  		  <th nowrap="nowrap">CBM</th>
					            <th nowrap="nowrap">WEIGHT</th>

				          	</tr>
		            	</thead>
			            <tbody id="tbody-inv-items-recs">
			              	<?php 

		              		if($result != ""):
		              			$nn = 1;
		              		
		              			foreach($result as $row):
					              $bgcolor = ($nn % 2) ? "#EAF3F3" : "#FFF";
					              $on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	

				           	$txt_mtknr = hash('sha384', $row['recid'] . $mpw_tkn);
				                  
				                  	$pcolor = "";
				
					       
			              	?>
			              	<tr bgcolor="<?= $bgcolor ?>" <?=$on_mouse?> >
			              		<td nowrap="nowrap">
		              			<?php if($row['is_out'] == 0): ?>
		              			<button class="btn btn-dgreen btn-sm" onclick="getBoxcontent('<?=$txt_mtknr;?>')"> <i class="bi bi-box-seam"></i> View</button>	
			              		<?php else: ?>
			              			-
			              		<?php endif; ?>
			              		</td>

								<td nowrap="nowrap" >
			              		<?php if($row['is_out'] == 0 && $count == 0 ): ?>
				              		<input type="checkbox"  class="mycheckbox green-cb fs-2" style="scale:1.3" data-id="" disabled>
				              	<?php else: ?>
				              		<input type="checkbox"  class="mycheckbox green-cb fs-2" style="scale:1.3" data-id="<?=$row['barcde']?>" >
			              		<?php endif; ?>
				              	</td> 

			              		<td nowrap="nowrap"><?=$row['stock_code']?></td>
			              		<td nowrap="nowrap"><?=$row['ART_CODE']?></td>
			              		<td nowrap="nowrap"><?=$row['ART_DESC']?></td>
			              		<td nowrap="nowrap">BOX</td>
			              		<td nowrap="nowrap"><?=$row['qty']?></td>
								<td nowrap="nowrap"><?=$row['convf']?></td>
								<td nowrap="nowrap"><?=$row['total_pcs_scanned']?></td>
								<!-- <td nowrap="nowrap"><?=$row['uprice']?></td> -->
								<td nowrap="nowrap"><?=$row['tamt_scanned']?></td>
								<td nowrap="nowrap"><?=$row['wshe_bin_name']?></td>
								<td nowrap="nowrap"><?=$row['remarks']?></td>
								<td nowrap="nowrap"><?=$row['barcde']?></td>
								<td nowrap="nowrap"><?=$row['cbm']?></td>
								<td nowrap="nowrap"><?=$row['weight']?></td>
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
			            <tfoot class="fw-bold">
			            	<tr> 
			            	<td colspan="5"></td>
			            	<td>TOTAL QTY</td>
			            	<td id="tf-qty">0.00</td>
			            	<td></td>
			            	<td>TOTAL AMT</td>
			            	<td id="tf-amt">0.00</td>
			            	<td colspan="2"></td>
			            	<td>TOTAL CBM</td>
			            	<td colspan="2" id="tf-cbm">0.00</td>
			            </tr>
			            </tfoot>
		          	</table>
	        	</div>
	        	<?php if(!empty($mtkn_trxno)): ?>
			<div class="form-row mt-2">
			<?php if($isdone == 0): ?>
			<button type="button" class="btn bg-dgreen btn-sm" id="btn-pl-update"> <i class="bi bi-save"> </i> Update</button>  
			<?php else: ?>
			<a type="button" href ="<?=site_url()?>warehouse-out" class="btn bg-dgreen btn-sm"> <i class="bi bi-plus-circle"> </i> New Trx</a>  
			<?php endif; ?>
			</div>
			</div>
			<?php elseif(empty($mtkn_trxno) && $result != ""):?>
			<div class="form-row mt-2">
			<button type="button" class="btn bg-dgreen btn-sm" id="btn-pl-sv"> <i class="bi bi-save"> </i> GENERATE DOC</button>  
			</div>
			</div>
			<?php endif;?>
	      
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){


 $("#checkAll").click(function (){

  $('.mycheckbox:enabled').prop('checked', this.checked);


});
	
 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });

  var tbl_pl_items = $('#tbl-whout-items-recs').DataTable({               
       'order':[],
       'columnDefs': [{
           "targets":[0,1,5,6,7],
           "orderable": false
       },
      { //center th
       targets:'_all' ,
       className: 'dt-head-center'
  	 }
       ]
   });

  
  $('#tbl-whout-items-recs_filter.dataTables_filter [type=search]').each(function () {
       $(this).attr(`placeholder`, `Search...`);
       $(this).before('<span class="bi bi-search text-dgreen"></span>');
   });

$('#btn-pl-update').on('click',function(){
	try { 

		var mtkn_whse = jQuery('#txt-warehouse').attr("data-id");

		var item_array = [];
		var trs =  tbl_pl_items.$('input.mycheckbox:checkbox:checked', {"page": "all"});

		trs.each(function(index,elem){
			mdata = $(elem).attr('data-id');
			item_array.push(`'${mdata}'`);

		});

		if(item_array.length == 0 ){
			jQuery('#memsgtestent_danger_bod').html('No items selected!');
			jQuery('#memsgtestent_danger').modal('show');
			return false;
		}

		var adata1 = item_array.join(',');
		var mparam = {
		  adata1:adata1,
		  txtWarehousetkn:mtkn_whse,
		  mtkn_trxno:'<?=$mtkn_trxno?>'
		}


	__mysys_apps.mepreloader('mepreloaderme',true);
	$.ajax({ // default declaration of ajax parameters
		type: "POST",
		url: '<?=site_url()?>warehouse-out-updt',
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

function getTotal(){
	var item_array = [];
	var totalqty    = 0;
	var totalamount = 0;
	var totalcbm = 0;
	var trs =  tbl_pl_items.$('input.mycheckbox:checkbox:checked', {"page": "all"});
  	trs.each(function(index,elem){
  		var clonerow     = $(elem).parent().parent().parent().clone();
  		var qty_value    = $(clonerow).find('td').eq(6).text();
  		var amt_value    = $(clonerow).find('td').eq(9).text();
  		var cbm_value    = $(clonerow).find('td').eq(13).text();
		totalqty    += parseInt(qty_value);
		totalamount += parseFloat(amt_value);
		totalcbm += parseFloat(cbm_value);

     });

	$('#tf-qty').html(totalqty);
	$('#tf-amt').html(totalamount);
	$('#tf-cbm').html(totalcbm);
			
}

$('.mycheckbox').on('change',function(){

	getTotal();

});

$('#checkAll').on('change',function(){
	getTotal();
});

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
	    var chk_by         = $('#chk_by').val();
	    var sm_tag         = $('#sm-tag').val();
	    var sm_tag         = $('#sm-tag').val();
	    var is_mkg         = ($('#is-mkg').is(':checked'))?'Y':'N';
	    var truck_type     = $('#truck-type').val(); 
	    var rems_ =  $('#rems_').val(); 

	    var item_array = [];
	    var trs =  tbl_pl_items.$('input.mycheckbox:checkbox:checked', {"page": "all"});

	    trs.each(function(index,elem){
	    	mdata = $(elem).attr('data-id');
	    	item_array.push(`'${mdata}'`);

	    });

	    if(item_array.length == 0 ){
	    	jQuery('#memsgtestent_danger_bod').html('No items selected!');
	    	jQuery('#memsgtestent_danger').modal('show');
	    	return false;
	    }

	    	var adata1 = item_array.join(',');

		var mparam = {
		  control_number:control_number,
		  branch_name:branch_name,
		  plate_number:plate_number,
		  driver:driver,
		  helper_one:helper_one,
		  helper_two:helper_two,
		  ref_no:ref_no,
		  chk_by:chk_by,
		  sm_tag:sm_tag,
		  is_mkg:is_mkg,
		  adata1:adata1 ,
		  rcvcount:item_array.length,
		  txtWarehousetkn:mtkn_whse,
		  truck_type:truck_type,
		  rems_:rems_
		}

	__mysys_apps.mepreloader('mepreloaderme',true);
	$.ajax({ // default declaration of ajax parameters
		type: "POST",
		url: '<?=site_url()?>rm-out-sv',
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


});

</script>