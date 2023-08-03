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
    select 
	aa.*,
    bb.VEND_NAME __vend_name,
    cc.CUST_NAME __vends_name,
    CONCAT(cc.`CUST_ADDR1`,',',cc.`CUST_ADDR2`) __vends_add,
    cc.`CUST_TELNO` __tel_no,
    dd.`recid` __po_cls_rid,
    ff.`myuserfulln`,
    ff.`myuserfulln` __user_approved
    FROM `gw_fg_po_hd` aa 
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
	$PO_CTRLNO    	= $r['po_sysctrlno'];
	$rmks     		= $r['rmks'];
	$print_flag    = $r['print_flag'];
	$approved_fullname = $r['__user_approved'];

}
else{
	redirect('me-rm-purchase-vw');
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
	    'GWRMPO',
	    '1',
	    '{$cuser}',
	    now(),
	    'GWPO_HD'
  	)
";

$q = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$approved_fullname1 = '';
$approved_fullname2 = '';


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
$pdf->Cell(200,5,'GOLDEN WIN EMPIRE MARKETING CORPORATION',0,0,'C'); 
$pdf->SetXY(5,10); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(200,15,'1002-B Apolonia St., Mapulang Lupa, Valenzuela City',0,0,'C'); 
$pdf->SetXY(5,11); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(200,22,'Tel# 961-8526, 961-8641M 931-7162',0,0,'C'); 

$pdf->SetXY(5,24);  
$pdf->SetFont('Arial','B',11);
$pdf->Cell(206,5,'Sewing Pick up',0,0,'C'); 


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
$pdf->Cell(48.5,5,$mylibzsys->mydate_mmddyyyy($r['trx_delivery_date']),'B',0,'L'); 

$pdf->SetXY(5,42);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(16.5,5,'Name:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(100.5,5,$r['__vends_name'],'B',0,'L');  
$pdf->SetFont('Arial','',8);

$pdf->SetXY(128,42);  
$pdf->Cell(34.5,5,'Due Date:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,$mylibzsys->mydate_mmddyyyy($r['trx_delivery_date']),'B',0,'L'); 

$pdf->SetXY(5,47);  
$pdf->SetFont('Arial','',8);
$pdf->Cell(16.5,5,'Address:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(100.5,5,$r['__vends_add'],'B',0,'L');  
$pdf->SetFont('Arial','',8);

$pdf->SetXY(128,47);  
$pdf->Cell(34.5,5,'Cut No.:',0,0,'L'); 
$pdf->SetFont('Arial','',8);
$pdf->Cell(48.5,5,$r['terms'],'B',0,'L'); 

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
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(5,62); 
$pdf->Cell(10,4,'ITEMS',1,0,'C','true'); 
$pdf->Cell(25,4,'QTY',1,0,'C','true'); 


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
		{$this->db_erp}.`gw_rm_po_dt` a
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




$pdf->output('','PO-'.$r['po_sysctrlno']);
