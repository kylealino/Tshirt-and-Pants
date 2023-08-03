<?php 
/**
 *  File        : view/trx/po/po_wf_rcpts.php
 *  Author      : Arnel L. Oquien
 *  Date Created: Dec 10, 2020
 *  last update : Dec 10, 20
 *  description : RFP/PCF Trx Work Flow
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
$cuser      = $this->mylibz->mysys_user();
$mpw_tkn    = $this->mylibz->mpw_tkn();
$mtkn_trxno  = trim($this->input->get_post('mtkn_trxno')); // 
$mtkn_IN     = trim($this->input->get_post('mtkn_IN')); // 
$aPL_PayMode = $this->mydataz->lk_Payment_Method($this->db_erp);
$reasons   = $this->mydataz->lk_disapprovalReason($this->db_erp);
$__modPay = '';
$m_remarks = '';
//var_dump($resons);
$str = "
SELECT 
  aa.*
FROM {$this->db_erp}.`trx_promoDamage_hd` aa 
JOIN {$this->db_erp}.`mst_companyBranch` bb on(aa.`branchID`= bb.`recid`)
WHERE sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) = '$mtkn_trxno' ";

$qry = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
if($qry->num_rows() == 0){
	echo "
	<div class=\" alert alert-danger\">
	<strong><i class=\"fas fa-exclamation-triangle\"> </i> Info</strong> 
	<p>Record not found!</p>
	</div>
	";
	die();
}
	$row = $qry->row_array();
	$aprtrx_no     = $row['trx_no'];
	$remarks    = $row['rsnDsApprvd'];
	$m_approver = $row['m_approver'];
	$trnstats   = $row['pd_stats'];
	$dteApprvd  = $row['dteApprvd'];
	$m_remarks   = $row['m_remarks'];
	$m_dateviewed = $row['m_dateviewed'];

	//update dateviewed
	if($m_dateviewed == ''){
		$str = "UPDATE {$this->db_erp}.`trx_promoDamage_hd` SET `m_dateviewed`= NOW() WHERE `trx_no` = '{$aprtrx_no}' ";
		$this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
	}


?>

<div class="p-3  rounded">

	<div class=" mAV-CFRM-mesg mb-1" ></div>
	<div class="form-group mb-3">
		<div class="col-md-12">
			<strong>Promo Trx No.</strong>
		</div>
		<div class="col-md-12">
			<input type="text" id="aprpromotrxno" data-mtkn="<?=$mtkn_trxno?>" class="form-control form-control-sm" value="<?=$aprtrx_no?>">
		</div>				
	</div>

	<div class="form-group" >
		<?php if($trnstats != ''):?>
		<div class="col-md-12">
			<strong>Approver:</strong>
		</div>
		<div class="col-md-12">
		<?=$m_approver;?>
		</div>
		<br>
		<div class="col-md-12">
			<strong>Date Approved:</strong>
		</div>
		<div class="col-md-12">
		<?=$dteApprvd;?>
		</div>
		<br>
		<?php endif; if($trnstats != 'A'):?>
		<div class="col-md-12">
			<strong>Reasons for disapproval:</strong>
		</div>
		<?php endif; if($trnstats == 'D'):?>
		<div class="col-md-12 mb-2" 	>
		<?=$remarks;?>
		</div>
		<?php endif;?>
		<?php if($trnstats == ''):?>
		<div class="col-md-12">
		<?php
			$this->memelibsys->mychecklist2($reasons);
		?>
		</div>	
	<?php endif;?>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<strong>Remarks:</strong>
		</div>
		<div class="col-md-12">
			<textarea class="form-control form-control-sm" id="mtxt-remk" rows="5"> <?=$m_remarks?></textarea>
		</div>				
	</div>
	<?php if($trnstats == ''):?>
	<div class="d-flex mt-2 mb-2 justify-content-center ">
			<button onclick="approval_btn_click('A')" class="flex-fill btn btn-primary btn-sm" data-id="<?=$mtkn_trxno?>" value="<?=$aprtrx_no?>" ><i class="fa fa-check-circle"></i> Approved
			<button onclick="approval_btn_click('D')" class="flex-fill btn btn-danger btn-sm" data-id="<?=$mtkn_trxno?>" value="<?=$aprtrx_no?>" ><i class="fa fa-times-circle"></i> Disapproved</button>
</div>

	<?php endif;?>


<script type="text/javascript"> 
function approval_btn_click(tag){
	try { 
		var mtkn_IN    = this.value;
		var me_remarks = '';
		var mktn_hdrid   = $('#aprpromotrxno').data('mtkn');
		var promotrxno   = $('#aprpromotrxno').val();
		var _remks       = $('#mtxt-remk').val();
		
		$("input:checkbox[name=reasons]:checked").each(function(){
			me_remarks += $(this).val() +'<br>';
		});

		if(me_remarks != '' && tag == 'A'){
			alert('Please uncheck reason of disapproval to proceed.');
			$.hideLoading();
		  	return false;
		}

		if(me_remarks == '' && tag == 'D' ){
			alert('Please select reason of disapproval to proceed.');
			$.hideLoading();
	  		return false;
		}

		var mparam = { 
			mktn_hdrid:mktn_hdrid,
			promotrxno:promotrxno,
			me_status: tag,
			me_remarks:me_remarks,
			_remks:_remks
		};
		

		$.showLoading({name: 'line-pulse', allowHide: false });
	  	jQuery.ajax({ // default declaration of ajax parameters
	  		type: 'POST',
	  		url: '<?=site_url();?>PromoDamage/promodmg_approval',
	  		context: document.body,
	  		data: eval(mparam),
	  		global: false,
	  		cache: false,
	  	success: function(data) { //display html using divID 
	  		$('.mAV-CFRM-mesg').html(data);
	  		$.hideLoading();
	  	//alert(data);
	  	return false;
	  },
	  error: function() { // display global error on the menu function
	  	alert('error loading page...');
	  	return false;
	  } 
	});	 
	  } catch(err) { 
	  	var mtxt = 'There was an error on this page.\\n';
	  	mtxt += 'Error description: ' + err.message;
	  	mtxt += '\\nClick OK to continue.';
	  	alert(mtxt);
	  	return false;
	}  //end try 
}

</script>