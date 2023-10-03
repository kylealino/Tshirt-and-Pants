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
$header = $request->getVar('header');
$xrecid = 0;

$str="";

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
		w.`wshe_code`,
		c.`trx`
	FROM
		{$this->db_erp}.`warehouse_shipdoc_hd` b
	JOIN
	warehouse_shipdoc_dt c
	ON
	b.`crpl_code` = c.`header`
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
		c.`header` = '$header'
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
$pdf->Cell(112,5,'GOLDENWIN EMPIRE MARKETING CORP.',1,0,'L'); 
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

$Y = 61;
$total_amount = 0;
$TQTY = 0;
$total_box=0;
$box_no = 1;
$xboxno = 0;
$count = 1;
$TAMOUNT = 0;
$item_num =1;
$current_fg =1;
$total_item_qty=0;



//pang kuha ng dalawang unique
$str = "
	SELECT 
	a.`fgreq_trxno`,b.`tpa_trxno`,c.`header`
	FROM 
	fgp_inv_rcv a 
	JOIN 
	trx_fgpack_req_dt b 
	ON 
	a.`fgreq_trxno` = b.`fgreq_trxno` 
	JOIN 
	warehouse_shipdoc_dt c
	ON
	a.`fgreq_trxno` = c.`trx` 
	WHERE
	c.`header` = '$header'
	GROUP BY fgreq_trxno
";

$q1 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q1->getRowArray();
$count_item = $q->getNumRows();

$data_rows = array();

//mismong total loop kung ilan yung unique = 2
foreach($q1->getResultArray() as $row){
	$fgreq_trxno = $row['fgreq_trxno'];
	$tpa_trxno = $row['tpa_trxno'];
	$count_rever=1;

	$str="
		SELECT
			b.`tpa_trxno`,
			b.`fgreq_trxno`,
			b.`qty_perpack` AS QTY,
			SUM(d.`box_count`) total_box_count,
			c.`ART_UOM` AS UNIT,
			c.`ART_BARCODE1` AS BARCODE,
			b.`mat_code` AS STOCK_NUMBER,
			c.`ART_DESC` AS DESCRIPTION,
			b.`req_pack` AS BOX_QTY,
			(b.`qty_perpack`) AS QTY_PER_BOX,
			c.`ART_UPRICE` AS UNIT_PRICE,
			((b.`qty_perpack` * b.`req_pack`) * c.`ART_UPRICE`) AS AMOUNT,
			b.`req_pack` AS BOX_QTY2

		FROM
			fgp_inv_rcv a
		JOIN
			trx_fgpack_req_dt b
		ON
			a.`fgreq_trxno` = b.`fgreq_trxno`
		JOIN
			mst_article c
		ON
			b.`mat_code` = c.`ART_CODE`
		JOIN
			warehouse_shipdoc_dt d
		ON
			a.`wob_barcde` = d.`wob_barcde`
		WHERE
			a.`tpa_trxno` = '$tpa_trxno' AND b.`fgreq_trxno` = '$fgreq_trxno' AND d.`header` = '$header'
		GROUP BY 
			b.`mat_code`, b.`fgreq_trxno`
		ORDER BY 
			b.`fgreq_trxno`
		
	";
	$q2 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	$count_item = $q2->getNumRows();
	$item_count =1;
	foreach($q2->getResultArray() as $row){
		
		$box_count =1;
		$fgreq_trxno = $row['fgreq_trxno'];
		$QTY = $row['QTY'];
		$UNIT = $row['UNIT'];
		$BARCODE = $row['BARCODE'];
		$STOCK_NUMBER = $row['STOCK_NUMBER'];
		$DESCRIPTION = $row['DESCRIPTION'];
		$BOX_QTY = $row['BOX_QTY'];
		$QTY_PER_BOX = $row['QTY_PER_BOX'];
		$UNIT_PRICE = $row['UNIT_PRICE'];
		$AMOUNT = $row['AMOUNT'];
		$BOX_QTY2 = $row['BOX_QTY2'];
		$total_box_count = $row['total_box_count'];
		$total_item_qty = $QTY * $total_box_count;
		$total_item_amount = $total_item_qty * $UNIT_PRICE;
		$TQTY += $total_item_qty;
		$TAMOUNT +=$total_item_amount;

		if($item_count == $count_item){
			$border = 'B,L,R';
		}else{
			$border = 'L,R';
		}

		if ($item_count == 1) {
			$pdf->SetFont('Dot','',10);
			$pdf->SetXY(5,$Y);
			$pdf->Cell(8,5,$current_fg,$border,0,'C');
			$pdf->Cell(12,5,number_format($total_item_qty),1,0,'C');
			$pdf->Cell(10,5,$UNIT,1,0,'C');
			$pdf->SetFont('Dot','',7.5);
			$pdf->Cell(26,5,$BARCODE,1,0,'C');
			$pdf->SetFont('Dot','',10);
			$pdf->Cell(27,5,$STOCK_NUMBER,1,0,'C');
			$pdf->SetFont('Dot','',8);
			$pdf->Cell(60,5,$DESCRIPTION,1,0,'L');
			$pdf->SetFont('Dot','',10);
			$pdf->Cell(11,5,$total_box_count,$border,0,'C'); 
			$pdf->Cell(12,5,number_format($QTY_PER_BOX,2),1,0,'C');
			$pdf->Cell(15,5,$UNIT_PRICE,1,0,'C');
			$pdf->Cell(15,5,number_format($total_item_amount,2),1,0,'C');
			$pdf->Cell(11,5,$total_box_count,$border,0,'C');
		}else{
			$pdf->SetFont('Dot','',10);
			$pdf->SetXY(5,$Y);
			$pdf->Cell(8,5,'',$border,0,'C');
			$pdf->Cell(12,5,number_format($total_item_qty),1,0,'C');
			$pdf->Cell(10,5,$UNIT,1,0,'C');
			$pdf->SetFont('Dot','',7.5);
			$pdf->Cell(26,5,$BARCODE,1,0,'C');
			$pdf->SetFont('Dot','',10);
			$pdf->Cell(27,5,$STOCK_NUMBER,1,0,'C');
			$pdf->SetFont('Dot','',8);
			$pdf->Cell(60,5,$DESCRIPTION,1,0,'L');
			$pdf->SetFont('Dot','',10);
			$pdf->Cell(11,5,'',$border,0,'C'); 
			$pdf->Cell(12,5,number_format($QTY_PER_BOX,2),1,0,'C');
			$pdf->Cell(15,5,$UNIT_PRICE,1,0,'C');
			$pdf->Cell(15,5,number_format($total_item_amount,2),1,0,'C');
			$pdf->Cell(11,5,'',$border,0,'C');

			$total_box = $total_box - $total_box_count;
		}

		$total_box += $total_box_count;
		$item_count++;
		$Y = $Y +5;

	}

	
	$current_fg++;
	$count_rever++;

}


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

$total_sack = 0;
$total_roll = 0;
$total_bundle = 0;
$total_plastic = 0;
$total_pcs_breakdown = 0;
$total_ctns = 0;
$total_req_pack =0;

$data_rows = array();
$xboxno = 0;


$total_break_down_imp = $imp_box+$imp_sack+$imp_roll+$imp_bundle+$imp_plastic+$imp_pcs+$imp_ctns;
$total_break_down_loc = $loc_box+$loc_sack+$loc_roll+$loc_bundle+$loc_plastic+$loc_pcs+$loc_ctns;



	$pdf->SetFont('Dot','',8);


	$pdf->SetXY(4,$Y);
	$pdf->Cell(10,5,'TOTAL: ',0,0,'L');
	$pdf->SetFont('Dot','',10);
	$pdf->SetXY(16,$Y);
	$pdf->Cell(18,5,number_format($TQTY),'B',0,'L');

	$pdf->SetXY(179,$Y);
	$pdf->Cell(10,5,'TOTAL: ',0,0,'L');
	$pdf->SetXY(190,$Y);
	$pdf->Cell(20,5,number_format($TAMOUNT,2),'B',0,'L');

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
	$pdf->Cell(60,4,$helper_1.' / '.'$helper_2','B',0,'L');

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

	$Y = $Y + 4;
	$pdf->SetXY(5,$Y);
	$pdf->SetFont('Dot','',10);
	$pdf->Cell(23,4,'LOCAL',1,0,'C');
	$pdf->Cell(23,4,$loc_box,1,0,'C');
	$pdf->Cell(23,4,$total_box,1,0,'C');
	$pdf->Cell(23,4,$loc_roll,1,0,'C');
	$pdf->Cell(23,4,$loc_bundle,1,0,'C');
	$pdf->Cell(23,4,$loc_plastic,1,0,'C');
	$pdf->Cell(23,4,$loc_pcs,1,0,'C');
	$pdf->Cell(23,4,$total_box,1,0,'C');


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
	$pdf->Cell(23,4,$total_roll,1,0,'C');
	$pdf->Cell(23,4,$total_box,1,0,'C');
	$pdf->Cell(23,4,$total_roll,1,0,'C');
	$pdf->Cell(23,4,$total_bundle,1,0,'C');
	$pdf->Cell(23,4,$total_plastic,1,0,'C');
	$pdf->Cell(23,4,$total_pcs_breakdown,1,0,'C');
	$pdf->Cell(23,4,$total_box ,1,0,'C');

$pdf->output('','SHIPPING-DOC-'.$crpl_code);
