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

$cuserlvl=$mylibzdb->mysys_userlvl();

$cuser_fullname = $mylibzdb->mysys_user_fullname();

$tmp_time = date("F j, Y, g:i A");
$tmp_date = new DateTime($tmp_time);
$print_time = $tmp_date->format('m/d/Y g:i:s A');
$mtkn_trans_rid = $request->getVar('mtkn_trans_rid');
$po_type   = 'T';


//Dto mo ilagay yung access.
$str = "
	SELECT 
	  a.`ref_no`,
	  a.`gr_date`,
	  a.`grtrx_no`,
	  a.`print_time`,
	  bb.`COMP_NAME`,
	  bb.`COMP_CODE`,
	  ee.`grtype_desc`,
	  ff.`wshe_code`,
	  usr.`myuserfulln` approver
	FROM
	(({$this->db_erp}.`trx_wshe_gr_hd` a
	JOIN {$this->db_erp}.`mst_company` bb
	ON (a.`comp_id` = bb.`recid`))
	JOIN {$this->db_erp}.`mst_wshe_gr_type` ee
	ON (a.`grtype_id` = ee.`recid`)
	JOIN {$this->db_erp}.`mst_wshe` ff
	ON (a.`wshe_id` = ff.`recid`)
	JOIN `myusers` usr
	ON (a.`apprvd_by` = usr.`myusername`)

	)
	WHERE
	sha2(concat(a.`recid`,'{$mpw_tkn}'),384) = '{$mtkn_trans_rid}'
";

$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
//var_dump($str); 
//die();
//$valid_id = '';
if($q->getNumRows() > 0){
	$r = $q->getRowArray();
	$refno = $r['ref_no'];
	$trx_no = $r['grtrx_no'];
	$COMP_NAME = $r['COMP_NAME'];
	$GRTYPE = $r['grtype_desc'];

	$grtmp_time = $r['gr_date'];
	$grwarehouse = $r['wshe_code'];
	$grtmp_date = new DateTime($grtmp_time);
	$gr_date = $grtmp_date->format("F j, Y, g:i A");
	$checked_by = $r['approver'];
	$tmp_time = $r['print_time'];
	
	if(!empty($tmp_time)){
		$tmp_date = new DateTime($tmp_time);
		$print_time = $tmp_date->format('m/d/Y g:i:s A');
	}

}
else{
	$data = array('message'=>"Transaction not found");
	echo view('errors/html/error_404',$data);
	die();
	
}
//ALAMIN KUNG TRADE NON TRADE
$str_q = "SELECT potrx_no,po_type FROM {$this->db_erp}.`trx_manrecs_po_hd` 
WHERE `potrx_no` = '{$refno}' AND `post_tag` = 'Y'";
$q7 = $mylibzdb->myoa_sql_exec($str_q,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

if($q7->getNumRows() > 0) { 
	$rw7        = $q7->getRowArray();
	$po_type   = $rw7['po_type'];
}


//WHEN USER IS USER THEN DOWNLOAD ONLY THEY ENCODE ELSE SA WILL ALL DOWNLOAD THE DATA.
$str_encduser="AND (aa.`muser` = '$cuser') AND !(aa.`p_flag` = 'Y' )";

if($cuserlvl=="S"){
	$str_encduser="";
}

$str = "SELECT
  a.`recid` hd_rid,
  a.`grtrx_no` hd_trx,
  a.`comp_id`,
  a.`gr_date`,
  a.`remk`,
  a.`hd_subtqty`,
  a.`hd_subtcost`,
  a.`hd_subtamt`,
  d.`grtype_desc`,
  a.`muser` hd_muser,
  a.`encd_date` hd_encd,
  a.`flag`,
  a.`p_flag`,
  a.`df_tag`,
  a.`post_tag`,
  b.`recid` dt_rid,
  b.`grhd_rid`,
  b.`grtrx_no` dt_trx,
  b.`mat_rid`,
  b.`mat_code`,
  b.`imat_code`,
  b.`ucost`,
  b.`tcost`,
  b.`uprice` __uprice,
  b.`tamt` __tamt,
  b.`qty`,
  b.`imat_qty`,
  b.`nremarks`,
  b.`muser` dt_muser,
  b.`encd` dt_encd,
  c.`ART_DESC`,
  e.`ART_UOM`,
  e.`ART_BARCODE1`,
  e.`ART_UCOST` __ucost,
  SUM(e.`ART_UCOST` * b.`imat_qty`) __tcost,
  e.`ART_DESC` __idesc 
FROM (((({$this->db_erp}.`trx_wshe_gr_hd` a
JOIN  {$this->db_erp}.`trx_wshe_gr_dt` b
ON (b.`grhd_rid` = a.`recid`))
JOIN  {$this->db_erp}.`mst_article` c
ON (c.`recid` = b.`mat_rid`))
JOIN {$this->db_erp}.`mst_wshe_gr_type` d
ON (a.`grtype_id` = d.`recid`))
JOIN  {$this->db_erp}.`mst_article` e
ON (b.`imat_rid` = e.`recid`))
WHERE (sha2(concat(a.`recid`,'{$mpw_tkn}'),384) = '$mtkn_trans_rid') 
GROUP BY b.`recid` ";
//AND !(a.`flag` = 'C' ) AND !(a.`df_tag`='D') AND !(a.`post_tag`='N') 
// var_dump($str); 
// die();

$q3 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
if($q3->getNumRows() == 0){ 
	$data = array('message'=>"No Data Found! <br/> Note: Maybe data already downloaded.");
	echo view('errors/html/error_404',$data);
	die();
}


$date = date("F j, Y, g:i A");
$r = $q3->getRow();

$pdf = new Mypdf();
$pdf->AliasNbPages();

//
$pdf->SetTitle('GR #: '.$trx_no);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);


$pdf->AddFont('Dot','','Calibri.php');
$pdf->SetFont('Dot','',10);

// header page

//$pdf->SetFont('Dot','',15);
//$pdf->SetTextColor(0,0,0);

//$pdf->Image(site_url().'public/assets/images/SMC-LOGO.png',5,5,40,0,'png');
$pdf->SetXY(5,10); 
$pdf->SetFont('Dot','',15);
$pdf->Cell(112,5,'SMARTLOOK MARKETING CORPORATION',1,0,'L'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(5,15,'1002-B Apolonia St. Mapulang Lupa, Valenzuela City',0,0,'L'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,22,'Tel. Nos.: (02) 961-8641 / 961-8526',0,0,'L'); 
$pdf->SetXY(5,22);  
//$pdf->SetFont('Dot','B',11);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(206,5,'GOODS RECEIPT',0,0,'C'); 


$pdf->SetXY(5,27);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(16.5,5,'COMPANY:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(50.5,5,$COMP_NAME,'B',0,'L');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(150,27);  
$pdf->Cell(15.5,5,'GR DATE:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(45,5,$gr_date,'B',0,'L'); 

/*
$pdf->SetXY(5,32);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(16.5,5,'SUPPLIER:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(50.5,5,$VEND_NAME,'B',0,'L');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(150,32);  
$pdf->Cell(15.5,5,'BRANCH:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(45,5,$BRNCH_NAME,'B',0,'L');
*/
$pdf->SetXY(5,37);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(16.5,5,'GR TYPE:',0,0,'L'); 
$pdf->SetFont('Dot','',10);

$pdf->Cell(50.5,5,$GRTYPE,'B',0,'L');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(150,37);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(15.5,5,'WAREHOUSE:',0,0,'L'); 
$pdf->SetFont('Dot','',10);

$pdf->SetXY(170,37);  
$pdf->Cell(40,5,$grwarehouse,'B',0,'C');  
$pdf->SetFont('Dot','',10);

/*$pdf->SetXY(150,32);  
$pdf->Cell(10.5,5,'USER:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(50.5,5,$cuser_fullname ,'B',0,'L');*/

//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Dot','',10);
$pdf->SetXY(5,45); 
$pdf->Cell(10,4,'NO',1,0,'C');

if($po_type != 'T'){
	$pdf->Cell(60,4,'PARTICULARS',1,0,'C'); 
	$pdf->Cell(25,4,'QTY/PCS',1,0,'C');
}
else{

$pdf->Cell(15,4,'QTY/PCS',1,0,'C'); 
$pdf->Cell(10,4,'UNIT',1,0,'C'); 
$pdf->Cell(25,4,'ITEMCODE',1,0,'C');
$pdf->Cell(25,4,'BOX CONTENT',1,0,'C');
$pdf->Cell(65,4,'ITEM NAME',1,0,'C');  
$pdf->Cell(20,4,'SRP',1,0,'C'); 
$pdf->Cell(34,4,'TOTAL AMOUNT',1,0,'C'); 
} 
 


//footer page number
$pdf->SetY(-15);
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of GR NO: '.$trx_no. '   Print by:'.$cuser_fullname. '   Print Time:'.$print_time,0,0,'C');
//$pdf->SetY(-15);
//$pdf->SetFont('Dot','',10);
//$pdf->Cell(0,16,,0,0,'C');
//$pdf->SetY(-15);
//$pdf->SetFont('Dot','',10);
//$pdf->Cell(0,22,,0,0,'C');

//header page number
$pdf->SetY(5);
$pdf->SetX(150);
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of GR NO: '.$trx_no,0,0,'C');

$Y = 49;
$total_qty = 0;
$box_no = 1;
$ntqty = 0;
$ntamt = 0;
$ntcost = 0;
$ntucost = 0;
$ntuprice = 0;
foreach($q3->getResult() as $row){

		
	$itemcode = $row->mat_code;
	$iitemcode = $row->imat_code;
	//$ucost = $row->ucost;
	//$tcost = $row->tcost;
	$uprice = $row->__uprice;
	$tamt = $row->__tamt;
	$qty = $row->qty;
	$iqty = $row->imat_qty;
	$iqty = $iqty * $qty;
	$unit=$row->ART_UOM;
	$bcode=$row->ART_BARCODE1;
	$itemname=$row->__idesc;
	$tamt = $iqty * $uprice;
	$particulars = $row->nremarks;
	
	
		if($Y < 226){
			$border = '1';
			
			$pdf->SetFont('Dot','',8);
			$pdf->SetXY(5,$Y); 
			/*if($_recid != $xrecid){*/
				$pdf->Cell(10,5,$box_no,$border,0,'C');
				if($po_type != 'T'){
					$pdf->Cell(60,5,$particulars,$border,0,'L'); 
					$pdf->Cell(25,5,$iqty,1,0,'C');
				}
				else{
				
				$pdf->Cell(15,5,$iqty,1,0,'C'); 
				$pdf->Cell(10,5,$unit,1,0,'C'); 
				$pdf->Cell(25,5,$itemcode,$border,0,'C');
				$pdf->Cell(25,5,$iitemcode,$border,0,'C');
				$pdf->Cell(65,5,$itemname,1,0,'C');  
				$pdf->Cell(20,5,number_format($uprice,2),$border,0,'C'); 
				$pdf->Cell(34,5,number_format($tamt,2),1,0,'C'); 
				}
				
			/*}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(40,5,'',$border,0,'C'); 
				$pdf->Cell(30,5,$_ART_CODE,1,0,'C'); 
				$pdf->Cell(30,5,$_ART_BARCODE1,1,0,'C'); 
				$pdf->Cell(18,5,$ART_SKU,1,0,'C'); 
				$pdf->Cell(17,5,'',$border,0,'C'); 
				$pdf->Cell(17,5,'',$border,0,'C'); 
				$pdf->Cell(20,5,$_total_pcs,1,0,'C');
				$pdf->Cell(20,5,$_cost,1,0,'C'); 
				$pdf->Cell(30,5,$_total_amt,1,0,'C');  
				$pdf->Cell(35,5,'',$border,0,'C'); 
			}
		*/

			
			
		}

		else{
			//2nd pahina
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(false);

			$Y = 15;

			//ITEMS TH
			$pdf->SetFillColor(239,225,131,1);
			$pdf->SetFont('Dot','',10);
			$pdf->SetXY(5,$Y); 
			$pdf->Cell(10,4,'NO',1,0,'C');
			if($po_type != 'T'){
				$pdf->Cell(60,4,'PARTICULARS',1,0,'C'); 
				$pdf->Cell(25,4,'QTY/PCS',1,0,'C');
			}
			else{
			$pdf->Cell(15,4,'QTY/PCS',1,0,'C'); 
			$pdf->Cell(10,4,'UNIT',1,0,'C'); 
			$pdf->Cell(25,4,'ITEMCODE',1,0,'C');
			$pdf->Cell(25,4,'BOX CONTENT',1,0,'C');
			$pdf->Cell(65,4,'ITEM NAME',1,0,'C');  
			$pdf->Cell(20,4,'SRP',1,0,'C'); 
			$pdf->Cell(34,4,'TOTAL AMOUNT',1,0,'C'); 
			} 

			//footer page numberScreenshot from 2023-04-12 14-07-03
			$pdf->SetY(-15);
			$pdf->SetFont('Dot','',10);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of GR NO: '.$trx_no. '   Print by:'.$cuser_fullname. '   Print Time:'.$print_time,0,0,'C');
			//$pdf->SetY(-15);
			//$pdf->SetFont('Dot','',10);
			//$pdf->Cell(0,16,,0,0,'C');
			//$pdf->SetY(-15);
			//$pdf->SetFont('Dot','',10);
			//$pdf->Cell(0,22,,0,0,'C');

			//header page number
			$pdf->SetY(5);
			$pdf->SetX(150);
			$pdf->SetFont('Dot','',10);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of GR NO: '.$trx_no,0,0,'C');



			$Y = $Y + 4;

			$pdf->SetFont('Dot','',9);
			$pdf->SetXY(5,$Y); 
			$border = '1';
			$pdf->SetFont('Dot','',9);
			$pdf->SetXY(5,$Y);
			$pdf->Cell(10,5,$box_no,$border,0,'C');

			if($po_type != 'T'){
				$pdf->Cell(60,5,$particulars,$border,0,'L'); 
				$pdf->Cell(25,5,$iqty,1,0,'C');
			}
			else{
				$pdf->Cell(15,5,$iqty,1,0,'C'); 
				$pdf->Cell(10,5,$unit,1,0,'C'); 
				$pdf->Cell(25,5,$itemcode,$border,0,'C');
				$pdf->Cell(25,5,$iitemcode,$border,0,'C');
				$pdf->Cell(65,5,$itemname,1,0,'C');  
				$pdf->Cell(20,5,number_format($uprice,2),$border,0,'C'); 
				$pdf->Cell(34,5,number_format($tamt,2),1,0,'C'); 
			}
		
			
		//$item_no++;
	}//endfor
	$Y = $Y + 5;
	$box_no++;
	$ntqty = $ntqty + $iqty;
	$ntucost = $ntucost + $uprice;
	$ntcost = $ntcost + $tamt;
	//$ntuprice = $ntuprice + $uprice;
	//$ntamt = $ntamt + $tprice;

	
	
}//endforeach

if($Y <= 220){
	$pdf->SetFont('Dot','',8);

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(10,5,'TOTAL: ',0,0,'L');

	if($po_type != 'T'){
		$pdf->SetXY(75,$Y); 
		$pdf->Cell(25,5,number_format($ntqty,2),1,0,'C'); 
	}
	else{
		$pdf->SetXY(15,$Y); 
		$pdf->Cell(15,5,$ntqty,1,0,'C');
		$pdf->SetXY(75,$Y); 

		$pdf->SetXY(175,$Y); 
		$pdf->Cell(34,5,number_format($ntcost,2),1,0,'C');
		
	}
	/*$Y = $Y + 5;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'REMARKS: ',0,0,'L'); 
	$pdf->Cell(250,4,'','B',0,'L'); 
*/
	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(203,4,'','',0,'L'); 


	$pdf->SetFont('Dot','',9);

	$Y = $Y + 10;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(60,4,$cuser_fullname,'',0,'C'); 
	$pdf->SetXY(5,$Y+6);  
	$pdf->Cell(60,5,'PREPARED BY: ','T',0,'C'); 
	
	$pdf->SetXY(76,$Y);  
	$pdf->Cell(60,4,$checked_by,'',0,'C'); 
	$pdf->SetXY(76,$Y+6);  
	$pdf->Cell(60,5,'CHECKED BY: ','T',0,'C'); 
	// $pdf->SetX(135); 
	// $pdf->Cell(60,4,'','',0,'L'); 

	$pdf->SetXY(150,$Y);  
	$pdf->Cell(60,4,'','',0,'C'); 
	$pdf->SetXY(150,$Y+6);   
	$pdf->Cell(60,5,'NOTED BY: ','T',0,'C'); 

	/*$Y = $Y + 8;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'RECEIVED BY: ',0,0,'L'); 
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'','B',0,'L'); 
	$Y = $Y + 4;
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'NAME/SIGNATURE/DATE',0,0,'C');*/
	

}
else{

	$pdf->SetFont('Dot','',8);

	$pdf->SetXY(160,$Y);  
	$pdf->Cell(10,5,'TOTAL: ',0,0,'L'); 
	if($po_type != 'T'){
		$pdf->SetXY(75,$Y); 
		$pdf->Cell(25,5,number_format($ntqty,2),1,0,'C'); 
	}
	else{
	$pdf->SetXY(176,$Y); 
	$pdf->Cell(17,5,$ntqty,1,0,'C');

	$pdf->SetXY(193,$Y); 
	$pdf->Cell(20,5,number_format($ntcost,2),1,0,'C'); 
	}

	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false);
	$Y = 11;

	/*$Y = $Y + 5;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,155,'REMARKS: ',0,0,'L'); 
	$pdf->Cell(187,4,'','B',0,'L'); 
*/
	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(232,4,'','',0,'L'); 


	$pdf->SetFont('Dot','',9);

	$Y = $Y + 10;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(60,4,$cuser_fullname,'',0,'C'); 
	$pdf->SetXY(5,$Y+6);  
	$pdf->Cell(60,5,'PREPARED BY: ','T',0,'C'); 
	
	$pdf->SetXY(76,$Y);  
	$pdf->Cell(60,4,$checked_by,'',0,'C'); 
	$pdf->SetXY(76,$Y+6);  
	$pdf->Cell(60,5,'CHECKED BY: ','T',0,'C'); 
	// $pdf->SetX(135); 
	// $pdf->Cell(60,4,'','',0,'L'); 

	$pdf->SetXY(150,$Y);  
	$pdf->Cell(60,4,'','',0,'C'); 
	$pdf->SetXY(150,$Y+6);   
	$pdf->Cell(60,5,'NOTED BY: ','T',0,'C'); 

	/*$Y = $Y + 8;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'NOTED BY: ',0,0,'L'); 
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'','B',0,'L'); 
	$Y = $Y + 4;
	$pdf->SetXY(30,$Y);  
	$pdf->Cell(60,4,'NAME/SIGNATURE/DATE',0,0,'C');*/

}
//if($cuserlvl != "S"){
$str = "update {$this->db_erp}.`trx_wshe_gr_hd` aa
		set aa.`p_flag`='Y',
		aa.`print_by`='$cuser_fullname',
		aa.`print_time` = now()
		WHERE (sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) = '$mtkn_trans_rid') AND !(aa.`flag` = 'C') AND !(aa.`df_tag`='D') AND !(aa.`post_tag`='N')"; 
$q3 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$mylibzdb->user_logs_activity_module($this->db_erp,'PRINT_MANRECS_GR',$trx_no,$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
//$mylibzdb->print_logs_manrecs_gr_module($this->db_erp,$trx_no,'',$cuser_fullname);
//}

//echo $str;
$pdf->output();


?>
