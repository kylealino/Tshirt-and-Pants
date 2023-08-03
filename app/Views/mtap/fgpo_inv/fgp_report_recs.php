<?php 

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
					<table class="table table-striped table-bordered text-center table-condensed" id="tbl-logfile-recs">
						<thead>
							<tr>
                                <th nowrap="nowrap">ITEM CODE</th>
                                <th nowrap="nowrap">ITEM DESC</th>
                                <th nowrap="nowrap">UOM</th>
                                <th nowrap="nowrap">FINISH GOOD P.O</th>
                                <th nowrap="nowrap">RAW MATERIAL PRODUCTION</th>
                                <th nowrap="nowrap">INBOUND</th>
                                <th nowrap="nowrap">DEMAND</th>
                                <th nowrap="nowrap">FOR PACKING</th>
                                <th nowrap="nowrap">OUTBOUND</th>
                                <th nowrap="nowrap">BALANCE</th>
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
                                    <td nowrap><?=$row['ART_CODE'];?></td>
                                    <td nowrap><?=$row['ART_DESC'];?></td>
                                    <td nowrap><?=$row['ART_UOM'];?></td>
                                    <td nowrap><?=$row['balance_qty'];?></td>     
                                    <td nowrap><?=$row['prod_qty'];?></td>    
                                    <td nowrap><?=$row['po_rcv_qty'];?></td>
                                    <td nowrap><?=$row['req_qty'];?></td>
                                    <td nowrap><?=$row['delivered_qty'];?></td> 
                                    <td nowrap><?=$row['balance_qty'];?></td> 
                                    <td nowrap><?=$row['po_qty'];?></td> 
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
			           "targets":[0],
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