<?php 
/**
*  File        : good_receive/gr_main.php
*  Author      : Arnel L. Oquien
*  Date Created: Nov 22,2022
*  last update : Nov 22,2022
*  description : Good receive entry crossdocking
*/

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$mywhout = model('App\Models\MyWarehouseoutModel');
$db_erp =$mydbname->medb(1);

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$cusergrp = $mylibzdb->mysys_usergrp();


?>
	<div class="box box-primary">
		<div class="box-body">
			<div class="table-responsive">
				<div class="col-md-12 col-md-12 col-md-12">
					<table class="table table-striped table-bordered table-condensed" id="tbl-logfile-recs">
						<thead>
							<tr>
								<th>Allocation Guide Trx. No</th>
								<th>STOCK_CODE</th>
								<th>BOX_ITEM_CODE</th>
								<th>BOX_ITEM_DESC</th>
								<th>PACKAGING</th>
								<th>QTY</th>
								<th>CONVF</th>
								<th>TOTAL_PCS</th>
								<th>TOTAL_AMOUNT</th>
								<th>BARCODE</th>
								<th>PLANT</th>
								<th>WAREHOUSE</th>
								<th>RACK</th>
								<th>BIN</th>
								<th>STEXT</th>
								<th>DATE ENCODED</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									
									$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td nowrap="nowrap"><?=$row['agpo_sysctrlno'];?></td>
									<td nowrap="nowrap"><?=$row['stock_code'];?></td>
									<td nowrap="nowrap"><?=$row['ART_CODE'];?></td>
									<td nowrap="nowrap"><?=$row['ART_DESC'];?></td>
									<td nowrap="nowrap"><?=$row['ART_SKU'];?></td>
                                    <td nowrap="nowrap"><?=$row['qty'];?></td>
									<td nowrap="nowrap"><?=$row['convf'];?></td>
									<td nowrap="nowrap"><?=$row['total_pcs'];?></td>
									<td nowrap="nowrap"><?=$row['po_tamt'];?></td>
									<td nowrap="nowrap"><?=$row['irb_barcde'];?></td>
                                    <td nowrap="nowrap"><?=$row['plnt_code'];?></td>
									<td nowrap="nowrap"><?=$row['wshe_code'];?></td>
									<td nowrap="nowrap"><?=$row['wshe_grp'];?></td>
									<td nowrap="nowrap"><?=$row['wshe_bin_name'];?></td>
									<td nowrap="nowrap"><?=$row['dr_list'];?></td>
                                    <td nowrap="nowrap"><?=$row['encd'];?></td>

								<?php 
								$nn++;
								endforeach;
							else:
								?>
						
							<?php 
							endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div> <!-- end box body -->
	</div> <!-- end box --> 

	<script type="text/javascript">
		
		$(document).ready(function(){
			 $.extend(true, $.fn.dataTable.defaults,{
			      language: {
			          search: ""
			      }
			  });

			  var tbl_pl_items = $('#tbl-logfile-recs').DataTable({               
			       'order':[],
			       'columnDefs': [{
			           "targets":[0,13,14],
			           "orderable": false
			       },
			       {
			        targets:'_all' ,
			        className: 'dt-head-center'
			   	 }
			       ],
	               'language': {
	       	        "infoEmpty": "No records available - Got it?",
	       	   		}
			   });

			   $('#tbl-logfile-recs_filter.dataTables_filter [type=search]').each(function () {
			        $(this).attr(`placeholder`, `Search...`);
			        $(this).before('<span class="bi bi-search text-dgreen"></span>');
			    });

			});
 
	</script>