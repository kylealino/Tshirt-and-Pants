<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C')<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$cuser     = $this->mylibz->mysys_user();
$cuser_fullname     = $this->mylibz->mysys_user_fullname();
$mpw_tkn   = $this->mylibz->mpw_tkn();

$mtkn_potr = $this->input->get_post('mtkn_potr');


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`po_cls_id`,
	aa.`po_vend_import_id`,
	aa.`po_stat_id`,
	aa.`ref_pr_no`,
	aa.`rev_no`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`vend_rid`,
	aa.`vend_add`,
	aa.`vend_cont_pers`,
	aa.`vend_cp_desig`,
	aa.`vend_cp_contno`,
	aa.`vends_rid`,
	aa.`vends_add`,
	aa.`vends_cont_pers`,
	aa.`vends_cp_desig`,
	aa.`vends_cp_contno`,
	aa.`rcvd_date`,
	aa.`tqty`,
	aa.`tamt`,
	aa.`terms`,
	aa.`disc_amt`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`netamt`,
	aa.`tdisc`,
	aa.`print_flag`,
	aa.`is_bcodegen`,
	aa.`hvat`,
	aa.`nvatamt`,
	aa.`hddisc`,
	aa.`hddisc_amt`,
	aa.`prno`,
	aa.`is_cancel`,
	aa.`hcurrency`,
	aa.`hd_ndate`,
	aa.`hd_ndays`,
	aa.`plnt_id`,
	aa.`wshe_id`,
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    bb.`VEND_NAME` __vend_name,
    cc.`CUST_NAME` __vends_name,
  	CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ee.`import_code`,
    ff.`myuserfulln`,
    sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    FROM (((((({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.`mst_vendor` bb 
    ON (aa.`vend_rid` = bb.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_customer` cc 
    ON (aa.`vends_rid` = cc.`recid`)) 
    LEFT JOIN 
    {$this->db_erp}.`mst_po_class` dd 
    ON (aa.`po_cls_id` = dd.`recid`))
    LEFT JOIN 
    {$this->db_erp}.mst_import_vendor ee 
    ON(aa.`po_vend_import_id`=ee.`recid`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.trx_agpo_hd_print gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->num_rows() > 0){
	$r             = $q->row();
	$valid_id      = $r->__ppo_id;
	$encd_fullname = $r->myuserfulln;
	$PPO_CTRLNO    = $r->agpo_sysctrlno;
	$PO_CTRLNO     = $r->po_sysctrlno;
	$rmks          = $r->rmks;
	$print_flag    = $r->print_flag;
	$asstd_tag     = '';
	$trxpos = $r->__po;
	$pl_no = $r->dr_list;
}
else{
	redirect('mypoprint/po_print');
}

$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	SELECT 
		a.`po_sysctrlno`,
		'PO',
	    '1',
	    '{$cuser}',
	    now(),
	    'PO_HD'
	FROM {$this->db_erp}.`trx_agpo_hd_print` a
	WHERE a.`agpo_sysctrlno` = '$PPO_CTRLNO'
	
";

$q = $this->mylibz->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = 'tungaw';
$approved_fullname2 = 'tungaw';
$approved_fullname = 'nganga';

$str = "
	
			SELECT
			dt.`recid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			0.00 price,
			00.00 _qty,
			00.00 _convf,
			0 convf,
			'SA' ART_SKU,
			 'N' asstd_tag,
			grp.`wshe_grp`,
			SUM(sdt.`itmQTY`)total
			FROM trx_agpo_hd_print aa
			JOIN trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN mst_article art ON dt.`art_rid` = art.`recid`
			JOIN mst_wshe_grp grp ON dt.`po_wshe_grp_id` = grp.`recid`
			JOIN (
			SELECT SUM(qty) itmQTY,po_sysctrlno,art_rid FROM  trx_po_dt 
			WHERE po_sysctrlno IN($trxpos) GROUP BY art_rid
			) sdt
			ON aa.`po_sysctrlno` = sdt.`po_sysctrlno` and dt.`art_rid` = sdt.`art_rid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`,grp.`recid`"
; 

$q3 = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r->agpo_sysctrlno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
$pdf->Image(site_url().'public/assets/images/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r->agpo_sysctrlno,1,0,'C'); 
$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION DETAILS',1,0,'C'); 

$pdf->SetXY(5,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'PL NO:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5, $pl_no,1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'DATE:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,$this->mylibz->mydate_mmddyyyy($r->trx_date),1,0,'L');  


$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',7);
$pdf->Cell(25,5,'',1,0,'L');  



$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(55,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(85,30);
$pdf->Cell(46,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(40,20,' ',1,0,'C'); 


$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  

$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,55); 
$pdf->Cell(10,8,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(25,8,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
$pdf->SetXY(125,59); 
$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
$pdf->SetXY(185,55); 
$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r->agpo_sysctrlno,0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$prev_item = '';
foreach($q3->result_array() as $row){

	$po_dt_id  = $row['recid'];
	$qty       = $row['_qty'];
	$convf     = $row['_convf'];
	$xconvf    = $row['convf'];
	$total = $row['total'];
	$price     = $row['price'];
	$po_dt_id  = $row['recid'];
	$expected =  $row['expected'];
	$area = $row['wshe_grp'];
	$store_branch = $this->memelibsys->mefirtsubstring('-',$row['wshe_grp']);

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;

	if($prev_item  != $ART_CODE ){
		$prev_item = $ART_CODE;
		$ART_CODE = $ART_CODE;
		$ART_DESC = $row['ART_DESC'];
		$me_border = 'T,R';
		$total = $row['total'];
	}
	else{
		$me_border = 'R';
		$area = '';
		$ART_CODE = '';
			$ART_DESC = '';
			$total = '0';
	
	}

	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`pohd_rid`    = '{$row['pohd_rid']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			 GROUP BY a.`imat_rid`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			WHERE
			a.`po_dt_id` = {$row['recid']}
			ORDER by
			a.`po_dt_id`
		";

		}

	$q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->num_rows() > 0){
		$_convf = 0;

		foreach($q->result_array() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];



			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	// echo $po_dt_id.'--------| '.number_format($qty).'------| '.$price.'--| '.$convf.'<br>';
	// echo 'MAT CODE | QTY <br>';
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;
	
	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,$yaxis,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,$yaxis,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,$me_border,0,'L'); 
				$pdf->Cell(25,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,($total > 0 )?number_format($total,0):'',$me_border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;
			
		}
		else{

			
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Arial','B',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->agpo_sysctrlno,0,0,'C');



			if($item_no == count($item)){
				$border = 'B,R,L';
			}
			elseif($item_no < count($item)){
				$border = 'L,R';
			}
			elseif($item_no == 1){
				$border = 'T,R,L';
			}
			else{
				$border = 'B';
			}

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(90,5,'Allocated By',0,0,'C'); 
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(90,20,' ',1,0,'L'); 

	$pdf->Cell(50,5,'Acknowledge by',0,0,'C'); 
	$pdf->SetXY(95,$Y);  
	$pdf->Cell(50,20,' ',1,0,'L'); 

	$pdf->Cell(66,5,'Reviewed by',0,0,'C'); 
	$pdf->SetXY(145,$Y);  
	$pdf->Cell(66,20,' ',1,0,'L'); 

	$pdf->SetFont('Arial','',8);


	$Y = $Y + 10;

	$pdf->SetXY(12,$Y);  
	$pdf->Cell(75,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
; 
	$pdf->SetXY(5,$Y+5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(90,5,'Cross Dock Allocation Personnel Involve',0,0,'C'); 

	$pdf->SetXY(100,$Y);  
	$pdf->Cell(40,5,'','B',0,'C'); 
	$pdf->SetXY(95,$Y+5);
	$pdf->Cell(50,5,'Cross Dock System Associate',0,0,'C'); 

	$pdf->SetXY(154,$Y);  
	$pdf->Cell(50,5,'','B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,0,'C'); 

	$Y = $Y + 10;

}


$pdf->output('','AGPO-'.$r->agpo_sysctrlno);
