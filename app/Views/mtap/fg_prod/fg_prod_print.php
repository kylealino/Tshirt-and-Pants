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
$fgreq_trxno = $request->getVar('fgreq_trxno');

$str="
	SELECT
		a.`pack_qty`,
		a.`req_date`,
		b.`branch_name`
	FROM
		trx_fgpack_req_hd a
	JOIN
		trx_tpa_hd b
	ON
		a.`tpa_trxno` = b.`tpa_trxno`
	WHERE
		fgreq_trxno = '$fgreq_trxno'
";
$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();
$pack_qty = $rw['pack_qty'];
$req_date = $rw['req_date'];
$branch_name = $rw['branch_name'];

$date = date("F j, Y, g:i A");


$pdf = new Mypdf();
$pdf->AliasNbPages();

//
$pdf->SetTitle('FGPR #: '.$fgreq_trxno);
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
$pdf->Cell(112,5,'GOLDEN WIN EMPIRE MARKETING CORPORATION',1,0,'L'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(5,15,'1002-B Apolonia St. Mapulang Lupa, Valenzuela City',0,0,'L'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,22,'Tel. Nos.: (02) 961-8641 / 961-8526',0,0,'L'); 
$pdf->SetXY(5,22);  
//$pdf->SetFont('Dot','B',11);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(206,5,'PRODUCTION REQUEST',0,0,'C'); 

$pdf->SetXY(5,27);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(16.5,5,'FGPR NO:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(50.5,5,$fgreq_trxno,'B',0,'L');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(150,27);  
$pdf->Cell(15.5,5,'REQUESTED BY:',0,0,'L'); 
$pdf->SetXY(170,27);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(40,5,'','B',0,'L'); 

$pdf->SetXY(5,37);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(16.5,5,'REQ DATE:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(50.5,5,$req_date,'B',0,'L');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(150,37);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(15.5,5,'RELEASE BY:',0,0,'L'); 
$pdf->SetFont('Dot','',10);

$pdf->SetXY(170,37);  
$pdf->Cell(40,5,'','B',0,'C');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(5,47);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(22.5,5,'NO. OF PACKS:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(44.5,5,$pack_qty,'B',0,'L');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(150,47);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(19.5,5,'Branch:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(40.5,5,$branch_name,'B',0,'L');  


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Dot','',10);
$pdf->SetXY(5,55); 
$pdf->Cell(10,4,'NO',1,0,'C');
$pdf->Cell(25,4,'ITEM CODE',1,0,'C'); 
$pdf->Cell(145,4,'DESCRIPTION',1,0,'C'); 
$pdf->Cell(25,4,'DEMAND QTY',1,0,'C');


//footer page number
$pdf->SetY(-15);
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of FGPR NO: '.$fgreq_trxno. '  Print Time:'.$print_time,0,0,'C');

//header page number
$pdf->SetY(5);
$pdf->SetX(150);
$pdf->SetFont('Dot','',10);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of FGPR NO: '.$fgreq_trxno,0,0,'C');


$Y = 59;
$total_qty = 0;
$box_no = 1;
$ntqty = 0;
$ntamt = 0;
$ntcost = 0;
$ntucost = 0;
$ntuprice = 0;


$str = "                        
	SELECT
		a.`mat_code`,
		b.`ART_DESC`,
		a.`qty_perpack`

	FROM
		trx_fgpack_req_dt a
	JOIN
		mst_article b
	ON
		a.`mat_code` = b.`ART_CODE`
	WHERE
		fgreq_trxno = '$fgreq_trxno'
";

$q3 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);



foreach($q3->getResult() as $row){

	$mat_code = $row->mat_code;
	$ART_DESC = $row->ART_DESC;
	$qty_perpack = $row->qty_perpack;
	$total_qty    += $qty_perpack;
	
	
		if($Y < 226){
			$border = '1';
			
			$pdf->SetFont('Dot','',8);
			$pdf->SetXY(5,$Y); 
			/*if($_recid != $xrecid){*/
				$pdf->Cell(10,5,$box_no,$border,0,'C');
				$pdf->Cell(25,5,$mat_code,1,0,'C'); 
				$pdf->Cell(145,5,$ART_DESC,1,0,'C'); 
				$pdf->Cell(25,5,$qty_perpack,$border,0,'C');
				
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

			$pdf->Cell(25,4,'ITEM CODE',1,0,'C'); 
			$pdf->Cell(145,4,'DESCRIPTION',1,0,'C'); 
			$pdf->Cell(25,4,'DEMAND QTY',1,0,'C');
			

			//footer page numberScreenshot from 2023-04-12 14-07-03
			$pdf->SetY(-15);
			$pdf->SetFont('Dot','',10);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of FGPR NO: '.$fgreq_trxno. '   Print by:'.$cuser_fullname. '   Print Time:'.$print_time,0,0,'C');
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
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of FGPR NO: '.$fgreq_trxno,0,0,'C');

			$Y = $Y + 4;

			$pdf->SetFont('Dot','',9);
			$pdf->SetXY(5,$Y); 
			$border = '1';
			$pdf->SetFont('Dot','',9);
			$pdf->SetXY(5,$Y);
			$pdf->Cell(10,5,$box_no,$border,0,'C');

			$pdf->Cell(25,5,$mat_code,1,0,'C'); 
			$pdf->Cell(145,5,$ART_DESC,1,0,'C'); 
			$pdf->Cell(25,5,$qty_perpack,$border,0,'C');

		}//endfor
		$Y = $Y + 5;
		$box_no++;


	
}//endforeach

$pdf->SetXY(180,$Y);
$pdf->Cell(10,5,'TOTAL: ',0,0,'L');
$pdf->SetXY(186,$Y);
$pdf->Cell(24,5,number_format($total_qty,2),'B',0,'C');

$pdf->SetFont('Dot','',10);
//echo $str;
$pdf->output();


?>
