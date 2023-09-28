<?php 
use App\Libraries\Fpdf\Mypdf;

$request      = \Config\Services::request();
$reponse      = \Config\Services::reponse();
$mydbname     = model('App\Models\MyDBNamesModel');
$mylibzdb     = model('App\Models\MyLibzDBModel');
$mylibzsys    = model('App\Models\MyLibzSysModel');
$memelibsys   = model('App\Models\Mymelibsys_model');
$mytrxrmpurch   = model('App\Models\MyRMPurchaseModel');
$mydataz      = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(1);

$cuser          = $mylibzdb->mysys_user();
//$cuser_fullname = $mylibzdb->mysys_user_fullname();
$mpw_tkn        = $mylibzdb->mpw_tkn();
$approved_fullname = '';

$rmap_trxno = $request->getVar('rmap_trxno');
$total_fg = $request->getVar('total_qty');

$str="

";


$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('RM-'.$rmap_trxno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(0,5,'GOLDEN WIN EMPIRE MARKETING CORPORATION',0,0,'C'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,15,'1002-B Apolonia St., Mapulang Lupa, Valenzuela City',0,0,'C'); 
$pdf->SetXY(5,11); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,22,'Tel# 961-8526, 961-8641M 931-7162',0,0,'C'); 

$pdf->SetXY(5,24);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,5,'Sewing Pick up',0,0,'C'); 


$pdf->SetXY(5,32);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(16.5,5,'Subcon',0,0,'L'); 


$pdf->SetFont('Arial','',8);
$pdf->SetXY(128,32);  
$pdf->Cell(34.5,5,'D.R.# NO:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,'','B',0,'L'); 

$pdf->SetXY(5,37);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(16.5,5,'Code:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(100.5,5,'','B',0,'L');  
$pdf->SetFont('Arial','',8);

$pdf->SetXY(128,37);  
$pdf->Cell(34.5,5,'Date:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,'','B',0,'L'); 

$pdf->SetXY(5,42);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(16.5,5,'Name:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(100.5,5,'','B',0,'L');  
$pdf->SetFont('Arial','',8);

$pdf->SetXY(128,42);  
$pdf->Cell(34.5,5,'Due Date:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,'','B',0,'L'); 

$pdf->SetXY(5,47);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(16.5,5,'Address:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(100.5,5,'','B',0,'L');  
$pdf->SetFont('Arial','',8);

$pdf->SetXY(128,47);  
$pdf->Cell(34.5,5,'Cut No.:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,'','B',0,'L'); 

$pdf->SetXY(5,52);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(16.5,5,'Stock No.:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(100.5,5,'','B',0,'L');  
$pdf->SetFont('Arial','',8);

$pdf->SetXY(128,52);  
$pdf->Cell(34.5,5,'Category:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,'BASIC','B',0,'L'); 

//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(5,62); 
$pdf->Cell(20,4,'ITEM NO',1,0,'C','true'); 
$pdf->Cell(35,4,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(81,4,'ITEM DESCRIPTION',1,0,'C','true'); 
$pdf->Cell(35,4,'ITEM QTY',1,0,'C','true'); 
$pdf->Cell(35,4,'ITEM UOM',1,0,'C','true'); 

$Y = 66;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$count=1;
$str="
	SELECT
	a.`rm_code` rm_code,
	b.`ART_DESC` ART_DESC,
	a.`rm_qty` rm_qty,
	b.`ART_UOM` ART_UOM
	FROM
	`trx_rmap_bom` a
	JOIN
	`mst_article` b
	ON 
	a.`rm_code` = b.`ART_CODE`
	WHERE 
	a.`rmap_trxno` = '$rmap_trxno'

";
$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getResultArray();
foreach ($rw as $row) {
	$rm_code = $row['rm_code'];
	$rm_qty = $row['rm_qty'];
	$ART_DESC = $row['ART_DESC'];
	$ART_UOM = $row['ART_UOM'];

	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(5,$Y);
	$pdf->Cell(20,4,$count,1,0,'C');
	$pdf->Cell(35,4,$rm_code,1,0,'C');
	$pdf->Cell(81,4,$ART_DESC,1,0,'C');
	$pdf->Cell(35,4,$rm_qty,1,0,'C');
	$pdf->Cell(35,4,$ART_UOM,1,0,'C');

	$Y = $Y + 4;
	$count = $count + 1;
}

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of RM: '.$rmap_trxno,0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(172);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of RM: '.$rmap_trxno,0,0,'R');


$pdf->output('','RM-'.$rmap_trxno);
