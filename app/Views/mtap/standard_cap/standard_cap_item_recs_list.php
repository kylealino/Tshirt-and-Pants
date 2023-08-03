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
<style>
#mat_code,#opt_cat,#branch_name{
	border:none;
}	
</style>
<div class="py-2 mt-3">
	<h5 class="text-end text-dgreen"> <span class="fw-bold">Itemcode : <?=$stcpitem?> </span> </h5>
</div>
<div class="box box-primary">
	<div class="box-body">
		<div class="row pt-3">
			<div class="col-md-12">
				<div class="table-responsive">
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-transfer-verify-items-recs-list">
		            	<thead class="thead-light">
				          	<tr>
                                <th nowrap="nowrap" style="color:red;">Itemcode</th>
                                <th nowrap="nowrap">Standard Qty</th>
                                <th nowrap="nowrap">Type</th>
                                <th nowrap="nowrap">Branch</th>
				          	</tr>
		            	</thead>
			            <tbody id="tbody-transfer-verify-items-recs">
			              	<?php 
			              		if($rlist != ""):
			              			$nn = 1;
									  foreach($rlist as $row): 
			              	?>
							<tr>
							<td nowrap="nowrap"><input type="text" id="mat_code" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$row['mat_code'];?>" disabled></td>
							<td nowrap="nowrap"><input type="text" id="opt_cat" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$row['opt_cat'];?>" disabled></td>
							<td nowrap="nowrap"><input type="text" id="branch_name" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$row['branch_name'];?>" disabled></td>
                            <td nowrap="nowrap"><input type="text" id="cap_qty" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$row['cap_qty'];?>"></td>
	
			              	<?php
		              			$nn++;
								endforeach;
		              			endif;
			              	
			              	?>
			            </tbody>
		          	</table>
	        	</div>
	        	<hr>
			</div>
		</div>
		<div class="row pt-3">
			<div class="col-sm-4 text-start">
				<button id="mbtn_mn_Update" type="submit" class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i>Update</button>
			</div>
		</div>
	</div>
</div>

<?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>  
<script type="text/javascript">

$(document).ready(function(){
 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });
	$('#tbl-transfer-verify-items-recs-list').DataTable({
		           
        'order':[],
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

   $('#tbl-transfer-verify-items-recs-list_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });

});

__mysys_apps.mepreloader('mepreloaderme',false);

$("#mbtn_mn_Update").click(function(e){
  try { 

		var rowCount1 = jQuery('#tbl-transfer-verify-items-recs-list tr').length;
		var adata1 = [];
		var mdata = '';

		for(aa = 1; aa < rowCount1; aa++) { 
			  var clonedRow = jQuery('#tbl-transfer-verify-items-recs-list tr:eq(' + aa + ')').clone(); 
			  var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val();
			  var mcat = jQuery(clonedRow).find('input[type=text]').eq(1).val();
			  var mbranch = jQuery(clonedRow).find('input[type=text]').eq(2).val();
			  var mqty = jQuery(clonedRow).find('input[type=text]').eq(3).val();
			 
			  mdata = mitemc + 'x|x' + mcat + 'x|x' + mbranch + 'x|x' + mqty;
			  adata1.push(mdata);
		  }  //end for

		var mparam = {
		  adata1: adata1
		};  


	$.ajax({ 
	  type: "POST",
	  url: '<?=site_url();?>me-standard-cap-update',
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

__mysys_apps.mepreloader('mepreloaderme',false);

</script>