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

$data = array();
$mpages = (empty($mylibzsys->oa_nospchar($request->getVar('mpages'))) ? 0 : $mylibzsys->oa_nospchar($request->getVar('mpages')));
$mpages = ($mpages > 0 ? $mpages : 1);
$apages = array();
$mpages = $npage_curr;
$npage_count = $npage_count;
for($aa = 1; $aa <= $npage_count; $aa++) {
	$apages[] = $aa . "xOx" . $aa;
}

?>



	<div class="box box-primary">
		<div class="box-body">
			<div class="table-responsive">
				<div class="col-md-12 col-md-12 col-md-12">
					<table class="table table-striped table-bordered table-condensed" id="tbl-boxbarcode-recs">
						<thead>
							<tr>
								<th class="text-center">
										<span class="bi bi-gear"> </span>
								</th>
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
								<th>Download</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									
									$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									$mtkn_trxno = hash('sha384', $row['__arid'] . $mpw_tkn); // recid
									$mtkn_trans_rid = hash('sha384', $row['grtrx_no'] . $mpw_tkn); // recid
									$mtkn_wshe = $row['_wshe_id']; //wshe
									$dis = ($row['post_tag'] == 'Y' || $row['df_tag'] == 'D' ? "disabled" : '');


								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td class="text-center" nowrap="nowrap">
										<?=anchor('good-receive/?mtkn_trxno=' . $mtkn_trxno, '<i class="bi bi-pencil-square"></i>',' class="btn btn-primary btn-xs" ');?>
									</td>
									
						
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
									<td>
					                  <button onclick="javascript:__mbtn_gr_bdownload('<?=$row['grtrx_no'];?>','<?=$row['_wshe_id'];?>');" class="btn btn-sm btn-primary"><i class="bi bi-printer"> Download</i></button>
					                </td>
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
	</div> <!-- end box --> 
<?php
  echo $mylibzsys->memsgbox1('memsgtestent_success','<i class="bi bi-check-circle"></i> System Alert','...','bg-psuccess');
?>  
	<script type="text/javascript">
	
		$(document).ready(function(){
			 $.extend(true, $.fn.dataTable.defaults,{
			      language: {
			          search: ""
			      }
			  });

			  var tbl_pl_items = $('#tbl-boxbarcode-recs').DataTable({               
			       'order':[],
			       'columnDefs': [{
			           "targets":[0,13],
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

			   $('#tbl-boxbarcode-recs_filter.dataTables_filter [type=search]').each(function () {
			        $(this).attr(`placeholder`, `Search...`);
			        $(this).before('<span class="bi bi-search text-dgreen"></span>');
			    });

			});

function __mbtn_gr_bdownload(grtrx_no,active_wshe_id) { 
        try {   
            // var txt_ponumb = $('#txt_ponumb').val();
            // var active_wshe_id = $('#active_wshe_id').val();//jQuery('#active_wshe_id').attr("data-id");
            __mysys_apps.mepreloader('mepreloaderme',true);
            var mparam ={
                grtrx_no: grtrx_no,
                active_wshe_id: active_wshe_id

            }
            
            jQuery.ajax({ // default declaration of ajax parameters
                url: '<?=site_url()?>gr-boxbarcode-dl',
                method:"POST",
                context:document.body,
                data: eval(mparam),
                global: false,
                cache: false,
                success: function(data)  { //display html using divID
                    __mysys_apps.mepreloader('mepreloaderme',false);
                    // jQuery('#myModSysMsgBod').html(data);
                    // jQuery('#myModSysMsg').modal('show');
					jQuery('#memsgtestent_success_bod').html(data);
					jQuery('#memsgtestent_success').modal('show');
                    return false;
                },
                error: function() { // display global error on the menu function
                    alert('error loading page...');
                    __mysys_apps.mepreloader('mepreloaderme',false);
                    return false;
                }   
            }); 
        } catch (err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
         __mysys_apps.mepreloader('mepreloaderme',false);
            alert(mtxt);
        } //end try
    }
  
	</script>