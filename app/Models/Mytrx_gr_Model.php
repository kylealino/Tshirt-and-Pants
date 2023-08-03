<?php
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Files\File;

class Mytrx_gr_Model extends Model
{
	
    public function __construct()
    {
        parent::__construct();
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(1);
        $this->db_temp = $this->mydbname->medb(3);
        $this->mylibzdb = model('App\Models\MyLibzDBModel');
        $this->mylibzsys = model('App\Models\MyLibzSysModel');
        $this->mymelibzsys = model('App\Models\Mymelibsys_model');
        $this->mydataz = model('App\Models\MyDatumModel');
        $this->mydatazua = model('App\Models\MyDatauaModel');
        $this->dbx = $this->mylibzdb->dbx;
        $this->request = \Config\Services::request();
    }


    public function view_recs($npages = 1,$npagelimit = 30,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();


        //PARA SA MGA ADMINSITRATOR LANG
        $cusergrp = $this->mylibzdb->mysys_usergrp();
        /*if($cusergrp != 'SA'){
            $data = "</br><div class=\"col-md-3 alert alert-warning\"><strong>Note:</strong><br>Only administrative users can view this records.</div>";
            echo $data;
            $data = array();
            $data['npage_count'] = 1;
            $data['npage_curr'] = 1;
            $data['rlist'] = '';
            return $data;
        }*/



        $__flag="C";
        $str_optn = "";
        //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
        $str_vwrecs = "AND aa.`muser` = '$cuser'";
        if($cusergrp == 'SA'){
            $str_vwrecs = "";
        }
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " AND (aa.`grtrx_no` like '%$msearchrec%' OR aa.`muser` like '%$msearchrec%' OR ee.`wshe_code` like '%$msearchrec%' OR bb.`COMP_NAME` like '%$msearchrec%') AND aa.flag != '$__flag' {$str_vwrecs}";
        }
        if(empty($msearchrec)) {
            $str_optn = " AND aa.flag != '$__flag' {$str_vwrecs}";
        } 

       
        $strqry = "
        SELECT aa.*,
        bb.`COMP_NAME`,
        dd.`plnt_code`,
        ee.`wshe_code`,
        sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) mtkn_arttr 
        FROM {$this->db_erp}.`trx_wshe_gr_hd` aa
        JOIN {$this->db_erp}.`mst_company` bb ON (aa.`comp_id` = bb.`recid`)
        JOIN {$this->db_erp}.`mst_plant` dd ON (aa.`plant_id` = dd.`recid`)
        JOIN {$this->db_erp}.`mst_wshe` ee ON (aa.`wshe_id` = ee.`recid`)
        WHERE aa.`cd_tag` = 'Y'
        {$str_optn}
        order by recid desc
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
    } 

    public function grsave(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $cuserrema=$this->mylibzdb->mysys_userrema();
        $mat_paste_tag = 0;
        $trxno = $this->request->getVar('trxno_id');
        
        //$this->dbx->escapeString($this->request->getVar('fld_txtgrtrx_no'));//systemgenfld_dftag
        $fld_Company_gr =  $this->dbx->escapeString($this->request->getVar('fld_Company_gr'));//GET id
        $fld_plant = $this->dbx->escapeString($this->request->getVar('fld_plant'));//GET id
        $fld_wshe = $this->dbx->escapeString($this->request->getVar('fld_wshe'));//GET id
        $fld_rack = $this->dbx->escapeString($this->request->getVar('fld_rack'));//GET id
        $fld_bin = $this->dbx->escapeString($this->request->getVar('fld_bin'));//GET id
        //var_dump($fld_bin);
        //die();
        $fld_dftag ='F';
        $fld_refno = $this->request->getVar('fld_refno');
        $fld_grtyp = $this->request->getVar('fld_grtyp');
        $fld_grdate = $this->mylibzsys->mydate_yyyymmdd($this->request->getVar('fld_grdate'));
        $fld_rems = $this->request->getVar('fld_rems');
        $ischck = $this->request->getVar('ischck');

        $fld_grclass= $this->request->getVar('fld_grclass');
        //var_dump($fld_grtyp);
        //die();
        //this is for branch tag
        /*$fld_dftag_temp  = $this->dbx->escapeString($this->request->getVar('fld_dftag'));
        $fld_dftag_r = (empty($fld_dftag_temp) ? 'F' : $fld_dftag_temp);
        */
        //(($cuserrema ==='B') ? 'D': $fld_dftag_r);
        
        /*$fld_subtqty = $this->dbx->escapeString(str_replace(',','',$this->request->getVar('fld_subtqty')));
        $fld_subtcost = $this->dbx->escapeString(str_replace(',','',$this->request->getVar('fld_subtcost')));
        $fld_subtamt = $this->dbx->escapeString(str_replace(',','',$this->request->getVar('fld_subtamt')));*/
          
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');
       
        $mmn_rid = '';
        $fld_txtgrtrx_no = '';
        
    

        //COMPANY
        $compData = $this->mymelibzsys->getCompany_data($fld_Company_gr);
        $fld_Company_gr = $compData['recid'];
        //END COMPANY

        $wsheData = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($fld_wshe);
        $fld_plant = $wsheData['plntID'];
        $fld_wshe = $wsheData['whID'];

        $sbinData = $this->mymelibzsys->getWhBinDetailsByTkn($fld_bin,'Y');
 
        $fld_rack = $sbinData['wshegrp_id'];
        $fld_bin = $sbinData['recid'];
     
        //CHECK IF VALID PO
        if(!empty($trxno)){ 
            $str = "select aa.recid,aa.grtrx_no from {$this->db_erp}.`trx_wshe_gr_hd` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$trxno' ";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Transaction DATA!!!.</div>";
                die();
            }
            $rw = $q->getRowArray();
            $mmn_rid  = $rw['recid'];
            $fld_txtgrtrx_no = $rw['grtrx_no'];
            $q->freeResult();
        } //END CHECK IF VALID PO

        //GENERATE NEW PO CTRL NO
        else{ 
            $fld_txtgrtrx_no =  "CWGR" .$this->mydataz->get_ctr($this->db_erp,'GR_CTR');//TRANSACTION NO
            } //end mtkn_potr

        //ITEM
        if(empty($adata1)){ 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No Item Data!!!.</div>";
            die();
        }
        //ITEM VALIDATION END

        if(count($adata1) > 0){ 
            $ame = array();
            $adatar1 = array();
            $adatar2 = array();
            $ntqty = 0;
            $ntamt = 0;
            $ntcost = 0;
    
            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);
                $mat_mtkn = $adata2[$aa];
                $fld_mitemcode = $this->dbx->escapeString(trim($medata[0]));
                $fld_mitemdesc = $this->dbx->escapeString(trim($medata[1]));
                $fld_mitempkg = $this->dbx->escapeString(trim($medata[2]));
                $fld_ucost = (empty(str_replace(',','',$medata[3])) ? 0 : (str_replace(',','',$medata[3]) + 0));
                $fld_mitemtcost = (empty(str_replace(',','',$medata[4])) ? 0 : (str_replace(',','',$medata[4]) + 0));
                $fld_srp =  (empty(str_replace(',','',$medata[5])) ? 0 : (str_replace(',','',$medata[5]) + 0));
                $fld_mitemtamt =(empty(str_replace(',','',$medata[6])) ? 0 : (str_replace(',','',$medata[6]) + 0));
                $fld_mitemqty = (empty(str_replace(',','',$medata[7])) ? 0 : (str_replace(',','',$medata[7]) + 0));
                //$fld_mitemqtyc = (empty($medata[7]) ? 0 : ($medata[7] + 0));
                $fld_remks = $this->dbx->escapeString(trim($medata[8]));

                $fld_iitemcode = $this->dbx->escapeString(trim($medata[11]));
                $fld_iqty = (empty(str_replace(',','',$medata[12])) ? 0 : (str_replace(',','',$medata[12]) + 0));
                $fld_iconvf =(empty(str_replace(',','',$medata[13])) ? 0 : (str_replace(',','',$medata[13]) + 0));
                $fld_imndt_rid = $this->dbx->escapeString(trim($medata[14]));
                
                $fld_aconvf = $this->dbx->escapeString(trim($medata[15]));
                $fld_actdmg = $this->dbx->escapeString(trim($medata[16]));
                $fld_actlck = $this->dbx->escapeString(trim($medata[17]));
                //COMPUTATION ON SAVING
                $fld_mitemtcost = ($fld_iqty * $fld_mitemqty * $fld_ucost);
                $fld_mitemtamt =($fld_iqty * $fld_mitemqty * $fld_srp);
                
                $ntqty = $ntqty + $fld_mitemqty;//actual hd_subtqty
                $ntcost = $ntcost + $fld_mitemtcost;//actual hd_subtcost
                $ntamt = $ntamt + $fld_mitemtamt;//actual hd_subtamt
                
                //GETTING THE GRAND TOTAL HD
                $fld_subtqty = $this->dbx->escapeString(str_replace(',','',$ntqty));
                $fld_subtcost = $this->dbx->escapeString(str_replace(',','',$ntcost));
                $fld_subtamt = $this->dbx->escapeString(str_replace(',','',$ntamt));
                //$total_pcs = $nconvf*$nqty;
                //$cmat_code = $this->dbx->escapeString(trim($medata[0])) . $mktn_plnt_id . $mtkn_wshe_id;

                $amatnr = array();
                if(!empty($fld_mitemcode)) { 
                    $str = "select aa.recid,aa.ART_CODE,aa.ART_DESC,aa.ART_UCOST,aa.ART_UPRICE,aa.ART_SKU from {$this->db_erp}.`mst_article` aa where aa.`ART_CODE` = '$fld_mitemcode' ";//sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mat_mtkn' and 
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    if($q->getNumRows() == 0) { 
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Item Data!!!<br/>[$fld_mitemcode]</div>";
                        die();
                    } else {
                        $rw = $q->getRowArray();
                        //PARA ITO SA PASTE ITEMCODE LANG
                        //if(empty($mat_mtkn)) { //KAPAG NAGSELECT
                        
                        //} //end if(!empty($mat_mtkn)) { 
                        if(empty($mat_mtkn)) { //KAPAG PASTE LANG
                            $mat_paste_tag = 1;
                            //$mmat_rid_tmp = $rw['recid'];
                            $fld_mitemcode = $rw['ART_CODE'];
                            $fld_mitemdesc = $rw['ART_DESC'];
                            $fld_ucost =$rw['ART_UCOST'];
                            $fld_srp =  $rw['ART_UPRICE'];
                            $fld_mitempkg = $rw['ART_SKU'];
                            
                            //COMPUTATION ON SAVING
                            $fld_mitemtcost = ($fld_mitemqty * $fld_ucost);
                            $fld_mitemtamt =($fld_mitemqty * $fld_srp);
                            
                            
                            $ntqty = $ntqty + $fld_mitemqty;//actual hd_subtqty
                            $ntcost = $ntcost + $fld_mitemtcost;//actual hd_subtcost
                            $ntamt = $ntamt + $fld_mitemtamt;//actual hd_subtamt
                            
                            //GETTING THE GRAND TOTAL HD
                            $fld_subtqty = $this->dbx->escapeString(str_replace(',','',$ntqty));
                            $fld_subtcost = $this->dbx->escapeString(str_replace(',','',$ntcost));
                            $fld_subtamt = $this->dbx->escapeString(str_replace(',','',$ntamt));
                            $_paste_itemcode = $fld_mitemcode . 'x|x' . $fld_mitemdesc . 'x|x' . $fld_mitempkg . 'x|x' .$fld_ucost . 'x|x' .$fld_mitemtcost . 'x|x' .$fld_srp . 'x|x' .$fld_mitemtamt . 'x|x' . $fld_mitemqty . 'x|x' .$fld_remks . 'x|x' .$fld_iitemcode . 'x|x' .$fld_iqty  . 'x|x' .$fld_iconvf  . 'x|x' .$fld_imndt_rid . 'x|x' . $fld_aconvf . 'x|x' . $fld_actdmg . 'x|x' . $fld_actlck . 'x|x';
                            $medata = explode("x|x",$_paste_itemcode);
                        }
                        //var_dump($fld_grtyp);
                        //die();
                        //VALIDATION OF ITEMS,QTY,PRICE
                        //if(in_array($cmat_code,$ame)) { 
                        if(!empty($fld_iitemcode)) { 
                            $str = "select aa.recid from {$this->db_erp}.`mst_article` aa where aa.`ART_CODE` = '$fld_iitemcode' ";//sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mat_mtkn' and 
                            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            if($q->getNumRows() == 0) { 
                                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Item ItemCode Please reselect the item!!!<br/>[$fld_iitemcode]</div>";
                                die();
                            }
                            $rw2 = $q->getRowArray();
                            $fld_imndt_rid = $rw2['recid']; 
                        }
                        
                        if(in_array($fld_mitemcode,$ame)) { 
                            if($ischck == 'N'){
                                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Item Code Data already exists [$fld_mitemcode]</div>";
                                die();
                            }
                        } else { 
                            if(($fld_grtyp != '3') && ($fld_mitemqty == 0 || $fld_mitemtamt == 0)) { 
                                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid QTY or Price entries [$fld_mitemcode]!!!</div>";
                                die();
                            }
                            
                        }
                        if(($fld_grtyp == '3') && ($fld_aconvf == 0) && ($fld_actdmg == 0) && ($fld_actlck == 0)){
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Actual Item Convf Goods, please remove if the qty is ZERO [$fld_mitemcode]!!!</div>";
                            die();

                        }
                        
                        //$rw = $q->getRowArray();
                        $mmat_rid = $rw['recid'];
                        //array_push($ame,$cmat_code); 
                        array_push($ame,$fld_mitemcode); 
                        array_push($adatar1,$medata);
                        array_push($adatar2,$mmat_rid);
                        /*$ntqty = ($ntqty + $nqty);*/
                        //$ntamt = ($ntamt + ($nprice * $nconvf * $nqty));
                        //$ntamt = ($ntamt + ($tamt));
                    }

                    $q->freeResult();
                }
                

            }  //end for 
        
            if(count($adatar1) > 0) { 
                if(!empty($trxno)) { 
                    //DR bAKA MAGAKATAON NA MAY MAGAKAIBANG SUP NA PAREHAS ANG DR
                    /*$str = "select aa.`dr_no` from {$this->db_erp}.`trx_wshe_gr_hd` aa where aa.`dr_no` = '$fld_grno' AND aa.`branch_id` = '$fld_area_code_dr' AND !(aa.`flag`='C') AND !(sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) = '$trxno')";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    if($q->getNumRows() > 0) { 
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> DR No already exists.!!!.[".$fld_grno."]</div>";
                        die();
                    }*/

                    if(!empty($fld_refno)){
                        $str = " SELECT `recid` FROM {$this->db_erp}.`trx_wshe_gr_hd` WHERE `ref_no` = '{$fld_refno}' AND !(sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$trxno')";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                        if($q->getNumRows() > 0) { 
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> GR Ref No Already Exist!!!.</div>";
                            die();
                        }
                    }





                    $str = "
                    UPDATE {$this->db_erp}.`trx_wshe_gr_hd`
                    SET `comp_id` = '$fld_Company_gr',
                        `gr_date` = '$fld_grdate',
                        `plant_id` = '$fld_plant',
                        `wshe_id` = '$fld_wshe',
                        `rack_id` = '$fld_rack',
                        `bin_id` = '$fld_bin',
                        `ref_no` = '$fld_refno',
                        `hd_subtqty`='$fld_subtqty',
                        `hd_subtcost`='$fld_subtcost',
                        `hd_subtamt`='$fld_subtamt',
                        `remk` = '$fld_rems',
                        `grtype_id`='$fld_grtyp',
                        `class_id`='$fld_grclass',
                        `is_asstd` = '$ischck'
                    WHERE `recid` = '$mmn_rid';
                    ";
                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'MN_GR_UREC','',$fld_txtgrtrx_no,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        

                } else { 
                    //PO bAKA MAGAKATAON NA MAY MAGAKAIBANG SUP NA PAREHAS ANG DR
                    /*$str = "select aa.`dr_no` from {$this->db_erp}.`trx_wshe_gr_hd` aa where aa.`dr_no` = '$fld_grno' AND aa.`branch_id` = '$fld_area_code_dr' AND !(aa.`flag`='C')";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    if($q->getNumRows() > 0) { 
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> DR No already exists.!!!.[".$fld_grno."]</div>";
                        die();
                    }*/

                    $str = "insert into {$this->db_erp}.`trx_wshe_gr_hd`
                    (`grtrx_no`,
                     `gr_date`,
                     `comp_id`,
                     `plant_id`,
                     `wshe_id`,
                     `rack_id`,
                     `bin_id`,
                     `grtype_id`,
                     `class_id`,
                     `ref_no`,
                     `hd_subtqty`,
                     `hd_subtcost`,
                     `hd_subtamt`,
                     `muser`,
                     `encd_date`,
                     `df_tag`,
                     `remk`,
                     `is_asstd`,
                    `cd_tag`
                     )
                    VALUES (
                    '$fld_txtgrtrx_no',
                    '$fld_grdate',
                    '$fld_Company_gr',
                    '$fld_plant',
                    '$fld_wshe',
                    '$fld_rack',
                    '$fld_bin',
                    '$fld_grtyp',
                    '$fld_grclass',
                    '$fld_refno',
                    '$fld_subtqty',
                    '$fld_subtcost',
                    '$fld_subtamt',
                    '$cuser',
                     now(),
                    '$fld_dftag',
                    '$fld_rems',
                    '$ischck',
                    'Y'
                    )";
                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'MN_GR_AREC','',$fld_txtgrtrx_no,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $str = "select recid,sha2(concat(aa.recid,'{$mpw_tkn}'),384) mtkn_mntr from {$this->db_erp}.`trx_wshe_gr_hd` aa where `grtrx_no` = '$fld_txtgrtrx_no' ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $rw = $q->getRowArray();
                    $mmn_rid = $rw['recid'];
                    //var_dump($mmn_rid);
                    $__hmtkn_mntr = $rw['mtkn_mntr'];
                    $q->freeResult();


                }

                //GET PLNT, WSHE, SBIN

                for($xx = 0; $xx < count($adatar1); $xx++) {  //MAY MALI DITO
        
                    $xdata = $adatar1[$xx];
                    $mat_rid = $adatar2[$xx];
                    
                    //$fld_mitemrid = $this->dbx->escapeString(trim($xdata[0]));
                    $fld_mitemcode = $xdata[0];
                    $fld_mitemdesc = $this->dbx->escapeString(trim($xdata[1]));
                    $fld_mitempkg = $this->dbx->escapeString(trim($xdata[2]));
                    $fld_ucost = (empty(str_replace(',','',$xdata[3])) ? 0 : (str_replace(',','',$xdata[3]) + 0));
                    $fld_mitemtcost = (empty(str_replace(',','',$xdata[4])) ? 0 : (str_replace(',','',$xdata[4]) + 0));
                    $fld_srp =  (empty(str_replace(',','',$xdata[5])) ? 0 : (str_replace(',','',$xdata[5]) + 0));
                    $fld_mitemtamt =(empty(str_replace(',','',$xdata[6])) ? 0 : (str_replace(',','',$xdata[6]) + 0));
                    $fld_mitemqty = (empty(str_replace(',','',$xdata[7])) ? 0 : (str_replace(',','',$xdata[7]) + 0));
                    //$fld_mitemqty = (empty($xdata[7]) ? 0 : ($xdata[7] + 0));
                    $fld_remks = $this->dbx->escapeString(trim($xdata[8]));
                    //$fld_olt = $this->dbx->escapeString(trim($xdata[9]));
                    $mndt_rid = $this->dbx->escapeString(trim($xdata[9]));//dt mn id
                    $fld_gr_rson = "";//$this->dbx->escapeString(trim($xdata[10]));
                    
                    $fld_iitemcode = $this->dbx->escapeString(trim($xdata[11]));
                    $fld_iqty =(empty(str_replace(',','',$xdata[12])) ? 0 : (str_replace(',','',$xdata[12]) + 0));
                    $fld_iconvf =(empty(str_replace(',','',$xdata[13])) ? 0 : (str_replace(',','',$xdata[13]) + 0));
                    $fld_imndt_rid = $this->dbx->escapeString(trim($xdata[14]));
                    $fld_aconvf = $this->dbx->escapeString(trim($xdata[15]));
                    $fld_actdmg = $this->dbx->escapeString(trim($xdata[16]));
                    $fld_actlck = $this->dbx->escapeString(trim($xdata[17]));

                    //COMPUTATION ON SAVING
                    $fld_mitemtcost = ($fld_iqty * $fld_mitemqty * $fld_ucost);
                    $fld_mitemtamt =($fld_iqty * $fld_mitemqty * $fld_srp);
            
                    //  $tamt = $xdata[7];
                    if(!empty($fld_iitemcode)) { 
                        $str = "select aa.recid from {$this->db_erp}.`mst_article` aa where aa.`ART_CODE` = '$fld_iitemcode' ";//sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mat_mtkn' and 
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        if($q->getNumRows() == 0) { 
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Item ItemCode Please reselect the item!!!<br/>[$fld_iitemcode]</div>";
                            die();
                        }
                        $rw2 = $q->getRowArray();
                        $fld_imndt_rid = $rw2['recid']; 
                    }
                    
                    
                    if(empty($trxno)) {  
                        
                        /*$str = "select recid from {$this->db_erp}.`trx_wshe_gr_dt` where `grtrx_no` = '$fld_txtgrtrx_no' and `mat_rid` = '$mat_rid'";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        if($q->getNumRows() > 0 ) { 
                            $rw = $q->getRowArray();
                            $mndt_rid = $rw['recid'];

                            $str = "update {$this->db_erp}.`trx_wshe_gr_dt`
                            SET `mat_rid` = '$mat_rid',
                              `mat_code` = '$fld_mitemcode',
                              `qty` = '$fld_mitemqty',
                              `ucost` = '$fld_ucost',
                              `tcost` = '$fld_mitemtcost',
                              `uprice` = '$fld_srp',
                              `tamt` = '$fld_mitemtamt',
                              `nremarks` = '$fld_remks',
                              `imat_rid` = '$fld_imndt_rid',
                              `imat_code` = '$fld_iitemcode',
                              `imat_convf` = '$fld_iconvf',
                              `imat_qty` = '$fld_iqty',
                              `muser` = '$cuser'
                            WHERE `recid` = '$mndt_rid'
                            ";
                        } else {*/ 
                            $str = "insert into {$this->db_erp}.`trx_wshe_gr_dt`
                            (`grhd_rid`,
                            `grtrx_no`,
                            `mat_rid`,
                            `mat_code`,
                            `ucost`,
                            `tcost`,
                            `uprice`,
                            `tamt`,
                            `qty`,
                            `nremarks`,
                            `imat_rid`,
                            `imat_code`,
                            `imat_convf`,
                            `imat_qty`,
                            `amat_convf`,
                            `amat_dmg`,
                            `amat_lck`,
                            `muser`)
                            VALUES ('$mmn_rid',
                            '$fld_txtgrtrx_no',
                            '$mat_rid',
                            '$fld_mitemcode',
                            '$fld_ucost',
                            '$fld_mitemtcost',
                            '$fld_srp',
                            '$fld_mitemtamt',
                            '$fld_mitemqty',
                            '$fld_remks',
                            '$fld_imndt_rid',
                            '$fld_iitemcode',
                            '$fld_iconvf',
                            '$fld_iqty',
                            '$fld_aconvf',
                            '$fld_actdmg',
                            '$fld_actlck',
                            '$cuser')
                            ";
                        //}
                        $q->freeResult();
                        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        $this->mylibzdb->user_logs_activity_module($this->db_erp,'TRX_GR_DT','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        //var_dump($str);
                        //die();
                        
                        
                        
                        
                        
                    } else { 
                        if(empty($mndt_rid)){ 
                            /*$str = "select recid from {$this->db_erp}.`trx_wshe_gr_dt` where `grtrx_no` = '$fld_txtgrtrx_no' and sha2(concat(recid,'{$mpw_tkn}'),384) = '$mndt_rid'";
                            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                             if($q->getNumRows() > 0 ) { 
                                    $rw = $q->getRowArray();
                                    $mn_rid = $rw['recid'];
                                    $str = "update {$this->db_erp}.`trx_wshe_gr_dt`
                                        SET `mat_rid` = '$mat_rid',
                                          `mat_code` = '$fld_mitemcode',
                                          `qty` = '$fld_mitemqty',
                                          `ucost` = '$fld_ucost',
                                          `tcost` = '$fld_mitemtcost',
                                          `uprice` = '$fld_srp',
                                          `tamt` = '$fld_mitemtamt',
                                          `nremarks` = '$fld_remks',
                                          `imat_rid` = '$fld_imndt_rid',
                                          `imat_code` = '$fld_iitemcode',
                                          `imat_convf` = '$fld_iconvf',
                                          `imat_qty` = '$fld_iqty',
                                          `muser` = '$cuser'
                                        WHERE `recid` = '$mndt_rid'
                                        ";
                                } else { */
                                    $str = "insert into {$this->db_erp}.`trx_wshe_gr_dt`
                                    (`grhd_rid`,
                                    `grtrx_no`,
                                    `mat_rid`,
                                    `mat_code`,
                                    `ucost`,
                                    `tcost`,
                                    `uprice`,
                                    `tamt`,
                                    `qty`,
                                    `nremarks`,
                                    `imat_rid`,
                                    `imat_code`,
                                    `imat_convf`,
                                    `imat_qty`,
                                    `amat_convf`,
                                    `amat_dmg`,
                                    `amat_lck`,
                                    `muser`)
                                    VALUES ('$mmn_rid',
                                    '$fld_txtgrtrx_no',
                                    '$mat_rid',
                                    '$fld_mitemcode',
                                    '$fld_ucost',
                                    '$fld_mitemtcost',
                                    '$fld_srp',
                                    '$fld_mitemtamt',
                                    '$fld_mitemqty',
                                    '$fld_remks',
                                    '$fld_imndt_rid',
                                    '$fld_iitemcode',
                                    '$fld_iconvf',
                                    '$fld_iqty',
                                    '$fld_aconvf',
                                    '$fld_actdmg',
                                    '$fld_actlck',
                                    '$cuser')
                                    ";
                                    //}
                                    $q->freeResult();
                                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'trx_dr_dt','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                
                            
                        } else { // end empty podt_rid */
                            $str = "select recid from {$this->db_erp}.`trx_wshe_gr_dt` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mndt_rid'";
                            $qq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            if($qq->getNumRows() > 0) { 
                                $rrw = $qq->getRowArray();
                                $mndt_rid = $rrw['recid'];
                                $str = "
                                UPDATE {$this->db_erp}.`trx_wshe_gr_dt`
                                        SET `mat_rid` = '$mat_rid',
                                          `mat_code` = '$fld_mitemcode',
                                          `qty` = '$fld_mitemqty',
                                          `ucost` = '$fld_ucost',
                                          `tcost` = '$fld_mitemtcost',
                                          `uprice` = '$fld_srp',
                                          `tamt` = '$fld_mitemtamt',
                                          `nremarks` = '$fld_remks',
                                          `imat_rid` = '$fld_imndt_rid',
                                          `imat_code` = '$fld_iitemcode',
                                          `imat_convf` = '$fld_iconvf',
                                          `imat_qty` = '$fld_iqty',
                                           `amat_convf` = '$fld_aconvf',
                                          `amat_dmg` = '$fld_actdmg',
                                          `amat_lck` = '$fld_actlck',
                                          `muser` = '$cuser'
                                        WHERE `recid` = '$mndt_rid'";
                                $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                $this->mylibzdb->user_logs_activity_module($this->db_erp,'TRX_GR_UPD_DT','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
                            }
                            $qq->freeResult();

                            

                            
                        }  //end 
                        
                    }
                    
                    
                }  //end for 


                
                //record on AV Work Flow
    //$qry->freeResult();  
                if(empty($trxno)) {
                    //PARA SA ENCODING PANGCHECK NG DUPLICATE IETMCODE
                    $str = "drop table if exists {$this->db_temp}.`isduplicate_itemc_logs`"; 
                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Data Recorded Successfully!!!</div>
                    <script type=\"text/javascript\"> 
                        function __dr_refresh_data() { 
                            try { 
                                $('#__hmtkn_trxnoid').val('{$__hmtkn_mntr}');
                                $('#txtgrtrx_no').val('{$fld_txtgrtrx_no}');
                                $('#mbtn_mn_Save').prop('disabled',true);
                            } catch(err) { 
                                var mtxt = 'There was an error on this page.\\n';
                                mtxt += 'Error description: ' + err.message;
                                mtxt += '\\nClick OK to continue.';
                                alert(mtxt);
                                return false;
                            }  //end try 
                        } 
                        
                        __dr_refresh_data();
                    </script>
                    ";
                    die();
                } else { 
                    //PARA SA ENCODING PANGCHECK NG DUPLICATE IETMCODE
                    //$str = "drop table if exists {$this->db_temp}.`isduplicate_itemc_logs`"; 
                    //$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Data Changes Successfully RECORDED!!!</div>
                    ";
                    die();
                }
            } else { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No VALID Item Data!!!.</div>";
                die();
            } //end if 
        } else { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Item Data!!!.</div>";
            die();
        }
            
    }


    public function save_nontrade() { 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $cuserrema=$this->mylibzdb->mysys_userrema();
        $mat_paste_tag = 0;
        $trxno = $this->request->getVar('trxno_id');
        
        //$this->dbx->escapeString($this->request->getVar('fld_txtgrtrx_no'));//systemgenfld_dftag

        $fld_Company_gr =  $this->dbx->escapeString($this->request->getVar('fld_Company_gr'));//GET id
        $fld_plant = $this->dbx->escapeString($this->request->getVar('fld_plant'));//GET id
        $fld_wshe = $this->dbx->escapeString($this->request->getVar('fld_wshe'));//GET id

        $fld_rack = $this->dbx->escapeString($this->request->getVar('fld_rack'));//GET id
        $fld_bin = $this->dbx->escapeString($this->request->getVar('fld_bin'));//GET id
        //var_dump($fld_bin);
        //die();
        $fld_dftag ='F';
        $fld_refno = $this->request->getVar('fld_refno');
        $fld_grtyp = $this->request->getVar('fld_grtyp');
        $fld_grdate = $this->mylibzdb->mydate_yyyymmdd($this->request->getVar('fld_grdate'));
        $fld_rems = $this->request->getVar('fld_rems');
        $ischck = $this->request->getVar('ischck');

        $fld_grclass= $this->request->getVar('fld_grclass');
        //var_dump($fld_grtyp);
        //die();
        //this is for branch tag
        /*$fld_dftag_temp  = $this->dbx->escapeString($this->request->getVar('fld_dftag'));
        $fld_dftag_r = (empty($fld_dftag_temp) ? 'F' : $fld_dftag_temp);
        */
        //(($cuserrema ==='B') ? 'D': $fld_dftag_r);
        
        /*$fld_subtqty = $this->dbx->escapeString(str_replace(',','',$this->request->getVar('fld_subtqty')));
        $fld_subtcost = $this->dbx->escapeString(str_replace(',','',$this->request->getVar('fld_subtcost')));
        $fld_subtamt = $this->dbx->escapeString(str_replace(',','',$this->request->getVar('fld_subtamt')));*/
        
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');
    
        $mmn_rid = '';
        $fld_txtgrtrx_no = '';
        
        
        
        //COMPANY
        $compData = $this->mymelibzsys->getCompany_data($fld_Company_gr);
        $fld_Company_gr = $compData['recid'];
        //END COMPANY

        $wsheData = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($fld_wshe);
        $fld_plant = $wsheData['plntID'];
        $fld_wshe = $wsheData['whID'];

        $sbinData = $this->mymelibzsys->getWhBinDetailsByTkn($fld_bin,'Y');
 
        $fld_rack = $sbinData['wshegrp_id'];
        $fld_bin = $sbinData['recid'];

        //CHECK IF VALID PO
        if(!empty($trxno)) { 
            $str = "select aa.recid,aa.grtrx_no from {$this->db_erp}.`trx_wshe_gr_hd` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$trxno' ";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Transaction DATA!!!.</div>";
                die();
            }
            $rw = $q->getRowArray();
            $mmn_rid  = $rw['recid'];
            $fld_txtgrtrx_no = $rw['grtrx_no'];
            $q->freeResult();
        } //END CHECK IF VALID PO

        //GENERATE NEW PO CTRL NO
        else { 
            $fld_txtgrtrx_no =  "CWGR" . $this->mydataz->get_ctr($this->db_erp,'GR_CTR');//TRANSACTION NO
        } //end mtkn_potr
        //ITEM
        if(empty($adata1)) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No Item Data!!!.</div>";
            die();
        }
        if(count($adata1) > 0) { 
            $ame = array();
            $adatar1 = array();
            $adatar2 = array();
            $ntqty = 0;
            $ntamt = 0;
            $ntcost = 0;
             $cc= 1;
            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);
                $mat_mtkn = $adata2[$aa];
                $fld_mitemcode = $cc;//$this->dbx->escapeString(trim($medata[0]));
                $fld_mitemdesc = $this->dbx->escapeString(trim($medata[1]));
                $fld_mitempkg = $this->dbx->escapeString(trim($medata[2]));
                $fld_ucost = (empty(str_replace(',','',$medata[3])) ? 0 : (str_replace(',','',$medata[3]) + 0));
                $fld_mitemtcost = (empty(str_replace(',','',$medata[4])) ? 0 : (str_replace(',','',$medata[4]) + 0));
                $fld_srp =  (empty(str_replace(',','',$medata[5])) ? 0 : (str_replace(',','',$medata[5]) + 0));
                $fld_mitemtamt =(empty(str_replace(',','',$medata[6])) ? 0 : (str_replace(',','',$medata[6]) + 0));
                $fld_mitemqty = (empty(str_replace(',','',$medata[7])) ? 0 : (str_replace(',','',$medata[7]) + 0));
                //$fld_mitemqtyc = (empty($medata[7]) ? 0 : ($medata[7] + 0));
                $fld_remks = $this->dbx->escapeString(trim($medata[8]));

                $fld_iitemcode = $this->dbx->escapeString(trim($medata[11]));
                $fld_iqty = $this->dbx->escapeString(trim($medata[12]));
                $fld_iconvf = $this->dbx->escapeString(trim($medata[13]));
                $fld_imndt_rid = $this->dbx->escapeString(trim($medata[14]));
                $fld_aconvf = $this->dbx->escapeString(trim($medata[15]));
                $fld_actdmg = $this->dbx->escapeString(trim($medata[16]));
                $fld_actlck = $this->dbx->escapeString(trim($medata[17]));
                
                //COMPUTATION ON SAVING
                $fld_mitemtcost = ($fld_iqty * $fld_mitemqty * $fld_ucost);
                $fld_mitemtamt =($fld_iqty * $fld_mitemqty * $fld_srp);
                
                $ntqty = $ntqty + $fld_mitemqty;//actual hd_subtqty
                $ntcost = $ntcost + $fld_mitemtcost;//actual hd_subtcost
                $ntamt = $ntamt + $fld_mitemtamt;//actual hd_subtamt
                
                //GETTING THE GRAND TOTAL HD
                $fld_subtqty = $this->dbx->escapeString(str_replace(',','',$ntqty));
                $fld_subtcost = $this->dbx->escapeString(str_replace(',','',$ntcost));
                $fld_subtamt = $this->dbx->escapeString(str_replace(',','',$ntamt));
                //$total_pcs = $nconvf*$nqty;
                //$cmat_code = $this->dbx->escapeString(trim($medata[0])) . $mktn_plnt_id . $mtkn_wshe_id;

                $amatnr = array();
                if(!empty($fld_mitemcode)) { 
                    // $str = "select aa.recid,aa.ART_CODE,aa.ART_DESC,aa.ART_UCOST,aa.ART_UPRICE,aa.ART_SKU from {$this->db_erp}.`mst_article` aa where aa.`ART_CODE` = '$fld_mitemcode' ";//sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mat_mtkn' and 
                    // $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    // if($q->getNumRows() == 0) { 
                    //  echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Material Data!!!<br/>[$fld_mitemcode]</div>";
                    //  die();
                    // } else {
                    //  $rw = $q->getRowArray();
                    //  //PARA ITO SA PASTE ITEMCODE LANG
                    //  //if(empty($mat_mtkn)) { //KAPAG NAGSELECT
                        
                    //  //} //end if(!empty($mat_mtkn)) { 
                    //  if(empty($mat_mtkn)) { //KAPAG PASTE LANG
                    //      $mat_paste_tag = 1;
                    //      //$mmat_rid_tmp = $rw['recid'];
                    //      $fld_mitemcode = $rw['ART_CODE'];
                    //      $fld_mitemdesc = $rw['ART_DESC'];
                    //      $fld_ucost =$rw['ART_UCOST'];
                    //      $fld_srp =  $rw['ART_UPRICE'];
                    //      $fld_mitempkg = $rw['ART_SKU'];
                            
                    //      //COMPUTATION ON SAVING
                    //      $fld_mitemtcost = ($fld_mitemqty * $fld_ucost);
                    //      $fld_mitemtamt =($fld_mitemqty * $fld_srp);
                            
                            
                    //      $ntqty = $ntqty + $fld_mitemqty;//actual hd_subtqty
                    //      $ntcost = $ntcost + $fld_mitemtcost;//actual hd_subtcost
                    //      $ntamt = $ntamt + $fld_mitemtamt;//actual hd_subtamt
                            
                    //      //GETTING THE GRAND TOTAL HD
                    //      $fld_subtqty = $this->dbx->escapeString(str_replace(',','',$ntqty));
                    //      $fld_subtcost = $this->dbx->escapeString(str_replace(',','',$ntcost));
                    //      $fld_subtamt = $this->dbx->escapeString(str_replace(',','',$ntamt));
                    //      $_paste_itemcode = $fld_mitemcode . 'x|x' . $fld_mitemdesc . 'x|x' . $fld_mitempkg . 'x|x' .$fld_ucost . 'x|x' .$fld_mitemtcost . 'x|x' .$fld_srp . 'x|x' .$fld_mitemtamt . 'x|x' . $fld_mitemqty . 'x|x' .$fld_remks . 'x|x' .$fld_iitemcode . 'x|x' .$fld_iqty  . 'x|x' .$fld_iconvf . 'x|x' . 'x|x' .$fld_imndt_rid . 'x|x';
                    //      $medata = explode("x|x",$_paste_itemcode);
                    //  }
                        //var_dump($fld_grtyp);
                        //die();
                        //VALIDATION OF ITEMS,QTY,PRICE
                        //if(in_array($cmat_code,$ame)) { 
                        /*if(!empty($fld_iitemcode)) { 
                            $str = "select aa.recid,aa.ART_CODE,aa.ART_DESC,aa.ART_UCOST,aa.ART_UPRICE,aa.ART_SKU from {$this->db_erp}.`mst_article` aa where aa.`ART_CODE` = '$fld_iitemcode' ";//sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mat_mtkn' and 
                            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            if($q->getNumRows() == 0) { 
                                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Item ItemCode!!!<br/>[$fld_iitemcode]</div>";
                                die();
                            }
                            $rw2 = $q2->getRowArray();
                            $fld_imndt_rid = $rw['recid']; 
                        }*/
                        
                        if(in_array($fld_mitemcode,$ame)) { 
                            if($ischck == 'N'){
                                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Particulars already exists [$fld_remks]</div>";
                                die();
                            }
                        } else { 
                            if(($fld_grtyp != '3') && ($fld_mitemqty == 0)) { 
                                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid QTY or Price entries [$fld_mitemcode]!!!</div>";
                                die();
                            }
                            
                        }
                        if(($fld_grtyp == '3') && ($fld_aconvf == 0) && ($fld_actdmg == 0) && ($fld_actlck == 0)){
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Actual Item Convf Goods, please remove if the qty is ZERO [$fld_mitemcode]!!!</div>";
                            die();

                        }
                        
                        //$rw = $q->getRowArray();
                        //$mmat_rid = $rw['recid'];
                        //array_push($ame,$cmat_code); 
                        array_push($ame,$fld_mitemcode); 
                        array_push($adatar1,$medata);
                        //array_push($adatar2,$mmat_rid);
                        /*$ntqty = ($ntqty + $nqty);*/
                        //$ntamt = ($ntamt + ($nprice * $nconvf * $nqty));
                        //$ntamt = ($ntamt + ($tamt));
                    //}

                    //$q->freeResult();
                }
                

             $cc++;
            }  //end for 
        
            if(count($adatar1) > 0) { 
                if(!empty($trxno)) { 
                    //DR bAKA MAGAKATAON NA MAY MAGAKAIBANG SUP NA PAREHAS ANG DR
                    /*$str = "select aa.`dr_no` from {$this->db_erp}.`trx_wshe_gr_hd` aa where aa.`dr_no` = '$fld_grno' AND aa.`branch_id` = '$fld_area_code_dr' AND !(aa.`flag`='C') AND !(sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) = '$trxno')";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    if($q->getNumRows() > 0) { 
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> DR No already exists.!!!.[".$fld_grno."]</div>";
                        die();
                    }*/
                    if(!empty($fld_refno)){
                        $str = " SELECT `recid` FROM {$this->db_erp}.`trx_wshe_gr_hd` WHERE `ref_no` = '{$fld_refno}' AND !(sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$trxno')";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                        if($q->getNumRows() > 0) { 
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> GR Ref No Already Exist!!!.</div>";
                            die();
                        }
                    }







                    $str = "
                    update {$this->db_erp}.`trx_wshe_gr_hd`
                    SET `comp_id` = '$fld_Company_gr',
                        `gr_date` = '$fld_grdate',
                        `plant_id` = '$fld_plant',
                        `wshe_id` = '$fld_wshe',
                        `rack_id` = '$fld_rack',
                        `bin_id` = '$fld_bin',
                        `ref_no` = '$fld_refno',
                        `hd_subtqty`='$fld_subtqty',
                        `hd_subtcost`='$fld_subtcost',
                        `hd_subtamt`='$fld_subtamt',
                        `remk` = '$fld_rems',
                        `grtype_id`='$fld_grtyp',
                        `class_id`='$fld_grclass',
                        `is_asstd` = '$ischck'
                    WHERE `recid` = '$mmn_rid';
                    ";
                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'MN_GR_UREC','',$fld_txtgrtrx_no,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        

                } else { 
                    //PO bAKA MAGAKATAON NA MAY MAGAKAIBANG SUP NA PAREHAS ANG DR
                    /*$str = "select aa.`dr_no` from {$this->db_erp}.`trx_wshe_gr_hd` aa where aa.`dr_no` = '$fld_grno' AND aa.`branch_id` = '$fld_area_code_dr' AND !(aa.`flag`='C')";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    if($q->getNumRows() > 0) { 
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> DR No already exists.!!!.[".$fld_grno."]</div>";
                        die();
                    }*/
                    if(!empty($fld_refno)){
                        $str = " SELECT `recid` FROM {$this->db_erp}.`trx_wshe_gr_hd` WHERE `ref_no` = '{$fld_refno}'";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                        if($q->getNumRows() > 0) { 
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> GR Ref No Already Exist!!!.</div>";
                            die();
                        }
                    }

                    $str = "insert into {$this->db_erp}.`trx_wshe_gr_hd`
                    (`grtrx_no`,
                     `gr_date`,
                     `comp_id`,
                     `plant_id`,
                     `wshe_id`,
                     `rack_id`,
                     `bin_id`,
                     `grtype_id`,
                     `class_id`,
                     `ref_no`,
                     `hd_subtqty`,
                     `hd_subtcost`,
                     `hd_subtamt`,
                     `muser`,
                     `encd_date`,
                     `df_tag`,
                     `remk`,
                     `is_asstd`,
                     `cd_tag`
                     )
                    VALUES (
                    '$fld_txtgrtrx_no',
                    '$fld_grdate',
                    '$fld_Company_gr',
                    '$fld_plant',
                    '$fld_wshe',
                    '$fld_rack',
                    '$fld_bin',
                    '$fld_grtyp',
                    '$fld_grclass',
                    '$fld_refno',
                    '$fld_subtqty',
                    '$fld_subtcost',
                    '$fld_subtamt',
                    '$cuser',
                     now(),
                    '$fld_dftag',
                    '$fld_rems',
                    '$ischck',
                    'Y')";

                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'MN_GR_AREC','',$fld_txtgrtrx_no,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $str = "select recid,sha2(concat(aa.recid,'{$mpw_tkn}'),384) mtkn_mntr from {$this->db_erp}.`trx_wshe_gr_hd` aa where `grtrx_no` = '$fld_txtgrtrx_no' ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $rw = $q->getRowArray();
                    $mmn_rid = $rw['recid'];
                    //var_dump($mmn_rid);
                    $__hmtkn_mntr = $rw['mtkn_mntr'];
                    $q->freeResult();


                }

                //GET PLNT, WSHE, SBIN

                 $cc= 1;
                for($xx = 0; $xx < count($adatar1); $xx++) {  //MAY MALI DITO
        
                    $xdata = $adatar1[$xx];
                    $mat_rid = '';//$adatar2[$xx];
                    
                    //$fld_mitemrid = $this->dbx->escapeString(trim($xdata[0]));
                    $fld_mitemcode = $cc;//$xdata[0];
                    $fld_mitemdesc = $this->dbx->escapeString(trim($xdata[1]));
                    $fld_mitempkg = $this->dbx->escapeString(trim($xdata[2]));
                    $fld_ucost = (empty(str_replace(',','',$xdata[3])) ? 0 : (str_replace(',','',$xdata[3]) + 0));
                    $fld_mitemtcost = (empty(str_replace(',','',$xdata[4])) ? 0 : (str_replace(',','',$xdata[4]) + 0));
                    $fld_srp =  (empty(str_replace(',','',$xdata[5])) ? 0 : (str_replace(',','',$xdata[5]) + 0));
                    $fld_mitemtamt =(empty(str_replace(',','',$xdata[6])) ? 0 : (str_replace(',','',$xdata[6]) + 0));
                    $fld_mitemqty = (empty(str_replace(',','',$xdata[7])) ? 0 : (str_replace(',','',$xdata[7]) + 0));
                    //$fld_mitemqty = (empty($xdata[7]) ? 0 : ($xdata[7] + 0));
                    $fld_remks = $this->dbx->escapeString(trim($xdata[8]));
                    //$fld_olt = $this->dbx->escapeString(trim($xdata[9]));
                    $mndt_rid = $this->dbx->escapeString(trim($xdata[9]));//dt mn id
                    $fld_gr_rson = "";//$this->dbx->escapeString(trim($xdata[10]));
                    
                    $fld_iitemcode = $this->dbx->escapeString(trim($xdata[11]));
                    $fld_iqty = $this->dbx->escapeString(trim($xdata[12]));
                    $fld_iconvf = $this->dbx->escapeString(trim($xdata[13]));
                    $fld_imndt_rid = $this->dbx->escapeString(trim($xdata[14]));
                    $fld_aconvf = $this->dbx->escapeString(trim($xdata[15]));
                    $fld_actdmg = $this->dbx->escapeString(trim($xdata[16]));
                    $fld_actlck = $this->dbx->escapeString(trim($xdata[17]));
                    //COMPUTATION ON SAVING
                    $fld_mitemtcost = ($fld_iqty * $fld_mitemqty * $fld_ucost);
                    $fld_mitemtamt =($fld_iqty * $fld_mitemqty * $fld_srp);
            
                    //  $tamt = $xdata[7];

                    
                    
                    if(empty($trxno)) {  
                        
                        /*$str = "select recid from {$this->db_erp}.`trx_wshe_gr_dt` where `grtrx_no` = '$fld_txtgrtrx_no' and `mat_rid` = '$mat_rid'";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        if($q->getNumRows() > 0 ) { 
                            $rw = $q->getRowArray();
                            $mndt_rid = $rw['recid'];

                            $str = "update {$this->db_erp}.`trx_wshe_gr_dt`
                            SET `mat_rid` = '$mat_rid',
                              `mat_code` = '$fld_mitemcode',
                              `qty` = '$fld_mitemqty',
                              `ucost` = '$fld_ucost',
                              `tcost` = '$fld_mitemtcost',
                              `uprice` = '$fld_srp',
                              `tamt` = '$fld_mitemtamt',
                              `nremarks` = '$fld_remks',
                              `imat_rid` = '$fld_imndt_rid',
                              `imat_code` = '$fld_iitemcode',
                              `imat_convf` = '$fld_iconvf',
                              `imat_qty` = '$fld_iqty',
                              `muser` = '$cuser'
                            WHERE `recid` = '$mndt_rid'
                            ";
                        } else {*/ 
                            $str = "insert into {$this->db_erp}.`trx_wshe_gr_dt`
                            (`grhd_rid`,
                            `grtrx_no`,
                            `mat_rid`,
                            `mat_code`,
                            `ucost`,
                            `tcost`,
                            `uprice`,
                            `tamt`,
                            `qty`,
                            `nremarks`,
                            `imat_rid`,
                            `imat_code`,
                            `imat_convf`,
                            `imat_qty`,
                            `amat_convf`,
                            `amat_dmg`,
                            `amat_lck`,
                            `muser`)
                            VALUES ('$mmn_rid',
                            '$fld_txtgrtrx_no',
                            '$mat_rid',
                            '$fld_mitemcode',
                            '$fld_ucost',
                            '$fld_mitemtcost',
                            '$fld_srp',
                            '$fld_mitemtamt',
                            '$fld_mitemqty',
                            '$fld_remks',
                            '$fld_imndt_rid',
                            '$fld_iitemcode',
                            '$fld_iconvf',
                            '$fld_iqty',
                            '$fld_aconvf',
                            '$fld_actdmg',
                            '$fld_actlck',
                            '$cuser')
                            ";
                        //}
                        $q->freeResult();
                        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        $this->mylibzdb->user_logs_activity_module($this->db_erp,'TRX_GR_DT','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        //var_dump($str);
                        //die();
                        
                        
                        
                        
                        
                    } else { 
                        if(empty($mndt_rid)){ 
                            /*$str = "select recid from {$this->db_erp}.`trx_wshe_gr_dt` where `grtrx_no` = '$fld_txtgrtrx_no' and sha2(concat(recid,'{$mpw_tkn}'),384) = '$mndt_rid'";
                            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                             if($q->getNumRows() > 0 ) { 
                                    $rw = $q->getRowArray();
                                    $mn_rid = $rw['recid'];
                                    $str = "update {$this->db_erp}.`trx_wshe_gr_dt`
                                        SET `mat_rid` = '$mat_rid',
                                          `mat_code` = '$fld_mitemcode',
                                          `qty` = '$fld_mitemqty',
                                          `ucost` = '$fld_ucost',
                                          `tcost` = '$fld_mitemtcost',
                                          `uprice` = '$fld_srp',
                                          `tamt` = '$fld_mitemtamt',
                                          `nremarks` = '$fld_remks',
                                          `imat_rid` = '$fld_imndt_rid',
                                          `imat_code` = '$fld_iitemcode',
                                          `imat_convf` = '$fld_iconvf',
                                          `imat_qty` = '$fld_iqty',
                                          `muser` = '$cuser'
                                        WHERE `recid` = '$mndt_rid'
                                        ";
                                } else { */
                                    $str = "insert into {$this->db_erp}.`trx_wshe_gr_dt`
                                    (`grhd_rid`,
                                    `grtrx_no`,
                                    `mat_rid`,
                                    `mat_code`,
                                    `ucost`,
                                    `tcost`,
                                    `uprice`,
                                    `tamt`,
                                    `qty`,
                                    `nremarks`,
                                    `imat_rid`,
                                    `imat_code`,
                                    `imat_convf`,
                                    `imat_qty`,
                                    `amat_convf`,
                                    `amat_dmg`,
                                    `amat_lck`,
                                    `muser`)
                                    VALUES ('$mmn_rid',
                                    '$fld_txtgrtrx_no',
                                    '$mat_rid',
                                    '$fld_mitemcode',
                                    '$fld_ucost',
                                    '$fld_mitemtcost',
                                    '$fld_srp',
                                    '$fld_mitemtamt',
                                    '$fld_mitemqty',
                                    '$fld_remks',
                                    '$fld_imndt_rid',
                                    '$fld_iitemcode',
                                    '$fld_iconvf',
                                    '$fld_iqty',
                                    '$fld_aconvf',
                                    '$fld_actdmg',
                                    '$fld_actlck',
                                    '$cuser')
                                    ";
                                    //}
                                    $q->freeResult();
                                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'trx_dr_dt','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                
                            
                        } else { // end empty podt_rid */
                            $str = "select recid from {$this->db_erp}.`trx_wshe_gr_dt` aa where `mat_code` = '$fld_mitemcode' and `grtrx_no` = '$trxno'";
                            $qq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            if($qq->getNumRows() > 0) { 
                                $rrw = $qq->getRowArray();
                                $mndt_rid = $rrw['recid'];
                                $str = "
                                update {$this->db_erp}.`trx_wshe_gr_dt`
                                        SET `mat_rid` = '$mat_rid',
                                          `mat_code` = '$fld_mitemcode',
                                          `qty` = '$fld_mitemqty',
                                          `ucost` = '$fld_ucost',
                                          `tcost` = '$fld_mitemtcost',
                                          `uprice` = '$fld_srp',
                                          `tamt` = '$fld_mitemtamt',
                                          `nremarks` = '$fld_remks',
                                          `imat_rid` = '$fld_imndt_rid',
                                          `imat_code` = '$fld_iitemcode',
                                          `imat_convf` = '$fld_iconvf',
                                          `imat_qty` = '$fld_iqty',
                                          `amat_convf` = '$fld_aconvf',
                                          `amat_dmg` = '$fld_actdmg',
                                          `amat_lck` = '$fld_actlck',
                                          `muser` = '$cuser'
                                        WHERE `mat_code` = '$fld_mitemcode' and `recid` = '$mndt_rid'";
                                $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                $this->mylibzdb->user_logs_activity_module($this->db_erp,'TRX_GR_UPD_DT','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
                            }
                            $qq->freeResult();

                            

                            
                        }  //end 
                        
                    }
                    
                    
                 $cc++;
                }  //end for 


                
                //record on AV Work Flow
                //$qry->freeResult();  
                if(empty($trxno)) {
                    //PARA SA ENCODING PANGCHECK NG DUPLICATE IETMCODE
                    $str = "drop table if exists {$this->db_temp}.`isduplicate_itemc_logs`"; 
                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Data Recorded Successfully!!!</div>
                    <script type=\"text/javascript\"> 
                        function __dr_refresh_data() { 
                            try { 
                                $('#__hmtkn_trxnoid').val('{$__hmtkn_mntr}');
                                $('#txtgrtrx_no').val('{$fld_txtgrtrx_no}');
                                $('#mbtn_mn_Save').prop('disabled',true);
                            } catch(err) { 
                                var mtxt = 'There was an error on this page.\\n';
                                mtxt += 'Error description: ' + err.message;
                                mtxt += '\\nClick OK to continue.';
                                alert(mtxt);
                                return false;
                            }  //end try 
                        } 
                        
                        __dr_refresh_data();
                    </script>
                    ";
                    die();
                } else { 
                    //PARA SA ENCODING PANGCHECK NG DUPLICATE IETMCODE
                    //$str = "drop table if exists {$this->db_temp}.`isduplicate_itemc_logs`"; 
                    //$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Data Changes Successfully RECORDED!!!</div>
                    ";
                    die();
                }
            } else { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No VALID Item Data!!!.</div>";
                die();
            } //end if 
        } else { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Item Data!!!.</div>";
            die();
        }
            
    }

    public function cancel_recs(){
            $cuser          = $this->mylibzdb->mysys_user();
            $mpw_tkn        = $this->mylibzdb->mpw_tkn();
            //$mtkn_mndt_rid: = $this->request->get_post('mtkn_podt_rid');
            $mtkn_rid  = $this->request->getVar('mtkn_itm');
            

            $str = "SELECT * from {$this->db_erp}.`trx_wshe_gr_hd` where sha2(concat(recid,'{$mpw_tkn}'),384) = '$mtkn_rid' AND `cd_tag` = 'Y'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);



            if($q->getNumRows() > 0) { 
                $rw         = $q->getRowArray();
                $txtrecid   = $rw['recid'];
                $__flag="C";

                $str = "UPDATE {$this->db_erp}.`trx_wshe_gr_hd` SET `flag` = '$__flag' WHERE `recid` = '$txtrecid' AND `cd_tag` = 'Y'";
                $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                $this->mylibzdb->user_logs_activity_module($this->db_erp,'GR_CREC','',$txtrecid,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                echo "
                <div class=\" alert alert-success\">
                <strong>Success</strong> 
                <p>Records successfully deleted!!!</p>
                </div>
                
                ";
            } 
            else 
            {
                echo "
                <div class=\" alert alert-danger\">
                <strong>Error</strong> 
                <p>Records already deleted!!!</p>
                </div>
                ";
            }
    }


        public function view_grlogfile_recs($npages = 1,$npagelimit = 30){
            $cuser          = $this->mylibzdb->mysys_user();
            $mpw_tkn        = $this->mylibzdb->mpw_tkn();

             $fld_dl_dteto   = $this->request->getVar('fld_dl_dteto');
             $fld_dl_dteto   = $this->mylibzsys->mydate_yyyymmdd($fld_dl_dteto);
             $fld_dl_dtefrom = $this->request->getVar('fld_dl_dtefrom');
             $fld_dl_dtefrom = $this->mylibzsys->mydate_yyyymmdd($fld_dl_dtefrom);

            $__flag         = "C";
            $str_optn       = "";
            $fld_dlsupp_q   = "";
            $fld_dlbranch_q = "";
            $chtmlhd        = "";
            $chtmljs        = "";
            $chtml          = "";
            $cmsexp         = "";
            $cmsgt          = "";
            $chtml2         = "";
            $cmsft          = "";
            $date           = date("F j, Y, g:i A");
            
            //CONSOLIDATE
            $fld_dldftag_q = (!empty($fld_dldftag) ? "AND aa.`df_tag` = '$fld_dldftag'" : "");
            $fld_dlgrtyp_q = (!empty($fld_dlgrtyp) ? "AND aa.`grtype_id` = '$fld_dlgrtyp'" : "");
            $fld_dl_dte_q = (((!empty($fld_dl_dteto) && !empty($fld_dl_dtefrom)) && (($fld_dl_dteto != '--') && ($fld_dl_dtefrom != '--'))) ? "AND (aa.`gr_date` >= '{$fld_dl_dtefrom}' AND  aa.`gr_date` <= '{$fld_dl_dteto}')" : "");
            $result = $this->mydatazua->get_Active_menus($this->db_erp,$cuser,"myuatrx_id='213'","myua_trx");
            if($result == 1){


            $chtmljs .= "
            <div class=\"col-md-3\" id=\"__mtoexport_dr\">
                <div class=\"col-md-12\">
                    <span class=\"\"><a href=\"JavaScript:void(0);\" id=\"lnkexportmsexcel_gr\"><i class=\"btn btn-success bi bi-download\"> DOWNLOAD</i></a></span>
                </div>
                </br>
            </div>
            ";
            }
            ////////////////////////////////////////////////////////////////////////PULLOUT LOGFILE REPORTS/////////////////////////////////////////////////////////////////////////////////
           $chtml = "
                        <html xmlns:x=\"urn:schemas-microsoft-com:office:excel\">
                            <head>
                            <meta http-equiv=Content-Type content=\"text/csv; charset=utf-8\">
                            </head>
                            <body>
                        <table class=\"table table-sm table-bordered table-hover\" id=\"testTable_dr\">
                           
                              <tr class=\"header-tr-addr\">
                                <th class=\"noborder\" colspan=\"27\">Goods Receipt Logfile</th>
                              </tr>
                              <tr class=\"header-tr-addr\">
                                <th class=\"noborder\" colspan=\"27\">".$fld_dl_dtefrom."- ".$fld_dl_dteto."</th>
                              </tr>
                              <tr class=\"header-tr-addr\">
                                <th class=\"noborder\" colspan=\"27\">&nbsp;</th>
                              </tr>
                              <tr class =\"header-theme-purple text-white\">
                                <th class=\"noborder\">No</th>
                                <th class=\"noborder\">Transaction No</th>
                                <th class=\"noborder\">Pullout Trx No</th>
                                <th class=\"noborder\">Company</th>
                                <th class=\"noborder\">Item Code</th>
                                <th class=\"noborder\">Item Description</th>
                                <th class=\"noborder\">Unit Cost</th>
                                <th class=\"noborder\">Total Unit Cost</th>
                                <th class=\"noborder\">Unit Price</th>
                                <th class=\"noborder\">Total Unit Price</th>
                                <th class=\"noborder\">Box Qty</th>
                                <th class=\"noborder\">Total Actual Qty</th>
                                <th class=\"noborder\">Total Actual Cost</th>
                                <th class=\"noborder\">Total Actual SRP</th>
                                <th class=\"noborder\">Branch</th>
                                <th class=\"noborder\">GR Date</th>
                                <th class=\"noborder\">User</th>
                                <th class=\"noborder\">Remarks</th>
                                <th class=\"noborder\">Type</th>
                                <th class=\"noborder\">Assorted Items</th>
                                <th class=\"noborder\">Item Qty Pcs (Pullout)</th>
                                <th class=\"noborder\">Item Qty Pcs(Goods)</th>
                                <th class=\"noborder\">Item Convf (Goods)</th>
                                <th class=\"noborder\">Item Qty Pcs (Damage)</th>
                                <th class=\"noborder\">Item Qty Pcs (Lacking)</th>
                                <th class=\"noborder\">Approval (Y/N)</th>

                                </tr>
                            ";
                            $strqry = "
                            SELECT
                            aa.`recid` __arid,
                            aa.`grtrx_no` __hdgrtrx,
                            aa.`comp_id`,
                            aa.`gr_date`,
                            aa.`remk`,
                            aa.`hd_subtqty`,
                            aa.`hd_subtcost`,
                            aa.`hd_subtamt`,
                            aa.`grtype_id`,
                            aa.`muser`,
                            aa.`encd_date`,
                            aa.`flag`,
                            aa.`p_flag`,
                            aa.`df_tag`,
                            aa.`post_tag`,
                            ee.`recid` __brid,
                            ee.`grhd_rid`,
                            ee.`grtrx_no` __dtpotrx,
                            ee.`mat_rid`,
                            ee.`mat_code`,
                            ee.`ucost`,
                            ee.`tcost`,
                            ee.`uprice`,
                            ee.`tamt`,
                            ee.`qty`,
                            ee.`nremarks`,
                            ee.`imat_code`,
                            ee.`imat_convf`,
                            ee.`imat_qty`,
                            gg.`grtype_desc`,
                            bb.`COMP_NAME`,
                            ff.`ART_CODE`,
                            ff.`ART_DESC`,
                            aa.`is_apprvd`,
                            aa.`ref_no` __refno,
                            ee.`amat_convf`,
                            ee.`amat_dmg`,
                            ee.`amat_lck`,
                            ii.`BRNCH_NAME`,
                            sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) mtkn_arttr 
                            from {$this->db_erp}.`trx_wshe_gr_hd` aa
                            join {$this->db_erp}.`mst_company` bb
                            on (aa.`comp_id` = bb.`recid`)
                            join {$this->db_erp}.`trx_wshe_gr_dt` ee
                            on (aa.`recid` = ee.`grhd_rid`)
                            join {$this->db_erp}.`mst_article` ff
                            on (ff.`recid`= ee.`mat_rid`)
                            join {$this->db_erp}.`mst_wshe_gr_type` gg
                            on (gg.`recid` = aa.`grtype_id`)
                            left join {$this->db_erp}.`trx_manrecs_po_hd` hh
                            on (aa.`ref_no` = hh.`potrx_no`)
                            left join {$this->db_erp}.`mst_companyBranch` ii
                            on (hh.`branch_id` = ii.`recid`)
                            where aa.`flag` != '$__flag' AND aa.`cd_tag` = 'Y'
                            {$fld_dldftag_q} {$fld_dlgrtyp_q} {$fld_dl_dte_q} {$fld_dlsupp_q} {$fld_dlbranch_q}
                            ";

                $q = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                  
                    $res=1;
                    if($q->getNumRows() > 0) { 
                        //IF QUERY HAS ALTEAST ONE RESULT CREATE PATH and FILE

                        $mpathdn   = ROOTPATH;
                        $mpathdest = $mpathdn . '/public/downloads/me'; 
                        $cdate = date('Ymd');
                        $cfiletmp = 'grlogfile_rpt' . '_' . $cdate .$this->mylibzsys->random_string(9) . '.xls' ;
                        $cfiledest = $mpathdest . '/' . $cfiletmp;
                        $cfilelnk = site_url() . '/downloads/me/' . $cfiletmp;
                        //SEND TO UALAM
                        $this->mylibzdb->user_logs_activity_module($this->db_erp,'GRRPT_DOWNLOAD','',$cuser."_FN_".$cfiletmp,$strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        
                        //SECUREW FILES
                        if(file_exists($cfiledest)) {
                        unlink($cfiledest);
                        }
                        $fh = fopen($cfiledest, 'w');
                        fwrite($fh, $chtml);
                        fclose($fh); 
                        chmod($cfiledest, 0755);
                        $ntqty  = 0;
                        $ntsrp  = 0;
                        $ntmu   = 0;
                        $ntcost = 0;
                        $ntprc  = 0;
                        $ntpay = 0;
                        $qrw = $q->getResultArray();
                            foreach($qrw as $row):
                                $chtml = "  <tr class=\"data-nm\">
                                            <td>".$res."</td>
                                            <td>'".$row['__hdgrtrx']."</td>
                                            <td>'".$row['__refno']."</td>
                                            <td>".$row['COMP_NAME']."</td>
                                            <td>".$row['ART_CODE']."</td>
                                            <td>".$row['ART_DESC']."</td>
                                            <td>".number_format($row['ucost'],2,'.',',')."</td>
                                            <td>".number_format($row['tcost'],2,'.',',')."</td>
                                            <td>".number_format($row['uprice'],2,'.',',')."</td>
                                            <td>".number_format($row['tamt'],2,'.',',')."</td>
                                            <td>".number_format($row['qty'],2,'.',',')."</td>
                                            <td>".number_format($row['hd_subtqty'],2,'.',',')."</td>
                                            <td>".number_format($row['hd_subtcost'],2,'.',',')."</td>
                                            <td>".number_format($row['hd_subtamt'],2,'.',',')."</td>
                                            <td>".$row['BRNCH_NAME']."</td>
                                            <td>".$this->mylibzsys->mydate_mmddyyyy($row['gr_date'])."</td>
                                            <td>".$row['muser']."</td>
                                            <td>".$row['nremarks']."</td>
                                            <td>".$row['grtype_desc']."</td>
                                            <td>".$row['imat_code']."</td>
                                            <td>".$row['amat_convf']."</td>
                                            <td>".$row['imat_qty']."</td>
                                            <td>".$row['imat_convf']."</td>
                                            <td>".$row['amat_dmg']."</td>
                                            <td>".$row['amat_lck']."</td>
                                            <td>".$row['is_apprvd']."</td>
                                           </tr>
                                       ";
                            file_put_contents ( $cfiledest , $chtml , FILE_APPEND | LOCK_EX ); 
                            $res++;
                            endforeach;

                            
                        
                    }//end if
                    else{

                         $dta['msg'] = 'No records found!';
                       echo view('components/no-records',$dta);
                       die();

                    }
            $chtmljs .= "
                            <script type=\"text/javascript\">
                                //window.parent.document.getElementById('myscrloading').innerHTML = '';
                                jQuery('#lnkexportmsexcel_gr').click(function() { 
                                    //jQuery('#messproc').css({display:''});
                                    window.location = '{$cfilelnk}';
                                    $('#lnkexportmsexcel_gr').css({display:'none'});
                                });
                                
                                jQuery('#lnktoprint').click(function() { 
                                    jQuery('#__mtoexport_dr').css({display:'none'});
                                    //jQuery('#__mtoprint').css({display:'none'});
                                    window.print();         
                                });
                            </script>
                            
                            ";
            echo $chtmljs;
            //var_dump($strqry);
            $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
            if($qry->getNumRows() > 0) { 
                $data['rlist']          = $qry->getResultArray();
                $data['fld_dl_dteto']   = $fld_dl_dteto;
                $data['fld_dl_dtefrom'] = $fld_dl_dtefrom;
            } else { 
                $data = array();
                $data['rlist']          = '';
                $data['fld_dl_dteto']   = $fld_dl_dteto;
                $data['fld_dl_dtefrom'] = $fld_dl_dtefrom;
            }
            return $data;
        }//endfunc
   

    //DR MONTHLY REPORTS
    public function gr_rpt_summ_download(){
        $cuserlvl=$this->mylibzdb->mysys_userlvl();
        $cuser = $this->mylibzdb->mysys_user();
        $cuser_fullname = $this->mylibzdb->mysys_user_fullname();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $fld_summ_dteto = $this->mylibzsys->mydate_yyyymmdd($this->request->getVar('fld_summ_dteto'));
        $fld_summ_dtefrom = $this->mylibzsys->mydate_yyyymmdd($this->request->getVar('fld_summ_dtefrom'));
        
        
        $str_optn      = '';
        $str_optn_po   = '';
        $monthName     = '';
        $fld_area_code = '';
        $chtmlhd       = "";
        $chtmljs       = "";
        $chtml         = "";
        $cmsexp        = "";
        $cmsgt         = "";
        $chtml2        = "";
        $cmsft         = "";
        $date = date("F j, Y, g:i A");
        
        
        
        //WHEN USER IS USER SELECT A RCV DATE FROM AND TO.
        $str_date="";
        if((!empty($fld_summ_dteto) && !empty($fld_summ_dtefrom)) && (($fld_summ_dteto != '--') && ($fld_summ_dtefrom != '--'))){
            $str_date = " AND (SUBSTRING_INDEX(aa.`gr_date`,' ',1) >= DATE('{$fld_summ_dtefrom}') AND  SUBSTRING_INDEX(aa.`gr_date`,' ',1) <= DATE('{$fld_summ_dteto}'))";
            //$str_date="AND (aa.`gr_date` >= '{$fld_summ_dtefrom}' AND  aa.`gr_date` <= '{$fld_summ_dteto}')";
        }
        $str_dftag='';
        
        //WHEN USER IS USER THEN DOWNLOAD ONLY THEY ENCODE ELSE SA WILL ALL DOWNLOAD THE DATA.
        if($cuserlvl=="S"){
            $str_encduser="";
        }
        else{
            $str_encduser="AND (aa.`muser` = '$cuser')";

        }
        // $chtmljs .= "
        // <div class=\"col-md-6\" id=\"__mtoexport_drtd\">
        //  <div class=\"col-md-3\">
        //      <span class=\"\"><a href=\"JavaScript:void(0);\" id=\"lnkexportmsexcel_gr\"><i class=\"btn btn-success fa fa-download\"> DR</i></a></span>
        //  </div>
        //  </br>
                //       </div> 
        // ";
        ////////////////////////////////////////////////////////////////////////DR REPORTS/////////////////////////////////////////////////////////////////////////////////
           $chtml = "
                    <html xmlns:x=\"urn:schemas-microsoft-com:office:excel\">
                        <head>
                        <meta http-equiv=Content-Type content=\"text/csv; charset=utf-8\">
                        </head>
                        <body>
                    <table class=\"table table-sm table-bordered table-hover\" id=\"testTable_dr\">
                       
                          <tr class=\"header-tr-addr\">
                            <th class=\"noborder\" colspan=\"9\">GR Breakdown of the month</th>
                          </tr>
                          <tr class=\"header-tr-addr\">
                            <th class=\"noborder\" colspan=\"9\">".$fld_summ_dtefrom." - ".$fld_summ_dteto."</th>
                          </tr>
                          <tr class=\"header-tr-addr\">
                            <th class=\"noborder\" colspan=\"9\">&nbsp;</th>
                          </tr>
                          <tr class =\"header-theme-purple text-white\">
                            <th class=\"noborder\">No</th>
                            <th class=\"noborder\">GR No</th>
                            <th class=\"noborder\">GR Date</th>
                            <th class=\"noborder\">Warehouse</th>
                            <th class=\"noborder\">Warehouse Group</th>
                            <th class=\"noborder\">Warehouse Bin</th>
                            <th class=\"noborder\">ITEM QUANTITY</th>
                            <th class=\"noborder\">AMOUNT</th>
                            <th class=\"noborder\">COST</th>
                            </tr>
                        ";
        
        //QUERY

        $str="
        SELECT
          xxx.`recid`,
          xxx.`grtrx_no`,
          xxx.`gr_date`,
          xxx.`comp_id`,
          xxx.`class_id`,
          xxx.`plant_id`,
          xxx.`wshe_id`,
          xxx.`rack_id`,
          xxx.`bin_id`,
          xxx.`grtype_id`,
          xxx.`ref_no`,
          xxx.`hd_subtqty`,
          xxx.`hd_subtcost`,
          xxx.`hd_subtamt`,
          xxx.`muser`,
          xxx.`encd_date`,
          xxx.`flag`,
          xxx.`df_tag`,
          xxx.`p_flag`,
          xxx.`post_tag`,
          xxx.`remk`,
          xxx.`is_remk`,
          xxx.`print_time`,
          xxx.`print_by`,
          xxx.`is_asstd`,
          xxx.`is_apprvd`,
          xxx.`is_bcodegen`,
          xxx.`wshe_code`,
          xxx.`wshe_grp`,
          xxx.`wshe_bin_name`,
          SUM(xxx.`qty`) qty 
        FROM
            (SELECT 
              aa.`recid`,
              aa.`grtrx_no`,
              aa.`gr_date`,
              aa.`comp_id`,
              aa.`class_id`,
              aa.`plant_id`,
              aa.`wshe_id`,
              aa.`rack_id`,
              aa.`bin_id`,
              aa.`grtype_id`,
              aa.`ref_no`,
              aa.`hd_subtqty`,
              aa.`hd_subtcost`,
              aa.`hd_subtamt`,
              aa.`muser`,
              aa.`encd_date`,
              aa.`flag`,
              aa.`df_tag`,
              aa.`p_flag`,
              aa.`post_tag`,
              aa.`remk`,
              aa.`is_remk`,
              aa.`print_time`,
              aa.`print_by`,
              aa.`is_asstd`,
              aa.`is_apprvd`,
              aa.`is_bcodegen`,
              cc.`wshe_code`,
              dd.`wshe_grp`,
              ee.`wshe_bin_name`,
              ff.`qty` qty 
            FROM (((({$this->db_erp}.`trx_wshe_gr_hd` aa 
            JOIN {$this->db_erp}.`mst_wshe` cc ON (cc.`recid` = aa.`wshe_id`))
            JOIN {$this->db_erp}.`mst_wshe_grp` dd 
                ON (dd.`recid` = aa.`rack_id` AND dd.`plnt_id`  = aa.`plant_id` 
                AND dd.`wshe_id` = aa.`wshe_id` ))
            JOIN {$this->db_erp}.`mst_wshe_bin` ee   
                ON (ee.`recid` = aa.`bin_id` AND ee.`plnt_id`  = aa.`plant_id` 
                AND ee.`wshe_id` = aa.`wshe_id` AND aa.`rack_id` = ee.`wshegrp_id` ))
            JOIN {$this->db_erp}.`trx_wshe_gr_dt` ff ON (ff.`grtrx_no` = aa.`grtrx_no`))
            WHERE !(aa.`flag`='C') AND (aa.`is_apprvd`='Y') AND aa.`cd_tag` = 'Y' {$str_date}
            GROUP BY ff.`mat_rid`

        )xxx
        GROUP BY xxx.`grtrx_no` 
        ";  //  AND !(aa.`post_tag`='N')  Pinatanggal noong septyembere 17,2019 ni Sir Claudio
        
        //var_dump($str);
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        //var_dump($str);
        $res=1;
        if($q->getNumRows() > 0) { 

          
    
            //IF QUERY HAS ALTEAST ONE RESULT CREATE PATH and FILE
            $mpathdn   = ROOTPATH;
            $mpathdest = $mpathdn . '/public/downloads/me'; 
            $cdate = date('Ymd');
            $cfiletmp = 'gr-summ_rpt' . '_' . $cdate .$this->mylibzsys->random_string(9) . '.xls' ;
            $cfiledest = $mpathdest . '/' . $cfiletmp;
            $cfilelnk = site_url() . '/downloads/me/' . $cfiletmp;
            //SEND TO UALAM
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'GRRPT_DOWNLOAD','',$cuser."_FN_".$cfiletmp,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
            //SECUREW FILES
            if(file_exists($cfiledest)) {
            unlink($cfiledest);
            }
            $fh = fopen($cfiledest, 'w');
            fwrite($fh, $chtml);
            fclose($fh); 
            chmod($cfiledest, 0755);
            $ntqty  = 0;
            $ntsrp  = 0;
            $ntmu   = 0;
            $ntcost = 0;
            $ntprc  = 0;
            $ntpay  = 0;
            //<th class=\"noborder\">SUPPLIER</th> <td>".$rw['SUPPLIER']."</td>
            $qrw = $q->getResultArray();
                foreach($qrw as $rw):
                    $chtml = "  <tr class=\"data-nm\">
                                <td>".$res."</td>
                                <td>".$rw['grtrx_no']."</td>
                                <td>".$rw['gr_date']."</td>
                                <td>".$rw['wshe_code']."</td>
                                <td>".$rw['wshe_grp']."</td>
                                <td>".$rw['wshe_bin_name']."</td>
                                <td>".$rw['qty']."</td>
                                <td>".number_format($rw['hd_subtamt'],2,'.','') ."</td>
                                <td>".number_format($rw['hd_subtcost'],2,'.','')."</td>
                               </tr>
                           ";
                file_put_contents ( $cfiledest , $chtml , FILE_APPEND | LOCK_EX ); 
                $ntqty=$ntqty + $rw['qty'];
                $ntsrp=$ntsrp + $rw['hd_subtamt'];
                $ntcost= $ntcost + $rw['hd_subtcost'];
                $res++;
                endforeach;

                
            
        }//end if
        else{
            echo "
                <div class=\"alert alert-danger\" role=\"alert\">
                No Data Found!!!
                </div>              
            ";
            die();
        }
        //QUERY

        /*$str="
        SELECT 
        IFNULL(SUM(aa.`hd_subtqty`),0) PTQTY,
        IFNULL(SUM(aa.`hd_subtcost`),0) PTCOST
        FROM {$this->db_erp}.`trx_wshe_gr_hd` aa 
        WHERE !(aa.`flag`='C') AND !(aa.`df_tag`='D') {$str_supp} {$str_brnch} {$str_optn_po}";  //  AND !(aa.`post_tag`='N')  Pinatanggal noong septyembere 17,2019 ni Sir Claudio
        //var_dump($str);
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $res = $q->getRowArray();

                */$chtml = "<tr></tr>
                          <tr class=\"header-tr\">
                            <th align=\"right\" class=\"noborder\" colspan=\"6\">Total</th>
                            <th align=\"center\" class=\"noborder\" colspan=\"1\">".$ntqty."</th>
                            <th align=\"center\" class=\"noborder\" colspan=\"1\">".number_format($ntsrp,2,'.','')."</th>
                            <th align=\"center\" class=\"noborder\" colspan=\"1\">".number_format($ntcost,2,'.','')."</th>
                          </tr>
                         
                          <tr></tr>
                          <tr></tr>
                          <tr></tr>
                          <tr class=\"header-tr\">
                            <th align=\"left\" class=\"noborder\" colspan=\"2\">PREPARED BY:</th>
                            <th align=\"center\" class=\"noborder\" colspan=\"2\">".$cuser_fullname."</th>
                            <th align=\"left\" class=\"noborder\" colspan=\"2\">CHECKED BY:</th>
                            <th align=\"center\" class=\"noborder\" colspan=\"2\"></th>
                          </tr>
                           ";
                file_put_contents ( $cfiledest , $chtml , FILE_APPEND | LOCK_EX );  
        $chtmljs .= "
                <script type=\"text/javascript\">
                    //window.parent.document.getElementById('myscrloading').innerHTML = '';
                    gr_summ_dl();
                    function gr_summ_dl(){
                        window.location = '{$cfilelnk}';
                    }
                    
                    
                    jQuery('#lnktoprint').click(function() { 
                        jQuery('#__mtoexport_drtd').css({display:'none'});
                        //jQuery('#__mtoprint').css({display:'none'});
                        window.print();         
                    });
                </script>
                
                ";
        echo $chtmljs;

    }//end func


        public function view_bcode_recs($npages = 1,$npagelimit = 30,$fld_grdtfrm='',$fld_grdtto=''){
            $cuser = $this->mylibzdb->mysys_user();
            $mpw_tkn = $this->mylibzdb->mpw_tkn();
            $fld_bb_grcode = $this->request->getVar('fld_bb_grcode');
            $fld_bb_plant = $this->request->getVar('fld_bb_plant');
            $fld_bb_wshe = $this->request->getVar('fld_bb_wshe');


            //var_dump($fld_grdtfrm);
            //die();
            $__flag="C";
            $str_bb_grcode = "";
            $str_bb_plant = "";
            $str_bb_wshe = "";
            $str_date = "";
            if(!empty($fld_bb_grcode)) { 
                $str = "select aa.recid,aa.grtrx_no from {$this->db_erp}.`trx_wshe_gr_hd` aa where aa.grtrx_no = '$fld_bb_grcode' ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                if($q->getNumRows() == 0) { 
                    echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Transaction DATA!!!.</div>";
                    die();
                }
                $rw = $q->getRowArray();
                $mmn_rid  = $rw['recid'];
                $fld_bb_grcode = $rw['grtrx_no'];
                $q->freeResult();
                $str_bb_grcode = "AND ( aa.`grtrx_no` = '$fld_bb_grcode')";
            } //END CHECK IF VALID PO

            if(!empty($fld_bb_wshe)){
                $wsheData = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($fld_bb_wshe);
                $fld_bb_plant = $wsheData['plntID'];
                $fld_bb_wshe = $wsheData['whID'];
                $str_bb_plant = "AND ( aa.`plant_id` =  '$fld_bb_plant')";
                $str_bb_wshe = "AND ( aa.`wshe_id` = '$fld_bb_wshe')";
            }
        
            
            
            if((!empty($fld_grdtfrm) && !empty($fld_grdtto)) && (($fld_grdtfrm != '--') && ($fld_grdtto != '--'))) {
                $str_date = " AND (SUBSTRING_INDEX(aa.`gr_date`,' ',1) >= DATE('{$fld_grdtfrm}') AND  SUBSTRING_INDEX(aa.`gr_date`,' ',1) <= DATE('{$fld_grdtto}'))";
            }
            if(((!empty($fld_grdtfrm) && !empty($fld_grdtto)) && (($fld_grdtfrm != '--') && ($fld_grdtto != '--'))) || (!empty($fld_bb_grcode) || !empty($fld_bb_plant)  || !empty($fld_bb_wshe))){
                $strqry = "
                SELECT
                aa.`recid` __arid,
                aa.`grtrx_no`,
                aa.`comp_id`,
                aa.`plant_id`,
                aa.`wshe_id`,
                aa.`gr_date`,
                aa.`remk`,
                aa.`hd_subtqty`,
                aa.`hd_subtcost`,
                aa.`hd_subtamt`,
                aa.`muser`,
                aa.`encd_date`,
                aa.`flag`,
                aa.`p_flag`,
                aa.`df_tag`,
                aa.`post_tag`,
                aa.`is_apprvd`,
                bb.`COMP_NAME`,
                dd.`plnt_code`,
                ee.`wshe_code`,
                sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) mtkn_arttr,
                sha2(concat(aa.`wshe_id`,'{$mpw_tkn}'),384) _wshe_id 
                 from {$this->db_erp}.`trx_wshe_gr_hd` aa
                JOIN {$this->db_erp}.`mst_company` bb
                ON (aa.`comp_id` = bb.`recid`)
                LEFT JOIN {$this->db_erp}.`mst_plant` dd
                ON (aa.`plant_id` = dd.`recid`)
                LEFT JOIN {$this->db_erp}.`mst_wshe` ee
                ON (aa.`wshe_id` = ee.`recid`)
                where aa.`flag` != '$__flag' AND aa.`post_tag` = 'N' AND aa.`df_tag`='F' AND aa.`cd_tag` = 'Y' AND `is_apprvd`='Y' AND `is_bcodegen`='Y'
                {$str_bb_grcode} {$str_bb_plant} {$str_bb_wshe} {$str_date}
                ";
                
                $str = "
                select count(*) __nrecs from ({$strqry}) oa
                ";
                $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                $rw = $qry->getRowArray();
                $npagelimit = ($npagelimit > 0 ? $npagelimit : 30);
                $nstart = ($npagelimit * ($npages - 1));
                
                
                $npage_count = ceil(($rw['__nrecs'] + 0) / $npagelimit);
                $data['npage_count'] = $npage_count;
                $data['npage_curr'] = $npages;
                $str = "
                SELECT * from ({$strqry}) oa order by __arid limit {$nstart},{$npagelimit} ";
                $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                
                if($qry->getNumRows() > 0) { 
                    $data['rlist'] = $qry->getResultArray();
                    $data['fld_grdtfrm']   = $fld_grdtfrm;
                    $data['fld_grdtto']    = $fld_grdtto;
                    $data['fld_bb_grcode'] = $fld_bb_grcode;
                    $data['fld_bb_plant']  = $fld_bb_plant;
                    $data['fld_bb_wshe']   = $fld_bb_wshe;
                } else { 
                    $data = array();
                    $data['npage_count'] = 1;
                    $data['npage_curr'] = 1;
                    $data['rlist'] = '';
                    $data['fld_grdtfrm']   = $fld_grdtfrm;
                    $data['fld_grdtto']    = $fld_grdtto;
                    $data['fld_bb_grcode'] = $fld_bb_grcode;
                    $data['fld_bb_plant']  = $fld_bb_plant;
                    $data['fld_bb_wshe']   = $fld_bb_wshe;
                }
                return $data;
            }
            else{
                $data = array();
                $data['npage_count'] = 1;
                $data['npage_curr'] = 1;
                $data['rlist'] = '';
                $data['fld_grdtfrm']   = $fld_grdtfrm;
                $data['fld_grdtto']    = $fld_grdtto;
                $data['fld_bb_grcode'] = $fld_bb_grcode;
                $data['fld_bb_plant']  = $fld_bb_plant;
                $data['fld_bb_wshe']   = $fld_bb_wshe;
                return $data;
            }

            
        }//endfunc


            public function download_gr_barcode(){
                $cuser = $this->mylibzdb->mysys_user();
                $mpw_tkn = $this->mylibzdb->mpw_tkn();
                $mpohd_rid = $this->request->getVar('grtrx_no');
                $active_wshe_id = $this->request->getVar('active_wshe_id');

                $chtmljs ="";

                $wsheData = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($active_wshe_id);
                $_valid_wshe_id = $wsheData['whID'];
                
                if($mpohd_rid != ''){
                    
                    $file_name = "grbarcodereports_{$mpohd_rid}_{$cuser}_" . $this->mylibzsys->random_string(9);
                    $mpathdn   = ROOTPATH;
                    $_csv_path = '/public/downloads/me/';
                    $filepath = $mpathdn.$_csv_path.$file_name.'.txt';
                    $cfilelnk = site_url() . '/downloads/me/' . $file_name.'.txt';
                    $file_name_temp = "barcodereports.txt";
            
                    $str="  
                    SELECT oa.* INTO OUTFILE '{$filepath}'
                    FIELDS TERMINATED BY '\t' 
                    LINES TERMINATED BY '\r\n'  
                    FROM (
                        SELECT
                            a.`header`,
                            (SELECT bb.`qty` FROM {$this->db_erp}.`trx_wshe_gr_barcdng_dt` bb WHERE bb.`stock_code` = a.`stock_code` AND bb.`mat_rid`= a.`mat_rid` AND bb.`header` = '{$mpohd_rid}'
                            AND bb.`to_wshe_id` = '{$_valid_wshe_id}' GROUP BY bb.`stock_code`) tqty,
                            a.`stock_code`,
                            b.`ART_CODE`,
                            b.`ART_DESC`,
                            b.`ART_SKU`,
                            a.`irb_barcde`,
                            a.`srb_barcde`,
                            a.`witb_barcde`,
                            a.`wob_barcde`,
                            a.`pob_barcde`,
                            a.`dmg_barcde`,
                            CONCAT(a.`box_no`,'/',(SELECT bb.`qty` FROM {$this->db_erp}.`trx_wshe_gr_barcdng_dt` bb WHERE bb.`stock_code` = a.`stock_code` AND bb.`mat_rid`= a.`mat_rid` AND bb.`header` = '{$mpohd_rid}'
                            AND bb.`to_wshe_id` = '{$_valid_wshe_id}' GROUP BY bb.`stock_code`)) box_no,
                            GROUP_CONCAT(ee.`ART_CODE` ORDER BY ee.`ART_CODE` ASC SEPARATOR ', ') __boxcontent,
                            c.`wshe_code`,
                            a.`convf`
                            FROM
                            {$this->db_erp}.`trx_wshe_gr_barcdng_dt` a
                            JOIN {$this->db_erp}.`trx_wshe_gr_barcdng_item` dd
                            ON (a.`recid` = dd.`dt_id`)
                            JOIN {$this->db_erp}.`mst_article` b
                            ON (a.`mat_rid` = b.`recid`)
                            JOIN {$this->db_erp}.`mst_article` ee
                            ON (dd.`mat_rid` = ee.`recid`)
                            JOIN {$this->db_erp}.`mst_wshe` c
                            ON (a.`to_wshe_id` = c.`recid`)
                            WHERE
                            a.`trx` ='GRI'
                            AND
                            a.`header` = '{$mpohd_rid}'
                            AND a.`to_wshe_id` = '{$_valid_wshe_id}'
                            GROUP BY a.`witb_barcde`
                            ORDER BY a.`recid`

                    ) oa       

                    ";
              
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'GR_DL_BARCODE',$mpohd_rid,$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    
                    $chtmljs .= "<div class=\"alert alert-success\"><strong>DOWNLOAD</strong><br>Box Barcode successfully download. <br> GR NO: <p style=\"color:red;display:inline-block; \">{$mpohd_rid}</p> </div>
                            <script type=\"text/javascript\">
                                //window.parent.document.getElementById('myscrloading').innerHTML = '';
                                // function download(filename, text) {
                                //   var element = document.createElement('a');
                                //   element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
                                //   element.setAttribute('download', filename);

                                //   element.style.display = 'none';
                                //   document.body.appendChild(element);

                                //   element.click();

                                //   document.body.removeChild(element);
                                // } 
                                pobarcode_dl('{$cfilelnk}','{$file_name_temp}');
                                function pobarcode_dl(fileName,file_name) {
                                    //Set the File URL.
                                    var url = fileName;
                         
                                    $.ajax({
                                        url: url,
                                        cache: false,
                                        xhr: function () {
                                            var xhr = new XMLHttpRequest();
                                            xhr.onreadystatechange = function () {
                                                if (xhr.readyState == 2) {
                                                    if (xhr.status == 200) {
                                                        xhr.responseType = 'blob';
                                                    } else {
                                                        xhr.responseType = 'text';
                                                    }
                                                }
                                            };
                                            return xhr;
                                        },
                                        success: function (data) {
                                            //Convert the Byte Data to BLOB object.
                                            var blob = new Blob([data], { type: 'application/octetstream' });
                         
                                            //Check the Browser type and download the File.
                                            var isIE = false || !!document.documentMode;
                                            if (isIE) {
                                                window.navigator.msSaveBlob(blob, fileName);
                                            } else {
                                                var url = window.URL || window.webkitURL;
                                                link = url.createObjectURL(blob);
                                                var a = $('<a />');
                                                a.attr('download', file_name);
                                                a.attr('href', link);
                                                $('body').append(a);
                                                a[0].click();
                                                $('body').remove(a);
                                            }
                                        }
                                    });
                                };

                                
                                //function pobarcode_dl() { 
                                    //jQuery('#lnkexportmsexcel_lbbd_sku').click(function() { 
                                    //jQuery('#messproc').css({display:''});
                                    //window.location = '{$cfilelnk}';
                                    //window.location.href='data:application/octet-stream;base64,'+Base64.encode('{$cfilelnk}');
                                    // Start file download.
                                    //download('barcodereports.txt',load('{$cfilelnk}'));
                                    //});
                                //}
                                // jQuery('#lnkexportmsexcel').click(function() { 
                                //  //jQuery('#messproc').css({display:''});
                                //  window.location = '{$cfilelnk}';
                                // });
                                jQuery('#lnktoprint').click(function() { 
                                    jQuery('#__mtoexport').css({display:'none'});
                                    //jQuery('#__mtoprint').css({display:'none'});
                                    window.print();         
                                });
                            </script>
                            
                            ";
                    $q->freeResult();
                    //file_put_contents( $cfiledest , $chtml , FILE_APPEND | LOCK_EX );
                    echo $chtmljs;
                }
            }

     public function view_wf_recs($npages = 1,$npagelimit = 30){
            $cuser = $this->mylibzdb->mysys_user();
            $mpw_tkn = $this->mylibzdb->mpw_tkn();
            /*var_dump($fld_grbranch);
           */
              $cusergrp = $this->mylibzdb->mysys_usergrp();

            $fld_grdtfrm = $this->mylibzsys->mydate_yyyymmdd($this->request->getVar('fld_wf_dtefrom'));
            $fld_grdtto = $this->mylibzsys->mydate_yyyymmdd($this->request->getVar('fld_wf_dteto'));

            $__flag="C";
            $str_brnch = "";
            $str_date = "";
            //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
            $str_vwrecs = "";

           
            if((!empty($fld_grdtfrm) && !empty($fld_grdtto)) && (($fld_grdtfrm != '--') && ($fld_grdtto != '--'))) {
                $str_date = " AND (SUBSTRING_INDEX(aa.`gr_date`,' ',1) >= DATE('{$fld_grdtfrm}') AND  SUBSTRING_INDEX(aa.`gr_date`,' ',1) <= DATE('{$fld_grdtto}'))";
            }
            if(((!empty($fld_grdtfrm) && !empty($fld_grdtto)) && (($fld_grdtfrm != '--') && ($fld_grdtto != '--'))) || (!empty($fld_grbranch) && !empty($fld_grbranch_id))){
                $strqry = "
                SELECT
                aa.`recid` __arid,
                aa.`grtrx_no`,
                aa.`comp_id`,
                aa.`plant_id`,
                aa.`gr_date`,
                aa.`remk`,
                aa.`hd_subtqty`,
                aa.`hd_subtcost`,
                aa.`hd_subtamt`,
                aa.`muser`,
                aa.`encd_date`,
                aa.`flag`,
                aa.`p_flag`,
                aa.`df_tag`,
                aa.`post_tag`,
                aa.`is_apprvd`,
                bb.`COMP_NAME`,
                dd.`plnt_code`,
                ee.`wshe_code`,
                sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) mtkn_arttr,
                sha2(concat(aa.`wshe_id`,'{$mpw_tkn}'),384) wshe_id 
                FROM {$this->db_erp}.`trx_wshe_gr_hd` aa
                JOIN {$this->db_erp}.`mst_company` bb
                ON (aa.`comp_id` = bb.`recid`)
                JOIN {$this->db_erp}.`mst_plant` dd
                ON (aa.`plant_id` = dd.`recid`)
                JOIN {$this->db_erp}.`mst_wshe` ee
                ON (aa.`wshe_id` = ee.`recid`)
                where aa.`flag` != '$__flag' AND aa.`post_tag` = 'N' AND aa.`df_tag`='F' AND `is_apprvd`='N' AND aa.`cd_tag` = 'Y'
                {$str_brnch} {$str_date} {$str_vwrecs}
                ";
                
    
                $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
                
                if($qry->getNumRows() > 0) { 
                    $data['rlist'] = $qry->getResultArray();
                    $data['fld_grdtfrm'] = $fld_grdtfrm;
                    $data['fld_grdtto'] = $fld_grdtto;
                } else { 
                    $data = array();
                    $data['rlist'] = '';
                    $data['fld_grdtfrm'] = $fld_grdtfrm;
                    $data['fld_grdtto'] = $fld_grdtto;
                }
                return $data;
            }
            else{
                $data = array();
                $data['rlist'] = '';
                $data['fld_grdtfrm'] = $fld_grdtfrm;
                $data['fld_grdtto'] = $fld_grdtto;

                return $data;
            }
            
        }//endfunc


        public function gr_approving(){
            $mtkn_trxno = $this->request->getVar('mtkn_trxno');
            $id_post = $this->request->getVar('id_appr');
            $gr_code = $this->request->getVar('gr_code');
            $wshe_id = $this->request->getVar('wshe_id');
            $cuser = $this->mylibzdb->mysys_user();
            $mpw_tkn = $this->mylibzdb->mpw_tkn();
            $rems = '';
           
            if(empty($mtkn_trxno)){
                $this->mymelibzsys->warning_msg("#dc3545","text-danger","Transaction no. is empty.");
                die();
            }

            //CHECK KUNG NKAPAGAPPROVED NA SYA
            $str = "SELECT recid from {$this->db_erp}.`trx_wshe_gr_hd` 
            WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$mtkn_trxno' AND `IS_APPRVD` = 'Y'";
            
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() > 0) { 
                $this->mymelibzsys->warning_msg("#dc3545","text-danger","You already approved this transaction!");
                die();
            }
                
            $str = "
            UPDATE {$this->db_erp}.`trx_wshe_gr_hd`
            SET `is_apprvd` = 'Y',
                `apprvd_by` = '{$cuser}'
            WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$mtkn_trxno' AND `is_apprvd` = 'N';
            ";

            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_UPD_GR_APPROVING',$cuser,$gr_code,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            

            // ADD MODULE HERE FOR UPDATE
            $str = "SELECT grtrx_no,ref_no,grtype_id FROM {$this->db_erp}.`trx_wshe_gr_hd` 
            WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$mtkn_trxno' AND `is_apprvd` = 'Y'";
            $q6 = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q6->getNumRows() > 0) {
                $rw6        = $q6->getRowArray();
                $ref_no     = $rw6['ref_no'];
                $grtrx_no   = $rw6['grtrx_no'];
                $grtype_id  = $rw6['grtype_id'];
                $po_type    = 'T';
                if(($ref_no != '') && ($grtype_id == 3)){ //RETURN TO MAPULA ONLY
                    //ALAMIN KUNG TRADE NON TRADE
                    $str = "SELECT potrx_no,po_type FROM {$this->db_erp}.`trx_manrecs_po_hd` 
                    WHERE `potrx_no` = '$ref_no' AND `post_tag` = 'Y'";
                    $q7 = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $rw7        = $q7->getRowArray();
                    
                    if($q7->getNumRows() > 0) { 
                        $rw7        = $q7->getRowArray();
                        $po_type   = $rw7['po_type'];
                    }
                    if($po_type == 'T'){
                        $str = "
                        UPDATE {$this->db_erp}.`trx_manrecs_po_dt` a,{$this->db_erp}.`trx_wshe_gr_dt` b
                        SET a.`qty` = (b.`imat_qty` + b.`amat_dmg`)
                        WHERE (a.`mat_code` = b.`mat_code`)
                        AND a.`potrx_no` = '$ref_no' 
                        AND b.`grtrx_no` = '$grtrx_no'
                        ";
                    }
                    else{
                        $str = "
                        UPDATE {$this->db_erp}.`trx_manrecs_po_dt` a,{$this->db_erp}.`trx_wshe_gr_dt` b
                        SET a.`qty_encd` = (b.`imat_qty` + b.`amat_dmg`)
                        WHERE (a.`mat_code` = b.`mat_code`)
                        AND a.`potrx_no` = '$ref_no' 
                        AND b.`grtrx_no` = '$grtrx_no'
                        ";
                    }
                    

                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_UPD_PO_QTYENCD',$cuser,$ref_no,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    //UPDATE LOGS
                   $this->mymelibzsys->upd_logs_pullout_gr($this->db_erp,'CD_UPD_PULLOUT_QTYENCD',$ref_no,$grtrx_no,$po_type,'',$str);

                    $str = "
                    UPDATE {$this->db_erp}.`trx_tpd_svfpout_dt` a
                    SET a.`tpd_tag` = 'Y'
                    WHERE a.`pullout_trx_no` = '$ref_no'
                    ";

                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_UPD_TPD_ISDELIVER',$cuser,$ref_no,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    //UPDATE LOGS
                    $this->mymelibzsys->upd_logs_tpd_pullout_gr($this->db_erp,'CD_UPD_TPD_TAG',$ref_no,$grtrx_no,$po_type,$str);
                }//endif
            }//q6

            echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Posted successfully!!!</div>";

        }//end func


    public function gr_barcde_gnrtion() { 
        $cuser = $this->mylibzdb->mysys_user();
        $cuser_lvl = $this->mylibzdb->mysys_userlvl();

        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $gr_CFRM_TAG='Y';
        $gr_code =$this->request->getVar('mtkn_grtr');

        /*if($cuser_lvl != 'S'){
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Access denied</strong> You are not allowed to proceed this Transaction.</div>";
            die();
        }*/

        if(!empty($gr_code)){
            $str2 = "
            SELECT `is_bcodegen`,`is_apprvd`,`grtrx_no` FROM {$this->db_erp}.`trx_wshe_gr_hd`
            where `is_apprvd` = '$gr_CFRM_TAG' AND `cd_tag` = 'Y' AND sha2(concat(`recid`,'{$mpw_tkn}'),384) = '{$gr_code}'";
            //var_dump($str2);
            $q =$this->mylibzdb->myoa_sql_exec($str2,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid PO Data!!!.</div>";
                die();
            }
            $rw = $q->getRowArray();
            $gr_trxno = $rw['grtrx_no'];
            $gr_CFRM_TAG=$rw['is_apprvd'];
            $is_bcodegen=$rw['is_bcodegen'];
            $q->freeResult();
            //var_dump($gr_CFRM_TAG);
            if($is_bcodegen === 'Y'){
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Failed </strong>Barcode already generated!!!</div>";
                die();
            }
            //BEGIN

            if($gr_CFRM_TAG == 'Y'){

                //barcoding
                //get gr data

                $str = "
                    SELECT 
                    recid,
                    grtrx_no,
                    class_id,
                    comp_id,
                    plant_id,
                    wshe_id,
                    bin_id,
                    rack_id 
                    FROM
                    {$this->db_erp}.`trx_wshe_gr_hd`
                    WHERE
                        `is_apprvd` = 'Y'
                    AND
                        `grtrx_no` = '{$gr_trxno}'
                ";

                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                $rr = $q->getRowArray();
                $_valid_gr_id = $rr['recid'];
                $_valid_gr_code = $rr['grtrx_no'];
                $mpocls_rid = $rr['class_id'];
                $comp_id = $rr['comp_id'];

                $plnt_id = $rr['plant_id']; 
                $wshe_id = $rr['wshe_id']; 
                $wshe_sbin_id = $rr['bin_id']; 
                $wshe_grp_id = $rr['rack_id']; 
                //UPDATE TAG FOR DONE GENERATION
                $str = "
                    UPDATE {$this->db_erp}.`trx_wshe_gr_hd`
                    SET `is_bcodegen` = 'Y'
                    WHERE `grtrx_no` ='$_valid_gr_code'
                ";
                //update to moto
               $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
               $this->mylibzdb->user_logs_activity_module($this->db_erp,'gr_BCODEGEN','',$_valid_gr_code,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                $str = "
                    SELECT recid,grhd_rid,qty,mat_rid,SUM(imat_qty) convf,SUM(tcost) tamt,ucost uprice  
                    FROM
                    {$this->db_erp}.`trx_wshe_gr_dt`
                    WHERE
                    `grhd_rid` = {$_valid_gr_id}
                    GROUP BY mat_rid
                    HAVING  (SUM(qty) > 0 AND SUM(imat_qty) > 0 AND SUM(imat_convf ) > 0 ) 
                ";

                $boxquery = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


                //insert into barcoding
                $str = "
                    INSERT INTO
                    {$this->db_erp}.`trx_wshe_gr_barcdng_hd`(
                        `trx`,
                        `header`,
                        `muser`,
                        `encd`
                    )
                    VALUES(
                        'GRI',
                        '{$_valid_gr_code}',
                        '{$cuser}',
                        now()
                    )
                ";
                $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                $str = "
                    SELECT recid,header
                    FROM
                    {$this->db_erp}.`trx_wshe_gr_barcdng_hd`
                    WHERE
                    `trx` = 'GRI'
                    AND
                    `header` = '{$_valid_gr_code}'
                ";

                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                $rr = $q->getRowArray();
                $_wshe_barcdng_rid = $rr['recid'];
                $_wshe_barcdng_header = $rr['header'];


                    foreach($boxquery->getResultArray() as $rr){
                        $mat_rid_hd = $rr['mat_rid'];
                        $qty2 = $rr['qty']; // '1';// lagi 1 kasi bahala na doon sa ilalim kung ilan qty
                        
                        $gr_tamt = $rr['tamt'];
                        //$tqty = $rr['tqty']; 
                        $total_amount = $gr_tamt/$qty2;
                        
                        //PARA SA STOCKCODE
                        $cseqn_stock = $this->mydataz->get_ctr_new($mpocls_rid,$comp_id,$this->db_erp,'CTRL_NO01');
                        //insert no of box first
                        $box_no = 1;
                        $cseqn_new = $this->mydataz->get_ctr_barcoding($this->db_erp,'CTRL_NO01');
                        $str = "
                            SELECT a.`recid`,a.`grhd_rid`,a.`qty`,a.`mat_rid`,SUM(a.`imat_qty`) convf,tcost tamt,a.`ucost` uprice,b.`ART_NCBM` cbm,a.`nremarks`
                            FROM
                            {$this->db_erp}.`trx_wshe_gr_dt` a
                            JOIN {$this->db_erp}.`mst_article` b
                            ON (a.`mat_rid` =  b.`recid`)
                            WHERE
                            `grhd_rid` = {$_valid_gr_id}
                            AND 
                            `mat_rid` ={$mat_rid_hd}
                        
                        ";

                        $boxquery_details = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        foreach($boxquery_details->getResultArray() as $row){

                                $_valid_gr_dt_id = $row['recid']; 
                                $_valid_gr_id = $row['grhd_rid']; 
                                $qty = $row['qty'];
                                $no_of_box = $row['qty'];
                                $mat_rid = $row['mat_rid']; 
                                $convf = $row['convf']; 
                                // $total_pcs = $row['total_pcs']; 
                                $price = $row['uprice']; 
                                $cbm = $row['cbm']; 
                                $remarks = $row['nremarks']; 

                                

                                for($i = 0; $i < $no_of_box; $i++){
                                    
                                    $box_id = str_pad($box_no,4, "0", STR_PAD_LEFT);

                                    $irb_barcde = '1111'.$cseqn_new.$box_id;    
                                    $srb_barcde = '2222'.$cseqn_new.$box_id;    
                                    $witb_barcde = '3333'.$cseqn_new.$box_id;    
                                    $wob_barcde = '4444'.$cseqn_new.$box_id;    
                                    $pob_barcde = '5555'.$cseqn_new.$box_id;    
                                    $dmg_barcde = '6666'.$cseqn_new.$box_id;

                                    $str = "
                                        INSERT INTO
                                        {$this->db_erp}.`trx_wshe_gr_barcdng_dt`(
                                            `trx`,
                                            `header_id`,
                                            `header`,
                                            `stock_code`,
                                            `barcde`,
                                            `irb_barcde`,
                                            `srb_barcde`,
                                            `witb_barcde`,
                                            `wob_barcde`,
                                            `pob_barcde`,
                                            `dmg_barcde`,
                                            `frm_plnt_id`,
                                            `frm_wshe_id`,
                                            `frm_wshe_sbin_id`,
                                            `frm_wshe_grp_id`,
                                            `to_plnt_id`,
                                            `to_wshe_id`,
                                            `to_wshe_sbin_id`,
                                            `to_wshe_grp_id`,
                                            `box_no`,
                                            `mat_rid`,
                                            `qty`,
                                            `convf`,
                                            -- `uom`,
                                            `cbm`,
                                            `total_pcs`,
                                            `total_amount`,
                                            `remarks`,
                                            `muser`,
                                            `encd`
                                        )
                                        VALUES(
                                            'GRI',
                                            {$_wshe_barcdng_rid},
                                            '{$_wshe_barcdng_header}',
                                            '{$cseqn_stock}',
                                            '{$cseqn_new}',
                                            '{$irb_barcde}',
                                            '{$srb_barcde}',
                                            '{$witb_barcde}',
                                            '{$wob_barcde}',
                                            '{$pob_barcde}',
                                            '{$dmg_barcde}',
                                            {$plnt_id},
                                            {$wshe_id},
                                            {$wshe_sbin_id},
                                            {$wshe_grp_id},
                                            {$plnt_id},
                                            {$wshe_id},
                                            {$wshe_sbin_id},
                                            {$wshe_grp_id},
                                            {$box_no},
                                            {$mat_rid},
                                            {$qty2},
                                            {$convf},
                                            {$cbm},
                                            {$convf},
                                            {$total_amount},
                                            '{$remarks}',
                                            '{$cuser}',
                                            now()
                                        )
                                    ";
                                    //var_dump($total_amount);
                                    //die();
                                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                    
                                    //get barcoding data first
                                    $str = "
                                        SELECT recid,header_id,header,mat_rid
                                        FROM
                                        {$this->db_erp}.`trx_wshe_gr_barcdng_dt`
                                        WHERE
                                        `irb_barcde` = '{$irb_barcde}'
                                        AND
                                        `header_id` = {$_wshe_barcdng_rid}
                                        AND
                                        `header` = '{$_wshe_barcdng_header}'
                                        AND 
                                        `mat_rid` = '{$mat_rid}'
                                    ";

                                    $boxqq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                                    $rr = $boxqq->getRowArray();
                                    $_wshe_dt_id = $rr['recid'];
                                    $_wshe_hd_id = $rr['header_id'];
                                    $_wshe_hd = $rr['header'];
                                    $_mat_rid = $rr['mat_rid'];


                                    //get order item
                                    $str = "
                                        SELECT a.`imat_rid`,SUM(a.`imat_qty`) qty,b.`ART_UCOST` price,SUM(a.`imat_qty` * b.`ART_UCOST` ) total_amount
                                        FROM
                                        {$this->db_erp}.`trx_wshe_gr_dt` a
                                        JOIN {$this->db_erp}.`mst_article` b
                                        ON (a.`imat_rid` =  b.`recid`)
                                        WHERE 
                                        `grhd_rid` = {$_valid_gr_id}
                                        AND `mat_rid` = '{$_mat_rid}'
                                        GROUP BY a.`imat_rid`
                                        HAVING  (SUM(qty) > 0 AND SUM(imat_qty) > 0 AND SUM(imat_convf ) > 0 ) 
                                    ";

                                    $itemq  = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                                    //automatic assorted if num rows > 0
                                    if($itemq->getNumRows() > 0){
                                        foreach($itemq->getResultArray() as $row){
                                            $_mat_rid = $row['imat_rid']; 
                                            $_qty = $row['qty'];  
                                            $_price = $row['price']; 
                                            $_total_amount = $row['total_amount']; 

                                            $str = "
                                                INSERT INTO {$this->db_erp}.`trx_wshe_gr_barcdng_item`(
                                                    `header`,
                                                    `header_id`,
                                                    `dt_id`,
                                                    `mat_rid`,
                                                    `qty`,
                                                    `price`,
                                                    `total_amount`,
                                                    -- `remarks`,
                                                    `muser`,
                                                    `encd`
                                                ) 
                                                VALUES(
                                                    '{$_wshe_hd}',
                                                    {$_wshe_hd_id},
                                                    {$_wshe_dt_id},
                                                    {$_mat_rid},
                                                    {$_qty},
                                                    {$_price},
                                                    {$_total_amount},
                                                    '{$cuser}',
                                                    now()
                                                )
                                            ";

                                            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                                        }//end if foreach($itemq->getResultArray()
                                    }//end if $itemq->getNumRows
                                    /*else{
                                        $str = "
                                            INSERT INTO {$this->db_erp}.`trx_wshe_gr_barcdng_item`(
                                                `header`,
                                                `header_id`,
                                                `dt_id`,
                                                `mat_rid`,
                                                `qty`,
                                                `price`,
                                                `total_amount`,
                                                -- `remarks`,
                                                `muser`,
                                                `encd`
                                            ) 
                                            VALUES(
                                                '{$_wshe_hd}',
                                                {$_wshe_hd_id},
                                                {$_wshe_dt_id},
                                                {$mat_rid},
                                                {$convf},
                                                {$price},
                                                {$total_amount},
                                                '{$cuser}',
                                                now()
                                            )
                                        ";

                                        $q = $this->mylibz->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                    }//else*/
                                    $itemq->freeResult();
                                    $box_no++;
                                }//for
                        }//foreach -------------------------------------------------------------------------------------------------------------------2
                        $boxquery_details->freeResult();
                    }//foreach -------------------------------------------------------------------------------------------------------------------1
                    $boxquery->freeResult();
                    echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Info.</strong> Successfully generated!!!.</div>";
            }//if($gr_CFRM_TAG == 1){
            

        }//endif $gr_trxno
    
        
        
    } //end migupldtmpl_proc


    public function view_rcvng_recs($npages = 1,$npagelimit = 30,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_whse = $this->request->getVar('mtkn_whse');


        //PARA SA MGA ADMINSITRATOR LANG
        $cusergrp = $this->mylibzdb->mysys_usergrp();
        /*if($cusergrp != 'SA'){
            $data = "</br><div class=\"col-md-3 alert alert-warning\"><strong>Note:</strong><br>Only administrative users can view this records.</div>";
            echo $data;
            $data = array();
            $data['npage_count'] = 1;
            $data['npage_curr'] = 1;
            $data['rlist'] = '';
            return $data;
        }*/

        $wsheData = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
        $fld_plant = $wsheData['plntID'];
        $fld_wshe = $wsheData['whID'];


        $__flag="C";
        $str_optn = "";
        //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
        $str_vwrecs = "AND aa.`muser` = '$cuser'";
        if($cusergrp == 'SA'){
            $str_vwrecs = "";
        }
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " AND (aa.`grtrx_no` like '%$msearchrec%' OR aa.`muser` like '%$msearchrec%' OR ee.`wshe_code` like '%$msearchrec%' OR bb.`COMP_NAME` like '%$msearchrec%') AND aa.flag != '$__flag' {$str_vwrecs}";
        }
        if(empty($msearchrec)) {
            $str_optn = " AND aa.flag != '$__flag' {$str_vwrecs}";
        } 

       
        $strqry = "
        SELECT aa.`grtrx_no`,aa.`muser`,aa.`encd_date`,aa.`recid`,
        dd.`plnt_code`,
        ee.`wshe_code`,
        sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) mtkn_arttr,
        (SELECT COUNT(b.`witb_barcde`) dt_qty FROM `trx_wshe_gr_barcdng_dt` b WHERE b.`header`  = aa.`grtrx_no` AND b.`to_plnt_id` = aa.`plant_id` AND b.`to_wshe_id` = aa.`wshe_id`) as dt_qty,
        (SELECT COUNT(b.`witb_barcde`) qty_rcv FROM `warehouse_inv_rcv` b WHERE b.`header`  = aa.`grtrx_no` AND b.`plnt_id` = aa.`plant_id` AND b.`wshe_id` = aa.`wshe_id` ) AS rcv_qty
        FROM {$this->db_erp}.`trx_wshe_gr_hd` aa
        JOIN {$this->db_erp}.`mst_plant` dd ON (aa.`plant_id` = dd.`recid`)
        JOIN {$this->db_erp}.`mst_wshe` ee ON (aa.`wshe_id` = ee.`recid`)
        WHERE aa.`cd_tag` = 'Y' AND aa.`is_apprvd` = 'Y' AND aa.`is_bcodegen` = 'Y' AND  aa.`wshe_id` = '{$fld_wshe}' AND aa.`plant_id` = '{$fld_plant}'
        {$str_optn} 
        order by recid desc 
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
    }

        public function wh_gr_rcvng_upld(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $txtprod_type_upld_sub = $this->request->getVar('txtprod_type_upld_sub');
        $txtWarehouse   =  $this->request->getVar('txtWarehouse'); 
        $txtWarehousetkn   =  $this->request->getVar('txtWarehousetkn'); 
        $grNo   =  $this->request->getVar('grNo'); 

        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end

        $type = "";
        $insertSubTag = 0;
        $nrecs_pb     = 0;
        $invalidUnit  = 0 ;


        //get items
        $str_itm = "SELECT
                c.`ART_CODE` AS `mat_code`,
                c.`ART_DESC`,
                c.`ART_SKU` as `ART_UOM`,
                b.`convf`,
                CASE
                    WHEN
                        COUNT(REPLACE(REPLACE(REPLACE(b.`witb_barcde`, ' ', ''), '\t', ''), '\n', '')) > b.`qty`
                    THEN
                        b.`qty`
                    ELSE
                        COUNT(REPLACE(REPLACE(REPLACE(b.`witb_barcde`, ' ', ''), '\t', ''), '\n', ''))
                END     
                AS `qty_scanned`,
                b.`total_pcs` AS `total_pcs_scanned`,
                b.`total_amount` as `price`,
                b.`total_amount` as `tamt`,
                (b.`convf` * COUNT(REPLACE(REPLACE(REPLACE(b.`witb_barcde`, ' ', ''), '\t', ''), '\n', ''))) * b.`total_amount` AS `tamt_scanned`,
                d.`plnt_code`,
                e.`wshe_code`,
                f.`wshe_bin_name`,
                b.`witb_barcde` AS `barcde`,
                b.`irb_barcde`,
                b.`remarks`,
                b.`witb_barcde` AS `witb_barcde`,
                b.`srb_barcde` AS `srb_barcde`,
                b.`wob_barcde` AS `wob_barcde`,
                b.`pob_barcde` AS `pob_barcde`,
                b.`dmg_barcde` AS `dmg_barcde`,
                '' AS `barc_type`,
                b.`stock_code`,
                b.`header` as `barcde_series`,
                b.`cbm`,
                b.`recid` as `wshe_barcdng_dt_id`,

                c.`recid` AS `mat_rid`,
                d.`recid` AS `plnt_id`,
                e.`recid` AS `wshe_id`,
                f.`recid` AS `wshe_sbin_id`

                FROM
                    {$this->db_erp}.`trx_wshe_gr_barcdng_dt` b
                JOIN
                    {$this->db_erp}.`mst_article` c
                ON
                    b.`mat_rid` = c.`recid`
                JOIN
                    {$this->db_erp}.`mst_plant` d
                ON
                    b.`to_plnt_id` = d.`recid`
                JOIN
                    {$this->db_erp}.`mst_wshe` e
                ON
                    b.`to_wshe_id` = e.`recid`
                JOIN
                    {$this->db_erp}.`mst_wshe_bin` f
                ON
                    b.`to_wshe_sbin_id` = f.`recid`
                JOIN
                    {$this->db_erp}.`trx_wshe_gr_barcdng_hd` g
                ON
                    b.`header` = g.`header`
                WHERE
                    b.`to_plnt_id` = {$plntID}
                AND
                    b.`to_wshe_id` = {$whID}
               AND
                    REPLACE(REPLACE(REPLACE(b.`witb_barcde`, ' ', ''), '\t', ''), '\n', '') <> ''
                AND
                    b.`rcv_tag` = '0'
                AND
                    b.`header` = '{$grNo}'
                 AND YEAR(b.`encd`) >= '2022'
                GROUP BY REPLACE(REPLACE(REPLACE(b.`witb_barcde`, ' ', ''), '\t', ''), '\n', '')
                ORDER BY
                    b.`stock_code`
                         ";



        $q3 = $this->mylibzdb->myoa_sql_exec($str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($q3->getNumRows() > 0 )
        {

            $data['result'] = $q3->getResultArray();
            $data['count'] = count($q3->getResultArray());
            $data['gr_code'] = $grNo;

        }
        else
        {

            $data['result'] = '';
            $data['count']  = 0;
            $data['gr_code'] = $grNo;

        }
        $data['response'] = true;
        return $data;
 
        
    }  //end simpleupld_proc

    public function mywh_gr_rcvng_save(){

        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $data_array = $this->request->getVar('data_array');
        $rowCount = $this->request->getVar('rowCount');
        $txtWarehousetkn   =  $this->request->getVar('txtWarehousetkn'); 

        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end
      
        if(empty($data_array))
        {
            $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>No items to be receive.</div>";
        }
        //CHECK BARCODES IF EXIST IN BARCODING DT
        $str = "
            SELECT 
                count(`irb_barcde`) brcde_count
            FROM
                {$this->db_erp}.`trx_wshe_gr_barcdng_dt`
            WHERE
               `witb_barcde` IN ($data_array) AND `frm_plnt_id` = $plntID AND `frm_wshe_id` = $whID
        ";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($q->getNumRows() == 0){
            $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Invalid barcoding data.</div>";
            echo $data;
            die();
        
        }
     
        $brcd_ck = $q->getRowArray();
        $dcount = $brcd_ck['brcde_count'];

        if($dcount != $rowCount){
            $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Invalid barcoding data.</div>";
            echo $data;
            die();
        }

        //CHECK BARCODES IF EXIST IN BARCODING DT END

        //CHECKING IF ALREADY RECIEVED
        $str = "
            SELECT 
               GROUP_CONCAT(`witb_barcde` SEPARATOR '<br>') witb_exist
            FROM
                {$this->db_erp}.`warehouse_inv_rcv`
            WHERE
                `witb_barcde` IN ($data_array) 
        ";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $exrow = $q->getRowArray();
        $witb_exist = $exrow['witb_exist'];
        if(!empty($witb_exist)){
        $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Barcode [ {$witb_exist} ] is already received.</div>";
        echo $data;
        die();
        }
        
        //CHECKING IF ALREADY RECIEVED END


        //create header transaction

        $_hd_ctrlno = $this->mydataz->get_ctr($this->db_erp,'CTRL_CWR');
        $qty = 1;
        //insert to logs
        $str = "
            INSERT INTO
        {$this->db_erp}.`warehouse_inv_rcv`(
            `trx`,
            `header`,
            `stock_code`,
            `barcde`,
            `irb_barcde`,
            `srb_barcde`,
            `witb_barcde`,
            `wob_barcde`,
            `pob_barcde`,
            `dmg_barcde`,
            `plnt_id`,
            `wshe_id`,
            `wshe_sbin_id`,
            `wshe_grp_id`,
            `box_no`,
            `mat_rid`,
            `qty`,
            `convf`,
            `cbm`,
            `total_pcs`,
            `total_amount`,
            `remarks`,
            `muser`,
            `encd`
        )
        SELECT 
            '{$_hd_ctrlno}',
            `header`,
            `stock_code`,
            `irb_barcde`,
            `irb_barcde` irb,
            `srb_barcde`,
            `witb_barcde`,
            `wob_barcde`,
            `pob_barcde`,
            `dmg_barcde`,
            `frm_plnt_id`,
            `frm_wshe_id`,
            `frm_wshe_sbin_id`,
            `frm_wshe_grp_id`,
            `box_no`,
            `mat_rid`,
            '{$qty}',
            `convf`,
             `cbm`,
            `convf` conv,
            `total_amount`,
            `remarks`,
            '{$cuser}',
            now() 
            FROM {$this->db_erp}.`trx_wshe_gr_barcdng_dt`
            WHERE  `witb_barcde` IN ($data_array) 
            AND `to_plnt_id` = {$plntID}
            AND `to_wshe_id` = {$whID}
            AND YEAR(`encd`) >= '2022'  ";

            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            //insert to logs end

        //insert in central

        $str = "
        INSERT INTO
        {$this->db_erp}.`warehouse_inv_rcv_logs`(
            `wshe_inv_id`,
            `trx`,
            `header`,
            `stock_code`,
            `barcde`,
            `irb_barcde`,
            `srb_barcde`,
            `witb_barcde`,
            `wob_barcde`,
            `pob_barcde`,
            `dmg_barcde`,
            `plnt_id`,
            `wshe_id`,
            `wshe_sbin_id`,
            `wshe_grp_id`,
            `box_no`,
            `mat_rid`,
            `qty`,
            `convf`,
            `cbm`,
            `total_pcs`,
            `total_amount`,
            `remarks`,
            `muser`,
            `encd`
        )

        SELECT 
            `recid`,
             `trx`,
             `header`,
             `stock_code`,
             `barcde`,
             `irb_barcde`,
             `srb_barcde`,
             `witb_barcde`,
             `wob_barcde`,
             `pob_barcde`,
             `dmg_barcde`,
             `plnt_id`,
             `wshe_id`,
             `wshe_sbin_id`,
             `wshe_grp_id`,
             `box_no`,
             `mat_rid`,
             `qty`,
             `convf`,
             `cbm`,
             `total_pcs`,
             `total_amount`,
             `remarks`,
             `muser`,
             `encd`
            FROM {$this->db_erp}.`warehouse_inv_rcv`
            WHERE `trx`  = '{$_hd_ctrlno}' AND `plnt_id`= $plntID AND  `wshe_id` = $whID ";

            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
            /***************** AUDIT LOGS *************************/

            $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CENTRAL_RCVNG','OLD PROCESS SAVING CENTRAL',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            /***************** AUDIT LOGS *************************/

            $str = "
                INSERT INTO
                {$this->db_erp}.`warehouse_inv_rcv_item`(
                    `wshe_inv_id`,
                    `witb_barcde`,
                    `mat_rid`,
                    `qty`,
                    `price`,
                    `total_amount`,
                    `muser`,
                    `encd`)
                    SELECT 
                    wdt.`recid`,
                    wdt.`witb_barcde`,
                    im.`mat_rid`,
                    im.`qty`,
                    im.`price`,
                    im.`total_amount`,
                    '{$cuser}',
                    NOW()
                    FROM  {$this->db_erp}.`trx_wshe_gr_barcdng_dt` dt 
                    JOIN  {$this->db_erp}.`warehouse_inv_rcv` wdt ON dt.`witb_barcde` = wdt.`witb_barcde`
                    JOIN  {$this->db_erp}.`trx_wshe_gr_barcdng_item` im ON dt.`recid` = im.`dt_id`
                    WHERE wdt.`trx` = '{$_hd_ctrlno}'
                    AND  dt.`to_plnt_id` = {$plntID}
                    AND dt.`to_wshe_id` = {$whID}
                    AND YEAR(dt.`encd`) >= '2022'
                     ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                /***************** AUDIT LOGS *************************/

                $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_RCVNG_ITEM','',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                $str = "
                    INSERT INTO
                    {$this->db_erp}.`warehouse_inv_rcv_item_logs`(
                        `wshe_inv_id`,
                        `witb_barcde`,
                        `mat_rid`,
                        `qty`,
                        `price`,
                        `total_amount`,
                        `muser`,
                        `encd`
                    )

                    SELECT 
                    dt.`wshe_inv_id`,
                    dt.`witb_barcde`,
                    dt.`mat_rid`,
                    dt.`qty`,
                    dt.`price`,
                    dt.`total_amount`,
                    dt.`muser`,
                    dt.`encd`
                    FROM  {$this->db_erp}.`warehouse_inv_rcv_item` dt 
                    JOIN  {$this->db_erp}.`warehouse_inv_rcv` wdt ON dt.`wshe_inv_id` = wdt.`recid`
                    WHERE wdt.`trx` = '{$_hd_ctrlno}'

                            ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
      

                //update rcv tag in gr barcoding dt

                $str_up = "
                UPDATE  {$this->db_erp}.`trx_wshe_gr_barcdng_dt` dt,{$this->db_erp}.`warehouse_inv_rcv` rcv
                SET  dt.`rcv_tag` = 1  
                WHERE  dt.`witb_barcde` IN ($data_array) AND  dt.`witb_barcde` =  rcv.`witb_barcde` AND rcv.`trx` = '{$_hd_ctrlno}'
                AND dt.`to_wshe_id` = $whID AND dt.`to_plnt_id` = {$plntID} ";
                $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
       

                /***************** AUDIT LOGS *************************/

                $data = "<div class=\"alert alert-success\"><strong>SAVE</strong><br>Transaction successfully saved. <br> TRANSACTION NO: <span style=\"color:red;display:inline-block; \">{$_hd_ctrlno}</span>
                    <p>TOTAL QTY: <span style=\"color:red;display:inline-block; \">{$rowCount}</span></p>
                 </div>";
                echo $data;

            
            
        
    }

    public function view_ent_itm_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $mtkn_dt   = $this->request->getVar('mtkn_dt');
        $grno   = $this->request->getVar('grno');
        
        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end

        
        //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
        $str_vwrecs = "AND a.`muser` = '$cuser'";
    
        $str_optn = '';
        if(!empty($msearchrec)){ 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = "
                AND
                    (rcv.`trx` LIKE '%{$msearchrec}%' OR rcv.`encd` LIKE '%{$msearchrec}%')
            ";
        }

        $strqry = "
        SELECT
        rcv.`recid`,
        rcv.`qty`,
        rcv.`qty`qty_scanned ,
     CASE
        WHEN
            IFNULL(rcv.`qty`,0) = 0
        THEN
            'N/A'
        WHEN
            1 > rcv.`qty`
        THEN
            'LESS'
        WHEN
           1 < rcv.`qty`
        THEN
            'OVER'
        WHEN
           1  = rcv.`qty`
        THEN
            'TALLY'
     END
     AS
     `variance`,
        rcv.`stock_code`,
        art.`ART_CODE`,
        art.`ART_DESC`,
        rcv.`convf`,
        rcv.`total_amount` tamt_scanned,
        '' price,
        rcv.`total_pcs` total_pcs_scanned,
      
        rcv.`witb_barcde`  barcde
        FROM
        {$this->db_erp}.`warehouse_inv_rcv` rcv
        JOIN  {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN  {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        JOIN  {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}'
        AND SHA2(CONCAT(rcv.`header`,'{$mpw_tkn}'),384) = '{$mtkn_dt}'
        GROUP BY rcv.`witb_barcde` ORDER BY `recid` DESC";
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['grno'] = $grno;
         
        } else { 
         $data = array();
         $data['grno'] = $grno;
         $data['rlist'] = '';
         $data['txtsearchedrec_rl'] = $msearchrec;
        }
        return $data;
    } //endfunc

} //end main MyMDCustomerModel