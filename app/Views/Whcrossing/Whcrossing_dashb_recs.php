<?php 
/**
 *	File        : masterdata/myprodt-invent-recs.php
 *  Auhtor      : Oliver V. Sta Maria
 *  Date Created: Sept 17, 2017
 * 	last update : Sept 17, 2017
 * 	description : Product Type Inventory Records
 */
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$cuser = $this->mylibz->mysys_user();
$mpw_tkn = $this->mylibz->mpw_tkn();
$cusergrp = $this->mylibz->mysys_usergrp();
$data = array();
$cuserrema=$this->mylibz->mysys_userrema();
$mytxtsearchrec_dmgp = $this->input->get_post('txtsearchedrec_dmgp');
$mtkn = $this->input->get_post('mtkn');

$data = array();
$mpages = (empty($this->mylibz->oa_nospchar($this->input->post('mpages'))) ? 0 : $this->mylibz->oa_nospchar($this->input->post('mpages')));
$mpages = ($mpages > 0 ? $mpages : 1);
$apages = array();
$mpages = $npage_curr;
$npage_count = $npage_count;
for($aa = 1; $aa <= $npage_count; $aa++) {
  $apages[] = $aa . "xOx" . $aa;
}
//PULLOUT REQ at NOT YET DISPATCH wala pa dmgp
$disp = " style=\"display:none;\"";
$dis_tag = " style=\"display:none;\""; 
$disp_name='';
//DISPATCH and DONE PULL OUT may dmgp na
if(($mtkn === 'Y') || ($mtkn === 'F')){
	$disp = "";
	
}
//TH NAME
if($mtkn === 'Y'){ //DISPATCH
	$disp_name="DISPATCH DATE";
}
elseif($mtkn === 'F'){ //DONE
	$disp_name="PULLOUT DONE DATE";
}
//PARA SA BUTTON DONE
if($mtkn === 'Y'){
	$dis_tag = '';
}


if(!empty($mtkn) && ($mtkn === 'I' || $mtkn === 'DR' || $mtkn === 'RE')) {
	$bg_btn ='btn-danger';
}
if(!empty($mtkn) && ($mtkn === 'O' || $mtkn === 'SN')) {
	$bg_btn ='btn-success';
}
if(!empty($mtkn) && ($mtkn === 'E' || $mtkn === 'AP')) {
	
	$bg_btn ='btn-warning';
}
if(!empty($mtkn) && ($mtkn === 'C' || $mtkn === 'DS')) {
	
	$bg_btn ='bg-violet';
}


?>
<style>
.disabled-link {
  pointer-events: none;
}
</style>
	<div class="box box-primary">
		<div class="box-body">
			<?=form_open('PromoDamage/myacct_vw_dashdmgp','class="" id="myfrmsearchrec_dmgp" ');?>
			<div class="row gy-2 mb-2">
				<div class="col-lg-4 col-md-12 col-sm-12">
					<div class="input-group input-group-sm mb-3">
						<span class="input-group-text" id="basic-addon1">Search</span>
						<input type="text" class="form-control" id="mytxtsearchrec_dmgp" placeholder="Search Transaction/Branch" aria-label="mytxtsearchrec_dmgp" aria-describedby="basic-addon1">
					</div>
				</div>
				<div class="col-lg-3 col-md-12 col-sm-12">
					<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i></button>
					<span id="__mtoexport_dmgp"><a href="JavaScript:void(0);" class="btn <?=$bg_btn?> btn-sm" id="lnkexportmsexcel_dmgp">
					<i class="fa fa-download"></i>
		</a>
		</span>
				</div>
			</div>
			<?=form_close();?> <!-- end of ./form -->

			<div class="table-responsive ">
				<div class="col-lg-12">
					<table class="table mb-0 table-striped table-hover table-bordered table-sm text-center">
						<thead>
							<tr>

								<th colspan="16" class="text-center">
								<?=$thead;?>
								</th>
							</tr>
						</thead>
						<thead>
							<tr>
								<th colspan="1" class="text-center">
									<i class="fa fa-cog">  </i>
								</th>
								<th>Pullout Transaction No</th>
								<th>Branch Code</th>
								<th>Branch Name</th>
						<!-- 		<th>Is Posted</th> -->
								<th>Start Date</th>
								<th>End Date</th>
								<th>Status</th>
								<!-- <th>Remarks</th> -->
								<th>Encoded User</th>								
								<th>Encoded Date</th>
								<th>Is Cancelled</th>
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
									$dis = ($row['PD_STAT'] == 'Y' ? "disabled" : '');
									$dis_recreate = (($row['PD_STAT'] == 'Y') ? "disabled-link" : '');
									$mtkn_trxno_dmg = hash('sha384', $row['PD_TRX_NO'] . $mpw_tkn);

									$status = $row['pd_stats'];
									$stat_display = "";
									switch($status){
										case '':
											if($cuserrema == 'B'):
												$label = ($row['PD_ISPOSTED'] == 'Y')?'<i class=" fa fa-flag"></i> FOR APPROVAL':'<i class="fa fa-pencil-alt"></i> DRAFT';

												$stat_display = "<button class=\"btn btn-success btn-sm w-100\" value=\"$mtkn_trxno\"> {$label} </button>";
											else:
												$stat_display = "<button class=\"btn_approve_dash btn btn-success btn-sm w-100\" value=\"$mtkn_trxno\"><i class=\" fa fa-flag\"></i> FOR APPROVAL </button>";
											endif;
											break;
											case 'A':
											$stat_display = "<button class=\"btn_approve_dash btn btn-info btn-sm w-100 \" value=\"$mtkn_trxno\"><i class=\" fa fa-check-circle\"></i> APPROVED </button>";
											break;
											case 'D':
											$stat_display = " <button class=\"btn_approve_dash btn btn-danger btn-sm w-100\" value=\"$mtkn_trxno\"><i class=\"fa fa-times-circle\"></i> DISAPPROVED </button> ";
											break;
									}

									?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td class="text-center" nowrap="nowrap">

										<?php if( $mtkn == 'RE'):?>
											<?=anchor('PromoDamage/?mtkn_trxno=' . $mtkn_trxno, '<i class="fa fa-edit"></i>',' class="btn btn-warning btn-sm '.$dis_recreate.'"');?>
											<button title="Download" id="done_<?=$row['PD_TRX_NO'];?>" class="btn btn-success btn-xs" type="button" onclick="proDamage_extract_rpt('<?=$row['PD_TRX_NO'];?>','<?=$mtkn?>')" ><i class="fa fa-download"></i></button>
										<?php else: ?>
										<button title="Cancelled" id="canc_<?=$row['PD_TRX_NO'];?>" class="btn btn-danger btn-xs" type="button" onclick="javascript:__mndt_invent_crecs('<?=$mtkn_trxno;?>');" <?=$dis;?>><i class="fa fa-times"></i></button>
										<?=anchor('PromoDamage/?mtkn_trxno=' . $mtkn_trxno, '<i class="fa fa-edit"></i>',' class="btn btn-warning btn-sm '.$dis_recreate.'"');?>

										<?php if($status == 'A' ):?>
										<button title="Download" id="done_<?=$row['PD_TRX_NO'];?>" class="btn btn-success btn-xs" type="button" onclick="proDamage_extract_rpt('<?=$row['PD_TRX_NO'];?>','<?=$mtkn?>')" ><i class="fa fa-download"></i></button>
										<?php endif; ?>

								    <?php if($row['PD_ISPOSTED'] == 'Y'):  ?>
								    	<button   type="button" class="btn btn-sm btn-info dbvw_files_" title = "View attachement file" value="<?=$row['PD_TRX_NO'];?>"><i class="fa fa-file-pdf"></i> </button>
                    <?php endif; ?>
                    <?php endif; ?>

									</td>
									<td nowrap="nowrap"><?=$row['PD_TRX_NO'];?></td>
									<td nowrap="nowrap"><?=$row['PD_BRANCH_CODE'];?></td>
									<td nowrap="nowrap"><?=$row['BRNCH_NAME'];?></td>
								  <!--<td nowrap="nowrap"><?=$row['PD_ISPOSTED'];?></td> -->
									<td nowrap="nowrap"><?=$this->mylibz->mydate_mmddyyyy($row['PD_SDATE']);?></td>
									<td nowrap="nowrap"><?=$this->mylibz->mydate_mmddyyyy($row['PD_EDATE']);?></td>
									<td  nowrap="nowrap"><?=$stat_display?></td>
								  <!--<td nowrap="nowrap"><?=$row['PD_REMKS'];?></td> -->
									<td nowrap="nowrap"><?=$row['PD_MUSER'];?></td>
									<td nowrap="nowrap"><?=$this->mylibz->mydate_mmddyyyy($row['PD_ENCD']);?></td>
									<td nowrap="nowrap"><?=$row['PD_STAT'];?></td>
								</tr>
								<?php 
								$nn++;
								endforeach;
							else:
								?>
								<tr>
									<td colspan="10">No data was found.</td>
								</tr>
							<?php 
							endif; ?>
						</tbody>
					</table>
					<div class="d-flex justify-content-center m-2">
							<?=$this->mylibz->mypagination($npage_curr,$npage_count,'__myredirected_rsearch_dmgp','');?>
					</div>

	
				</div>
			</div>
		</div> <!-- end box body -->
	</div> <!-- end box --> 

	
	</div> <!-- end box --> 
	<script type="text/javascript">

$('.btn_approve_dash').on('click',function(){
	   approval_vw($(this).val());
	   //approval_vw();
	});

$('.dbvw_files_').click(function(){
   viewAttachment($(this).val());
});

    function __myredirected_rsearch_dmgp(mobj) { 
    try { 
      $.showLoading({name: 'line-pulse', allowHide: false });
      var mtkn ='<?=$mtkn;?>';
      var txtsearchedrec_dmgp = $('#mytxtsearchrec_dmgp').val();
     
      var mparam = {
      	mtkn:mtkn,
        txtsearchedrec_dmgp: txtsearchedrec_dmgp,
        fld_dmgpbranch: '<?=$fld_dmgpbranch;?>',
				fld_dmgpbranch_id: '<?=$fld_dmgpbranch_id;?>',
				fld_dmgp_month: '<?=$fld_dmgp_month;?>',
				fld_dmgp_year: '<?=$fld_dmgp_year;?>',
        mpages: mobj 
      };  
      $.ajax({ // default declaration of ajax parameters
      type: "POST",
      url: '<?=site_url()?>PromoDamage/myacct_vw_dashdmgp',
      context: document.body,
      data: eval(mparam),
      global: false,
      cache: false,
        success: function(data)  { //display html using divID
            $.hideLoading();
            $('#mymodoutrecs_dmgp').html(data);
            
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
  
  
  $('#myfrmsearchrec_dmgp').validate({
    submitHandler: function() { 
      try { 
        //$('html,body').scrollTop(0);
        $.showLoading({name: 'line-pulse', allowHide: false });
        var txtsearchedrec_dmgp = $('#mytxtsearchrec_dmgp').val();
        var mtkn ='<?=$mtkn;?>';
        var mparam = {
          mtkn:mtkn,
          txtsearchedrec_dmgp: txtsearchedrec_dmgp,
          mpages: 1 
        };
        
        
        $.ajax({ // default declaration of ajax parameters
        type: "POST",
        url: '<?=site_url();?>PromoDamage/myacct_vw_dashdmgp',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
          success: function(data)  { //display html using divID
              $.hideLoading();
              $('#mymodoutrecs_dmgp').html(data);
             
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
      }  //end try
      return false; 
    }
  });   
  
	</script>