<?php
/*
 * Module      :    MyItemCompModel.php
 * Type 	   :    Model
 * Program Desc:    MyItemCompModel
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/
namespace App\Models;
use CodeIgniter\Model;

class MyItemCompModel extends Model
{
	
    public function __construct()
    {
        parent::__construct();
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(0);
        $this->mylibzdb = model('App\Models\MyLibzDBModel');
        $this->mylibzsys = model('App\Models\MyLibzSysModel');
        $this->mydataz = model('App\Models\MyDatumModel');
        $this->dbx = $this->mylibzdb->dbx;
        $this->request = \Config\Services::request();
    }

    public function item_comp_entry_save() {
        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");  
        $txt_fg_code = $this->request->getVar('txt_fg_code');

        $txt_fabric_code = $this->request->getVar('txt_fabric_code');
        $txt_fabric_qty = $this->request->getVar('txt_fabric_qty');
        $txt_fabric_tcost = $this->request->getVar('txt_fabric_tcost');

        $txt_lining_code = $this->request->getVar('txt_lining_code');
        $txt_lining_qty = $this->request->getVar('txt_lining_qty');
        $txt_lining_tcost = $this->request->getVar('txt_lining_tcost');

        $txt_btn_code = $this->request->getVar('txt_btn_code');
        $txt_btn_qty = $this->request->getVar('txt_btn_qty');
        $txt_btn_tcost = $this->request->getVar('txt_btn_tcost');

        $txt_rivets_code = $this->request->getVar('txt_rivets_code');
        $txt_rivets_qty = $this->request->getVar('txt_rivets_qty');
        $txt_rivets_tcost = $this->request->getVar('txt_rivets_tcost');

        $txt_leather_patch_code = $this->request->getVar('txt_leather_patch_code');
        $txt_leather_patch_qty = $this->request->getVar('txt_leather_patch_qty');
        $txt_leather_patch_tcost = $this->request->getVar('txt_leather_patch_tcost');

        $txt_plastic_btn_code = $this->request->getVar('txt_plastic_btn_code');
        $txt_plastic_btn_qty = $this->request->getVar('txt_plastic_btn_qty');
        $txt_plastic_btn_tcost = $this->request->getVar('txt_plastic_btn_tcost');

        $txt_inside_garter_code = $this->request->getVar('txt_inside_garter_code');
        $txt_inside_garter_qty = $this->request->getVar('txt_inside_garter_qty');
        $txt_inside_garter_tcost = $this->request->getVar('txt_inside_garter_tcost');

        $txt_hang_tag_code = $this->request->getVar('txt_hang_tag_code');
        $txt_hang_tag_qty = $this->request->getVar('txt_hang_tag_qty');
        $txt_hang_tag_tcost = $this->request->getVar('txt_hang_tag_tcost');
        
        $txt_zipper_code = $this->request->getVar('txt_zipper_code');
        $txt_zipper_qty = $this->request->getVar('txt_zipper_qty');
        $txt_zipper_tcost = $this->request->getVar('txt_zipper_tcost');

        $txt_size_lbl_code = $this->request->getVar('txt_size_lbl_code');
        $txt_size_lbl_qty = $this->request->getVar('txt_size_lbl_qty');
        $txt_size_lbl_tcost = $this->request->getVar('txt_size_lbl_tcost');

        $txt_size_care_lbl_code = $this->request->getVar('txt_size_care_lbl_code');
        $txt_size_care_lbl_qty = $this->request->getVar('txt_size_care_lbl_qty');
        $txt_size_care_lbl_tcost = $this->request->getVar('txt_size_care_lbl_tcost');

        $txt_side_lbl_code = $this->request->getVar('txt_side_lbl_code');
        $txt_side_lbl_qty = $this->request->getVar('txt_side_lbl_qty');
        $txt_side_lbl_tcost = $this->request->getVar('txt_side_lbl_tcost');

        $txt_kids_lbl_code = $this->request->getVar('txt_kids_lbl_code');
        $txt_kids_lbl_qty = $this->request->getVar('txt_kids_lbl_qty');
        $txt_kids_lbl_tcost = $this->request->getVar('txt_kids_lbl_tcost');

        $txt_kids_side_lbl_code = $this->request->getVar('txt_kids_side_lbl_code');
        $txt_kids_side_lbl_qty = $this->request->getVar('txt_kids_side_lbl_qty');
        $txt_kids_side_lbl_tcost = $this->request->getVar('txt_kids_side_lbl_tcost');

        $txt_plastic_bag_code = $this->request->getVar('txt_plastic_bag_code');
        $txt_plastic_bag_qty = $this->request->getVar('txt_plastic_bag_qty');
        $txt_plastic_bag_tcost = $this->request->getVar('txt_plastic_bag_tcost');

        $txt_barcode_code = $this->request->getVar('txt_barcode_code');
        $txt_barcode_qty = $this->request->getVar('txt_barcode_qty');
        $txt_barcode_tcost = $this->request->getVar('txt_barcode_tcost');

        $txt_fitting_sticker_code = $this->request->getVar('txt_fitting_sticker_code');
        $txt_fitting_sticker_qty = $this->request->getVar('txt_fitting_sticker_qty');
        $txt_fitting_sticker_tcost = $this->request->getVar('txt_fitting_sticker_tcost');

        $txt_tag_pin_code = $this->request->getVar('txt_tag_pin_code');
        $txt_tag_pin_qty = $this->request->getVar('txt_tag_pin_qty');
        $txt_tag_pin_tcost = $this->request->getVar('txt_tag_pin_tcost');

        $txt_chip_board_code = $this->request->getVar('txt_chip_board_code');
        $txt_chip_board_qty = $this->request->getVar('txt_chip_board_qty');
        $txt_chip_board_tcost = $this->request->getVar('txt_chip_board_tcost');

        $str="
        SELECT ART_CODE FROM mst_item_comp WHERE ART_CODE = '$txt_fg_code';
        ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        if($qry->resultID->num_rows > 0) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error: </strong>Rawmats for this FG item is already existing.</div>";
            die();
        }else{
            $str = "
            INSERT INTO {$this->db_erp}.`mst_item_comp`(
                `ART_CODE`,
                `fabric_code`,
                `fabric_qty`,
                `fabric_tcost`,
                `lining_code`,
                `lining_qty`,
                `lining_tcost`,
                `btn_code`,
                `btn_qty`,
                `btn_tcost`,
                `rivets_code`,
                `rivets_qty`,
                `rivets_tcost`,
                `leather_patch_code`,
                `leather_patch_qty`,
                `leather_patch_tcost`,
                `plastic_btn_code`,
                `plastic_btn_qty`,
                `plastic_btn_tcost`,
                `inside_garter_code`,
                `inside_garter_qty`,
                `inside_garter_tcost`,
                `hang_tag_code`,
                `hang_tag_qty`,
                `hang_tag_tcost`,
                `zipper_code`,
                `zipper_qty`,
                `zipper_tcost`,
                `size_lbl_code`,
                `size_lbl_qty`,
                `size_lbl_tcost`,
                `size_care_lbl_code`,
                `size_care_lbl_qty`,
                `size_care_lbl_tcost`,
                `side_lbl_code`,
                `side_lbl_qty`,
                `side_lbl_tcost`,
                `kids_lbl_code`,
                `kids_lbl_qty`,
                `kids_lbl_tcost`,
                `kids_side_lbl_code`,
                `kids_side_lbl_qty`,
                `kids_side_lbl_tcost`,
                `plastic_bag_code`,
                `plastic_bag_qty`,
                `plastic_bag_tcost`,
                `barcode_code`,
                `barcode_qty`,
                `barcode_tcost`,
                `fitting_sticker_code`,
                `fitting_sticker_qty`,
                `fitting_sticker_tcost`,
                `tag_pin_code`,
                `tag_pin_qty`,
                `tag_pin_tcost`,
                `chip_board_code`,
                `chip_board_qty`,
                `chip_board_tcost`
                )
    
                values(
                '$txt_fg_code',
                '$txt_fabric_code',
                '$txt_fabric_qty',
                '$txt_fabric_tcost',
                '$txt_lining_code',
                '$txt_lining_qty',
                '$txt_lining_tcost',
                '$txt_btn_code',
                '$txt_btn_qty',
                '$txt_btn_tcost',
                '$txt_rivets_code',
                '$txt_rivets_qty',
                '$txt_rivets_tcost',
                '$txt_leather_patch_code',
                '$txt_leather_patch_qty',
                '$txt_leather_patch_tcost',
                '$txt_plastic_btn_code',
                '$txt_plastic_btn_qty',
                '$txt_plastic_btn_tcost',
                '$txt_inside_garter_code',
                '$txt_inside_garter_qty',
                '$txt_inside_garter_tcost',
                '$txt_hang_tag_code',
                '$txt_hang_tag_qty',
                '$txt_hang_tag_tcost',
                '$txt_zipper_code',
                '$txt_zipper_qty',
                '$txt_zipper_tcost',
                '$txt_size_lbl_code',
                '$txt_size_lbl_qty',
                '$txt_size_lbl_tcost',
                '$txt_size_care_lbl_code',
                '$txt_size_care_lbl_qty',
                '$txt_size_care_lbl_tcost',
                '$txt_side_lbl_code',
                '$txt_side_lbl_qty',
                '$txt_side_lbl_tcost',
                '$txt_kids_lbl_code',
                '$txt_kids_lbl_qty',
                '$txt_kids_lbl_tcost',
                '$txt_kids_side_lbl_code',
                '$txt_kids_side_lbl_qty',
                '$txt_kids_side_lbl_tcost',
                '$txt_plastic_bag_code',
                '$txt_plastic_bag_qty',
                '$txt_plastic_bag_tcost',
                '$txt_barcode_code',
                '$txt_barcode_qty',
                '$txt_barcode_tcost',
                '$txt_fitting_sticker_code',
                '$txt_fitting_sticker_qty',
                '$txt_fitting_sticker_tcost',
                '$txt_tag_pin_code',
                '$txt_tag_pin_qty',
                '$txt_tag_pin_tcost',
                '$txt_chip_board_code',
                '$txt_chip_board_qty',
                '$txt_chip_board_tcost'
                )
            ";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
        }

        echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Successfully Saved.</div>
                        <script type=\"text/javascript\"> 
                        function __fg_refresh_data() { 
                            try { 
                                
                                jQuery('#mbtn_mn_Save').prop('disabled',true);
                                } catch(err) { 
                                    var mtxt = 'There was an error on this page.\\n';
                                    mtxt += 'Error description: ' + err.message;
                                    mtxt += '\\nClick OK to continue.';
                                    alert(mtxt);
                                    return false;
                                    }  //end try 
                                } 
                                
                                __fg_refresh_data();
                                </script>
                                ";  
         
    } //end item_comp_entry_save

    public function item_comp_entry_save_2() {
        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();

        $icomp_trxno = $this->request->getVar('icomp_trxno');
        $fg_code = $this->request->getVar('fg_item');
        $adata1 = $this->request->getVar('adata1');


        if (empty($fg_code)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error: </strong> FG Item is required! </div>";
            die();
        }

        $cseqn =  $this->mydataz->get_ctr_new_dr('IC','',$this->db_erp,'CTRL_ITEMCOMP');

        $str="
        SELECT fg_code FROM mst_item_comp2 WHERE fg_code = '$fg_code';
        ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        if($qry->resultID->num_rows > 0) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error: </strong>Rawmats for this FG item is already existing.</div>";
            die();
        }else{
            if(count($adata1) > 0) { 
                $ame = array();
                $adatar1 = array();
    
                for($aa = 0; $aa < count($adata1); $aa++) { 
                    $medata = explode("x|x",$adata1[$aa]);
                    $mitemc = trim($medata[0]);
                    $amatnr = array();
    
                    if(!empty($mitemc)) { 
                        $str = "select aa.recid,aa.ART_CODE from {$this->db_erp}.`mst_article` aa where ART_CODE = '$mitemc' ";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        $rw = $q->getRowArray(); 
                        array_push($ame,$mitemc); 
                        array_push($adatar1,$medata);
                    }
                }  
                
                if(count($adatar1) > 0) { 
                    for($xx = 0; $xx < count($adatar1); $xx++) { 
                        
                        $xdata = $adatar1[$xx];
                        $mitemc = $xdata[0];
                        $mdesc = $xdata[1];
                        $mqty = $xdata[2];
                        $mcost = $xdata[3];
                        $mtcost = $xdata[4];
                        $muom = $xdata[5];
    
                        $str="
                        INSERT INTO `mst_item_comp2` (
                            `icomp_trxno`,
                            `fg_code`,
                            `rm_code`,
                            `item_desc`,
                            `item_qty`,
                            `item_cost`,
                            `item_tcost`,
                            `item_uom`,
                            `comp_date`
                          )
                          VALUES
                            (
                              '$cseqn',
                              '$fg_code',
                              '$mitemc',
                              '$mdesc',
                              '$mqty',
                              '$mcost',
                              '$mtcost',
                              '$muom',
                              now()
                            );
                        ";
                        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
                    } 
                    
                } 
            }
        }


        echo "<div class=\"alert alert-success mb-0 pb-1\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! Series No:{$cseqn} </div>
            <script type=\"text/javascript\"> 
                function __fg_refresh_data() { 
                    try { 
                        $('#icomp_trxno').val('{$cseqn}');
                        
                        jQuery('#mbtn_mn_Save').prop('disabled',true);
                    } catch(err) { 
                        var mtxt = 'There was an error on this page.\\n';
                        mtxt += 'Error description: ' + err.message;
                        mtxt += '\\nClick OK to continue.';
                        alert(mtxt);
                        return false;
                    }  //end try 
                } 
                
                __fg_refresh_data();
            </script>
            ";
            die();



         
    } //end item_comp_entry_save_2

    public function item_comp_update_2() {
        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();

        $icomp_trxno = $this->request->getVar('icomp_trxno');
        $fg_code = $this->request->getVar('fg_item');
        $comp_date = $this->request->getVar('comp_date');
        $adata1 = $this->request->getVar('adata1');

        var_dump($adata1);
        die();

        if (empty($comp_date)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error: </strong> Date is required! </div>";
            die();
        }

        if (empty($fg_code)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error: </strong> FG Item is required! </div>";
            die();
        }

        if(count($adata1) > 0) { 
            $ame = array();
            $adatar1 = array();

            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);
                $mitemc = trim($medata[0]);
                $amatnr = array();

                if(!empty($mitemc)) { 
                    $str = "select aa.recid,aa.ART_CODE from {$this->db_erp}.`mst_article` aa where ART_CODE = '$mitemc' ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $rw = $q->getRowArray(); 
                    array_push($ame,$mitemc); 
                    array_push($adatar1,$medata);
                }
            }  
            
            if(count($adatar1) > 0) { 
                for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                    $xdata = $adatar1[$xx];
                    $mitemc = $xdata[0];
                    $mdesc = $xdata[1];
                    $mqty = $xdata[2];
                    $mcost = $xdata[3];
                    $mtcost = $xdata[4];
                    $muom = $xdata[5];

                    $str="
                    SELECT rm_code FROM mst_item_comp2 WHERE rm_code = '$mitemc' AND icomp_trxno = '$icomp_trxno';
                    ";
                    $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $rw = $qry->getResultArray();           
                    foreach ($rw as $data) {
                        $rm_code = $data['rm_code'];

                        if ($rm_code == $mitemc) {
                            $str="
                            UPDATE mst_item_comp2 SET 
                            `icomp_trxno` = '$icomp_trxno',
                            `fg_code` = '$fg_code',
                            `rm_code` = '$mitemc',
                            `item_desc` = '$mdesc',
                            `item_qty` = '$mqty',
                            `item_cost` = '$mcost',
                            `item_tcost` = '$mtcost',
                            `item_uom` = '$muom',
                            `comp_date` = '$comp_date'
                            ";
                        }else{
                            $str="
                            INSERT INTO `mst_item_comp2` (
                                `icomp_trxno`,
                                `fg_code`,
                                `rm_code`,
                                `item_desc`,
                                `item_qty`,
                                `item_cost`,
                                `item_tcost`,
                                `item_uom`,
                                `comp_date`
                                )
                                VALUES
                                (
                                '$icomp_trxno',
                                '$fg_code',
                                '$mitemc',
                                '$mdesc',
                                '$mqty',
                                '$mcost',
                                '$mtcost',
                                '$muom',
                                '$comp_date'
                                );
                            ";
                            $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        }
                    }
                } 
                
            } 
            
        }


        echo "<div class=\"alert alert-success mb-0 pb-1\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Updated Successfully!!! </div>
            <script type=\"text/javascript\"> 
                function __fg_refresh_data() { 
                    try { 
                        
                        jQuery('#mbtn_mn_Update').prop('disabled',true);
                    } catch(err) { 
                        var mtxt = 'There was an error on this page.\\n';
                        mtxt += 'Error description: ' + err.message;
                        mtxt += '\\nClick OK to continue.';
                        alert(mtxt);
                        return false;
                    }  //end try 
                } 
                
                __fg_refresh_data();
            </script>
            ";
            die();



         
    } //end item_comp_update_2

    public function item_comp_upld_entry_save_2() {

        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $icomp_trxno = $this->request->getVar('icomp_trxno');
        $fg_code = $this->request->getVar('fg_code');
        $ucomp_date = $this->request->getVar('ucomp_date');
        $adata1 = $this->request->getVar('adata1');

        if (empty($fg_code)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error: </strong> FG Item is required! </div>";
            die();
        }

        $cseqn =  $this->mydataz->get_ctr_new_dr('ITCP','',$this->db_erp,'CTRL_ITEMCOMP');

        $str="
        SELECT fg_code FROM mst_item_comp2 WHERE fg_code = '$fg_code';
        ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        if($qry->resultID->num_rows > 0) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error: </strong>Rawmats for this FG item is already existing.</div>";
            die();
        }else{

            if(count($adata1) > 0) { 
                $ame = array();
                $adatar1 = array();
    
                for($aa = 0; $aa < count($adata1); $aa++) { 
                    $medata = explode("x|x",$adata1[$aa]);
                    $mitemc = trim($medata[0]);
                    $amatnr = array();
    
                    if(!empty($mitemc)) { 
                        $str = "select aa.recid,aa.ART_CODE from {$this->db_erp}.`mst_article` aa where ART_CODE = '$mitemc' ";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        $rw = $q->getRowArray(); 
                        array_push($ame,$mitemc); 
                        array_push($adatar1,$medata);
                    }
                }  
                
                if(count($adatar1) > 0) { 
                    for($xx = 0; $xx < count($adatar1); $xx++) { 
                        
                        $xdata = $adatar1[$xx];
                        $mitemc = $xdata[0];
                        $mdesc = $xdata[1];
                        $mqty = $xdata[2];
                        $mcost = $xdata[3];
                        $mtcost = $xdata[4];
                        $muom = $xdata[5];
    
                        $str="
                        INSERT INTO `mst_item_comp2` (
                            `icomp_trxno`,
                            `fg_code`,
                            `rm_code`,
                            `item_desc`,
                            `item_qty`,
                            `item_cost`,
                            `item_tcost`,
                            `item_uom`,
                            `comp_date`
                          )
                          VALUES
                            (
                              '$cseqn',
                              '$fg_code',
                              '$mitemc',
                              '$mdesc',
                              '$mqty',
                              '$mcost',
                              '$mtcost',
                              '$muom',
                              '$ucomp_date'
                            );
                        ";
                        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    } 
                    
                } 
            }
        }


        echo "<div class=\"alert alert-success mb-0 pb-1\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! Series No:{$cseqn} </div>
            <script type=\"text/javascript\"> 
                function __fg_refresh_data() { 
                    try { 
                        $('#iucomp_trxno').val('{$cseqn}');
                        jQuery('#mbtn_upld_Save').prop('disabled',true);
                    } catch(err) { 
                        var mtxt = 'There was an error on this page.\\n';
                        mtxt += 'Error description: ' + err.message;
                        mtxt += '\\nClick OK to continue.';
                        alert(mtxt);
                        return false;
                    }  //end try 
                } 
                
                __fg_refresh_data();
            </script>
            ";
            die();



         
    } //end item_comp_upld_entry_save_2

    public function item_comp_entry_update() {
        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");  
        $txt_fg_code = $this->request->getVar('txt_fg_code');
        $txt_fabric_code = $this->request->getVar('txt_fabric_code');
        $txt_fabric_qty = $this->request->getVar('txt_fabric_qty');
        $txt_lining_code = $this->request->getVar('txt_lining_code');
        $txt_lining_qty = $this->request->getVar('txt_lining_qty');
        $txt_btn_code = $this->request->getVar('txt_btn_code');
        $txt_btn_qty = $this->request->getVar('txt_btn_qty');
        $txt_rivets_code = $this->request->getVar('txt_rivets_code');
        $txt_rivets_qty = $this->request->getVar('txt_rivets_qty');
        $txt_leather_patch_code = $this->request->getVar('txt_leather_patch_code');
        $txt_leather_patch_qty = $this->request->getVar('txt_leather_patch_qty');
        $txt_plastic_btn_code = $this->request->getVar('txt_plastic_btn_code');
        $txt_plastic_btn_qty = $this->request->getVar('txt_plastic_btn_qty');
        $txt_inside_garter_code = $this->request->getVar('txt_inside_garter_code');
        $txt_inside_garter_qty = $this->request->getVar('txt_inside_garter_qty');
        $txt_hang_tag_code = $this->request->getVar('txt_hang_tag_code');
        $txt_hang_tag_qty = $this->request->getVar('txt_hang_tag_qty');   
        $txt_zipper_code = $this->request->getVar('txt_zipper_code');
        $txt_zipper_qty = $this->request->getVar('txt_zipper_qty');
        $txt_size_lbl_code = $this->request->getVar('txt_size_lbl_code');
        $txt_size_lbl_qty = $this->request->getVar('txt_size_lbl_qty');
        $txt_size_care_lbl_code = $this->request->getVar('txt_size_care_lbl_code');
        $txt_size_care_lbl_qty = $this->request->getVar('txt_size_care_lbl_qty');
        $txt_side_lbl_code = $this->request->getVar('txt_side_lbl_code');
        $txt_side_lbl_qty = $this->request->getVar('txt_side_lbl_qty');
        $txt_kids_lbl_code = $this->request->getVar('txt_kids_lbl_code');
        $txt_kids_lbl_qty = $this->request->getVar('txt_kids_lbl_qty');
        $txt_kids_side_lbl_code = $this->request->getVar('txt_kids_side_lbl_code');
        $txt_kids_side_lbl_qty = $this->request->getVar('txt_kids_side_lbl_qty');
        $txt_plastic_bag_code = $this->request->getVar('txt_plastic_bag_code');
        $txt_plastic_bag_qty = $this->request->getVar('txt_plastic_bag_qty');
        $txt_barcode_code = $this->request->getVar('txt_barcode_code');
        $txt_barcode_qty = $this->request->getVar('txt_barcode_qty');
        $txt_fitting_sticker_code = $this->request->getVar('txt_fitting_sticker_code');
        $txt_fitting_sticker_qty = $this->request->getVar('txt_fitting_sticker_qty');
        $txt_tag_pin_code = $this->request->getVar('txt_tag_pin_code');
        $txt_tag_pin_qty = $this->request->getVar('txt_tag_pin_qty');
        $txt_chip_board_code = $this->request->getVar('txt_chip_board_code');
        $txt_chip_board_qty = $this->request->getVar('txt_chip_board_qty');

    
        $str="
        UPDATE
            `mst_item_comp`
        SET
            `fabric_code` = '$txt_fabric_code',
            `fabric_qty` = '$txt_fabric_qty',
            `lining_code` = '$txt_lining_code',
            `lining_qty` = '$txt_lining_qty',
            `btn_code` = '$txt_btn_code',
            `btn_qty` = '$txt_btn_qty',
            `rivets_code` = '$txt_rivets_code',
            `rivets_qty` = '$txt_rivets_qty',
            `leather_patch_code` = '$txt_leather_patch_code',
            `leather_patch_qty` = '$txt_leather_patch_qty',
            `plastic_btn_code` = '$txt_plastic_btn_code',
            `plastic_btn_qty` = '$txt_plastic_btn_qty',
            `inside_garter_code` = '$txt_inside_garter_code',
            `inside_garter_qty` = '$txt_inside_garter_qty',
            `hang_tag_code` = '$txt_hang_tag_code',
            `hang_tag_qty` = '$txt_hang_tag_qty',
            `zipper_code` = '$txt_zipper_code',
            `zipper_qty` = '$txt_zipper_qty',
            `size_lbl_code` = '$txt_size_lbl_code',
            `size_lbl_qty` = '$txt_size_lbl_qty',
            `size_care_lbl_code` = '$txt_size_care_lbl_code',
            `size_care_lbl_qty` = '$txt_size_care_lbl_qty',
            `side_lbl_code` = '$txt_side_lbl_code',
            `side_lbl_qty` = '$txt_side_lbl_qty',
            `kids_lbl_code` = '$txt_kids_lbl_code',
            `kids_lbl_qty` = '$txt_kids_lbl_qty',
            `kids_side_lbl_code` = '$txt_kids_side_lbl_code',
            `kids_side_lbl_qty` = '$txt_kids_side_lbl_qty',
            `plastic_bag_code` = '$txt_plastic_bag_code',
            `plastic_bag_qty` = '$txt_plastic_bag_qty',
            `barcode_code` = '$txt_barcode_code',
            `barcode_qty` = '$txt_barcode_qty',
            `fitting_sticker_code` = '$txt_fitting_sticker_code',
            `fitting_sticker_qty` = '$txt_fitting_sticker_qty',
            `tag_pin_code` = '$txt_tag_pin_code',
            `tag_pin_qty` = '$txt_tag_pin_qty',
            `chip_board_code` = '$txt_chip_board_code',
            `chip_board_qty` = '$txt_chip_board_qty'
        WHERE `ART_CODE` = '$txt_fg_code'
        ";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  

        echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Updated Successfully</div>
                        <script type=\"text/javascript\"> 
                        function __fg_refresh_data() { 
                            try { 
                                
                                jQuery('#mbtn_mn_update').prop('disabled',true);
                                } catch(err) { 
                                    var mtxt = 'There was an error on this page.\\n';
                                    mtxt += 'Error description: ' + err.message;
                                    mtxt += '\\nClick OK to continue.';
                                    alert(mtxt);
                                    return false;
                                    }  //end try 
                                } 
                                
                                __fg_refresh_data();
                                </script>
                                ";  
         
    } //end item_comp_entry_update

    public function view_recs($npages = 1,$npagelimit = 30,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $strqry = "
        SELECT
        ART_CODE,
        CONCAT(a.`fabric_code`,'-',a.`fabric_qty`) AS fabric_code,
        CONCAT(a.`lining_code`,'-',a.`lining_qty`) AS lining_code,
        CONCAT(a.`btn_code`,'-',a.`btn_qty`) AS btn_code,
        CONCAT(a.`rivets_code`,'-',a.`rivets_qty`) AS rivets_code,
        CONCAT(a.`leather_patch_code`,'-',a.`leather_patch_qty`) AS leather_patch_code,
        CONCAT(a.`plastic_btn_code`,'-',a.`plastic_btn_qty`) AS plastic_btn_code,
        CONCAT(a.`inside_garter_code`,'-',a.`inside_garter_qty`) AS inside_garter_code,
        CONCAT(a.`hang_tag_code`,'-',a.`hang_tag_qty`) AS hang_tag_code,
        CONCAT(a.`zipper_code`,'-',a.`zipper_qty`) AS zipper_code,
        CONCAT(a.`size_lbl_code`,'-',a.`size_lbl_qty`) AS size_lbl_code,
        CONCAT(a.`size_care_lbl_code`,'-',a.`size_care_lbl_qty`) AS size_care_lbl_code,
        CONCAT(a.`side_lbl_code`,'-',a.`side_lbl_qty`) AS side_lbl_code,
        CONCAT(a.`kids_lbl_code`,'-',a.`kids_lbl_qty`) AS kids_lbl_code,
        CONCAT(a.`kids_side_lbl_code`,'-',a.`kids_side_lbl_qty`) AS kids_side_lbl_code,
        CONCAT(a.`plastic_bag_code`,'-',a.`plastic_bag_qty`) AS plastic_bag_code,
        CONCAT(a.`barcode_code`,'-',a.`barcode_qty`) AS barcode_code,
        CONCAT(a.`fitting_sticker_code`,'-',a.`fitting_sticker_qty`) AS fitting_sticker_code,
        CONCAT(a.`tag_pin_code`,'-',a.`tag_pin_qty`) AS tag_pin_code,
        CONCAT(a.`chip_board_code`,'-',a.`chip_board_qty`) AS chip_board_code
        FROM mst_item_comp a
        ";
        
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
   
        
        if($qry->getNumRows() > 0) { 
            $data['rlist'] = $qry->getResultArray();
            $data['response'] = true;
        } else { 
            $data = array();
            $data['rlist'] = '';
            $data['response'] = false;
        }
        return $data;
    } //end view_recs

    public function view_recs_2($npages = 1,$npagelimit = 30,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $strqry = "

        SELECT
            `icomp_trxno`,
            `fg_code`,
            `comp_date`
        FROM
            `mst_item_comp2`
        GROUP BY
            `icomp_trxno`
        ";
        
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
   
        
        if($qry->getNumRows() > 0) { 
            $data['rlist'] = $qry->getResultArray();
            $data['response'] = true;
        } else { 
            $data = array();
            $data['rlist'] = '';
            $data['response'] = false;
        }
        return $data;
    } //end view_recs_2

    public function item_comp_upld(){ 

        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $fg_code = $this->request->getVar('fg_code');
        $ucomp_date = $this->request->getVar('ucomp_date');

        $type = "";
        $insertSubTag = 0;
        $nrecs_pb     = 0;
        $invalidUnit  = 0 ;
        $tableName = '';
        
        $csv_file = "";
        $csv_ofile = "";
        $_csv_path = './itemcomp_upld/';
        $_csv_upath = './itemcomp_upld/';
        $_csv_pubpath = './uploads/itemcomp_upld/';

        $str="
        SELECT fg_code FROM mst_item_comp2 WHERE fg_code = '$fg_code';
        ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        if($qry->resultID->num_rows > 0) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error: </strong>Rawmats for this FG item is already existing.</div>";
            die();
        }else{

         $this->validate([
                'userfile' => 'uploaded[userfile]|max_size[userfile,100]'
                               . '|mime_in[userfile,text/x-comma-separated-values, text/comma-separated-values, application/octet-stream, application/vnd.ms-excel,application/x-csv,text/x-csv,text/csv,application/csv,application/excel,application/vnd.msexcel,text/plain]'
                               . '|ext_in[userfile,csv,xls,text,txt,xlsx]|max_dims[userfile,1024,768]',
            ]);
    
            if(!is_dir($_csv_pubpath)) mkdir($_csv_pubpath, '0755', true);
            $file = $this->request->getFile('rcv_file');

            $file->move($_csv_pubpath,$file->getName());
    
            if(! $file->hasMoved())
            {
                echo "Error File Uploading/Process";
                die();
            }
            
            $csv_file  = $file->getName();
            $csv_ofile = $file->getName();
            $tbltemp   = $this->db_erp . ".`itemcomp_upld_temp_" . $this->mylibzsys->random_string(15) . "`";
    
            $str = "drop table if exists {$tbltemp}";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $cfile = $_csv_pubpath . $csv_file;
            //create temp table 
            $str = "
            CREATE table {$tbltemp} ( 
            `recid` int(25) NOT NULL AUTO_INCREMENT,
            ART_CODE varchar(35) default '',
            ART_QTY varchar(15) default '',
            PRIMARY KEY (`recid`)
            )
    
            ";
    
    
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
             //create temp table end
        
    
            $str = "
            LOAD DATA LOCAL INFILE '$cfile' INTO TABLE {$tbltemp} 
            FIELDS TERMINATED BY '\t' 
              LINES TERMINATED BY '\n' 
             
            (
            ART_CODE,ART_QTY
            ) 
             ";         
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
            $str = "SELECT count(*) __nrecs from {$tbltemp}";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() == 0){ 
                $str = "
                LOAD DATA LOCAL INFILE '$cfile' INTO TABLE {$tbltemp} 
                FIELDS TERMINATED BY '\t' 
                  LINES TERMINATED BY '\r\n' 
                 
                (
                ART_CODE,ART_QTY
                ) 
                 ";         
                $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                
                $str = "SELECT count(*) __nrecs from {$tbltemp}";
                $qq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                if($qq->getNumRows() == 0) { 
                    $str = "
                    LOAD DATA LOCAL INFILE '$cfile' INTO TABLE {$tbltemp} 
                    FIELDS TERMINATED BY '\t' 
                      LINES TERMINATED BY '\r' 
                     
                    (
                    ART_CODE,ART_QTY
                    ) 
                     "; 
                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                }
                $qq->freeResult();
                
                
            }
            $q->freeResult();
            
            
            $str = "SELECT count(*) __nrecs from {$tbltemp}";
    
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $rw = $q->getRowArray();
            $nrecs = $rw['__nrecs'];
            $q->freeResult();
    
      
            $str = "UPDATE {$tbltemp} SET 
            ART_CODE  = TRIM(ART_CODE),
            ART_QTY  = TRIM(ART_QTY)

            ";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
            $str = "insert into temp_upld (
                ART_CODE,
                ART_QTY
                ) SELECT 
                ART_CODE,
                ART_QTY
                FROM {$tbltemp} 
                
                ";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
    
            $sep = '"\'"';
            $str = "SELECT GROUP_CONCAT({$sep},`ART_CODE`,{$sep}) ART_CODE from  {$tbltemp} ";
            $itemq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $item = $itemq->getRowArray();
            $item_code_list = $item['ART_CODE'];
    
            
            //get items
            $str_itm = "SELECT
                    a.`ART_CODE`,
                    b.`ART_DESC`,
                    a.`ART_QTY`,
                    b.`ART_UPRICE`,
                    b.`ART_UOM`
    
                FROM
                    {$tbltemp} a
                JOIN 
                    mst_article b
                ON
                    REPLACE(REPLACE(REPLACE(a.`ART_CODE`, ' ', ''), '\t', ''), '\n', '') = b.`ART_CODE`
                ";
    
            $q3 = $this->mylibzdb->myoa_sql_exec($str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
            if($q3->getNumRows() > 0 )
            {
    
                $data['result'] = $q3->getResultArray();
                $data['count'] = count($q3->getResultArray());
    
            }
            else
            {
    
                $data['result'] = '';
                 $data['count']  = 0;
    
            }
            $data['response'] = true;
            return $data;
        }
    } //end item_comp_upld
   
}