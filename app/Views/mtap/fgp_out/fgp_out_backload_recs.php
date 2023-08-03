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

				<div class="table-responsive">
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-whout-backload-recs">
		            	<thead class="thead-light">
				          	<tr class="text-dgreen">
							    <th nowrap="nowrap"></th>
							    <th nowrap="nowrap">TPA Transaction No.</th>
					            <th nowrap="nowrap">FGP Transaction No.</th>
					            <th nowrap="nowrap">Stock Code</th>
								<th nowrap="nowrap">Barcode</th>
					            <th nowrap="nowrap">Item Code</th>
					            <th nowrap="nowrap">QTY Per/Pack</th>
				          	</tr>
		            	</thead>
			            <tbody id="tbody-inv-items-recs">
			              	<?php 
							
		              		if($result != ""):
		              			$nn = 1;
								$count = 1;
		              		
		              			foreach($result as $row):
									$bgcolor = ($nn % 2) ? "#EAF3F3" : "#FFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
  
										 $txt_mtknr = hash('sha384', $row['recid'] . $mpw_tkn);
									
										$pcolor = "";
					       
			              	?>
			              	<tr bgcolor="<?= $bgcolor ?>" <?=$on_mouse?> >
							    <td nowrap="nowrap"><?=$count;?></td>
							    <td nowrap="nowrap"><?=$row['tpa_trxno']?></td>
			              		<td nowrap="nowrap"><?=$row['fgreq_trxno']?></td>
			              		<td nowrap="nowrap"><?=$row['stock_code']?></td>
			              		<td nowrap="nowrap"><?=$row['witb_barcde']?></td>
								<td nowrap="nowrap"><?=$row['mat_code']?></td>
								<td nowrap="nowrap"><?=$row['qty_perpack']?></td>
			              	</tr>
			              	<?php
							$nn++;
							$count++;
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

  var tbl_pl_items = $('#tbl-whout-backload-recs').DataTable({               
       'order':[],
       'columnDefs': [{
           "targets":[0,1,5,6,7],
           "orderable": false
       },
      {
       targets:'_all' ,
       className: 'dt-head-center'
  	 }
       ]
   });
  $('#tbl-whout-backload-recs_filter.dataTables_filter [type=search]').each(function () {
       $(this).attr(`placeholder`, `Search...`);
       $(this).before('<span class="bi bi-search text-dgreen"></span>');
   });


});

</script>