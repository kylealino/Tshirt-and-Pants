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
$mtkn_potr      = $request->getVar('mtkn_potr');
$PO_CTRLNO		= $request->getVar('mtkn_pono');
$approved_fullname = '';

$str = "
	UPDATE
		{$this->db_erp}.`gw_rm_po_hd`
	SET
		`print_flag` = '2'
	WHERE
		SHA2(CONCAT(`recid`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;


$str = "
		
	INSERT INTO {$this->db_erp}.`print_logs` (
		`CTRL_NO`,
		`TYPE`,
		`MFLAG`,
		`MUSER`,
		`ENCD`,
		`REF_TBL`
	) 
	VALUES(
	    '{$PO_CTRLNO}',
	    'GWRMPO',
	    '1',
	    '{$cuser}',
	    now(),
	    'GWPO_HD'
  	)
";

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$str = "
    select 
	aa.*,
    bb.VEND_NAME __vend_name,
    cc.CUST_NAME __vends_name,
    CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ff.`myuserfulln`,
    ff.`myuserfulln` __user_approved
    FROM `gw_rm_po_hd` aa 
    JOIN `mst_vendor` bb 
    ON 
    aa.vend_rid = bb.recid
    JOIN `mst_customer` cc 
    ON 
    aa.vends_rid = cc.recid
    JOIN `mst_po_class` dd 
    ON 
    aa.`po_cls_id` = dd.recid
    JOIN myusers ff
     ON 
     aa.`muser` = ff.`myusername`
    where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_potr' 
";

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->getNumRows() > 0){
	$r             = $q->getRowArray();
	$valid_id      = $r['recid'];
	$encd_fullname = $r['myuserfulln'];
	$rmks     		= $r['rmks'];
	$print_flag    = $r['print_flag'];
	$approved_fullname = $r['__user_approved'];

}

$approved_fullname1 = '';
$approved_fullname2 = '';


$str="
	SELECT 
		b.`VEND_NAME`,
		a.`vends_add`,
		a.`vends_cont_pers`,
		a.`vends_cp_desig`,
		a.`vends_cp_contno`
	FROM 
		`gw_rm_po_hd` a
	JOIN
		`mst_vendor` b
	ON 
		a.`vend_rid` = b.`recid`
	WHERE 
		po_sysctrlno = '$PO_CTRLNO'
";
$qv = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $qv->getRowArray();
$VEND_NAME = $rw['VEND_NAME'];
$vends_add = $rw['vends_add'];
$vends_cont_pers = $rw['vends_cont_pers'];
$vends_cp_desig = $rw['vends_cp_desig'];
$vends_cp_contno = $rw['vends_cp_contno'];

$approved_fullname1 = '';
$approved_fullname2 = '';

$box = array();

$pdf = new Mypdf();
$pdf->AliasNbPages();
$pdf->SetTitle('PO-'.$r['po_sysctrlno']);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial','',10);

// header page

$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0,0,0);

$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','B',15);
$pdf->Cell(200,5,'GOLDEN WIN EMPIRE MARKETING CORPORATION',0,0,'C'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(200,15,'1002-B Apolonia St., Mapulang Lupa, Valenzuela City',0,0,'C'); 
$pdf->SetXY(5,11); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(200,22,'Tel# 961-8526, 961-8641M 931-7162',0,0,'C'); 

$pdf->SetXY(5,24);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'PURCHASE ORDER',0,0,'C'); 


$pdf->SetXY(5,32);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(25.5,5,'Vendor',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(90.5,5,$VEND_NAME,'B',0,'L'); 

$pdf->SetFont('Arial','',8);
$pdf->SetXY(128,32);  
$pdf->Cell(34.5,5,'PO NO:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,$PO_CTRLNO,'B',0,'L'); 

$pdf->SetXY(5,37);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(25.5,5,'Address:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(90.5,5,$vends_add,'B',0,'L');  

$pdf->SetFont('Arial','',8);
$pdf->SetXY(128,37);  
$pdf->Cell(34.5,5,'User:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,$cuser,'B',0,'L'); 

$pdf->SetXY(5,42);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(25.5,5,'Contact Person:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(90.5,5,$vends_cont_pers,'B',0,'L');  
$pdf->SetFont('Arial','',8);

$pdf->SetXY(128,42);  
$pdf->Cell(34.5,5,'Entry Date:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,$mylibzsys->mydate_mmddyyyy($r['trx_date']),'B',0,'L'); 

$pdf->SetXY(5,47);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(25.5,5,'Designation:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(90.5,5,$vends_cp_desig,'B',0,'L');  
$pdf->SetFont('Arial','',8);

$pdf->SetXY(128,47);  
$pdf->Cell(34.5,5,'Receive Date:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,'','B',0,'L'); 

$pdf->SetXY(5,52);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(25.5,5,'Contact No.:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(90.5,5,$vends_cp_contno,'B',0,'L');  
$pdf->SetFont('Arial','',8);


//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(5,62); 
$pdf->Cell(20,4,'ITEM NO',1,0,'C','true'); 
$pdf->Cell(35,4,'ITEM CODE',1,0,'C','true'); 
$pdf->Cell(106.5,4,'ITEM DESCRIPTION',1,0,'C','true'); 
$pdf->Cell(22,4,'PO QTY',1,0,'C','true'); 
$pdf->Cell(22,4,'ACTUAL QTY',1,0,'C','true'); 


$Y = 66;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
$count=1;

$str="
	SELECT 
		a.`mat_code`,
		b.`ART_DESC`,
		a.`qty`
	FROM 
		gw_rm_po_dt a
	JOIN
		mst_article b
	ON
		a.`mat_code` = b.`ART_CODE`
	WHERE 
		po_sysctrlno = '$PO_CTRLNO'
";
$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
foreach ($q->getResultArray() as $row) {
	$mat_code = $row['mat_code'];
	$qty = $row['qty'];
	$ART_DESC = $row['ART_DESC'];

	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(5,$Y);
	$pdf->Cell(20,4,$count,1,0,'C');
	$pdf->Cell(35,4,$mat_code,1,0,'C');
	$pdf->Cell(106.5,4,$ART_DESC,1,0,'C');
	$pdf->Cell(22,4,intval($qty),1,0,'C');
	$pdf->Cell(22,4,'',1,0,'C');

	$Y = $Y + 4;
	$count = $count + 1;
}


//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of RM: '.$r['po_sysctrlno'],0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of RM: '.$r['po_sysctrlno'],0,0,'C');

$pdf->output('','PO-'.$r['po_sysctrlno']);
