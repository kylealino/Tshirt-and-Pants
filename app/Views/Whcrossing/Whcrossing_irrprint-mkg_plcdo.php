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


$str = "
	UPDATE
		{$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
	SET
		a.`print_flag` = '2',
		b.`irr_print` = '2'
	WHERE
		(a.`po_sysctrlno` = b.`po_sysctrlno`) AND SHA2(CONCAT(b.`po_sysctrlno`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$sep = '"\'"';
$str = "
    SELECT 
    aa.`recid`,
	aa.`po_sysctrlno`,
	aa.`po_type_id`,
	aa.`trx_date`,
	aa.`trx_delivery_date`,
	aa.`print_flag`,
	sha2(concat(aa.`vend_rid`,'{$mpw_tkn}'),384) mtkn_vndrtr,
	sha2(concat(aa.`vends_rid`,'{$mpw_tkn}'),384) mtkn_vndsrtr,
	gg.`recid` __ppo_id,
	ff.`myuserfulln`,
	gg.`agpo_sysctrlno` agpo_sysctrlno,
	gg.`irr_sysctrlno`,
	aa.`dr_list`,
	GROUP_CONCAT(gg.`po_sysctrlno` ORDER BY gg.`po_sysctrlno` ASC SEPARATOR ', ') __poref,
	GROUP_CONCAT( concat($sep,gg.`po_sysctrlno`,$sep)) __po
    FROM (({$this->db_erp}.`trx_po_hd` aa 
    LEFT JOIN {$this->db_erp}.`trx_agpo_hd_print` gg 
    ON(aa.`recid`=gg.`po_id`))
    LEFT JOIN 
    {$this->db_erp}.myusers ff 
    ON(gg.`muser`=ff.`myusername`))
    WHERE sha2(concat(gg.`agpo_sysctrlno`,'{$mpw_tkn}'),384) = '$mtkn_potr'
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
	$irr_ctrlno    = $r['irr_sysctrlno'];
	$PO_CTRLNO     = $r['po_sysctrlno'];
	$print_flag    = $r['print_flag'];
	$po_ref = $r['__poref'];
	$asstd_tag     = '';
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

//167 CD OVERFLOW
//166 CD-CDO
$str = "
			SELECT
			dt.`recid`,
			dt.`art_rid`,
			dt.`recid` po_dt_id, 
			art.`ART_CODE`,art.`ART_BARCODE1` ART_DESC,
			aa.`recid` __ppo_id,
			sum(dt.`qty`) expected,
			dt.`convf`,
			IFNULL((SELECT SUM(qty) FROM  trx_po_dt WHERE  po_wshe_id = 167 AND `art_rid` = dt.`art_rid` AND  po_sysctrlno IN ($trxpos) ),0) w11ov,
			IFNULL((SELECT SUM(qty) FROM  trx_po_dt WHERE  po_wshe_id = 166 AND `art_rid` = dt.`art_rid` AND  po_sysctrlno IN ($trxpos) ),0) w1g1,
			'SA' ART_SKU,
			po.`asstd_tag`,
			dt.`po_sysctrlno`
			FROM {$this->db_erp}.`trx_agpo_hd_print` aa
			JOIN {$this->db_erp}.`trx_po_dt` dt ON aa.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN {$this->db_erp}.`trx_po_hd` po ON po.`po_sysctrlno` = dt.`po_sysctrlno` 
			JOIN {$this->db_erp}.`mst_article` art ON dt.`art_rid` = art.`recid`
			WHERE aa.`agpo_sysctrlno` = '$PPO_CTRLNO' 
			GROUP BY dt.`art_rid`";



$q3 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$box = array();

$pdf = new Mypdf('L');
$pdf->AliasNbPages();
$pdf->SetTitle('IRR-'.$r['irr_sysctrlno']);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(45,15,'',1,0,'C');
// $pdf->Image(site_url().'/assets/img/SMC-LOGO.png',8,11,37,0,'png');

$pdf->Cell(165,15,'INBOUND RECEIVING & INSPECTION OUTPUT REPORT',1,0,'C'); 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(58,10,'Inbound Receiving Report Ref. No.',1,0,'C'); 
$pdf->SetXY(215,20); 
$pdf->Cell(58,5,$irr_ctrlno,1,0,'C'); 

$pdf->SetXY(5,25);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,10,'Date Received and Inspected:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(40,10, '',1,0,'L');

$pdf->SetXY(5,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,10,'Packing List:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(40,10,$pl_no,1,0,'L');  

$pdf->SetXY(90,25);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,10,'Inspected by:',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(30,10, '',1,0,'L');

$pdf->SetXY(90,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,10,'Pakyawan Group',1,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(30,10,'',1,0,'L');  


$pdf->SetXY(150,25);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(65,5,'TIME OF UNLOADING',1,0,'C');
$pdf->SetXY(150,30);  
$pdf->Cell(32.5,5,'STARTED',1,0,'C'); 
$pdf->Cell(32.5,5,'ENDED',1,0,'C'); 

$pdf->SetFont('Arial','',8);
$pdf->SetXY(150,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(32.5,10,'',1,0,'L'); 
$pdf->Cell(32.5,10,'',1,0,'L'); 

$pdf->SetXY(215,25);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(58,10,'DEVIATION REPORT INVOLVE',1,0,'C'); 
$pdf->SetXY(215,35);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(58,10,'',1,0,'L'); 



// $pdf->SetXY(5,35);  
// $pdf->SetFont('Arial','B',8);
// $pdf->Cell(50,10,'Time start',1,0,'L'); 
// $pdf->SetFont('Arial','',8);
// $pdf->Cell(50,10,'',1,0,'L');  
// $pdf->SetFont('Arial','B',8);

// $pdf->SetXY(5,40);  
// $pdf->SetFont('Arial','B',8);
// $pdf->Cell(50,10,'Time end:',1,0,'L'); 
// $pdf->SetFont('Arial','',7);
// $pdf->Cell(50,10,'',1,0,'L');  



// $pdf->SetXY(55,30);  
// $pdf->SetFont('Arial','B',8);
// $pdf->Cell(30,20,'Allocator ',1,0,'C'); 
// $pdf->SetXY(55,33);  
// $pdf->Cell(30,20,'Personnel Involve ',0,0,'C'); z
// $pdf->SetXY(85,30);
// $pdf->Cell(46,20,'',1,0,'C'); 
// $pdf->Cell(40,20,'Cross Dock Team Leader ',1,0,'C'); 
// $pdf->Cell(40,20,' ',1,0,'C'); 


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,50); 
$pdf->Cell(20,12,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(20,12,'BOX CONTENT',1,0,'C','true'); 
$pdf->Cell(10,6,'TOTAL','T,R,L',0,'C','true'); 
$pdf->Cell(10.5,6,'TOTAL',1,0,'C','true'); 
$pdf->Cell(57,4,'INSPECTION RESULT',1,0,'C','true');
$pdf->Cell(150,4,'QUANTITY ENDORSEMENT',1,0,'C','true');  
$pdf->SetXY(45,56);
$pdf->Cell(10,6,'PALLET','B,L,R',0,'C','true'); 
$pdf->Cell(10.5,6,'KG','B,L,R',0,'C','true'); 
$pdf->SetXY(65.5,54);
$pdf->Cell(17,4,'GOOD','T,R,L',0,'C','true'); 
$pdf->Cell(20,8,'DAMAGE',1,0,'C','true'); 
$pdf->Cell(20,8,'VARIANCE',1,0,'C','true');
// $pdf->Cell(20,4,'DUMPING AREA','T,L,R',0,'C','true');
$pdf->Cell(24,4,'OVERFLOW',1,0,'C','true');
$pdf->Cell(21,4,'CD-CDO',1,0,'C','true');
$pdf->Cell(21,4,'--',1,0,'C','true');
$pdf->Cell(21,4,'--',1,0,'C','true');
$pdf->Cell(21,4,'--',1,0,'C','true');
$pdf->Cell(21,4,'--',1,0,'C','true');
$pdf->Cell(21,4,'--',1,0,'C','true');

$pdf->SetXY(65.5,58); 
$pdf->Cell(17,4,'RECEIVE','B,R,L',0,'C','true'); 
$pdf->SetXY(122.5,58); 
// $pdf->Cell(20,4,'(WH11B)','B,L,R',0,'C','true');
$pdf->Cell(12,4,'XPCTD',1,0,'C','true');
$pdf->Cell(12,4,'ACTUAL',1,0,'C','true');
$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
// $pdf->SetXY(185,50); 
// $pdf->Cell(26,8,'REMARKS',1,0,'C','true');  

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of IRR: '.$irr_ctrlno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(220);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of IRR: '.$irr_ctrlno,0,0,'C');


$Y                = 62;
$total_qty        = 0;
$total_amount     = 0;
$box_no           = 0;
$prev_item        = '';
$me_border        = 1;
$grandTotalBox    = 0;
$grandTotalPerBox = 0;

$gtw1g1 = 0;
$gtw1g2 = 0;
$gtw2g3 = 0;
$gtw2g4 = 0;
$gtw11g5 = 0;
$gtw11g6 = 0;
$gtw11ov = 0;
foreach($q3->getResultArray() as $row){

	$po_dt_id  = $row['recid'];
	$total = 0;//$row['total'];
	$po_dt_id  = $row['recid'];
	$tboxes =  $row['expected'];
	$convf = $row['convf'];

	$ART_DESC = $row['ART_CODE'];
	$ART_CODE = $row['ART_CODE'];
	$asstd_tag = $row['asstd_tag'];
	$w11ov = $row['w11ov'];
	$w1g1 = $row['w1g1'];
	$w1g2 = 0;//$row['w1g2'];
	$w2g3 = 0;//$row['w2g3'];
	$w2g4 = 0;//$row['w2g4'];
	$w11g5 = 0;//$row['w11g5'];
	$w11g6 = 0;//$row['w11g6'];
	

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
			{$this->db_erp}.`trx_po_dt` c
			ON
			a.`po_dt_id` = c.`recid` 
			WHERE
			a.`potrx_no`    = '{$row['po_sysctrlno']}'
			AND 
			a.`mat_rid` = '{$row['art_rid']}'
			GROUP BY a.`imat_rid`,a.`imat_qty`
			ORDER by
			a.`pohd_rid`
		";

	}
	else{
		$str = "
			SELECT 
				SUM(a.`qty`) AS `total_pcs`,
				a.`qty`,
				a.`po_dt_id`,
				a.`price`,
				b.`ART_CODE`,
				b.`ART_DESC`,
				SUM(c.`qty`) tot_box,
				SUM(c.`convf`) convf,
				IFNULL((SELECT SUM(qty) FROM  trx_po_dt WHERE  po_wshe_id = 167 AND `art_rid` = c.`art_rid` AND `convf` = a.`qty` AND  po_sysctrlno IN ($trxpos) ),0) w11ov,
				IFNULL((SELECT SUM(qty) FROM  trx_po_dt WHERE  po_wshe_id = 166 AND `art_rid` = c.`art_rid` AND `convf` = a.`qty` AND  po_sysctrlno IN ($trxpos) ),0) w1g1
				
			FROM
			{$this->db_erp}.`trx_po_dt_item` a
			LEFT JOIN
			{$this->db_erp}.`mst_article` b
			ON
			a.`mat_rid` = b.`recid`
			 JOIN
			{$this->db_erp}.`trx_po_dt` c
			ON
			a.`po_dt_id` = c.`recid` 
			WHERE
			a.`po_sysctrlno` IN ($trxpos) AND a.`mat_rid` =  '{$row['art_rid']}'
			GROUP BY a.`mat_rid`,a.`qty`
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
			$_po_dt_id = $row['po_dt_id'];
			$_convf += $row['qty'];
			$total_pallet = $row['tot_box'];
			$total_kg = $row['qty'];
			$w11ov = $row['w11ov'];
			$w1g1  = $row['w1g1'];
			$w1g2  = 0;//$row['w1g2'];
			$w2g3  = 0;//$row['w2g3'];
			$w2g4  = 0;//$row['w2g4'];
			$w11g5 = 0;//$row['w11g5'];
			$w11g6 = 0;//$row['w11g6'];
	


			$item_data = $ART_CODE.'x|x'.$_ART_CODE.'x|x'.$total_pallet.'x|x'.$total_kg.'x|x'.''.'x|x'.$po_dt_id.'x|x'
			.$row['w1g1'].'x|x'
			.'0'.'x|x'
			.'0'.'x|x'
			.'0'.'x|x'
			.'0'.'x|x'
			.'0'.'x|x'
			.$row['w11ov'];
			array_push($item, $item_data);
		}
	}
	else{


		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$tboxes.'x|x'.$convf.'x|x'.''.'x|x'.$po_dt_id.'x|x'
		.$w1g1.'x|x'
		.$w1g2.'x|x'
		.$w2g3.'x|x'
		.$w2g4.'x|x'
		.$w11g5.'x|x'
		.$w11g6.'x|x'
		.$w11ov;
		array_push($item, $item_data);
	}
	

	$xrecid = 0;
	$item_no = 1;


	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$tboxes = $data[2];
		$convf =  $data[3];
		//$store_branch =  $data[4];
		$_recid = $data[5];

		$w1g1  = $data[6];
		$w1g2  = $data[7];
		$w2g3  = $data[8];
		$w2g4  = $data[9];
		$w11g5 = $data[10];
		$w11g6 = $data[11];
		$w11ov = $data[12];

		$grandTotalBox += $tboxes;
		$grandTotalPerBox += $convf;
		$gtw11ov += $w11ov;
		$gtw1g1 += $w1g1;
		$gtw1g2 += $w1g2;
		$gtw2g3 += $w2g3;
		$gtw2g4 += $w2g4;
		$gtw11g5 += $w11g5;
		$gtw11g6 += $w11g6;

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



		if($Y < 182){
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(20,$yaxis,$_ART_CODE,$border,0,'L'); 
				$pdf->Cell(20,$yaxis,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(10,5,number_format($tboxes,0),1,0,'C'); 
				$pdf->Cell(10.5,5,number_format($convf,2,'.',','),1,0,'C'); 
				$pdf->Cell(17,5,'',1,0,'L'); //good receive
				$pdf->Cell(20,5,'',1,0,'L');  // damge
				$pdf->Cell(20,5,'',1,0,'L');  // variance
				// $pdf->Cell(20,5,'',1,0,'L');  // dump area
				$pdf->Cell(12,5,number_format($w11ov,0),1,0,'C'); //overflow xpctd
				$pdf->Cell(12,5,'',1,0,'L'); //overflow actual
				$pdf->Cell(10.5,5,number_format($w1g1,0),1,0,'C'); //w1g1 xpctd
				$pdf->Cell(10.5,5,'',1,0,'L'); //w1g1 actual
				$pdf->Cell(10.5,5,number_format($w1g2,0),1,0,'C'); //w1g2 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w1g2 actual
				$pdf->Cell(10.5,5,number_format($w2g3,0),1,0,'C');//w2g3 xpctd
				$pdf->Cell(10.5,5,'',1,0,'L'); //w2g3 actual
				$pdf->Cell(10.5,5,number_format($w2g4,0),1,0,'C');//w2g4 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w2g4 actual
				$pdf->Cell(10.5,5,number_format($w11g5,0),1,0,'C');//w11g5 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w11g5actual
				$pdf->Cell(10.5,5,number_format($w11g6,0),1,0,'C'); //w11g6 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w11g6 actual
				 
				// $pdf->Cell(30,$yaxis,$area,1,0,'C'); 
				// $pdf->Cell(30,5,$store_branch,1,0,'C'); 
				// $pdf->Cell(20,5,number_format($expected,0),1,0,'C'); 
				// $pdf->Cell(20,$yaxis,($total > 0 )?number_format($total,0):'',1,0,'C'); 

			}
			else{
				$pdf->Cell(20,$yaxis,'',$border,0,'L'); 
				$pdf->Cell(20,$yaxis,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(10,5,number_format($tboxes,0),1,0,'C'); 
				$pdf->Cell(10.5,5,number_format($convf,2,'.',','),1,0,'L'); 
				$pdf->Cell(17,5,'',1,0,'L'); //good receive
				$pdf->Cell(20,5,'',1,0,'L');  // damge
				$pdf->Cell(20,5,'',1,0,'L');  // variance
				// $pdf->Cell(20,5,'',1,0,'L');  // dump area
				$pdf->Cell(12,5,number_format($w11ov,0),1,0,'C'); //overflow xpctd
				$pdf->Cell(12,5,'',1,0,'L'); //overflow actual
				$pdf->Cell(10.5,5,number_format($w1g1,0),1,0,'C'); //w1g1 xpctd
				$pdf->Cell(10.5,5,'',1,0,'L'); //w1g1 actual
				$pdf->Cell(10.5,5,number_format($w1g2,0),1,0,'C'); //w1g2 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w1g2 actual
				$pdf->Cell(10.5,5,number_format($w2g3,0),1,0,'C');//w2g3 xpctd
				$pdf->Cell(10.5,5,'',1,0,'L'); //w2g3 actual
				$pdf->Cell(10.5,5,number_format($w2g4,0),1,0,'C');//w2g4 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w2g4 actual
				$pdf->Cell(10.5,5,number_format($w11g5,0),1,0,'C');//w11g5 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w11g5actual
				$pdf->Cell(10.5,5,number_format($w11g6,0),1,0,'C'); //w11g6 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w11g6 actual

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
			$pdf->Cell(20,12,'ITEM CODE',1,0,'C','true'); 
			$pdf->Cell(20,12,'BOX CONTENT',1,0,'C','true'); 
			$pdf->Cell(10,6,'TOTAL','T,R,L',0,'C','true'); 
			$pdf->Cell(10.5,6,'TOTAL','T,R,L',0,'C','true'); 
			$pdf->Cell(57,4,'INSPECTION RESULT',1,0,'C','true');
			$pdf->Cell(150,4,'QUANTITY ENDORSEMENT',1,0,'C','true');  
			$pdf->SetXY(45,$Y+6);
			$pdf->Cell(10,6,'PALLET','B,L,R',0,'C','true'); 
			$pdf->Cell(10.5,6,'KG','B,L,R',0,'C','true'); 
			$pdf->SetXY(65.5,$Y+4);
			$pdf->Cell(17,4,'GOOD','T,R,L',0,'C','true'); 
			$pdf->Cell(20,8,'DAMAGE',1,0,'C','true'); 
			$pdf->Cell(20,8,'VARIANCE',1,0,'C','true');
			// $pdf->Cell(20,4,'DUMPING AREA','T,L,R',0,'C','true');
			$pdf->Cell(24,4,'OVERFLOW',1,0,'C','true');
			$pdf->Cell(21,4,'CD-CDO',1,0,'C','true');
			$pdf->Cell(21,4,'--',1,0,'C','true');
			$pdf->Cell(21,4,'--',1,0,'C','true');
			$pdf->Cell(21,4,'--',1,0,'C','true');
			$pdf->Cell(21,4,'--',1,0,'C','true');
			$pdf->Cell(21,4,'--',1,0,'C','true');
			$pdf->SetXY(65.5,$Y+8); 
			$pdf->Cell(17,4,'RECEIVE','B,R,L',0,'C','true'); 
			$pdf->SetXY(122.5,$Y+8); 
			// $pdf->Cell(20,4,'(WH11B)','B,L,R',0,'C','true');
			$pdf->Cell(12,4,'XPCTD',1,0,'C','true');
			$pdf->Cell(12,4,'ACTUAL',1,0,'C','true');
			$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
			$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
			$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
			$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
			$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
			$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
			$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
			$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
			$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
			$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');
			$pdf->Cell(10.5,4,'XPCTD',1,0,'C','true');
			$pdf->Cell(10.5,4,'ACTUAL',1,0,'C','true');

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$irr_ctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(220);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of AGPO: '.$irr_ctrlno,0,0,'C');



	

			$Y = $Y + 12;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(20,$yaxis,$_ART_CODE,$border,0,'L'); 
				$pdf->Cell(20,$yaxis,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(10,5,number_format($tboxes,0),1,0,'C'); 
				$pdf->Cell(10.5,5,number_format($convf,2,'.',','),1,0,'C'); 
				$pdf->Cell(17,5,'',1,0,'L'); //good receive
				$pdf->Cell(20,5,'',1,0,'L');  // damge
				$pdf->Cell(20,5,'',1,0,'L');  // variance
				// $pdf->Cell(20,5,'',1,0,'L');  // dump area
				$pdf->Cell(12,5,number_format($w11ov,0),1,0,'C'); //overflow xpctd
				$pdf->Cell(12,5,'',1,0,'L'); //overflow actual
				$pdf->Cell(10.5,5,number_format($w1g1,0),1,0,'C'); //w1g1 xpctd
				$pdf->Cell(10.5,5,'',1,0,'L'); //w1g1 actual
				$pdf->Cell(10.5,5,number_format($w1g2,0),1,0,'C'); //w1g2 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w1g2 actual
				$pdf->Cell(10.5,5,number_format($w2g3,0),1,0,'C');//w2g3 xpctd
				$pdf->Cell(10.5,5,'',1,0,'L'); //w2g3 actual
				$pdf->Cell(10.5,5,number_format($w2g4,0),1,0,'C');//w2g4 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w2g4 actual
				$pdf->Cell(10.5,5,number_format($w11g5,0),1,0,'C');//w11g5 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w11g5actual
				$pdf->Cell(10.5,5,number_format($w11g6,0),1,0,'C'); //w11g6 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w11g6 actual

			}
			else{
				$pdf->Cell(20,$yaxis,'',$border,0,'L'); 
				$pdf->Cell(20,$yaxis,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(10,5,number_format($tboxes,0),1,0,'C'); 
				$pdf->Cell(10.5,5,number_format($convf,2,'.',','),1,0,'L'); 
				$pdf->Cell(17,5,'',1,0,'L'); //good receive
				$pdf->Cell(20,5,'',1,0,'L');  // damge
				$pdf->Cell(20,5,'',1,0,'L');  // variance
				// $pdf->Cell(20,5,'',1,0,'L');  // dump area
				$pdf->Cell(12,5,number_format($w11ov,0),1,0,'C'); //overflow xpctd
				$pdf->Cell(12,5,'',1,0,'L'); //overflow actual
				$pdf->Cell(10.5,5,number_format($w1g1,0),1,0,'C'); //w1g1 xpctd
				$pdf->Cell(10.5,5,'',1,0,'L'); //w1g1 actual
				$pdf->Cell(10.5,5,number_format($w1g2,0),1,0,'C'); //w1g2 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w1g2 actual
				$pdf->Cell(10.5,5,number_format($w2g3,0),1,0,'C');//w2g3 xpctd
				$pdf->Cell(10.5,5,'',1,0,'L'); //w2g3 actual
				$pdf->Cell(10.5,5,number_format($w2g4,0),1,0,'C');//w2g4 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w2g4 actual
				$pdf->Cell(10.5,5,number_format($w11g5,0),1,0,'C');//w11g5 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w11g5actual
				$pdf->Cell(10.5,5,number_format($w11g6,0),1,0,'C'); //w11g6 xpctd
				$pdf->Cell(10.5,5,'',1,0,'C'); //w11g6 actual

			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	

}

if($Y < 191){
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(40,5,'TOTAL',1,0,'C'); 
	$pdf->Cell(10,5,number_format($grandTotalBox,0),1,0,'C');
	$pdf->Cell(10.5,5,number_format($grandTotalPerBox,2,'.',','),1,0,'C'); 
	$pdf->Cell(17,5,'',1,0,'L'); //good receive
	$pdf->Cell(20,5,'',1,0,'L');  // damge
	$pdf->Cell(20,5,'',1,0,'L');  // variance
	// $pdf->Cell(20,5,'',1,0,'L');  // dump area
	$pdf->Cell(12,5,number_format($gtw11ov,0),1,0,'C'); //overflow xpctd
	$pdf->Cell(12,5,'',1,0,'L'); //overflow actual
	$pdf->Cell(10.5,5,number_format($gtw1g1,0),1,0,'C'); //w1g1 xpctd
	$pdf->Cell(10.5,5,'',1,0,'L'); //w1g1 actual
	$pdf->Cell(10.5,5,number_format($gtw1g2,0),1,0,'C'); //w1g2 xpctd
	$pdf->Cell(10.5,5,'',1,0,'C'); //w1g2 actual
	$pdf->Cell(10.5,5,number_format($gtw2g3,0),1,0,'C');//w2g3 xpctd
	$pdf->Cell(10.5,5,'',1,0,'L'); //w2g3 actual
	$pdf->Cell(10.5,5,number_format($gtw2g4,0),1,0,'C');//w2g4 xpctd
	$pdf->Cell(10.5,5,'',1,0,'C'); //w2g4 actual
	$pdf->Cell(10.5,5,number_format($gtw11g5,0),1,0,'C');//w11g5 xpctd
	$pdf->Cell(10.5,5,'',1,0,'C'); //w11g5actual
	$pdf->Cell(10.5,5,number_format($gtw11g6,0),1,0,'C'); //w11g6 xpctd
	$pdf->Cell(10.5,5,'',1,0,'C'); //w11g6 actual

	$pdf->SetXY(5,$Y+5);
	$pdf->Cell(267.5,5,'BY SIGNING THIS DOCUMENTS IT INDICATES THAT THE ENDORSEMENT OF ITEMS BY THE GROUP.',1,1,'C'); 
	$pdf->SetXY(5,$Y+10);
	$pdf->Cell(40,8,'CD-CDO','R,L',0,'C'); 
	$pdf->Cell(45,8,'--','R,L',0,'C'); 
	$pdf->Cell(45,8,'--','R,L',0,'C'); 
	$pdf->Cell(50,8,'--','R,L',0,'C'); 
	$pdf->Cell(38,8,'--','R,L',0,'C'); 
	$pdf->Cell(49.5,8,'--','R,L',1,'C'); 
	$pdf->SetXY(5,$Y+18);
	$pdf->SetFont('Arial','',8); 
	$pdf->Cell(40,4,'','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'','B,R,L',0,'C'); 
	$pdf->Cell(50,4,'','B,R,L',0,'C'); 
	$pdf->Cell(38,4,'','B,R,L',0,'C'); 
	$pdf->Cell(49.5,4,'','B,R,L',1,'C');

	$pdf->SetXY(5,$Y+22);
	$pdf->Cell(40,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->Cell(50,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->Cell(38,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->Cell(49.5,4,'Representative signature','B,R,L',1,'C');



	$pdf->SetFont('Arial','B',8); 
	$pdf->SetXY(5,$Y+30);
	$pdf->Cell(40,8,'Prepared by','T,R,L',0,'C'); 
	$pdf->Cell(45,8,'Inspected by','T,R,L',0,'C'); 
	$pdf->Cell(61,8,'Reviewed by','T,R,L',0,'C'); 
	$pdf->Cell(61,8,'Verified by','T,R,L',0,'C'); 
	$pdf->Cell(61,8,'Validated by','T,R,L',0,'C'); 

	$pdf->SetXY(5,$Y+38);
	$pdf->SetFont('Arial','',8); 
	$pdf->Cell(40,4,$encd_fullname,'B,R,L',0,'C'); 
	$pdf->Cell(45,4,'','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'','B,R,L',0,'C'); 

	$pdf->SetXY(5,$Y+42);
	$pdf->Cell(40,4,'Inbound Associate','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'Inbound Checker','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'Inbound In-charge','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'CAD Inbound Officer','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'Representative signature','B,R,L',0,'C'); 

	$pdf->SetXY(5,$Y+47);
	$pdf->Cell(13,5,'PO ref.',0,0,'L');
	$pdf->Cell(192,5,substr($po_ref, 0,113),'B',1,'L');
	$pdf->SetX(5);
	$pdf->Cell(205,5,substr($po_ref, 113),'B',1,'L');

}
else{

	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(5,$Y); 
	$pdf->SetFont('Arial','B',8); 
	$pdf->Cell(40,5,'TOTAL',1,0,'C'); 
	$pdf->Cell(10,5,number_format($grandTotalBox,0),1,0,'C');
	$pdf->Cell(10.5,5,number_format($grandTotalPerBox,2,'.',','),1,0,'C'); 
	$pdf->Cell(17,5,'',1,0,'L'); //good receive
	$pdf->Cell(20,5,'',1,0,'L');  // damge
	$pdf->Cell(20,5,'',1,0,'L');  // variance
	// $pdf->Cell(20,5,'',1,0,'L');  // dump area
	$pdf->Cell(12,5,number_format($gtw11ov,0),1,0,'C'); //overflow xpctd
	$pdf->Cell(12,5,'',1,0,'L'); //overflow actual
	$pdf->Cell(12.5,5,number_format($gtw1g1,0),1,0,'C'); //w1g1 xpctd
	$pdf->Cell(12.5,5,'',1,0,'L'); //w1g1 actual
	$pdf->Cell(12.5,5,number_format($gtw1g2,0),1,0,'C'); //w1g2 xpctd
	$pdf->Cell(12.5,5,'',1,0,'C'); //w1g2 actual
	$pdf->Cell(12.5,5,number_format($gtw2g3,0),1,0,'C');//w2g3 xpctd
	$pdf->Cell(12.5,5,'',1,0,'L'); //w2g3 actual
	$pdf->Cell(12.5,5,number_format($gtw2g4,0),1,0,'C');//w2g4 xpctd
	$pdf->Cell(12.5,5,'',1,0,'C'); //w2g4 actual
	$pdf->Cell(12.5,5,number_format($gtw11g5,0),1,0,'C');//w11g5 xpctd
	$pdf->Cell(12.5,5,'',1,0,'C'); //w11g5actual
	$pdf->Cell(12.5,5,number_format($gtw11g6,0),1,0,'C'); //w11g6 xpctd
	$pdf->Cell(12.5,5,'',1,0,'C'); //w11g6 actual
	$pdf->SetXY(5,$Y+5);
	$pdf->Cell(267.5,5,'BY SIGNING THIS DOCUMENTS IT INDICATES THAT THE ENDORSEMENT OF ITEMS BY THE GROUP.',1,1,'C'); 
	$pdf->SetXY(5,$Y+10);
	$pdf->Cell(40,8,'CD-CDO','R,L',0,'C'); 
	$pdf->Cell(45,8,'--','R,L',0,'C'); 
	$pdf->Cell(45,8,'--','R,L',0,'C'); 
	$pdf->Cell(50,8,'--','R,L',0,'C'); 
	$pdf->Cell(38,8,'--','R,L',0,'C'); 
	$pdf->Cell(49.5,8,'--','R,L',1,'C'); 
	$pdf->SetXY(5,$Y+18);
	$pdf->SetFont('Arial','',8); 
	$pdf->Cell(40,4,'','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'','B,R,L',0,'C'); 
	$pdf->Cell(50,4,'','B,R,L',0,'C'); 
	$pdf->Cell(38,4,'','B,R,L',0,'C'); 
	$pdf->Cell(49.5,4,'','B,R,L',1,'C');

	$pdf->SetXY(5,$Y+22);
	$pdf->Cell(40,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->Cell(50,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->Cell(38,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->Cell(49.5,4,'Representative signature','B,R,L',1,'C');



	$pdf->SetFont('Arial','B',8); 
	$pdf->SetXY(5,$Y+30);
	$pdf->Cell(40,8,'Prepared by','T,R,L',0,'C'); 
	$pdf->Cell(45,8,'Inspected by','T,R,L',0,'C'); 
	$pdf->Cell(61,8,'Reviewed by','T,R,L',0,'C'); 
	$pdf->Cell(61,8,'Verified by','T,R,L',0,'C'); 
	$pdf->Cell(61,8,'Validated by','T,R,L',0,'C'); 

	$pdf->SetXY(5,$Y+38);
	$pdf->SetFont('Arial','',8); 
	$pdf->Cell(40,4,'','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'','B,R,L',0,'C'); 

	$pdf->SetXY(5,$Y+42);
	$pdf->Cell(40,4,'Inbound Associate','B,R,L',0,'C'); 
	$pdf->Cell(45,4,'Inbound Checker','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'Inbound In-charge','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'CAD Inbound Officer','B,R,L',0,'C'); 
	$pdf->Cell(61,4,'Representative signature','B,R,L',0,'C'); 
	$pdf->SetXY(5,$Y+47);
	$pdf->Cell(13,5,'PO ref.',0,0,'L');
	$pdf->Cell(192,5,substr($po_ref, 0,113),'B',1,'L');
	$pdf->SetX(5);
	$pdf->Cell(205,5,substr($po_ref, 113),'B',1,'L');

}

$pdf->output('','IRR-'.$irr_ctrlno);
