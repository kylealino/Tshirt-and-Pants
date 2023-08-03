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

$mtkn_trans_rid = $request->getVar('mtkn_trans_rid');

$str = "
	SELECT 
		a.*,
		b.`plnt_code`,
		c.`wshe_code`
	FROM
	{$this->db_erp}.`warehouse_gi_hd` a
	 JOIN
		{$this->db_erp}.`mst_plant` b
	ON
		a.`plnt_id` = b.`recid`
	 JOIN
		{$this->db_erp}.`mst_wshe` c
	ON
		a.`wshe_id` = c.`recid`
	WHERE
	sha2(concat(a.`recid`,'{$mpw_tkn}'),384) = '{$mtkn_trans_rid}'
";

$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
if($q->getNumRows() > 0){
	$r = $q->getRowArray();

	$valid_id = $r['header'];
	$plnt_code = $r['plnt_code'];
	$wshe_code = $r['wshe_code'];
	$type = $r['type'];
}
else{
	redirect('mydboard/warehouse_inventory?tab=7');
}

//CHECK IF THERE IS ALREADY A DR

/*$str = "
	SELECT *
	FROM
	{$this->db_erp}.`trx_jo_dr`
	WHERE
	`JO_CODE` = '{$valid_id}'
";

$q2 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);*/

// if($q2->num_rows() > 0){
	//GET ITEMS, JOIN in str_jo_dr AND GENERATE

	$str = "
	
		SELECT 
			aa.*,
			SUM(aa.`qty`) totqty,
			cc.`ART_CODE` AS `mat_code`,
			cc.`ART_DESC` AS `mat_desc`,
			cc.`ART_SKU`,
			cc.`ART_BARCODE1`,
			bb.`header` AS `GI_CODE`,
			bb.`recid` AS `gi_id`,
			bb.`remarks`,
			bb.`encd` AS `DATE_CREATED`,
			dd.`plnt_code` AS `plnt_code`,
			ee.`wshe_code` AS `wshe_code`
		FROM
			{$this->db_erp}.`warehouse_gi_dt` aa
		LEFT JOIN
			{$this->db_erp}.`warehouse_gi_hd` bb
		ON
			aa.`trx` = bb.`header`
		LEFT JOIN
			{$this->db_erp}.`mst_article` cc
		ON
			aa.`mat_rid` = cc.`recid`
		LEFT JOIN
			{$this->db_erp}.`mst_plant` dd
		ON
			bb.`plnt_id` = dd.`recid`
		LEFT JOIN
			{$this->db_erp}.`mst_wshe` ee
		ON
			bb.`wshe_id` = ee.`recid`
		WHERE
			aa.`trx` = '{$valid_id}'
		GROUP BY
			aa.`stock_code`,aa.`barcde`
		ORDER BY
			aa.`stock_code`

	"; 
	//dinagdagan ko yung GROUP BY aa.`barcde` .
	$q3 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

// }

$r = $q3->getRow();

$pdf = new Mypdf('L');
$pdf->AliasNbPages();
$pdf->SetTitle('CRPL -'. $r->GI_CODE);
$pdf->AddPage('L');
$pdf->SetAutoPageBreak(false);


$pdf->AddFont('Dot','','Calibri.php');

$pdf->SetFont('Dot','',10);

// header page

$pdf->SetFont('Dot','',15);
$pdf->SetTextColor(0,0,0);


// header page

$pdf->SetFont('Dot','',15);
$pdf->SetTextColor(0,0,0);

// $pdf->Image(_XMYAPP_PATH_.'/public/assets/images/SMC-LOGO.png',5,1,40,0,'png');
$pdf->SetXY(47,5);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,5,'1002-B Apolonia St. Mapulang Lupa, Valenzuela City',0,0,'L'); 

$pdf->SetXY(47,9);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,5,'Tel. Nos.: (02) 961-8641 / 961-8526',0,0,'L'); 

$pdf->SetXY(5,18);  
$pdf->SetFont('Dot','',11);
$pdf->Cell(206,5,'CHECKER\'S REPORT PROOF LIST/GI DOCUMENTS',0,0,'C'); 


$pdf->SetXY(5,27);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(16.5,5,'CRPL NO:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(50.5,5,$r->GI_CODE,'B',0,'L');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(148,27);  
$pdf->Cell(10.5,5,'DATE:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(48.5,5,$r->encd,'B',0,'L'); 


$pdf->SetXY(5,32);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(16.5,5,'FROM:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(50.5,5,$plnt_code .'-'. $wshe_code,'B',0,'L');  
$pdf->SetFont('Dot','',10);

//ITEMS TH
// $pdf->SetFillColor(239,225,131,1);
// $pdf->SetFont('Dot','',10);
// $pdf->SetXY(5,42); 
// $pdf->Cell(10,4,'#',1,0,'C'); 
// $pdf->Cell(30,4,'STOCK CODE',1,0,'C'); 
// $pdf->Cell(30,4,'ITEM CODE',1,0,'C'); 
// $pdf->Cell(30,4,'ITEM BARCODE',1,0,'C'); 
// $pdf->Cell(18,4,'PACKAGING',1,0,'C'); 
// $pdf->Cell(17,4,'QTY',1,0,'C'); 
// $pdf->Cell(17,4,'QTY/BOX',1,0,'C'); 
// $pdf->Cell(16,4,'TOTAL PCS',1,0,'C'); 
// $pdf->Cell(35,4,'BOX BARCODE',1,0,'C');

$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Dot','',8);
$pdf->SetXY(5,42); 
$pdf->Cell(10,4,'#',1,0,'C'); 
$pdf->Cell(40,4,'STOCK CODE',1,0,'C'); 
$pdf->Cell(30,4,'ITEM CODE',1,0,'C'); 
$pdf->Cell(40,4,'BOX CONTENT',1,0,'C');
$pdf->Cell(30,4,'ITEM BARCODE',1,0,'C'); 
$pdf->Cell(20,4,'PACKAGING',1,0,'C'); 
$pdf->Cell(20,4,'QTY',1,0,'C'); 
$pdf->Cell(20,4,'QTY/BOX',1,0,'C'); 
$pdf->Cell(20,4,'TOTAL PCS',1,0,'C'); 
// $pdf->Cell(27,4,'TOTAL AMOUNT',1,0,'C'); 
$pdf->Cell(35,4,'BOX BARCODE',1,0,'C'); 



//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of GI FORM: '.$r->GI_CODE,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(145);
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of GI FORM: '.$r->GI_CODE,0,0,'C');

$Y = 46;
$total_qty = 0;
$box_no = 1;
foreach($q3->getResult() as $row){

		
	$no_of_box = $row->totqty;
	$total_qty += $row->totqty;

	$gi_id = $row->recid;
	$gi_hd_id = $row->gi_id;
	$stock_code = $row->stock_code;
	$ART_CODE = $row->mat_code;
	$ART_DESC = $row->mat_desc;
	$ART_BARCODE1 = $row->ART_BARCODE1;
	$ART_SKU = $row->ART_SKU;
	$qty = $row->totqty;
	$convf = $row->convf;
	$total_pcs = $row->total_pcs;
	$barcde = $row->barcde;
	$price = '';
	//Tinaggal ko pala yung SUM(a.`qty`) binago ko.

	if($no_of_box > 1){
		$barcde = '';
	}

	if($barcde == $row->witb_barcde && $no_of_box == 1){
		$barcde = $row->barcde;
	}


	$str = "
		SELECT 
			a.`qty` AS `total_pcs`,
			a.*,
			b.`ART_CODE`,
			b.`ART_DESC`,
			b.`ART_UPRICE`,
			b.`ART_BARCODE1`
		FROM 
			{$this->db_erp}.`trx_gi_item` a
		LEFT JOIN
			{$this->db_erp}.`mst_article` b
		ON
			a.`mat_rid` = b.`recid`
		LEFT JOIN
			{$this->db_erp}.`trx_gi_dt` c
		ON
			a.`dt_id` = c.`recid`
		LEFT JOIN
			{$this->db_erp}.`trx_gi_hd` d
		ON
			c.`gi_id` = d.`recid`
		WHERE
			c.`stock_code` = '{$stock_code}'
		AND
			d.`recid` = {$gi_hd_id}
		GROUP BY 
			a.`mat_rid`,c.`stock_code`
	";

	$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->getNumRows() > 0){
		$_convf = 0;
		foreach($q->getResultArray() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty = $row['qty'];
			$_ART_DESC = $row['ART_DESC'];
			$_ART_BARCODE1 = $row['ART_BARCODE1'];
			$_price = $row['ART_UPRICE'];
			$_gi_id = $row['dt_id'];
			$_convf += $row['qty'];
			$_total_pcs = $row['total_pcs'];

			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_ART_BARCODE1.'x|x'.number_format($_qty).'x|x'.$_price.'x|x'.$_gi_id.'x|x'.$stock_code.'x|x'.$_total_pcs.'x|x'.$barcde.'x|x'.$convf;
			array_push($item, $item_data);
		}
	}
	else{
		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$ART_BARCODE1.'x|x'.number_format($qty).'x|x'.$price.'x|x'.$gi_id.'x|x'.$stock_code.'x|x'.$total_pcs.'x|x'.$barcde.'x|x'.$convf;
		array_push($item, $item_data);
	}

	//no of box is qty, qty/unit is qty of item
	$xrecid = 0;
	$item_no = 1;

	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_ART_BARCODE1 = $data[2];
		$_qty = $data[3];
		// $_convf = $data[4];
		// $_price = $data[5];
		$_recid = $data[5];
		$_stock_code = $data[6];
		$_total_pcs = $data[7];
		$_barcde = $data[8];
		$_convf = $data[9];


		// echo $_ART_CODE.' | '.$_qty .'<br>';

		if($Y < 200){

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

		
			$pdf->SetFont('Dot','',9);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){

				// $pdf->Cell(10,5,$box_no,$border,0,'C'); 
				// $pdf->Cell(30,5,$_stock_code,$border,0,'L'); 
				// $pdf->Cell(30,5,$_ART_CODE,1,0,'L'); 
				// $pdf->Cell(30,5,$_ART_BARCODE1,1,0,'C'); 
				// $pdf->Cell(18,5,$ART_SKU,1,0,'C'); 
				// $pdf->Cell(17,5,$no_of_box,$border,0,'C'); 
				// $pdf->Cell(17,5,$_convf,$border,0,'C'); 
				// $pdf->Cell(16,5,$_total_pcs,1,0,'C'); 
				// $pdf->Cell(35,5,$_barcde,$border,0,'C'); 

				$pdf->Cell(10,6,$box_no,$border,0,'L'); 
				$pdf->Cell(40,6,$_stock_code,$border,0,'L'); 
				$pdf->Cell(30,6,$ART_CODE,$border,0,'L'); 
				$pdf->Cell(40,6,$_ART_CODE,$border,0,'L');
				$pdf->Cell(30,6,$_ART_BARCODE1,$border,0,'L'); 
				$pdf->Cell(20,6,$ART_SKU,$border,0,'L'); 
				$pdf->Cell(20,6,$no_of_box,$border,0,'C'); 
				$pdf->Cell(20,6,$_convf,$border,0,'C'); 

				$pdf->Cell(20,6,$_total_pcs,$border,0,'C');
				// $pdf->Cell(27,6,$_total_amt,$border,0,'C'); 
				$pdf->Cell(35,6,$_barcde,$border,0,'L');  
			}
			else{
				// $pdf->Cell(10,5,'',$border,0,'C'); 
				// $pdf->Cell(30,5,'',$border,0,'C'); 
				// $pdf->Cell(30,5,$_ART_CODE,1,0,'C'); 
				// $pdf->Cell(30,5,$_ART_BARCODE1,1,0,'C'); 
				// $pdf->Cell(18,5,$ART_SKU,1,0,'C'); 
				// $pdf->Cell(17,5,'',$border,0,'C'); 
				// $pdf->Cell(17,5,'',$border,0,'C'); 
				// $pdf->Cell(16,5,$_total_pcs,1,0,'C'); 
				// $pdf->Cell(35,5,'',$border,0,'C'); 

				$pdf->Cell(10,6,'',$border,0,'L'); 
				$pdf->Cell(40,6,'',$border,0,'L'); 
				$pdf->Cell(30,6,'',$border,0,'L'); 
				$pdf->Cell(40,6,$_ART_CODE,$border,0,'L');
				$pdf->Cell(30,6,$_ART_BARCODE1,$border,0,'L'); 
				$pdf->Cell(20,6,'',$border,0,'L'); 
				$pdf->Cell(20,6,'',$border,0,'C'); 
				$pdf->Cell(20,6,'',$border,0,'C'); 

				$pdf->Cell(20,6,$_total_pcs,$border,0,'C');
				// $pdf->Cell(27,6,$_total_amt,$border,0,'C'); 
				$pdf->Cell(35,6,'',$border,0,'L'); 
			}
		

			$xrecid = $_recid;
			
		}

		else{

			$pdf->AddPage('L');
			$pdf->SetAutoPageBreak(false);

			$Y = 11;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Dot','',6);
			$pdf->SetXY(5,$Y); 
			// $pdf->Cell(10,4,'#',1,0,'C'); 
			// $pdf->Cell(30,4,'STOCK CODE',1,0,'C'); 
			// $pdf->Cell(30,4,'ITEM CODE',1,0,'C'); 
			// $pdf->Cell(30,4,'ITEM BARCODE',1,0,'C'); 
			// $pdf->Cell(18,4,'PACKAGING',1,0,'C'); 
			// $pdf->Cell(17,4,'QTY',1,0,'C'); 
			// $pdf->Cell(17,4,'QTY/BOX',1,0,'C'); 
			// $pdf->Cell(16,4,'TOTAL PCS',1,0,'C'); 
			// $pdf->Cell(35,4,'BOX BARCODE',1,0,'C'); 
			// $pdf->Cell(18,4,'TOTAL',1,0,'C'); 

			$pdf->Cell(10,4,'#',1,0,'C'); 
			$pdf->Cell(40,4,'STOCK CODE',1,0,'C'); 
			$pdf->Cell(30,4,'ITEM CODE',1,0,'C'); 
			$pdf->Cell(40,4,'BOX CONTENT',1,0,'C');
			$pdf->Cell(30,4,'ITEM BARCODE',1,0,'C'); 
			$pdf->Cell(20,4,'PACKAGING',1,0,'C'); 
			$pdf->Cell(20,4,'QTY',1,0,'C'); 
			$pdf->Cell(20,4,'QTY/BOX',1,0,'C'); 
			$pdf->Cell(20,4,'TOTAL PCS',1,0,'C'); 
			// $pdf->Cell(27,4,'TOTAL AMOUNT',1,0,'C'); 
			$pdf->Cell(35,4,'BOX BARCODE',1,0,'C'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Dot','',10);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of gi FORM: '.$r->GI_CODE,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(145);
			$pdf->SetFont('Dot','',10);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of gi FORM: '.$r->GI_CODE,0,0,'C');



			$Y = $Y + 4;

			$pdf->SetFont('Dot','',9);
			$pdf->SetXY(5,$Y); 

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

		
			$pdf->SetFont('Dot','',9);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){

				// $pdf->Cell(10,5,$box_no,$border,0,'C'); 
				// $pdf->Cell(30,5,$_stock_code,$border,0,'C'); 
				// $pdf->Cell(30,5,$_ART_CODE,1,0,'C'); 
				// $pdf->Cell(30,5,$_ART_BARCODE1,1,0,'C'); 
				// $pdf->Cell(18,5,$ART_SKU,1,0,'C'); 
				// $pdf->Cell(17,5,$no_of_box,$border,0,'C'); 
				// $pdf->Cell(17,5,$_convf,$border,0,'C'); 
				// $pdf->Cell(16,5,$_total_pcs,1,0,'C'); 
				// $pdf->Cell(35,5,$_barcde,$border,0,'C'); 

				$pdf->Cell(10,6,$box_no,$border,0,'L'); 
				$pdf->Cell(40,6,$_stock_code,$border,0,'L'); 
				$pdf->Cell(30,6,$ART_CODE,$border,0,'L'); 
				$pdf->Cell(40,6,$_ART_CODE,$border,0,'L');
				$pdf->Cell(30,6,$_ART_BARCODE1,$border,0,'L'); 
				$pdf->Cell(20,6,$ART_SKU,$border,0,'L'); 
				$pdf->Cell(20,6,$no_of_box,$border,0,'C'); 
				$pdf->Cell(20,6,$_convf,$border,0,'C'); 

				$pdf->Cell(20,6,$_total_pcs,$border,0,'C');
				// $pdf->Cell(27,6,$_total_amt,$border,0,'C'); 
				$pdf->Cell(35,6,$_barcde,$border,0,'L');  
			}
			else{
				// $pdf->Cell(10,5,'',$border,0,'C'); 
				// $pdf->Cell(30,5,'',$border,0,'C'); 
				// $pdf->Cell(30,5,$_ART_CODE,1,0,'C'); 
				// $pdf->Cell(30,5,$_ART_BARCODE1,1,0,'C'); 
				// $pdf->Cell(18,5,$ART_SKU,1,0,'C'); 
				// $pdf->Cell(17,5,'',$border,0,'C'); 
				// $pdf->Cell(17,5,'',$border,0,'C'); 
				// $pdf->Cell(16,5,$_total_pcs,1,0,'C'); 
				// $pdf->Cell(35,5,'',$border,0,'C'); 

				$pdf->Cell(10,6,'',$border,0,'L'); 
				$pdf->Cell(40,6,'',$border,0,'L'); 
				$pdf->Cell(30,6,'',$border,0,'L'); 
				$pdf->Cell(40,6,$_ART_CODE,$border,0,'L');
				$pdf->Cell(30,6,$_ART_BARCODE1,$border,0,'L'); 
				$pdf->Cell(20,6,'',$border,0,'L'); 
				$pdf->Cell(20,6,'',$border,0,'C'); 
				$pdf->Cell(20,6,'',$border,0,'C'); 

				$pdf->Cell(20,6,$_total_pcs,$border,0,'C');
				// $pdf->Cell(27,6,$_total_amt,$border,0,'C'); 
				$pdf->Cell(35,6,'',$border,0,'L'); 
			}
		

			$xrecid = $_recid;


		}

		/*else{

			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);

			$Y = 11;

			// ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Dot','',6);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(12,4,'ITEMS',1,0,'C'); 
			$pdf->Cell(17,4,'QTY',1,0,'C'); 
			$pdf->Cell(17,4,'QTY/UNIT',1,0,'C'); 
			$pdf->Cell(28,4,'BARCODE',1,0,'C'); 
			$pdf->Cell(28,4,'STOCK NUMBER',1,0,'C'); 
			$pdf->Cell(71,4,'DESCRIPTION',1,0,'C'); 
			$pdf->Cell(15,4,'UNIT PRICE',1,0,'C'); 
			$pdf->Cell(15,4,'AMOUNT',1,0,'C'); 
			// $pdf->Cell(18,4,'TOTAL',1,0,'C'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Dot','',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of SHIPPING DOC: '.$crpl_code,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(160);
			$pdf->SetFont('Dot','',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of SHIPPING DOC: '.$crpl_code,0,0,'C');

			$Y = $Y + 4;

			$pdf->SetFont('Dot','',6);
			$pdf->SetXY(5,$Y); 

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

		
			$pdf->SetFont('Dot','',6);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){

				$pdf->Cell(12,5,$box_no,$border,0,'C'); 
				$pdf->Cell(17,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(17,5,number_format($_qty),1,0,'C'); 
				$pdf->Cell(28,5,$_ART_BARCODE1,1,0,'C'); 
				$pdf->Cell(28,5,$_ART_CODE,1,0,'C'); 
				$pdf->Cell(71,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C');
			}
			else{
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(17,5,'',$border,0,'C'); 
				$pdf->Cell(17,5,number_format($_qty),1,0,'C'); 
				$pdf->Cell(28,5,$_ART_BARCODE1,1,0,'C'); 
				$pdf->Cell(28,5,$_ART_CODE,1,0,'C'); 
				$pdf->Cell(71,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C');
			}
			

			$xrecid = $_recid;
		}*/

		$Y = $Y + 5;
		$item_no++;
	}//endfor
	$box_no++;


	// if($Y < 261){

	// 	$pdf->SetFont('Dot','',7);
	// 	$pdf->SetXY(5,$Y); 
	// 	$pdf->Cell(10,5,'',1,0,'C'); 
	// 	$pdf->Cell(30,5,$row->stock_code,1,0,'C'); 
	// 	$pdf->Cell(30,5,$row->mat_code,1,0,'C'); 
	// 	$pdf->Cell(30,5,$row->ART_BARCODE1,1,0,'C'); 
	// 	$pdf->Cell(18,5,'PACKAGING',1,0,'C'); 
	// 	$pdf->Cell(17,5,'QTY',1,0,'C'); 
	// 	$pdf->Cell(17,5,'QTY/BOX',1,0,'C'); 
	// 	$pdf->Cell(16,5,'TOTAL PCS',1,0,'C'); 
	// 	$pdf->Cell(35,5,'BOX BARCODE',1,0,'C'); 

	// 	// $pdf->Cell(50,6,$row->stock_code,1,0,'L'); 
	// 	// $pdf->Cell(30,6,$row->mat_code,1,0,'L'); 
	// 	// $pdf->Cell(71,6,$row->mat_desc,1,0,'L'); 
	// 	// $pdf->Cell(25,6,$row->totqty,1,0,'L'); 
	// 	// $pdf->Cell(30,6,$row->ART_SKU,1,0,'L'); 
	// 	//$pdf->Cell(34,6,$row->barcde,1,0,'L');
	// }
	// else{
	// 	$pdf->AddPage();
	// 	$pdf->SetAutoPageBreak(false);

	// 	$Y = 11;
	// 	//ITEMS TH
	// 	$pdf->SetFillColor(239,225,131,1);
	// 	$pdf->SetFont('Dot','',10);
	// 	$pdf->SetXY(5,11); 
	// 	$pdf->Cell(15,6,'QTY',1,0,'C'); 
	// 	$pdf->Cell(30,6,'CONVF',1,0,'C'); 
	// 	$pdf->Cell(32,6,'TOT PCS',1,0,'C'); 
	// 	$pdf->Cell(40,6,'ITEM CODE',1,0,'C'); 
	// 	$pdf->Cell(89,6,'ITEM DESC',1,0,'C');  

	// 	$Y = $Y + 6;
	// 	$pdf->SetFont('Dot','',8);
	// 	$pdf->SetXY(5,$Y); 
	// 	$pdf->Cell(50,6,$row->stock_code,1,0,'L'); 
	// 	$pdf->Cell(50,6,$row->mat_code,1,0,'L'); 
	// 	$pdf->Cell(35,6,$row->totqty,1,0,'L'); 
	// 	$pdf->Cell(35,6,$row->convf,1,0,'L'); 
	// 	$pdf->Cell(36,6,$row->total_pcs,1,0,'L'); 
	// 	//$pdf->Cell(34,6,$row->barcde,1,0,'L');

	// 	//footer page number
	// 	$pdf->SetY(-12);
	// 	$pdf->SetFont('Dot','',8);
	// 	$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of Warehouse gi Prooflist: '.$r->GI_CODE,0,0,'C');

	// 	//header page number
	// 	$pdf->SetY(4);
	// 	$pdf->SetX(177);
	// 	$pdf->SetFont('Dot','',8);
	// 	$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of Warehouse gi Prooflist: '.$r->GI_CODE,0,0,'C');
	// }
	// $Y = $Y + 6;
	
}//endforeach

if($Y <= 216){
	$pdf->SetFont('Dot','',9);

	$pdf->SetXY(158,$Y);  
	$pdf->Cell(10,5,'TOTAL: ',0,0,'L'); 
	$pdf->SetXY(178,$Y); 
	$pdf->Cell(13,5,number_format($total_qty),'',0,'C'); 

	$Y = $Y + 5;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'REMARKS: ',0,0,'L'); 
	$pdf->Cell(187,4,$r->remarks,'B',0,'L'); 

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(203,4,'','',0,'L'); 


	$pdf->SetFont('Dot','',9);

	$Y = $Y + 10;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(60,5,'ENCODED BY: ','T',0,'C'); 
	// $pdf->SetXY(30,$Y);  
	// $pdf->Cell(60,4,'','',0,'L'); 

	$pdf->SetX(76.5);  
	$pdf->Cell(60,5,'CHECKED BY: ','T',0,'C'); 
	// $pdf->SetX(135); 
	// $pdf->Cell(60,4,'','',0,'L'); 

	$pdf->SetX(148);  
	$pdf->Cell(60,5,'APPROVED BY: ','T',0,'C'); 

	$Y = $Y + 8;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'RECEIVED BY: ',0,0,'L'); 
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'','B',0,'L'); 
	$Y = $Y + 4;
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'NAME/SIGNATURE/DATE',0,0,'C');
	

}
else{

	$pdf->SetFont('Dot','',9);

	$pdf->SetXY(158,$Y);  
	$pdf->Cell(10,5,'TOTAL: ',0,0,'L'); 
	$pdf->SetXY(178,$Y); 
	$pdf->Cell(13,5,number_format($total_qty),'',0,'C'); 

	$pdf->AddPage('L');
	$pdf->SetAutoPageBreak(false);
	$Y = 11;

	$Y = $Y + 5;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'REMARKS: ',0,0,'L'); 
	$pdf->Cell(187,4,$r->remarks,'B',0,'L'); 

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(203,4,'','',0,'L'); 


	$pdf->SetFont('Dot','',9);

	$Y = $Y + 10;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(60,5,'ENCODED BY: ','T',0,'C'); 
	// $pdf->SetXY(30,$Y);  
	// $pdf->Cell(60,4,'','',0,'L'); 

	$pdf->SetX(76.5);  
	$pdf->Cell(60,5,'CHECKED BY: ','T',0,'C'); 
	// $pdf->SetX(135); 
	// $pdf->Cell(60,4,'','',0,'L'); 

	$pdf->SetX(148);  
	$pdf->Cell(60,5,'APPROVED BY: ','T',0,'C'); 

	$Y = $Y + 8;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'RECEIVED BY: ',0,0,'L'); 
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'','B',0,'L'); 
	$Y = $Y + 4;
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'NAME/SIGNATURE/DATE',0,0,'C');

}

$pdf->output();


?>