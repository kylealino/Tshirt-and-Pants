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
					            <th nowrap="nowrap" data-orderable="false">BOX CONTENT</th>
					            <th nowrap="nowrap">STOCK CODE</th>
					            <th nowrap="nowrap">ITEM CODE</th>
					            <th nowrap="nowrap">ITEM DESC</th>
					            <th nowrap="nowrap">PACKAGING</th>
					            <th nowrap="nowrap">QTY</th>
					            <th nowrap="nowrap">CONVF</th>
							  <th nowrap="nowrap">TOTAL PCS</th>
							  <th nowrap="nowrap">UNIT PRICE</th>
							  <th nowrap="nowrap">TOTAL AMT</th>
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
				               $barcodes_arr[]  = $row['barcde'];
					       
			              	?>
			              	<tr bgcolor="<?= $bgcolor ?>" <?=$on_mouse?> >
			              		<td nowrap="nowrap">
		              			<?php if($row['is_out'] == 0): ?>
		              			<button class="btn btn-dgreen btn-sm" onclick="getBoxcontent('<?=$txt_mtknr;?>')"> <i class="bi bi-box-seam"></i> View</button>	
			              		<?php else: ?>
			              			-
			              		<?php endif; ?>
			              		</td>
			        
			              		<td nowrap="nowrap"><?=$row['stock_code']?></td>
			              		<td nowrap="nowrap"><?=$row['ART_CODE']?></td>
			              		<td nowrap="nowrap"><?=$row['ART_DESC']?></td>
			              		<td nowrap="nowrap">BOX</td>
			              		<td nowrap="nowrap"><?=$row['qty']?></td>
							<td nowrap="nowrap"><?=$row['convf']?></td>
							<td nowrap="nowrap"><?=$row['total_pcs_scanned']?></td>
							<td nowrap="nowrap"><?=$row['uprice']?></td>
							<td nowrap="nowrap"><?=$row['tamt_scanned']?></td>
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