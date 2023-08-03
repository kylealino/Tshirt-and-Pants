<?php 
use App\Libraries\Fpdf\Mypdf;

$request      = \Config\Services::request();
$reponse      = \Config\Services::reponse();
$mydbname     = model('App\Models\MyDBNamesModel');
$mylibzdb     = model('App\Models\MyLibzDBModel');
$mylibzsys    = model('App\Models\MyLibzSysModel');
$memelibsys   = model('App\Models\Mymelibsys_model');
$mytrxpurch   = model('App\Models\MyPurchaseModel');
$mydataz      = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(1);

$cuser          = $mylibzdb->mysys_user();
//$cuser_fullname = $mylibzdb->mysys_user_fullname();
$mpw_tkn        = $mylibzdb->mpw_tkn();
$mtkn_potr      = $request->getVar('mtkn_potr');
$approved_fullname = '';

$str = "
	UPDATE
		{$this->db_erp}.`gw_fg_po_hd`
	SET
		`print_flag` = '2'
	WHERE
		SHA2(CONCAT(`recid`,'{$mpw_tkn}'),384) = '{$mtkn_potr}' 
";

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


$str = "
    select aa.*,
    bb.VEND_NAME __vend_name,
    cc.CUST_NAME __vends_name,
    CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ff.`myuserfulln`,
    gg.`myuserfulln` __user_approved,
    sha2(concat(aa.vend_rid,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.vends_rid,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    from ((((({$this->db_erp}.`gw_fg_po_hd` aa 
    join {$this->db_erp}.`mst_vendor` bb on (aa.vend_rid = bb.recid)) 
    join {$this->db_erp}.`mst_customer` cc on (aa.vends_rid = cc.recid)) 
    join {$this->db_erp}.`mst_po_class` dd on (aa.`po_cls_id` = dd.recid))
    join {$this->db_erp}.myusers ff on(aa.`muser`=ff.myusername))
    join {$this->db_erp}.myusers gg on(aa.`user_approved`=gg.myusername))
    where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_potr' 
";

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$valid_id = '';
$print_flag = 0;

if($q->getNumRows() > 0){
	$r             = $q->getRowArray();
	$valid_id      = $r['recid'];
	$encd_fullname = $r['myuserfulln'];
	$PO_CTRLNO    	= $r['po_sysctrlno'];
	$rmks     		= $r['rmks'];
	$print_flag    = $r['print_flag'];
	$approved_fullname = $r['__user_approved'];

}
else{
	redirect('me-purchase-vw');
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
	VALUES(
	    '{$PO_CTRLNO}',
	    'GWPO',
	    '1',
	    '{$cuser}',
	    now(),
	    'GWPO_HD'
  	)
";

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


//get approver
// $str = "
// 	SELECT
// 		a.*,
// 		c.`myuserfulln`
// 	FROM
// 		{$this->db_erp}.`trx_po_wf_urcpt` a
// 	JOIN
// 		{$this->db_erp}.`mst_po_wf_urcpt` b
// 	ON
// 		a.`PO_URID` = b.`URCPT_ID`
// 	JOIN
// 		{$this->db_erp}.`myusers` c
// 	ON
// 		a.`PO_URID` = c.`myusername`
// 	WHERE
// 		b.`URCPT_CUMM_APP` = 'Y'
// 	AND
// 		b.`UCRPT_PRFORM_TAG` = 3
// 	AND
// 		a.`PO_CTRLNO` = '{$PO_CTRLNO}'
// 	AND
// 		a.`PO_CFRM_TAG` = 'Y'
// ";

// $q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


// if($q->resultID->num_rows > 0) { 
// 	$rr = $q->getRowArray();
//    	$approved_fullname = $rr['myuserfulln'];
// }
//get approver
// $str = "
// 	SELECT
// 		a.*,
// 		c.`myuserfulln`
// 	FROM
// 		{$this->db_erp}.`trx_po_wf_urcpt` a
// 	JOIN
// 		{$this->db_erp}.`mst_po_wf_urcpt` b
// 	ON
// 		a.`PO_URID` = b.`URCPT_ID`
// 	JOIN
// 		{$this->db_erp}.`myusers` c
// 	ON
// 		a.`PO_URID` = c.`myusername`
// 	WHERE
// 		b.`URCPT_CUMM_APP` = 'Y'
// 	AND
// 		b.`UCRPT_PRFORM_TAG` = 2
// 	AND
// 		a.`PO_CTRLNO` = '{$PO_CTRLNO}'
// 	AND
// 		a.`PO_CFRM_TAG` = 'Y'
// ";

// $q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = '';
// if($q->resultID->num_rows > 0) { 
// 	$rr = $q->getRowArray();
//    	$approved_fullname1 = $rr['myuserfulln'];
// }
//get approver
// $str = "
// 	SELECT
// 		a.*,
// 		c.`myuserfulln`
// 	FROM
// 		{$this->db_erp}.`trx_po_wf_urcpt` a
// 	JOIN
// 		{$this->db_erp}.`mst_po_wf_urcpt` b
// 	ON
// 		a.`PO_URID` = b.`URCPT_ID`
// 	JOIN
// 		{$this->db_erp}.`myusers` c
// 	ON
// 		a.`PO_URID` = c.`myusername`
// 	WHERE
// 		b.`URCPT_CUMM_APP` = 'Y'
// 	AND
// 		b.`UCRPT_PRFORM_TAG` = 1
// 	AND
// 		a.`PO_CTRLNO` = '{$PO_CTRLNO}'
// 	AND
// 		a.`PO_CFRM_TAG` = 'Y'
// ";

// $q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname2 = '';
// if($q->resultID->num_rows > 0) { 
// 	$rr = $q->getRowArray();
//    	$approved_fullname2 = $rr['myuserfulln'];
// }


$str = "
	select aa.*,
	SUM(aa.`qty`) AS `_qty`,
	SUM(aa.`convf`) AS `_convf`,
	cc.`plnt_code` AS `po_wshe_plant`,
	dd.`wshe_code` AS `po_wshe_loc`,
	ee.`wshe_bin_name` AS `po_wshe_sbin`,
	bb.`ART_DESC`,
	bb.`ART_SKU`,
	bb.`ART_CODE`
	from ((((({$this->db_erp}.`gw_fg_po_dt` aa
	join {$this->db_erp}.`mst_article` bb on(aa.`art_rid` = bb.recid)) 
	join {$this->db_erp}.mst_plant cc on (cc.`recid` = aa.po_plnt_id)) 
	join {$this->db_erp}.mst_wshe dd on (dd.plnt_id = cc.`recid` and dd.recid = aa.po_wshe_id)) 
	join {$this->db_erp}.mst_wshe_grp ff on (aa.`po_wshe_id` = ff.`wshe_id` AND ff.`recid` = aa.`po_wshe_grp_id`))
    join {$this->db_erp}.mst_wshe_bin ee on (ee.`wshegrp_id` = ff.`recid` AND ee.`recid` = aa.`po_wshe_sbin_id`)) 
	where aa.`pohd_rid` = $valid_id 
	GROUP BY aa.`art_rid`
	order by aa.recid
	"
; 
$q3 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

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
$pdf->Cell(112,5,$r['__vends_name'],0,0,'L'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(5,15,$r['__vends_add'],0,0,'L'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,22,'Tel. Nos.: (02) '.$r['__tel_no'],0,0,'L'); 

$pdf->SetXY(5,18);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'FG PURCHASE ORDER',0,0,'C'); 

$pdf->SetXY(5,27);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(16.5,5,'SUPPLIER:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(100.5,5,$r['__vend_name'],'B',0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(128,27);  
$pdf->Cell(34.5,5,'PURCHASE ORDER NO:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,$r['po_sysctrlno'],'B',0,'L'); 

$pdf->SetXY(5,32);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(16.5,5,'DATE:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(100.5,5,$mylibzsys->mydate_mmddyyyy($r['trx_date']),'B',0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(128,32);  
$pdf->Cell(34.5,5,'DELIVERY DATE:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,$mylibzsys->mydate_mmddyyyy($r['trx_delivery_date']),'B',0,'L'); 

$pdf->SetXY(5,37);  
$pdf->SetFont('Arial','B',8);
$pdf->Cell(16.5,5,'SHIP TO:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(100.5,5,$r['__vends_name'],'B',0,'L');  
$pdf->SetFont('Arial','B',8);

$pdf->SetXY(128,37);  
$pdf->Cell(34.5,5,'TERMS OF PAYMENT:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,$r['terms'],'B',0,'L'); 

//ITEMS TH
$pdf->SetFillColor(239,225,131,1);
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,47); 
$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

//footer page number
$pdf->SetY(-12);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r['po_sysctrlno'],0,0,'C');

//header page number
$pdf->SetY(0);
$pdf->SetX(177);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r['po_sysctrlno'],0,0,'C');


$Y = 51;
$total_qty = 0;
$total_amount = 0;
$box_no = 1;
foreach($q3->getResultArray() as $row){
	$po_dt_id = $row['recid'];
	$qty = $row['_qty'];
	$convf = $row['_convf'];
	$xconvf = $row['convf'];
	$total_pcs = $row['_convf'];
	$price = $row['price'];
	$po_dt_id = $row['recid'];

	$ART_DESC = $row['ART_DESC'];
	$ART_UOM = $row['ART_SKU'];
	$ART_CODE = $row['ART_CODE'];


	$str = "
		SELECT 
			a.*,
			b.`ART_CODE`,
			b.`ART_DESC`
		FROM
		{$this->db_erp}.`gw_po_dt` a
		JOIN
		{$this->db_erp}.`mst_article` b
		ON
		a.`art_rid` = b.`recid`
		WHERE
		a.`recid` = {$row['recid']}
		ORDER by
		a.`recid`
	";
	$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

	$item = array();

	if($q->getNumRows() > 0){
		$_convf = 0;

		foreach($q->getResultArray() as $row){
			$_ART_CODE = $row['ART_CODE'];
			$_qty = $row['qty'] * $row['convf'];
			$_ART_DESC = $row['ART_DESC'];
			$_price = $row['price'];
			$_po_dt_id = $row['recid'];
			$_convf += $row['qty'] * $row['convf'];

			$item_data = $_ART_CODE.'x|x'.$_ART_DESC.'x|x'.$_qty.'x|x'.$_convf.'x|x'.$_price.'x|x'.$_po_dt_id;
			array_push($item, $item_data);
		}
	}
	else{
		
		$item_data = $ART_CODE.'x|x'.$ART_DESC.'x|x'.$xconvf.'x|x'.$convf.'x|x'.$price.'x|x'.$po_dt_id;
		array_push($item, $item_data);
	}
	
	$xrecid = 0;
	$item_no = 1;
	$total_qty += $qty;



	for($i = 0; $i < count($item); $i++){
		$data = explode('x|x', $item[$i]);
		$_ART_CODE = $data[0];
		$_ART_DESC = $data[1];
		$_qty = $data[2];
		$_price = $data[4];
		$_recid = $data[5];
		
		$total_pcs = $qty * $_qty;


		$total_price = $_qty*$_price*$qty;
		$total_amount += $total_price;
		if($Y < 251){

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

		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
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
			$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
			$pdf->Cell(25,4,'STOCK NUMBER',1,0,'C','true'); 
			$pdf->Cell(69,4,'DESCRIPTION',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY',1,0,'C','true'); 
			$pdf->Cell(15,4,'PACKAGING',1,0,'C','true'); 
			$pdf->Cell(12,4,'QTY/UNIT',1,0,'C','true'); 
			$pdf->Cell(15,4,'TOTAL PCS',1,0,'C','true'); 
			$pdf->Cell(15,4,'PRICE/PC',1,0,'C','true'); 
			$pdf->Cell(15,4,'DISCOUNT',1,0,'C','true'); 
			$pdf->Cell(18,4,'TOTAL',1,0,'C','true'); 

			//footer page number
			$pdf->SetY(-12);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->po_sysctrlno,0,0,'C');

			//header page number
			$pdf->SetY(0);
			$pdf->SetX(177);
			$pdf->SetFont('Arial','I',8);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of PO: '.$r->po_sysctrlno,0,0,'C');



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

			$Y = $Y + 4;
		
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(5,$Y); 
			if($_recid != $xrecid){
				$pdf->Cell(10,5,$box_no,$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,number_format($qty),$border,0,'C'); 
				$pdf->Cell(15,5,$ART_UOM,$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			else{
				$pdf->Cell(10,5,'',$border,0,'C'); 
				$pdf->Cell(25,5,$_ART_CODE,1,0,'L'); 
				$pdf->Cell(69,5,$_ART_DESC,1,0,'L'); 
				$pdf->Cell(12,5,'',$border,0,'C'); 
				$pdf->Cell(15,5,'',$border,0,'C'); 
				$pdf->Cell(12,5,$_qty,1,0,'C'); 
				$pdf->Cell(15,5,$total_pcs,1,0,'C'); 
				$pdf->Cell(15,5,$_price,1,0,'C'); 
				$pdf->Cell(15,5,'',1,0,'C'); 
				$pdf->Cell(18,5,number_format($total_price,2),1,0,'C'); 
			}
			

			$xrecid = $_recid;

		}
		$Y = $Y + 5;
		$item_no++;
		
	}//endfor
	
	$box_no++;
}

$vat = $total_amount*0.12;
$total_amount_due = $total_amount + $vat;

if($Y < 191){
	$pdf->SetFont('Arial','',7);

	$pdf->SetXY(96,$Y);  
	$pdf->Cell(15,5,'TOTAL: ',0,0,'L'); 
	$pdf->SetXY(108,$Y);  
	$pdf->Cell(15,5,number_format($total_qty),'B',0,'C'); 

	$pdf->SetXY(178,$Y);  
	$pdf->Cell(17,5,'SUB TOTAL: ',0,0,'L'); 
	$pdf->SetXY(195,$Y);  
	$pdf->Cell(15,5,number_format($total_amount,2),'B',0,'C'); 

	$Y = $Y + 5;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'REMARKS: ',0,0,'L'); 
	$pdf->Cell(127,4,$rmks,'B',0,'L'); 

	$pdf->SetXY(179,$Y);  
	$pdf->Cell(16,5,'DISCOUNT: ',0,0,'L'); 
	$pdf->Cell(15,4,'','B',0,'L'); 

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(143,4,'','B',0,'L'); 

	$pdf->SetXY(187,$Y);  
	$pdf->Cell(7,4,'VAT: ',0,'B','L'); 
	$pdf->SetXY(195,$Y);  
	$pdf->Cell(15,4,number_format($vat,2),'B',0,'C');

	$pdf->SetFont('Arial','',8);

	$Y = $Y + 8;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'NOTE: Supplier warrants that the delivered articles/merchandize are DOH-FDA approved and/or compliant',0,0,'L'); 
	$pdf->SetXY(162,$Y);  
	// $pdf->SetFont('Arial','',8);
	$pdf->Cell(16,5,'TOTAL AMOUNT DUE:',0,0,'L'); 
	$pdf->SetXY(195,$Y);  
	$pdf->Cell(15,4,number_format($total_amount_due,2),'B',0,'C');

	$pdf->SetFont('Arial','',8);
	$Y = $Y + 5;

	$pdf->SetXY(14.5,$Y);  
	$pdf->Cell(16,5,'with the Department of Trade & Industry rules pursuant to RA 4109 and RA 7394 whichever is ',0,0,'L'); 

	$Y = $Y + 5;

	$pdf->SetXY(14.5,$Y);  
	$pdf->Cell(16,5,'applicable  in addition to withholding of payment for non-compliant article/merchandize arriving',0,0,'L'); 

	$Y = $Y + 5;

	$pdf->SetXY(14.5,$Y);  
	$pdf->Cell(16,5,'from breach of warranties.',0,0,'L'); 

	$Y = $Y + 5;

	$pdf->SetXY(14.5,$Y);  
	$pdf->Cell(16,5,'All item must be properly labelled wih barcode, cbm, stock #, quantity/pack, gross weight.',0,0,'L'); 

	$Y = $Y + 5;

	$pdf->SetXY(14.5,$Y);  
	$pdf->Cell(16,5,'Delivery time: 7:30AM - 2:00PM',0,0,'L'); 

	// $Y = $Y + 10;

	// $pdf->SetXY(145,$Y);  
	// $pdf->Cell(60,5,'APPROVED BY','T',0,'C'); 

	$Y = $Y + 10;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(60,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->Cell(60,5,'PREPARED BY',0,0,'C'); 

	$pdf->SetXY(78,$Y);  
	$pdf->Cell(60,5,$approved_fullname1.' / '.$approved_fullname2,'B',0,'C'); 
	$pdf->SetXY(78,$Y+5);
	$pdf->Cell(60,5,'CHECKED BY',0,0,'C'); 

	$pdf->SetXY(150,$Y);  
	$pdf->Cell(60,5,$approved_fullname,'B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'APPROVED BY',0,0,'C'); 

	$Y = $Y + 10;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(60,5,'','B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->Cell(60,5,'RECEIVED BY',0,0,'C'); 

}
else{

	$pdf->SetFont('Arial','B',7);

	$pdf->SetXY(96,$Y);  
	$pdf->Cell(15,5,'TOTAL: ',0,0,'L'); 
	$pdf->SetXY(108,$Y);  
	$pdf->Cell(15,5,number_format($total_qty),'B',0,'C'); 

	$pdf->SetXY(178,$Y);  
	$pdf->Cell(17,5,'SUB TOTAL: ',0,0,'L'); 
	$pdf->SetXY(195,$Y);  
	$pdf->Cell(15,5,number_format($total_amount,2),'B',0,'L'); 

	$Y = $Y + 8;
	$pdf->SetXY(179,$Y);  
	$pdf->Cell(16,5,'DISCOUNT: ',0,0,'L'); 
	$pdf->Cell(15,4,'','B',0,'L'); 

	$Y = $Y + 8;

	$pdf->SetXY(187,$Y);  
	$pdf->Cell(7,4,'VAT: ',0,'B','L'); 
	$pdf->SetXY(195,$Y);  
	$pdf->Cell(15,4,number_format($vat,2),'B',0,'C');

	$Y = $Y + 8;

	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY(163,$Y); 
	$pdf->Cell(16,5,'TOTAL AMOUNT DUE:',0,0,'L'); 
	$pdf->SetXY(195,$Y);  
	$pdf->Cell(15,4,number_format($total_amount_due,2),'B',0,'C');




	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false);
	$Y = 11;

	$Y = $Y + 5;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'REMARKS: ',0,0,'L'); 
	$pdf->Cell(187,4,$rmks,'B',0,'L'); 

	$Y = $Y + 5;
	$pdf->SetXY(5,$Y);  
	$pdf->Cell(203,4,'','B',0,'L'); 


	$pdf->SetFont('Arial','',8);

	$Y = $Y + 8;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(16,5,'NOTE: Supplier warrants that the delivered articles/merchandize are DOH-FDA approved and/or compliant',0,0,'L'); 
	 
	

	$pdf->SetFont('Arial','',8);
	$Y = $Y + 5;

	$pdf->SetXY(14.5,$Y);  
	$pdf->Cell(16,5,'with the Department of Trade & Industry rules pursuant to RA 4109 and RA 7394 whichever is ',0,0,'L'); 

	$Y = $Y + 5;

	$pdf->SetXY(14.5,$Y);  
	$pdf->Cell(16,5,'applicable  in addition to withholding of payment for non-compliant article/merchandize arriving',0,0,'L'); 

	$Y = $Y + 5;

	$pdf->SetXY(14.5,$Y);  
	$pdf->Cell(16,5,'from breach of warranties.',0,0,'L'); 

	$Y = $Y + 5;

	$pdf->SetXY(14.5,$Y);  
	$pdf->Cell(16,5,'All item must be properly labelled wih barcode, cbm,stock#, quantity/pack, gross weight',0,0,'L'); 

	$Y = $Y + 5;

	$pdf->SetXY(14.5,$Y);  
	$pdf->Cell(16,5,'Delivery time: 7:30AM - 2:00PM',0,0,'L'); 

	$Y = $Y + 10;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(60,5,$encd_fullname,'B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->Cell(60,5,'PREPARED BY',0,0,'C'); 

	$pdf->SetXY(78,$Y);  
	$pdf->Cell(60,5,$approved_fullname1.' / '.$approved_fullname2,'B',0,'C'); 
	$pdf->SetXY(78,$Y+5);
	$pdf->Cell(60,5,'CHECKED BY',0,0,'C'); 

	$pdf->SetXY(150,$Y);  
	$pdf->Cell(60,5,$approved_fullname,'B',0,'C'); 
	$pdf->SetXY(150,$Y+5);
	$pdf->Cell(60,5,'APPROVED BY',0,0,'C'); 

	$Y = $Y + 10;

	$pdf->SetXY(5,$Y);  
	$pdf->Cell(60,5,'','B',0,'C'); 
	$pdf->SetXY(5,$Y+5);
	$pdf->Cell(60,5,'RECEIVED BY',0,0,'C'); 

}

$pdf->output('','FGPO-'.$r['po_sysctrlno']);
