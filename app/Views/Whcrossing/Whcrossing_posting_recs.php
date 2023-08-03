<?php 
/**
 *  File        : promodamage_posting_recs.php
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
$branchName =$this->input->get_post('branchName'); 
$fromdate = $this->input->get_post('fromdate');
$todate = $this->input->get_post('todate');

?>

<style>
	
.form-readonly[readonly]{
	background-color: #f9f9f9;
    opacity: 1;
}

</style>

<?=form_open('PromoDamage/promodamage_recs','class="form-inline pull-left" id="myfrmsearchrec" ');?>
<div class="row gy-2 mb-2">
	<div class="col-lg-3 col-md-12 col-sm-12">
		<div class="input-group input-group-sm mb-3">
			<span class="input-group-text" id="basic-addon1">Branch</span>
			<p> </p>
			<input type="text" class="form-control form-control-sm form-readonly"  value="<?=$branchName?>" aria-label="mytxtsearchrec" aria-describedby="basic-addon1" readonly>
		</div>
	</div>
	<div class="col-lg-2  col-md-12 col-sm-12">
		<div class="input-group input-group-sm mb-3">
			<span class="input-group-text" id="basic-addon1">From</span>
			<input type="text" class="form-control form-control-sm form-readonly"   value="<?=$fromdate?>" readonly>
		</div>
	</div>
	<div class="col-lg-2 col-md-12 col-sm-12">
		<div class="input-group input-group-sm mb-3">
			<span class="input-group-text" id="basic-addon1">To</span>
			<input type="text" class="form-control form-control-sm form-readonly "  value="<?=$todate?>" readonly>
		</div>
	</div>
	<div class="col-lg-3 col-md-12 col-sm-12">
		
		<?=anchor('PromoDamage', 'Reset',' class="btn btn-success btn-sm" ');?>
		<!-- <button class="btn btn-success btn-sm"> <i class="fas fa-download"></i> Extract Report</button> -->
		<button type="button" class="btn btn-sm btn-success" onclick="__myredirected_rsearch_dmgpromo('<?=$mpages?>')" title="Reload"> <i class="fas fa-sync"></i></button>
		<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#posting_modal" > <i class="fas fa-paper-plane"></i> Posting</button>
	</div>
</div>
<?=form_close();?> <!-- end of ./form -->
	<div class="box box-primary">
		<div class="box-body">
			<?=$this->mylibz->mypagination($npage_curr,$npage_count,'__myredirected_rsearch_dmgpromo','');?>
			<div class="table-responsive">
				<div class="col-md-12 col-md-12 col-md-12">
					<table class="table mb-0 table-striped table-hover table-bordered table-sm text-center">
						<thead>
							<tr>
								<th colspan="1" class="text-center">
									Post
								</th>
								<th>Transaction No</th>
								<th>Branch Code</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Peso Discount</th>
								<th>Percent Discount</th>
								<th>Total Qty</th>
								<th>Total Promo Srp</th>
								<th>Encoded Date</th>
								<th>Encoded By</th>
								<th><i class="fas fa-cog"></i></th>
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
								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
								
							
									<td class="text-center" nowrap="nowrap">
										<button class="btn bg-info btn-sm text-white" type="button" onclick="javascript:post_trx('<?=$mtkn_trxno;?>','<?=$row['trx_no']?>');"><i class="fas fa-paper-plane"></i></button>
									</td>
									<td nowrap="nowrap"><?=$row['trx_no'];?></td>
									<td nowrap="nowrap"><?=$row['BRNCH_NAME'];?></td>
									<td nowrap="nowrap"><?=$this->mylibz->mydate_mmddyyyy($row['startDate']);?></td>
									<td nowrap="nowrap"><?=$this->mylibz->mydate_mmddyyyy($row['endDate']);?></td>
									<td nowrap="nowrap" ><?=($row['pesoDiscount'])?"<i class=\"text-success fas fa-check-circle\"></i>":"<i class=\"text-danger fas fa-times-circle\"></i>";?></td>
									<td nowrap="nowrap" ><?=($row['percentDiscount'])?"<i class=\"text-success fas fa-check-circle\"></i>":"<i class=\"text-danger fas fa-times-circle\"></i>";?></td>
									<td nowrap="nowrap"><?=number_format($row['totalQty'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=number_format($row['totalAmt'],2,'.',',');?></td>
									<td nowrap="nowrap"><?=$this->mylibz->mydate_mmddyyyy($row['encd']);?></td>
									<td nowrap="nowrap"><?=$row['muser'];?></td>
									<td> 
									<?php if($row['post_tag'] == 'Y'): ?>
									 <button class="btn btn-success"  onclick="proDamage_extract_rpt('<?=$row['trx_no'];?>')"> <i class="fas fa-download"></i> </button>
									<?php endif; ?>
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
				</div>
			</div>
		</div> <!-- end box body -->
	</div> <!-- end box --> 


<script type="text/javascript"> 


	
</script>
