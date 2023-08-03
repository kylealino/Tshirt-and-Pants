<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mydataz = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(0);
$defaultDate = date('Y-m-d');
$fg_code = $request->getVar('fg_code');


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
$icomp_trxno = '';
$comp_date = '';
$nporecs=0;
if(!empty($fg_code)) {
  $str = "
    SELECT
    a.`icomp_trxno`,
    a.`comp_date`
    FROM
    mst_item_comp2 a
    WHERE
    fg_code = '$fg_code'
    ";

$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();
$icomp_trxno = $rw['icomp_trxno'];
$comp_date = $rw['comp_date'];
}


?>
<style>
  .main {
      max-width: 800px;
      margin: 0 auto; 
    }

</style>
<main id="main">
    <div class="pagetitle">
        <h1>Item components</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Item components</li>
            </ol>
            </nav>
    </div>
    <div class="row mb-3 me-form-font">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-pencil-square"></i> Entry</h3>
                </div>
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-lg-3">
                        <div class="col-sm-12">
                            <h6 class="card-title p-0">Generated Transaction No.:</h6>
                            <input type="text" id="icomp_trxno" name="icomp_trxno" class="form-control form-control-sm" value="<?=$icomp_trxno;?>" disabled/>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="col-sm-12">
                            <h6 class="card-title p-0">Select Item code:</h6>
                            <input type="text" id="txt_fg_code" name="txt_fg_code" class="form-control form-control-sm thick-border" value="<?=$fg_code;?>"/>
                        </div>
                    </div>
                  </div>
                  <hr>




                  <div class="row-mb-3">
                    <div class="col-md-12">
                      <div class="table-responsive">
                          <table class="table table-bordered table-hover table-sm text-center" id="tbl-item-comp-recs">
                            <thead class="thead-light">
                              <tr>
                                <?php if(empty($fg_code)):?>
                                <th>Material Type</th>
                                <th>Itemcode</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Cost</th>
                                <th>Total Cost</th>
                                <th>Uom</th>
                                <?php else:?>
                                <th></th>
                                <th nowrap="nowrap">
                                  <button type="button" class="btn btn-dgreen btn-sm" onclick="javascript:my_add_line_item_fgpack();" >
                                    <i class="bi bi-plus"></i>
                                  </button>
                                </th>
                                <th>Itemcode</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Cost</th>
                                <th>Total Cost</th>
                                <th>Uom</th>
                                <?php endif;?> 

                              </tr>
                            </thead>
                            <tbody>
                              <?php if(empty($fg_code)):?>
                                <tr>
                                  <td nowrap>Fabric</td>
                                  <td nowrap><input type="text" id="txt_fabric_code" name="txt_fabric_code" class="form-control form-control-sm thick-border" /></td>
                                  <td nowrap><input type="text" id="txt_fabric_desc" name="txt_fabric_desc" class="form-control form-control-sm"  readonly/></td>
                                  <td nowrap><input type="text" id="txt_fabric_qty" name="txt_fabric_qty" class="form-control form-control-sm thick-border"/>  </td>
                                  <td nowrap><input type="text" id="txt_fabric_cost" name="txt_fabric_cost" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap><input type="text" id="txt_fabric_cost_total" name="txt_fabric_cost_total" class="form-control form-control-sm"  readonly />  </td>
                                  <td nowrap><input type="text" id="txt_fabric_uom" name="txt_fabric_uom" class="form-control form-control-sm"  readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Lining</td>
                                  <td nowrap><input type="text" id="txt_lining_code" name="txt_lining_code" class="form-control form-control-sm thick-border" /></td>
                                  <td nowrap><input type="text" id="txt_lining_desc" name="txt_lining_desc" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap><input type="text" id="txt_lining_qty" name="txt_lining_qty" class="form-control form-control-sm thick-border"/>   </td>
                                  <td nowrap><input type="text" id="txt_lining_cost" name="txt_lining_cost" class="form-control form-control-sm"  readonly/></td>
                                  <td nowrap><input type="text" id="txt_lining_cost_total" name="txt_lining_cost_total" class="form-control form-control-sm" readonly/>   </td>
                                  <td nowrap><input type="text" id="txt_lining_uom" name="txt_lining_uom" class="form-control form-control-sm" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Buttons</td>
                                  <td nowrap><input type="text" id="txt_btn_code" name="txt_btn_code" class="form-control form-control-sm thick-border" /></td>
                                  <td nowrap><input type="text" id="txt_btn_desc" name="txt_btn_desc" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap><input type="text" id="txt_btn_qty" name="txt_btn_qty" class="form-control form-control-sm thick-border"/> </td>
                                  <td nowrap><input type="text" id="txt_btn_cost" name="txt_btn_cost" class="form-control form-control-sm" readonly/>  </td>
                                  <td nowrap><input type="text" id="txt_btn_cost_total" name="txt_btn_cost_total" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap> <input type="text" id="txt_btn_uom" name="txt_btn_uom" class="form-control form-control-sm" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Rivets</td>
                                  <td nowrap><input type="text" id="txt_rivets_code" name="txt_rivets_code" class="form-control form-control-sm thick-border"/></td>
                                  <td nowrap><input type="text" id="txt_rivets_desc" name="txt_rivets_desc" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap><input type="text" id="txt_rivets_qty" name="txt_rivets_qty" class="form-control form-control-sm thick-border"/></td>
                                  <td nowrap><input type="text" id="txt_rivets_cost" name="txt_rivets_cost" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap><input type="text" id="txt_rivets_cost_total" name="txt_rivets_cost_total" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap><input type="text" id="txt_rivets_uom" name="txt_rivets_uom" class="form-control form-control-sm" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Leather Patch</td>
                                  <td nowrap><input type="text" id="txt_leather_patch_code" name="txt_leather_patch_code" class="form-control form-control-sm thick-border"/></td>
                                  <td nowrap><input type="text" id="txt_leather_patch_desc" name="txt_leather_patch_desc" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap><input type="text" id="txt_leather_patch_qty" name="txt_leather_patch_qty" class="form-control form-control-sm thick-border"/></td>
                                  <td nowrap><input type="text" id="txt_leather_patch_cost" name="txt_leather_patch_cost" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap><input type="text" id="txt_leather_patch_cost_total" name="txt_leather_patch_cost_total" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap><input type="text" id="txt_leather_patch_uom" name="txt_leather_patch_code" class="form-control form-control-sm" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Plastic Button</td>
                                  <td nowrap><input type="text" id="txt_plastic_btn_code" name="txt_plastic_btn_code" class="form-control form-control-sm thick-border"/></td>
                                  <td nowrap><input type="text" id="txt_plastic_btn_desc" name="txt_plastic_btn_desc" class="form-control form-control-sm" readonly/></td>
                                  <td nowrap><input type="text" id="txt_plastic_btn_qty" name="txt_plastic_btn_qty" class="form-control form-control-sm thick-border"/> </td>
                                  <td nowrap><input type="text" id="txt_plastic_btn_cost" name="txt_plastic_btn_cost" class="form-control form-control-sm" readonly/> </td>
                                  <td nowrap><input type="text" id="txt_plastic_btn_cost_total" name="txt_plastic_btn_cost_total" class="form-control form-control-sm" readonly/>  </td>
                                  <td nowrap><input type="text" id="txt_plastic_btn_uom" name="txt_plastic_btn_uom" class="form-control form-control-sm" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Inside Garter</td>
                                  <td nowrap><input type="text" id="txt_inside_garter_code" name="txt_inside_garter_code" class="form-control form-control-sm thick-border" value="<?=$inside_garter_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_inside_garter_desc" name="txt_inside_garter_desc" class="form-control form-control-sm" value="<?=$inside_garter_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_inside_garter_qty" name="txt_inside_garter_qty" class="form-control form-control-sm thick-border" value="<?=$inside_garter_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_inside_garter_cost" name="txt_inside_garter_cost" class="form-control form-control-sm" value="<?=$inside_garter_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_inside_garter_cost_total" name="txt_inside_garter_cost_total" class="form-control form-control-sm" value="<?=$inside_garter_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_inside_garter_uom" name="txt_inside_garter_uom" class="form-control form-control-sm" value="<?=$inside_garter_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Hang Tag</td>
                                  <td nowrap><input type="text" id="txt_hang_tag_code" name="txt_hang_tag_code" class="form-control form-control-sm thick-border" value="<?=$hang_tag_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_hang_tag_desc" name="txt_hang_tag_desc" class="form-control form-control-sm" value="<?=$hang_tag_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_hang_tag_qty" name="txt_hang_tag_qty" class="form-control form-control-sm thick-border" value="<?=$hang_tag_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_hang_tag_cost" name="txt_hang_tag_cost" class="form-control form-control-sm" value="<?=$hang_tag_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_hang_tag_cost_total" name="txt_hang_tag_cost_total" class="form-control form-control-sm" value="<?=$hang_tag_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_hang_tag_uom" name="txt_hang_tag_uom" class="form-control form-control-sm" value="<?=$hang_tag_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Zipper</td>
                                  <td nowrap><input type="text" id="txt_zipper_code" name="txt_zipper_code" class="form-control form-control-sm thick-border" value="<?=$zipper_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_zipper_desc" name="txt_zipper_desc" class="form-control form-control-sm" value="<?=$zipper_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_zipper_qty" name="txt_zipper_qty" class="form-control form-control-sm thick-border" value="<?=$zipper_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_zipper_cost" name="txt_zipper_cost" class="form-control form-control-sm" value="<?=$zipper_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_zipper_cost_total" name="txt_zipper_cost_total" class="form-control form-control-sm" value="<?=$zipper_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_zipper_uom" name="txt_zipper_uom" class="form-control form-control-sm" value="<?=$zipper_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Size Label</td>
                                  <td nowrap><input type="text" id="txt_size_lbl_code" name="txt_size_lbl_code" class="form-control form-control-sm thick-border" value="<?=$size_lbl_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_size_lbl_desc" name="txt_size_lbl_desc" class="form-control form-control-sm" value="<?=$size_lbl_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_size_lbl_qty" name="txt_size_lbl_qty" class="form-control form-control-sm thick-border" value="<?=$size_lbl_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_size_lbl_cost" name="txt_size_lbl_cost" class="form-control form-control-sm" value="<?=$size_lbl_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_size_lbl_cost_total" name="txt_size_lbl_cost_total" class="form-control form-control-sm" value="<?=$size_lbl_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_size_lbl_uom" name="txt_size_lbl_uom" class="form-control form-control-sm" value="<?=$size_lbl_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Size Care Label</td>
                                  <td nowrap><input type="text" id="txt_size_care_lbl_code" name="txt_size_care_lbl_code" class="form-control form-control-sm thick-border" value="<?=$size_care_lbl_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_size_care_lbl_desc" name="txt_size_care_lbl_desc" class="form-control form-control-sm" value="<?=$size_care_lbl_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_size_care_lbl_qty" name="txt_size_care_lbl_qty" class="form-control form-control-sm thick-border" value="<?=$size_care_lbl_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_size_care_lbl_cost" name="txt_size_care_lbl_cost" class="form-control form-control-sm" value="<?=$size_care_lbl_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_size_care_lbl_cost_total" name="txt_size_care_lbl_cost_total" class="form-control form-control-sm" value="<?=$size_care_lbl_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_size_care_lbl_uom" name="txt_size_care_lbl_uom" class="form-control form-control-sm" value="<?=$size_care_lbl_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Side Label</td>
                                  <td nowrap><input type="text" id="txt_side_lbl_code" name="txt_side_lbl_code" class="form-control form-control-sm thick-border" value="<?=$side_lbl_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_side_lbl_desc" name="txt_side_lbl_desc" class="form-control form-control-sm" value="<?=$side_lbl_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_side_lbl_qty" name="txt_side_lbl_qty" class="form-control form-control-sm thick-border" value="<?=$side_lbl_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_side_lbl_cost" name="txt_side_lbl_cost" class="form-control form-control-sm" value="<?=$side_lbl_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_side_lbl_cost_total" name="txt_side_lbl_cost_total" class="form-control form-control-sm" value="<?=$side_lbl_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_side_lbl_uom" name="txt_side_lbl_uom" class="form-control form-control-sm" value="<?=$side_lbl_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Kids Label</td>
                                  <td nowrap><input type="text" id="txt_kids_lbl_code" name="txt_kids_lbl_code" class="form-control form-control-sm thick-border" value="<?=$kids_lbl_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_kids_lbl_desc" name="txt_kids_lbl_desc" class="form-control form-control-sm"  value="<?=$kids_lbl_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_kids_lbl_qty" name="txt_kids_lbl_qty" class="form-control form-control-sm thick-border"  value="<?=$kids_lbl_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_kids_lbl_cost" name="txt_kids_lbl_cost" class="form-control form-control-sm"  value="<?=$kids_lbl_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_kids_lbl_cost_total" name="txt_kids_lbl_cost_total" class="form-control form-control-sm"  value="<?=$kids_lbl_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_kids_lbl_uom" name="txt_kids_lbl_uom" class="form-control form-control-sm"  value="<?=$kids_lbl_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Kids Side Label</td>
                                  <td nowrap><input type="text" id="txt_kids_side_lbl_code" name="txt_kids_side_lbl_code" class="form-control form-control-sm thick-border"  value="<?=$kids_side_lbl_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_kids_side_lbl_desc" name="txt_kids_side_lbl_desc" class="form-control form-control-sm" value="<?=$kids_side_lbl_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_kids_side_lbl_qty" name="txt_kids_side_lbl_qty" class="form-control form-control-sm thick-border" value="<?=$kids_side_lbl_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_kids_side_lbl_cost" name="txt_kids_side_lbl_cost" class="form-control form-control-sm" value="<?=$kids_side_lbl_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_kids_side_lbl_cost_total" name="txt_kids_side_lbl_cost_total" class="form-control form-control-sm" value="<?=$kids_side_lbl_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_kids_side_lbl_uom" name="txt_kids_side_lbl_code" class="form-control form-control-sm" value="<?=$kids_side_lbl_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Plastic Bag</td>
                                  <td nowrap><input type="text" id="txt_plastic_bag_code" name="txt_plastic_bag_code" class="form-control form-control-sm thick-border" value="<?=$plastic_bag_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_plastic_bag_desc" name="txt_plastic_bag_desc" class="form-control form-control-sm" value="<?=$plastic_bag_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_plastic_bag_qty" name="txt_plastic_bag_qty" class="form-control form-control-sm thick-border" value="<?=$plastic_bag_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_plastic_bag_cost" name="txt_plastic_bag_cost" class="form-control form-control-sm" value="<?=$plastic_bag_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_plastic_bag_cost_total" name="txt_plastic_bag_cost_total" class="form-control form-control-sm" value="<?=$plastic_bag_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_plastic_bag_uom" name="txt_plastic_bag_uom" class="form-control form-control-sm" value="<?=$plastic_bag_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Barcode</td>
                                  <td nowrap><input type="text" id="txt_barcode_code" name="txt_barcode_code" class="form-control form-control-sm thick-border" value="<?=$barcode_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_barcode_desc" name="txt_barcode_desc" class="form-control form-control-sm" value="<?=$barcode_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_barcode_qty" name="txt_barcode_qty" class="form-control form-control-sm thick-border" value="<?=$barcode_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_barcode_cost" name="txt_barcode_cost" class="form-control form-control-sm" value="<?=$barcode_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_barcode_cost_total" name="txt_barcode_cost_total" class="form-control form-control-sm" value="<?=$barcode_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_barcode_uom" name="txt_barcode_uom" class="form-control form-control-sm" value="<?=$barcode_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Fitting Sticker</td>
                                  <td nowrap><input type="text" id="txt_fitting_sticker_code" name="txt_fitting_sticker_code" class="form-control form-control-sm thick-border" value="<?=$fitting_sticker_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_fitting_sticker_desc" name="txt_fitting_sticker_desc" class="form-control form-control-sm" value="<?=$fitting_sticker_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_fitting_sticker_qty" name="txt_fitting_sticker_qty" class="form-control form-control-sm thick-border" value="<?=$fitting_sticker_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_fitting_sticker_cost" name="txt_fitting_sticker_cost" class="form-control form-control-sm" value="<?=$fitting_sticker_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_fitting_sticker_cost_total" name="txt_fitting_sticker_cost_total" class="form-control form-control-sm" value="<?=$fitting_sticker_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_fitting_sticker_uom" name="txt_fitting_sticker_uom" class="form-control form-control-sm" value="<?=$fitting_sticker_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Tag pin</td>
                                  <td nowrap><input type="text" id="txt_tag_pin_code" name="txt_tag_pin_code" class="form-control form-control-sm thick-border" value="<?=$tag_pin_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_tag_pin_desc" name="txt_tag_pin_desc" class="form-control form-control-sm" value="<?=$tag_pin_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_tag_pin_qty" name="txt_tag_pin_qty" class="form-control form-control-sm thick-border" value="<?=$tag_pin_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_tag_pin_cost" name="txt_tag_pin_cost" class="form-control form-control-sm" value="<?=$tag_pin_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_tag_pin_cost_total" name="txt_tag_pin_cost_total" class="form-control form-control-sm" value="<?=$tag_pin_tcost;?>" readonly/> </td>
                                  <td nowrap><input type="text" id="txt_tag_pin_uom" name="txt_tag_pin_uom" class="form-control form-control-sm" value="<?=$tag_pin_uom;?>" readonly/></td>
                                </tr>
                                <tr>
                                  <td nowrap>Chip Board</td>
                                  <td nowrap><input type="text" id="txt_chip_board_code" name="txt_chip_board_code" class="form-control form-control-sm thick-border" value="<?=$chip_board_code;?>"/></td>
                                  <td nowrap><input type="text" id="txt_chip_board_desc" name="txt_chip_board_desc" class="form-control form-control-sm" value="<?=$chip_board_desc;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_chip_board_qty" name="txt_chip_board_qty" class="form-control form-control-sm thick-border" value="<?=$chip_board_qty;?>"/></td>
                                  <td nowrap><input type="text" id="txt_chip_board_cost" name="txt_chip_board_cost" class="form-control form-control-sm" value="<?=$chip_board_cost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_chip_board_cost_total" name="txt_chip_board_cost_total" class="form-control form-control-sm" value="<?=$chip_board_tcost;?>" readonly/></td>
                                  <td nowrap><input type="text" id="txt_chip_board_uom" name="txt_chip_board_uom" class="form-control form-control-sm" value="<?=$chip_board_uom;?>" readonly/></td>
                                </tr>
                                <?php
                                  else:
                                  $nn=1;

                                  $str = "
                                  SELECT
                                    `rm_code`,
                                    `item_desc`,
                                    `item_qty`,
                                    `item_cost`,
                                    `item_tcost`,
                                    `item_uom`
                                  FROM
                                    `mst_item_comp2`
                                  WHERE 
                                    `fg_code` = '{$fg_code}'
                                  ";

                                  $q =  $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                                  $rrec = $q->getResultArray();
                                  foreach($rrec as $rdt){
                                      $nporecs++;
                                    
                                      $rm_code = $rdt['rm_code'];
                                      $item_desc = $rdt['item_desc'];
                                      $item_qty = $rdt['item_qty'];
                                      $item_cost = $rdt['item_cost'];
                                      $item_tcost = $rdt['item_tcost'];
                                      $item_uom = $rdt['item_uom'];

                                  ?>
                                  <tr>
                                    <td nowrap="nowrap"><?=$nporecs;?></td>
                                    <td nowrap="nowrap">
                                      <button type="button" class="btn btn-xs btn-danger" onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button>
                                      <input class="mitemrid" type="hidden" value=""/>
                                      <input type="hidden" value=""/>
                                    </td>
                                    <td nowrap="nowrap"><input type="text" id="rm_code<?=$nporecs;?>" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$rdt['rm_code'];?>"></td>
                                    <td nowrap="nowrap"><input type="text" id="item_desc<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white" size="30" value="<?=$rdt['item_desc'];?>" disabled></td>
                                    <td nowrap="nowrap"><input type="text" id="item_qty<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white" size="10" value="<?=$rdt['item_qty'];?>"></td>
                                    <td nowrap="nowrap"><input type="text" id="item_cost<?=$nporecs;?>" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$rdt['item_cost'];?>" disabled></td>
                                    <td nowrap="nowrap"><input type="text" id="item_tcost<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white" size="10" value="<?=$rdt['item_tcost'];?>" disabled></td>
                                    <td nowrap="nowrap"><input type="text" id="item_uom<?=$nporecs;?>" class="form-control text-center form-control-sm bg-white"size="10" value="<?=$rdt['item_uom'];?>" disabled></td>
                                  </tr>
                                  <?php 
                                    $nn++;
                                      } 
                                    endif;
                                  ?> 
                                  
                                <tr style="display: none;">
                                  <td></td>
                                  <td nowrap="nowrap">
                                    <button type="button" class="btn btn-xs btn-danger" onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button>
                                    <input class="mitemrid" type="hidden" value=""/>
                                    <input type="hidden" value=""/>
                                  
                                  </td>
                                  <td nowrap="nowrap"><input type="text" class="form-control form-control-sm mitemcode text-center" size="20"></td> 
                                  <td nowrap="nowrap"><input type="text" class="form-control form-control-sm text-center" size="20" readonly="readonly"></td> 
                                  <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm text-center" onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();"></td> <!--3 QTY -->
                                  <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm text-center" readonly="readonly"></td> 
                                  <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm text-center" readonly="readonly"></td>
                                  <td nowrap="nowrap"><input type="text" size="5" class="form-control form-control-sm text-center" readonly="readonly"></td>
                                </tr>
                            </tbody>
                          </table>
                        </div>
                    </div>
                  </div>
                  <div class="row gy-2 mb-3">
                      <div class="col-sm-4">
                        <?php if(!empty($fg_code)): ?>
                        <div class="col-sm-4">
                          <button id="mbtn_mn_Update" type="submit" class="btn btn-dgreen btn-sm">Update</button>
                          <?=anchor('me-item-comp-vw-2', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-sm" ');?>
                        </div>
                        <?php else:?>
                        <div class="col-sm-4">
                          <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
                          <?=anchor('me-item-comp-vw-2', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-sm" ');?>
                        </div>
                        <?php endif;?>
                      </div>
                  </div>
                </div> 
            </div>
        </div>


        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Upload entry</h3>
                </div>
                <div class="card-body">
                  <div class="row mb-3">
                      <div class="col-lg-3">
                          <div class="col-sm-12">
                              <h6 class="card-title p-0">Select FG code:</h6>
                              <input type="text"  placeholder="Finish good code" id="fg_code" name="fg_code" class="fg_code form-control form-control-sm " required/>
                          </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="col-sm-12">
                            <h6 class="card-title p-0">Select Date.:</h6>
                            <input type="date" id="ucomp_date" name="ucomp_date" class="form-control form-control-sm" value="<?=$defaultDate;?>"/>
                        </div>
                      </div>
                      <div class="col-lg-3">
                          <div class="col-sm-12">
                            <h6>Select file to upload</h6>
                            <div class="input-group input-group-sm ">
                                <input type="file" class="form-control form-control-sm" id="rcv-upld-file" placeholder="Search Transaction/Branch" aria-label="mytxtsearchrec" aria-describedby="basic-addon1">
                                <div class="input-group-prepend" id="basic-addon1">
                                    <button type="button" id="btn-upload-item-comp" class="btn btn-dgreen btn-sm m-0 rounded-0 rounded-end" ><i class="bi bi-upload"></i> Upload</button>
                                </div>
                            </div>
                          </div>
                      </div>
                  </div>
                  <div class="row mb-3">
                      <div class="col-12">
                        <div id="mymodoutrecs">
                            <div class="text-center p-2 rounded-3  mt-2 border-dotted bg-light col-lg-12  p-4">
                            <h5><i class="bi bi-info-circle-fill text-dgreen"></i> Uploaded item components will display here.</h5> 
                            </div>
                        </div>
                      </div>
                  </div> 
                </div> 
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Records</h3>
                </div>
                <div class="card-body">
                    <div class="pt-2 bg-dgreen mt-2"> 
                        <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                            <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> List</a>
                            <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> Items</a>
                        </nav>
                    </div>

                    <div id="itemlist" class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">
                          <?php

                          ?> 
                      </div> 
                </div> 
            </div>
        </div>
        
    </div>   
</main>
<?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
?>  
<script>
  __mysys_apps.mepreloader('mepreloaderme',false);
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


//     $("#mbtn_mn_Save").click(function(e){
       
//        try { 
//          //__mysys_apps.mepreloader('mepreloaderme',true);
//           var txt_fg_code = jQuery('#txt_fg_code').val();
//           var txt_fabric_code = jQuery('#txt_fabric_code').val();
//           var txt_fabric_qty = jQuery('#txt_fabric_qty').val();
//           var txt_fabric_tcost = jQuery('#txt_fabric_cost_total').val();

//           var txt_lining_code = jQuery('#txt_lining_code').val();
//           var txt_lining_qty = jQuery('#txt_lining_qty').val();
//           var txt_lining_tcost = jQuery('#txt_lining_cost_total').val();

//           var txt_btn_code = jQuery('#txt_btn_code').val();
//           var txt_btn_qty = jQuery('#txt_btn_qty').val();
//           var txt_btn_tcost = jQuery('#txt_btn_cost_total').val();

//           var txt_rivets_code = jQuery('#txt_rivets_code').val();
//           var txt_rivets_qty = jQuery('#txt_rivets_qty').val();
//           var txt_rivets_tcost = jQuery('#txt_rivets_cost_total').val();

//           var txt_leather_patch_code =  jQuery('#txt_leather_patch_code').val();
//           var txt_leather_patch_qty = jQuery('#txt_leather_patch_qty').val();
//           var txt_leather_patch_tcost = jQuery('#txt_leather_patch_cost_total').val();

//           var txt_plastic_btn_code = jQuery('#txt_plastic_btn_code').val();
//           var txt_plastic_btn_qty = jQuery('#txt_plastic_btn_qty').val();
//           var txt_plastic_btn_tcost = jQuery('#txt_plastic_btn_cost_total').val();

//           var txt_inside_garter_code  = jQuery('#txt_inside_garter_code').val();
//           var txt_inside_garter_qty = jQuery('#txt_inside_garter_qty').val();
//           var txt_inside_garter_tcost = jQuery('#txt_inside_garter_cost_total').val();

//           var txt_hang_tag_code = jQuery('#txt_hang_tag_code').val();
//           var txt_hang_tag_qty = jQuery('#txt_hang_tag_qty').val();
//           var txt_hang_tag_tcost = jQuery('#txt_hang_tag_cost_total').val();

//           var txt_zipper_code = jQuery('#txt_zipper_code').val();
//           var txt_zipper_qty = jQuery('#txt_zipper_qty').val();
//           var txt_zipper_tcost = jQuery('#txt_zipper_cost_total').val();

//           var txt_size_lbl_code = jQuery('#txt_size_lbl_code').val();
//           var txt_size_lbl_qty = jQuery('#txt_size_lbl_qty').val();
//           var txt_size_lbl_tcost = jQuery('#txt_size_lbl_cost_total').val();

//           var txt_size_care_lbl_code = jQuery('#txt_size_care_lbl_code').val();
//           var txt_size_care_lbl_qty = jQuery('#txt_size_care_lbl_qty').val();
//           var txt_size_care_lbl_tcost = jQuery('#txt_size_care_lbl_cost_total').val();

//           var txt_side_lbl_code = jQuery('#txt_side_lbl_code').val();
//           var txt_side_lbl_qty = jQuery('#txt_side_lbl_qty').val();
//           var txt_side_lbl_tcost = jQuery('#txt_side_lbl_cost_total').val();

//           var txt_kids_lbl_code = jQuery('#txt_kids_lbl_code').val();
//           var txt_kids_lbl_qty = jQuery('#txt_kids_lbl_qty').val();
//           var txt_kids_lbl_tcost = jQuery('#txt_kids_lbl_cost_total').val();

//           var txt_kids_side_lbl_code = jQuery('#txt_kids_side_lbl_code').val();
//           var txt_kids_side_lbl_qty = jQuery('#txt_kids_side_lbl_qty').val();
//           var txt_kids_side_lbl_tcost = jQuery('#txt_kids_side_lbl_cost_total').val();

//           var txt_plastic_bag_code = jQuery('#txt_plastic_bag_code').val();
//           var txt_plastic_bag_qty = jQuery('#txt_plastic_bag_qty').val();
//           var txt_plastic_bag_tcost = jQuery('#txt_plastic_bag_cost_total').val();

//           var txt_barcode_code = jQuery('#txt_barcode_code').val();
//           var txt_barcode_qty = jQuery('#txt_barcode_qty').val();
//           var txt_barcode_tcost = jQuery('#txt_barcode_cost_total').val();

//           var txt_fitting_sticker_code = jQuery('#txt_fitting_sticker_code').val();
//           var txt_fitting_sticker_qty = jQuery('#txt_fitting_sticker_qty').val();
//           var txt_fitting_sticker_tcost = jQuery('#txt_fitting_sticker_cost_total').val();

//           var txt_tag_pin_code = jQuery('#txt_tag_pin_code').val();
//           var txt_tag_pin_qty = jQuery('#txt_tag_pin_qty').val();
//           var txt_tag_pin_tcost = jQuery('#txt_tag_pin_cost_total').val();

//           var txt_chip_board_code = jQuery('#txt_chip_board_code').val();
//           var txt_chip_board_qty = jQuery('#txt_chip_board_qty').val();
//           var txt_chip_board_tcost = jQuery('#txt_chip_board_cost_total').val();

//            var mparam = {
//             txt_fg_code:txt_fg_code,
//             txt_fabric_code:txt_fabric_code,
//             txt_fabric_qty:txt_fabric_qty,
//             txt_fabric_tcost:txt_fabric_tcost,
//             txt_lining_code:txt_lining_code,
//             txt_lining_qty:txt_lining_qty,
//             txt_lining_tcost:txt_lining_tcost,
//             txt_btn_code:txt_btn_code,
//             txt_btn_qty:txt_btn_qty,
//             txt_btn_tcost:txt_btn_tcost,
//             txt_rivets_code:txt_rivets_code,
//             txt_rivets_qty:txt_rivets_qty,
//             txt_rivets_tcost:txt_rivets_tcost,
//             txt_leather_patch_code:txt_leather_patch_code,
//             txt_leather_patch_qty:txt_leather_patch_qty,
//             txt_leather_patch_tcost:txt_leather_patch_tcost,
//             txt_plastic_btn_code:txt_plastic_btn_code,
//             txt_plastic_btn_qty:txt_plastic_btn_qty,
//             txt_plastic_btn_tcost:txt_plastic_btn_tcost,
//             txt_inside_garter_code:txt_inside_garter_code,
//             txt_inside_garter_qty:txt_inside_garter_qty,
//             txt_inside_garter_tcost:txt_inside_garter_tcost,
//             txt_hang_tag_code:txt_hang_tag_code,
//             txt_hang_tag_qty:txt_hang_tag_qty,
//             txt_hang_tag_tcost:txt_hang_tag_tcost,
//             txt_zipper_code:txt_zipper_code,
//             txt_zipper_qty:txt_zipper_qty,
//             txt_zipper_tcost:txt_zipper_tcost,
//             txt_size_lbl_code:txt_size_lbl_code,
//             txt_size_lbl_qty:txt_size_lbl_qty,
//             txt_size_lbl_tcost:txt_size_lbl_tcost,
//             txt_size_care_lbl_code:txt_size_care_lbl_code,
//             txt_size_care_lbl_qty:txt_size_care_lbl_qty,
//             txt_size_care_lbl_tcost:txt_size_care_lbl_tcost,
//             txt_side_lbl_code:txt_side_lbl_code,
//             txt_side_lbl_qty:txt_side_lbl_qty,
//             txt_side_lbl_tcost:txt_side_lbl_tcost,
//             txt_kids_lbl_code:txt_kids_lbl_code,
//             txt_kids_lbl_qty:txt_kids_lbl_qty,
//             txt_kids_lbl_tcost:txt_kids_lbl_tcost,
//             txt_kids_side_lbl_code:txt_kids_side_lbl_code,
//             txt_kids_side_lbl_qty:txt_kids_side_lbl_qty,
//             txt_kids_side_lbl_tcost:txt_kids_side_lbl_tcost,
//             txt_plastic_bag_code:txt_plastic_bag_code,
//             txt_plastic_bag_qty:txt_plastic_bag_qty,
//             txt_plastic_bag_tcost:txt_plastic_bag_tcost,
//             txt_barcode_code:txt_barcode_code,
//             txt_barcode_qty:txt_barcode_qty,
//             txt_barcode_tcost:txt_barcode_tcost,
//             txt_fitting_sticker_code:txt_fitting_sticker_code,
//             txt_fitting_sticker_qty:txt_fitting_sticker_qty,
//             txt_fitting_sticker_tcost:txt_fitting_sticker_tcost,
//             txt_tag_pin_code:txt_tag_pin_code,
//             txt_tag_pin_qty:txt_tag_pin_qty,
//             txt_tag_pin_tcost:txt_tag_pin_tcost,
//             txt_chip_board_code:txt_chip_board_code,
//             txt_chip_board_qty:txt_chip_board_qty,
//             txt_chip_board_tcost:txt_chip_board_tcost
//            };  

//            console.log(mparam);
           
//            $.ajax({ 
//              type: "POST",
//              url: '<?=site_url();?>me-item-comp-save',
//              context: document.body,
//              data: eval(mparam),
//              global: false,
//              cache: false,
//              success: function(data)  { 
//                $(this).prop('disabled', false);
//           // $.hideLoading();
//           jQuery('#memsgtestent_bod').html(data);
//           jQuery('#memsgtestent').modal('show');
//           return false;
//         },
//         error: function() {
//          alert('error loading page...');
//         // $.hideLoading();
//         return false;
//       } 
//     });

//          } catch(err) {
//            var mtxt = 'There was an error on this page.\n';
//            mtxt += 'Error description: ' + err.message;
//            mtxt += '\nClick OK to continue.';
//            alert(mtxt);
//    }  //end try
//    return false; 
//  });

 $("#mbtn_mn_Save").click(function(e){
    try { 

          var icomp_trxno = jQuery('#icomp_trxno').val();
          var fg_item = jQuery('#txt_fg_code').val();
          var rowCount1 = jQuery('#tbl-item-comp-recs tr').length;
          var adata1 = [];
          var mdata = '';

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-item-comp-recs tr:eq(' + aa + ')').clone(); 
                var m1 = jQuery(clonedRow).find('input[type=text]').eq(0).val(); 
                var m2 = jQuery(clonedRow).find('input[type=text]').eq(1).val();
                var m3 = jQuery(clonedRow).find('input[type=text]').eq(2).val(); 
                var m4 = jQuery(clonedRow).find('input[type=text]').eq(3).val();
                var m5 = jQuery(clonedRow).find('input[type=text]').eq(4).val(); 
                var m6 = jQuery(clonedRow).find('input[type=text]').eq(5).val();


                mdata = m1 + 'x|x' + m2 + 'x|x' + m3 + 'x|x' + m4 + 'x|x' + m5 + 'x|x' + m6;
                adata1.push(mdata);
            } 

          var mparam = {
            icomp_trxno:icomp_trxno,
            fg_item:fg_item,
            adata1: adata1
          };  

          console.log(adata1);

      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-item-comp-save-2',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { 
            $(this).prop('disabled', false);
            jQuery('#memsgtestent_bod').html(data);
            jQuery('#memsgtestent').modal('show');
            return false;
        },
        error: function() {
          alert('error loading page...');
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


  $("#mbtn_mn_Update").click(function(e){
    try { 

          var icomp_trxno = jQuery('#icomp_trxno').val();
          var fg_item = jQuery('#txt_fg_code').val();
          var comp_date = jQuery('#comp_date').val();
          var rowCount1 = jQuery('#tbl-item-comp-recs tr').length;
          var adata1 = [];
          var mdata = '';

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-item-comp-recs tr:eq(' + aa + ')').clone(); 
                var m1 = jQuery(clonedRow).find('input[type=text]').eq(0).val(); 
                var m2 = jQuery(clonedRow).find('input[type=text]').eq(1).val();
                var m3 = jQuery(clonedRow).find('input[type=text]').eq(2).val(); 
                var m4 = jQuery(clonedRow).find('input[type=text]').eq(3).val();
                var m5 = jQuery(clonedRow).find('input[type=text]').eq(4).val(); 
                var m6 = jQuery(clonedRow).find('input[type=text]').eq(5).val();


                mdata = m1 + 'x|x' + m2 + 'x|x' + m3 + 'x|x' + m4 + 'x|x' + m5 + 'x|x' + m6;
                adata1.push(mdata);
            } 

          var mparam = {
            icomp_trxno:icomp_trxno,
            fg_item:fg_item,
            comp_date:comp_date,
            adata1: adata1
          };  

          console.log(rowCount1);

      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-item-comp-update-2',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { 
            $(this).prop('disabled', false);
            jQuery('#memsgtestent_bod').html(data);
            jQuery('#memsgtestent').modal('show');
            return false;
        },
        error: function() {
          alert('error loading page...');
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
    item_view_recs_2(mtkn_whse);

});

function item_view_recs_2(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>item-comp-recs-2",
        type: "post",
        data: {
            mtkn_whse: mtkn_whse
        }
    });

    // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#itemlist').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
      });
  };

  jQuery('#fg_code')
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
                jQuery('#fg_code').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#fg_code').val(ui.item.ART_CODE);
                

                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));
          
    }); //whse  txt_btn_code

    $('#btn-upload-item-comp').click(function(){ 
      try {   

        var ucomp_date = jQuery('#ucomp_date').val();
        var file       = $('#rcv-upld-file').val();
        var fg_code_val = document.getElementById('fg_code');
        var fg_code = fg_code_val.value;
        
        if($.trim(file) == ''){ 
          jQuery('#myModSysMsgSubBod').css({
            display: ''
          });
          jQuery('#myModSysMsgSubBod').html('Please select file to upload!');
          jQuery('#myModSysMsgSub').modal('show');
          return false;
        }

        my_data = new FormData();
        my_data.append('rcv_file', $('#rcv-upld-file')[0].files[0]);
        my_data.append('fg_code', fg_code);
        my_data.append('ucomp_date', ucomp_date);



        __mysys_apps.mepreloader('mepreloaderme',true);
        $.ajax({ // default declaration of ajax parameters
          url: '<?=site_url()?>item-comp-upld',
          method:"POST",
          context:document.body,
          data: my_data,
          contentType: false,
          global: false,
          cache: false,
          processData:false,
          success: function(data)  { //display html using divID
            __mysys_apps.mepreloader('mepreloaderme',false);
            jQuery('#mymodoutrecs').html(data);
            return false;
          },
          error: function() { // display global error on the menu function
            alert('error loading page...');
            
            return false;
          } 
        }); 
      } catch (err) {
        var mtxt = 'There was an error on this page.\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\nClick OK to continue.';
        
        alert(mtxt);
      } //end try
      $(this).prop("disabled", true);
    }); 

    function my_add_line_item_fgpack() {  
      try {
          
          var rowCount = jQuery('#tbl-item-comp-recs tr').length;
          var mid = __mysys_apps.__do_makeid() + (rowCount + 1);
          var clonedRow = jQuery('#tbl-item-comp-recs tr:eq(' + (rowCount -1) + ')').clone(); 

          jQuery(clonedRow).find('input[type=text]').eq(0).attr('id','mitemcode_' );
          jQuery(clonedRow).find('input[type=text]').eq(1).attr('id','mitemdesc_' );
          jQuery(clonedRow).find('input[type=text]').eq(2).attr('id','mitemqty_' );
          jQuery(clonedRow).find('input[type=text]').eq(3).attr('id','txt-mtext-') ;
          jQuery(clonedRow).find('input[type=text]').eq(4).attr('id','txt-test-' );
          jQuery(clonedRow).find('input[type=text]').eq(5).attr('id','txt-rm-qty');

          
          jQuery('#tbl-item-comp-recs tr').eq(rowCount - 1).before(clonedRow);
          jQuery(clonedRow).css({'display':''});
          var xobjArtItem= jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
          jQuery('#' + xobjArtItem).focus();
          $( '#tbl-item-comp-recs tr').each(function(i) { 
                  $(this).find('td').eq(0).html(i);
          });
              
          __my_item_lookup();
      } catch(err) { 
          var mtxt = 'There was an error on this page.\\n';
          mtxt += 'Error description: ' + err.message;
          mtxt += '\\nClick OK to continue.';
          alert(mtxt);
          return false;
      }  //end try 
    }

    function __my_item_lookup(){
        jQuery('.mitemcode' ) 
          // don't navigate away from the field on tab when selecting an item
          .bind( 'keydown', function( event ) {
              if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                      jQuery( this ).data( 'autocomplete' ).menu.active ) {
                  event.preventDefault();
              }
              if( event.keyCode === jQuery.ui.keyCode.TAB ) {
                  event.preventDefault();
              }
          })
          .autocomplete({
              minLength: 0,
              source: '<?= site_url(); ?>get-rm-add-line',
              focus: function() {
                  // prevent value inserted on focus
                  return false;
              },
              select: function( event, ui ) {
                  var terms = ui.item.value;
                  
                  jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                  jQuery(this).attr('title', jQuery.trim(ui.item.value));
               
                  this.value = ui.item.value;
              

                  var clonedRow = jQuery(this).parent().parent().clone();
                  var indexRow = jQuery(this).parent().parent().index();
                  var xobjitemc = jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id');
                  var xobjitemdesc = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');
                  var xobjitemsrp = jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');
                  var xobjitemuom = jQuery(clonedRow).find('input[type=text]').eq(5).attr('id');

                  $('#' + xobjitemc).val(ui.item.ART_CODE);
                  $('#' + xobjitemdesc).val(ui.item.ART_DESC);
                  $('#' + xobjitemsrp).val(ui.item.ART_UPRICE);
                  $('#' + xobjitemuom).val(ui.item.ART_UOM);
                 

                  return false;
              }
          })
          .click(function() { 

              //jQuery(this).keydown(); 
              var terms = this.value;
              //jQuery(this).autocomplete('search', '');
              jQuery(this).autocomplete('search', jQuery.trim(terms));
          });  
        }

        function __pack_totals() { 

        try { 
            var rowCount1 = jQuery('#tbl-item-comp-recs tr').length;

            var mdata = '';
            var total_qty_serve = 0;
            var total_amount_serve = 0;
            var total_lacking = 0;
            var total= 0;
            var lck = 0;
            var total_lck = 0;
            var total = 0;
            var amount = 0;
            var total_cost = 0;

            for(aa = 1; aa < rowCount1 ; aa++) { 
                var clonedRow = jQuery('#tbl-item-comp-recs tr:eq(' + aa + ')').clone(); 

                var IQTY = jQuery(clonedRow).find('input[type=text]').eq(2).val();
                var ICOST = jQuery(clonedRow).find('input[type=text]').eq(3).val();
                var ITCOST = jQuery(clonedRow).find('input[type=text]').eq(4).attr('id');


                var IQTY_TOTAL = parseFloat(IQTY);
                var ICOST_TOTAL = parseFloat(ICOST);

                total_cost = (IQTY_TOTAL * ICOST_TOTAL);
                console.log(total_cost);
                $('#' + ITCOST).val(total_cost);

            }

        } catch(err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            alert(mtxt);
            $.hideLoading();
            return false;
        }         

        }
</script>


