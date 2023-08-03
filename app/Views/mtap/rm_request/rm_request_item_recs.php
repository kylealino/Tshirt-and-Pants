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
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-rmap-items">
		            	<thead class="thead-light">
				          	<tr>
					            <th nowrap="nowrap" style="color:red;">Rawmats Code.</th>
					            <th nowrap="nowrap">Description</th>
					            <th nowrap="nowrap">Qty</th>
				          	</tr>
		            	</thead>
			            <tbody id="tbody-transfer-verify-items-recs">
			              	<?php 
			              		if($rlist != ""):
			              			$nn = 1;
									  foreach($rlist as $row): 
			              	?>
							<tr>
								<td nowrap="nowrap"><?=$row['fabric_code']?></td>
								<td nowrap="nowrap"><?=$row['fabric_desc']?></td>
								<td nowrap="nowrap"><?=$row['fabric_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['lining_code']?></td>
								<td nowrap="nowrap"><?=$row['lining_desc']?></td>
								<td nowrap="nowrap"><?=$row['lining_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['btn_code']?></td>
								<td nowrap="nowrap"><?=$row['btn_desc']?></td>
								<td nowrap="nowrap"><?=$row['btn_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['rivets_code']?></td>
								<td nowrap="nowrap"><?=$row['rivets_desc']?></td>
								<td nowrap="nowrap"><?=$row['rivets_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['leather_patch_code']?></td>
								<td nowrap="nowrap"><?=$row['leather_patch_desc']?></td>
								<td nowrap="nowrap"><?=$row['leather_patch_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['inside_garter_code']?></td>
								<td nowrap="nowrap"><?=$row['inside_garter_desc']?></td>
								<td nowrap="nowrap"><?=$row['inside_garter_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['hang_tag_code']?></td>
								<td nowrap="nowrap"><?=$row['hang_tag_desc']?></td>
								<td nowrap="nowrap"><?=$row['hang_tag_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['zipper_code']?></td>
								<td nowrap="nowrap"><?=$row['zipper_desc']?></td>
								<td nowrap="nowrap"><?=$row['zipper_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['size_lbl_code']?></td>
								<td nowrap="nowrap"><?=$row['size_lbl_desc']?></td>
								<td nowrap="nowrap"><?=$row['size_lbl_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['size_care_lbl_code']?></td>
								<td nowrap="nowrap"><?=$row['size_care_lbl_desc']?></td>
								<td nowrap="nowrap"><?=$row['size_care_lbl_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['side_lbl_code']?></td>
								<td nowrap="nowrap"><?=$row['side_lbl_desc']?></td>
								<td nowrap="nowrap"><?=$row['side_lbl_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['kids_lbl_code']?></td>
								<td nowrap="nowrap"><?=$row['kids_lbl_desc']?></td>
								<td nowrap="nowrap"><?=$row['kids_lbl_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['kids_side_lbl_code']?></td>
								<td nowrap="nowrap"><?=$row['kids_side_lbl_desc']?></td>
								<td nowrap="nowrap"><?=$row['kids_side_lbl_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['plastic_bag_code']?></td>
								<td nowrap="nowrap"><?=$row['plastic_bag_desc']?></td>
								<td nowrap="nowrap"><?=$row['plastic_bag_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['barcode_code']?></td>
								<td nowrap="nowrap"><?=$row['barcode_desc']?></td>
								<td nowrap="nowrap"><?=$row['barcode_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['tag_pin_code']?></td>
								<td nowrap="nowrap"><?=$row['tag_pin_desc']?></td>
								<td nowrap="nowrap"><?=$row['tag_pin_qty']?></td>
							<tr>
							<tr>
								<td nowrap="nowrap"><?=$row['chip_board_code']?></td>
								<td nowrap="nowrap"><?=$row['chip_board_desc']?></td>
								<td nowrap="nowrap"><?=$row['chip_board_qty']?></td>
							<tr>
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
	$('#tbl-rmap-items').DataTable({
		           
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

   $('#tbl-rmap-items_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });


</script>