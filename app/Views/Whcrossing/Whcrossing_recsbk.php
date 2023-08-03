<?php 
/**
 *  File        : WhCrossing_recs.php
 *  Author      : Arnel Oquien
 *  Date Created: July. 27, 2022
 */
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$cuser = $this->mylibz->mysys_user();
$mpw_tkn = $this->mylibz->mpw_tkn();
$cusergrp = $this->mylibz->mysys_usergrp();
$data = array();
$mpages = (empty($this->mylibz->oa_nospchar($this->input->post('mpages'))) ? 0 : $this->mylibz->oa_nospchar($this->input->post('mpages')));
$mpages = ($mpages > 0 ? $mpages : 1);
$apages = array();
$mpages = $npage_curr;
$npage_count = $npage_count;
for($aa = 1; $aa <= $npage_count; $aa++) {
	$apages[] = $aa . "xOx" . $aa;
}
$str_style='';
$cuserrema=$this->mylibz->mysys_userrema();
if($cuserrema ==='B'){
    //$this->load->view('template/novo/header_br');
    $str_style=" style=\"display:none;\"";
}
else{
    //$this->load->view('template/novo/header');
    $str_style='';
}
?>
<!-- <div class="row" style="padding: 0px 18px 0px 18px !important;">
	
</div>	 -->

<?=form_open('WhCrossing/WhCrossing_recs','class="form-row align-items-center" id="myfrmsearchrec" ');?>
	<div class="col-lg-4 "> 
	<div class="form-group ">
			<input type="text" class="col form-control form-control-sm" id="mytxtsearchrec" placeholder="Search Transaction/Branch" aria-label="mytxtsearchrec" aria-describedby="basic-addon1">
	</div>

	</div>
	<div class="col-auto">
			<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i></button>
	<?=anchor('WhCrossing', 'Reset',' class="btn btn-success btn-sm" ');?>
	<div class="form-group ">
	</div>


		<!-- <button class="btn btn-success btn-sm"> <i class="fa fa-download"></i> Extract Report</button> -->
		<!-- <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#posting_modal" > <i class="fa fa-paper-plane"></i> Posting</button> -->
	</div>

<?=form_close();?> <!-- end of ./form -->

			<div class="d-flex  justify-content-start">
				<div class="col-auto">
					<?=$this->mylibz->mypagination($npage_curr,$npage_count,'__myredirected_rsearch','');?>
				 </div>	
			</div>
			<div class="table-responsive">
				<div class="col-md-12 col-md-12 col-md-12 p-0">
					<table class="table mb-0 table-striped table-hover table-bordered table-sm text-center">
						<thead>
							<tr>
								<th colspan="2" class="text-center">
									<?=anchor('WhCrossing', '<i class="fa fa-plus"></i>',' class="btn btn-success btn-xs" ');?>
								</th>
								<th>Transaction No</th>
								<th>Branch Code</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Peso Discount</th>
								<th>Percent Discount</th>
								<th>Total Qty</th>
								<th>Total Promo Srp</th>
								<th>Status</th>
								<th>Encoded Date</th>
								<th>Encoded By</th>
								<th><i class="fa fa-cog"></i></th>

								
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									
									$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									$mtkn_trxno = hash('sha384', $row['recid'] . $mpw_tkn);

									$status = $row['pd_stats'];
									$stat_display = "";
									switch($status){
										case '':
											if($cuserrema == 'B'):
												$label = ($row['post_tag'] == 'Y')?'<i class=" fa fa-flag"></i> FOR APPROVAL':'<i class="fa fa-pencil-alt"></i> DRAFT';

												$stat_display = "<button class=\"btn btn-success btn-sm w-100\" value=\"$mtkn_trxno\"> {$label} </button>";
											else:
												$stat_display = "<button class=\"btn_approve_recs btn btn-success btn-sm w-100\" value=\"$mtkn_trxno\"><i class=\" fa fa-flag\"></i> FOR APPROVAL </button>";
											endif;
											break;
											case 'A':
											$stat_display = "<button class=\"btn_approve_recs btn btn-info btn-sm w-100 \" value=\"$mtkn_trxno\"><i class=\" fa fa-check-circle\"></i> APPROVED </button>";
											break;
											case 'D':
											$stat_display = " <button class=\"btn_approve_recs btn btn-danger btn-sm w-100\" value=\"$mtkn_trxno\"><i class=\"fa fa-times-circle\"></i> DISAPPROVED </button> ";
											break;
									}
								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td class="text-center" nowrap="nowrap">
										<?=anchor('WhCrossing/?mtkn_trxno=' . $mtkn_trxno, '<i class="fa fa-edit"></i>',' class="btn btn-primary btn-sm"');?>
									</td>
							
									<td class="text-center" nowrap="nowrap">
										<button class="btn btn-danger btn-sm" type="button" onclick="javascript:__mndt_invent_crecs('<?=$mtkn_trxno;?>');"><i class="fa fa-trash"></i></button>
									</td>
									<td nowrap="nowrap"><?=$row['trx_no'];?></td>
									<td nowrap="nowrap"><?=$row['BRNCH_NAME'];?></td>
									<td nowrap="nowrap"><?=$this->mylibz->mydate_mmddyyyy($row['startDate']);?></td>
									<td nowrap="nowrap"><?=$this->mylibz->mydate_mmddyyyy($row['endDate']);?></td>
									<td nowrap="nowrap" ><?=($row['pesoDiscount'])?"<i class=\"text-success fa fa-check-circle\"></i>":"<i class=\"text-danger fa fa-times-circle\"></i>";?></td>
									<td nowrap="nowrap" ><?=($row['percentDiscount'])?"<i class=\"text-success fa fa-check-circle\"></i>":"<i class=\"text-danger fa fa-times-circle\"></i>";?></td>
									<td nowrap="nowrap"><?=number_format($row['totalQty'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['totalAmt'],2,'.',',');?></td>
									<td  nowrap="nowrap"><?=$stat_display?></td>
									<td nowrap="nowrap"><?=$this->mylibz->mydate_mmddyyyy($row['encd']);?></td>
									<td nowrap="nowrap"><?=$row['muser'];?></td>
									<td>
									<?php if($row['post_tag'] == 'Y'): ?>
									 <button class="btn btn-success"  onclick="proDamage_extract_rpt('<?=$row['trx_no'];?>')"> <i class="fa fa-download"></i> </button>
									<?php endif; ?>
									<button class="btn btn-info" onclick="window.open('<?= site_url() ?>WhCrossing/mydmgpro_print?mtkn_trans_rid=<?=$mtkn_trxno?>')"> <i class="fa fa-print"></i> </button>
									</td>
								</tr>
								<?php 
								$nn++;
								endforeach;
							else:
								?>
								<tr>
									<td colspan="9">No data was found.</td>
								</tr>
							<?php 
							endif; ?>
						</tbody>
					</table>
					<div class="modal fade" id="myMod_po_post" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
				        <div class="modal-dialog">
				            <div class="modal-content">
				                <div class="color-line"></div>
				                <div class="modal-header text-center">
				                    <h4 class="modal-title">Data Posting</h4>
				                    <!--<small class="font-bold">...</small>-->
				                </div>
				                <div class="modal-body" id="myMod_po_post_Bod">
				                </div>
				                <div class="modal-footer">
				                    <button type="button" class="btn btn-default" onclick="javascript:OnModalReload()" data-dismiss="modal">Close</button>
				                </div>
				            </div>
				        </div>
				    </div>
					<!-- Modal  data-keyboard="false" -->
				    <div class="modal fade" id="myMod_pocrecs" tabindex="-1" role="dialog" aria-labelledby="myMod_pocrecs_label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
				        <div class="modal-dialog">
				            <div id="myMod_pocrecs_Bod" class="modal-content">
				            </div>
				            <!-- end modal-content -->
				        </div>
				    </div>
				</div>
			</div>



<script type="text/javascript"> 

	$('.btn_approve_recs').on('click',function(){
	   approval_vw($(this).val());
	   //approval_vw();
	});

	function __mndt_invent_crecs(mtkn_itm) { 

                try { 
                   
                    $.showLoading({name: 'line-pulse', allowHide: false });
                    var mparam = {
                       mtkn_itm: mtkn_itm

                    }; 

                    var lcon = confirm('Record selected will permanently deleted...\nProceed anyway?');
                    if(lcon){ 
	                $.ajax({ // default declaration of ajax parameters
	                    type: "POST",
	                    url: '<?=site_url();?>WhCrossing/del_hdrec',
	                    context: document.body,
	                    data: eval(mparam),
	                    global: false,
	                    cache: false,

	                    success: function(data)  { //display html using divID
	                        $.hideLoading();
	                        jQuery('#myModalSysMsgBod').html(data);
	                    	jQuery('#myModSysMsg').modal('show');
	                        return false;
	                    },
	                    error: function() { // display global error on the menu function
	                        alert('error loading page...');
	                        $.hideLoading();
	                        return false;
	                    }   
	                }); 
            	}
            	$.hideLoading();
            } catch(err) {
                var mtxt = 'There was an error on this page.\n';
                mtxt += 'Error description: ' + err.message;
                mtxt += '\nClick OK to continue.';
                alert(mtxt);
                $.hideLoading();
                return false;
            }  //end try            
        }
	
	function __myredirected_rsearch(mobj){ 
		try { 
			//$('html,body').scrollTop(0);
			$.showLoading({name: 'line-pulse', allowHide: false });
			var txtsearchedrec = $('#mytxtsearchrec').val();
			
			var mparam = {
				txtsearchedrec: txtsearchedrec,
				mpages: mobj 
			};	
			$.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>WhCrossing/WhCrossing_recs',
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,
				success: function(data)  { //display html using divID
						$.hideLoading();
						$('#mymodoutrecs').html(data);
						
						return false;
				},
				error: function() { // display global error on the menu function
					alert('error loading page...');
					$.hideLoading();
					return false;
				}	
			});			
								
		} catch(err) {
			var mtxt = 'There was an error on this page.\n';
			mtxt += 'Error description: ' + err.message;
			mtxt += '\nClick OK to continue.';
			alert(mtxt);
			$.hideLoading();
			return false;

		}  //end try
	}	
	$('#mytxtsearchrec').keypress(function(event) { 
		if(event.which == 13) { 
			event.preventDefault(); 
			__myredirected_rsearch(1);
			
		}
	});
	
	$('#myfrmsearchrec').validate({
		submitHandler: function() { 
		__myredirected_rsearch(1);
		}
	});	
	
	
</script>
