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
$mpw_tkn        = $mylibzdb->mpw_tkn();

$mtkn_whout = $request->getVar('mtkn_whout');



//get crpl data
$str = "

	SELECT
		b.`crpl_code` 'header',
	    '' __jocode,
	     '' jo_header,
	     '' user,
	     '' print_flag,
		b.`frm_plnt_id`,
		b.`frm_wshe_id`,
		b.`driver`,
		b.`helper_1`,
		b.`helper_2`,
		b.`plate_no`,
		b.`chk_by`,
		b.`refno`,
		b.`me_remk`,
		b.`truck_type`,
		b.`date_encd` AS `date_created`,
		'' AS `date_needed`,
		e.`BRNCH_NAME`,
		e.`BRNCH_ADDR1`,
		e.`BRNCH_ADDR2`,
		e.`BRNCH_ADDR3`,
		e.`BRNCH_ADDR4`,
		f.`COMP_NAME`,
		h.`myuserfulln`,
		w.`plnt_id`,
		w.`recid` `wshe_id`,
		b.`remk` `remarks`,
		w.`wshe_code`
	FROM
		{$this->db_erp}.`warehouse_shipdoc_hd` b
	LEFT JOIN
		{$this->db_erp}.`mst_companyBranch` e
	ON
	b.`brnch_rid` = e.`recid`
	LEFT JOIN
		{$this->db_erp}.`mst_company` f
	ON
	e.`COMP_CODE` = f.`COMP_CODE`
	LEFT JOIN
		{$this->db_erp}.`myusers` h
	ON
		b.`user` = h.`myusername`
			LEFT JOIN
		{$this->db_erp}.`mst_wshe` w
	ON
		b.`frm_wshe_id` = w.`recid`
	WHERE
	SHA2(CONCAT(b.`recid`,'{$mpw_tkn}'),384) = '{$mtkn_whout}'
";
$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

if($q->getNumRows() > 0){
	$rr = $q->getRowArray();
	$crpl_code = $rr['header'];
	$jo_ref = $rr['__jocode'];
	$jo_code = $rr['jo_header'];
	$encd = $rr['date_created'];
	$user = $rr['user'];
	$driver = $rr['driver'];
	$helper_1 = $rr['helper_1'];
	$helper_2 = $rr['helper_2'];
	$plate_no = $rr['plate_no'];
	$chk_by = $rr['chk_by'];
	$date_needed = $rr['date_needed'];
	$BRNCH_NAME = $rr['BRNCH_NAME'];
	$BRNCH_ADDR1 = $rr['BRNCH_ADDR1'];
	$BRNCH_ADDR2 = $rr['BRNCH_ADDR2'];
	$BRNCH_ADDR3 = $rr['BRNCH_ADDR3'];
	$BRNCH_ADDR4 = $rr['BRNCH_ADDR4'];
	$COMP_NAME = $rr['COMP_NAME'];
	$encd_fullname = $rr['myuserfulln'];
	$remarks = $rr['remarks'];
	$wshe_id = $rr['wshe_id'];
	$print_flag = $rr['print_flag'];
	$frm_plnt_id = $rr['frm_plnt_id'];
	$frm_wshe_id = $rr['frm_wshe_id'];
	$refno = $rr['refno'];
	$me_remk = $rr['me_remk'];
	$active_wshe = $rr['wshe_code'];
	$truck_type = $rr['truck_type'];

}
else{
	redirect('warehouse-out/');
}



$str = "
	UPDATE
		{$this->db_erp}.`warehouse_shipdoc_hd`
	SET
		`print_flag` = '2'
	WHERE
		`crpl_code` = '{$crpl_code}'
";

$q_print = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


$str = "

	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`FRM_PLNT`,
		`FRM_WSHE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	)
	VALUES(
	    '{$crpl_code}',
	    'SHIPDOC',
	    '{$frm_plnt_id}',
	    '{$frm_wshe_id}',
	    '1',
	    '{$cuser}',
	    now(),
	    'CRPL_HD'
  	)
";

$q_print_logs = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);




$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('SHIPPING DOCUMENT - '.$crpl_code);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);

$pdf->AddFont('Dot','','Calibri.php');

$pdf->SetFont('Dot','',10);

// header page

$pdf->SetFont('Dot','',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Dot','',15);
$pdf->Cell(112,5,'SMARTLOOK MARKETING CORPORATION',1,0,'L'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(5,15,'1002-B Apolonia St. Mapulang Lupa, Valenzuela City',0,0,'L'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,22,'Tel. Nos.: (02) 961-8641 / 961-8526',0,0,'L'); 

$pdf->SetXY(5,18);
$pdf->SetFont('Dot','',11);
$pdf->Cell(206,5,'SHIPPING DOCUMENT',0,0,'C');

$pdf->SetFont('Dot','',10);
$pdf->SetXY(5,27);

$pdf->Cell(39.5,5,'SHIPPING DOCUMENT NO:',0,0,'L');
$pdf->Cell(77.5,5,$crpl_code,'B',0,'L');


// $pdf->SetXY(134,37);
// $pdf->Cell(25.5,5,'DATE NEEDED:',0,0,'L');
// $pdf->Cell(48.5,5,$mylibzsys->mydate_mmddyyyy($date_needed),'B',0,'L');

$pdf->SetXY(134,32);
$pdf->Cell(35.5,5,'DATE / TIME CREATED:',0,0,'L');
$pdf->Cell(38.5,5,$encd,'B',0,'L');


// $pdf->SetXY(5,47);
// $pdf->Cell(27,5,'JO REFERENCE:',0,0,'L');
// $pdf->SetFont('Dot','',6);
// $pdf->Cell(176,5,$jo_ref,'B',0,'L');

$pdf->SetFont('Dot','',10);
$pdf->SetXY(5,32);
$pdf->Cell(16.5,5,'BRANCH:',0,0,'L');
$pdf->Cell(100.5,5,$BRNCH_NAME,'B',0,'L');

$pdf->SetXY(133.5,42);
$pdf->Cell(25.5,5,'ENCODED BY:',0,0,'L');
$pdf->Cell(48.5,5,$encd_fullname,'B',0,'L');

$pdf->SetXY(5,37);
$pdf->Cell(17.5,5,'COMPANY:',0,0,'L');
$pdf->Cell(99.5,5,$COMP_NAME,'B',0,'L');

$pdf->SetXY(5,42);
$pdf->Cell(17.5,5,'ADDRESS:',0,0,'L');
$pdf->Cell(99.5,5,$BRNCH_ADDR1.', '.$BRNCH_ADDR2.', '.$BRNCH_ADDR3.' '.$BRNCH_ADDR4,'B',0,'L');




//BARCODE
$pdf->SetFont('Dot','',12);
$pdf->Code128(145,10,$crpl_code,65,15);
$pdf->SetXY(145,25);
$pdf->Cell(65,6,$crpl_code,0,0,'C');

// ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Dot','',9);
$pdf->SetXY(5,57);
$pdf->SetFont('Dot','',7);
$pdf->Cell(8,4,'ITEMS',1,0,'C');
$pdf->SetFont('Dot','',9);
$pdf->Cell(12,4,'QTY',1,0,'C');
$pdf->Cell(10,4,'UNIT',1,0,'C');
$pdf->Cell(26,4,'BARCODE',1,0,'C');
$pdf->Cell(27,4,'STOCK NUMBER',1,0,'C');
$pdf->Cell(60,4,'DESCRIPTION',1,0,'C');
$pdf->SetFont('Dot','',7);
$pdf->Cell(11,4,'QTY BOX',1,0,'C');
$pdf->Cell(12,4,'QTY/BOX',1,0,'C');
$pdf->SetFont('Dot','',8);
$pdf->Cell(15,4,'UNIT PRICE',1,0,'C');
$pdf->Cell(15,4,'AMOUNT',1,0,'C');
$pdf->Cell(11,4,'QTY BOX',1,0,'C');
// $pdf->Cell(18,4,'TOTAL',1,0,'C');

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of SHIPPING DOC: '.$crpl_code,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(142);
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of SHIPPING DOC: '.$crpl_code,0,0,'C');

//if($print_flag > 1){
$pdf->SetY(4);
$pdf->SetX(152);
$pdf->SetFont('Arial','I',7);
$pdf->Cell(0,10,'PRE-PRINT - '.$crpl_code,0,0,'C');
//}


$str = "
	SELECT
		 '' `jo_header`,
		SUM(a.`qty`) AS `qty_scan`,
		a.`recid`,
		a.`convf`,
		a.`mat_rid`,
		a.`total_pcs`,
		a.`stock_code`,
		a.`header`,
		b.`ART_CODE`,
		b.`ART_DESC`,
		b.`ART_UPRICE`,
		b.`ART_HIERC1`,
		b.`ART_SKU`,
		b.`ART_BARCODE1`,
		b.`ART_HIERC1`,
		b.`ART_UOM`,
		CONCAT(REPLACE(SUBSTR(a.`wob_barcde`,1,4),'4444','3333'),SUBSTR(a.`wob_barcde`,5)) witb_barcde
	FROM
		{$this->db_erp}.`warehouse_shipdoc_dt` a
	LEFT JOIN
		{$this->db_erp}.`mst_article` b
	ON
		a.`mat_rid` = b.`recid`
	WHERE
		a.`header` = '{$crpl_code}'
	GROUP BY
		a.`header`,a.`mat_rid`,a.`stock_code`
	ORDER BY
		b.`ART_CODE`,a.`stock_code`
";


$q3 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


$Y = 61;
$total_amount = 0;
$total_qty = 0;
$box_no = 1;


//for sku summary
$imp_box = 0;
$imp_sack = 0;
$imp_roll = 0;
$imp_bundle = 0;
$imp_plastic = 0;
$imp_pcs = 0;
$imp_ctns = 0;

$loc_box = 0;
$loc_sack = 0;
$loc_roll = 0;
$loc_bundle = 0;
$loc_plastic = 0;
$loc_pcs = 0;
$loc_ctns = 0;

//additional
$su_box = 0;
$su_sack = 0;
$su_roll = 0;
$su_bundle = 0;
$su_plastic = 0;
$su_pcs = 0;
$su_ctns = 0;


$hsy_box = 0;
$hsy_sack = 0;
$hsy_roll = 0;
$hsy_bundle = 0;
$hsy_plastic = 0;
$hsy_pcs = 0;
$hsy_ctns = 0;


$lsg_box = 0;
$lsg_sack = 0;
$lsg_roll = 0;
$lsg_bundle = 0;
$lsg_plastic = 0;
$lsg_pcs = 0;
$lsg_ctns = 0;


$hqe_box = 0;
$hqe_sack = 0;
$hqe_roll = 0;
$hqe_bundle = 0;
$hqe_plastic = 0;
$hqe_pcs = 0;
$hqe_ctns = 0;


$aycc_box = 0;
$aycc_sack = 0;
$aycc_roll = 0;
$aycc_bundle = 0;
$aycc_plastic = 0;
$aycc_pcs = 0;
$aycc_ctns = 0;


$total_box = 0;
$total_sack = 0;
$total_roll = 0;
$total_bundle = 0;
$total_plastic = 0;
$total_pcs_breakdown = 0;
$total_ctns = 0;


$data_rows = array();

foreach($q3->getResultArray() as $row){

	$no_of_box    = $row['qty_scan'];
	$qty_scan     = $row['qty_scan'];
	$mat_rid      = $row['mat_rid'];
	$crpl_id      = $row['recid'];
	$stock_code   = $row['stock_code'];
	$ART_CODE     = $row['ART_CODE'];
	$ART_DESC     = $row['ART_DESC'];
	$ART_BARCODE1 = $row['ART_BARCODE1'];
	$ART_UOM      = $row['ART_UOM'];
	$ART_SKU      = $row['ART_SKU'];
	$qty          = $row['qty_scan'];
	$convf        = $row['convf'];
	$price        = $row['ART_UPRICE'];
	$total_pcs    = $row['total_pcs'];
	$ART_HIERC1 = $row['ART_HIERC1'];
	$witb_barcde = $row['witb_barcde'];


	$po_class = substr($stock_code, 6, 1);


	if($ART_HIERC1 == 'STORE USE'){
		if($ART_SKU == 'BOX'){
 			$su_box += $qty;
 		}
 		else if($ART_SKU == 'SACK'){
 			$su_sack += $qty;
 		}
 		else if($ART_SKU == 'ROLL'){
 			$su_roll += $qty;
 		}
 		else if($ART_SKU == 'BUNDLE'){
 			$su_bundle += $qty;
 		}
 		else if($ART_SKU == 'PLASTIC'){
 			$su_plastic += $qty;
 		}
 		else if($ART_SKU == 'PCS'){
 			$su_pcs += $qty;
 		}
 		else if($ART_SKU == 'CTNS'){
 			$su_ctns += $qty;
 		}
	}

	else if($ART_HIERC1 == 'HSY'){
		if($ART_SKU == 'BOX'){
 			$hsy_box += $qty;
 		}
 		else if($ART_SKU == 'SACK'){
 			$hsy_sack += $qty;
 		}
 		else if($ART_SKU == 'ROLL'){
 			$hsy_roll += $qty;
 		}
 		else if($ART_SKU == 'BUNDLE'){
 			$hsy_bundle += $qty;
 		}
 		else if($ART_SKU == 'PLASTIC'){
 			$hsy_plastic += $qty;
 		}
 		else if($ART_SKU == 'PCS'){
 			$hsy_pcs += $qty;
 		}
 		else if($ART_SKU == 'CTNS'){
 			$hsy_ctns += $qty;
 		}
	}

	else if($ART_HIERC1 == 'LSG'){
		if($ART_SKU == 'BOX'){
 			$lsg_box += $qty;
 		}
 		else if($ART_SKU == 'SACK'){
 			$lsg_sack += $qty;
 		}
 		else if($ART_SKU == 'ROLL'){
 			$lsg_roll += $qty;
 		}
 		else if($ART_SKU == 'BUNDLE'){
 			$lsg_bundle += $qty;
 		}
 		else if($ART_SKU == 'PLASTIC'){
 			$lsg_plastic += $qty;
 		}
 		else if($ART_SKU == 'PCS'){
 			$lsg_pcs += $qty;
 		}
 		else if($ART_SKU == 'CTNS'){
 			$lsg_ctns += $qty;
 		}
	}

	else if($ART_HIERC1 == 'HQE'){
		if($ART_SKU == 'BOX'){
 			$hqe_box += $qty;
 		}
 		else if($ART_SKU == 'SACK'){
 			$hqe_sack += $qty;
 		}
 		else if($ART_SKU == 'ROLL'){
 			$hqe_roll += $qty;
 		}
 		else if($ART_SKU == 'BUNDLE'){
 			$hqe_bundle += $qty;
 		}
 		else if($ART_SKU == 'PLASTIC'){
 			$hqe_plastic += $qty;
 		}
 		else if($ART_SKU == 'PCS'){
 			$hqe_pcs += $qty;
 		}
 		else if($ART_SKU == 'CTNS'){
 			$hqe_ctns += $qty;
 		}
	}

	else if($ART_HIERC1 == 'AYCC'){
		if($ART_SKU == 'BOX'){
 			$aycc_box += $qty;
 		}
 		else if($ART_SKU == 'SACK'){
 			$aycc_sack += $qty;
 		}
 		else if($ART_SKU == 'ROLL'){
 			$aycc_roll += $qty;
 		}
 		else if($ART_SKU == 'BUNDLE'){
 			$aycc_bundle += $qty;
 		}
 		else if($ART_SKU == 'PLASTIC'){
 			$aycc_plastic += $qty;
 		}
 		else if($ART_SKU == 'PCS'){
 			$aycc_pcs += $qty;
 		}
 		else if($ART_SKU == 'CTNS'){
 			$aycc_ctns += $qty;
 		}
	}

	if($ART_SKU == 'BOX'){
		$total_box += $qty;
	}
	else if($ART_SKU == 'SACK'){
		$total_sack += $qty;
	}
	else if($ART_SKU == 'ROLL'){
		$total_roll += $qty;
	}
	else if($ART_SKU == 'BUNDLE'){
		$total_bundle += $qty;
	}
	else if($ART_SKU == 'PLASTIC'){
		$total_plastic += $qty;
	}
	else if($ART_SKU == 'PCS'){
		$total_pcs_breakdown += $qty;
	}
	else if($ART_SKU == 'CTNS'){
		$total_ctns += $qty;
	}




 	if($po_class == 1){
 		if($ART_SKU == 'BOX'){
 			$imp_box += $qty;
 		}
 		else if($ART_SKU == 'SACK'){
 			$imp_sack += $qty;
 		}
 		else if($ART_SKU == 'ROLL'){
 			$imp_roll += $qty;
 		}
 		else if($ART_SKU == 'BUNDLE'){
 			$imp_bundle += $qty;
 		}
 		else if($ART_SKU == 'PLASTIC'){
 			$imp_plastic += $qty;
 		}
 		else if($ART_SKU == 'PCS'){
 			$imp_pcs += $qty;
 		}
 		else if($ART_SKU == 'CTNS'){
 			$imp_ctns += $qty;
 		}
 	}
 	else{
 		if($ART_SKU == 'BOX'){
 			$loc_box += $qty;
 		}
 		else if($ART_SKU == 'SACK'){
 			$loc_sack += $qty;
 		}
 		else if($ART_SKU == 'ROLL'){
 			$loc_roll += $qty;
 		}
 		else if($ART_SKU == 'BUNDLE'){
 			$loc_bundle += $qty;
 		}
 		else if($ART_SKU == 'PLASTIC'){
 			$loc_plastic += $qty;
 		}
 		else if($ART_SKU == 'PCS'){
 			$loc_pcs += $qty;
 		}
 		else if($ART_SKU == 'CTNS'){
 			$loc_ctns += $qty;
 		}
 	}

 	$str = "
 		SELECT
 			a.`qty`,
 			b.`ART_CODE`,
 			b.`ART_DESC`,
 			b.`ART_UPRICE`,
 			b.`ART_BARCODE1`,
 			b.`ART_SKU`,
 			b.`ART_UOM`
 		FROM
 			{$this->db_erp}.`warehouse_inv_rcv_item` a
 		LEFT JOIN
 			{$this->db_erp}.`mst_article` b
 		ON
 			a.`mat_rid` = b.`recid`
 		WHERE
 			a.`witb_barcde` = '{$witb_barcde}'
 		GROUP BY b.`ART_CODE`
 		ORDER BY
 			b.`ART_CODE`";


	$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	if($q->getNumRows() > 0){

		$_count_item = $q->getNumRows();
		$_item_no = 1;
		foreach($q->getResultArray() as $rr){
			$_ART_CODE = $rr['ART_CODE'];
			$_ART_DESC = $rr['ART_DESC'];
			$_price = $rr['ART_UPRICE'];
			$_ART_BARCODE1 = $rr['ART_BARCODE1'];
			$_ART_SKU = $rr['ART_SKU'];
			$_ART_UOM = $rr['ART_UOM'];
			$_ART_UOM = $rr['ART_UOM'];
			$_qty_items = $rr['qty'];

			$str = "
				SELECT
					a.`recid`,
					b.`ART_CODE`,
					b.`ART_DESC`,
					b.`ART_UPRICE`,
					b.`ART_BARCODE1`,
					b.`ART_SKU`,
					b.`ART_UOM`
				FROM
					{$this->db_erp}.`warehouse_inv_rcv` a
				LEFT JOIN
					{$this->db_erp}.`mst_article` b
				ON
					a.`mat_rid` = b.`recid`
				LEFT JOIN
					{$this->db_erp}.`warehouse_inv_rcv_item` c
				ON
					a.`recid` = c.`wshe_inv_id`
				WHERE
					b.`ART_CODE` LIKE '%ASSTD%'
				AND
					b.`ART_CODE` = '{$_ART_CODE}'
				AND
					c.`recid` IS NOT NULL
				ORDER BY a.`recid` DESC
				LIMIT 1
			";

			$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

			if($q->getNumRows() > 0){

				$_rr = $q->getRowArray();

				$wshe_inv_id = $_rr['recid'];

				$str = "
					SELECT
						a.`qty`,
						b.`ART_CODE`,
						b.`ART_NCONVF`,
						b.`ART_DESC`,
						b.`ART_BARCODE1`,
						b.`ART_UPRICE`,
						b.`ART_UOM`,
						b.`ART_SKU`
					FROM
						{$this->db_erp}.`warehouse_inv_rcv_item` a
					LEFT JOIN
						{$this->db_erp}.`mst_article` b
					ON
						a.`mat_rid` = b.`recid`
					WHERE
						a.`wshe_inv_id` = '{$wshe_inv_id}'
				";

				$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				$count_item = $q->getNumRows();
				$item_no = 1;
				foreach($q->getResultArray() as $zz){

					$data = $zz['ART_CODE'].'x|x'.$zz['ART_DESC'].'x|x'.$zz['ART_BARCODE1'].'x|x'.$qty.'x|x'.$zz['ART_UPRICE'].'x|x'.$crpl_id.'x|x'.$zz['qty']*$no_of_box.'x|x'.$zz['ART_UOM'].'x|x'.$box_no.'x|x'.$count_item.'x|x'.$item_no.'x|x'.$zz['qty'];

					array_push($data_rows,$data);
					
					$item_no++;
				}

			}
			else{

				$data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_ART_BARCODE1.'x|x'.$qty.'x|x'.$_price.'x|x'.$crpl_id.'x|x'.$_qty_items*$qty.'x|x'.$_ART_UOM.'x|x'.$box_no.'x|x'.$_count_item.'x|x'.$_item_no.'x|x'.$_qty_items;
				array_push($data_rows,$data);
				
			}
			$_item_no++;
		}//endforeach

	}
	$box_no++;
}//endforeach

$item_no = 1;
$xboxno = 0;


for($i = 0; $i < count($data_rows); $i++){
	$data = explode('x|x', $data_rows[$i]);

	$ART_CODE     = $data[0];
	$ART_DESC     = mb_strimwidth($data[1],0,39,'...');//$data[1];
	$ART_BARCODE1 = $data[2];
	$qty          = $data[3];
	$price        = (float)$data[4];
	$recid        = $data[5];
	$total_pcs    = (float)$data[6];
	$ART_UOM      = $data[7];
	$box_no       = $data[8];
	$count_item   = $data[9];
	$item_num     = $data[10];
	$conf         = $data[11];
	$amount       = (float)$price*(float)$total_pcs;
	$total_qty    += $total_pcs;
	$total_amount += $amount;

	if($Y < 226){

		if($item_num == $count_item){
			$border = 'B,R,L';
		}
		elseif($item_num < $count_item){
			$border = 'L,R';
		}
		elseif($item_num == 1){
			$border = 'T,R,L';
		}
		else{
			$border = 'L,B';
		}


		$pdf->SetFont('Dot','',10);
		$pdf->SetXY(5,$Y);
		if($box_no != $xboxno){

			$pdf->Cell(8,5,$box_no,$border,0,'C');
			$pdf->Cell(12,5,number_format($total_pcs),1,0,'C');
			$pdf->Cell(10,5,$ART_UOM,1,0,'C');
			$pdf->Cell(26,5,$ART_BARCODE1,1,0,'C');
			$pdf->Cell(27,5,$ART_CODE,1,0,'L');
			$pdf->SetFont('Dot','',8);
			$pdf->Cell(60,5,$ART_DESC,1,0,'L');
			$pdf->SetFont('Dot','',10);
			$pdf->Cell(11,5,$qty,$border,0,'C'); 
			$pdf->Cell(12,5,number_format($conf,2),1,0,'C');
			$pdf->Cell(15,5,$price,1,0,'C');
			$pdf->Cell(15,5,number_format($price*$total_pcs,2),1,0,'C');
			$pdf->Cell(11,5,$qty,$border,0,'C'); 
		}
		else{
			$pdf->Cell(8,5,'',$border,0,'C');
			$pdf->Cell(12,5,number_format($total_pcs),1,0,'C');
			$pdf->Cell(10,5,$ART_UOM,1,0,'C');
			$pdf->Cell(26,5,$ART_BARCODE1,1,0,'C');
			$pdf->Cell(27,5,$ART_CODE,1,0,'L');
			$pdf->SetFont('Dot','',8);
			$pdf->Cell(60,5,$ART_DESC,1,0,'L');
			$pdf->SetFont('Dot','',10);
			//$pdf->Cell(12,5,$qty,1,0,'C');
	        $pdf->Cell(11,5,'',$border,0,'L'); 
			$pdf->Cell(12,5,number_format($conf,2),1,0,'C');
			$pdf->Cell(15,5,$price,1,0,'C');
			$pdf->Cell(15,5,number_format($price*$total_pcs,2),1,0,'C');
			$pdf->Cell(11,5,'',$border,0,'L'); 
		}


		$xboxno = $box_no;

	}
	else{

		$pdf->AddPage();
		$pdf->SetAutoPageBreak(false);

		$Y = 11;

		// ITEMS TH
		$pdf->SetFillColor(239,225,131,1);
		$pdf->SetFont('Dot','',9);
		$pdf->SetXY(5,$Y);
		$pdf->SetFont('Dot','',7);
		$pdf->Cell(8,4,'ITEMS',1,0,'C');
		$pdf->SetFont('Dot','',9);
		$pdf->Cell(12,4,'QTY',1,0,'C');
		$pdf->Cell(10,4,'UNIT',1,0,'C');
		$pdf->Cell(26,4,'BARCODE',1,0,'C');
		$pdf->Cell(27,4,'STOCK NUMBER',1,0,'C');
		$pdf->Cell(60,4,'DESCRIPTION',1,0,'C');
		$pdf->SetFont('Dot','',7);
		$pdf->Cell(11,4,'QTY BOX',1,0,'C');
		$pdf->Cell(12,4,'QTY/BOX',1,0,'C');
		$pdf->SetFont('Dot','',8);
		$pdf->Cell(15,4,'UNIT PRICE',1,0,'C');
		$pdf->Cell(15,4,'AMOUNT',1,0,'C');
		$pdf->Cell(11,4,'QTY BOX',1,0,'C');
		// $pdf->Cell(18,4,'TOTAL',1,0,'C');

		//footer page number
		$pdf->SetY(-12);
		$pdf->SetFont('Dot','',10);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of SHIPPING DOC: '.$crpl_code,0,0,'C');

		//header page number
		$pdf->SetY(0);
		$pdf->SetX(142);
		$pdf->SetFont('Dot','',10);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of SHIPPING DOC: '.$crpl_code,0,0,'C');

		$Y = $Y + 4;

		$pdf->SetFont('Dot','',6);
		$pdf->SetXY(5,$Y);

		if($item_num == $count_item){
			$border = 'B,R,L';
		}
		elseif($item_num < $count_item){
			$border = 'L,R';
		}
		elseif($item_num == 1){
			$border = 'T,R,L';
		}
		else{
			$border = 'L,B';
		}


		$pdf->SetFont('Dot','',10);
		$pdf->SetXY(5,$Y);
		if($box_no != $xboxno){

			$pdf->Cell(8,5,$box_no,$border,0,'C');
			$pdf->Cell(12,5,number_format($total_pcs),1,0,'C');
			$pdf->Cell(10,5,$ART_UOM,1,0,'C');
			$pdf->Cell(26,5,$ART_BARCODE1,1,0,'C');
			$pdf->Cell(27,5,$ART_CODE,1,0,'L');
			$pdf->SetFont('Dot','',8);
			$pdf->Cell(60,5,$ART_DESC,1,0,'L');
			$pdf->SetFont('Dot','',10);
			//$pdf->Cell(12,5,$qty,1,0,'C');
	        $pdf->Cell(11,5,$qty,$border,0,'C'); 
			$pdf->Cell(12,5,number_format($conf,2),1,0,'C');
			$pdf->Cell(15,5,$price,1,0,'C');
			$pdf->Cell(15,5,number_format($price*$total_pcs,2),1,0,'C');
			$pdf->Cell(11,5,$qty,$border,0,'C'); 
		}
		else{
			$pdf->Cell(8,5,'',$border,0,'C');
			$pdf->Cell(12,5,number_format($total_pcs),1,0,'C');
			$pdf->Cell(10,5,$ART_UOM,1,0,'C');
			$pdf->Cell(26,5,$ART_BARCODE1,1,0,'C');
			$pdf->Cell(27,5,$ART_CODE,1,0,'L');
			$pdf->SetFont('Dot','',8);
			$pdf->Cell(60,5,$ART_DESC,1,0,'L');
			$pdf->SetFont('Dot','',10);
			//$pdf->Cell(12,5,$qty,1,0,'C');
	        $pdf->Cell(11,5,'',$border,0,'L'); 
			$pdf->Cell(12,5,number_format($conf,2),1,0,'C');
			$pdf->Cell(15,5,$price,1,0,'C');
			$pdf->Cell(15,5,number_format($price*$total_pcs,2),1,0,'C');
			$pdf->Cell(11,5,'',$border,0,'L'); 
		}


		$xboxno = $box_no;


	}

	$Y = $Y + 5;
	$item_no++;
}



$total_break_down_imp = $imp_box+$imp_sack+$imp_roll+$imp_bundle+$imp_plastic+$imp_pcs+$imp_ctns;
$total_break_down_loc = $loc_box+$loc_sack+$loc_roll+$loc_bundle+$loc_plastic+$loc_pcs+$loc_ctns;


if($Y < 186){
	$pdf->SetFont('Dot','',8);


	$pdf->SetXY(4,$Y);
	$pdf->Cell(10,5,'TOTAL: ',0,0,'L');
	$pdf->SetFont('Dot','',10);
	$pdf->SetXY(16,$Y);
	$pdf->Cell(18,5,number_format($total_qty),'B',0,'L');

	$pdf->SetXY(179,$Y);
	$pdf->Cell(10,5,'TOTAL: ',0,0,'L');
	$pdf->SetXY(190,$Y);
	$pdf->Cell(20,5,number_format($total_amount,2),'B',0,'L');

	$pdf->SetFont('Dot','',10);

	$Y = $Y + 6;

	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'REMARKS: ',0,0,'L');
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(155,4,$remarks.$me_remk,'B',0,'L');

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);
	$pdf->Cell(171,4,'','',0,'L');


	$pdf->SetFont('Dot','',10);

	$Y = $Y + 8;



	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'DRIVER\'S NAME: ',0,0,'L');
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,$driver,'B',0,'L');
	
	$pdf->SetXY(110,$Y);
	$pdf->Cell(16,5,'WAREHOUSE: ',0,0,'L');
	$pdf->SetXY(131,$Y);
	$pdf->Cell(60,4,$active_wshe,'B',0,'L');

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'HELPER\'S NAME: ',0,0,'L');
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,$helper_1.' / '.$helper_2,'B',0,'L');

	$pdf->SetXY(110,$Y);
	$pdf->Cell(16,5,'REF NO.: ',0,0,'L');
	$pdf->SetXY(131,$Y);
	$pdf->Cell(60,4,$refno,'B',0,'L');

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'PLATE NO: ',0,0,'L');
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,$plate_no,'B',0,'L');

	$pdf->SetXY(110,$Y);
	$pdf->Cell(16,5,'TRUCK TYPE.: ',0,0,'L');
	$pdf->SetXY(131,$Y);
	$pdf->Cell(60,4,$truck_type,'B',0,'L');

	$Y = $Y + 8;
	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'CHECKED BY: ',0,0,'L');
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,$chk_by,'B',0,'L');

	$pdf->SetX(110);
	$pdf->Cell(16,5,'APPROVED BY: ',0,0,'L');
	$pdf->SetX(135);
	$pdf->Cell(60,4,'','B',0,'L');

	$Y = $Y + 8;
	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'RECEIVED BY: ',0,0,'L');
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,'','B',0,'L');
	$Y = $Y + 4;
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,'NAME/SIGNATURE/DATE',0,0,'C');

	$Y = $Y + 8;
	$pdf->SetFont('Dot','',10);
	$pdf->SetXY(5,$Y);
	$pdf->Cell(23,4,'TOTAL PER UNIT',0,0,'L');

	$Y = $Y + 4;
	$pdf->SetXY(28,$Y);
	// $pdf->Cell(23,8,'IMPORTED',1,0,'C');
	$pdf->Cell(23,4,'BOX',1,0,'C');
	$pdf->Cell(23,4,'SACK',1,0,'C');
	$pdf->Cell(23,4,'ROLL',1,0,'C');
	$pdf->Cell(23,4,'BUNDLE',1,0,'C');
	$pdf->Cell(23,4,'PLASTIC',1,0,'C');
	$pdf->Cell(23,4,'PCS',1,0,'C');
	$pdf->Cell(23,4,'TOTAL',1,0,'C');
	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'IMPORTED',1,0,'C');
	$pdf->Cell(23,4,$imp_box,1,0,'C');
	$pdf->Cell(23,4,$imp_sack,1,0,'C');
	$pdf->Cell(23,4,$imp_roll,1,0,'C');
	$pdf->Cell(23,4,$imp_bundle,1,0,'C');
	$pdf->Cell(23,4,$imp_plastic,1,0,'C');
	$pdf->Cell(23,4,$imp_pcs,1,0,'C');
	$pdf->Cell(23,4,$total_break_down_imp,1,0,'C');
	// $Y = $Y + 4;
	// $pdf->SetXY(5,$Y);
	// $pdf->SetFont('Dot','',10);
	// $pdf->Cell(23,8,'LOCAL',1,0,'C');
	// $pdf->Cell(23,4,'BOX',1,0,'C');
	// $pdf->Cell(23,4,'SACK',1,0,'C');
	// $pdf->Cell(23,4,'ROLL',1,0,'C');
	// $pdf->Cell(23,4,'BUNDLE',1,0,'C');
	// $pdf->Cell(23,4,'PLASTIC',1,0,'C');
	// $pdf->Cell(23,4,'PCS',1,0,'C');
	// $pdf->Cell(23,4,'CTNS',1,0,'C');
	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'LOCAL',1,0,'C');
	$pdf->Cell(23,4,$loc_box,1,0,'C');
	$pdf->Cell(23,4,$loc_sack,1,0,'C');
	$pdf->Cell(23,4,$loc_roll,1,0,'C');
	$pdf->Cell(23,4,$loc_bundle,1,0,'C');
	$pdf->Cell(23,4,$loc_plastic,1,0,'C');
	$pdf->Cell(23,4,$loc_pcs,1,0,'C');
	$pdf->Cell(23,4,$total_break_down_loc,1,0,'C');


	//added


	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'STORE USE',1,0,'C');
	$pdf->Cell(23,4,$su_box,1,0,'C');
	$pdf->Cell(23,4,$su_sack,1,0,'C');
	$pdf->Cell(23,4,$su_roll,1,0,'C');
	$pdf->Cell(23,4,$su_bundle,1,0,'C');
	$pdf->Cell(23,4,$su_plastic,1,0,'C');
	$pdf->Cell(23,4,$su_pcs,1,0,'C');
	$pdf->Cell(23,4,$su_ctns,1,0,'C');

	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'HSY',1,0,'C');
	$pdf->Cell(23,4,$hsy_box,1,0,'C');
	$pdf->Cell(23,4,$hsy_sack,1,0,'C');
	$pdf->Cell(23,4,$hsy_roll,1,0,'C');
	$pdf->Cell(23,4,$hsy_bundle,1,0,'C');
	$pdf->Cell(23,4,$hsy_plastic,1,0,'C');
	$pdf->Cell(23,4,$hsy_pcs,1,0,'C');
	$pdf->Cell(23,4,$hsy_ctns,1,0,'C');

	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'LSG',1,0,'C');
	$pdf->Cell(23,4,$lsg_box,1,0,'C');
	$pdf->Cell(23,4,$lsg_sack,1,0,'C');
	$pdf->Cell(23,4,$lsg_roll,1,0,'C');
	$pdf->Cell(23,4,$lsg_bundle,1,0,'C');
	$pdf->Cell(23,4,$lsg_plastic,1,0,'C');
	$pdf->Cell(23,4,$lsg_pcs,1,0,'C');
	$pdf->Cell(23,4,$lsg_ctns,1,0,'C');

	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'HQE',1,0,'C');
	$pdf->Cell(23,4,$hqe_box,1,0,'C');
	$pdf->Cell(23,4,$hqe_sack,1,0,'C');
	$pdf->Cell(23,4,$hqe_roll,1,0,'C');
	$pdf->Cell(23,4,$hqe_bundle,1,0,'C');
	$pdf->Cell(23,4,$hqe_plastic,1,0,'C');
	$pdf->Cell(23,4,$hqe_pcs,1,0,'C');
	$pdf->Cell(23,4,$hqe_ctns,1,0,'C');

	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'AYCC',1,0,'C');
	$pdf->Cell(23,4,$aycc_box,1,0,'C');
	$pdf->Cell(23,4,$aycc_sack,1,0,'C');
	$pdf->Cell(23,4,$aycc_roll,1,0,'C');
	$pdf->Cell(23,4,$aycc_bundle,1,0,'C');
	$pdf->Cell(23,4,$aycc_plastic,1,0,'C');
	$pdf->Cell(23,4,$aycc_pcs,1,0,'C');
	$pdf->Cell(23,4,$aycc_ctns,1,0,'C');
	$pinakatotalver = $aycc_ctns +$hqe_ctns+$lsg_ctns+$hsy_ctns+$su_ctns+$total_break_down_loc+$total_break_down_imp;
	$pinakatotalhori = $total_box+$total_sack+$total_roll+$total_bundle+$total_plastic+$total_pcs_breakdown;
	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'TOTAL',1,0,'C');
	$pdf->Cell(23,4,$total_box,1,0,'C');
	$pdf->Cell(23,4,$total_sack,1,0,'C');
	$pdf->Cell(23,4,$total_roll,1,0,'C');
	$pdf->Cell(23,4,$total_bundle,1,0,'C');
	$pdf->Cell(23,4,$total_plastic,1,0,'C');
	$pdf->Cell(23,4,$total_pcs_breakdown,1,0,'C');
	$pdf->Cell(23,4,$pinakatotalhori ,1,0,'C');




}
else{

	$pdf->SetFont('Dot','',10);

	$pdf->SetXY(5,$Y);
	$pdf->Cell(10,5,'TOTAL: ',0,0,'L');
	$pdf->SetXY(18,$Y);
	$pdf->Cell(18,5,number_format($total_qty),'B',0,'L');

	$pdf->SetXY(175,$Y);
	$pdf->Cell(10,5,'TOTAL: ',0,0,'L');
	$pdf->SetXY(191,$Y);
	$pdf->Cell(18,5,number_format($total_amount,2),'B',0,'L');

	$Y = $Y + 8;
	$pdf->SetFont('Dot','',10);
	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'REMARKS: ',0,0,'L');
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(190,4,$remarks,'B',0,'L');

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);
	$pdf->Cell(206,4,'','',0,'L');

	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false);
	$Y = 11;


	$pdf->SetFont('Dot','',10);

	$Y = $Y + 8;

	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'DRIVER\'S NAME: ',0,0,'L');
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,$driver,'B',0,'L');
	
	$pdf->SetXY(110,$Y);
	$pdf->Cell(16,5,'WAREHOUSE: ',0,0,'L');
	$pdf->SetXY(131,$Y);
	$pdf->Cell(60,4,$active_wshe,'B',0,'L');
	
	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'HELPER\'S NAME: ',0,0,'L');
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,$helper_1.' / '.$helper_2,'B',0,'L');

	$pdf->SetXY(110,$Y);
	$pdf->Cell(16,5,'REF NO.: ',0,0,'L');
	$pdf->SetXY(131,$Y);
	$pdf->Cell(60,4,$refno,'B',0,'L');

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'PLATE NO: ',0,0,'L');
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,$plate_no,'B',0,'L');

	$pdf->SetXY(110,$Y);
	$pdf->Cell(16,5,'TRUCK TYPE.: ',0,0,'L');
	$pdf->SetXY(131,$Y);
	$pdf->Cell(60,4,$truck_type,'B',0,'L');

	$Y = $Y + 8;
	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'CHECKED BY: ',0,0,'L');
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,'','B',0,'L');

	$pdf->SetX(110);
	$pdf->Cell(16,5,'APPROVED BY: ',0,0,'L');
	$pdf->SetX(135);
	$pdf->Cell(60,4,'','B',0,'L');

	$Y = $Y + 8;
	$pdf->SetXY(5,$Y);
	$pdf->Cell(16,5,'RECEIVED BY: ',0,0,'L');
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,'','B',0,'L');
	$Y = $Y + 4;
	$pdf->SetXY(30,$Y);
	$pdf->Cell(60,4,'NAME/SIGNATURE/DATE',0,0,'C');


	$Y = $Y + 8;
	$pdf->SetFont('Dot','',10);
	$pdf->SetXY(5,$Y);
	$pdf->Cell(23,4,'TOTAL PER UNIT',0,0,'L');


	$Y = $Y + 4;
	$pdf->SetXY(28,$Y);
	// $pdf->Cell(23,8,'IMPORTED',1,0,'C');
	$pdf->Cell(23,4,'BOX',1,0,'C');
	$pdf->Cell(23,4,'SACK',1,0,'C');
	$pdf->Cell(23,4,'ROLL',1,0,'C');
	$pdf->Cell(23,4,'BUNDLE',1,0,'C');
	$pdf->Cell(23,4,'PLASTIC',1,0,'C');
	$pdf->Cell(23,4,'PCS',1,0,'C');
	$pdf->Cell(23,4,'TOTAL',1,0,'C');
	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'IMPORTED',1,0,'C');
	$pdf->Cell(23,4,$imp_box,1,0,'C');
	$pdf->Cell(23,4,$imp_sack,1,0,'C');
	$pdf->Cell(23,4,$imp_roll,1,0,'C');
	$pdf->Cell(23,4,$imp_bundle,1,0,'C');
	$pdf->Cell(23,4,$imp_plastic,1,0,'C');
	$pdf->Cell(23,4,$imp_pcs,1,0,'C');
	$pdf->Cell(23,4,$total_break_down_imp,1,0,'C');
	// $Y = $Y + 4;
	// $pdf->SetXY(5,$Y);
	// $pdf->SetFont('Dot','',10);
	// $pdf->Cell(23,8,'LOCAL',1,0,'C');
	// $pdf->Cell(23,4,'BOX',1,0,'C');
	// $pdf->Cell(23,4,'SACK',1,0,'C');
	// $pdf->Cell(23,4,'ROLL',1,0,'C');
	// $pdf->Cell(23,4,'BUNDLE',1,0,'C');
	// $pdf->Cell(23,4,'PLASTIC',1,0,'C');
	// $pdf->Cell(23,4,'PCS',1,0,'C');
	// $pdf->Cell(23,4,'CTNS',1,0,'C');
	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'LOCAL',1,0,'C');
	$pdf->Cell(23,4,$loc_box,1,0,'C');
	$pdf->Cell(23,4,$loc_sack,1,0,'C');
	$pdf->Cell(23,4,$loc_roll,1,0,'C');
	$pdf->Cell(23,4,$loc_bundle,1,0,'C');
	$pdf->Cell(23,4,$loc_plastic,1,0,'C');
	$pdf->Cell(23,4,$loc_pcs,1,0,'C');
	$pdf->Cell(23,4,$total_break_down_loc,1,0,'C');


	//added


	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'STORE USE',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');

	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'HSY',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');

	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'LSG',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');

	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'HQE',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');

	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'AYCC',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pinakatotalver = $aycc_ctns +$hqe_ctns+$lsg_ctns+$hsy_ctns+$su_ctns+$total_break_down_loc+$total_break_down_imp;
	$pinakatotalhori = $total_box+$total_sack+$total_roll+$total_bundle+$total_plastic+$total_pcs_breakdown;
	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'TOTAL',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,'',1,0,'C');
	$pdf->Cell(23,4,$pinakatotalhori,1,0,'C');


	//footer page number
	$pdf->SetY(-12);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of SHIPPING DOC: '.$crpl_code,0,0,'C');

	//header page number
	$pdf->SetY(0);
	$pdf->SetX(160);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of SHIPPING DOC: '.$crpl_code,0,0,'C');
}


$pdf->output('','SHIPPING-DOC-'.$crpl_code);
