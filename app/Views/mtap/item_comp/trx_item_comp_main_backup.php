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

$txtactive_plnt_id = "";
$txtactive_wshe_id = "";
$mtkn_active_plnt_id ='';
$mtkn_active_wshe_id ='';
$splnt_id = '';
$swshe_id = '';
$mencd_date       = date("Y-m-d");  

//$aPO_Class = $mydataz->lk_PO_Class();

$mtkn_trxno = $request->getVar('mtkn_trxno');
$mtkn_vndrtr              = '';
$mtkn_vndsrtr             = '';
//echo $mtkn_trxno;  
// die();
$txt_ponumb = '';
//$mtkn_trxno = '';
$txt_po_cls = '';
$txt_po_cls_rid = '';
$txt_tdate = $mencd_date ;
$txt_ddate = $mencd_date ;
$txtvend_addr = '';
$txtvend_code = '';
$txtvend_cont_persn = '';
$txtvend_cont_persn_desgn = '';
$txtvend_cont_persn_cnos = '';
$txtvends_code = '';
$txtvends_addr = '';
$txtvends_cont_persn = '';
$txtvends_cont_persn_desgn = '';
$txtvends_cont_persn_cnos = '';
$txt_drlist = '';
$txt_remk = '';
$terms = '';
$txtpo_totals = '';
$txtpo_qty = '';
$txtpo_tsku = '';

$mtkn_vndrtr              = '';
$mtkn_vndsrtr = '';
$mpohd_rid = '';
$nporecs = 0;

if(!empty($mtkn_trxno)) {
$str = "
    select aa.*,
    bb.VEND_NAME __vend_name,
    cc.CUST_NAME __vends_name,
    dd.`recid` __po_cls_rid,
    e.plnt_code,
    d.wshe_code,
    dd.`PO_CLS_CODE`,
    sha2(concat(aa.`plnt_id`,'{$mpw_tkn}'),384) plnt_id,
    sha2(concat(aa.`wshe_id`,'{$mpw_tkn}'),384) wshe_id,
    sha2(concat(aa.vend_rid,'{$mpw_tkn}'),384) mtkn_vndrtr,
    sha2(concat(aa.vends_rid,'{$mpw_tkn}'),384) mtkn_vndsrtr 
    from ((((({$this->db_erp}.`gw_fg_po_hd` aa 
    join {$this->db_erp}.`mst_vendor` bb on (aa.vend_rid = bb.recid)) 
    join {$this->db_erp}.`mst_customer` cc on (aa.vends_rid = cc.recid)) 
    join {$this->db_erp}.`mst_po_class` dd on (aa.`po_cls_id` = dd.recid))
    join  {$this->db_erp}.`mst_plant`  e
    ON (aa.`plnt_id` = e.`recid`))
    join  {$this->db_erp}.`mst_wshe`  d
    ON (aa.`wshe_id` = d.`recid`))
     where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_trxno' 
        ";

$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();
//$mtkn_trxno = $rw['mtkn_trxno'];
$mtkn_vndrtr              = $rw['mtkn_vndrtr'];
$mtkn_vndsrtr             = $rw['mtkn_vndsrtr'];
$mpohd_rid = $rw['recid'];

$txt_ponumb = $rw['po_sysctrlno'];
$txt_po_cls = $rw['PO_CLS_CODE'];
$txt_po_cls_rid = $rw['__po_cls_rid'];
$txt_tdate =  $rw['trx_date'];
$txt_ddate =  $rw['trx_delivery_date'];
$txtvend_code = $rw['__vend_name'];
$txtvend_addr             = $rw['vend_add'];
$txtvend_cont_persn       = $rw['vend_cont_pers'];
$txtvend_cont_persn_desgn = $rw['vend_cp_desig'];
$txtvend_cont_persn_cnos  = $rw['vend_cp_contno'];
$txtvends_code = $rw['__vends_name'];
$txtvends_addr             = $rw['vends_add'];
$txtvends_cont_persn       = $rw['vends_cont_pers'];
$txtvends_cont_persn_desgn = $rw['vends_cp_desig'];
$txtvends_cont_persn_cnos  = $rw['vends_cp_contno'];

$txt_drlist = $rw['dr_list'];
$txt_remk = $rw['rmks'];
$terms = $rw['terms'];

//$txtpo_totals = $rw['txtpo_totals'];
//$txtpo_qty = $rw['txtpo_qty'];
//$txtpo_tsku = $rw['txtpo_tsku'];

$txtactive_plnt_id = $rw['plnt_code'];
$txtactive_wshe_id = $rw['wshe_code'];
$mtkn_active_plnt_id = $rw['plnt_id'];
$mtkn_active_wshe_id = $rw['wshe_id'];

$splnt_id = $rw['plnt_id'];
$swshe_id = $rw['wshe_id'];
}

?>
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
          <div class="card-body">
            <h6 class="card-title">Select Item Code</h6>
            <?=form_open('me-purchase-save','class="needs-validation" id="myfrms_customer" ');?>
            <div class="row mb-2">
                <div class="col-lg-6">
                    <div class="col-sm-4">
                        <input type="text" id="txt_fg_code" name="txt_fg_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />
                    </div>    
                </div>
            </div>
            <hr class="prettyline">
            <div class="row display:inline-block;">
                <div class="col-sm-2">
                    <h6 class="card-title">Materials:</h6>
                </div>
                <div class="col-sm-2 text-center">
                    <h6 class="card-title">Item Code</h6>
                </div>
                <div class="col-sm-4 text-center">
                    <h6 class="card-title">Description</h6>
                </div>
                <div class="col-sm-2 text-center">
                    <h6 class="card-title">QTY</h6>
                </div>
                <div class="col-sm-2 text-center">
                    <h6 class="card-title">UOM</h6>
                </div>
                
            </div>
            <div class="row mb-3 display:inline-block;">
                    <label class="col-sm-2  form-label" for="txt_fabric_code">Fabric:</label>
                    <div class="col-sm-2">
                        <input type="text" id="txt_fabric_code" name="txt_fabric_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" id="txt_fabric_desc" name="txt_fabric_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt_fabric_qty" name="txt_fabric_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt_fabric_uom" name="txt_fabric_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                    </div>
            </div>
            <div class="row mb-3 display:inline-block;">
                    <label class="col-sm-2 form-label" for="txt_lining_code">Lining:</label>
                    <div class="col-sm-2">
                        <input type="text" id="txt_lining_code" name="txt_lining_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" id="txt_lining_desc" name="txt_lining_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt_lining_qty" name="txt_lining_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="txt_lining_uom" name="txt_lining_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                    </div>
            </div>     

            <h6 class="card-title">Accessories:</h6>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_btn_code">Buttons:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_btn_code" name="txt_btn_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_btn_desc" name="txt_btn_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_btn_qty" name="txt_btn_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_btn_uom" name="txt_btn_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_rivets_code">Rivets:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_rivets_code" name="txt_rivets_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_rivets_desc" name="txt_rivets_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_rivets_qty" name="txt_rivets_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_rivets_uom" name="txt_rivets_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_leather_patch_code">Leather Patch:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_leather_patch_code" name="txt_leather_patch_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_leather_patch_desc" name="txt_leather_patch_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_leather_patch_qty" name="txt_leather_patch_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_leather_patch_uom" name="txt_leather_patch_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_plastic_btn_code">Plastic Button:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_btn_code" name="txt_plastic_btn_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_plastic_btn_desc" name="txt_plastic_btn_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_btn_qty" name="txt_plastic_btn_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_btn_uom" name="txt_plastic_btn_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_inside_garter_code">Inside Garter:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_inside_garter_code" name="txt_inside_garter_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_inside_garter_desc" name="txt_inside_garter_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_inside_garter_qty" name="txt_inside_garter_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_inside_garter_uom" name="txt_inside_garter_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_hang_tag_code">Hang Tag:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_hang_tag_code" name="txt_hang_tag_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_hang_tag_desc" name="txt_hang_tag_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_hang_tag_qty" name="txt_hang_tag_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_hand_tag_uom" name="txt_hang_tag_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_zipper_code">Zipper:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_zipper_code" name="txt_zipper_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_zipper_desc" name="txt_zipper_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_zipper_qty" name="txt_zipper_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_zipper_uom" name="txt_zipper_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_size_lbl_code">Size Label:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_lbl_code" name="txt_size_lbl_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_size_lbl_desc" name="txt_size_lbl_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_lbl_qty" name="txt_size_lbl_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_lbl_uom" name="txt_size_lbl_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_size_care_lbl_code">Size Care Label:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_care_lbl_code" name="txt_size_care_lbl_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_size_care_lbl_desc" name="txt_size_care_lbl_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_care_lbl_qty" name="txt_size_care_lbl_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_size_care_lbl_uom" name="txt_size_care_lbl_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_side_lbl_code">Side Label:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_side_lbl_code" name="txt_side_lbl_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_side_lbl_desc" name="txt_side_lbl_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_side_lbl_qty" name="txt_side_lbl_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_side_lbl_uom" name="txt_side_lbl_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_kids_lbl_code">Kids Label:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_lbl_code" name="txt_kids_lbl_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_kids_lbl_desc" name="txt_kids_lbl_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_lbl_qty" name="txt_kids_lbl_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_lbl_uom" name="txt_kids_lbl_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_kids_side_lbl_code">Kids Side Label:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_side_lbl_code" name="txt_kids_side_lbl_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_kids_side_lbl_desc" name="txt_kids_side_lbl_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_side_lbl_qty" name="txt_kids_side_lbl_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_kids_side_lbl_uom" name="txt_kids_side_lbl_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div> 

            <h6 class="card-title">Packaging:</h6>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_plastic_bag_code">Plastic Bag:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_bag_code" name="txt_plastic_bag_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_plastic_bag_desc" name="txt_plastic_bag_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_bag_qty" name="txt_plastic_bag_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_plastic_bag_uom" name="txt_plastic_bag_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_barcode_code">Barcode:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_barcode_code" name="txt_barcode_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_barcode_desc" name="txt_barcode_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_barcode_qty" name="txt_barcode_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_barcode_uom" name="txt_barcode_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_fitting_sticker_code">Fitting Sticker:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_fitting_sticker_code" name="txt_fitting_sticker_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_fitting_sticker_desc" name="txt_fitting_sticker_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_fitting_sticker_qty" name="txt_fitting_sticker_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_fitting_sticker_uom" name="txt_fitting_sticker_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div>
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_tag_pin_code">Tag pin:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_tag_pin_code" name="txt_tag_pin_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_tag_pin_desc" name="txt_tag_pin_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_tag_pin_qty" name="txt_tag_pin_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_tag_pin_uom" name="txt_tag_pin_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div> 
                <div class="row mb-3 display:inline-block;">
                      <label class="col-sm-2 form-label" for="txt_chip_board_code">Chip Board:</label>
                      <div class="col-sm-2">
                          <input type="text" id="txt_chip_board_code" name="txt_chip_board_code" class="form-control form-control-sm" value="<?=$txt_ponumb;?>"/>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" id="txt_chip_board_desc" name="txt_chip_board_desc" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_chip_board_qty" name="txt_chip_board_qty" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" />    
                      </div>
                      <div class="col-sm-2">
                          <input type="text" id="txt_chip_board_uom" name="txt_chip_board_uom" class="form-control form-control-sm" value="<?=$txt_ponumb;?>" readonly/>
                      </div>
                </div>      
            
        

            <div class="row gy-2 mb-3">
              <div class="col-sm-4">
                <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
              </div>
            </div> <!-- end Save Records -->
            <?=form_close();?> <!-- end of ./form -->
            </div> <!-- end card-body -->
          </div>
    </div>

    <!-- <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title">Records</h6>
            <div class="pt-2 bg-dgreen mt-2"> 
               <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> Records</a>
               </nav>
               </div>
                  
               <div id="purchlist" class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">
                    <?php
                       // $data = $mytrxpurch->purch_rec_view(1,20);
                       // echo view('mtap/trx-purchase-order-recs',$data);
                    ?> 
                </div> 
          </div>
          </div>
    </div> -->
  </div> <!-- end row -->
  <?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>  
</main>    

<script type="text/javascript">
    __my_item_lookup();
    frack_lookup();
    fbin_lookup();
     __po_compute_totals();
    // <?php 
    // if($nporecs == 0) { 
    //     echo "my_add_line_item_gwpo();";
    // }
    // ?>
    //PARA SA TIMER NG TAMT TOTALS
    var tid = setInterval(myTamtTimer, 30000);
    function myTamtTimer() {
      __po_compute_totals();
      // do some stuff...
      // no need to recall the function (it's an interval, it'll loop forever)
    }
    jQuery('.meform_date').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true
    });
    
    jQuery('#txt_po_cls')
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
            source: '<?= site_url(); ?>get-poclass',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
        
           
            select: function( event, ui ) {
                var terms = ui.item.value;
                var mtkn_rrec = ui.item.mtkn_rrec;
                jQuery('#txt_po_cls').val(terms);
                jQuery('#txt_po_cls').attr("data-id-imp",mtkn_rrec);
               
                jQuery(this).autocomplete('search', jQuery.trim(terms));
                jQuery('#txtvend_code').val('');
                jQuery('#txtvend_code').attr("data-id-vnd",'');

                jQuery('#txtvend_addr').val('');
                jQuery('#txtvend_cont_persn').val('');
                jQuery('#txtvend_cont_persn_desgn').val('');
                jQuery('#txtvend_cont_persn_cnos').val('');
                jQuery('#txt-terms').val('');
                jQuery('#txtvend_code').focus();
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //vendor po class

    jQuery('#txtvend_code')
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
            source: '<?= site_url(); ?>get-vendor',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
        
            search: function(oEvent, oUi) {
                var sValue = jQuery(oEvent.target).val();
                var mtkn_rec = jQuery('#txt_po_cls').attr("data-id-imp");
                jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-vendor/?mtkn_rec=' + mtkn_rec); 
                
               
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txtvend_code').val(terms);
                jQuery('#txtvend_code').attr("data-id-vnd",ui.item.mtkn_rid);

                jQuery('#txtvend_addr').val(ui.item._address);
                jQuery('#txtvend_cont_persn').val(ui.item.cont_prsn);
                jQuery('#txtvend_cont_persn_desgn').val(ui.item.cp_desig);
                jQuery('#txtvend_cont_persn_cnos').val(ui.item.cp_no);
                jQuery('#txt-terms').val(ui.item._terms);
                

                jQuery(this).autocomplete('search', jQuery.trim(terms));
                
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //vendor

    jQuery('#txtvends_code')
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
            source: '<?= site_url(); ?>get-customer',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
        
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txtvends_code').val(terms);
                jQuery('#txtvends_code').attr("data-id-vnds",ui.item.mtkn_rid);

                jQuery('#txtvends_addr').val(ui.item._address);
                jQuery('#txtvends_cont_persn').val(ui.item.cont_prsn);
                jQuery('#txtvends_cont_persn_desgn').val(ui.item.cp_desig);
                jQuery('#txtvends_cont_persn_cnos').val(ui.item.cp_no);

                jQuery(this).autocomplete('search', jQuery.trim(terms));
                
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //shipto/customer

    jQuery('.active_plnt_id')
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
            source: '<?= site_url(); ?>get-plant-list',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
        
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#active_plnt_id').val(terms);
                jQuery('#active_plnt_id').attr("data-id-plant",ui.item.mtkn_rid);

                jQuery(this).autocomplete('search', jQuery.trim(terms));
                
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //plant


    jQuery('.active_wshe_id')
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
            source: '<?= site_url(); ?>get-warehouse-list',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) {
                var sValue = jQuery(oEvent.target).val();
                var mtkn_plnt = jQuery('#active_plnt_id').attr("data-id-plant");
                jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-warehouse-list/?mtkn_plnt=' + mtkn_plnt); 
                
               
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#active_wshe_id').val(terms);
                jQuery('#active_wshe_id').attr("data-id-whse",ui.item.mtkn_rid);

                jQuery(this).autocomplete('search', jQuery.trim(terms));
                
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //whse

    jQuery('#txt_itmgrparea_s')
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
            source: '<?= site_url(); ?>get-branch-group',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#txt_itmgrparea_s').val(terms);
                jQuery(this).autocomplete('search', jQuery.trim(terms));
                
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //branch group

    jQuery('#txt_itemcode')
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
            source: '<?= site_url(); ?>get-itemc',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                var txt_itmgrparea_s =  jQuery('#txt_itmgrparea_s').val();
                var wshe_id = jQuery('#active_wshe_id').attr("data-id-whse");
                if(wshe_id == ''){
                    alert('Plant and Warehouse is required');
                     return false;
                }

                jQuery('#txt_itemcode').val(terms);
                var _itemcode = ui.item.ART_CODE;
                var mdat1 = ui.item.ART_DESC;
                var mdat2 = ui.item.ART_SKU;
                var mdat3 = ui.item.ART_NCONVF;
                var mdat4 = ui.item.ART_UCOST;
                var __rid = ui.item.mtkn_rid;
                var mdat6 = ui.item.ART_NCBM;
                

                jQuery(this).autocomplete('search', jQuery.trim(terms));
                
                //AUTOADDLINES
                vw_wshename(wshe_id,_itemcode,mdat1,mdat2,mdat3,mdat4,__rid,mdat6,txt_itmgrparea_s);
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //itemc

    
    $('#mbtn_mn_NTRX').click(function() { 
        var userselection = confirm("Are you sure you want to new transaction?");
        if (userselection == true){
            window.location = '<?=site_url();?>me-purchase-vw';
         }
        else{
            $.hideLoading();
            return false;
        } 
    });

    function __do_makeid(){
    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    for( var i=0; i < 7; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

      return text;
    }


    function my_add_line_item_gwpo(wshe_bin_name,wshe_grp_name,wshe_bin,wshe_grp,plant_id,wshe_id,plant_code,wshe_code,itemcode,itemdesc,sku,convf,ucost,__rid,cbm) {  
      try {
          
          var rowCount = jQuery('#tbl-gwpoentry tr').length;
          var mid = __mysys_apps.__do_makeid() + (rowCount + 1);
          var clonedRow = jQuery('#tbl-gwpoentry tr:eq(' + (rowCount - 1) + ')').clone(); 

          jQuery(clonedRow).find('input[type=text]').eq(0).attr('id','mitemcode_' + mid).val(itemcode);
         jQuery(clonedRow).find('input[type=text]').eq(1).attr('id','mitemdesc_' + mid).val(itemdesc);
          jQuery(clonedRow).find('input[type=text]').eq(2).attr('id','mitemsku_' + mid).val(sku);
          jQuery(clonedRow).find('input[type=text]').eq(3).attr('id','mitemcf_' + mid).val(convf);
          jQuery(clonedRow).find('input[type=text]').eq(4).attr('id','mitemqty_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(5).attr('id','mitemcfqty_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(6).attr('id','mitemcost_' + mid).val(ucost);
          jQuery(clonedRow).find('input[type=text]').eq(7).attr('id','mitemcbm_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(8).attr('id','mitemtamt_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(9).attr('id','mitemdisc_' + mid);
          jQuery(clonedRow).find('input[type=text]').eq(10).attr('id','mitemnamt_' + mid);


          jQuery(clonedRow).find('input[type=text]').eq(11).attr('id','txt-wshe-grp-' + mid).val(wshe_grp_name);
          jQuery(clonedRow).find('input[type=text]').eq(12).attr('id','txt-wshe-sbin-' + mid).val(wshe_bin_name);
          jQuery(clonedRow).find('input[type=text]').eq(13).attr('id','txt-mtext-' + mid);
          

          jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id','mitemrid_' + mid).val(__rid);
          jQuery(clonedRow).find('input[type=hidden]').eq(2).attr('id','mwshe_bin_' + mid).val(wshe_bin);
          jQuery(clonedRow).find('input[type=hidden]').eq(3).attr('id','mwshe_grp_' + mid).val(wshe_grp);
          jQuery(clonedRow).find('input[type=hidden]').eq(4).attr('id','mitemcbmh_' + mid).val(cbm);
          
          jQuery('#tbl-gwpoentry tr').eq(rowCount - 1).before(clonedRow);
          jQuery(clonedRow).css({'display':''});
          var xobjArtItem= jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
          jQuery('#' + xobjArtItem).focus();
          $( '#tbl-gwpoentry tr').each(function(i) { 
                  $(this).find('td').eq(0).html(i);
          });
          
          __my_item_lookup();
          frack_lookup();
          fbin_lookup();
          __po_compute_totals();
              
      } catch(err) { 
          var mtxt = 'There was an error on this page.\\n';
          mtxt += 'Error description: ' + err.message;
          mtxt += '\\nClick OK to continue.';
          alert(mtxt);
          return false;
      }  //end try 
    }
    
    function vw_wshename(wshe_name,itemcode,itemdesc,sku,convf,ucost,__rid,cbm,txt_itmgrparea_s){

            try { 
                __mysys_apps.mepreloader('mepreloaderme',true);
                
                        var mparam = {
                            wshe_id: wshe_name,
                            itemcode : itemcode,
                            itemdesc : itemdesc,
                            sku : sku,
                            convf : convf,
                            ucost :ucost,
                            __rid : __rid,
                            cbm : cbm,
                            txt_itmgrparea_s: txt_itmgrparea_s

                        }; 
                       //console.log(mparam);
                      jQuery.ajax({ // default declaration of ajax parameters
                        type: "POST",
                        url: '<?=site_url();?>me-auto-addlines-po',
                        context: document.body,
                        data: eval(mparam),
                        global: false,
                        cache: false,

                        success: function(data)  { //display html using divID
                            __mysys_apps.mepreloader('mepreloaderme',false);
                            jQuery('#__me_numerate_wshe__').html(data);
                            return false;
                        },
                        error: function() { // display global error on the menu function
                            alert('error loading page...');
                           __mysys_apps.mepreloader('mepreloaderme',false);
                            return false;
                        }   
            }); 
            } catch(err) {
                var mtxt = 'There was an error on this page.\n';
                mtxt += 'Error description: ' + err.message;
                mtxt += '\nClick OK to continue.';
                alert(mtxt);
                 __mysys_apps.mepreloader('mepreloaderme',false);
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
              source: '<?= site_url(); ?>get-itemc',
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
                  var xobjitemrid = jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id'); //ID
                  
                  var xobjitemdesc = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');/*DESC*/
                  var xobjitemsku = jQuery(clonedRow).find('input[type=text]').eq(2).attr('id');/*PACKAGING*/
                  var xobjitemconf = jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');/*CONVF*/
                  var xobjitemcost = jQuery(clonedRow).find('input[type=text]').eq(6).attr('id');/*cost*/
                  var xobjitemcbm = jQuery(clonedRow).find('input[type=text]').eq(7).attr('id'); /*CBM*/

                  $('#' + xobjitemrid).val(ui.item.mtkn_rid);
                  $('#' + xobjitemdesc).val(ui.item.ART_DESC);
                  $('#' + xobjitemsku).val(ui.item.ART_SKU);
                  $('#' + xobjitemconf).val(ui.item.ART_NCONVF);
                  $('#' + xobjitemcost).val(ui.item.ART_UCOST);
                  $('#' + xobjitemcbm).val(ui.item.ART_NCBM);


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

    function frack_lookup(){
        jQuery('.frack_lookup' ) 
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
              source: '<?= site_url(); ?>get-warehouse-group',
              focus: function() {
                  // prevent value inserted on focus
                  return false;
              },
              search: function(oEvent, oUi) { 
                  var sValue = jQuery(oEvent.target).val();

                  var mtkn_whse = jQuery('#active_wshe_id').attr("data-id-whse");
                  if(mtkn_whse == ''){
                    jQuery('#memsgtestent_danger_bod').html('Please select warehouse.');
                    jQuery('#memsgtestent_danger').modal('show');
                    alert('Please select warehouse.');
                    return false;
                  }
                  $(this).autocomplete('option', 'source', '<?=site_url();?>get-warehouse-group-v/?mtkn_uid='+mtkn_whse);
              },
              select: function( event, ui ) {
                  var terms = ui.item.value;
                  
                  jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                  jQuery(this).attr('title', jQuery.trim(ui.item.value));
               
                  this.value = ui.item.value;
              

                  var clonedRow = jQuery(this).parent().parent().clone();
                  var indexRow = jQuery(this).parent().parent().index();
                  var xobjgrp = jQuery(clonedRow).find('input[type=hidden]').eq(3).attr('id');
                  $('#' + xobjgrp).val(ui.item.mtkn_rid);

                  return false;
              }
          })
          .click(function() { 

              //jQuery(this).keydown(); 
              var terms = this.value;
              //jQuery(this).autocomplete('search', '');
              jQuery(this).autocomplete('search', jQuery.trim(terms));
              __po_compute_totals();
          });  
        }



    function fbin_lookup( ) { 
      $('.fbin_lookup' ) 
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
              source: '<?= site_url(); ?>get-warehouse-sbin',
              focus: function() {
                  // prevent value inserted on focus
                  return false;
              },
              search: function(oEvent, oUi) { 
                  var sValue = jQuery(oEvent.target).val();
              
                  var mtkn_uid = '';
                  var mtkn_wshegrp = '';
                  
  
                  
                  var mtkn_whse = jQuery('#active_wshe_id').attr("data-id-whse");
                  
                  var clonedRow = jQuery(this).parent().parent().clone();
                  var xobjWshegrp = jQuery(clonedRow).find('input[type=hidden]').eq(3).attr('id');
                  var mtkn_wshegrp = $('#' + xobjWshegrp).val();

                  if(mtkn_wshegrp == ''){
                    jQuery('#memsgtestent_danger_bod').html('Please select rack.');
                    jQuery('#memsgtestent_danger').modal('show');
                    return false;
                  }
                  $(this).autocomplete('option', 'source', '<?=site_url();?>get-warehouse-sbin/?mtkn_uid='+mtkn_whse+'&mtkn_wshe_grp='+mtkn_wshegrp);
              },
              select: function( event, ui ) {
                  var terms = ui.item.value;
                  
                  jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                  jQuery(this).attr('title', jQuery.trim(ui.item.value));
                  
                  this.value = ui.item.value;

                  var clonedRow = jQuery(this).parent().parent().clone();
                  var indexRow = jQuery(this).parent().parent().index();
                  var xobjBin = jQuery(clonedRow).find('input[type=hidden]').eq(2).attr('id');
                  $('#' + xobjBin).val(ui.item.mtkn_rid);

                  return false;
              }
          })
          .click(function() { 
              //jQuery(this).keydown(); 
              var terms = this.value;
              //jQuery(this).autocomplete('search', '');
              jQuery(this).autocomplete('search', jQuery.trim(terms));
              __po_compute_totals();
          });         
      
    }  //end __my_wshe_lkup   

    function __cbm_compute(obj) { 
        var getQTY         = obj.value;
        var clonedRow      = jQuery(obj).parent().parent().clone();
        var xobjArtMCBMIdh = jQuery(clonedRow).find('input[type=hidden]').eq(4).attr('id');
        var xobjArtMCBMId  = jQuery(clonedRow).find('input[type=text]').eq(7).attr('id');

        var xobjArtTamtId  = jQuery(clonedRow).find('input[type=text]').eq(8).attr('id');

        var getCBMVal      = jQuery('#'+xobjArtMCBMIdh).val();
        var getCBMVal      = parseFloat(getCBMVal);

        var getTamtVal      = jQuery('#'+xobjArtTamtId).val();
        var getTamtVal      = parseFloat(getTamtVal);
                

        if(($.isNumeric(getQTY) && $.isNumeric(getCBMVal)) && getQTY!='' ){
              var totalcbm=getQTY*getCBMVal;
              var totalcbm=parseFloat(totalcbm).toFixed(4);

              jQuery('#'+xobjArtMCBMId).val(totalcbm);

        }
        else{

              jQuery('#'+xobjArtMCBMId).val(getCBMVal);


        }

        if(($.isNumeric(getQTY) && $.isNumeric(getTamtVal)) && getQTY!='' ){
              var totaltamt=getQTY*getTamtVal;
              var totaltamt=parseFloat(totaltamt).toFixed(4);

              jQuery('#'+xobjArtTamtId).val(totaltamt);

          }
          else{

              jQuery('#'+xobjArtTamtId).val(getTamtVal);


          }
    __po_compute_totals();

    }

    function __po_compute_totals() { 

            try { 
                var rowCount1 = jQuery('#tbl-gwpoentry tr').length - 1;
            var adata1 = [];
            var adata2 = [];
            var mdata = '';
            var ninc = 0;
            var nTAmount = 0;
            var nTQty = 0;
            var nTQtyItems = 0;
            for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-gwpoentry tr:eq(' + aa + ')').clone(); 
                var mdat4 = jQuery(clonedRow).find('input[type=text]').eq(3).val();
                var mdat5 = jQuery(clonedRow).find('input[type=text]').eq(4).val();
                var mdat7 = jQuery(clonedRow).find('input[type=text]').eq(6).val();
                var mdat8 = jQuery(clonedRow).find('input[type=text]').eq(7).val();
                var mdat9 = jQuery(clonedRow).find('input[type=text]').eq(8).val();
                var mdat10 = jQuery(clonedRow).find('input[type=text]').eq(9).val();
               
                var xCFQtyId = jQuery(clonedRow).find('input[type=text]').eq(5).attr('id');
                var xTAmntId = jQuery(clonedRow).find('input[type=text]').eq(8).attr('id');
                var xNetAmntId = jQuery(clonedRow).find('input[type=text]').eq(10).attr('id');
                var nconvf = 0;
                var nqty = 0;
                var nprice = 0;
                if($.trim(mdat4) == '') { 
                    nconvf = 0;
                } else { 
                    nconvf = mdat4;
                }
                if($.trim(mdat5) == '') { 
                    nqty = 0;
                } else { 
          //__mysys_apps.oa_ommit_comma
                    nqty = mdat5;
                }
                if($.trim(mdat7) == '') { 
                    nprice = 0;//COST
                } else { 
                    nprice =mdat7;
                }

                  if($.trim(xTAmntId) == '') { 
                    nprice2 = 0;
                } else { 
                    nprice2 = xTAmntId;
                }
                 //DISCOUNT
                if($.trim(mdat10) == '') { 
                    ndisc = 0;
                } else { 
                    ndisc =mdat10;
                }
                //NET AMT
               if($.trim(xNetAmntId) == '') { 
                    netamt = 0;
                } else { 
                    netamt = xNetAmntId;
                }

                var ntqty = parseFloat(nconvf * nqty);
                if($('#' + xTAmntId).val()==''){
                  var ntprice = parseFloat(nprice * ntqty);
                }
                else{

                     var ntprice = parseFloat(nprice * ntqty);
                }

            
                if(!isNaN(ntqty) || ntqty > 0) { 
                    $('#' + xCFQtyId).val(ntqty.toFixed(4));
                }
                
                if(!isNaN(ntprice) || ntprice > 0) { 
                    $('#' + xTAmntId).val(ntprice.toFixed(2));
                   // console.log(xTAmntId);
                }
                nTAmount = (nTAmount + ntprice);
                nTQty = (nTQty + parseFloat(nqty));
                nTQtyItems = (nTQtyItems + ntqty);
                if($('#' + xTAmntId).val()=='') { 
                    var nNetAmount = parseFloat(ntprice - ndisc);
                }
                else{
                    var nNetAmount = parseFloat(ntprice - ndisc);
                }

                if(!isNaN(nNetAmount) || nNetAmount > 0) { 
                    $('#' + xNetAmntId).val(nNetAmount .toFixed(4));
                }
            }  //end for 
            
            //$('#txtpo_totals').val(__mysys_apps.oa_addCommas(nTAmount));
            $('#txtpo_qty').val(__mysys_apps.oa_addCommas(nTQty));
            $('#txtpo_tsku').val(__mysys_apps.oa_addCommas(nTQtyItems));
            $('#txtpo_totals').val(__mysys_apps.oa_addCommas(nTAmount));
        } catch(err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            alert(mtxt);
            $.hideLoading();
            return false;
        }  //end try            
    }

     $('#tbl-gwpoentry').on('keydown', "input", function(e) { 
      switch(e.which) {
          case 37: // left 
          break;

          case 38: // up
              var nidx_rw = jQuery(this).parent().parent().index();
              var nidx_td = $(this).parent().index();
              if(nidx_td == 4) { 
              } else { 
                  var clonedRow = jQuery('#tbl-gwpoentry  tr:eq(' + (nidx_rw) + ')').clone(); 
                  var el_id = jQuery(clonedRow).find('td').eq(nidx_td).find('input[type=text]').eq(0).attr('id');
                  $('#' + el_id).focus();
              }
              
              break;

          case 39: // right
              break;

          case 40: // down
              var nidx_rw = jQuery(this).parent().parent().index();
              var nidx_td = $(this).parent().index();
              if(nidx_td == 4) { 
              } else { 
                  var clonedRow = jQuery('#tbl-gwpoentry  tr:eq(' + (nidx_rw + 2) + ')').clone(); 
                  var el_id = jQuery(clonedRow).find('td').eq(nidx_td).find('input[type=text]').eq(0).attr('id');
                  //alert(nidx_rw + ':' + nidx_td + ':' + el_id);
                  $('#' + el_id).focus();
              }
              
              break;
          default: return; // exit this handler for other keys
      }
      //e.preventDefault(); // prevent the default action (scroll / move caret)
  });

  // (function () {
  //   'use strict'

  //   // Fetch all the forms we want to apply custom Bootstrap validation styles to
  //   var forms = document.querySelectorAll('.needs-validations')
  //   // Loop over them and prevent submission
  //   Array.prototype.slice.call(forms)
  //   .forEach(function (form) {
  //     form.addEventListener('submit', function (event) {
  //       if (!form.checkValidity()) {
  //         event.preventDefault()
  //         event.stopPropagation()
  //       }
  //       form.classList.add('was-validated') 

  //       try {
  //         event.preventDefault();
  //               event.stopPropagation();
          
  //         __mysys_apps.mepreloader('mepreloaderme',true);
          
  //         var __hmtkn_vndrtr = jQuery('#__hmtkn_vndrtr').val();
  //         var __hmtkn_vndsrtr = jQuery('#__hmtkn_vndsrtr').val();

  //         var mtkn_mntr = jQuery('#mtkn_mntr').val();
  //         var txt_ponumb = jQuery('#txt_ponumb').val();
  //         var txt_po_cls = jQuery('#txt_po_cls').val();
  //         var txt_tdate = jQuery('#txt_tdate').val();
  //         var txt_ddate = jQuery('#txt_ddate').val();
  //         var txtvend_addr = jQuery('#txtvend_addr').val();
  //         var txtvend_code = jQuery('#txtvend_code').val();
  //         var txtvend_cont_persn = jQuery('#txtvend_cont_persn').val();
  //         var txtvend_cont_persn_desgn = jQuery('#txtvend_cont_persn_desgn').val();
  //         var txtvend_cont_persn_cnos =  jQuery('#txtvend_cont_persn_cnos').val();
  //         var txtvends_addr = jQuery('#txtvends_addr').val();
  //         var txtvends_addr = jQuery('#txtvends_addr').val();
  //         var txtvends_cont_persn = jQuery('#txtvends_cont_persn').val();
  //         var txtvends_cont_persn_desgn  = jQuery('#txtvends_cont_persn_desgn').val();
          
  //         var txtvends_cont_persn_cnos = jQuery('#txtvends_cont_persn_cnos').val();
  //         var txt_drlist = jQuery('#txt_drlist').val();
  //         var txt_remk = jQuery('#txt_remk').val();
  //         var terms = jQuery('#terms').val();

  //         var txtpo_totals = jQuery('#txtpo_totals').val();
  //         var txtpo_qty = jQuery('#txtpo_qty').val();
  //         var txtpo_tsku = jQuery('#txtpo_tsku').val();
         
  //         var active_plnt_id = jQuery('#active_plnt_id').val();
  //         var active_wshe_id = jQuery('#active_wshe_id').val();

  //         var mtkn_plnt = jQuery('#active_plnt_id').attr("data-id-plant");
  //         var mtkn_whse = jQuery('#active_wshe_id').attr("data-id-whse");
  //         var rowCount1 = jQuery('#tbl-gwpoentry tr').length - 1;
  //         var adata1 = [];
  //         var adata2 = [];

  //         var mdata = '';
  //         var ninc = 0;

  //         for(aa = 1; aa < rowCount1; aa++) { 
  //               var clonedRow = jQuery('#tbl-gwpoentry tr:eq(' + aa + ')').clone(); 
  //               var mdat1 = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //ITEM CODE
  //               var mdat2 = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //ITEM DESC
  //               var mdat3 = jQuery(clonedRow).find('input[type=text]').eq(2).val(); //PACKAGING
  //               var mdat4 = jQuery(clonedRow).find('input[type=text]').eq(3).val(); //CONVF
  //               var mdat5 = jQuery(clonedRow).find('input[type=text]').eq(4).val(); //QTY
  //               var mdat6 = jQuery(clonedRow).find('input[type=text]').eq(5).val(); //TOTPCS
  //               var mdat7 = jQuery(clonedRow).find('input[type=text]').eq(6).val(); //cost

  //               var cbm = jQuery(clonedRow).find('input[type=text]').eq(7).val(); //CBM

  //               var mdat8 = jQuery(clonedRow).find('input[type=text]').eq(8).val(); //TAMT
  //               var mdat9 = jQuery(clonedRow).find('input[type=text]').eq(9).val(); //DISC
  //               var mdat10 = jQuery(clonedRow).find('input[type=text]').eq(10).val(); //NAMT
                
  //               var mdat13 = jQuery(clonedRow).find('input[type=text]').eq(11).val(); //WHSE SBIN
  //               var mdat14 = jQuery(clonedRow).find('input[type=hidden]').eq(1).val(); 
  //               var mdat15 = jQuery(clonedRow).find('input[type=text]').eq(12).val(); //GRP
  //               var mdat16 = jQuery(clonedRow).find('input[type=text]').eq(13).val(); //STEXT
                
  //               var wshe_sbin_id = jQuery(clonedRow).find('input[type=hidden]').eq(2).val();
  //               var wshe_grp_id = jQuery(clonedRow).find('input[type=hidden]').eq(3).val();
  //               var wshe_barcdng_id = '';//jQuery(clonedRow).find('input[type=hidden]').eq(6).val();
                
  //               mdata = mdat1 + 'x|x' + mdat2 + 'x|x' + mdat3 + 'x|x' + mdat4 + 'x|x' + mdat5 + 'x|x' + mdat6 + 'x|x' + mdat7 + 'x|x' + mdat8 + 'x|x' + mdat9 + 'x|x' + mdat10 + 'x|x' + active_plnt_id + 'x|x' + active_wshe_id + 'x|x' + mdat13+ 'x|x'  + mdat14+ 'x|x'  + mdat15+ 'x|x'  + mtkn_plnt+ 'x|x' + mtkn_whse+ 'x|x' + wshe_sbin_id+ 'x|x' + wshe_grp_id+ 'x|x' + cbm + 'x|x' + mdat16;
  //               adata1.push(mdata);
  //               var mdat = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
  //               adata2.push(mdat);


  //           }  //end for

  //         var mparam = {
  //           mtkn_mntr:mtkn_mntr,
  //           txt_ponumb:txt_ponumb,
  //           txt_po_cls: txt_po_cls,
  //           txt_tdate: txt_tdate,
  //           txt_ddate: txt_ddate,
  //           txtvend_addr: txtvend_addr,
  //           txtvend_code: txtvend_code,
  //           txtvend_cont_persn: txtvend_cont_persn,
  //           txtvend_cont_persn_desgn: txtvend_cont_persn_desgn,
  //           txtvend_cont_persn_cnos:txtvend_cont_persn_cnos,
  //           txtvends_addr: txtvends_addr,
  //           txtvends_addr: txtvends_addr,
  //           txtvends_cont_persn: txtvends_cont_persn,
  //           txtvends_cont_persn_desgn: txtvends_cont_persn_desgn,
  //           txtvends_cont_persn_cnos: txtvends_cont_persn_cnos,
  //           txt_drlist: txt_drlist,
  //           txt_remk: txt_remk,
  //           terms:terms,
  //           txtpo_totals:txtpo_totals,
  //           txtpo_qty:txtpo_qty,
  //           txtpo_tsku: txtpo_tsku,
  //           __hmtkn_vndrtr:__hmtkn_vndrtr,
  //           __hmtkn_vndsrtr:__hmtkn_vndsrtr,
  //           active_plnt_id:active_plnt_id,
  //           active_wshe_id:active_wshe_id,
  //           adata1: adata1,
  //           adata2: adata2
  //         };  
  //         jQuery.ajax({ // default declaration of ajax parameters
  //         type: "POST",
  //         url: '<?=site_url();?>me-purchase-save',
  //         context: document.body,
  //         data: eval(mparam),
  //         global: false,
  //         cache: false,
  //           success: function(data)  { //display html using divID
  //               __mysys_apps.mepreloader('mepreloaderme',false);
  //                         jQuery('#memsgtestent_bod').html(data);
  //                         jQuery('#memsgtestent').modal('show');
                
  //               return false;
  //           },
  //           error: function() { // display global error on the menu function 
  //             __mysys_apps.mepreloader('mepreloaderme',false);
  //             alert('error loading page...');
  //             return false;
  //           } 
  //         }); 
  //       } catch(err) { 
  //         __mysys_apps.mepreloader('mepreloaderme',false);
  //         var mtxt = 'There was an error on this page.\n';
  //         mtxt += 'Error description: ' + err.message;
  //         mtxt += '\nClick OK to continue.';
  //         alert(mtxt);
  //         return false;
  //       }  //end try          
  //     }, false)
  //   })
  // })();

  $("#mbtn_mn_Save").click(function(e){
    try { 
    //__mysys_apps.mepreloader('mepreloaderme',true);
          
          var __hmtkn_vndrtr = jQuery('#txtvend_code').attr("data-id-vnd");;//jQuery('#__hmtkn_vndrtr').val();
          var __hmtkn_vndsrtr = jQuery('#txtvends_code').attr("data-id-vnds");;//jQuery('#__hmtkn_vndsrtr').val();

          var mtkn_mntr = jQuery('#__hmpotrxnoid').val();
          var txt_ponumb = jQuery('#txt_ponumb').val();
          var txt_po_cls = jQuery('#txt_po_cls').val();
          var txt_tdate = jQuery('#txt_tdate').val();
          var txt_ddate = jQuery('#txt_ddate').val();
          var txtvend_addr = jQuery('#txtvend_addr').val();
          var txtvend_code = jQuery('#txtvend_code').val();
          var txtvend_cont_persn = jQuery('#txtvend_cont_persn').val();
          var txtvend_cont_persn_desgn = jQuery('#txtvend_cont_persn_desgn').val();
          var txtvend_cont_persn_cnos =  jQuery('#txtvend_cont_persn_cnos').val();
          var txtvends_code = jQuery('#txtvends_code').val();
          var txtvends_addr = jQuery('#txtvends_addr').val();
          var txtvends_cont_persn = jQuery('#txtvends_cont_persn').val();
          var txtvends_cont_persn_desgn  = jQuery('#txtvends_cont_persn_desgn').val();
          
          var txtvends_cont_persn_cnos = jQuery('#txtvends_cont_persn_cnos').val();
          var txt_drlist = jQuery('#txt_drlist').val();
          var txt_remk = jQuery('#txt_remk').val();
          var terms = jQuery('#terms').val();

          var txtpo_totals = jQuery('#txtpo_totals').val();
          var txtpo_qty = jQuery('#txtpo_qty').val();
          var txtpo_tsku = jQuery('#txtpo_tsku').val();
         
          var active_plnt_id = jQuery('#active_plnt_id').val();
          var active_wshe_id = jQuery('#active_wshe_id').val();

          var mtkn_plnt = jQuery('#active_plnt_id').attr("data-id-plant");
          var mtkn_whse = jQuery('#active_wshe_id').attr("data-id-whse");
          var rowCount1 = jQuery('#tbl-gwpoentry tr').length - 1;
          var adata1 = [];
          var adata2 = [];

          var mdata = '';
          var ninc = 0;

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-gwpoentry tr:eq(' + aa + ')').clone(); 
                var mdat1 = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //ITEM CODE
                var mdat2 = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //ITEM DESC
                var mdat3 = jQuery(clonedRow).find('input[type=text]').eq(2).val(); //PACKAGING
                var mdat4 = jQuery(clonedRow).find('input[type=text]').eq(3).val(); //CONVF
                var mdat5 = jQuery(clonedRow).find('input[type=text]').eq(4).val(); //QTY
                var mdat6 = jQuery(clonedRow).find('input[type=text]').eq(5).val(); //TOTPCS
                var mdat7 = jQuery(clonedRow).find('input[type=text]').eq(6).val(); //cost

                var cbm = jQuery(clonedRow).find('input[type=text]').eq(7).val(); //CBM

                var mdat8 = jQuery(clonedRow).find('input[type=text]').eq(8).val(); //TAMT
                var mdat9 = jQuery(clonedRow).find('input[type=text]').eq(9).val(); //DISC
                var mdat10 = jQuery(clonedRow).find('input[type=text]').eq(10).val(); //NAMT
                
                var mdat13 = jQuery(clonedRow).find('input[type=text]').eq(11).val(); //WHSE SBIN
                var mdat14 = jQuery(clonedRow).find('input[type=hidden]').eq(1).val(); 
                var mdat15 = jQuery(clonedRow).find('input[type=text]').eq(12).val(); //GRP
                var mdat16 = jQuery(clonedRow).find('input[type=text]').eq(13).val(); //STEXT
                
                var wshe_sbin_id = jQuery(clonedRow).find('input[type=hidden]').eq(2).val();
                var wshe_grp_id = jQuery(clonedRow).find('input[type=hidden]').eq(3).val();
                var wshe_barcdng_id = '';//jQuery(clonedRow).find('input[type=hidden]').eq(6).val();
                
                mdata = mdat1 + 'x|x' + mdat2 + 'x|x' + mdat3 + 'x|x' + mdat4 + 'x|x' + mdat5 + 'x|x' + mdat6 + 'x|x' + mdat7 + 'x|x' + mdat8 + 'x|x' + mdat9 + 'x|x' + mdat10 + 'x|x' + active_plnt_id + 'x|x' + active_wshe_id + 'x|x' + mdat13+ 'x|x'  + mdat14+ 'x|x'  + mdat15+ 'x|x'  + mtkn_plnt+ 'x|x' + mtkn_whse+ 'x|x' + wshe_sbin_id+ 'x|x' + wshe_grp_id+ 'x|x' + cbm + 'x|x' + mdat16;
                adata1.push(mdata);
                var mdat = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
                adata2.push(mdat);


            }  //end for

          var mparam = {
            mtkn_mntr:mtkn_mntr,
            txt_ponumb:txt_ponumb,
            txt_po_cls: txt_po_cls,
            txt_tdate: txt_tdate,
            txt_ddate: txt_ddate,
            txtvend_addr: txtvend_addr,
            txtvend_code: txtvend_code,
            txtvend_cont_persn: txtvend_cont_persn,
            txtvend_cont_persn_desgn: txtvend_cont_persn_desgn,
            txtvend_cont_persn_cnos:txtvend_cont_persn_cnos,
            txtvends_code: txtvends_code,
            txtvends_addr: txtvends_addr,
            txtvends_cont_persn: txtvends_cont_persn,
            txtvends_cont_persn_desgn: txtvends_cont_persn_desgn,
            txtvends_cont_persn_cnos: txtvends_cont_persn_cnos,
            txt_drlist: txt_drlist,
            txt_remk: txt_remk,
            terms:terms,
            txtpo_totals:txtpo_totals,
            txtpo_qty:txtpo_qty,
            txtpo_tsku: txtpo_tsku,
            __hmtkn_vndrtr:__hmtkn_vndrtr,
            __hmtkn_vndsrtr:__hmtkn_vndsrtr,
            active_plnt_id:active_plnt_id,
            active_wshe_id:active_wshe_id,
            adata1: adata1,
            adata2: adata2
          };  

  
      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-rm-req-save',
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
        url: "<?=site_url();?>me-purchase-view",
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

  $('#anchor-items').on('click',function(){
    $('#anchor-items').addClass('active');
    $('#anchor-list').removeClass('active');
    var mtkn_whse = '';
    mypo_view_appr(mtkn_whse);

});

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
          source: '<?= site_url(); ?>get-fg-code-list',
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
                
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));
          
    });
</script>