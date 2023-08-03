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

<div class="box box-primary">
	<div class="box-body">
		<div class="row pt-3">
			<div class="col-md-12">
				<div class="table-responsive">
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-transfer-verify-items-recs">
		            	<thead class="thead-light">
				          	<tr>
							  		<th class="text-center">
									</th>
									<th>Item Code</th>
									<th>Fabric</th>
									<th>Lining</th>
									<th>Button</th>
									<th>Rivets</th>
									<th>Leather Patch</th>
									<th>Plastic Button</th>
									<th>Inside Garter</th>
									<th>Hang Tag</th>
									<th>Zipper</th>
									<th>Size Label</th>
									<th>Size Care Label</th>
									<th>Side Label</th>
									<th>Kids Label</th>
									<th>Kids Side Label</th>
									<th>Plastic Bag</th>
									<th>Barcode</th>
									<th>Fitting Sticker</th>
									<th>Tag Pin</th>
									<th>Chip Board</th>
				          	</tr>
		            	</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									$art_code = $row['ART_CODE'];
								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td class="text-center" nowrap>
										<?=anchor('me-item-comp-vw/?ART_CODE=' . $art_code , '<i class="bi bi bi-eye"></i> Edit ',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm"');?>
									</td>
									<td nowrap><?=$row['ART_CODE'];?></td>
									<td nowrap><?=$row['fabric_code'];?></td>
									<td nowrap><?=$row['lining_code'];?></td>
									<td nowrap><?=$row['btn_code'];?></td>
									<td nowrap><?=$row['rivets_code'];?></td>
									<td nowrap><?=$row['leather_patch_code'];?></td>
									<td nowrap><?=$row['plastic_btn_code'];?></td>
									<td nowrap><?=$row['inside_garter_code'];?></td>
									<td nowrap><?=$row['hang_tag_code'];?></td>
									<td nowrap><?=$row['zipper_code'];?></td>
									<td nowrap><?=$row['size_lbl_code'];?></td>
									<td nowrap><?=$row['size_care_lbl_code'];?></td>
									<td nowrap><?=$row['side_lbl_code'];?></td>
									<td nowrap><?=$row['kids_lbl_code'];?></td>
									<td nowrap><?=$row['kids_side_lbl_code'];?></td>
									<td nowrap><?=$row['plastic_bag_code'];?></td>
									<td nowrap><?=$row['barcode_code'];?></td>
									<td nowrap><?=$row['fitting_sticker_code'];?></td>
									<td nowrap><?=$row['tag_pin_code'];?></td>
									<td nowrap><?=$row['chip_board_code'];?></td>
									
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
	        	<hr>
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

});

</script>

