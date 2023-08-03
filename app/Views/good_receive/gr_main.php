<?php 
/**
*  File        : good_receive/gr_main.php
*  Author      : Arnel L. Oquien
*  Date Created: Nov 22,2022
*  last update : Dec 12,2022
*  description : Good receive entry crossdocking
*/

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$mywhout = model('App\Models\MyWarehouseoutModel');
$db_erp =$mydbname->medb(1);

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

$str_convf_disp=""; // style=\"display:none;\"

$mtkn_trxno  = $request->getVar('mtkn_trxno');
$agr_type    = $mydataz->lk_Active_GRTYPE($db_erp);
$txtgr_type  = '';
$fld_ptyp    = $request->getVar('fld_ptyp');
$agr_class   = $mydataz->lk_Active_GRCLASS($db_erp);


$txtgr_class = '';
$asysdate    = $mydataz->__get_mysysdatetime();

$txtgrdate   = $asysdate[2];
$txtgrtrx_no = '';
$txtcomp  = '';
$txtplant = '';
$txtwshe  = '';
$txtrefno = '';
$txtrems  = '';

$mmnhd_rid   = '';
$nmnrecs     = 0;
$txtsubtqty  = '';
$txtsubtcost = '';
$txtsubtamt  = '';
$mtkn_active_plnt_id = '';
$mtkn_active_wshe_id = '';
$is_apprvd = '';
$str_appr  = '';

$txtrack             = '';
$txtbin              = '';
$mtkn_active_rack_id = '';
$mtkn_active_bin_id  = '';
$ischecked           ='';

$dis3 ='';

if(!empty($mtkn_trxno)) { 
    $str = "SELECT aa.`recid`,aa.`grtrx_no`,aa.`ref_no`,aa.`encd_date`,aa.`remk`,aa.`grtype_id`,aa.`class_id`,aa.`hd_subtqty`,
    aa.`hd_subtcost`,
    aa.`hd_subtamt`,
    aa.`plant_id`,
    aa.`wshe_id`,
    aa.`is_asstd`,
    aa.`is_apprvd`,
    aa.`is_bcodegen`,
    bb.`COMP_NAME`,
    cc.`grtype_desc`,
    dd.`plnt_code`,
    ee.`wshe_code`,
    ff.`wshe_grp`,
    gg.`wshe_bin_name`,
    hh.`PO_CLS_CODE`,
    sha2(concat(dd.`recid`,'{$mpw_tkn}'),384) plant_id,
    sha2(concat(ee.`recid`,'{$mpw_tkn}'),384) wshe_id,
    sha2(concat(ff.`recid`,'{$mpw_tkn}'),384) rack_id,
    sha2(concat(gg.`recid`,'{$mpw_tkn}'),384) bin_id,
    sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) mtkn_trxtr 
    from {$db_erp}.`trx_wshe_gr_hd` aa
    JOIN {$db_erp}.`mst_company` bb ON (aa.`comp_id` = bb.`recid`)
    JOIN {$db_erp}.`mst_wshe_gr_type` cc ON (aa.`grtype_id` = cc.`recid`)
    JOIN {$db_erp}.`mst_plant` dd ON (aa.`plant_id` = dd.`recid`)
    JOIN {$db_erp}.`mst_wshe` ee ON (aa.`wshe_id` = ee.`recid`)
    JOIN {$db_erp}.`mst_wshe_grp` ff ON (aa.`rack_id` = ff.`recid` AND ff.`plnt_id`  = aa.`plant_id` AND ff.`wshe_id` = aa.`wshe_id`)
    JOIN {$db_erp}.`mst_wshe_bin` gg ON (aa.`bin_id` = gg.`recid` AND gg.`plnt_id`  = aa.`plant_id` AND gg.`wshe_id` = aa.`wshe_id` AND aa.`rack_id` = gg.`wshegrp_id`)
    LEFT JOIN {$db_erp}.`mst_po_class` hh
    ON (aa.`class_id` = hh.`recid`)
    where sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) = '$mtkn_trxno' AND aa.`cd_tag` = 'Y' ";
    $qq = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    $rw = $qq->getRowArray();
    $mmnhd_rid = $rw['mtkn_trxtr'];
    $txtgrtrx_no = $rw['grtrx_no'];
    $txtcomp = $rw['COMP_NAME'];
    $txtplant= $rw['plnt_code'];
    $txtwshe= $rw['wshe_code'];
    $txtrefno = $rw['ref_no'];
    $txtgrdate = $mylibzsys->mydate_mmddyyyy($rw['encd_date']);
    $txtrems = $rw['remk'];
    $txtgr_type = $rw['grtype_id'];
    $txtgr_class = $rw['class_id'];
    $txtsubtqty= number_format($rw['hd_subtqty'],2,'.','');
    $txtsubtcost= number_format($rw['hd_subtcost'],2,'.','');
    $txtsubtamt= number_format($rw['hd_subtamt'],2,'.','');
    $mtkn_active_plnt_id = $rw['plant_id'];
    $mtkn_active_wshe_id = $rw['wshe_id'];

    $txtrack= $rw['wshe_grp'];
    $txtbin= $rw['wshe_bin_name'];
    $mtkn_active_rack_id = $rw['rack_id'];
    $mtkn_active_bin_id = $rw['bin_id'];
    $is_asstd = $rw['is_asstd'];
    $is_apprvd = $rw['is_apprvd'];
    if($is_apprvd === 'Y'){
        $str_appr=" style=\"display:none;\"";
    }
//ALAMIN KUNG TRADE NON TRADE
    $str = "SELECT potrx_no,po_type FROM {$db_erp}.`trx_manrecs_po_hd` 
    WHERE `potrx_no` = '{$txtrefno}' AND `post_tag` = 'Y'";
    $q7 = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    if($q7->getNumRows() > 0) { 
        $rw7        = $q7->getRowArray();
        $fld_ptyp   = $rw7['po_type'];
    }

// //FOR PULLOUT
// if($txtgr_type == 3){
//     $str_convf_disp="";
// }
//var_dump($is_asstd);
    $ischecked = '';
    if($is_asstd ==='Y'){
        $ischecked = "checked";
    }
    $dis3 = (($rw['is_apprvd'] == 'Y' && $rw['is_bcodegen'] == 'Y') || (($rw['is_apprvd'] == 'Y' && $rw['is_bcodegen'] == 'N')) ? "disabled" : '');

}
$str_style='';
$str_dis="";




?>

<!-- Main Wrapper -->


<main id="main" >
    <div class="pagetitle">
        <h1>Good Receive (GR) </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Good Receive Entry</li>
                <li class="breadcrumb-item "> <a href="#headingTwo">Workflow</a> </li>
                <li class="breadcrumb-item "> <a href="#headingThree">Logfile</a></li>
                <li class="breadcrumb-item "> <a href="#headingFour">Summary</a></li>
                <li class="breadcrumb-item "> <a href="#headingFive">Box Barcode</a></li>
            </ol>
        </nav>
    </div>
      <section class="forms"> 
        <div class="container-fluid" >
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="h4 mb-0"><i class="bi bi-pencil-square"></i> Entry</h3>
                    </div>
                    <div class="row card-body">

                <div class="col-lg-6">
                    <div class="mt-2 col-12">                        
                        <label>GR No.:</label>
                        <input type="text" id="txtgrtrx_no" name="txtgrtrx_no" class="form-control form-control-sm" value="<?=$txtgrtrx_no;?>" readonly />
                        <input type="hidden" class="form-control form-control-sm input-sm" name="__hmtkn_trxnoid" id="__hmtkn_trxnoid" value="<?= $mmnhd_rid;?>" readonly />
                        <input type="hidden" class="form-control form-control-sm input-sm" name="__hmtkn_ptyp" id="__hmtkn_ptyp" value="<?=$fld_ptyp;?>" readonly />
                    </div>
                    <div class="mt-2 col-12">
                        Company Name:
                        <input type="text" class="form-control form-control-sm input-sm" data-id="" id="fld_Company_gr" name="fld_Company_gr" value="<?=$txtcomp;?>" required/>
                    </div>
                    <div class="mt-2 col-12">
                        Plant Code:
                        <input type="text" class="form-control form-control-sm input-sm active_plnt_id" data-id=""  data-mtkn="<?=$mtkn_active_plnt_id;?>" id="fld_plant" name="fld_plant" value="<?=$txtplant;?>" required/>
                    </div>
                    <div class="mt-2 col-12">
                        Warehouse Code:
                        <input type="text" class="form-control form-control-sm input-sm active_wshe_id" data-id="<?=$mtkn_active_wshe_id;?>" id="fld_wshe" name="fld_wshe" value="<?=$txtwshe;?>" required/>
                    </div>
                    <div class="mt-2 col-12">
                        Warehouse Rack:
                        <input type="text" class="form-control form-control-sm input-sm active_rack_id" data-id="<?=$mtkn_active_rack_id;?>" id="fld_rack" name="fld_rack" value="<?=$txtrack;?>" required/>
                    </div>
                    <div class="mt-2 col-12">
                        Warehouse Bin:
                        <input type="text" class="form-control form-control-sm input-sm active_bin_id" data-id="<?=$mtkn_active_bin_id;?>" id="fld_bin" name="fld_bin" value="<?=$txtbin;?>" required/>
                    </div>
                    <div class=" mt-2 p-4 col-md-12 border rounded">
                        <div class="row ">
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <div class="input-group-text">
                                         <input class="asstd_chk green-cb fs-2" type="checkbox" id="asstd_chk" style="scale: 1.3" name="asstd_chk" <?=$ischecked;?> >
                                    </div>
                                    <input type="text" placeholder="Assorted?" class="form-control form-control-sm">
                                </div>
                             
                                
                            </div>
                            <div class=" col-lg-2">
                                # of Line Item:
                            </div>
                            <div class=" col-lg-2">
                                <input type="text" class="form-control form-control-sm input-sm " id="line_item" name="line_item" value="" onkeypress="return __meNumbersOnly(event)"/>
                            </div>
                              <div class=" col-lg-2">
                            Assorted Code:
                        </div>
                            <div class=" col-lg-3">
                                <input type="text" class="form-control form-control-sm input-sm" id="asst_itemc" name="asst_itemc" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12" id="msg_show"></div>

                </div>
                <div class="col-lg-6">
                    <div class="mt-2 col-12">
                        Ref No:
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <a style="margin: 0px 0px !important; color:white;" title="PICK FROM " class="rounded-0 rounded-start btn btn-success btn-sm input-group-text fld_refno " id="btn_refno" >PICK FROM</a>
                            </div>
                            <input type="text" class="form-control form-control-sm input-sm" data-id="" id="fld_refno" name="fld_refno" value="<?=$txtrefno;?>" />
                        </div>

                    </div>

                    <div class="mt-2 col-12">
                        Good Receive Type:
                        <?=$mylibzsys->mypopulist_2($agr_type,$txtgr_type,'fld_grtyp','class="form-control form-control-sm" name="txtgr_type"','','');?>

                    </div>
                    <div class="mt-2 col-12">
                        Good Receive Class:
                        <?=$mylibzsys->mypopulist_2($agr_class,$txtgr_class,'fld_grclass','class="form-control form-control-sm" name="txtgr_class"','','');?>
                    </div>
                    <div class="mt-2 col-12">
                        GR Date:
                        <input type="text" class="form-control form-control-sm input-sm" name="fld_grdate" id="fld_grdate" value="<?=$txtgrdate;?>" readonly/>
                    </div>
                    <div class="mt-2 col-12">
                        Remarks:
                        <textarea type="text" class="form-control form-control-sm input-sm" name="fld_rems" id="fld_rems"><?=$txtrems;?></textarea>
                        <!-- <input type="text" class="form-control form-control-sm input-sm" name="fld_rems" id="fld_rems" value="<?=$txtrems;?>" required/> -->
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-4">
                            Total Actual Pcs :
                            <input type="text" class="form-control form-control-sm input-sm" name="fld_subtqty" id="fld_subtqty" value="<?=$txtsubtqty;?>" required readonly/>
                        </div>
                        <div  class="col-lg-4">
                            Total Cost:
                            <input type="text" class="form-control form-control-sm input-sm" name="fld_subtcost" id="fld_subtcost" value="<?=$txtsubtcost;?>" required readonly/>
                        </div>
                        <div class="col-lg-4">
                            Total SRP:
                            <input type="text" class="form-control form-control-sm input-sm" name="fld_subtamt" id="fld_subtamt" value="<?=$txtsubtamt;?>" required readonly/>
                        </div>
                    </div>

                </div>
      
            <div class="col-sm-12 mt-4">
                <div class="table-responsive">
                    <table id="tbl_grdata" class="table table-striped table-bordered table-condensed" style="font-size: 0.8rem !important;">
                        <thead class="text-dgreen text-center">
                            <th> </th>
                            <th width="20px" class="text-center">
                                <button id="btn_additms" type="button" class="btn btn-primary btn-xs" onclick="javascript:my_add_line_item();" >
                                    <i class="bi bi-plus"></i>
                                </button>
                            </th>
                            <th>Box Itemcode</th>
                            <th>Item Description</th>
                            <th>Packaging</th>
                            <th>Unit Cost</th>
                            <th>Total Cost</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Box Quantity</th>
                            <th>Particulars / Remarks</th>
                            <th>Assorted Items</th>
                            <th>Item Quantity Goods</th>
                            <th>Item Convf Goods</th>
                            <th  id="POB" <?=$str_convf_disp;?>>Item Convf(Pout)</th>
                            <th  id="POB2" <?=$str_convf_disp;?>>Item Quantity Dmg</th>
                            <th id="POB2" <?=$str_convf_disp;?>>Lacking Qty</th>

                        </thead>
                        <tbody id="contentArea">
                            <?php

                            $str = "
                            SELECT
                            a.*,
                            SHA2(CONCAT(a.`recid`,'{$mpw_tkn}'),384) mtkn_mndttr,
                            SHA2(CONCAT(b.`recid`,'{$mpw_tkn}'),384) mtkn_artmtr,
                            IFNULL(b.`ART_CODE`,a.`mat_code`) ART_CODE, 
                            IFNULL(b.`ART_DESC`,'') ART_DESC,
                            IFNULL(b.`ART_SKU`,'') ART_SKU,
                            IFNULL(b.`ART_UCOST`,'') ART_UCOST,
                            IFNULL(b.`ART_UPRICE`,'') ART_UPRICE
                            FROM
                            {$db_erp}.`trx_wshe_gr_dt` a
                            LEFT JOIN 
                            {$db_erp}.`mst_article` b
                            ON
                            a.`mat_rid` = b.`recid`
                            WHERE
                            sha2(concat(a.`grhd_rid`,'{$mpw_tkn}'),384) = '{$mmnhd_rid}'
                            ORDER BY 
                            a.`recid`
                            ";

                            $qdt = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                            foreach($qdt->getResultArray() as $rdt) {
                                $mitemcode_ = $rdt['ART_CODE'];
                                $nmnrecs++;
                                ?>
                                <track kind="" src="">
                                    <tr>
                                        <td><?=$nmnrecs;?></td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button" onclick="javascript:__mn_items_drecs('<?=$rdt['mtkn_mndttr'];?>');" <?=$dis3;?>>
                                                <i class="bi bi-x"></i>
                                            </button>
                                            <input type="hidden" id="mitemrid_<?=$nmnrecs;?>" value="<?=$rdt['mtkn_artmtr'];?>"/> <!--id-->
                                            <input type="hidden" id="mid_<?=$nmnrecs;?>" value="<?=$rdt['mtkn_mndttr'];?>"/>
                                            <input type="hidden" id="miitemrid_<?=$nmnrecs;?>" value="<?=$rdt['imat_rid'];?>"/>
                                        </td>
                                        <td><input type="text" id="fld_mitemcode_<?=$nmnrecs;?>" size="20" class="mitemcode form-control form-control-sm" value="<?=$rdt['ART_CODE'];?>" /></td> <!--itemcode-->
                                        <td><input type="text" id="fld_mitemdesc_<?=$nmnrecs;?>" size="40" class="form-control form-control-sm" value="<?=$rdt['ART_DESC'];?>" readonly /></td> <!--item desc-->
                                        <td><input type="text" id="fld_mitempkg_<?=$nmnrecs;?>" size="5" class="form-control form-control-sm" value="<?=$rdt['ART_SKU'];?>" readonly /></td> <!--packaging-->
                                        <td><input type="text" id="fld_ucost_<?=$nmnrecs;?>" size="15" class="form-control form-control-sm" value="<?=$rdt['ucost'];?>" /></td> <!--ucost-->
                                        <td><input type="text" id="fld_mitemtcost_<?=$nmnrecs;?>" size="15"  class="form-control form-control-sm" value="<?=$rdt['tcost'];?>" readonly required/></td> <!--tcost-->
                                        <td><input type="text" id="fld_srp_<?=$nmnrecs;?>" size="15" class="form-control form-control-sm" value="<?=$rdt['uprice'];?>"/></td> <!--srp-->
                                        <td><input type="text" id="fld_mitemtamt_<?=$nmnrecs;?>" size="15" class="form-control form-control-sm" value="<?=$rdt['tamt'];?>" readonly /></td> <!--tamt-->
                                        <td><input type="text" id="fld_mitemqty_<?=$nmnrecs;?>" size="15" class="form-control form-control-sm" value="<?=$rdt['qty'];?>" onmouseover="javascript:__tamt_compute_totals();" onmouseout="javascript:__tamt_compute_totals();" onclick="javascript:__tamt_compute_totals();" onblur="javascript:__tamt_compute_totals();" required/></td> <!--qty rcvd-->
                                        <!-- <td></td> --> <!--reason-->
                                        <td><input type="text" id="fld_remks_<?=$nmnrecs;?>" size="20" class="form-control form-control-sm" value="<?=$rdt['nremarks'];?>" /></td> <!--remks-->
                                        <td><input type="text" id="fld_iitemcode_<?=$nmnrecs;?>" size="20" class="miitemcode form-control form-control-sm " value="<?=$rdt['imat_code'];?>" required/></td> <!--fld_iitemcode-->
                                        <td><input type="text" id="fld_iqty_<?=$nmnrecs;?>" size="20" class="form-control form-control-sm" value="<?=$rdt['imat_qty'];?>" required/></td> <!--fld_iqty-->
                                        <td><input type="text" id="fld_iconvf_<?=$nmnrecs;?>" size="15" class="form-control form-control-sm" value="<?=$rdt['imat_convf'];?>" onmouseover="javascript:__tamt_compute_totals();" onmouseout="javascript:__tamt_compute_totals();" onclick="javascript:__tamt_compute_totals();" onblur="javascript:__tamt_compute_totals();" required/></td> <!--fld_iconvf-->
                                        <td class="POB1" <?=$str_convf_disp;?>><input type="text" id="fld_actconvf_<?=$nmnrecs;?>" size="20" value="<?=$rdt['amat_convf'];?>"  class="form-control form-control-sm" readonly/></td> <!--OLT-->
                                        <td class="POB3" <?=$str_convf_disp;?>><input type="text" id="fld_actdmg_<?=$nmnrecs;?>" size="15" value="<?=$rdt['amat_dmg'];?>"  class="form-control form-control-sm" /></td> <!--OLT -->
                                        <td class="POB3" <?=$str_convf_disp;?>><input type="text" id="fld_actlck_<?=$nmnrecs;?>" size="15" value="<?=$rdt['amat_lck'];?>"  class="form-control form-control-sm" readonly/></td> <!--OLT -->
                                    </tr>
                                    <?php 
                                } //end foreach 
                                $qdt->freeResult();
                                ?>
                                <tr style="display:none;">
                                    <td></td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs nullvaluethis" onclick="javascript:confirmalert(this);">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        <input type="hidden" value=""/>
                                        <input type="hidden" value=""/>
                                        <input type="hidden" value=""/>
                                    </td>
                                    <td><input type="text"  class="mitemcode form-control form-control-sm" value="" /></td> <!--itemcode-->
                                    <td><input type="text"  value="" class="form-control form-control-sm" readonly /></td> <!--item desc-->
                                    <td><input type="text"  value="" class="form-control form-control-sm" readonly /></td> <!--packaging-->
                                    <td><input type="text"  value="" class="form-control form-control-sm" /></td> <!--ucost-->
                                    <td><input type="text"  value="" class="form-control form-control-sm" readonly /></td> <!--tcost-->
                                    <td><input type="text"  value="" class="form-control form-control-sm"/></td> <!--srp-->
                                    <td><input type="text"  value="" class="form-control form-control-sm" readonly /></td> <!--tamt-->
                                    <td><input type="text"  value="" class="form-control form-control-sm" onkeypress="return __meNumbersOnly(event)" onmouseover="javascript:__tamt_compute_totals();" onmouseout="javascript:__tamt_compute_totals();" onclick="javascript:__tamt_compute_totals();" onblur="javascript:__tamt_compute_totals();" required/></td> <!--qty-->
                                    <!-- <td></td>  --><!--reason-->
                                    <td><input type="text" class="form-control form-control-sm" value="" /></td> <!--remks-->
                                    <td><input type="text" class="miitemcode form-control form-control-sm"  value="" required/></td> <!--fld_iitemcode-->
                                    <td><input type="text" class="form-control form-control-sm"  value="" required/></td> <!--fld_iqty-->
                                    <td><input type="text" class="form-control form-control-sm"  value="" onkeypress="return __meNumbersOnly(event)" onmouseover="javascript:__tamt_compute_totals();" onmouseout="javascript:__tamt_compute_totals();" onclick="javascript:__tamt_compute_totals();" onblur="javascript:__tamt_compute_totals();" required/></td> <!--fld_iconvf-->
                                    <td class="POB1" <?=$str_convf_disp;?>><input type="text" class="form-control form-control-sm" value="" readonly/></td> <!--fld_aconvf-->
                                    <td class="POB3" <?=$str_convf_disp;?>><input type="text" class="form-control form-control-sm" value="" /></td> <!--fld_aconvf-->
                                    <td class="POB3" <?=$str_convf_disp;?>><input type="text" class="form-control form-control-sm" value="" readonly/></td> <!--fld_aconvf-->
                                </tr>                
                                </tbody>
                                </table>
                                </div><!-- end table-responsive -->
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button <?=$str_appr;?> class="btn btn-success btn-sm" id="mbtn_mn_Save" type="submit">Save</button>&nbsp;
                                        <button class="btn btn-success btn-sm" id="mbtn_mn_NTRX" type="button">New Trx</button>&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>

<!--  <div class="container row" style="padding: 20px;">  
</div>
<input type="button" class="btn btn-md" value="Clear" onClick="history.go(0)">
tton class="btn btn-success btn-sm" id="mbtn_gr_Save" type="submit">Save</button>
</div>
</div> -->

</div>
<div class="accordion" id="accordionExample">
<div class="col-lg-12">
<div class="card">      
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Records</h3>
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body">
         <div id="myoutdrecs"> </div>
      </div>
    </div>
  </div>
</div>
</div>

<div class="col-lg-12">
<div class="card">   
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        <h3 class="h4 mb-0"> <i class="bi bi-layer-forward"></i> Workflow</h3>
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <div id="my-workflow-vw"></div>
      </div>
    </div>
  </div>
</div>
</div>

<div class="col-lg-12">
    <div class="card">   
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
               <h3 class="h4 mb-0"> <i class="bi bi-journals"></i> Logfile</h3>
           </button>
       </h2>
       <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
        <div class="accordion-body">
           <div id="my-logfile-vw"></div>
       </div>
   </div>
</div>
</div>
</div>

<div class="col-lg-12">
    <div class="card">   
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
              <h3 class="h4 mb-0"> <i class="bi bi-list-check"></i> Summary</h3>
          </button>
      </h2>
      <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
        <div class="accordion-body">
           <div id="my-summarry-vw"></div>
       </div>
   </div>
</div>
</div>
</div>

<div class="col-lg-12">
  <div class="card">   
      <div class="accordion-item">
          <h2 class="accordion-header" id="headingFive">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                  <h3 class="h4 mb-0"> <i class="bi bi-box-seam"></i> Box Barcode</h3>
              </button>
          </h2>
          <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                  <div id="my-boxbarcode-gr"></div>
              </div>
          </div>
      </div>
  </div>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- end row -->
<?php
  echo $mylibzsys->memypreloader01('mepreloaderme');
  echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
  echo $mylibzsys->memsgbox1('memsgtestent_success','<i class="bi bi-check-circle"></i> System Alert','...','bg-psuccess');
  echo $mylibzsys->memsgbox2('memsgtestent_gen','<i class="bi bi-receipt"></i> Generate Shipping document','...','bg-psuccess','modal-xl');
?>  
</main>

<script type="text/javascript">

function add(accumulator, a) {
  return accumulator + a;
}

     gr_ent_recs();
    function gr_ent_recs(mtkn_whse){ 
        var ajaxRequest;

        ajaxRequest = jQuery.ajax({
            url: "<?=site_url();?>gr-recs",
            type: "post",
            data: {
                mtkn: ''
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


      ///LOGFILE

      mywg_gr_log();
      function mywg_gr_log(mtkn_arttr) { 
          var ajaxRequest;

          ajaxRequest = jQuery.ajax({
              url: "<?=site_url();?>gr-logfile",
              type: "post",
              data: {
                  mtkn_arttr: mtkn_arttr
              }
          });

  // Deal with the results of the above ajax call
          ajaxRequest.done(function(response, textStatus, jqXHR) {
              jQuery('#my-logfile-vw').html(response);
            __mysys_apps.mepreloader('mepreloaderme',false);
  // and do it again
  //setTimeout(get_if_stats, 5000);
          });
      };


      ///Summary
      mywg_gr_summ();
      function mywg_gr_summ(mtkn_arttr) { 
          var ajaxRequest;

          ajaxRequest = jQuery.ajax({
              url: "<?=site_url();?>gr-summary",
              type: "post",
              data: {
                  mtkn_arttr: mtkn_arttr
              }
          });

  // Deal with the results of the above ajax call
          ajaxRequest.done(function(response, textStatus, jqXHR) {
              jQuery('#my-summarry-vw').html(response);
  // and do it again
  //setTimeout(get_if_stats, 5000);
          });
      };

      ///Summary
      mywg_gr_barcde();
      function mywg_gr_barcde(mtkn_arttr) { 
          var ajaxRequest;

          ajaxRequest = jQuery.ajax({
              url: "<?=site_url();?>gr-boxbarcode",
              type: "post",
              data: {
                  mtkn_arttr: mtkn_arttr
              }
          });

  // Deal with the results of the above ajax call
          ajaxRequest.done(function(response, textStatus, jqXHR) {
              jQuery('#my-boxbarcode-gr').html(response);
  // and do it again
  //setTimeout(get_if_stats, 5000);
          });
      };

    mywg_gr_wf();
    function mywg_gr_wf(mtkn_arttr) { 
        var ajaxRequest;

        ajaxRequest = jQuery.ajax({
            url: "<?=site_url();?>gr-workflow",
            type: "post",
            data: {
                mtkn_arttr: mtkn_arttr
            }
        });

// Deal with the results of the above ajax call
        ajaxRequest.done(function(response, textStatus, jqXHR) {
            jQuery('#my-workflow-vw').html(response);
// and do it again
//setTimeout(get_if_stats, 5000);
        });
    };


//<![CDATA[
    function __meNumbersOnly(e) {
        var code = (e.which) ? e.which : e.keyCode;
//if (code > 31 && (code < 47 || code > 57)) {
        if(!((code > 47 && code < 58) || code == 46)) { 
            e.preventDefault();
        }
} //end __meNumbersOnly
function confirmalert(smuid){
    var userselection = confirm("Are you sure you want to remove this item permanently?");
    if (userselection == true){
        alert("Item deleted!");
        nullvalue(smuid);
    }
    else{
        alert("Item is not deleted!");
    }    
}
function nullvalue(muid) {
///console.log(muid);
    jQuery(muid).parent().parent().remove();
    $( '#tbl_grdata tr').each(function(i) { 
        $(this).find('td').eq(0).html(i);
    });
    __tamt_compute_totals();

}

$('.form_datetime').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: false,
    autoclose: true,
    format: 'mm/dd/yyyy'
});




$('.me-date-me').datepicker({ 
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true 
});
function __autof(){
    var xobjArtRem= jQuery(clonedRow).find('input[type=text]').eq(8).attr('id');
    var txtxobjArtRem = jQuery('#' + xobjArtItem);
    txtxobjArtRem.bind("enterKey",function(e){
        alert("Enter");
    });
    txtxobjArtRem.keyup(function(e){
        if(e.keyCode == 13)
        {
            $(this).trigger("enterKey");
        }
    });

}
//NEW TRX
$('#mbtn_mn_NTRX').click(function() { 
    var userselection = confirm("Are you sure you want to new transaction?");
    if (userselection == true){
        window.location = '<?=site_url();?>good-receive';
    }
    else{
          __mysys_apps.mepreloader('mepreloaderme',false);
        return false;
    } 
});

function __mn_items_drecs(mtkn_mndt_rid) { 

    try { 
        $('html,body').scrollTop(0);
          __mysys_apps.mepreloader('mepreloaderme',true);

        var mparam = {
            mtkn_mndt_rid: mtkn_mndt_rid

        }; 

$.ajax({ // default declaration of ajax parameters
    type: "POST",
    url: '<?=site_url();?>mytrx_gr/gr_confdrecs',
    context: document.body,
    data: eval(mparam),
    global: false,
    cache: false,

success: function(data)  { //display html using divID
      __mysys_apps.mepreloader('mepreloaderme',false);
    jQuery('#myMod_drdtdrecs_Bod').html(data);
    jQuery('#myMod_drdtdrecs').modal('show');


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


function my_add_line_item(itemcode,itemdesc,sku,ucost,uprice,__rid,tqty,_particulars,grtyp_tag,fld_ptyp) { 
    try {
        var rowCount = jQuery('#tbl_grdata tr').length;
        var mid = __mysys_apps.__do_makeid(7) + (rowCount + 1);
        var clonedRow = jQuery('#tbl_grdata tr:eq(' + (rowCount - 1) + ')').clone(); 
        jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id','mitemrid_' + mid).val(__rid);
        jQuery(clonedRow).find('input[type=hidden]').eq(1).attr('id','mid_' + mid);

        jQuery(clonedRow).find('input[type=text]').eq(0).attr('id','fld_mitemcode' + mid).val(itemcode);
        jQuery(clonedRow).find('input[type=text]').eq(1).attr('id','fld_mitemdesc' + mid).val(itemdesc);
        jQuery(clonedRow).find('input[type=text]').eq(2).attr('id','fld_mitempkg' + mid).val(sku);
        jQuery(clonedRow).find('input[type=text]').eq(3).attr('id','fld_ucost' + mid).val(ucost);
        jQuery(clonedRow).find('input[type=text]').eq(4).attr('id','fld_mitemtcost' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(5).attr('id','fld_srp' + mid).val(uprice);
        jQuery(clonedRow).find('input[type=text]').eq(6).attr('id','fld_mitemtamt' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(10).attr('id','fld_iqty' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(11).attr('id','fld_iconvf' + mid);
//jQuery(clonedRow).find('input[type=text]').eq(7).attr('id','fld_mitemqtycorr' + mid);
        jQuery(clonedRow).find('input[type=text]').eq(8).attr('id','fld_remks' + mid).val(_particulars);

//console.log(grtyp_tag);
// if(grtyp_tag == 3){
//     $('#POB').css('display', (grtyp_tag == 3) ? 'flex' : 'none');
//     $('#POB2').css('display', (grtyp_tag == 3) ? 'flex' : 'none');
//     $('.POB1').css('display', (grtyp_tag == 3) ? 'flex' : 'none');
//     $('.POB3').css('display', (grtyp_tag == 3) ? 'flex' : 'none');

// }
// if(grtyp_tag != 3){
//     $('#POB').css('display', (grtyp_tag != 3) ? 'none' : 'flex');
//     $('#POB2').css('display', (grtyp_tag != 3) ? 'none' : 'flex');
//     $('.POB1').css('display', (grtyp_tag != 3) ? 'none' : 'flex');
//     $('.POB3').css('display', (grtyp_tag != 3) ? 'none' : 'flex');

// }

        var $firstc = $('#tbl_grdata').find('thead').find('th:nth-child(15)');
        var $secondc = $('#tbl_grdata').find('thead').find('th:nth-child(16)');
        var $thirdc = $('#tbl_grdata').find('thead').find('th:nth-child(17)');
        var $first = $('#tbl_grdata').find('tr').find('td:nth-child(15)');
        var $second = $('#tbl_grdata').find('tr').find('td:nth-child(16)');
        var $third = $('#tbl_grdata').find('tr').find('td:nth-child(17)');

        var hc3 = $('#tbl_grdata').find('thead').find('th:nth-child(3)');
        var hc4 = $('#tbl_grdata').find('thead').find('th:nth-child(4)');
        var hc5 = $('#tbl_grdata').find('thead').find('th:nth-child(5)');
        var hc6 = $('#tbl_grdata').find('thead').find('th:nth-child(6)');
        var hc7 = $('#tbl_grdata').find('thead').find('th:nth-child(7)');
        var hc8 = $('#tbl_grdata').find('thead').find('th:nth-child(8)');
        var hc9 = $('#tbl_grdata').find('thead').find('th:nth-child(9)');
        var hc10 = $('#tbl_grdata').find('thead').find('th:nth-child(10)');
        var hc12 = $('#tbl_grdata').find('thead').find('th:nth-child(12)');
        var hr3 = $('#tbl_grdata').find('tr').find('td:nth-child(3)');
        var hr4 = $('#tbl_grdata').find('tr').find('td:nth-child(4)');
        var hr5 = $('#tbl_grdata').find('tr').find('td:nth-child(5)');
        var hr6 = $('#tbl_grdata').find('tr').find('td:nth-child(6)');
        var hr7 = $('#tbl_grdata').find('tr').find('td:nth-child(7)');
        var hr8 = $('#tbl_grdata').find('tr').find('td:nth-child(8)');
        var hr9 = $('#tbl_grdata').find('tr').find('td:nth-child(9)');
        var hr10 = $('#tbl_grdata').find('tr').find('td:nth-child(10)');
        var hr12 = $('#tbl_grdata').find('tr').find('td:nth-child(12)');

        $firstc.hide();
        $secondc.hide();
        $thirdc.hide();
        $first.hide();
        $second.hide();
        $third.hide();


//FOR PULLOUT ONLY
        if(grtyp_tag == 3){
            var bqty = 1;
            if(fld_ptyp == 'N' ){
                hc3.hide();
                hc4.hide();
                hc5.hide();
                hc6.hide();
                hc7.hide();
                hc8.hide();
                hc9.hide();
                hc10.hide();
                hc12.hide();

                hr3.hide();
                hr4.hide();
                hr5.hide();
                hr6.hide();
                hr7.hide();
                hr8.hide();
                hr9.hide();
                hr10.hide();
                hr12.hide();
            }
            else{
                hc3.show();
                hc4.show();
                hc5.show();
                hc6.show();
                hc7.show();
                hc8.show();
                hc9.show();
                hc10.show();
                hc12.show();

                hr3.show();
                hr4.show();
                hr5.show();
                hr6.show();
                hr7.show();
                hr8.show();
                hr9.show();
                hr10.show();
                hr12.show();
            }
            $firstc.show();
            $secondc.show();
            $thirdc.show();
            $first.show();
            $second.show();
            $third.show();
            jQuery(clonedRow).find('input[type=text]').eq(7).attr('id','fld_mitemqty' + mid).val(bqty);
            jQuery(clonedRow).find('input[type=text]').eq(12).attr('id','fld_actconvf' + mid).val(tqty);
            jQuery(clonedRow).find('input[type=text]').eq(13).attr('id','fld_actdmg' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(14).attr('id','fld_actlck' + mid);
            jQuery(clonedRow).find('input[type=hidden]').eq(2).attr('id','miitemrid_' + mid).val(__rid);
            jQuery(clonedRow).find('input[type=text]').eq(9).attr('id','fld_iitemcode' + mid).val(itemcode);
        }
        else{
            $firstc.hide();
            $secondc.hide();
            $thirdc.hide();
            $first.hide();
            $second.hide();
            $third.hide();
            jQuery(clonedRow).find('input[type=hidden]').eq(2).attr('id','miitemrid_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(9).attr('id','fld_iitemcode' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(7).attr('id','fld_mitemqty' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(12).attr('id','fld_actconvf' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(13).attr('id','fld_actdmg' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(14).attr('id','fld_actlck' + mid);
        }
//jQuery(clonedRow).find('select[name=txtgr_rson]').eq(0).attr('id','txtgr_rson' + mid);
//jQuery(clonedRow).find('input[type=text]').eq(9).attr('id','fld_mitemolt' + mid);


        jQuery('#tbl_grdata tr').eq(rowCount - 1).before(clonedRow);
        jQuery(clonedRow).css({'display':''});


        $('.me-date-me').datepicker({ 
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });
        __my_item_lookup();
        __my_item_lookup_i();
        __tamt_compute_totals();
        var xobjArtItem= jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
        jQuery('#' + xobjArtItem).focus();
        $( '#tbl_grdata tr').each(function(i) { 
            $(this).find('td').eq(0).html(i);
        });
    } catch(err) { 
        var mtxt = 'There was an error on this page.\\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\\nClick OK to continue.';
        alert(mtxt);
        return false;
}  //end try 
}

function deleteRow(cobj,mruid) {
    jQuery(cobj).parent().parent().remove();
}

jQuery('#fld_Company_gr')
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
    source: '<?= site_url(); ?>get-companies/',
    focus: function() {
// prevent value inserted on focus
        return false;
    },
    search: function(oEvent, oUi) {
        var sValue = jQuery(oEvent.target).val();
//jQuery(oEvent.target).val('&mcocd=1' + sValue);
//alert(sValue);
    },
    select: function( event, ui ) {
        var terms = ui.item.value;
        var apv_id = ui.item._compcode;
        this.value = ui.item.value;
        var comp_id = ui.item.mtkn_recid;
//console.log(comp_id);
//jQuery('#apv_id').val('APV-'+ui.item._compcode+'-'+ui.item.cseqn);
//jQuery('#comp_code_').val(ui.item._compcode);
        jQuery('#fld_Company_gr').val(terms);
        jQuery('#fld_Company_gr').attr("data-id",comp_id);


        return false;
    }
})
.click(function() {
//jQuery(this).keydown();
    var terms = this.value;

// var terms=('')+'xox'+$('#fld_Company_gr').val()
//jQuery(this).autocomplete('search', '');
    jQuery(this).autocomplete('search', jQuery.trim(terms));


});



function __my_item_lookup() {  
    jQuery('.mitemcode' ) 
// don't navigate away from the field on tab when selecting an item
    .bind( 'keypress', function( event ) {
        if (event.keyCode === jQuery.ui.keyCode.ENTER && jQuery( this ).data( 'autocomplete-ui' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.ENTER ) {
            event.preventDefault();
        }

    })
    .autocomplete({
        minLength: 0,
        autoFocus: true,
        source: '<?= site_url(); ?>get-article',
        search: function(oEvent, oUi) { 
            var sValue = jQuery(oEvent.target).val();

//jQuery(oEvent.target).val('&mcocd=1' + sValue);
//alert(sValue);
        },
        select: function( event, ui ) {
            var terms = ui.item.value;

            jQuery(this).attr('alt', jQuery.trim(ui.item.ART_CODE));
            jQuery(this).attr('title', jQuery.trim(ui.item.ART_CODE));

            this.value = ui.item.ART_CODE;

            var clonedRow = jQuery(this).parent().parent().clone();
            var indexRow = jQuery(this).parent().parent().index();
            var xobjArtMDescId = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');
            var xobjArtMUOM = jQuery(clonedRow).find('input[type=text]').eq(2).attr('id');
            var xobjArtMUcost= jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');
            var xobjArtMSRP = jQuery(clonedRow).find('input[type=text]').eq(5).attr('id');
            var xobjArtMQty = jQuery(clonedRow).find('input[type=text]').eq(7).attr('id');
            var xobjArtMCode_i = jQuery(clonedRow).find('input[type=text]').eq(9).attr('id');

            var xobjArtMConvf_i = jQuery(clonedRow).find('input[type=text]').eq(11).attr('id');

            var xobjArtMrid = jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id');
            var xobjArtMrid_i = jQuery(clonedRow).find('input[type=hidden]').eq(2).attr('id');

            var xobjArtMqty_i = jQuery(clonedRow).find('input[type=text]').eq(10).attr('id');

            if($('input.asstd_chk').prop("checked") != true){
                jQuery('#' + xobjArtMqty_i).val(ui.item.ART_NCONVF); 
            }
// alert(xobjArtMSRP);
//alert('<?= site_url(); ?>public/assets/img/thumbnail/items/' + ui.item.ART_IMG);
            jQuery('#' + xobjArtMDescId).val(ui.item.ART_DESC);
            jQuery('#' + xobjArtMUOM).val(ui.item.ART_SKU);
            jQuery('#' + xobjArtMUcost).val(ui.item.ART_UCOST);
            jQuery('#' + xobjArtMSRP).val(ui.item.ART_UPRICE);
            jQuery('#' + xobjArtMrid).val(ui.item.mtkn_rid);

            jQuery('#' + xobjArtMCode_i).val(ui.item.ART_CODE);
            jQuery('#' + xobjArtMrid_i).val(ui.item.ART_MATRID);

            jQuery('#' + xobjArtMConvf_i).val(ui.item.ART_NCONVF);


//jQuery('#' + xobjArtMCode_i).prop('disabled', true);
            jQuery('#' + xobjArtMQty).focus();

            var mitemcode_ = ui.item.ART_CODE;
//__isduplicate_mitemcode(this);
// console.log(ui.item.ART_NCBM);
//jQuery('#' + xobjArtMImgId).attr('src','<?= site_url(); ?>uploads/artm/' + ui.item.ART_IMG);

            return false;
        }
    })
    .click(function() { 
//jQuery(this).keydown(); 
        var terms = this.value.split('=>');
//jQuery(this).autocomplete('search', '');
        jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
    });        
}   


function __tamt_compute_totals() { 
    try { 
        var rowCount1 = jQuery('#tbl_grdata tr').length - 1;
        var adata1 = [];
        var adata2 = [];
        var mdata = '';
        var ninc = 0;
        var nTAmount = 0;
        var nTAmountCost = 0;
        var nTQty = 0;
        var nTQtyItems = 0;
        var fld_grtyp = jQuery('#fld_grtyp').val();
        const items = [];
        for(aa = 1; aa < rowCount1; aa++) { 
            var clonedRow = jQuery('#tbl_grdata tr:eq(' + aa + ')').clone(); 
            var mdat1 = jQuery(clonedRow).find('input[type=text]').eq(0).val();
            var mdat2 = jQuery(clonedRow).find('input[type=text]').eq(1).val();
            var mdat3 = jQuery(clonedRow).find('input[type=text]').eq(2).val();//uom/pkg
            var mdat4 = jQuery(clonedRow).find('input[type=text]').eq(3).val();//ucost
            var mdat5 = jQuery(clonedRow).find('input[type=text]').eq(4).val();//tcost
            var mdat6 = jQuery(clonedRow).find('input[type=text]').eq(5).val();//srp
            var mdat7 = jQuery(clonedRow).find('input[type=text]').eq(6).val();//tamt
            var mdat8 = jQuery(clonedRow).find('input[type=text]').eq(7).val();//qty rcvd
            //var mdat8 = jQuery(clonedRow).find('input[type=text]').eq(7).val();//qty corrected
            var mdat9 = jQuery(clonedRow).find('input[type=text]').eq(8).val();//rems

            var mdat9x = jQuery(clonedRow).find('input[type=text]').eq(9).val();
            var mdat10 = jQuery(clonedRow).find('input[type=text]').eq(10).val();
            var mdat11 = jQuery(clonedRow).find('input[type=text]').eq(11).val();

            //var mdat10 = jQuery(clonedRow).find('input[type=text]').eq(9).val();//OLT
            var xTAmntCostId = jQuery(clonedRow).find('input[type=text]').eq(4).attr('id');
            var xTAmntCostIdh = jQuery(clonedRow).find('input[type=hidden]').eq(4).attr('id');

            var xTQtyId = jQuery(clonedRow).find('input[type=text]').eq(7).attr('id');
            var xTQtyIdh = jQuery(clonedRow).find('input[type=hidden]').eq(7).attr('id');

            var xTQtyId_i = jQuery(clonedRow).find('input[type=text]').eq(10).attr('id');
            var xTQtyIdh_i = jQuery(clonedRow).find('input[type=hidden]').eq(10).attr('id');

            var xTAmntId = jQuery(clonedRow).find('input[type=text]').eq(6).attr('id');
            var xTAmntIdh = jQuery(clonedRow).find('input[type=hidden]').eq(6).attr('id');


            //var xOLTId = jQuery(clonedRow).find('input[type=text]').eq(9).attr('id');
            //var xOLTIdh = jQuery(clonedRow).find('input[type=hidden]').eq(9).attr('id');
            if(fld_grtyp == 3){
            var mdat12 = jQuery(clonedRow).find('input[type=text]').eq(12).val();//actual cnvf
            var mdat13 = jQuery(clonedRow).find('input[type=text]').eq(13).val();//actual dmg qty
            var mdat14 = jQuery(clonedRow).find('input[type=text]').eq(14).val();//Lacking qty

            // var xTDmgQtyId_i = jQuery(clonedRow).find('input[type=text]').eq(13).attr('id');
            // var xTDmgQtyIdh_i = jQuery(clonedRow).find('input[type=hidden]').eq(13).attr('id');

            var xTLckQtyId_i = jQuery(clonedRow).find('input[type=text]').eq(14).attr('id');
            var xTLckQtyIdh_i = jQuery(clonedRow).find('input[type=hidden]').eq(14).attr('id');
            //
            var naqty_i = 0;
            if($.trim(mdat12) == '') { //qty rcvd
                naqty_i = 0;
            } else { 

                naqty_i = mdat12;
            }

            var nadmgqty_i = 0;
            if($.trim(mdat13) == '') { //qty rcvd
                nadmgqty_i = 0;
            } else { 

                nadmgqty_i = mdat13;
            }

            var nalckqty_i = 0;
            if($.trim(mdat14) == '') { //qty rcvd
                nalckqty_i = 0;
            } else { 

                nalckqty_i = mdat14;
            }

            // if($.trim(xTDmgQtyId_i) == '') { 
            //     nadmgqty_i = 0;
            // } else { 
            //     nadmgqty_i = xTDmgQtyId_i;
            // }

            if($.trim(xTLckQtyId_i) == '') { 
                nlckqty_i = 0;
            } 
            else { 
                nlckqty_i = xTLckQtyId_i;
            }
            var naqty_i = parseFloat(naqty_i);
            var nadmgqty_i = parseFloat(nadmgqty_i);
            var nlckqty_i = parseFloat(nlckqty_i);
            //TOTAL QTY DMG
            // if(!isNaN(nadmgqty_i) || nadmgqty_i > 0) { 
            //     $('#' + xTDmgQtyId_i).val(nadmgqty_i.toFixed(2));
            //    // console.log(xTAmntId);
            // }
            }
            var nqty = 0;
            var nqty_i = 0;
            var nqtyc = 0;
            var nprice = 0;
            if($.trim(mdat3) == '') { //uom/pkg
                nuom = "BOX";
            } else { 
                nuom = mdat3;
            }
            if($.trim(mdat4) == '') { //ucost
                ncost = 0;
            } else { 

                ncost = mdat4;
            }
            if($.trim(mdat6) == '') { //srp
                nsrp = 0;
            } else { 

                nsrp = mdat6;
            }

            if($.trim(mdat8) == '') { //qty rcvd
                nqty = 0;
            } else { 

                nqty = mdat8;
            }
            if($.trim(mdat10) == '') { //qty rcvd
                nqty_i = 0;
            } else { 

                nqty_i = mdat10;
            }
            if($.trim(xTAmntCostId) == '') { 
                nucost = 0;
            } else { 
                nucost = xTAmntCostId;
            }


    if(mdat1 != '' && nqty > 0  ){
        const find  = items.findIndex(el=>{if (el.itemcode === mdat1 ) {return true}return false; }); //check if item code already exist 
       
        if(find >= 0 )
        { //update pcs
            items[find]['pcs'].push(parseFloat(nqty_i));
        }
        else
        { //insert new item
          var item = {
              itemcode:mdat1,
              qty:nqty,
              pcs:[(nqty_i > 0 )?parseFloat(nqty_i):1]
          }
          items.push(item);

        }

    }


/*if($.trim(mdat8) == '') { //qty corrected
nqtyc = 0;
} else { 

nqtyc = mdat8;
}*/
if($.trim(xTAmntId) == '') { 
    nprice2 = 0;
} else { 
    nprice2 = xTAmntId;
}
if($.trim(xTQtyId) == '') { 
    nuqty = 0;
} else { 
    nuqty = xTQtyId;
}
if($.trim(xTQtyId_i) == '') { 
    nuqty_i = 0;
} else { 
    nuqty_i = xTQtyId_i;
}
/*if($.trim(xOLTId) == '') { 
nprice2 = "";
} else { 
nprice2 = xOLTId;
}*/

//console.log(mdat7);
var ntqty = parseFloat(nqty);
var ntqtyc = parseFloat(nqtyc);
var ntqty_i = parseFloat(nqty_i);
//TOTAL COST AMT
if($('#' + xTAmntCostIdh).val()==''){
    var ntCost = parseFloat(ncost * ntqty * ntqty_i);
}
else{

    var ntCost = parseFloat(ncost * ntqty * ntqty_i);
}
//TOTAL AMT
if($('#' + xTAmntIdh).val()==''){
    var ntprice = parseFloat(nsrp * ntqty *ntqty_i);
}
else{ 

    var ntprice = parseFloat(nsrp * ntqty * ntqty_i);
}

//TOTAL QTY AMT
if($('#' + xTQtyIdh).val()==''){
    var ntQty = parseFloat(nuqty);
}
else{

    var ntQty = parseFloat(nuqty);
}

//TOTAL QTY AMT
if($('#' + xTQtyIdh_i).val()==''){
    var ntQty_i = parseFloat(nuqty_i);
}
else{

    var ntQty_i = parseFloat(nuqty_i);
}



//TOTAL AMT COST
if(!isNaN(ntCost) || ntCost > 0) { 
    $('#' + xTAmntCostId).val(__mysys_apps.oa_addCommas(ntCost.toFixed(5)));
// console.log(xTAmntId);
}

//TOTAL AMT
if(!isNaN(ntprice) || ntprice > 0) { 
    $('#' + xTAmntId).val(__mysys_apps.oa_addCommas(ntprice.toFixed(2)));
// console.log(xTAmntId);
}
//TOTAL QTY COST
if(!isNaN(ntQty) || ntQty > 0) { 
    $('#' + xTQtyId).val(ntQty.toFixed(2));
// console.log(xTAmntId);
}

//RETURN TO MAPULANG LUPA
if(fld_grtyp == 3){
// console.log(naqty_i);
// console.log(ntqty_i);
// console.log(nadmgqty_i);
    if($('#' + xTLckQtyIdh_i).val()==''){
var ntlckqty = parseFloat((naqty_i - ntqty_i) - nadmgqty_i); //PULLOUT CONVF - USER INPUT CONVF - DAMAGE
}
else{

    var ntlckqty = parseFloat((naqty_i - ntqty_i) - nadmgqty_i);
}

if(!isNaN(ntlckqty) || ntlckqty > 0) { 
    $('#' + xTLckQtyId_i).val(__mysys_apps.oa_addCommas(ntlckqty.toFixed(5)));
// console.log(xTAmntId);
}
}
nTAmount = (nTAmount + ntprice);
nTAmountCost = (nTAmountCost + ntCost);
nTQty = (nTQty + ntqty);

}  //end for 
getTotalPcs(items);
if (!isNaN(nTAmount) || nTAmount < 0){
    $('#fld_subtamt').val(__mysys_apps.oa_addCommas(nTAmount.toFixed(2)));
}
if (!isNaN(nTAmount) || nTAmount < 0){
    $('#fld_subtcost').val(__mysys_apps.oa_addCommas(nTAmountCost.toFixed(5)));
}
// if (!isNaN(nTQty) || nTQty < 0){
//     $('#fld_subtqty').val(__mysys_apps.oa_addCommas(nTQty.toFixed(2)));
// }
//$('#txtgr_totals').val(__mysys_apps.oa_addCommas(nTAmount));
//$('#txtgr_qty').val(__mysys_apps.oa_addCommas(nTQty));
//$('#txtgr_tsku').val(__mysys_apps.oa_addCommas(nTQtyItems));

} catch(err) {
    var mtxt = 'There was an error on this page.\n';
    mtxt += 'Error description: ' + err.message;
    mtxt += '\nClick OK to continue.';
    alert(mtxt);
}  //end try

} //__tamt_compute_totals
__my_item_lookup();
__my_item_lookup_i();
__tamt_compute_totals();
<?php 
if($nmnrecs == 0) { 
    echo "my_add_line_item();";
}
?>


function getTotalPcs(arr){
var gTotal = 0;
for(let i = 0 ;i < arr.length;i++ ){
    var Total = (parseFloat(arr[i].qty) *  parseFloat(arr[i].pcs.reduce(add, 0)));
    gTotal += Total;
 
}

 $('#fld_subtqty').val(__mysys_apps.oa_addCommas(gTotal.toFixed(2)));

}

$('.mydtepicker').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true
});


   $('#mbtn_mn_Save').on('click',function(){

        try { 
        __mysys_apps.mepreloader('mepreloaderme',true);
        var userselection = confirm("Are you sure you want to save?");
        if (userselection == true){
        var fld_txtgrtrx_no = jQuery('#txtgrtrx_no').val();
        var fld_Company_gr  = jQuery('#fld_Company_gr').val();
        var fld_plant       = jQuery('#fld_plant').attr("data-id");
        var fld_wshe        = jQuery('#fld_wshe').attr("data-id");
        var fld_rack        = jQuery('#fld_rack').attr("data-id");
        var fld_bin         = jQuery('#fld_bin').attr("data-id");
        //var fld_dftag     = jQuery('#fld_dftag').val();
        var fld_refno       = jQuery('#fld_refno').val();
        var fld_grdate      = jQuery('#fld_grdate').val();
        var fld_rems        = jQuery('#fld_rems').val();
        var fld_grtyp       = jQuery('#fld_grtyp').val();
        var fld_grclass     = jQuery('#fld_grclass').val();

        var trxno_id        = jQuery('#__hmtkn_trxnoid').val();

        var fld_subtqty     = jQuery('#fld_subtqty').val();
        var fld_subtcost    = jQuery('#fld_subtcost').val();
        var fld_subtamt     = jQuery('#fld_subtamt').val();

        if(fld_grtyp == ''){
        alert("GR Type is required!");
        return false;
        }

        //var tbl_grdata = jQuery('#tbl_grdata');
        var ischck = 'N';
        if($('input.asstd_chk').prop("checked")){
        var ischck = 'Y';
        }
        var rowCount1 = jQuery('#tbl_grdata tr').length - 1;
        var adata1 = [];
        var adata2 = [];
        var mdata = '';
        var mdat ='';
        //console.log(fld_rems); 
        for(aa = 1; aa < rowCount1; aa++) { 
        var clonedRow       = jQuery('#tbl_grdata tr:eq(' + aa + ')').clone(); 
        var fld_mitemcode   = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //icode
        var fld_mitemdesc   = jQuery(clonedRow).find('input[type=text]').eq(1).val(); //desc
        var fld_mitempkg    = jQuery(clonedRow).find('input[type=text]').eq(2).val(); //pkg
        var fld_ucost       = jQuery(clonedRow).find('input[type=text]').eq(3).val(); //ucost
        var fld_mitemtcost  = jQuery(clonedRow).find('input[type=text]').eq(4).val(); //ucost
        var fld_srp         = jQuery(clonedRow).find('input[type=text]').eq(5).val(); //srp
        var fld_mitemtamt   = jQuery(clonedRow).find('input[type=text]').eq(6).val(); //tamt
        var fld_mitemqty    = jQuery(clonedRow).find('input[type=text]').eq(7).val(); //qty r
        //var fld_mitemqtyc = jQuery(clonedRow).find('input[type=text]').eq(7).val(); //qty c
        var fld_remks       = jQuery(clonedRow).find('input[type=text]').eq(8).val(); //rems
        var fld_iitemcode   = jQuery(clonedRow).find('input[type=text]').eq(9).val(); //fld_iitemcode
        var fld_iqty        = jQuery(clonedRow).find('input[type=text]').eq(10).val(); //fld_iqty
        var fld_iconvf      = jQuery(clonedRow).find('input[type=text]').eq(11).val(); //fld_iconvf
        var fld_aconvf      = jQuery(clonedRow).find('input[type=text]').eq(12).val(); //fld_iconvf
        var fld_actdmg      = jQuery(clonedRow).find('input[type=text]').eq(13).val(); //fld_iconvf
        var fld_actlck      = jQuery(clonedRow).find('input[type=text]').eq(14).val(); //fld_iconvf
        // var fld_olt = jQuery(clonedRow).find('input[type=text]').eq(9).val(); //olt
        var fld_drrson_id = "";//$(clonedRow).find('select[name=txtgr_rson]').eq(0).attr('id');
        var fld_drrson    = "";//$('#' + fld_drrson_id).val();
        var fld_mndt_rid  = jQuery(clonedRow).find('input[type=hidden]').eq(1).val(); //mndt id
        var fld_imndt_rid = jQuery(clonedRow).find('input[type=hidden]').eq(2).val(); //mnidt id

        mdata = fld_mitemcode + 'x|x' + fld_mitemdesc + 'x|x' + fld_mitempkg + 'x|x' + fld_ucost + 'x|x' + fld_mitemtcost + 'x|x' + fld_srp + 'x|x' + fld_mitemtamt + 'x|x' + fld_mitemqty + 'x|x' + fld_remks + 'x|x' + fld_mndt_rid + 'x|x' + fld_drrson+ 'x|x' + fld_iitemcode + 'x|x' + fld_iqty + 'x|x' + fld_iconvf + 'x|x' + fld_imndt_rid + 'x|x' + fld_aconvf + 'x|x' + fld_actdmg + 'x|x' + fld_actlck; 
        adata1.push(mdata);
        mdat = $(clonedRow).find('input[type=hidden]').eq(0).val(); //icode
        adata2.push(mdat);


       }  //end forfld_supplier_dr: fld_supplier_dr,

       var smparam = { 
           trxno_id: trxno_id,
           fld_txtgrtrx_no: fld_txtgrtrx_no,
           fld_Company_gr: fld_Company_gr,
           fld_plant : fld_plant,
           fld_wshe  : fld_wshe,
           fld_rack: fld_rack,
           fld_bin : fld_bin,                   
           fld_refno: fld_refno,
           fld_grdate: fld_grdate,
           fld_rems: fld_rems,
           fld_grtyp:fld_grtyp,
           fld_grclass:fld_grclass,
           fld_subtqty: fld_subtqty,
           fld_subtcost: fld_subtcost,
           fld_subtamt: fld_subtamt,
           ischck: ischck,
           adata1: adata1,
           adata2: adata2
       }


       jQuery.ajax({ // default declaration of ajax parameters
           type: "POST",
           url: '<?= site_url() ?>gr-save',
           context: document.body,
           data: eval(smparam),
           global: false,
           cache: false,
       success: function(data)  { //display html using divID 

            __mysys_apps.mepreloader('mepreloaderme',false);
           jQuery('#memsgtestent_success_bod').html(data);
           jQuery('#memsgtestent_success').modal('show');
       },
       error: function(data) { // display global error on the menu function
           alert('error loading page...');
           return false;
       }
       });
       }
       else{
             __mysys_apps.mepreloader('mepreloaderme',false);
           return false;
       }
       } catch(err) {
           var mtxt = 'There was an error on this page.\n';
           mtxt += 'Error description: ' + err.message;
           mtxt += '\nClick OK to continue.';
             __mysys_apps.mepreloader('mepreloaderme',false);
           alert(mtxt);
       }  //end try
       return false; 

    });


$('#tbl_grdata').on('keydown', "input", function(e) { 
    switch(e.which) {
case 37: // left 
    break;

case 38: // up
    var nidx_rw = jQuery(this).parent().parent().index();
    var nidx_td = $(this).parent().index();
    if(nidx_td == 2) { 
    } else { 
        var clonedRow = jQuery('#tbl_grdata tr:eq(' + (nidx_rw) + ')').clone(); 
        var el_id = jQuery(clonedRow).find('td').eq(nidx_td).find('input[type=text]').eq(0).attr('id');
        $('#' + el_id).focus();
    }

    break;

case 39: // right
    break;

case 40: // down
    var nidx_rw = jQuery(this).parent().parent().index();
    var nidx_td = $(this).parent().index();
    if(nidx_td == 2) { 
    } else { 
        var clonedRow = jQuery('#tbl_grdata tr:eq(' + (nidx_rw + 2) + ')').clone(); 
        var el_id = jQuery(clonedRow).find('td').eq(nidx_td).find('input[type=text]').eq(0).attr('id');
//alert(nidx_rw + ':' + nidx_td + ':' + el_id);
        $('#' + el_id).focus();
    }

    break;
default: return; // exit this handler for other keys
}
//e.preventDefault(); // prevent the default action (scroll / move caret)
}); 


function __isduplicate_mitemcode(id) { 
    try { 
        var rowCount1 = jQuery('#tbl_grdata tr').length - 1;
        var adata1 = [];
        var adata2 = [];
        var mdata = '';

        for(aa = 1; aa < rowCount1; aa++) { 
            var clonedRow = jQuery('#tbl_grdata tr:eq(' + aa + ')').clone(); 
var fld_mitemcode = jQuery(clonedRow).find('input[type=text]').eq(0).val(); //icode
//var fld_clone = id;
mdata = fld_mitemcode; //+ 'x|x' +  fld_clone
adata1.push(mdata);

}
var smparam = { 
    adata1: adata1
}
//console.log(smparam);
jQuery.ajax({ // default declaration of ajax parameters
    type: "POST",
    url: '<?= site_url() ?>mytrx_gr/isdupli_mitemcode',
    context: document.body,
    data: eval(smparam),
    global: false,
    cache: false,
success: function(data)  { //display html using divID 

      __mysys_apps.mepreloader('mepreloaderme',false);
//jQuery('#msg_show').html(data);
    if(data != ''){
        jQuery('#memsgtestent_success_bod').html(data);
        jQuery('#memsgtestent_success').modal('show');
        nullvalue(id);
    }


},
error: function(data) { // display global error on the menu function
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
}

active_plant();
function active_plant(){
    jQuery('.active_plnt_id' ) 
    // don't navigate away from the field on tab when selecting an item
    .bind( 'keypress', function( event ) {
        if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
    }
    if( event.keyCode === jQuery.ui.keyCode.TAB ) {
        event.preventDefault();
    }
    if( event.keyCode === jQuery.ui.keyCode.BACKSPACE) {
        return false;
    }
    var regex = new RegExp("^[a-zA-Z0-9\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
    })
    .autocomplete({
        minLength: 0,
        source: '<?=site_url();?>get-plant-list',
        focus: function() {
    // prevent value inserted on focus
            return false;
        },
        search: function(oEvent, oUi) { 
            var sValue = jQuery(oEvent.target).val();
    //jQuery(oEvent.target).val('&mcocd=1' + sValue);
    //alert(sValue);
        },
        select: function( event, ui ) {

            var terms = ui.item.value;
            jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
            jQuery('#' + this.id).attr('title', jQuery.trim(terms));
            var plant_id = ui.item.mtkn_rid;
            jQuery('#' + this.id).attr("data-id",plant_id);
            //jQuery('#mtkn_active_plnt_id').val(plant_id);
    //vw_brnchname(ui.item.mtkn_rid);
            this.value = ui.item.value; 
            return false;


        }
    })
    .click(function() { 
    //jQuery(this).keydown(); 
        var terms = this.value.split('|');
    //jQuery(this).autocomplete('search', '');
        jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
    });
}
// *****//end plant
active_wshe();
function active_wshe(){
jQuery('.active_wshe_id' ) 

// don't navigate away from the field on tab when selecting an item
.bind( 'keypress', function( event ) {

    if ( event.keyCode === jQuery.ui.keyCode.TAB &&
        jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
        event.preventDefault();
}
if( event.keyCode === jQuery.ui.keyCode.TAB ) {
    event.preventDefault();
}

if( event.keyCode === jQuery.ui.keyCode.BACKSPACE) {
    return false;
}
var regex = new RegExp("^[a-zA-Z0-9\b]+$");
var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
if (!regex.test(key)) {
    event.preventDefault();
    return false;
}


})
.autocomplete({
    minLength: 0,
    source: '<?=site_url();?>get-cdwarehouse-list',
    focus: function() {
// prevent value inserted on focus
        return false;
    },
    search: function(oEvent, oUi) { 
        var sValue = jQuery(oEvent.target).val();
        var plant = jQuery('#fld_plant').attr("data-id");

        if(jQuery(oEvent.target).attr("data-type") == 'bb'){
            plant = jQuery('#fld_bb_plant').attr("data-id");
        }
        jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-cdwarehouse-list?mtkn_plnt=' + plant); 
    },
    select: function( event, ui ) {

        var terms = ui.item.value;
        jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
        jQuery('#' + this.id).attr('title', jQuery.trim(terms));
        jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));
        var wshe_id = ui.item.mtkn_rid;
        jQuery('#' + this.id).attr("data-id",wshe_id);
        //jQuery('#mtkn_active_wshe_id').val(wshe_id);

        //reset value for rack and bin
        resetRackBin('.active_rack_id');
        resetRackBin('.active_bin_id');

        this.value = ui.item.value; 
        return false;
    }
})
.click(function() { 
//jQuery(this).keydown(); 
    var terms = this.value.split('|');
//jQuery(this).autocomplete('search', '');
    jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
});
}
// *****//end wshe

function resetRackBin(elem){
    $(elem).val('');
    $(elem).attr("data-id",'');
    $(elem).attr("alt",'');
    $(elem).attr("title",'');
}

jQuery('.active_rack_id' ) 

// don't navigate away from the field on tab when selecting an item
.bind( 'keypress', function( event ) {

    if ( event.keyCode === jQuery.ui.keyCode.TAB &&
        jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
        event.preventDefault();
}
if( event.keyCode === jQuery.ui.keyCode.TAB ) {
    event.preventDefault();
}

if( event.keyCode === jQuery.ui.keyCode.BACKSPACE) {
    return false;
}
var regex = new RegExp("^[a-zA-Z0-9\b]+$");
var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
if (!regex.test(key)) {
    event.preventDefault();
    return false;
}


})


.autocomplete({
    minLength: 0,
    source: '<?=site_url();?>get-warehouse-group',
    focus: function() {
// prevent value inserted on focus
        return false;
    },
    search: function(oEvent, oUi) { 
        var sValue = jQuery(oEvent.target).val();
        var mtnk_plnt = jQuery('#fld_plant').attr("data-id"); 
        var mtkn_wshe =   jQuery('#fld_wshe').attr("data-id");
        jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-warehouse-group?&mtkn_uid=' + mtkn_wshe); 
    },
    select: function( event, ui ) {

        var terms = ui.item.value;
        jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
        jQuery('#' + this.id).attr('title', jQuery.trim(terms));
        jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));
        var rack_id = ui.item.mtkn_rid;
        jQuery('#fld_rack').attr("data-id",rack_id);

        this.value = ui.item.value; 
        return false;
    }
})
.click(function() { 
//jQuery(this).keydown(); 
    var terms = this.value.split('|');
//jQuery(this).autocomplete('search', '');
    jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
});
// *****//end plant

jQuery('.active_bin_id' ) 
// don't navigate away from the field on tab when selecting an item
.bind( 'keypress', function( event ) {
if ( event.keyCode === jQuery.ui.keyCode.TAB &&
        jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
        event.preventDefault();
}
if( event.keyCode === jQuery.ui.keyCode.TAB ) {
    event.preventDefault();
}

if( event.keyCode === jQuery.ui.keyCode.BACKSPACE) {
    return false;
}
var regex = new RegExp("^[a-zA-Z0-9\b]+$");
var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
if (!regex.test(key)) {
    event.preventDefault();
    return false;
}
})

.autocomplete({
    minLength: 0,
    source: '<?=site_url();?>get-warehouse-sbin',
    focus: function() {
// prevent value inserted on focus
        return false;
    },
    search: function(oEvent, oUi) { 
        var sValue = jQuery(oEvent.target).val();
        var mtkn_wshegrp = jQuery('#fld_rack').attr("data-id");
         var mtkn_wshe =   jQuery('#fld_wshe').attr("data-id");
        jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-warehouse-sbin?&mtkn_wshe_grp=' + mtkn_wshegrp+'&mtkn_uid=' + mtkn_wshe); 
    },
    select: function( event, ui ) {

        var terms = ui.item.value;
        jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
        jQuery('#' + this.id).attr('title', jQuery.trim(terms));
        jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));
        var bin_id = ui.item.mtkn_rid;
        jQuery('#fld_bin').attr("data-id",bin_id);


        this.value = ui.item.value; 
        return false;
    }
})
.click(function() { 
//jQuery(this).keydown(); 
    var terms = this.value.split('|');
//jQuery(this).autocomplete('search', '');
    jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
});
// *****//end plant

jQuery('#asst_itemc') 
// don't navigate away from the field on tab when selecting an item
.bind( 'keypress', function( event ) {
    if (event.keyCode === jQuery.ui.keyCode.ENTER && jQuery( this ).data( 'autocomplete-ui' ).menu.active ) {
        event.preventDefault();
    }
    if( event.keyCode === jQuery.ui.keyCode.ENTER ) {
        event.preventDefault();
    }

})
.autocomplete({
    minLength: 0,
    source: '<?= site_url(); ?>get-article-asstd/',
    focus: function() {
// prevent value inserted on focus
        return false;
    },
    search: function(oEvent, oUi) { 
        var sValue = jQuery(oEvent.target).val();
//jQuery(oEvent.target).val('&mcocd=1' + sValue);
//alert(sValue);
    },
    select: function( event, ui ) {
        var total_line = parseInt(jQuery('#line_item').val(),10);
        var gr_type = jQuery('#fld_grtyp').val();
        if(total_line == '' || total_line == 0){
            alert('No. of Line Item is required');
            return false;
        }

        if(total_line > 20){
            alert('Line Item value is 20 below only !');
            return false;
        }
        if($('input.asstd_chk').prop("checked") != true){
            alert('Check is required');
            return false;  
        }
        var terms = ui.item.value;
        jQuery(this).attr('alt', jQuery.trim(ui.item.ART_CODE));
        jQuery(this).attr('title', jQuery.trim(ui.item.ART_CODE));
        jQuery(this).attr('src', jQuery.trim(ui.item.ART_IMG));

//console.log(wshe_id);
        this.value = ui.item.ART_CODE;
        var _itemcode = ui.item.ART_CODE;
        var mdat1 = ui.item.ART_DESC;
        var mdat2 = ui.item.ART_SKU;
        var mdat3 = ui.item.ART_UCOST;
        var mdat4 = ui.item.ART_UPRICE;
        var __rid = ui.item.mtkn_rid;

        if (gr_type == 3){
            var rowCount = jQuery('#tbl_grdata tr').length;
            for(aa = 1; aa < rowCount; aa++) { 
                var clonedRow = jQuery('#tbl_grdata tr:eq(' + aa + ')').clone();
                var box_itemcode = jQuery(clonedRow).find('input[type=text]').eq(0).attr('id'); 
                var item_desc = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');
                var packaging = jQuery(clonedRow).find('input[type=text]').eq(2).attr('id');
                jQuery('#' + box_itemcode).val(_itemcode);
                jQuery('#' + item_desc).val(mdat1);
                jQuery('#' + packaging).val(mdat2);
            }

        } 
//AUTOADDLINES
//console.log(__rid);
        addlines_asstd(total_line,_itemcode,mdat1,mdat2,mdat3,mdat4,__rid);
/*if ($('input.asstd_chk').is(':checked')) {

}
else{

}*/
    }
})
.click(function() { 
//jQuery(this).keydown(); 
    var terms = this.value.split('=>');
//jQuery(this).autocomplete('search', '');
    jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
});
function addlines_asstd(total_line,itemcode,itemdesc,sku,ucost,uprice,__rid){
    try { 

//if(supp_name =="9afc216ae427a234fd010e8d68a71b8b02b83b37b0a914fbe9438f3d4f1a7b216b981dfcb0a4fec6751577d19b29a123"){
//var mtxt = 'Please wait...We add 150 fields.\n';
//mtxt += '\nClick OK to continue.';
//alert(mtxt);
          __mysys_apps.mepreloader('mepreloaderme',true);
        var meparam = {
            total_line: total_line,
            itemcode : itemcode,
            itemdesc : itemdesc,
            sku : sku,
            ucost : ucost,
            uprice :uprice,
            __rid : __rid                   
        };
        jQuery.ajax({ // default declaration of ajax parameters
            type: "POST",
            url: '<?= site_url() ?>gr-auto-addline',
            context: document.body,
            data: eval(meparam),
            global: false,
            cache: false,
        success: function(data)  { //display html using divID 
            $('#msg_show').html(data);
              __mysys_apps.mepreloader('mepreloaderme',false);
        //for(aa = 1; aa <= data; aa++) { 
        //my_add_line_item();
        //}///end for
        },
        error: function(data) { // display global error on the menu function
            alert('error loading page...');
            return false;
        }
        });

//for($aa = 0; $aa < $smc; $aa++) {
//      my_add_line_item();
//}
//  __mysys_apps.mepreloader('mepreloaderme',false);
//}

} catch(err) {
    var mtxt = 'There was an error on this page.\n';
    mtxt += 'Error description: ' + err.message;
    mtxt += '\nClick OK to continue.';
    alert(mtxt);
      __mysys_apps.mepreloader('mepreloaderme',false);
    return false;
}  //end try   

}
function __my_item_lookup_i() {  
    jQuery('.miitemcode' ) 
// don't navigate away from the field on tab when selecting an item
    .bind( 'keypress', function( event ) {
        if (event.keyCode === jQuery.ui.keyCode.ENTER && jQuery( this ).data( 'autocomplete-ui' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.ENTER ) {
            event.preventDefault();
        }

    })
    .autocomplete({
        minLength: 0,
        autoFocus: true,
        source: '<?= site_url(); ?>get-article-reg/',
        search: function(oEvent, oUi) { 
            var sValue = jQuery(oEvent.target).val();

//jQuery(oEvent.target).val('&mcocd=1' + sValue);
//alert(sValue);
        },
        select: function( event, ui ) {
            var terms = ui.item.value;

            jQuery(this).attr('alt', jQuery.trim(ui.item.ART_CODE));
            jQuery(this).attr('title', jQuery.trim(ui.item.ART_CODE));

            this.value = ui.item.ART_CODE;
//var xobjArtMrid_ic = jQuery(clonedRow).find('input[type=text]').eq(9).attr('id');
            var clonedRow = jQuery(this).parent().parent().clone();
            var indexRow = jQuery(this).parent().parent().index();
            var xobjArtMrid_i = jQuery(clonedRow).find('input[type=hidden]').eq(2).attr('id');
            var xobjArtMConvf_i = jQuery(clonedRow).find('input[type=text]').eq(11).attr('id');

            var xobjArtMUcost_i= jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');
            var xobjArtMSRP_i = jQuery(clonedRow).find('input[type=text]').eq(5).attr('id');


            jQuery('#' + xobjArtMUcost_i).val(ui.item.ART_UCOST);
            jQuery('#' + xobjArtMSRP_i).val(ui.item.ART_UPRICE);
            jQuery('#' + xobjArtMrid_i).val(ui.item.ART_MATRID);
            jQuery('#' + xobjArtMConvf_i).val(ui.item.ART_NCONVF);
//jQuery('#' + xobjArtMrid_ic).val(ui.item.ART_CODE);
//jQuery('#' + xobjArtMQty).focus();


// console.log(ui.item.ART_NCBM);
//jQuery('#' + xobjArtMImgId).attr('src','<?= site_url(); ?>uploads/artm/' + ui.item.ART_IMG);

            return false;
        }
    })
    .click(function() { 
//jQuery(this).keydown(); 
        var terms = this.value.split('=>');
//jQuery(this).autocomplete('search', '');
        jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
    });        
}

//FOR PULLOUT PICKFROM
// $('#btn_refno').on('click',function() {

//         //$('#btn_prno').attr("disabled","disabled");

//         var fld_grtyp = jQuery('#fld_grtyp').val();
//         var fld_refno = jQuery('#fld_refno').val();

//         if(fld_grtyp != 3){
//             alert('Pickfrom is for Pullout from Branch GR type only!');
//             return false;
//         }
//         if(fld_grtyp == ''){
//             alert('GR type is required!');
//             return false;
//         }
//         if(fld_grtyp == 3){
//             $('#POB').css('display', (fld_grtyp == 3) ? 'flex' : 'none');
//             $('#POB2').css('display', (grtyp_tag == 3) ? 'flex' : 'none');
//             $('.POB1').css('display', (fld_grtyp == 3) ? 'flex' : 'none');
//             $('.POB3').css('display', (fld_grtyp == 3) ? 'flex' : 'none');

//         }
//         if(fld_grtyp != 3){
//             $('#POB').css('display', (fld_grtyp != 3) ? 'none' : 'flex');
//             $('#POB2').css('display', (grtyp_tag != 3) ? 'none' : 'flex');
//             $('.POB1').css('display', (fld_grtyp != 3) ? 'none' : 'flex');
//             $('.POB3').css('display', (fld_grtyp == 3) ? 'flex' : 'none');

//         }
//         vw_pullout(fld_refno,fld_grtyp);
// });

// __hidecolumn();
// function __hidecolumn(){
//     // var fld_grtyp = jQuery('#fld_grtyp').val();
//     // if(fld_grtyp == ''){
//         var $firstc = $('#tbl_grdata').find('thead').find('th:nth-child(12)');
//         var $secondc = $('#tbl_grdata').find('thead').find('th:nth-child(13)');
//         var $first = $('#tbl_grdata').find('tr').find('td:nth-child(12)');
//         var $second = $('#tbl_grdata').find('tr').find('td:nth-child(13)');


//         $firstc.hide();
//         $secondc.hide();
//         $first.hide();
//         $second.hide();             
//     //}


// }
var __hmtkn_ptyp = jQuery('#__hmtkn_ptyp').val();
var hc3 = $('#tbl_grdata').find('thead').find('th:nth-child(3)');
var hc4 = $('#tbl_grdata').find('thead').find('th:nth-child(4)');
var hc5 = $('#tbl_grdata').find('thead').find('th:nth-child(5)');
var hc6 = $('#tbl_grdata').find('thead').find('th:nth-child(6)');
var hc7 = $('#tbl_grdata').find('thead').find('th:nth-child(7)');
var hc8 = $('#tbl_grdata').find('thead').find('th:nth-child(8)');
var hc9 = $('#tbl_grdata').find('thead').find('th:nth-child(9)');
var hc10 = $('#tbl_grdata').find('thead').find('th:nth-child(10)');
var hc12 = $('#tbl_grdata').find('thead').find('th:nth-child(12)');

var firstc = $('#tbl_grdata').find('thead').find('th:nth-child(15)');
var secondc = $('#tbl_grdata').find('thead').find('th:nth-child(16)');
var thirdc = $('#tbl_grdata').find('thead').find('th:nth-child(17)');

var hr3 = $('#tbl_grdata').find('tr').find('td:nth-child(3)');
var hr4 = $('#tbl_grdata').find('tr').find('td:nth-child(4)');
var hr5 = $('#tbl_grdata').find('tr').find('td:nth-child(5)');
var hr6 = $('#tbl_grdata').find('tr').find('td:nth-child(6)');
var hr7 = $('#tbl_grdata').find('tr').find('td:nth-child(7)');
var hr8 = $('#tbl_grdata').find('tr').find('td:nth-child(8)');
var hr9 = $('#tbl_grdata').find('tr').find('td:nth-child(9)');
var hr10 = $('#tbl_grdata').find('tr').find('td:nth-child(10)');
var hr12 = $('#tbl_grdata').find('tr').find('td:nth-child(12)');
var first = $('#tbl_grdata').find('tr').find('td:nth-child(15)');
var second = $('#tbl_grdata').find('tr').find('td:nth-child(16)');
var third = $('#tbl_grdata').find('tr').find('td:nth-child(17)');
<?php 
if($txtgr_type == 3){
    if($fld_ptyp == 'N'){
        echo "hc3.hide();
        hc4.hide();
        hc5.hide();
        hc6.hide();
        hc7.hide();
        hc8.hide();
        hc9.hide();
        hc10.hide();
        hc12.hide();

        hr3.hide();
        hr4.hide();
        hr5.hide();
        hr6.hide();
        hr7.hide();
        hr8.hide();
        hr9.hide();
        hr10.hide();
        hr12.hide(); ";
    }
    else{
        echo "hc3.show();
        hc4.show();
        hc5.show();
        hc6.show();
        hc7.show();
        hc8.show();
        hc9.show();
        hc10.show();
        hc12.show();

        hr3.show();
        hr4.show();
        hr5.show();
        hr6.show();
        hr7.show();
        hr8.show();
        hr9.show();
        hr10.show();
        hr12.show(); ";
    }
    echo "  firstc.show();
    secondc.show();
    thirdc.show();
    first.show();
    second.show();
    third.show(); ";
}
else{
    echo "  firstc.hide();
    secondc.hide();
    thirdc.hide();
    first.hide();
    second.hide();
    third.hide();  ";
}
?>

$('#btn_refno').click(function() {
    var fld_grtyp = jQuery('#fld_grtyp').val();
    var fld_refno = jQuery('#fld_refno').val();


    if(fld_grtyp != 3){
        alert('Pickfrom is for Pullout from Branch GR type only!');
        return false;
    }
    if(fld_grtyp == ''){
        alert('GR type is required!');
        return false;
    }
    vw_pullout(fld_refno,fld_grtyp);
// var $firstc = $('#tbl_grdata').find('thead').find('th:nth-child(12)');
// var $secondc = $('#tbl_grdata').find('thead').find('th:nth-child(13)');
// var $first = $('#tbl_grdata').find('tr').find('td:nth-child(12)');
// var $second = $('#tbl_grdata').find('tr').find('td:nth-child(13)');


// $firstc.hide();
// $secondc.hide();
// $first.hide();
// $second.hide(); 

// if(fld_grtyp == 3){
//     $firstc.show();
//     $secondc.show();
//     $first.show();
//     $second.show();

// }
// else if(fld_grtyp != 3){
//      $firstc.hide();
//     $secondc.hide();
//     $first.hide();
//     $second.hide(); 

// }
// var __hmtkn_ptyp = jQuery('#__hmtkn_ptyp').val();
// var fld_grtyp = jQuery('#fld_grtyp').val(); // 3 is Return to mapulang Lupa
// var $firstc = $('#tbl_grdata').find('thead').find('th:nth-child(15)');
// var $secondc = $('#tbl_grdata').find('thead').find('th:nth-child(16)');
// var $thirdc = $('#tbl_grdata').find('thead').find('th:nth-child(17)');
// var $first = $('#tbl_grdata').find('tr').find('td:nth-child(15)');
// var $second = $('#tbl_grdata').find('tr').find('td:nth-child(16)');
// var $third = $('#tbl_grdata').find('tr').find('td:nth-child(17)');

/*var hc3 = $('#tbl_grdata').find('thead').find('th:nth-child(3)');
var hc4 = $('#tbl_grdata').find('thead').find('th:nth-child(4)');
var hc5 = $('#tbl_grdata').find('thead').find('th:nth-child(5)');
var hc6 = $('#tbl_grdata').find('thead').find('th:nth-child(6)');
var hc7 = $('#tbl_grdata').find('thead').find('th:nth-child(7)');
var hc8 = $('#tbl_grdata').find('thead').find('th:nth-child(8)');
var hc9 = $('#tbl_grdata').find('thead').find('th:nth-child(9)');
var hc10 = $('#tbl_grdata').find('thead').find('th:nth-child(10)');
var hc12 = $('#tbl_grdata').find('thead').find('th:nth-child(12)');
var hr3 = $('#tbl_grdata').find('tr').find('td:nth-child(3)');
var hr4 = $('#tbl_grdata').find('tr').find('td:nth-child(4)');
var hr5 = $('#tbl_grdata').find('tr').find('td:nth-child(5)');
var hr6 = $('#tbl_grdata').find('tr').find('td:nth-child(6)');
var hr7 = $('#tbl_grdata').find('tr').find('td:nth-child(7)');
var hr8 = $('#tbl_grdata').find('tr').find('td:nth-child(8)');
var hr9 = $('#tbl_grdata').find('tr').find('td:nth-child(9)');
var hr10 = $('#tbl_grdata').find('tr').find('td:nth-child(10)');
var hr12 = $('#tbl_grdata').find('tr').find('td:nth-child(12)');*/

// $firstc.hide();
// $secondc.hide();
// $thirdc.hide();
// $first.hide();
// $second.hide();
// $third.hide();

// if(fld_grtyp != 3){
//     $firstc.hide();
//     $secondc.hide();
//     $thirdc.hide();
//     $first.hide();
//     $second.hide();
//     $third.hide(); 
// }
// else if(fld_grtyp == 3){
//         if(__hmtkn_ptyp == 'N' ){
//             hc3.hide();
//             hc4.hide();
//             hc5.hide();
//             hc6.hide();
//             hc7.hide();
//             hc8.hide();
//             hc9.hide();
//             hc10.hide();
//             hc12.hide();

//             hr3.hide();
//             hr4.hide();
//             hr5.hide();
//             hr6.hide();
//             hr7.hide();
//             hr8.hide();
//             hr9.hide();
//             hr10.hide();
//             hr12.hide();
//         }
//         else{
//             hc3.show();
//             hc4.show();
//             hc5.show();
//             hc6.show();
//             hc7.show();
//             hc8.show();
//             hc9.show();
//             hc10.show();
//             hc12.show();

//             hr3.show();
//             hr4.show();
//             hr5.show();
//             hr6.show();
//             hr7.show();
//             hr8.show();
//             hr9.show();
//             hr10.show();
//             hr12.show();
//         }
//         $firstc.show();
//         $secondc.show();
//         $thirdc.show();
//         $first.show();
//         $second.show();
//         $third.show();
// }

});

$('#fld_grtyp').change(function() {
$("#fld_grtyp").attr("disabled", true);
var fld_grtyp = jQuery('#fld_grtyp').val(); // 3 is Return to mapulang Lupa
var $firstc = $('#tbl_grdata').find('thead').find('th:nth-child(15)');
var $secondc = $('#tbl_grdata').find('thead').find('th:nth-child(16)');
var $thirdc = $('#tbl_grdata').find('thead').find('th:nth-child(17)');
var $first = $('#tbl_grdata').find('tr').find('td:nth-child(15)');
var $second = $('#tbl_grdata').find('tr').find('td:nth-child(16)');
var $third = $('#tbl_grdata').find('tr').find('td:nth-child(17)');


$firstc.hide();
$secondc.hide();
$thirdc.hide();
$first.hide();
$second.hide();
$third.hide();

if(fld_grtyp != 3){
    $firstc.hide();
    $secondc.hide();
    $thirdc.hide();
    $first.hide();
    $second.hide();
    $third.hide(); 
}
else if(fld_grtyp == 3){

    $firstc.show();
    $secondc.show();
    $thirdc.show();
    $first.show();
    $second.show();
    $third.show();
}



});
function vw_pullout(fld_refno,fld_grtyp){
    try { 
          __mysys_apps.mepreloader('mepreloaderme',true);


        var meparam = {
            fld_refno: fld_refno,
            fld_grtyp: fld_grtyp
        };
//console.log(meparam);
jQuery.ajax({ // default declaration of ajax parameters
    type: "POST",
    url: '<?= site_url() ?>gr-addlines-pullout',
    context: document.body,
    data: eval(meparam),
    global: false,
    cache: false,
success: function(data)  { //display html using divID 
    $('#msg_show').html(data);

      __mysys_apps.mepreloader('mepreloaderme',false);

//for(aa = 1; aa <= data; aa++) { 
//my_add_line_item();
//}///end for
},
error: function(data) { // display global error on the menu function
    alert('error loading page...');
    return false;
}
});

//for($aa = 0; $aa < $smc; $aa++) {
//      my_add_line_item();
//}
//  __mysys_apps.mepreloader('mepreloaderme',false);
//}

} catch(err) {
    var mtxt = 'There was an error on this page.\n';
    mtxt += 'Error description: ' + err.message;
    mtxt += '\nClick OK to continue.';
    alert(mtxt);
      __mysys_apps.mepreloader('mepreloaderme',false);
    return false;
}  //end try   

}

</script>
