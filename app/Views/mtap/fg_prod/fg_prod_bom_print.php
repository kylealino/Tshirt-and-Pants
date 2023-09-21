

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

$pdf->SetTitle('BOM-'. $fgreq_trxno);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);

$pdf->AddFont('Dot','','Calibri.php');
$pdf->SetFont('Dot','',10);

$pdf->SetXY(5,10); 
$pdf->SetFont('Dot','',15);
$pdf->Cell(112,5,'GOLDENWIN EMPIRE MARKETING CORPORATION',1,1,'L'); 


$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5+10,'Bill of Materials',0,0,'C'); 

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
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of FGPR NO: '.$fgreq_trxno. '  Print Time:'.$print_time,0,0,'C');

//header page number
$pdf->SetY(5);
$pdf->SetX(150);
$pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of FGPR NO: '.$fgreq_trxno,0,0,'C');


$str = "
SELECT 
    a.`fg_code`,
    a.`rm_code`,
    a.`item_desc`,
    (a.`item_qty` * b.`total_pack`) item_qty,
    a.`item_uom`,
    a.`item_tcost`
    FROM
    mst_item_comp2 a
    JOIN
    trx_fgpack_req_dt b
    ON
    a.`fg_code` = b.`mat_code`
    WHERE
    b.`fgreq_trxno` = '$fgreq_trxno'
";

$q3 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

$Y = 52;
$border = 1;
$item = array();

foreach ($q3->getResult() as $row) {
    $yaxis = 5;
    $fg_code = $row->fg_code;


    $rm_code = $row->rm_code;
    $item_desc = $row->item_desc;
    $item_qty = $row->item_qty;
    $item_uom = $row->item_uom;
    $item_tcost = $row->item_tcost;

    if($Y < 240){
        $border = '1';
        
        $pdf->SetLeftMargin(5);
        $pdf->SetXY(5, $Y);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 5, $fg_code, $border, 0, 'C');
        $pdf->Cell(25, 5, $rm_code, 1, 0, 'C');
        $pdf->Cell(80, 5, $item_desc, 1, 0, 'C');
        $pdf->Cell(25, 5, number_format($item_qty,2), 1, 0, 'C');
        $pdf->Cell(25, 5, $item_uom, 1, 0, 'C');
        $pdf->Cell(25, 5, $item_tcost, 1, 1, 'C');
            
    }else{

        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);


        $Y = 20;

        //ITEMS TH
        $pdf->SetFillColor(239,225,131,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(5,$Y); 
        $pdf->Cell(25,5,'SKU',1,0,'C'); 
        $pdf->Cell(25,5,'MATERIAL',1,0,'C'); 
        $pdf->Cell(80,5,'DESCRIPTION',1,0,'C'); 
        $pdf->Cell(25,5,'QTY',1,0,'C');
        $pdf->Cell(25,5,'UOM',1,0,'C');
        $pdf->Cell(25,5,'COST',1,0,'C');

                //footer page numberScreenshot from 2023-04-12 14-07-03
        $pdf->SetY(-15);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of FGPR NO: '.$fgreq_trxno. '   Print by:'.$cuser_fullname. '   Print Time:'.$print_time,0,0,'C');

        //header page number
        $pdf->SetY(5);
        $pdf->SetX(150);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}'.' of FGPR NO: '.$fgreq_trxno,0,0,'C');

        $Y = $Y + 5;

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(5,$Y); 
        $border = '1';
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(5,$Y);
        $pdf->Cell(25, 5, $fg_code, $border, 0, 'C');
        $pdf->Cell(25, 5, $rm_code, 1, 0, 'C');
        $pdf->Cell(80, 5, $item_desc, 1, 0, 'C');
        $pdf->Cell(25, 5, number_format($item_qty,2), 1, 0, 'C');
        $pdf->Cell(25, 5, $item_uom, 1, 0, 'C');
        $pdf->Cell(25, 5, $item_tcost, 1, 1, 'C');;

    }
    $Y = $Y + 5;
}


$Y = $Y + 80;
$pdf->SetXY(173,$Y+10);
$pdf->Cell(5,5,'TOTAL: ',0,0,'L');
$pdf->SetXY(186,$Y+10);
$pdf->Cell(24,5,number_format($total_overall,2),'B',0,'C');


$pdf->output();


?>
