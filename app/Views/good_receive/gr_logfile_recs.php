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
								<th>No</th>
								<th>Transaction No</th>
								<th>Pullout Trx No</th>
								<th>Company</th>
								<th>Item Code</th>
								<th>Item Description</th>
								<th>Unit Cost</th>
								<th>Total Unit Cost</th>
								<th>Unit Price</th>
								<th>Total Unit Price</th>
								<th>Actual Qty</th>
								<th>Total Actual Qty</th>
								<th>Total Actual Cost</th>
								<th>Total Actual SRP</th>
								<th>Branch</th>
								<th>GR Date</th>
								<th>User</th>
								<th>Remarks</th>
								<th>Type</th>
								<th>Assorted Items</th>
								<th>Item Qty Pcs (Pullout)</th>
								<th>Item Qty Pcs (Goods)</th>
								<th>Item Convf (Goods)</th>
								<th>Item Qty Pcs (Damage)</th>
								<th>Item Qty Pcs (Lacking)</th>
								<th>Approval (Y/N)</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									
									$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									$mtkn_trxno = hash('sha384', $row['__arid'] . $mpw_tkn);
								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td nowrap="nowrap"><?=$nn;?></td>
									<td nowrap="nowrap"><?=$row['__hdgrtrx'];?></td>
									<td nowrap="nowrap"><?=$row['__refno'];?></td>
									<td nowrap="nowrap"><?=$row['COMP_NAME'];?></td>
									<td nowrap="nowrap"><?=$row['ART_CODE'];?></td>
									<td nowrap="nowrap"><?=$row['ART_DESC'];?></td>
									<td nowrap="nowrap"><?=number_format($row['ucost'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['tcost'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['uprice'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['tamt'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['qty'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['hd_subtqty'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['hd_subtcost'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['hd_subtamt'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=$row['BRNCH_NAME'];?></td>
									<td nowrap="nowrap"><?=$mylibzsys->mydate_mmddyyyy($row['gr_date']);?></td>
									<td nowrap="nowrap"><?=$row['muser'];?></td>
									<td nowrap="nowrap"><?=$row['remk'];?></td>
									<td nowrap="nowrap"><?=$row['grtype_desc'];?></td>
									<td nowrap="nowrap"><?=$row['imat_code'];?></td>
									<td nowrap="nowrap"><?=$row['amat_convf'];?></td>
									<td nowrap="nowrap"><?=$row['imat_qty'];?></td>
									<td nowrap="nowrap"><?=$row['imat_convf'];?></td>
									<td nowrap="nowrap"><?=$row['amat_dmg'];?></td>
									<td nowrap="nowrap"><?=$row['amat_lck'];?></td>
									<td nowrap="nowrap"><?=$row['is_apprvd'];?></td>
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