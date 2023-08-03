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

<div class="py-2 mt-3">
	<h5 class="text-end text-dgreen"> <span class="fw-bold">RMAP No. : <?=$rmapno?> </span> </h5>
</div>
<div class="box box-primary">
	<div class="box-body">
		<div class="row pt-3">
			<div class="col-md-12">
				<div class="table-responsive">
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-transfer-verify-items-recs">
		            	<thead class="thead-light">
				          	<tr>
					            <th nowrap="nowrap" style="color:red;">Transaction No.</th>
					            <th nowrap="nowrap">Item Code</th>
					            <th nowrap="nowrap">Description</th>
					            <th nowrap="nowrap">Packaging</th>
					            <th nowrap="nowrap">Request Qty</th>
				          	</tr>
		            	</thead>
			            <tbody id="tbody-transfer-verify-items-recs">
			              	<?php 
			              		if($rlist != ""):
			              			$nn = 1;
									  foreach($rlist as $row): 
			              	?>
							<tr>
							<td nowrap="nowrap"><?=$row['rmap_trxno']?></td>
							<td nowrap="nowrap"><?=$row['item_code']?></td>
							<td nowrap="nowrap"><?=$row['ART_DESC']?></td>
							<td nowrap="nowrap"><?=$row['ART_SKU']?></td>
							<td nowrap="nowrap"><?=$row['item_qty']?></td>
	
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
	</div>
</div>

<script type="text/javascript">

 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });
	$('#tbl-transfer-verify-items-recs').DataTable({
		           
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

   $('#tbl-transfer-verify-items-recs_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });


</script>