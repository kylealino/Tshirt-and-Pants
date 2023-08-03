<?php 
use App\Libraries\Fpdf\Mypdf;

$request      = \Config\Services::request();
$reponse      = \Config\Services::reponse();
$mydbname     = model('App\Models\MyDBNamesModel');
$mylibzdb     = model('App\Models\MyLibzDBModel');
$mylibzsys    = model('App\Models\MyLibzSysModel');
$memelibsys   = model('App\Models\Mymelibsys_model');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz      = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(1);

$cuser          = $mylibzdb->mysys_user();
//$cuser_fullname = $mylibzdb->mysys_user_fullname();
$mpw_tkn        = $mylibzdb->mpw_tkn();
$mtkn_potr      = $request->getVar('mtkn_potr');
$txt_wshetkn      = $request->getVar('txt_wshetkn');

//get warehouse data
$whseData = $memelibsys->getCDPlantWarehouse_data_bytkn($txt_wshetkn);
$plantID  = $whseData['plntID'];
$whseID   = $whseData['whID'];
$wshe_code = $whseData['wshe_code'];
//end

$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2',
		b.`is_print` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`rmks`,
	aa.`posted_flg`,
	aa.`muser`,
	aa.`encd_date`,
	aa.`done`,
	aa.`is_approved`,
	aa.`print_flag`, 
	aa.`dr_list`,
	aa.`ppo_print`,
	aa.`asstd_tag`,
    gg.`recid` __ppo_id,
    gg.`agpo_sysctrlno` agpo_sysctrlno,
    GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
    GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po,
    ff.`myuserfulln`
    FROM (({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(aa.`muser`=ff.`myusername`))
    LEFT JOIN {$this->db_erp}.`trx_agpo_hd_print` gg 
    ON(aa.`recid`=gg.`po_id`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
    AND aa.`plnt_id` = '{$plantID}' AND aa.`wshe_id` = '{$whseID}'
    GROUP BY gg.`agpo_sysctrlno`
";

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->getNumRows() > 0){
	$r             = $q->getRowArray();
	$valid_id      = $r['__ppo_id'];
	$encd_fullname = $r['myuserfulln'];
	$PPO_CTRLNO    = $r['agpo_sysctrlno'];
	$PO_CTRLNO     = $r['po_sysctrlno'];
	$rmks          = $r['rmks'];
	$print_flag    = $r['print_flag'];
	$asstd_tag     = '';
	$po_ref = $r['__poref'];
	$trxpos = $r['__po'];
	$pl_no = $r['dr_list'];

}
else{
	redirect('whcrossing');
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

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = '';
$approved_fullname2 = '';
$approved_fullname = '';

$str = "
		SELECT
		dt.`recid`,
		dt.`art_rid`,
		dt.`recid` po_dt_id, 
		art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
		aa.`recid` __ppo_id,
		dt.`qty` expected,
		'SA' ART_SKU,
		po.`asstd_tag`,
		ar.`storeBranch` storbranch, 
		ar.`_area` area , 
		(SELECT SUM(qty) FROM  trx_po_dt 
		WHERE po_sysctrlno IN($trxpos)  AND art_rid = dt.`art_rid` GROUP BY art_rid LIMIT 1) total,
		dt.`po_sysctrlno`
		FROM {$this->db_erp}.trx_agpo_hd_print aa
		JOIN {$this->db_erp}.trx_po_dt dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
		JOIN {$this->db_erp}.trx_po_hd po ON po.`po_sysctrlno` = dt.`po_sysctrlno` 
		JOIN {$this->db_erp}.mst_article art ON dt.`art_rid` = art.`recid`
		JOIN  {$this->db_erp}.`mst_wshe_grp` rack
		ON( dt.`po_wshe_grp_id` = rack.`recid` OR dt.`po_wshe_sbin_id` = rack.`recid` )
		JOIN {$this->db_erp}.`mst_branch_area` ar 
		ON (SUBSTR(rack.`wshe_grp`,(INSTR(rack.`wshe_grp`,'-')+1)) = ar.`storeBranch`)
		WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
		 AND po.`plnt_id` = '{$plantID}' AND po.`wshe_id` = '{$whseID}'
		GROUP BY 
		aa.`agpo_sysctrlno`,
		art.`ART_CODE`,
		ar.`_area`,
		ar.`storeBranch`
		 "; 


$q3 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('AGPO-'.$r['agpo_sysctrlno']);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(25,15,'',1,0,'C');
// $pdf->Image(site_url().'/assets/img/SMC-LOGOv2.png',8,11,20,0,'png');

$pdf->Cell(132,15,'CROSS DOCK ALLOCATION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(49,10,'Cross Dock Allocation Ref No.',1,0,'C'); 
$pdf->SetXY(162,20); 
$pdf->Cell(49,5,$r['agpo_sysctrlno'],1,0,'C'); 
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
$pdf->Cell(25,5,$mylibzsys->mydate_mmddyyyy($r['trx_date']),1,0,'L');  

$pdf->SetXY(5,40);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time start',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(5,45);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'Time end:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'',1,0,'L');  

$pdf->SetXY(55,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,10,'WHSE: ',1,1,'C'); 
$pdf->SetXY(55,40);  
$pdf->Cell(25,10,$wshe_code,1,0,'C'); 

$pdf->SetXY(80,30);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,20,'Allocator ',1,0,'C'); 
$pdf->SetXY(80,33);  
$pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); 
$pdf->SetXY(110,30);
$pdf->Cell(35,20,'',1,0,'C'); 
$pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
$pdf->Cell(26,20,' ',1,0,'C'); 

$pdf->SetXY(5,50);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'ALLOCATION GUIDE',1,0,'C');  
$pdf->SetFont('Arial','B',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(5,55); 

$pdf->Cell(30,8,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(30,8,'BOX CONTENT',1,0,'C','true'); 
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
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r['agpo_sysctrlno'],0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(170);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r['agpo_sysctrlno'],0,0,'C');


$Y = 63;
$total_qty = 0;
$total_amount = 0;
$box_no = 0;
$prev_item = '';
$me_border = 1;
$xrecid = 0;

foreach($q3->getResultArray() as $row){

	//$po_dt_id  = $row['recid'];
	$total = $row['total'];
	$po_dt_id  = $row['art_rid'];
	$expected =  $row['expected'];
	$area = $row['area'];
	$store_branch = utf8_decode($memelibsys->mefirtsubstring('-',$row['storbranch']));

	$ART_DESC = $row['ART_CODE'];
	$ART_UOM  = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$yaxis = 5;



	if ($asstd_tag == 'Y') {
			$str = "
			SELECT 
				a.`imat_qty` qty ,
				a.`imat_wgrp` po_dt_id,
				a.`ucost` price,	
				b.`ART_CODE`,
				b.`ART_BARCODE1` ART_DESC,
				a.`imat_sbincode`
			FROM
			{$this->db_erp}.`trx_po_asstd_dt` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`imat_rid` = b.`recid`
			WHERE
			a.`potrx_no`    = '{$row['po_sysctrlno']}'
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

	$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->getNumRows() > 0){
		$_convf = 0;

		foreach($q->getResultArray() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty      = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_price    = $row['price'];
			//$_po_dt_id = $row['po_dt_id'];
			$_convf    += $row['qty'];

			$item_data = $ART_CODE.'x|x'.$_ART_CODE.'x|x'.''.'x|x'.''.'x|x'.''.'x|x'.$po_dt_id;
			array_push($item, $item_data);

		}
	}
	else{
		
		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.''.'x|x'.''.'x|x'.''.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	$item_no = 1;



	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		//$store_branch =  $data[4];
		$_recid = $data[5];

		
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

		if($_recid !=  $xrecid ) {
			$border = 'T,R,L';
		}
		elseif ($_recid == $xrecid) {
			$border = 'L,R';
		}
		else{
			$border = 'B';
		}

		if($Y < 251){
		
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(30,$yaxis,$_ART_CODE,$border,0,'L'); 
				$pdf->Cell(30,$yaxis,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,1,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),1,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(30,5,'',$border,0,'L'); 
				$pdf->Cell(30,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),1,0,'C'); 
				$pdf->Cell(20,5,'',$border,0,'C'); 
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
			$pdf->Cell(30,8,'ITEM CODE',1,0,'C','true'); 
			$pdf->Cell(30,8,'BOX CONTENT',1,0,'C','true'); 
			$pdf->Cell(30,8,'AREA',1,0,'C','true'); 
			$pdf->Cell(30,8,'STORE BRANCH',1,0,'C','true'); 
			$pdf->Cell(60,4,'QUANTITY',1,0,'C','true'); 
			$pdf->SetXY(125,15); 
			$pdf->Cell(20,4,'EXPECTED',1,0,'C','true'); 
			$pdf->Cell(20,4,'TOTAL',1,0,'C','true'); 
			$pdf->Cell(20,4,'ACTUAL',1,0,'C','true'); 
			$pdf->SetXY(185,11); 
			$pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r['agpo_sysctrlno'],0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(170);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$r['agpo_sysctrlno'],0,0,'C');




			$Y = $Y + 8;
		
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(30,$yaxis,$_ART_CODE,$border,0,'L'); 
				$pdf->Cell(30,$yaxis,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(30,$yaxis,$area,1,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),1,0,'C'); 
				$pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',$border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			else{
				$pdf->Cell(30,5,'',$border,0,'L'); 
				$pdf->Cell(30,5,$_ART_DESC,$me_border,0,'L'); 
				$pdf->Cell(30,5,$area,$me_border,0,'C'); 
				$pdf->Cell(30,5,$store_branch,1,0,'C'); 
				$pdf->Cell(20,5,number_format($expected,0),$border,0,'C'); 
				$pdf->Cell(20,5,'',$border,0,'C'); 
				$pdf->Cell(20,5,'',1,0,'C'); 
				$pdf->Cell(26,5,'',1,0,'C'); 

			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	

}

if($Y < 191){
	$pdf->SetFont('Arial','',8);
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
	$pdf->Cell(75,5,''/*$encd_fullname*/,'B',0,'C'); 
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
	$pdf->Cell(60,5,'Cross Dock Supervisor',0,1,'C');




	$Y = $Y + 10;
	$pdf->SetXY(5,$Y+5);
	$pdf->Cell(13,5,'PO ref.',0,0,'L');
	$pdf->Cell(192,5,substr($po_ref, 0,113),'B',1,'L');
	$pdf->SetX(5);
	$pdf->Cell(205,5,substr($po_ref, 113),'B',1,'L');



}
else{

	$pdf->SetFont('Arial','',8);
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
	$pdf->Cell(75,5,''/*$encd_fullname*/,'B',0,'C'); 
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
	$pdf->SetXY(5,$Y+5);
	$pdf->Cell(13,5,'PO ref.',0,0,'L');
	$pdf->Cell(192,5,substr($po_ref, 0,113),'B',1,'L');
	$pdf->SetX(5);
	$pdf->Cell(205,5,substr($po_ref, 113),'B',1,'L');

}

$pdf->output('','AGPO-'.$r['agpo_sysctrlno']);
