<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytrxpurch = model('App\Models\MyPurchaseModel');
$mydataz = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(0);

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$ART_CODE = $request->getVar('ART_CODE');
$fabric_code = '';
$fabric_desc = '';
$fabric_qty = '';
$fabric_cost = '';
$fabric_tcost = '';
$fabric_uom = '';
$lining_code = '';
$lining_desc = '';
$lining_qty = '';
$lining_cost = '';
$lining_tcost = '';
$lining_uom = '';
$btn_code = '';
$btn_desc = '';
$btn_qty = '';
$btn_cost = '';
$btn_tcost = '';
$btn_uom = '';
$rivets_code = '';
$rivets_desc = '';
$rivets_qty = '';
$rivets_cost = '';
$rivets_tcost = '';
$rivets_uom = '';
$leather_patch_code = '';
$leather_patch_desc = '';
$leather_patch_qty = '';
$leather_patch_cost = '';
$leather_patch_tcost = '';
$leather_patch_uom = '';
$plastic_btn_code = '';
$plastic_btn_desc = '';
$plastic_btn_qty = '';
$plastic_btn_cost = '';
$plastic_btn_tcost = '';
$plastic_btn_uom = '';
$inside_garter_code = '';
$inside_garter_desc = '';
$inside_garter_qty = '';
$inside_garter_cost = '';
$inside_garter_tcost = '';
$inside_garter_uom = '';
$hang_tag_code = '';
$hang_tag_desc = '';
$hang_tag_qty = '';
$hang_tag_cost = '';
$hang_tag_tcost = '';
$hang_tag_uom = '';
$zipper_code = '';
$zipper_desc = '';
$zipper_qty = '';
$zipper_cost = '';
$zipper_tcost = '';
$zipper_uom = '';
$size_lbl_code = '';
$size_lbl_desc = '';
$size_lbl_qty = '';
$size_lbl_cost = '';
$size_lbl_tcost = '';
$size_lbl_uom = '';
$size_care_lbl_code = '';
$size_care_lbl_desc = '';
$size_care_lbl_qty = '';
$size_care_lbl_cost = '';
$size_care_lbl_tcost = '';
$size_care_lbl_uom = '';
$side_lbl_code = '';
$side_lbl_desc = '';
$side_lbl_qty = '';
$side_lbl_cost = '';
$side_lbl_tcost = '';
$side_lbl_uom = '';
$kids_lbl_code = '';
$kids_lbl_desc = '';
$kids_lbl_qty = '';
$kids_lbl_cost = '';
$kids_lbl_tcost = '';
$kids_lbl_uom = '';
$kids_side_lbl_code = '';
$kids_side_lbl_desc = '';
$kids_side_lbl_qty = '';
$kids_side_lbl_cost = '';
$kids_side_lbl_tcost = '';
$kids_side_lbl_uom = '';
$plastic_bag_code = '';
$plastic_bag_desc = '';
$plastic_bag_qty = '';
$plastic_bag_cost = '';
$plastic_bag_tcost = '';
$plastic_bag_uom = '';
$barcode_code = '';
$barcode_desc = '';
$barcode_qty = '';
$barcode_cost = '';
$barcode_tcost = '';
$barcode_uom = '';
$fitting_sticker_code = '';
$fitting_sticker_desc = '';
$fitting_sticker_qty = '';
$fitting_sticker_cost = '';
$fitting_sticker_tcost = '';
$fitting_sticker_uom = '';
$tag_pin_code = '';
$tag_pin_desc = '';
$tag_pin_qty = '';
$tag_pin_cost = '';
$tag_pin_tcost = '';
$tag_pin_uom = '';
$chip_board_code = '';
$chip_board_desc = '';
$chip_board_qty = '';
$chip_board_cost = '';
$chip_board_tcost = '';
$chip_board_uom = '';

if (!empty($ART_CODE)) {
  $str="
  SELECT
  `recid`,
  `ART_CODE`,
  `fabric_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = fabric_code) AS fabric_desc,
  `fabric_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = fabric_code) AS fabric_cost,
  `fabric_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = fabric_code) AS fabric_uom,
  `lining_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = lining_code) AS lining_desc,
  `lining_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = lining_code) AS lining_cost,
  `lining_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = lining_code) AS lining_uom,
  `btn_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = btn_code) AS btn_desc,
  `btn_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = btn_code) AS btn_cost,
  `btn_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = btn_code) AS btn_uom,
  `rivets_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = rivets_code) AS rivets_desc,
  `rivets_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = rivets_code) AS rivets_cost,
  `rivets_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = rivets_code) AS rivets_uom,
  `leather_patch_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = leather_patch_code) AS leather_patch_desc,
  `leather_patch_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = leather_patch_code) AS leather_patch_cost,
  `leather_patch_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = leather_patch_code) AS leather_patch_uom,
  `plastic_btn_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = plastic_btn_code) AS plastic_btn_desc,
  `plastic_btn_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = plastic_btn_code) AS plastic_btn_cost,
  `plastic_btn_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = plastic_btn_code) AS plastic_btn_uom,
  `inside_garter_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = inside_garter_code) AS inside_garter_desc,
  `inside_garter_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = inside_garter_code) AS inside_garter_cost,
  `inside_garter_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = inside_garter_code) AS inside_garter_uom,
  `hang_tag_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = hang_tag_code) AS hang_tag_desc,
  `hang_tag_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = hang_tag_code) AS hang_tag_cost,
  `hang_tag_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = hang_tag_code) AS hang_tag_uom,
  `zipper_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = zipper_code) AS zipper_desc,
  `zipper_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = zipper_code) AS zipper_cost,
  `zipper_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = zipper_code) AS zipper_uom,
  `size_lbl_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = size_lbl_code) AS size_lbl_desc,
  `size_lbl_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = size_lbl_code) AS size_lbl_cost,
  `size_lbl_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = size_lbl_code) AS size_lbl_uom,
  `size_care_lbl_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = size_care_lbl_code) AS size_care_lbl_desc,
  `size_care_lbl_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = size_care_lbl_code) AS size_care_lbl_cost,
  `size_care_lbl_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = size_care_lbl_code) AS size_care_lbl_uom,
  `side_lbl_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = side_lbl_code) AS side_lbl_desc,
  `side_lbl_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = side_lbl_code) AS side_lbl_cost,
  `side_lbl_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = side_lbl_code) AS side_lbl_uom,
  `kids_lbl_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = kids_lbl_code) AS kids_lbl_desc,
  `kids_lbl_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = kids_lbl_code) AS kids_lbl_cost,
  `kids_lbl_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = kids_lbl_code) AS kids_lbl_uom,
  `kids_side_lbl_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = kids_side_lbl_code) AS kids_side_lbl_desc,
  `kids_side_lbl_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = kids_side_lbl_code) AS kids_side_lbl_cost,
  `kids_side_lbl_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = kids_side_lbl_code) AS kids_side_lbl_uom,
  `plastic_bag_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = plastic_bag_code) AS plastic_bag_desc,
  `plastic_bag_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = plastic_bag_code) AS plastic_bag_cost,
  `plastic_bag_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = plastic_bag_code) AS plastic_bag_uom,
  `barcode_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = barcode_code) AS barcode_desc,
  `barcode_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = barcode_code) AS barcode_cost,
  `barcode_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = barcode_code) AS barcode_uom,
  `fitting_sticker_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = fitting_sticker_code) AS fitting_sticker_desc,
  `fitting_sticker_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = fitting_sticker_code) AS fitting_sticker_cost,
  `fitting_sticker_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = fitting_sticker_code) AS fitting_sticker_uom,
  `tag_pin_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = tag_pin_code) AS tag_pin_desc,
  `tag_pin_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = tag_pin_code) AS tag_pin_cost,
  `tag_pin_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = tag_pin_code) AS tag_pin_uom,
  `chip_board_code`,
  (SELECT a.`ART_DESC` FROM mst_article a WHERE a.`ART_CODE` = chip_board_code) AS chip_board_desc,
  `chip_board_qty`,
  (SELECT a.`ART_UCOST` FROM mst_article a WHERE a.`ART_CODE` = chip_board_code) AS chip_board_cost,
  `chip_board_tcost`,
  (SELECT a.`ART_UOM` FROM mst_article a WHERE a.`ART_CODE` = chip_board_code) AS chip_board_uom
  FROM
    `mst_item_comp`
  WHERE 
  `ART_CODE` = '$ART_CODE'
  ";

  $q =  $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
  $rw = $q->getRowArray();
  $ART_CODE = $rw['ART_CODE'];
  $fabric_code = $rw['fabric_code'];
  $fabric_desc = $rw['fabric_desc'];
  $fabric_qty = $rw['fabric_qty'];
  $fabric_cost = $rw['fabric_cost'];
  $fabric_tcost = $rw['fabric_tcost'];
  $fabric_uom = $rw['fabric_uom'];

  $lining_code = $rw['lining_code'];
  $lining_desc = $rw['lining_desc'];
  $lining_qty = $rw['lining_qty'];
  $lining_cost = $rw['lining_cost'];
  $lining_tcost = $rw['lining_tcost'];
  $lining_uom = $rw['lining_uom'];
  
  $btn_code = $rw['btn_code'];
  $btn_desc = $rw['btn_desc'];
  $btn_qty = $rw['btn_qty'];
  $btn_cost = $rw['btn_cost'];
  $btn_tcost = $rw['btn_tcost'];
  $btn_uom = $rw['btn_uom'];

  $rivets_code = $rw['rivets_code'];
  $rivets_desc = $rw['rivets_desc'];
  $rivets_qty = $rw['rivets_qty'];
  $rivets_cost = $rw['rivets_cost'];
  $rivets_tcost = $rw['rivets_tcost'];
  $rivets_uom = $rw['rivets_uom'];

  $leather_patch_code = $rw['leather_patch_code'];
  $leather_patch_desc = $rw['leather_patch_desc'];
  $leather_patch_qty = $rw['leather_patch_qty'];
  $leather_patch_cost = $rw['leather_patch_cost'];
  $leather_patch_tcost = $rw['leather_patch_tcost'];
  $leather_patch_uom = $rw['leather_patch_uom'];

  $plastic_btn_code = $rw['plastic_btn_code'];
  $plastic_btn_desc = $rw['plastic_btn_desc'];
  $plastic_btn_qty = $rw['plastic_btn_qty'];
  $plastic_btn_cost = $rw['plastic_btn_cost'];
  $plastic_btn_tcost = $rw['plastic_btn_tcost'];
  $plastic_btn_uom = $rw['plastic_btn_uom'];

  $inside_garter_code = $rw['inside_garter_code'];
  $inside_garter_desc = $rw['inside_garter_desc'];
  $inside_garter_qty = $rw['inside_garter_qty'];
  $inside_garter_cost = $rw['inside_garter_cost'];
  $inside_garter_tcost = $rw['inside_garter_tcost'];
  $inside_garter_uom = $rw['inside_garter_uom'];
  
  $hang_tag_code = $rw['hang_tag_code'];
  $hang_tag_desc = $rw['hang_tag_desc'];
  $hang_tag_qty = $rw['hang_tag_qty'];
  $hang_tag_cost = $rw['hang_tag_cost'];
  $hang_tag_tcost = $rw['hang_tag_tcost'];
  $hang_tag_uom = $rw['hang_tag_uom'];

  $zipper_code = $rw['zipper_code'];
  $zipper_desc = $rw['zipper_desc'];
  $zipper_qty = $rw['zipper_qty'];
  $zipper_cost = $rw['zipper_cost'];
  $zipper_tcost = $rw['zipper_tcost'];
  $zipper_uom = $rw['zipper_uom'];

  $size_lbl_code = $rw['size_lbl_code'];
  $size_lbl_desc = $rw['size_lbl_desc'];
  $size_lbl_qty = $rw['size_lbl_qty'];
  $size_lbl_cost = $rw['size_lbl_cost'];
  $size_lbl_tcost = $rw['size_lbl_tcost'];
  $size_lbl_uom = $rw['size_lbl_uom'];

  $size_care_lbl_code = $rw['size_care_lbl_code'];
  $size_care_lbl_desc = $rw['size_care_lbl_desc'];
  $size_care_lbl_qty = $rw['size_care_lbl_qty'];
  $size_care_lbl_cost = $rw['size_care_lbl_cost'];
  $size_care_lbl_tcost = $rw['size_care_lbl_tcost'];
  $size_care_lbl_uom = $rw['size_care_lbl_uom'];

  $side_lbl_code = $rw['side_lbl_code'];
  $side_lbl_desc = $rw['side_lbl_desc'];
  $side_lbl_qty = $rw['side_lbl_qty'];
  $side_lbl_cost = $rw['side_lbl_cost'];
  $side_lbl_tcost = $rw['side_lbl_tcost'];
  $side_lbl_uom = $rw['side_lbl_uom'];

  $kids_lbl_code = $rw['kids_lbl_code'];
  $kids_lbl_desc = $rw['kids_lbl_desc'];
  $kids_lbl_qty = $rw['kids_lbl_qty'];
  $kids_lbl_cost = $rw['kids_lbl_cost'];
  $kids_lbl_tcost = $rw['kids_lbl_tcost'];
  $kids_lbl_uom = $rw['kids_lbl_uom'];

  $kids_side_lbl_code = $rw['kids_side_lbl_code'];
  $kids_side_lbl_desc = $rw['kids_side_lbl_desc'];
  $kids_side_lbl_qty = $rw['kids_side_lbl_qty'];
  $kids_side_lbl_cost = $rw['kids_side_lbl_cost'];
  $kids_side_lbl_tcost = $rw['kids_side_lbl_tcost'];
  $kids_side_lbl_uom = $rw['kids_side_lbl_uom'];

  $plastic_bag_code = $rw['plastic_bag_code'];
  $plastic_bag_desc = $rw['plastic_bag_desc'];
  $plastic_bag_qty = $rw['plastic_bag_qty'];
  $plastic_bag_cost = $rw['plastic_bag_cost'];
  $plastic_bag_tcost = $rw['plastic_bag_tcost'];
  $plastic_bag_uom = $rw['plastic_bag_uom'];

  $barcode_code = $rw['barcode_code'];
  $barcode_desc = $rw['barcode_desc'];
  $barcode_qty = $rw['barcode_qty'];
  $barcode_cost = $rw['barcode_cost'];
  $barcode_tcost = $rw['barcode_tcost'];
  $barcode_uom = $rw['barcode_uom'];

  $fitting_sticker_code = $rw['fitting_sticker_code'];
  $fitting_sticker_desc = $rw['fitting_sticker_desc'];
  $fitting_sticker_qty = $rw['fitting_sticker_qty'];
  $fitting_sticker_cost = $rw['fitting_sticker_cost'];
  $fitting_sticker_tcost = $rw['fitting_sticker_tcost'];
  $fitting_sticker_uom = $rw['fitting_sticker_uom'];

  $tag_pin_code = $rw['tag_pin_code'];
  $tag_pin_desc = $rw['tag_pin_desc'];
  $tag_pin_qty = $rw['tag_pin_qty'];
  $tag_pin_cost = $rw['tag_pin_cost'];
  $tag_pin_tcost = $rw['tag_pin_tcost'];
  $tag_pin_uom = $rw['tag_pin_uom'];

  $chip_board_code = $rw['chip_board_code'];
  $chip_board_desc = $rw['chip_board_desc'];
  $chip_board_qty = $rw['chip_board_qty'];
  $chip_board_cost = $rw['chip_board_cost'];
  $chip_board_tcost = $rw['chip_board_tcost'];
  $chip_board_uom = $rw['chip_board_uom'];

}

?>
<style>
	table.memetable, th.memetable, td.memetable {
		border: 1px solid #F6F5F4;
		border-collapse: collapse;
	}
	thead.memetable, th.memetable, td.memetable {
		padding: 6px;
	}
</style>
<main id="main">
    <div class="pagetitle">
        <h1>ITEM COMPONENTS</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Item Components</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->
  <div class="row mb-3 me-form-font">
      <span id="__me_numerate_wshe__" ></span>
      <div class="col-md-12">
        <div class="card">
            <div class="card-header mb-3">
                <h3 class="h4 mb-0"> <i class="bi bi-pencil-square"></i>Entry</h3>
            </div>
          <div class="card-body">
            <?=form_open('me-item-comp-save','class="needs-validation" id="myfrms_customer" ');?>
            <div class="row mb-2">
                <div class="col-lg-6">
                    <div class="col-sm-4">
                        <h6 class="card-title p-0">Select Item code:</h6>
                        <input type="text" id="txt_fg_code" name="txt_fg_code" class="form-control form-control-sm" value="<?=$ART_CODE;?>"/>
                    </div>    
                </div>
            </div>
            <hr>
            <div class="row display:inline-block;">
                <div class="col-sm-2">
                    <h6 class="card-title">Materials:</h6>
                </div>
                <div class="col-sm-2 text-center">
                    <h6 class="card-title">Item Code</h6>
                </div>
                <div class="col-sm-2 text-center">
                    <h6 class="card-title">Description</h6>
                </div>
                <div class="col-sm-2 text-center">
                    <h6 class="card-title">QTY</h6>
                </div>
                <div class="col-sm-1 text-center">
                    <h6 class="card-title">Cost</h6>
                </div>
                <div class="col-sm-1 text-center">
                    <h6 class="card-title">Total Cost</h6>
                </div>
                <div class="col-sm-2 text-center">
                    <h6 class="card-title">UOM</h6>
                </div>
                
            </div>
            <div class="row mb-3 display:inline-block;">
                    <label class="col-sm-2  form-label" for="txt_fabric_code">Fabric:</label>
                    <div class="col-sm-2">
                        <input type="text" id="txt_fabric_code" name="txt_fabric_code" class="form-control form-control-sm" value="<?=$fabric_code;?>"/>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt_fabric_desc" name="txt_fabric_desc" class="form-control form-control-sm" value="<?=$fabric_desc;?>" readonly/>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt_fabric_qty" name="txt_fabric_qty" class="form-control form-control-sm" value="<?=$fabric_qty;?>"/>    
                    </div>
                    <div class="col-sm-1">
                        <input type="text" id="txt_fabric_cost" name="txt_fabric_cost" class="form-control form-control-sm" value="<?=$fabric_cost;?>" readonly/>    
                    </div>
                    <div class="col-sm-1">
                        <input type="text" id="txt_fabric_cost_total" name="txt_fabric_cost_total" class="form-control form-control-sm" value="<?=$fabric_tcost;?>" readonly onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();"/>    
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt_fabric_uom" name="txt_fabric_uom" class="form-control form-control-sm" value="<?=$fabric_uom;?>" readonly/>
                    </div>
            </div>
            <div class="row mb-3 display:inline-block;">
                    <label class="col-sm-2 form-label" for="txt_lining_code">Lining:</label>
                    <div class="col-sm-2">
                        <input type="text" id="txt_lining_code" name="txt_lining_code" class="form-control form-control-sm" value="<?=$lining_code;?>"/>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt_lining_desc" name="txt_lining_desc" class="form-control form-control-sm" value="<?=$lining_desc;?>" readonly/>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt_lining_qty" name="txt_lining_qty" class="form-control form-control-sm" value="<?=$lining_qty;?>"/>    
                    </div>
                    <div class="col-sm-1">
                        <input type="text" id="txt_lining_cost" name="txt_lining_cost" class="form-control form-control-sm" value="<?=$lining_cost;?>" readonly/>    
                    </div>
                    <div class="col-sm-1">
                        <input type="text" id="txt_lining_cost_total" name="txt_lining_cost_total" class="form-control form-control-sm" value="<?=$lining_tcost;?>" readonly/>    
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt_lining_uom" name="txt_lining_uom" class="form-control form-control-sm" value="<?=$lining_uom;?>" readonly/>
                    </div>
            </div>     

            <h6 class="card-title">Accessories:</h6>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_btn_code">Buttons:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_btn_code" name="txt_btn_code" class="form-control form-control-sm" value="<?=$btn_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_btn_desc" name="txt_btn_desc" class="form-control form-control-sm" value="<?=$btn_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_btn_qty" name="txt_btn_qty" class="form-control form-control-sm" value="<?=$btn_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_btn_cost" name="txt_btn_cost" class="form-control form-control-sm" value="<?=$btn_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_btn_cost_total" name="txt_btn_cost_total" class="form-control form-control-sm" value="<?=$btn_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_btn_uom" name="txt_btn_uom" class="form-control form-control-sm" value="<?=$btn_uom;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_rivets_code">Rivets:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_rivets_code" name="txt_rivets_code" class="form-control form-control-sm" value="<?=$rivets_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_rivets_desc" name="txt_rivets_desc" class="form-control form-control-sm" value="<?=$rivets_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_rivets_qty" name="txt_rivets_qty" class="form-control form-control-sm" value="<?=$rivets_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_rivets_cost" name="txt_rivets_cost" class="form-control form-control-sm" value="<?=$rivets_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_rivets_cost_total" name="txt_rivets_cost_total" class="form-control form-control-sm" value="<?=$rivets_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_rivets_uom" name="txt_rivets_uom" class="form-control form-control-sm" value="<?=$rivets_uom;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_leather_patch_code">Leather Patch:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_leather_patch_code" name="txt_leather_patch_code" class="form-control form-control-sm" value="<?=$leather_patch_code;?>" />
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_leather_patch_desc" name="txt_leather_patch_desc" class="form-control form-control-sm" value="<?=$leather_patch_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_leather_patch_qty" name="txt_leather_patch_qty" class="form-control form-control-sm" value="<?=$leather_patch_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_leather_patch_cost" name="txt_leather_patch_cost" class="form-control form-control-sm" value="<?=$leather_patch_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_leather_patch_cost_total" name="txt_leather_patch_cost_total" class="form-control form-control-sm" value="<?=$leather_patch_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_leather_patch_uom" name="txt_leather_patch_code" class="form-control form-control-sm" value="<?=$leather_patch_uom;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_plastic_btn_code">Plastic Button:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_btn_code" name="txt_plastic_btn_code" class="form-control form-control-sm" value="<?=$plastic_btn_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_btn_desc" name="txt_plastic_btn_desc" class="form-control form-control-sm" value="<?=$plastic_btn_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_btn_qty" name="txt_plastic_btn_qty" class="form-control form-control-sm" value="<?=$plastic_btn_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_plastic_btn_cost" name="txt_plastic_btn_cost" class="form-control form-control-sm" value="<?=$plastic_btn_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_plastic_btn_cost_total" name="txt_plastic_btn_cost_total" class="form-control form-control-sm" value="<?=$plastic_btn_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_btn_uom" name="txt_plastic_btn_uom" class="form-control form-control-sm" value="<?=$plastic_btn_uom;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_inside_garter_code">Inside Garter:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_inside_garter_code" name="txt_inside_garter_code" class="form-control form-control-sm" value="<?=$inside_garter_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_inside_garter_desc" name="txt_inside_garter_desc" class="form-control form-control-sm" value="<?=$inside_garter_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_inside_garter_qty" name="txt_inside_garter_qty" class="form-control form-control-sm" value="<?=$inside_garter_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_inside_garter_cost" name="txt_inside_garter_cost" class="form-control form-control-sm" value="<?=$inside_garter_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_inside_garter_cost_total" name="txt_inside_garter_cost_total" class="form-control form-control-sm" value="<?=$inside_garter_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_inside_garter_uom" name="txt_inside_garter_uom" class="form-control form-control-sm" value="<?=$inside_garter_uom;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_hang_tag_code">Hang Tag:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_hang_tag_code" name="txt_hang_tag_code" class="form-control form-control-sm" value="<?=$hang_tag_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_hang_tag_desc" name="txt_hang_tag_desc" class="form-control form-control-sm" value="<?=$hang_tag_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_hang_tag_qty" name="txt_hang_tag_qty" class="form-control form-control-sm" value="<?=$hang_tag_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_hang_tag_cost" name="txt_hang_tag_cost" class="form-control form-control-sm" value="<?=$hang_tag_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_hang_tag_cost_total" name="txt_hang_tag_cost_total" class="form-control form-control-sm" value="<?=$hang_tag_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_hang_tag_uom" name="txt_hang_tag_uom" class="form-control form-control-sm" value="<?=$hang_tag_uom;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_zipper_code">Zipper:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_zipper_code" name="txt_zipper_code" class="form-control form-control-sm" value="<?=$zipper_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_zipper_desc" name="txt_zipper_desc" class="form-control form-control-sm" value="<?=$zipper_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_zipper_qty" name="txt_zipper_qty" class="form-control form-control-sm" value="<?=$zipper_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_zipper_cost" name="txt_zipper_cost" class="form-control form-control-sm" value="<?=$zipper_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_zipper_cost_total" name="txt_zipper_cost_total" class="form-control form-control-sm" value="<?=$zipper_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_zipper_uom" name="txt_zipper_uom" class="form-control form-control-sm" value="<?=$zipper_uom;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_size_lbl_code">Size Label:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_lbl_code" name="txt_size_lbl_code" class="form-control form-control-sm" value="<?=$size_lbl_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_lbl_desc" name="txt_size_lbl_desc" class="form-control form-control-sm" value="<?=$size_lbl_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_lbl_qty" name="txt_size_lbl_qty" class="form-control form-control-sm" value="<?=$size_lbl_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_size_lbl_cost" name="txt_size_lbl_cost" class="form-control form-control-sm" value="<?=$size_lbl_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_size_lbl_cost_total" name="txt_size_lbl_cost_total" class="form-control form-control-sm" value="<?=$size_lbl_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_lbl_uom" name="txt_size_lbl_uom" class="form-control form-control-sm" value="<?=$size_lbl_uom;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_size_care_lbl_code">Size Care Label:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_care_lbl_code" name="txt_size_care_lbl_code" class="form-control form-control-sm" value="<?=$size_care_lbl_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_care_lbl_desc" name="txt_size_care_lbl_desc" class="form-control form-control-sm" value="<?=$size_care_lbl_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_care_lbl_qty" name="txt_size_care_lbl_qty" class="form-control form-control-sm" value="<?=$size_care_lbl_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_size_care_lbl_cost" name="txt_size_care_lbl_cost" class="form-control form-control-sm" value="<?=$size_care_lbl_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_size_care_lbl_cost_total" name="txt_size_care_lbl_cost_total" class="form-control form-control-sm" value="<?=$size_care_lbl_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_care_lbl_uom" name="txt_size_care_lbl_uom" class="form-control form-control-sm" value="<?=$size_care_lbl_uom;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_side_lbl_code">Side Label:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_side_lbl_code" name="txt_side_lbl_code" class="form-control form-control-sm" value="<?=$side_lbl_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_side_lbl_desc" name="txt_side_lbl_desc" class="form-control form-control-sm" value="<?=$side_lbl_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_side_lbl_qty" name="txt_side_lbl_qty" class="form-control form-control-sm" value="<?=$side_lbl_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_side_lbl_cost" name="txt_side_lbl_cost" class="form-control form-control-sm" value="<?=$side_lbl_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_side_lbl_cost_total" name="txt_side_lbl_cost_total" class="form-control form-control-sm" value="<?=$side_lbl_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_side_lbl_uom" name="txt_side_lbl_uom" class="form-control form-control-sm" value="<?=$side_lbl_uom;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_kids_lbl_code">Kids Label:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_lbl_code" name="txt_kids_lbl_code" class="form-control form-control-sm" value="<?=$kids_lbl_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_lbl_desc" name="txt_kids_lbl_desc" class="form-control form-control-sm"  value="<?=$kids_lbl_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_lbl_qty" name="txt_kids_lbl_qty" class="form-control form-control-sm"  value="<?=$kids_lbl_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_kids_lbl_cost" name="txt_kids_lbl_cost" class="form-control form-control-sm"  value="<?=$kids_lbl_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_kids_lbl_cost_total" name="txt_kids_lbl_cost_total" class="form-control form-control-sm"  value="<?=$kids_lbl_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_lbl_uom" name="txt_kids_lbl_uom" class="form-control form-control-sm"  value="<?=$kids_lbl_uom;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_kids_side_lbl_code">Kids Side Label:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_side_lbl_code" name="txt_kids_side_lbl_code" class="form-control form-control-sm"  value="<?=$kids_side_lbl_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_side_lbl_desc" name="txt_kids_side_lbl_desc" class="form-control form-control-sm" value="<?=$kids_side_lbl_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_side_lbl_qty" name="txt_kids_side_lbl_qty" class="form-control form-control-sm" value="<?=$kids_side_lbl_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_kids_side_lbl_cost" name="txt_kids_side_lbl_cost" class="form-control form-control-sm" value="<?=$kids_side_lbl_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_kids_side_lbl_cost_total" name="txt_kids_side_lbl_cost_total" class="form-control form-control-sm" value="<?=$kids_side_lbl_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_side_lbl_uom" name="txt_kids_side_lbl_code" class="form-control form-control-sm" value="<?=$kids_side_lbl_uom;?>" readonly/>
                      </div>
                </div> 

            <h6 class="card-title">Packaging:</h6>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_plastic_bag_code">Plastic Bag:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_bag_code" name="txt_plastic_bag_code" class="form-control form-control-sm" value="<?=$plastic_bag_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_bag_desc" name="txt_plastic_bag_desc" class="form-control form-control-sm" value="<?=$plastic_bag_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_bag_qty" name="txt_plastic_bag_qty" class="form-control form-control-sm" value="<?=$plastic_bag_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_plastic_bag_cost" name="txt_plastic_bag_cost" class="form-control form-control-sm" value="<?=$plastic_bag_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_plastic_bag_cost_total" name="txt_plastic_bag_cost_total" class="form-control form-control-sm" value="<?=$plastic_bag_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_bag_uom" name="txt_plastic_bag_uom" class="form-control form-control-sm" value="<?=$plastic_bag_uom;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_barcode_code">Barcode:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_barcode_code" name="txt_barcode_code" class="form-control form-control-sm" value="<?=$barcode_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_barcode_desc" name="txt_barcode_desc" class="form-control form-control-sm" value="<?=$barcode_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_barcode_qty" name="txt_barcode_qty" class="form-control form-control-sm" value="<?=$barcode_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_barcode_cost" name="txt_barcode_cost" class="form-control form-control-sm" value="<?=$barcode_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_barcode_cost_total" name="txt_barcode_cost_total" class="form-control form-control-sm" value="<?=$barcode_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_barcode_uom" name="txt_barcode_uom" class="form-control form-control-sm" value="<?=$barcode_uom;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_fitting_sticker_code">Fitting Sticker:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_fitting_sticker_code" name="txt_fitting_sticker_code" class="form-control form-control-sm" value="<?=$fitting_sticker_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_fitting_sticker_desc" name="txt_fitting_sticker_desc" class="form-control form-control-sm" value="<?=$fitting_sticker_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_fitting_sticker_qty" name="txt_fitting_sticker_qty" class="form-control form-control-sm" value="<?=$fitting_sticker_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_fitting_sticker_cost" name="txt_fitting_sticker_cost" class="form-control form-control-sm" value="<?=$fitting_sticker_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_fitting_sticker_cost_total" name="txt_fitting_sticker_cost_total" class="form-control form-control-sm" value="<?=$fitting_sticker_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_fitting_sticker_uom" name="txt_fitting_sticker_uom" class="form-control form-control-sm" value="<?=$fitting_sticker_uom;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_tag_pin_code">Tag pin:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_tag_pin_code" name="txt_tag_pin_code" class="form-control form-control-sm" value="<?=$tag_pin_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_tag_pin_desc" name="txt_tag_pin_desc" class="form-control form-control-sm" value="<?=$tag_pin_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_tag_pin_qty" name="txt_tag_pin_qty" class="form-control form-control-sm" value="<?=$tag_pin_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_tag_pin_cost" name="txt_tag_pin_cost" class="form-control form-control-sm" value="<?=$tag_pin_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_tag_pin_cost_total" name="txt_tag_pin_cost_total" class="form-control form-control-sm" value="<?=$tag_pin_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_tag_pin_uom" name="txt_tag_pin_uom" class="form-control form-control-sm" value="<?=$tag_pin_uom;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_chip_board_code">Chip Board:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_chip_board_code" name="txt_chip_board_code" class="form-control form-control-sm" value="<?=$chip_board_code;?>"/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_chip_board_desc" name="txt_chip_board_desc" class="form-control form-control-sm" value="<?=$chip_board_desc;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_chip_board_qty" name="txt_chip_board_qty" class="form-control form-control-sm" value="<?=$chip_board_qty;?>"/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_chip_board_cost" name="txt_chip_board_cost" class="form-control form-control-sm" value="<?=$chip_board_cost;?>" readonly/>    
                      </div>
                      <div class="col-sm-1">
                          <input type="text" id="txt_chip_board_cost_total" name="txt_chip_board_cost_total" class="form-control form-control-sm" value="<?=$chip_board_tcost;?>" readonly/>    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_chip_board_uom" name="txt_chip_board_uom" class="form-control form-control-sm" value="<?=$chip_board_uom;?>" readonly/>
                      </div>
                </div>      
            
            <div class="row gy-2 mb-3">
              <?php if(!empty($ART_CODE)): ?>
                <div class="col-sm-4">
                  <button id="mbtn_mn_update" type="submit" class="btn btn-dgreen btn-sm">Update</button>
                  <button id="mbtn_mn_NTRX" type="button" class="btn btn-primary btn-sm">New Entry</button>
                </div>
                <?php else:?>
                <div class="col-sm-4">
                  <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
                  <!-- <button id="mbtn_mn_NTRX" type="button" class="btn btn-primary btn-sm">New Trx</button> -->
                  <button id="mbtn_mn_NTRX" type="button" class="btn btn-primary btn-sm">New Entry</button>
                </div>
              <?php endif;?>
            </div> <!-- end Save Records -->
            <?=form_close();?> <!-- end of ./form -->
            </div> <!-- end card-body -->
          </div>

          <div class="col-md-12">
              <div class="card">
                <div class="card-header mb-3">
                      <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i>Records</h3>
                  </div>
                <div class="card-body">
                  <div class="pt-2 bg-dgreen mt-2"> 
                    <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                      <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> List</a>
                      <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> Items</a>
                    </nav>
                    </div>
                        
                    <div id="packlist" class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">
                          <?php

                          ?> 
                      </div> 
                </div> 
              </div>
          </div>
        
          </div> 
        </div>
      </div>
    </div>
  </div> <!-- end row -->
  <?php
      echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
      echo $mylibzsys->memypreloader01('mepreloaderme');
      echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>  
</main>    

<script type="text/javascript">
$(document).ready(function() {
  var resultPerformed = false;

  $('#txt_fabric_cost_total').mouseover(function() {

    var txt_fabric_qty = $('#txt_fabric_qty').val();
    var txt_fabric_cost = $('#txt_fabric_cost').val();

    const result = txt_fabric_qty * txt_fabric_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_fabric_cost_total').val(decimalValue);

  });

  $('#txt_lining_cost_total').mouseover(function() {

    var txt_lining_qty = $('#txt_lining_qty').val();
    var txt_lining_cost = $('#txt_lining_cost').val();

    const result = txt_lining_qty * txt_lining_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_lining_cost_total').val(decimalValue);

  });

  $('#txt_btn_cost_total').mouseover(function() {

    var txt_btn_qty = $('#txt_btn_qty').val();
    var txt_btn_cost = $('#txt_btn_cost').val();

    const result = txt_btn_qty * txt_btn_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_btn_cost_total').val(decimalValue);

  });

  $('#txt_rivets_cost_total').mouseover(function() {

    var txt_rivets_qty = $('#txt_rivets_qty').val();
    var txt_rivets_cost = $('#txt_rivets_cost').val();

    const result = txt_rivets_qty * txt_rivets_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_rivets_cost_total').val(decimalValue);

  });

  $('#txt_leather_patch_cost_total').mouseover(function() {

    var txt_leather_patch_qty = $('#txt_leather_patch_qty').val();
    var txt_leather_patch_cost = $('#txt_leather_patch_cost').val();

    const result = txt_leather_patch_qty * txt_leather_patch_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_leather_patch_cost_total').val(decimalValue);

  });

  $('#txt_plastic_btn_cost_total').mouseover(function() {

    var txt_plastic_btn_qty = $('#txt_plastic_btn_qty').val();
    var txt_plastic_btn_cost = $('#txt_plastic_btn_cost').val();

    const result = txt_plastic_btn_qty * txt_plastic_btn_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_plastic_btn_cost_total').val(decimalValue);

  });

  $('#txt_inside_garter_cost_total').mouseover(function() {

    var txt_inside_garter_qty = $('#txt_inside_garter_qty').val();
    var txt_inside_garter_cost = $('#txt_inside_garter_cost').val();

    const result = txt_inside_garter_qty * txt_inside_garter_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_inside_garter_cost_total').val(decimalValue);

  });

  $('#txt_hang_tag_cost_total').mouseover(function() {

    var txt_hang_tag_qty = $('#txt_hang_tag_qty').val();
    var txt_hang_tag_cost = $('#txt_hang_tag_cost').val();

    const result = txt_hang_tag_qty * txt_hang_tag_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_hang_tag_cost_total').val(decimalValue);

  });

  $('#txt_zipper_cost_total').mouseover(function() {

    var txt_zipper_qty = $('#txt_zipper_qty').val();
    var txt_zipper_cost = $('#txt_zipper_cost').val();

    const result = txt_zipper_qty * txt_zipper_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_zipper_cost_total').val(decimalValue);

  });

  $('#txt_size_lbl_cost_total').mouseover(function() {

    var txt_size_lbl_qty = $('#txt_size_lbl_qty').val();
    var txt_size_lbl_cost = $('#txt_size_lbl_cost').val();

    const result = txt_size_lbl_qty * txt_size_lbl_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_size_lbl_cost_total').val(decimalValue);

  });

  $('#txt_size_care_lbl_cost_total').mouseover(function() {

    var txt_size_care_lbl_qty = $('#txt_size_care_lbl_qty').val();
    var txt_size_care_lbl_cost = $('#txt_size_care_lbl_cost').val();

    const result = txt_size_care_lbl_qty * txt_size_care_lbl_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_size_care_lbl_cost_total').val(decimalValue);

  });

  $('#txt_side_lbl_cost_total').mouseover(function() {

    var txt_side_lbl_qty = $('#txt_side_lbl_qty').val();
    var txt_side_lbl_cost = $('#txt_side_lbl_cost').val();

    const result = txt_side_lbl_qty * txt_side_lbl_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_side_lbl_cost_total').val(decimalValue);

  });

  $('#txt_kids_lbl_cost_total').mouseover(function() {

    var txt_kids_lbl_qty = $('#txt_kids_lbl_qty').val();
    var txt_kids_lbl_cost = $('#txt_kids_lbl_cost').val();

    const result = txt_kids_lbl_qty * txt_kids_lbl_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_kids_lbl_cost_total').val(decimalValue);

  });

  $('#txt_kids_side_lbl_cost_total').mouseover(function() {

    var txt_kids_side_lbl_qty = $('#txt_kids_side_lbl_qty').val();
    var txt_kids_side_lbl_cost = $('#txt_kids_side_lbl_cost').val();

    const result = txt_kids_side_lbl_qty * txt_kids_side_lbl_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_kids_side_lbl_cost_total').val(decimalValue);

  });

  $('#txt_plastic_bag_cost_total').mouseover(function() {

    var txt_plastic_bag_qty = $('#txt_plastic_bag_qty').val();
    var txt_plastic_bag_cost = $('#txt_plastic_bag_cost').val();

    const result = txt_plastic_bag_qty * txt_plastic_bag_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_plastic_bag_cost_total').val(decimalValue);

  });

  $('#txt_barcode_cost_total').mouseover(function() {

    var txt_barcode_qty = $('#txt_barcode_qty').val();
    var txt_barcode_cost = $('#txt_barcode_cost').val();

    const result = txt_barcode_qty * txt_barcode_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_barcode_cost_total').val(decimalValue);

  });

  $('#txt_barcode_cost_total').mouseover(function() {

    var txt_barcode_qty = $('#txt_barcode_qty').val();
    var txt_barcode_cost = $('#txt_barcode_cost').val();

    const result = txt_barcode_qty * txt_barcode_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_barcode_cost_total').val(decimalValue);

  });

  $('#txt_fitting_sticker_cost_total').mouseover(function() {

    var txt_fitting_sticker_qty = $('#txt_fitting_sticker_qty').val();
    var txt_fitting_sticker_cost = $('#txt_fitting_sticker_cost').val();

    const result = txt_fitting_sticker_qty * txt_fitting_sticker_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_fitting_sticker_cost_total').val(decimalValue);

  });

  $('#txt_tag_pin_cost_total').mouseover(function() {

    var txt_tag_pin_qty = $('#txt_tag_pin_qty').val();
    var txt_tag_pin_cost = $('#txt_tag_pin_cost').val();

    const result = txt_tag_pin_qty * txt_tag_pin_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_tag_pin_cost_total').val(decimalValue);

  });

  $('#txt_chip_board_cost_total').mouseover(function() {

    var txt_chip_board_qty = $('#txt_chip_board_qty').val();
    var txt_chip_board_cost = $('#txt_chip_board_cost').val();

    const result = txt_chip_board_qty * txt_chip_board_cost;
    const decimalValue = (result).toFixed(5);
    $('#txt_chip_board_cost_total').val(decimalValue);

  });

});

$('#mbtn_mn_NTRX').click(function() { 
        var userselection = confirm("Are you sure you want to new transaction?");
        if (userselection == true){
            window.location = '<?=site_url();?>me-item-comp-vw';
         }
        else{
            $.hideLoading();
            return false;
        } 
    });

item_comp_recs();
    function item_comp_recs(mtkn_whse){ 
        var ajaxRequest;

        ajaxRequest = jQuery.ajax({
            url: "<?=site_url();?>item-comp-recs",
            type: "post",
            data: {
              mtkn_whse: mtkn_whse
            }
        });

          // Deal with the results of the above ajax call
         // $.showLoading({name: 'line-pulse', allowHide: false });
          ajaxRequest.done(function(response, textStatus, jqXHR) {
              jQuery('#myoutdrecs').html(response);
         
             // $.hideLoading();
              // and do it again
              //setTimeout(get_if_stats, 5000);
          });
      };
    
$("#mbtn_mn_Save").click(function(e){
       
       try { 
         //__mysys_apps.mepreloader('mepreloaderme',true);
          var txt_fg_code = jQuery('#txt_fg_code').val();
          var txt_fabric_code = jQuery('#txt_fabric_code').val();
          var txt_fabric_qty = jQuery('#txt_fabric_qty').val();
          var txt_fabric_tcost = jQuery('#txt_fabric_cost_total').val();

          var txt_lining_code = jQuery('#txt_lining_code').val();
          var txt_lining_qty = jQuery('#txt_lining_qty').val();
          var txt_lining_tcost = jQuery('#txt_lining_cost_total').val();

          var txt_btn_code = jQuery('#txt_btn_code').val();
          var txt_btn_qty = jQuery('#txt_btn_qty').val();
          var txt_btn_tcost = jQuery('#txt_btn_cost_total').val();

          var txt_rivets_code = jQuery('#txt_rivets_code').val();
          var txt_rivets_qty = jQuery('#txt_rivets_qty').val();
          var txt_rivets_tcost = jQuery('#txt_rivets_cost_total').val();

          var txt_leather_patch_code =  jQuery('#txt_leather_patch_code').val();
          var txt_leather_patch_qty = jQuery('#txt_leather_patch_qty').val();
          var txt_leather_patch_tcost = jQuery('#txt_leather_patch_cost_total').val();

          var txt_plastic_btn_code = jQuery('#txt_plastic_btn_code').val();
          var txt_plastic_btn_qty = jQuery('#txt_plastic_btn_qty').val();
          var txt_plastic_btn_tcost = jQuery('#txt_plastic_btn_cost_total').val();

          var txt_inside_garter_code  = jQuery('#txt_inside_garter_code').val();
          var txt_inside_garter_qty = jQuery('#txt_inside_garter_qty').val();
          var txt_inside_garter_tcost = jQuery('#txt_inside_garter_cost_total').val();

          var txt_hang_tag_code = jQuery('#txt_hang_tag_code').val();
          var txt_hang_tag_qty = jQuery('#txt_hang_tag_qty').val();
          var txt_hang_tag_tcost = jQuery('#txt_hang_tag_cost_total').val();

          var txt_zipper_code = jQuery('#txt_zipper_code').val();
          var txt_zipper_qty = jQuery('#txt_zipper_qty').val();
          var txt_zipper_tcost = jQuery('#txt_zipper_cost_total').val();

          var txt_size_lbl_code = jQuery('#txt_size_lbl_code').val();
          var txt_size_lbl_qty = jQuery('#txt_size_lbl_qty').val();
          var txt_size_lbl_tcost = jQuery('#txt_size_lbl_cost_total').val();

          var txt_size_care_lbl_code = jQuery('#txt_size_care_lbl_code').val();
          var txt_size_care_lbl_qty = jQuery('#txt_size_care_lbl_qty').val();
          var txt_size_care_lbl_tcost = jQuery('#txt_size_care_lbl_cost_total').val();

          var txt_side_lbl_code = jQuery('#txt_side_lbl_code').val();
          var txt_side_lbl_qty = jQuery('#txt_side_lbl_qty').val();
          var txt_side_lbl_tcost = jQuery('#txt_side_lbl_cost_total').val();

          var txt_kids_lbl_code = jQuery('#txt_kids_lbl_code').val();
          var txt_kids_lbl_qty = jQuery('#txt_kids_lbl_qty').val();
          var txt_kids_lbl_tcost = jQuery('#txt_kids_lbl_cost_total').val();

          var txt_kids_side_lbl_code = jQuery('#txt_kids_side_lbl_code').val();
          var txt_kids_side_lbl_qty = jQuery('#txt_kids_side_lbl_qty').val();
          var txt_kids_side_lbl_tcost = jQuery('#txt_kids_side_lbl_cost_total').val();

          var txt_plastic_bag_code = jQuery('#txt_plastic_bag_code').val();
          var txt_plastic_bag_qty = jQuery('#txt_plastic_bag_qty').val();
          var txt_plastic_bag_tcost = jQuery('#txt_plastic_bag_cost_total').val();

          var txt_barcode_code = jQuery('#txt_barcode_code').val();
          var txt_barcode_qty = jQuery('#txt_barcode_qty').val();
          var txt_barcode_tcost = jQuery('#txt_barcode_cost_total').val();

          var txt_fitting_sticker_code = jQuery('#txt_fitting_sticker_code').val();
          var txt_fitting_sticker_qty = jQuery('#txt_fitting_sticker_qty').val();
          var txt_fitting_sticker_tcost = jQuery('#txt_fitting_sticker_cost_total').val();

          var txt_tag_pin_code = jQuery('#txt_tag_pin_code').val();
          var txt_tag_pin_qty = jQuery('#txt_tag_pin_qty').val();
          var txt_tag_pin_tcost = jQuery('#txt_tag_pin_cost_total').val();

          var txt_chip_board_code = jQuery('#txt_chip_board_code').val();
          var txt_chip_board_qty = jQuery('#txt_chip_board_qty').val();
          var txt_chip_board_tcost = jQuery('#txt_chip_board_cost_total').val();

           var mparam = {
            txt_fg_code:txt_fg_code,
            txt_fabric_code:txt_fabric_code,
            txt_fabric_qty:txt_fabric_qty,
            txt_fabric_tcost:txt_fabric_tcost,
            txt_lining_code:txt_lining_code,
            txt_lining_qty:txt_lining_qty,
            txt_lining_tcost:txt_lining_tcost,
            txt_btn_code:txt_btn_code,
            txt_btn_qty:txt_btn_qty,
            txt_btn_tcost:txt_btn_tcost,
            txt_rivets_code:txt_rivets_code,
            txt_rivets_qty:txt_rivets_qty,
            txt_rivets_tcost:txt_rivets_tcost,
            txt_leather_patch_code:txt_leather_patch_code,
            txt_leather_patch_qty:txt_leather_patch_qty,
            txt_leather_patch_tcost:txt_leather_patch_tcost,
            txt_plastic_btn_code:txt_plastic_btn_code,
            txt_plastic_btn_qty:txt_plastic_btn_qty,
            txt_plastic_btn_tcost:txt_plastic_btn_tcost,
            txt_inside_garter_code:txt_inside_garter_code,
            txt_inside_garter_qty:txt_inside_garter_qty,
            txt_inside_garter_tcost:txt_inside_garter_tcost,
            txt_hang_tag_code:txt_hang_tag_code,
            txt_hang_tag_qty:txt_hang_tag_qty,
            txt_hang_tag_tcost:txt_hang_tag_tcost,
            txt_zipper_code:txt_zipper_code,
            txt_zipper_qty:txt_zipper_qty,
            txt_zipper_tcost:txt_zipper_tcost,
            txt_size_lbl_code:txt_size_lbl_code,
            txt_size_lbl_qty:txt_size_lbl_qty,
            txt_size_lbl_tcost:txt_size_lbl_tcost,
            txt_size_care_lbl_code:txt_size_care_lbl_code,
            txt_size_care_lbl_qty:txt_size_care_lbl_qty,
            txt_size_care_lbl_tcost:txt_size_care_lbl_tcost,
            txt_side_lbl_code:txt_side_lbl_code,
            txt_side_lbl_qty:txt_side_lbl_qty,
            txt_side_lbl_tcost:txt_side_lbl_tcost,
            txt_kids_lbl_code:txt_kids_lbl_code,
            txt_kids_lbl_qty:txt_kids_lbl_qty,
            txt_kids_lbl_tcost:txt_kids_lbl_tcost,
            txt_kids_side_lbl_code:txt_kids_side_lbl_code,
            txt_kids_side_lbl_qty:txt_kids_side_lbl_qty,
            txt_kids_side_lbl_tcost:txt_kids_side_lbl_tcost,
            txt_plastic_bag_code:txt_plastic_bag_code,
            txt_plastic_bag_qty:txt_plastic_bag_qty,
            txt_plastic_bag_tcost:txt_plastic_bag_tcost,
            txt_barcode_code:txt_barcode_code,
            txt_barcode_qty:txt_barcode_qty,
            txt_barcode_tcost:txt_barcode_tcost,
            txt_fitting_sticker_code:txt_fitting_sticker_code,
            txt_fitting_sticker_qty:txt_fitting_sticker_qty,
            txt_fitting_sticker_tcost:txt_fitting_sticker_tcost,
            txt_tag_pin_code:txt_tag_pin_code,
            txt_tag_pin_qty:txt_tag_pin_qty,
            txt_tag_pin_tcost:txt_tag_pin_tcost,
            txt_chip_board_code:txt_chip_board_code,
            txt_chip_board_qty:txt_chip_board_qty,
            txt_chip_board_tcost:txt_chip_board_tcost
           };  

           console.log(mparam);
           
           $.ajax({ 
             type: "POST",
             url: '<?=site_url();?>me-item-comp-save',
             context: document.body,
             data: eval(mparam),
             global: false,
             cache: false,
             success: function(data)  { 
               $(this).prop('disabled', false);
          // $.hideLoading();
          jQuery('#memsgtestent_bod').html(data);
          jQuery('#memsgtestent').modal('show');
          return false;
        },
        error: function() {
         alert('error loading page...');
        // $.hideLoading();
        return false;
      } 
    });

         } catch(err) {
           var mtxt = 'There was an error on this page.\n';
           mtxt += 'Error description: ' + err.message;
           mtxt += '\nClick OK to continue.';
           alert(mtxt);
   }  //end try
   return false; 
 });
  

  __mysys_apps.mepreloader('mepreloaderme',false);

  $("#mbtn_mn_update").click(function(e){
       
       try { 
         //__mysys_apps.mepreloader('mepreloaderme',true);
          var txt_fg_code = jQuery('#txt_fg_code').val();
          var txt_fabric_code = jQuery('#txt_fabric_code').val();
          var txt_fabric_qty = jQuery('#txt_fabric_qty').val();

          var txt_lining_code = jQuery('#txt_lining_code').val();
          var txt_lining_qty = jQuery('#txt_lining_qty').val();

          var txt_btn_code = jQuery('#txt_btn_code').val();
          var txt_btn_qty = jQuery('#txt_btn_qty').val();

          var txt_rivets_code = jQuery('#txt_rivets_code').val();
          var txt_rivets_qty = jQuery('#txt_rivets_qty').val();

          var txt_leather_patch_code =  jQuery('#txt_leather_patch_code').val();
          var txt_leather_patch_qty = jQuery('#txt_leather_patch_qty').val();

          var txt_plastic_btn_code = jQuery('#txt_plastic_btn_code').val();
          var txt_plastic_btn_qty = jQuery('#txt_plastic_btn_qty').val();

          var txt_inside_garter_code  = jQuery('#txt_inside_garter_code').val();
          var txt_inside_garter_qty = jQuery('#txt_inside_garter_qty').val();

          var txt_hang_tag_code = jQuery('#txt_hang_tag_code').val();
          var txt_hang_tag_qty = jQuery('#txt_hang_tag_qty').val();

          var txt_zipper_code = jQuery('#txt_zipper_code').val();
          var txt_zipper_qty = jQuery('#txt_zipper_qty').val();

          var txt_size_lbl_code = jQuery('#txt_size_lbl_code').val();
          var txt_size_lbl_qty = jQuery('#txt_size_lbl_qty').val();

          var txt_size_care_lbl_code = jQuery('#txt_size_care_lbl_code').val();
          var txt_size_care_lbl_qty = jQuery('#txt_size_care_lbl_qty').val();

          var txt_side_lbl_code = jQuery('#txt_side_lbl_code').val();
          var txt_side_lbl_qty = jQuery('#txt_side_lbl_qty').val();

          var txt_kids_lbl_code = jQuery('#txt_kids_lbl_code').val();
          var txt_kids_lbl_qty = jQuery('#txt_kids_lbl_qty').val();

          var txt_kids_side_lbl_code = jQuery('#txt_kids_side_lbl_code').val();
          var txt_kids_side_lbl_qty = jQuery('#txt_kids_side_lbl_qty').val();

          var txt_plastic_bag_code = jQuery('#txt_plastic_bag_code').val();
          var txt_plastic_bag_qty = jQuery('#txt_plastic_bag_qty').val();

          var txt_barcode_code = jQuery('#txt_barcode_code').val();
          var txt_barcode_qty = jQuery('#txt_barcode_qty').val();

          var txt_fitting_sticker_code = jQuery('#txt_fitting_sticker_code').val();
          var txt_fitting_sticker_qty = jQuery('#txt_fitting_sticker_qty').val();

          var txt_tag_pin_code = jQuery('#txt_tag_pin_code').val();
          var txt_tag_pin_qty = jQuery('#txt_tag_pin_qty').val();

          var txt_chip_board_code = jQuery('#txt_chip_board_code').val();
          var txt_chip_board_qty = jQuery('#txt_chip_board_qty').val();

           var mparam = {
            txt_fg_code:txt_fg_code,
            txt_fabric_code:txt_fabric_code,
            txt_fabric_qty:txt_fabric_qty,
            txt_lining_code:txt_lining_code,
            txt_lining_qty:txt_lining_qty,
            txt_btn_code:txt_btn_code,
            txt_btn_qty:txt_btn_qty,
            txt_rivets_code:txt_rivets_code,
            txt_rivets_qty:txt_rivets_qty,
            txt_leather_patch_code:txt_leather_patch_code,
            txt_leather_patch_qty:txt_leather_patch_qty,
            txt_plastic_btn_code:txt_plastic_btn_code,
            txt_plastic_btn_qty:txt_plastic_btn_qty,
            txt_inside_garter_code:txt_inside_garter_code,
            txt_inside_garter_qty:txt_inside_garter_qty,
            txt_hang_tag_code:txt_hang_tag_code,
            txt_hang_tag_qty:txt_hang_tag_qty,
            txt_zipper_code:txt_zipper_code,
            txt_zipper_qty:txt_zipper_qty,
            txt_size_lbl_code:txt_size_lbl_code,
            txt_size_lbl_qty:txt_size_lbl_qty,
            txt_size_care_lbl_code:txt_size_care_lbl_code,
            txt_size_care_lbl_qty:txt_size_care_lbl_qty,
            txt_side_lbl_code:txt_side_lbl_code,
            txt_side_lbl_qty:txt_side_lbl_qty,
            txt_kids_lbl_code:txt_kids_lbl_code,
            txt_kids_lbl_qty:txt_kids_lbl_qty,
            txt_kids_side_lbl_code:txt_kids_side_lbl_code,
            txt_kids_side_lbl_qty:txt_kids_side_lbl_qty,
            txt_plastic_bag_code:txt_plastic_bag_code,
            txt_plastic_bag_qty:txt_plastic_bag_qty,
            txt_barcode_code:txt_barcode_code,
            txt_barcode_qty:txt_barcode_qty,
            txt_fitting_sticker_code:txt_fitting_sticker_code,
            txt_fitting_sticker_qty:txt_fitting_sticker_qty,
            txt_tag_pin_code:txt_tag_pin_code,
            txt_tag_pin_qty:txt_tag_pin_qty,
            txt_chip_board_code:txt_chip_board_code,
            txt_chip_board_qty:txt_chip_board_qty
           };  

           console.log(mparam);
           
           $.ajax({ 
             type: "POST",
             url: '<?=site_url();?>me-item-comp-update',
             context: document.body,
             data: eval(mparam),
             global: false,
             cache: false,
             success: function(data)  { 
               $(this).prop('disabled', false);
          // $.hideLoading();
          jQuery('#memsgtestent_bod').html(data);
          jQuery('#memsgtestent').modal('show');
          return false;
        },
        error: function() {
         alert('error loading page...');
        // $.hideLoading();
        return false;
      } 
    });

         } catch(err) {
           var mtxt = 'There was an error on this page.\n';
           mtxt += 'Error description: ' + err.message;
           mtxt += '\nClick OK to continue.';
           alert(mtxt);
   }  //end try
   return false; 
 });
  

  __mysys_apps.mepreloader('mepreloaderme',false);

$('#anchor-list').on('click',function(){
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    var mtkn_whse = '';
    mypo_view_recs(mtkn_whse);

});

function mypo_view_recs(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>item-comp-recs",
        type: "post",
        data: {
            mtkn_whse: mtkn_whse
        }
    });

    // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#packlist').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
      });
  };


function mypo_view_appr(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>me-purchase-view-appr",
        type: "post",
        data: {
            mtkn_whse: mtkn_whse
        }
    });

    // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#purchlist').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
      });
  };
   

  jQuery('#txt_fg_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-itemc-fg',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_fg_desc').val(terms);
                jQuery('#txt_fg_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_fg_code').val(ui.item.ART_CODE);
                

                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));
          
    }); //whse  txt_btn_code

    jQuery('#txt_btn_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-btn-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_btn_code').val(terms);
                jQuery('#txt_btn_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_btn_desc').val(ui.item.ART_DESC);
                jQuery('#txt_btn_uom').val(ui.item.ART_UOM);
                jQuery('#txt_btn_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));
          
    });

    jQuery('#txt_plastic_bag_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-plastic-bag-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_plastic_bag_code').val(terms);
                jQuery('#txt_plastic_bag_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_plastic_bag_desc').val(ui.item.ART_DESC);
                jQuery('#txt_plastic_bag_uom').val(ui.item.ART_UOM);
                jQuery('#txt_plastic_bag_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));
          
    });

    jQuery('#txt_inside_garter_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-inside-garter-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_inside_garter_code').val(terms);
                jQuery('#txt_inside_garter_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_inside_garter_desc').val(ui.item.ART_DESC);
                jQuery('#txt_inside_garter_uom').val(ui.item.ART_UOM);
                jQuery('#txt_inside_garter_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));
          
    });

    jQuery('#txt_rivets_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-rivets-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_rivets_code').val(terms);
                jQuery('#txt_rivets_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_rivets_desc').val(ui.item.ART_DESC);
                jQuery('#txt_rivets_uom').val(ui.item.ART_UOM);
                jQuery('#txt_rivets_cost').val(ui.item.ART_UCOST);
                
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    }); 

    jQuery('#txt_zipper_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-zipper-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_zipper_code').val(terms);
                jQuery('#txt_zipper_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_zipper_desc').val(ui.item.ART_DESC);
                jQuery('#txt_zipper_uom').val(ui.item.ART_UOM);
                jQuery('#txt_zipper_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    }); 

    jQuery('#txt_fabric_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-fabric-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_fabric_code').val(terms);
                jQuery('#txt_fabric_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_fabric_desc').val(ui.item.ART_DESC);
                jQuery('#txt_fabric_uom').val(ui.item.ART_UOM);
                jQuery('#txt_fabric_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    }); 

    jQuery('#txt_lining_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-lining-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_lining_code').val(terms);
                jQuery('#txt_lining_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_lining_desc').val(ui.item.ART_DESC);
                jQuery('#txt_lining_uom').val(ui.item.ART_UOM);
                jQuery('#txt_lining_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    });

    jQuery('#txt_leather_patch_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-leather-patch-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_leather_patch_code').val(terms);
                jQuery('#txt_leather_patch_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_leather_patch_desc').val(ui.item.ART_DESC);
                jQuery('#txt_leather_patch_uom').val(ui.item.ART_UOM);
                jQuery('#txt_leather_patch_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    }); 

    jQuery('#txt_hang_tag_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-hangtag-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_hang_tag_code').val(terms);
                jQuery('#txt_hang_tag_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_hang_tag_desc').val(ui.item.ART_DESC);
                jQuery('#txt_hang_tag_uom').val(ui.item.ART_UOM);
                jQuery('#txt_hang_tag_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    }); 

    jQuery('#txt_side_lbl_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-side-lbl-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_side_lbl_code').val(terms);
                jQuery('#txt_side_lbl_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_side_lbl_desc').val(ui.item.ART_DESC);
                jQuery('#txt_side_lbl_uom').val(ui.item.ART_UOM);
                jQuery('#txt_side_lbl_cost').val(ui.item.ART_UCOST);
                
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    }); 

    jQuery('#txt_size_care_lbl_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-size-care-lbl-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_size_care_lbl_code').val(terms);
                jQuery('#txt_size_care_lbl_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_size_care_lbl_desc').val(ui.item.ART_DESC);
                jQuery('#txt_size_care_lbl_uom').val(ui.item.ART_UOM);
                jQuery('#txt_size_care_lbl_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    });

    jQuery('#txt_kids_lbl_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-kids-lbl-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_kids_lbl_code').val(terms);
                jQuery('#txt_kids_lbl_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_kids_lbl_desc').val(ui.item.ART_DESC);
                jQuery('#txt_kids_lbl_uom').val(ui.item.ART_UOM);
                jQuery('#txt_kids_lbl_cost').val(ui.item.ART_UCOST);
                
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    }); 

    jQuery('#txt_kids_side_lbl_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-kids-side-lbl-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_kids_side_lbl_code').val(terms);
                jQuery('#txt_kids_side_lbl_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_kids_side_lbl_desc').val(ui.item.ART_DESC);
                jQuery('#txt_kids_side_lbl_uom').val(ui.item.ART_UOM);
                jQuery('#txt_kids_side_lbl_cost').val(ui.item.ART_UCOST);
                
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    }); 

    jQuery('#txt_size_lbl_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-size-lbl-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_size_lbl_code').val(terms);
                jQuery('#txt_size_lbl_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_size_lbl_desc').val(ui.item.ART_DESC);
                jQuery('#txt_size_lbl_uom').val(ui.item.ART_UOM);
                jQuery('#txt_size_lbl_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    });  

    jQuery('#txt_barcode_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-barcode-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_barcode_code').val(terms);
                jQuery('#txt_barcode_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_barcode_desc').val(ui.item.ART_DESC);
                jQuery('#txt_barcode_uom').val(ui.item.ART_UOM);
                jQuery('#txt_barcode_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    }); 

    jQuery('#txt_tag_pin_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-tagpin-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_tag_pin_code').val(terms);
                jQuery('#txt_tag_pin_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_tag_pin_desc').val(ui.item.ART_DESC);
                jQuery('#txt_tag_pin_uom').val(ui.item.ART_UOM);
                jQuery('#txt_tag_pin_cost').val(ui.item.ART_UCOST);
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    });

    jQuery('#txt_chip_board_code')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-rm-chip-board-code-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_chip_board_code').val(terms);
                jQuery('#txt_chip_board_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#txt_chip_board_desc').val(ui.item.ART_DESC);
                jQuery('#txt_chip_board_uom').val(ui.item.ART_UOM);
                jQuery('#txt_chip_board_cost').val(ui.item.ART_UCOST);
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));     
    });


</script>