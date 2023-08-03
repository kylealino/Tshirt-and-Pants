
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
$rmap_trxno = $request->getVar('rmap_trxno');
$tpa_trxno = $request->getVar('tpa_trxno');

$fabric_code = '';
$fabric_desc = '';
$fabric_qty = '';
$fabric_tcost = 0;
$fabric_uom = '';

$lining_code = '';
$lining_desc = '';
$lining_qty = '';
$lining_tcost = 0;
$lining_uom = '';

$btn_code = '';
$btn_desc = '';
$btn_qty = '';
$btn_tcost = 0;
$btn_uom = '';

$rivets_code = '';
$rivets_desc = '';
$rivets_qty = '';
$rivets_tcost = 0;
$rivets_uom = '';

$leather_patch_code = '';
$leather_patch_desc = '';
$leather_patch_qty = '';
$leather_patch_tcost = 0;
$leather_patch_uom = '';

$plastic_btn_code = '';
$plastic_btn_desc = '';
$plastic_btn_qty = '';
$plastic_btn_tcost = 0;
$plastic_btn_uom = '';

$inside_garter_code = '';
$inside_garter_desc = '';
$inside_garter_qty = '';
$inside_garter_tcost = 0;
$inside_garter_uom = '';

$hang_tag_code = '';
$hang_tag_desc = '';
$hang_tag_qty = '';
$hang_tag_tcost = 0;
$hang_tag_uom = '';

$zipper_code = '';
$zipper_desc = '';
$zipper_qty = '';
$zipper_tcost = 0;
$zipper_uom = '';

$size_lbl_code = '';
$size_lbl_desc = '';
$size_lbl_qty = '';
$size_lbl_tcost = 0;
$size_lbl_uom = '';

$size_care_lbl_code = '';
$size_care_lbl_desc = '';
$size_care_lbl_qty = '';
$size_care_lbl_tcost = 0;
$size_care_lbl_uom = '';

$side_lbl_code = '';
$side_lbl_desc = '';
$side_lbl_qty = '';
$side_lbl_tcost = 0;
$side_lbl_uom = '';

$kids_lbl_code = '';
$kids_lbl_desc = '';
$kids_lbl_qty = '';
$kids_lbl_tcost = 0;
$kids_lbl_uom = '';

$kids_side_lbl_code = '';
$kids_side_lbl_desc = '';
$kids_side_lbl_qty = '';
$kids_side_lbl_tcost = 0;
$kids_side_lbl_uom = '';

$plastic_bag_code = '';
$plastic_bag_desc = '';
$plastic_bag_qty = '';
$plastic_bag_tcost = 0;
$plastic_bag_uom = '';

$barcode_code = '';
$barcode_desc = '';
$barcode_qty = '';
$barcode_tcost = 0;
$barcode_uom = '';

$fitting_sticker_code = '';
$fitting_sticker_desc = '';
$fitting_sticker_qty = '';
$fitting_sticker_tcost = 0;
$fitting_sticker_uom = '';

$tag_pin_code = '';
$tag_pin_desc = '';
$tag_pin_qty = '';
$tag_pin_tcost = 0;
$tag_pin_uom = '';

$chip_board_code = '';
$chip_board_desc = '';
$chip_board_qty = '';
$chip_board_tcost = 0;
$chip_board_uom = '';
$xrecid = 0;
$total_qty =0;

$fabric_total = 0;
$lining_total = 0;
$btn_total = 0;
$rivets_total = 0;
$leather_patch_total = 0;
$plastic_btn_total = 0;
$inside_garter_total = 0;
$hang_tag_total = 0;
$zipper_total = 0;
$size_lbl_total = 0;
$size_care_lbl_total = 0;
$side_lbl_total = 0;
$kids_lbl_total = 0;
$kids_side_lbl_total = 0;
$plastic_bag_total = 0;
$barcode_total = 0;
$fitting_sticker_total = 0;
$tag_pin_total = 0;
$chip_board_total = 0;
$total_overall = 0;

$pdf = new Mypdf();
$pdf->AliasNbPages();

$pdf->SetTitle('BOM-'. $rmap_trxno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);

$pdf->AddFont('Dot','','Calibri.php');
$pdf->SetFont('Dot','',10);

$pdf->SetXY(5,10); 
$pdf->SetFont('Dot','',15);
$pdf->Cell(112,5,'GOLDENWIN EMPIRE MARKETING CORPORATION',1,1,'L'); 


$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5+10,'Materials',0,0,'C'); 

$pdf->SetXY(5,27);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(16.5,5,'RMAP NO:',0,0,'L'); 
$pdf->SetFont('Dot','',10);
$pdf->Cell(50.5,5,$rmap_trxno,'B',0,'L');  
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
$pdf->Cell(50.5,5,$print_time,'B',0,'L');  
$pdf->SetFont('Dot','',10);

$pdf->SetXY(150,37);  
$pdf->SetFont('Dot','',10);
$pdf->Cell(15.5,5,'RELEASE BY:',0,0,'L'); 
$pdf->SetFont('Dot','',10);

$pdf->SetXY(170,37);  
$pdf->Cell(40,5,'','B',0,'C');  
$pdf->SetFont('Dot','',10);

$Y = 52;

$pdf->SetXY(5,$Y-5);
$pdf->Cell(25,5,'SKU',1,0,'C'); 
$pdf->Cell(25,5,'MATERIAL',1,0,'C'); 
$pdf->Cell(80,5,'DESCRIPTION',1,0,'C'); 
$pdf->Cell(25,5,'QTY',1,0,'C');
$pdf->Cell(25,5,'UOM',1,0,'C');
$pdf->Cell(25,5,'COST',1,0,'C');


//footer page number
$pdf->SetY(-15);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of RMAP NO: '.$rmap_trxno. '  Print Time:'.$print_time,0,0,'C');

//header page number
$pdf->SetY(5);
$pdf->SetX(150);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of RMAP NO: '.$rmap_trxno,0,0,'C');


$str = "                        
    SELECT
        b.`item_code`,
        a.`fabric_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`fabric_code` LIMIT 1) AS fabric_desc,
        a.`fabric_qty`,
        a.`fabric_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`fabric_code` LIMIT 1) AS fabric_uom,
        a.`lining_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`lining_code` LIMIT 1) AS lining_desc,
        a.`lining_qty`,
        a.`lining_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`lining_code` LIMIT 1) AS lining_uom,
        a.`btn_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`btn_code` LIMIT 1) AS btn_desc,
        a.`btn_qty`,
        a.`btn_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`btn_code` LIMIT 1) AS btn_uom,
        a.`rivets_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`rivets_code` LIMIT 1) AS rivets_desc,
        a.`rivets_qty`,
        a.`rivets_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`rivets_code` LIMIT 1) AS rivets_uom,
        a.`leather_patch_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`leather_patch_code` LIMIT 1) AS leather_patch_desc,
        a.`leather_patch_qty`,
        a.`leather_patch_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`leather_patch_code`) AS leather_patch_uom,
        a.`plastic_btn_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`plastic_btn_code`) AS plastic_btn_desc,
        a.`plastic_btn_qty`,
        a.`plastic_btn_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`plastic_btn_code` LIMIT 1) AS plastic_btn_uom,
        a.`inside_garter_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`inside_garter_code` LIMIT 1) AS inside_garter_desc,
        a.`inside_garter_qty`,
        a.`inside_garter_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`inside_garter_code`) AS inside_garter_uom,
        a.`hang_tag_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`hang_tag_code` LIMIT 1) AS hang_tag_desc,
        a.`hang_tag_qty`,
        a.`hang_tag_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`hang_tag_code` LIMIT 1) AS hang_tag_uom,
        a.`zipper_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`zipper_code` LIMIT 1) AS zipper_desc,
        a.`zipper_qty`,
        a.`zipper_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`zipper_code` LIMIT 1) AS zipper_uom,
        a.`size_lbl_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`size_lbl_code` LIMIT 1) AS size_lbl_desc,
        a.`size_lbl_qty`,
        a.`size_lbl_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`size_lbl_code` LIMIT 1) AS size_lbl_uom,
        a.`size_care_lbl_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`size_care_lbl_code` LIMIT 1) AS size_care_lbl_desc,
        a.`size_care_lbl_qty`,
        a.`size_care_lbl_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`size_care_lbl_code` LIMIT 1) AS size_care_lbl_uom,
        a.`side_lbl_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`side_lbl_code` LIMIT 1) AS side_lbl_desc,
        a.`side_lbl_qty`,
        a.`side_lbl_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`side_lbl_code` LIMIT 1) AS side_lbl_uom,
        a.`kids_lbl_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`kids_lbl_code` LIMIT 1) AS kids_lbl_desc,
        a.`kids_lbl_qty`,
        a.`kids_lbl_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`kids_lbl_code` LIMIT 1) AS kids_lbl_uom,
        a.`kids_side_lbl_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`kids_side_lbl_code` LIMIT 1) AS kids_side_lbl_desc,
        a.`kids_side_lbl_qty`,
        a.`kids_side_lbl_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`kids_side_lbl_code` LIMIT 1) AS kids_side_lbl_uom,
        a.`plastic_bag_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`plastic_bag_code` LIMIT 1) AS plastic_bag_desc,
        a.`plastic_bag_qty`,
        a.`plastic_bag_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`plastic_bag_code` LIMIT 1) AS plastic_bag_uom,
        a.`barcode_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`barcode_code` LIMIT 1) AS barcode_desc,
        a.`barcode_qty`,
        a.`barcode_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`barcode_code` LIMIT 1) AS barcode_uom,
        a.`fitting_sticker_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`fitting_sticker_code` LIMIT 1) AS fitting_sticker_desc,
        a.`fitting_sticker_qty`,
        a.`fitting_sticker_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`fitting_sticker_code` LIMIT 1) AS fitting_sticker_uom,
        a.`tag_pin_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`tag_pin_code` LIMIT 1) AS tag_pin_desc,
        a.`tag_pin_qty`,
        a.`tag_pin_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`tag_pin_code` LIMIT 1) AS tag_pin_uom,
        a.`chip_board_code`,
        (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = a.`chip_board_code`) AS chip_board_desc,
        a.`chip_board_qty`,
        a.`chip_board_tcost`,
        (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = a.`chip_board_code` LIMIT 1) AS chip_board_uom,
        b.`item_qty`

    FROM
        `mst_item_comp` a
    JOIN
        `trx_rmap_req_dt` b
    ON
        a.`ART_CODE` = b.`item_code`
    WHERE 
        b.`rmap_trxno` = '$rmap_trxno'
    GROUP BY
        b.`item_code`
";

$q3 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$Y = 52;
$border = 1;
$item = array();

foreach($q3->getResult() as $row){
    $yaxis = 5;
    $item_code = $row->item_code;
    $item_qty = $row->item_qty;
	$fabric_code = $row->fabric_code;
	$fabric_desc = $row->fabric_desc;
    $fabric_qty = $row->fabric_qty;
	$fabric_tcost = $row->fabric_tcost;
    $fabric_uom = $row->fabric_uom;
    $fabric_total += $fabric_tcost;

	$lining_code = $row->lining_code;
    $lining_desc = $row->lining_desc;
	$lining_qty = $row->lining_qty;
    $lining_tcost = $row->lining_tcost;
	$lining_uom = $row->lining_uom;
    $lining_total += $lining_tcost;

    $btn_code = $row->btn_code;
	$btn_desc = $row->btn_desc;
    $btn_qty = $row->btn_qty;
	$btn_tcost = $row->btn_tcost;
    $btn_uom = $row->btn_uom;
    $btn_total += $btn_tcost;

	$rivets_code = $row->rivets_code;
    $rivets_desc = $row->rivets_desc;
	$rivets_qty = $row->rivets_qty;
    $rivets_tcost = $row->rivets_tcost;
	$rivets_uom = $row->rivets_uom;
    $rivets_total += $rivets_tcost;

    $leather_patch_code = $row->leather_patch_code;
	$leather_patch_desc = $row->leather_patch_desc;
    $leather_patch_qty = $row->leather_patch_qty;
	$leather_patch_tcost = $row->leather_patch_tcost;
    $leather_patch_uom = $row->leather_patch_uom;
    $leather_patch_total += $leather_patch_tcost;

	$plastic_btn_code = $row->plastic_btn_code;
    $plastic_btn_desc = $row->plastic_btn_desc;
	$plastic_btn_qty = $row->plastic_btn_qty;
    $plastic_btn_tcost = $row->plastic_btn_tcost;
	$plastic_btn_uom = $row->plastic_btn_uom;
    $plastic_btn_total += $plastic_btn_tcost;

    $inside_garter_code = $row->inside_garter_code;
	$inside_garter_desc = $row->inside_garter_desc;
    $inside_garter_qty = $row->inside_garter_qty;
	$inside_garter_tcost = $row->inside_garter_tcost;
    $inside_garter_uom = $row->inside_garter_uom;
    $inside_garter_total += $inside_garter_tcost;

	$hang_tag_code = $row->hang_tag_code;
    $hang_tag_desc = $row->hang_tag_desc;
	$hang_tag_qty = $row->hang_tag_qty;
    $hang_tag_tcost = $row->hang_tag_tcost;
	$hang_tag_uom = $row->hang_tag_uom;
    $hang_tag_total += $hang_tag_tcost;

    $zipper_code = $row->zipper_code;
	$zipper_desc = $row->zipper_desc;
    $zipper_qty = $row->zipper_qty;
	$zipper_tcost = $row->zipper_tcost;
    $zipper_uom = $row->zipper_uom;
    $zipper_total += $zipper_tcost;

	$size_lbl_code = $row->size_lbl_code;
    $size_lbl_desc = $row->size_lbl_desc;
	$size_lbl_qty = $row->size_lbl_qty;
    $size_lbl_tcost = $row->size_lbl_tcost;
	$size_lbl_uom = $row->size_lbl_uom;
    $size_lbl_total += $size_lbl_tcost;

    $size_care_lbl_code = $row->size_care_lbl_code;
	$size_care_lbl_desc = $row->size_care_lbl_desc;
    $size_care_lbl_qty = $row->size_care_lbl_qty;
	$size_care_lbl_tcost = $row->size_care_lbl_tcost;
    $size_care_lbl_uom = $row->size_care_lbl_uom;
    $size_care_lbl_total += $size_care_lbl_tcost;

	$side_lbl_code = $row->side_lbl_code;
    $side_lbl_desc = $row->side_lbl_desc;
	$side_lbl_qty = $row->side_lbl_qty;
    $side_lbl_tcost = $row->side_lbl_tcost;
	$side_lbl_uom = $row->side_lbl_uom;
    $side_lbl_total += $side_lbl_tcost;

    $kids_lbl_code = $row->kids_lbl_code;
	$kids_lbl_desc = $row->kids_lbl_desc;
    $kids_lbl_qty = $row->kids_lbl_qty;
	$kids_lbl_tcost = $row->kids_lbl_tcost;
    $kids_lbl_uom = $row->kids_lbl_uom;
    $kids_lbl_total += $kids_lbl_tcost;

	$kids_side_lbl_code = $row->kids_side_lbl_code;
    $kids_side_lbl_desc = $row->kids_side_lbl_desc;
	$kids_side_lbl_qty = $row->kids_side_lbl_qty;
    $kids_side_lbl_tcost = $row->kids_side_lbl_tcost;
	$kids_side_lbl_uom = $row->kids_side_lbl_uom;
    $kids_side_lbl_total += $kids_side_lbl_tcost;

    $plastic_bag_code = $row->plastic_bag_code;
	$plastic_bag_desc = $row->plastic_bag_desc;
    $plastic_bag_qty = $row->plastic_bag_qty;
	$plastic_bag_tcost = $row->plastic_bag_tcost;
    $plastic_bag_uom = $row->plastic_bag_uom;
    $plastic_bag_total += $plastic_bag_tcost;

	$barcode_code = $row->barcode_code;
    $barcode_desc = $row->barcode_desc;
	$barcode_qty = $row->barcode_qty;
    $barcode_tcost = $row->barcode_tcost;
	$barcode_uom = $row->barcode_uom;
    $barcode_total += $barcode_tcost;

    $fitting_sticker_code = $row->fitting_sticker_code;
	$fitting_sticker_desc = $row->fitting_sticker_desc;
    $fitting_sticker_qty = $row->fitting_sticker_qty;
	$fitting_sticker_tcost = $row->fitting_sticker_tcost;
    $fitting_sticker_uom = $row->fitting_sticker_uom;
    $fitting_sticker_total += $fitting_sticker_tcost;

	$tag_pin_code = $row->tag_pin_code;
    $tag_pin_desc = $row->tag_pin_desc;
	$tag_pin_qty = $row->tag_pin_qty;
    $tag_pin_tcost = $row->tag_pin_tcost;
	$tag_pin_uom = $row->tag_pin_uom;
    $tag_pin_total += $tag_pin_tcost;

	$chip_board_code = $row->chip_board_code;
    $chip_board_desc = $row->chip_board_desc;
	$chip_board_qty = $row->chip_board_qty;
    $chip_board_tcost = $row->chip_board_tcost;
	$chip_board_uom = $row->chip_board_uom;
    $chip_board_total += $chip_board_tcost;

    $item_data = $item_code;
    $item_qty = $item_qty;
    array_push($item, $item_data);
    
    $total_overall = $fabric_total + $lining_total + $btn_total + $rivets_total + $leather_patch_total + $plastic_btn_total + $inside_garter_total + $hang_tag_total + $zipper_total + $size_lbl_total + $size_care_lbl_total + $side_lbl_total + $kids_lbl_total + $kids_side_lbl_total + $plastic_bag_total + $barcode_total + $fitting_sticker_total + $tag_pin_total + $chip_board_total;
    for ($i=0; $i < count($item) -$i; $i++) { 
        if($Y < 200){
            
            $pdf->SetLeftMargin(5);
            $pdf->SetXY(5,$Y);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(138,5,$item_data,'TBL',0,'L');
            $pdf->Cell(67,5,number_format($item_qty,2),'TRB',1,'L'); 
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25,5,'Fabric:',$border,0,'C'); 
            $pdf->Cell(25,5,$fabric_code,1,0,'C'); 
            $pdf->Cell(80,5,$fabric_desc,1,0,'C'); 
            $pdf->Cell(25,5,$fabric_qty,1,0,'C');
            $pdf->Cell(25,5,$fabric_uom,1,0,'C');
            $pdf->Cell(25,5,$fabric_tcost,1,1,'C');
            $pdf->Cell(25,5,'Lining:',$border,0,'C'); 
            $pdf->Cell(25,5,$lining_code,1,0,'C'); 
            $pdf->Cell(80,5,$lining_desc,1,0,'C'); 
            $pdf->Cell(25,5,$lining_qty,1,0,'C');
            $pdf->Cell(25,5,$lining_uom,1,0,'C');
            $pdf->Cell(25,5,$lining_tcost,1,1,'C');
            $pdf->Cell(25,5,'Button:',$border,0,'C'); 
            $pdf->Cell(25,5,$btn_code,1,0,'C'); 
            $pdf->Cell(80,5,$btn_desc,1,0,'C'); 
            $pdf->Cell(25,5,$btn_qty,1,0,'C');
            $pdf->Cell(25,5,$btn_uom,1,0,'C');
            $pdf->Cell(25,5,$btn_tcost,1,1,'C');
            $pdf->Cell(25,5,'Rivets:',$border,0,'C'); 
            $pdf->Cell(25,5,$rivets_code,1,0,'C'); 
            $pdf->Cell(80,5,$rivets_desc,1,0,'C'); 
            $pdf->Cell(25,5,$rivets_qty,1,0,'C');
            $pdf->Cell(25,5,$rivets_uom,1,0,'C');
            $pdf->Cell(25,5,$rivets_tcost,1,1,'C');
            $pdf->Cell(25,5,'Leather Patch:',$border,0,'C'); 
            $pdf->Cell(25,5,$leather_patch_code,1,0,'C'); 
            $pdf->Cell(80,5,$leather_patch_desc,1,0,'C'); 
            $pdf->Cell(25,5,$leather_patch_qty,1,0,'C');
            $pdf->Cell(25,5,$leather_patch_uom,1,0,'C');
            $pdf->Cell(25,5,$leather_patch_tcost,1,1,'C');
            $pdf->Cell(25,5,'Inside Garter:',$border,0,'C'); 
            $pdf->Cell(25,5,$inside_garter_code,1,0,'C'); 
            $pdf->Cell(80,5,$inside_garter_desc,1,0,'C'); 
            $pdf->Cell(25,5,$inside_garter_qty,1,0,'C');
            $pdf->Cell(25,5,$inside_garter_uom,1,0,'C');
            $pdf->Cell(25,5,$inside_garter_tcost,1,1,'C');
            $pdf->Cell(25,5,'Hang Tag:',$border,0,'C'); 
            $pdf->Cell(25,5,$hang_tag_code,1,0,'C'); 
            $pdf->Cell(80,5,$hang_tag_desc,1,0,'C'); 
            $pdf->Cell(25,5,$hang_tag_qty,1,0,'C');
            $pdf->Cell(25,5,$hang_tag_uom,1,0,'C');
            $pdf->Cell(25,5,$hang_tag_tcost,1,1,'C');
            $pdf->Cell(25,5,'Zipper:',$border,0,'C'); 
            $pdf->Cell(25,5,$zipper_code,1,0,'C'); 
            $pdf->Cell(80,5,$zipper_desc,1,0,'C'); 
            $pdf->Cell(25,5,$zipper_qty,1,0,'C');
            $pdf->Cell(25,5,$zipper_uom,1,0,'C');
            $pdf->Cell(25,5,$zipper_tcost,1,1,'C');
            $pdf->Cell(25,5,'Size Label:',$border,0,'C'); 
            $pdf->Cell(25,5,$side_lbl_code,1,0,'C'); 
            $pdf->Cell(80,5,$side_lbl_desc,1,0,'C'); 
            $pdf->Cell(25,5,$side_lbl_qty,1,0,'C');
            $pdf->Cell(25,5,$side_lbl_uom,1,0,'C');
            $pdf->Cell(25,5,$side_lbl_tcost,1,1,'C');
            $pdf->Cell(25,5,'Kids Label:',$border,0,'C'); 
            $pdf->Cell(25,5,$kids_lbl_code,1,0,'C'); 
            $pdf->Cell(80,5,$kids_lbl_desc,1,0,'C'); 
            $pdf->Cell(25,5,$kids_lbl_qty,1,0,'C');
            $pdf->Cell(25,5,$kids_lbl_uom,1,0,'C');
            $pdf->Cell(25,5,$kids_lbl_tcost,1,1,'C');
            $pdf->Cell(25,5,'Kids Side Label:',$border,0,'C'); 
            $pdf->Cell(25,5,$kids_side_lbl_code,1,0,'C'); 
            $pdf->Cell(80,5,$kids_side_lbl_desc,1,0,'C'); 
            $pdf->Cell(25,5,$kids_side_lbl_qty,1,0,'C');
            $pdf->Cell(25,5,$kids_side_lbl_uom,1,0,'C');
            $pdf->Cell(25,5,$kids_side_lbl_tcost,1,1,'C');
            $pdf->Cell(25,5,'Plastic Bag:',$border,0,'C'); 
            $pdf->Cell(25,5,$plastic_bag_code,1,0,'C'); 
            $pdf->Cell(80,5,$plastic_bag_desc,1,0,'C'); 
            $pdf->Cell(25,5,$plastic_bag_qty,1,0,'C');
            $pdf->Cell(25,5,$plastic_bag_uom,1,0,'C');
            $pdf->Cell(25,5,$plastic_bag_tcost,1,1,'C');
            $pdf->Cell(25,5,'Barcode:',$border,0,'C'); 
            $pdf->Cell(25,5,$barcode_code,1,0,'C'); 
            $pdf->Cell(80,5,$barcode_desc,1,0,'C'); 
            $pdf->Cell(25,5,$barcode_qty,1,0,'C');
            $pdf->Cell(25,5,$barcode_uom,1,0,'C');
            $pdf->Cell(25,5,$barcode_tcost,1,1,'C');
            $pdf->Cell(25,5,'Fitting Sticker:',$border,0,'C'); 
            $pdf->Cell(25,5,'',1,0,'C'); 
            $pdf->Cell(80,5,'',1,0,'C'); 
            $pdf->Cell(25,5,'',1,0,'C');
            $pdf->Cell(25,5,'',1,0,'C');
            $pdf->Cell(25,5,'',1,1,'C');
            $pdf->Cell(25,5,'Tag Pin:',$border,0,'C'); 
            $pdf->Cell(25,5,$tag_pin_code,1,0,'C'); 
            $pdf->Cell(80,5,$tag_pin_desc,1,0,'C'); 
            $pdf->Cell(25,5,$tag_pin_qty,1,0,'C');
            $pdf->Cell(25,5,$tag_pin_uom,1,0,'C');
            $pdf->Cell(25,5,$tag_pin_tcost,1,1,'C');
            $pdf->Cell(25,5,'Chip Board:',$border,0,'C'); 
            $pdf->Cell(25,5,$chip_board_code,1,0,'C'); 
            $pdf->Cell(80,5,$chip_board_desc,1,0,'C'); 
            $pdf->Cell(25,5,$chip_board_qty,1,0,'C');
            $pdf->Cell(25,5,$chip_board_uom,1,0,'C');
            $pdf->Cell(25,5,$chip_board_tcost,1,1,'C');
            

        }else{
            $pdf->AddPage();
			$pdf->SetAutoPageBreak(false);
            $Y = 20;
            $pdf->SetXY(5,$Y-5);
            $pdf->Cell(25,5,'SKU',1,0,'C'); 
            $pdf->Cell(25,5,'MATERIAL',1,0,'C'); 
            $pdf->Cell(80,5,'DESCRIPTION',1,0,'C'); 
            $pdf->Cell(25,5,'QTY',1,0,'C');
            $pdf->Cell(25,5,'UOM',1,0,'C');
            $pdf->Cell(25,5,'COST',1,0,'C');

            $pdf->SetLeftMargin(5);
            $pdf->SetXY(5,$Y);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(138,5,$item_data,'TBL',0,'L');
            $pdf->Cell(67,5,number_format($item_qty,2),'TRB',1,'L'); 
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25,5,'Fabric:',$border,0,'C'); 
            $pdf->Cell(25,5,$fabric_code,1,0,'C'); 
            $pdf->Cell(80,5,$fabric_desc,1,0,'C'); 
            $pdf->Cell(25,5,$fabric_qty,1,0,'C');
            $pdf->Cell(25,5,$fabric_uom,1,0,'C');
            $pdf->Cell(25,5,$fabric_tcost,1,1,'C');
            $pdf->Cell(25,5,'Lining:',$border,0,'C'); 
            $pdf->Cell(25,5,$lining_code,1,0,'C'); 
            $pdf->Cell(80,5,$lining_desc,1,0,'C'); 
            $pdf->Cell(25,5,$lining_qty,1,0,'C');
            $pdf->Cell(25,5,$lining_uom,1,0,'C');
            $pdf->Cell(25,5,$lining_tcost,1,1,'C');
            $pdf->Cell(25,5,'Button:',$border,0,'C'); 
            $pdf->Cell(25,5,$btn_code,1,0,'C'); 
            $pdf->Cell(80,5,$btn_desc,1,0,'C'); 
            $pdf->Cell(25,5,$btn_qty,1,0,'C');
            $pdf->Cell(25,5,$btn_uom,1,0,'C');
            $pdf->Cell(25,5,$btn_tcost,1,1,'C');
            $pdf->Cell(25,5,'Rivets:',$border,0,'C'); 
            $pdf->Cell(25,5,$rivets_code,1,0,'C'); 
            $pdf->Cell(80,5,$rivets_desc,1,0,'C'); 
            $pdf->Cell(25,5,$rivets_qty,1,0,'C');
            $pdf->Cell(25,5,$rivets_uom,1,0,'C');
            $pdf->Cell(25,5,$rivets_tcost,1,1,'C');
            $pdf->Cell(25,5,'Leather Patch:',$border,0,'C'); 
            $pdf->Cell(25,5,$leather_patch_code,1,0,'C'); 
            $pdf->Cell(80,5,$leather_patch_desc,1,0,'C'); 
            $pdf->Cell(25,5,$leather_patch_qty,1,0,'C');
            $pdf->Cell(25,5,$leather_patch_uom,1,0,'C');
            $pdf->Cell(25,5,$leather_patch_tcost,1,1,'C');
            $pdf->Cell(25,5,'Inside Garter:',$border,0,'C'); 
            $pdf->Cell(25,5,$inside_garter_code,1,0,'C'); 
            $pdf->Cell(80,5,$inside_garter_desc,1,0,'C'); 
            $pdf->Cell(25,5,$inside_garter_qty,1,0,'C');
            $pdf->Cell(25,5,$inside_garter_uom,1,0,'C');
            $pdf->Cell(25,5,$inside_garter_tcost,1,1,'C');
            $pdf->Cell(25,5,'Hang Tag:',$border,0,'C'); 
            $pdf->Cell(25,5,$hang_tag_code,1,0,'C'); 
            $pdf->Cell(80,5,$hang_tag_desc,1,0,'C'); 
            $pdf->Cell(25,5,$hang_tag_qty,1,0,'C');
            $pdf->Cell(25,5,$hang_tag_uom,1,0,'C');
            $pdf->Cell(25,5,$hang_tag_tcost,1,1,'C');
            $pdf->Cell(25,5,'Zipper:',$border,0,'C'); 
            $pdf->Cell(25,5,$zipper_code,1,0,'C'); 
            $pdf->Cell(80,5,$zipper_desc,1,0,'C'); 
            $pdf->Cell(25,5,$zipper_qty,1,0,'C');
            $pdf->Cell(25,5,$zipper_uom,1,0,'C');
            $pdf->Cell(25,5,$zipper_tcost,1,1,'C');
            $pdf->Cell(25,5,'Size Label:',$border,0,'C'); 
            $pdf->Cell(25,5,$side_lbl_code,1,0,'C'); 
            $pdf->Cell(80,5,$side_lbl_desc,1,0,'C'); 
            $pdf->Cell(25,5,$side_lbl_qty,1,0,'C');
            $pdf->Cell(25,5,$side_lbl_uom,1,0,'C');
            $pdf->Cell(25,5,$side_lbl_tcost,1,1,'C');
            $pdf->Cell(25,5,'Kids Label:',$border,0,'C'); 
            $pdf->Cell(25,5,$kids_lbl_code,1,0,'C'); 
            $pdf->Cell(80,5,$kids_lbl_desc,1,0,'C'); 
            $pdf->Cell(25,5,$kids_lbl_qty,1,0,'C');
            $pdf->Cell(25,5,$kids_lbl_uom,1,0,'C');
            $pdf->Cell(25,5,$kids_lbl_tcost,1,1,'C');
            $pdf->Cell(25,5,'Kids Side Label:',$border,0,'C'); 
            $pdf->Cell(25,5,$kids_side_lbl_code,1,0,'C'); 
            $pdf->Cell(80,5,$kids_side_lbl_desc,1,0,'C'); 
            $pdf->Cell(25,5,$kids_side_lbl_qty,1,0,'C');
            $pdf->Cell(25,5,$kids_side_lbl_uom,1,0,'C');
            $pdf->Cell(25,5,$kids_side_lbl_tcost,1,1,'C');
            $pdf->Cell(25,5,'Plastic Bag:',$border,0,'C'); 
            $pdf->Cell(25,5,$plastic_bag_code,1,0,'C'); 
            $pdf->Cell(80,5,$plastic_bag_desc,1,0,'C'); 
            $pdf->Cell(25,5,$plastic_bag_qty,1,0,'C');
            $pdf->Cell(25,5,$plastic_bag_uom,1,0,'C');
            $pdf->Cell(25,5,$plastic_bag_tcost,1,1,'C');
            $pdf->Cell(25,5,'Barcode:',$border,0,'C'); 
            $pdf->Cell(25,5,$barcode_code,1,0,'C'); 
            $pdf->Cell(80,5,$barcode_desc,1,0,'C'); 
            $pdf->Cell(25,5,$barcode_qty,1,0,'C');
            $pdf->Cell(25,5,$barcode_uom,1,0,'C');
            $pdf->Cell(25,5,$barcode_tcost,1,1,'C');
            $pdf->Cell(25,5,'Fitting Sticker:',$border,0,'C'); 
            $pdf->Cell(25,5,'',1,0,'C'); 
            $pdf->Cell(80,5,'',1,0,'C'); 
            $pdf->Cell(25,5,'',1,0,'C');
            $pdf->Cell(25,5,'',1,0,'C');
            $pdf->Cell(25,5,'',1,1,'C');
            $pdf->Cell(25,5,'Tag Pin:',$border,0,'C'); 
            $pdf->Cell(25,5,$tag_pin_code,1,0,'C'); 
            $pdf->Cell(80,5,$tag_pin_desc,1,0,'C'); 
            $pdf->Cell(25,5,$tag_pin_qty,1,0,'C');
            $pdf->Cell(25,5,$tag_pin_uom,1,0,'C');
            $pdf->Cell(25,5,$tag_pin_tcost,1,1,'C');
            $pdf->Cell(25,5,'Chip Board:',$border,0,'C'); 
            $pdf->Cell(25,5,$chip_board_code,1,0,'C'); 
            $pdf->Cell(80,5,$chip_board_desc,1,0,'C'); 
            $pdf->Cell(25,5,$chip_board_qty,1,0,'C');
            $pdf->Cell(25,5,$chip_board_uom,1,0,'C');
            $pdf->Cell(25,5,$chip_board_tcost,1,1,'C');
            //footer page number
            $pdf->SetY(-15);
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of RMAP NO: '.$rmap_trxno. '  Print Time:'.$print_time,0,0,'C');

            //header page number
            $pdf->SetY(5);
            $pdf->SetX(150);
            $pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of RMAP NO: '.$rmap_trxno,0,0,'C');

            
        }
        $Y = $Y + 5;
        $i = $i+ 1;
   
    }
    $Y = $Y + 80;
    
}

$pdf->SetXY(173,$Y+10);
$pdf->Cell(5,5,'TOTAL: ',0,0,'L');
$pdf->SetXY(186,$Y+10);
$pdf->Cell(24,5,number_format($total_overall,2),'B',0,'C');


$pdf->output();


?>
