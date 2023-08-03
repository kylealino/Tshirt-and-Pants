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
	<h5 class="text-end text-dgreen"> <span class="fw-bold"> GR CODE : <?=$grno?> </span> </h5>
</div>
<div class="box box-primary">
	<div class="box-body">
		<div class="row pt-3">
			<div class="col-md-12">
				<div class="table-responsive">
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-transfer-verify-items-recs">
		            	<thead class="thead-light">
				          	<tr>
					            <th nowrap="nowrap" style="color:red;">QTY SCANNED</th>
					            <th nowrap="nowrap">QTY</th>
					            <th nowrap="nowrap">VARIANCE</th>
					            <th nowrap="nowrap">STOCK CODE</th>
					            <th nowrap="nowrap">ITEM CODE</th>
					            <th nowrap="nowrap">ITEM DESC</th>
					            <th nowrap="nowrap">PACKAGING</th>
					            <th nowrap="nowrap">CONVF</th>
					            <th nowrap="nowrap">TOTAL PCS</th>
					            <th nowrap="nowrap">TOTAL AMT</th>
					            <th nowrap="nowrap">BARCODE</th>
					            <!-- <th nowrap="nowrap"><i class="fa fa-cog"></i></th> -->
				          	</tr>
		            	</thead>
			            <tbody id="tbody-transfer-verify-items-recs">
			              	<?php 
			              		if($rlist != ""):
			              			$nn = 1;
			              			$total_scanned = 0;
			              			foreach($rlist as $row):
					            
					                  	$total_scanned += $row['qty_scanned'];
					                  	$pcolor = "";
					                  	if($row['variance'] == "N/A"){
					                  		$pcolor = "style=\"color:red;\"";
					                  	}
			              	?>
							<tr>
							<td nowrap="nowrap"><?=$row['qty_scanned']?></td>
							<td nowrap="nowrap"><?=$row['qty']?></td>
							<td nowrap="nowrap" <?=$pcolor?>><?=$row['variance']?></td>
							<td nowrap="nowrap"><?=$row['stock_code']?></td>
							<td nowrap="nowrap"><?=$row['ART_CODE']?></td>
							<td nowrap="nowrap"><?=$row['ART_DESC']?></td>
							<td nowrap="nowrap">BOX</td>
							<td nowrap="nowrap"><?=$row['convf']?></td>
							<td nowrap="nowrap"><?=$row['total_pcs_scanned']?></td>
							<td nowrap="nowrap"><?=$row['tamt_scanned']?></td>
							<td nowrap="nowrap"><?=$row['barcde']?></td>
							<!-- <td nowrap="nowrap"></td> -->
							</tr>
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