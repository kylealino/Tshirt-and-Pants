<?php 
/**
*  File        : good_receive/gr_main.php
*  Author      : Arnel L. Oquien
*  Date Created: Nov 26,2022
*  last update : Nov 26,2022
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
					<table class="table table-striped table-bordered table-condensed" id="tbl-wf-recs">
						<thead>
							<tr>
								<th><span class="bi bi-gear"></span></th>
								<th><span class="bi bi-gear"></span></th>
								<th>Transaction No</th>
								<th>Company</th>
								<th>Plant</th>
								<th>Warehouse</th>
								<th>Total Actual Qty</th>
								<th>Total Actual Cost</th>
								<th>Total Actual SRP</th>
								<th>GR Date</th>
								<th>User</th>
								<th>Y/N IsApproved</th>
								<th>Remarks</th>
								<th>Encoded Date</th>
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
									$dis = ($row['post_tag'] == 'Y' || $row['df_tag'] == 'D' ? "disabled" : '');


								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td class="text-center" nowrap="nowrap">
										<?=anchor('good-receive/?mtkn_trxno=' . $mtkn_trxno, '<i class="bi bi-pencil-square"></i>',' class="btn btn-primary btn-xs" ');?>
									</td>
									<td class="text-center" nowrap="nowrap">
										<button title="" id="appr_<?=$row['__arid'];?>" class="btn btn-info btn-xs text-white" type="button" onclick="javascript:__sv_appr('<?=$mtkn_trxno;?>','<?=$row['__arid'];?>','<?=$row['wshe_id'];?>','<?=$row['grtrx_no'];?>');" <?=$dis;?>><i class="bi bi-box-arrow-in-up-left"></i></button>
									</td>
									<!-- <td class="text-center" nowrap="nowrap">
										<button class="btn btn-danger btn-xs" type="button" onclick="javascript:__mndt_invent_crecs('<?=$mtkn_trxno;?>');"><i class="fa fa-close"></i></button>
									</td> -->
									<td nowrap="nowrap"><?=$row['grtrx_no'];?></td>
									<td nowrap="nowrap"><?=$row['COMP_NAME'];?></td>
									<td nowrap="nowrap"><?=$row['plnt_code'];?></td>
									<td nowrap="nowrap"><?=$row['wshe_code'];?></td>
									<td nowrap="nowrap"><?=number_format($row['hd_subtqty'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['hd_subtcost'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['hd_subtamt'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=$mylibzsys->mydate_mmddyyyy($row['gr_date']);?></td>
									<td nowrap="nowrap"><?=$row['muser'];?></td>
									<td nowrap="nowrap"><?=$row['is_apprvd'];?></td>
									<td nowrap="nowrap"><?=$row['remk'];?></td>
									<td nowrap="nowrap"><?=$mylibzsys->mydate_mmddyyyy($row['encd_date']);?></td>
								</tr>
								<?php 
								$nn++;
								endforeach;
							else:
								?>
							<?php 
							endif; ?>
						</tbody>
					</table>
					

				    <!-- Modal for Download button -->
				    
				</div>
			</div>
		</div> <!-- end box body -->
		<div class="modal fade" id="myMod_post" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
				        <div class="modal-dialog">
				            <div class="modal-content">
				                <div class="color-line"></div>
				                <div class="modal-header text-center">
				                    <h4 class="modal-title">Data Posting</h4>
				                    <!--<small class="font-bold">...</small>-->
				                </div>
				                <div class="modal-body" id="myMod_post_Bod">
				                </div>
				                <div class="modal-footer">
				                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				                </div>
				            </div>
				        </div>
				    </div>
	</div> <!-- end box --> 
	<script type="text/javascript">
	
			$(document).ready(function(){
				 $.extend(true, $.fn.dataTable.defaults,{
				      language: {
				          search: ""
				      }
				  });

				  var tbl_pl_items = $('#tbl-wf-recs').DataTable({               
				       'order':[],
				       'columnDefs': [{
				           "targets":[0,1],
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

				   $('#tbl-wf-recs_filter.dataTables_filter [type=search]').each(function () {
				        $(this).attr(`placeholder`, `Search...`);
				        $(this).before('<span class="bi bi-search text-dgreen"></span>');
				    });

				});

	function __sv_appr(mtkn_trxno,id_appr,wshe_id,gr_code){
		var ajaxRequest;

			ajaxRequest = jQuery.ajax({
				url: "<?=site_url();?>gr-workflow-aprvd",
				type: "POST",
				data: {
					mtkn_trxno: mtkn_trxno,
					id_appr: id_appr,
					wshe_id:wshe_id,
					gr_code:gr_code
				}
			});

			// Deal with the results of the above ajax call
			ajaxRequest.done(function(response, textStatus, jqXHR) {
				jQuery('#memsgtestent_success_bod').html(response);
				jQuery('#memsgtestent_success').modal('show');
		
				// and do it again
				//setTimeout(get_if_stats, 5000);
			});
	}	


	</script>
