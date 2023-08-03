<?php 
use App\Libraries\Fpdf\Mypdf;

$request      = \Config\Services::request();
$reponse      = \Config\Services::reponse();
$mydbname     = model('App\Models\MyDBNamesModel');
$mylibzdb     = model('App\Models\MyLibzDBModel');
$mylibzsys    = model('App\Models\MyLibzSysModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(1);
$cuser          = $mylibzdb->mysys_user();
$mpw_tkn        = $mylibzdb->mpw_tkn();

$transfer_code = $request->getVar('transfer_code');

$str = "
	SELECT 
		a.`recid`,
		a.`header`,
		a.`encd`,
		b.`myuserfulln`,
		c.`plnt_code`,
		d.`wshe_code`,
		a.`from_wshe_sbin_name` AS `frm_bin`,
		a.`to_wshe_sbin_name` AS `to_bin`
	FROM
		{$this->db_erp}.`warehouse_rack_transfer_hd` a
	 JOIN
		{$this->db_erp}.`myusers` b
	ON
		a.`muser` = b.`myusername`
	 JOIN
		{$this->db_erp}.`mst_plant` c
	ON
		a.`plnt_id` = c.`recid`
	 JOIN
		{$this->db_erp}.`mst_wshe` d
	ON
		a.`wshe_id` = d.`recid`
	WHERE
		a.`header` = '{$transfer_code}'
";

$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id           = '';
$encd_fullname      = '';
$rack_transfer_code = '';
$plnt_code          = '';
$wshe_code          = '';
$frm_bin            = '';
$to_bin             = '';
$encd               = '';

if($q->getNumRows() > 0){
	$r                  = $q->getRowArray();
	$valid_id           = $r['recid'];
	$encd_fullname      = $r['myuserfulln'];
	$rack_transfer_code = $r['header'];
	$plnt_code          = $r['plnt_code'];
	$wshe_code          = $r['wshe_code'];
	$frm_bin            = $r['frm_bin'];
	$to_bin             = $r['to_bin'];
	$encd               = $r['encd'];
}
else{
	redirect('/warehouse-inv#rackbintrans-div');
}


$str = "
	SELECT 
		b.`stock_code`,
		b.`barcde`,
		b.`irb_barcde`,
		b.`srb_barcde`,
		b.`witb_barcde`,
		b.`wob_barcde`,
		b.`dmg_barcde`,
		b.`qty` AS `box_qty`,
		b.`convf`,
		b.`cbm`,
		b.`total_pcs`,
		b.`total_amount`,
		c.`ART_CODE`,
		c.`ART_DESC`,
		c.`ART_SKU`,
		c.`ART_UOM`,
		c.`ART_BARCODE1`,
		d.`wshe_grp`,
		e.`wshe_bin_name`
	FROM
		{$this->db_erp}.`warehouse_rack_transfer` b
	 JOIN
		{$this->db_erp}.`mst_article` c
	ON
		b.`mat_rid` = c.`recid`
	 JOIN
		{$this->db_erp}.`mst_wshe_grp` d
	ON
		b.`to_wshe_grp_id` = d.`recid`
	 JOIN
		{$this->db_erp}.`mst_wshe_bin` e
	ON
		b.`to_wshe_sbin_id` = e.`recid`
	WHERE
		b.`rack_transfer_hd` = '{$rack_transfer_code}'
";

$qq = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);



$pdf = new Mypdf('L');
$pdf->AliasNbPages();
$pdf->SetTitle('CRPL-'.$rack_transfer_code);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);

$pdf->AddFont('Dot','','Calibri.php');

$pdf->SetFont('Dot','',10);


// header page

$pdf->SetFont('Dot','',15);
$pdf->SetTextColor(0,0,0);


// header page

$pdf->SetFont('Dot','',14);
$pdf->SetTextColor(0,0,0);

//$pdf->Image(_XMYAPP_PATH_.'/public/assets/images/SMC-LOGO.png',5,1,40,0,'png');
$pdf->SetXY(47,5);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,5,'1002-B Apolonia St. Mapulang Lupa, Valenzuela City',0,0,'L'); 

$pdf->SetXY(47,9);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,5,'Tel. Nos.: (02) 961-8641 / 961-8526',0,0,'L'); 

$pdf->SetXY(5,18);  
$pdf->SetFont('Dot','',11);
$pdf->Cell(270,5,'CHECKER\'S REPORT PROOF LIST/TRANSFER DOCUMENTS',0,0,'C'); 


$pdf->SetXY(5,27);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(16.5,5,'CRPL NO:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(50.5,5,$rack_transfer_code,'B',0,'L');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(215,27);  
$pdf->Cell(10.5,5,'DATE:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(48.5,5,$encd,'B',0,'L'); 


$pdf->SetXY(5,32);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(16.5,5,'FROM:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(50.5,5,$plnt_code.' - '.$wshe_code,'B',0,'L');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(215,32);  
$pdf->Cell(10.5,5,'FROM:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(48.5,5,$frm_bin,'B',0,'L'); 


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Dot','',8);
$pdf->SetXY(5,42); 
$pdf->Cell(5,4,'#',1,0,'C'); 
$pdf->Cell(35,4,'STOCK CODE',1,0,'C'); 
$pdf->Cell(28,4,'ITEM CODE',1,0,'C'); 
$pdf->Cell(30,4,'ITEM BARCODE',1,0,'C'); 
$pdf->Cell(18,4,'PACKAGING',1,0,'C'); 
$pdf->Cell(12,4,'QTY',1,0,'C'); 
$pdf->Cell(12,4,'QTY/BOX',1,0,'C'); 
$pdf->Cell(16,4,'TOTAL PCS',1,0,'C'); 
$pdf->Cell(35,4,'BOX BARCODE',1,0,'C'); 
$pdf->Cell(42,4,'TO WAREHOUSE GROUP(RACK)',1,0,'C'); 
$pdf->Cell(38,4,'TO STORAGE BIN(BIN)',1,0,'C'); 


//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of CRPL: '.$rack_transfer_code,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(217);
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of CRPL: '.$rack_transfer_code,0,0,'C');


$ctr = 1;
$item_no = 1;

$Y = 46;
$_stock_code = '';
$_barcde = '';

$arr_len = count($qq->getResultArray());

$total_qty = 0;

foreach($qq->getResultArray() as $row){

	$stock_code    = $row['stock_code'];
	$ART_CODE      = $row['ART_CODE'];
	$ART_BARCODE1  = $row['ART_BARCODE1'];
	$ART_SKU       = $row['ART_SKU'];
	$ART_UOM       = $row['ART_UOM'];
	$box_qty       = $row['box_qty'];
	$convf         = $row['convf'];
	$total_pcs     = $row['total_pcs'];
	$barcde        = $row['barcde'];
	$wshe_grp      = $row['wshe_grp'];	
	$wshe_bin_name = $row['wshe_bin_name'];


	if($Y < 166){

	

		if($ctr == 1){
			if($ctr == $arr_len){
				$pdf->SetFont('Dot','',10);
				$pdf->SetXY(5,$Y); 
				$pdf->Cell(5,6,$item_no,1,0,'L'); 
				$pdf->Cell(35,6,$stock_code,1,0,'L'); 
				$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
				$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
				$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
				$pdf->Cell(12,6,$box_qty,1,0,'C'); 
				$pdf->Cell(12,6,$convf,1,0,'C'); 
				$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
				$pdf->Cell(35,6,$barcde,1,0,'L'); 
				$pdf->Cell(42,6,$wshe_grp,1,0,'L'); 
				$pdf->Cell(38,6,$wshe_bin_name,1,0,'L'); 

				
			}
			else{

				$pdf->SetFont('Dot','',10);
				$pdf->SetXY(5,$Y); 
				$pdf->Cell(5,6,$item_no,'L,R,T',0,'L'); 
				$pdf->Cell(35,6,$stock_code,'L,R,T',0,'L'); 
				$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
				$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
				$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
				$pdf->Cell(12,6,$box_qty,'L,R,T',0,'C'); 
				$pdf->Cell(12,6,$convf,1,0,'C'); 
				$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
				$pdf->Cell(35,6,$barcde,'L,R,T',0,'L'); 
				$pdf->Cell(42,6,$wshe_grp,'L,R,T',0,'L'); 
				$pdf->Cell(38,6,$wshe_bin_name,'L,R,T',0,'L'); 

			}
			$total_qty = $total_qty + $box_qty;
		}
		else{

			if($ctr == $arr_len){

				if($barcde == $_barcde){

					$pdf->SetFont('Dot','',10);
					$pdf->SetXY(5,$Y); 
					$pdf->Cell(5,6,'','L,R,B',0,'L'); 
					$pdf->Cell(35,6,'','B',0,'L'); 
					$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
					$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
					$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
					$pdf->Cell(12,6,'','B',0,'C'); 
					$pdf->Cell(12,6,$convf,1,0,'C'); 
					$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
					$pdf->Cell(35,6,'','L,R,B',0,'L'); 
					$pdf->Cell(42,6,'','R,B',0,'L'); 
					$pdf->Cell(38,6,'','R,B',0,'L'); 

				}
				else{

					$item_no = $item_no + 1;

					$pdf->SetFont('Dot','',10);
					$pdf->SetXY(5,$Y); 
					$pdf->Cell(5,6,$item_no,1,0,'L'); 
					$pdf->Cell(35,6,$stock_code,1,0,'L'); 
					$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
					$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
					$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
					$pdf->Cell(12,6,$box_qty,1,0,'C'); 
					$pdf->Cell(12,6,$convf,1,0,'C'); 
					$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
					$pdf->Cell(35,6,$barcde,1,0,'L'); 
					$pdf->Cell(42,6,$wshe_grp,1,0,'L'); 
					$pdf->Cell(38,6,$wshe_bin_name,1,0,'L'); 

					$total_qty = $total_qty + $box_qty;
				}

			}
			else{

				if($barcde == $_barcde){

					$pdf->SetFont('Dot','',10);
					$pdf->SetXY(5,$Y); 
					$pdf->Cell(5,6,'','L,R',0,'L'); 
					$pdf->Cell(35,6,'','',0,'L'); 
					$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
					$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
					$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
					$pdf->Cell(12,6,'','',0,'C'); 
					$pdf->Cell(12,6,$convf,1,0,'C'); 
					$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
					$pdf->Cell(35,6,'','L,R',0,'L'); 
					$pdf->Cell(42,6,'','R',0,'L'); 
					$pdf->Cell(38,6,'','R',0,'L'); 

				}
				else{

					$item_no = $item_no + 1;

					$pdf->SetFont('Dot','',10);
					$pdf->SetXY(5,$Y); 
					$pdf->Cell(5,6,$item_no,'L,R,T',0,'L'); 
					$pdf->Cell(35,6,$stock_code,'L,R,T',0,'L'); 
					$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
					$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
					$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
					$pdf->Cell(12,6,$box_qty,'L,R,T',0,'C'); 
					$pdf->Cell(12,6,$convf,1,0,'C'); 
					$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
					$pdf->Cell(35,6,$barcde,'L,R,T',0,'L'); 
					$pdf->Cell(42,6,$wshe_grp,'L,R,T',0,'L'); 
					$pdf->Cell(38,6,$wshe_bin_name,'L,R,T',0,'L'); 

					$total_qty = $total_qty + $box_qty;
				}

			}

		}

	}
	else{

		$pdf->AddPage();
		$pdf->SetAutoPageBreak(false);

		$Y = 11;

		// //ITEMS TH
		// $pdf->SetFillColor(239,225,131,1);
		// $pdf->SetFont('Dot','',8);
		// $pdf->SetXY(5,$Y); 
		// $pdf->Cell(5,4,'#',1,0,'C'); 
		// $pdf->Cell(35,4,'STOCK CODE',1,0,'C'); 
		// $pdf->Cell(28,4,'ITEM CODE',1,0,'C'); 
		// $pdf->Cell(30,4,'ITEM BARCODE',1,0,'C'); 
		// $pdf->Cell(18,4,'PACKAGING',1,0,'C'); 
		// $pdf->Cell(12,4,'QTY',1,0,'C'); 
		// $pdf->Cell(12,4,'QTY/BOX',1,0,'C'); 
		// $pdf->Cell(16,4,'TOTAL PCS',1,0,'C'); 
		// $pdf->Cell(35,4,'BOX BARCODE',1,0,'C'); 
		// $pdf->Cell(42,4,'TO WAREHOUSE GROUP(RACK)',1,0,'C'); 
		// $pdf->Cell(38,4,'TO STORAGE BIN(BIN)',1,0,'C'); 

		//footer page number
		$pdf->SetY(-12);
		$pdf->SetFont('Dot','',10);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of CRPL: '.$rack_transfer_code,0,0,'C');

		//header page number
		$pdf->SetY(0);
		$pdf->SetX(217);
		$pdf->SetFont('Dot','',10);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of CRPL: '.$rack_transfer_code,0,0,'C');


		$Y = $Y + 4;

		if($ctr == 1){
			$pdf->SetFont('Dot','',10);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(5,6,$item_no,'L,R,T',0,'L'); 
			$pdf->Cell(35,6,$stock_code,'L,R,T',0,'L'); 
			$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
			$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
			$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
			$pdf->Cell(12,6,$box_qty,'L,R,T',0,'C'); 
			$pdf->Cell(12,6,$convf,1,0,'C'); 
			$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
			$pdf->Cell(35,6,$barcde,'L,R,T',0,'L'); 
			$pdf->Cell(42,6,$wshe_grp,'L,R,T',0,'L'); 
			$pdf->Cell(38,6,$wshe_bin_name,'L,R,T',0,'L'); 
		}
		else{

			if($ctr == $arr_len){

				if($barcde == $_barcde){

					$pdf->SetFont('Dot','',10);
					$pdf->SetXY(5,$Y); 
					$pdf->Cell(5,6,'','L,R,B',0,'L'); 
					$pdf->Cell(35,6,'','B',0,'L'); 
					$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
					$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
					$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
					$pdf->Cell(12,6,'','B',0,'C'); 
					$pdf->Cell(12,6,$convf,1,0,'C'); 
					$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
					$pdf->Cell(35,6,'','L,R,B',0,'L'); 
					$pdf->Cell(42,6,'','R,B',0,'L'); 
					$pdf->Cell(38,6,'','R,B',0,'L'); 

				}
				else{

					$item_no = $item_no + 1;

					$pdf->SetFont('Dot','',10);
					$pdf->SetXY(5,$Y); 
					$pdf->Cell(5,6,$item_no,'L,R,T',0,'L'); 
					$pdf->Cell(35,6,$stock_code,'L,R,T',0,'L'); 
					$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
					$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
					$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
					$pdf->Cell(12,6,$box_qty,'L,R,T',0,'C'); 
					$pdf->Cell(12,6,$convf,1,0,'C'); 
					$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
					$pdf->Cell(35,6,$barcde,'L,R,T',0,'L'); 
					$pdf->Cell(42,6,$wshe_grp,'L,R,T',0,'L'); 
					$pdf->Cell(38,6,$wshe_bin_name,'L,R,T',0,'L'); 

					$total_qty = $total_qty + $box_qty;
				}

			}
			else{

				if($barcde == $_barcde){

					$pdf->SetFont('Dot','',10);
					$pdf->SetXY(5,$Y); 
					$pdf->Cell(5,6,'','L,R',0,'L'); 
					$pdf->Cell(35,6,'','',0,'L'); 
					$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
					$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
					$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
					$pdf->Cell(12,6,'','',0,'C'); 
					$pdf->Cell(12,6,$convf,1,0,'C'); 
					$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
					$pdf->Cell(35,6,'','L,R',0,'L'); 
					$pdf->Cell(42,6,'','R',0,'L'); 
					$pdf->Cell(38,6,'','R',0,'L'); 

				}
				else{

					$item_no = $item_no + 1;

					$pdf->SetFont('Dot','',10);
					$pdf->SetXY(5,$Y); 
					$pdf->Cell(5,6,$item_no,'L,R,T',0,'L'); 
					$pdf->Cell(35,6,$stock_code,'L,R,T',0,'L'); 
					$pdf->Cell(28,6,$ART_CODE,1,0,'L'); 
					$pdf->Cell(30,6,$ART_BARCODE1,1,0,'L'); 
					$pdf->Cell(18,6,$ART_SKU,1,0,'L'); 
					$pdf->Cell(12,6,$box_qty,'L,R,T',0,'C'); 
					$pdf->Cell(12,6,$convf,1,0,'C'); 
					$pdf->Cell(16,6,$total_pcs,1,0,'C'); 
					$pdf->Cell(35,6,$barcde,'L,R,T',0,'L'); 
					$pdf->Cell(42,6,$wshe_grp,'L,R,T',0,'L'); 
					$pdf->Cell(38,6,$wshe_bin_name,'L,R,T',0,'L'); 

					$total_qty = $total_qty + $box_qty;
				}

			}

		}

	}



	$ctr++;
	$Y = $Y + 6;
	$_stock_code = $row['stock_code'];
	$_barcde = $row['barcde'];
}

if($Y < 161){
	$pdf->SetFont('Dot','',10);

	$pdf->SetXY(109,$Y);  
	$pdf->Cell(12,5,'TOTAL: ',0,0,'L'); 
	$pdf->SetXY(120.5,$Y);  
	$pdf->Cell(13,5,number_format($total_qty),'B',0,'C'); 

	$Y = $Y + 5;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'REMARKS: ',0,0,'L'); 
	$pdf->Cell(254,4,'','B',0,'L'); 

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(270,4,'','B',0,'L'); 


	$pdf->SetFont('Dot','',10);

	$Y = $Y + 8;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(60,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->Cell(60,5,'ENCODED BY',0,0,'C'); 

	$pdf->SetX(110.5);  
	$pdf->Cell(60,5,'CHECKED BY ','T',0,'C'); 
	// $pdf->SetX(135); 
	// $pdf->Cell(60,4,'','',0,'L'); 

	$pdf->SetX(213);  
	$pdf->Cell(60,5,'APPROVED BY ','T',0,'C'); 

	$Y = $Y + 13;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'RECEIVED BY: ',0,0,'L'); 
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'','B',0,'L'); 
	$Y = $Y + 4;
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'NAME/SIGNATURE/DATE',0,0,'C');
	

}
else{

	$pdf->SetFont('Dot','',10);

	$pdf->SetXY(109,$Y);  
	$pdf->Cell(12,5,'TOTAL: ',0,0,'L'); 
	$pdf->SetXY(120.5,$Y);  
	$pdf->Cell(13,5,number_format($total_qty),'',0,'C'); 

	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false);
	$Y = 11;

	$Y = $Y + 5;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'REMARKS: ',0,0,'L'); 
	$pdf->Cell(254,4,'','B',0,'L'); 

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(270,4,'','B',0,'L'); 


	$pdf->SetFont('Dot','',10);

	$Y = $Y + 8;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(60,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->Cell(60,5,'ENCODED BY',0,0,'C'); 

	$pdf->SetX(110.5);  
	$pdf->Cell(60,5,'CHECKED BY ','T',0,'C'); 
	// $pdf->SetX(135); 
	// $pdf->Cell(60,4,'','',0,'L'); 

	$pdf->SetX(213);  
	$pdf->Cell(60,5,'APPROVED BY ','T',0,'C'); 

	$Y = $Y + 13;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'RECEIVED BY: ',0,0,'L'); 
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'','B',0,'L'); 
	$Y = $Y + 4;
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'NAME/SIGNATURE/DATE',0,0,'C');
	

}



$pdf->Output('I','CRPL - '.$rack_transfer_code);


?>
