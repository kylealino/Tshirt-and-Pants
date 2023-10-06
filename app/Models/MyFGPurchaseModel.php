<?php
/*
 * Module      :    MyFGPurchaseModel.php
 * Type 	   :    Model
 * Program Desc:    MyFGPurchaseModel
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/
namespace App\Models;
use CodeIgniter\Model;

class MyFGPurchaseModel extends Model
{
	
    public function __construct()
    {
        parent::__construct();
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(0);
        $this->mylibzdb = model('App\Models\MyLibzDBModel');
        $this->mylibzsys = model('App\Models\MyLibzSysModel');
        $this->mymelibzsys = model('App\Models\Mymelibsys_model');
        $this->mydataz = model('App\Models\MyDatumModel');
        $this->dbx = $this->mylibzdb->dbx;
        $this->request = \Config\Services::request();
        // Set orderable column fields
        $this->column_order = array(null, 'stock_code','ART_CODE',`ART_DESC`,null,null,'convf','total_pcs_scanned','tamt_scanned','remarks',null,null,'wshe_bin_name','wshe_grp','barcde','box_no',null,'encd',null,'SD_NO');
        // Set searchable column fields
        $this->column_search = array('rcv.`remarks`','rcv.`stock_code`','art.`ART_CODE`','rcv.`SD_NO`','rcv.`witb_barcde`','sbin.`wshe_bin_name`','grp.`wshe_grp`',
    );
        // Set default order
        $this->order = array('encd' => 'desc');
    }
    
    public function fgpurch_rec_view($npages = 1,$npagelimit = 30,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');
        
        $str_optn = "";
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " WHERE
            (a.`po_sysctrlno` LIKE '%{$msearchrec}%' OR b.`VEND_NAME` LIKE '%{$msearchrec}%' OR a.`rmks` LIKE '%{$msearchrec}%')";
        }


        $strqry = "
        SELECT 
            a.*,
            b.`VEND_NAME` AS `__vend_name`,
            b.`VEND_ICODE` AS `__vend_SUPINCODE`, 
            c.`CUST_NAME` AS `__vends_name`
        FROM
            {$this->db_erp}.`gw_fg_po_hd` a
        JOIN
            {$this->db_erp}.`mst_vendor` b
        ON
            a.`vend_rid` = b.`recid`
        JOIN
            {$this->db_erp}.`mst_customer` c
        ON
            a.`vends_rid` = c.`recid`
        {$str_optn}
        ORDER BY a.`trx_date` DESC
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
        SELECT * from ({$strqry}) oa limit {$nstart},{$npagelimit} ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        if($qry->resultID->num_rows > 0) { 
            $data['rlist'] = $qry->getResultArray();
        } else { 
            $data = array();
            $data['npage_count'] = 1;
            $data['npage_curr'] = 1;
            $data['rlist'] = '';
        }
        return $data;
    } //end fgpurch_rec_view

    public function fgpurch_entry_save() {
        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");  
      
        $mtkn_mntr = $this->request->getVar('mtkn_mntr');
        //$mtkn_mntr = $this->request->getVar('mtkn_mntr');
        $mtkn_wshe_rid = $this->request->getVar('mtkn_wshe_rid');
        $__hmtkn_mntr = '';  //token after saving new PO
        $mtkn_vndrtr = $this->request->getVar('__hmtkn_vndrtr');
        $mtkn_vndsrtr = $this->request->getVar('__hmtkn_vndsrtr');
        //$pocls = $this->request->getVar('pocls');
        $__hmtkn_potr = '';
        $txt_ponumb = $this->request->getVar('txt_ponumb');
        $pocls = $this->request->getVar('txt_po_cls');
        $txt_tdate = $this->request->getVar('txt_tdate');
        $txt_ddate = $this->request->getVar('txt_ddate');
        $txtvend_addr = $this->request->getVar('txtvend_addr');
        $txtvend_code = $this->request->getVar('txtvend_code');
        $txtvend_cont_persn = $this->request->getVar('txtvend_cont_persn');
        $txtvend_cont_persn_desgn = $this->request->getVar('txtvend_cont_persn_desgn');
        $txtvend_cont_persn_cnos = $this->request->getVar('txtvend_cont_persn_cnos');
        $txtvends_code = $this->request->getVar('txtvends_code');
        $txtvends_addr = $this->request->getVar('txtvends_addr');
        $txtvends_cont_persn = $this->request->getVar('txtvends_cont_persn');
        $txtvends_cont_persn_desgn = $this->request->getVar('txtvends_cont_persn_desgn');
        $txtvends_cont_persn_cnos = $this->request->getVar('txtvends_cont_persn_cnos');
        $txt_drlist = $this->request->getVar('txt_drlist');
        $txt_remk = $this->request->getVar('txt_remk');
        $terms = $this->request->getVar('terms');
        $txtpo_totals = $this->request->getVar('txtpo_totals');
        $txtpo_qty = $this->request->getVar('txtpo_qty');
        $txtpo_tsku = $this->request->getVar('txtpo_tsku');

        $mktn_plnt_id = $this->request->getVar('active_plnt_id');
        $mtkn_wshe_id = $this->request->getVar('active_wshe_id');
        $cur_date = $this->request->getVar('cur_date');

        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');


        //PLANT
            if(!empty($mktn_plnt_id)) {
            $str = "SELECT `recid` FROM {$this->db_erp}.`mst_plant` WHERE plnt_code = '{$mktn_plnt_id}' ";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
           
            if($q->resultID->num_rows == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please Select Plant!!!.</div>";
                die();
            }

            $rw = $q->getRowArray();
            $plnt_id = $rw['recid'];
           
            $q->freeResult();
        
            //END BRANCH
        }
        else{ 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please Select Plant!!!.</div>";
            die();
        }


        //CHECK DATE
        if(!empty($txt_ddate)) {
            $str = "SELECT DATE('$txt_ddate') <= DATE(NOW()) _deldate ";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
           
            if($q->resultID->num_rows > 0) { 
                $rw = $q->getRowArray();
                $_deldate = $rw['_deldate'];
                if($_deldate == 1){
                     echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Failed</strong> Invalid Delivery Date!!!.</div>";
                    die();
                }

               
            }
        }
        else{
             echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Failed</strong> Required Delivery Date!!!.</div>";
            die();
        }

       //PO CLASS
        if(!empty($pocls)) {
            $str = "select recid from {$this->db_erp}.mst_po_class where PO_CLS_CODE = '$pocls' ";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
           
            if($q->resultID->num_rows == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid PO Class Data!!!.</div>";
                die();
            }

            $rw = $q->getRowArray();
            $mpocls_rid = $rw['recid'];
            $q->freeResult();
        
            //END BRANCH
        }
        else { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Required PO Class Data!!!.</div>";
            die();
        }


        //VENDOR
        if(!empty($mtkn_vndrtr) && !empty($txtvend_code)) {
            $str = "select recid,concat(VEND_ADDR1,' ',VEND_ADDR2,' ',VEND_ADDR3) _address , concat(VEND_CPRSN) cont_prsn , concat(VEND_CPRSN_DESGN) cp_desig , concat(VEND_CPRSN_TELNO) cp_no 
         from {$this->db_erp}.mst_vendor aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_vndrtr'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
           
            if($q->resultID->num_rows == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Vendor Data!!!.</div>";
                die();
            }

            $rw = $q->getRowArray();
            $txtvnd = $rw['recid'];
            $q->freeResult();
        
            //END BRANCH
        }
        else{
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Required Vendor Data!!!.</div>";
                die();
        }
        //SHIP TO
        if(!empty($mtkn_vndsrtr) && !empty($txtvends_code)) {
            $str = "select recid
         from {$this->db_erp}.mst_customer aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_vndsrtr' and CUST_NAME = '$txtvends_code'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
           
            if($q->resultID->num_rows == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid PO Class Data!!!.</div>";
                die();
            }

            $rw = $q->getRowArray();
            $txtvnds = $rw['recid'];
            $q->freeResult();
        
            //END BRANCH
        }
        else{
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Required PO Class Data!!!.</div>";
                die();
        }
        $mpo_rid = '';
        $cseqn = '';
        
     
        if(empty($adata1)) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No Item Data!!!.</div>";
            die();
        }

        //UPDATE
        if(!empty($mtkn_mntr)) { 
           //CHECK IF VALID PO
            $str = "select aa.recid,aa.po_sysctrlno from {$this->db_erp}.gw_fg_po_hd aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_mntr'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->resultID->num_rows == 0) {
                echo "No Records Found!!!";
                die();
            }
            $rw = $q->getRowArray();
            $mpo_rid = $rw['recid'];
            $cseqn = $rw['po_sysctrlno'];
            $q->freeResult();

        }//endif
        //INSERT
        else{
            
            $cseqn =  $this->mydataz->get_ctr_new_dr('FG','',$this->db_erp,'CTRL_GWRMPO');//TRANSACTION NO
        } //end else

        if(count($adata1) > 0) { 
            $ame = array();
            $adatar1 = array();
            $adatar2 = array();
            $ntqty = 0;
            $ntamt = 0;
            $nNetamt = 0;
            $nTDisc = 0;

            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);
                $cmat_code = trim($medata[0]);
                $mat_mtkn = $adata2[$aa];
                $nconvf = (empty($medata[3]) ? 0 : ($medata[3] + 0));
                $nqty = (empty($medata[4]) ? 0 : ($medata[4] + 0));
                $nprice = (empty($medata[6]) ? 0 : ($medata[6] + 0));

                $cwshe_sbin = trim($medata[12]);
                $mtkn_wshe_sbin_id = ( empty(trim($medata[17])) ) ? 0 : trim($medata[17]);
                $mtkn_wshe_grp_id = ( empty(trim($medata[18])) ) ? 0 : trim($medata[18]);

                $cbm = trim($medata[19]);
                $tamt = trim($medata[7]);
                
                $disc = trim($medata[8]);
                $netamt =trim($medata[9]);

                $total_pcs = $nconvf*$nqty;

                $cmat_code_plnt_wshe = trim($medata[0]) . $plnt_id . $mtkn_wshe_sbin_id . $mtkn_wshe_grp_id;

                $amatnr = array();
                if(!empty($cmat_code)) { 
                    $str = "select aa.recid,aa.ART_CODE from {$this->db_erp}.`mst_article` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mat_mtkn' and ART_CODE = '$cmat_code' ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    if($q->resultID->num_rows == 0) {
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Material Data!!!<br/>[$cmat_code]</div>";
                        die();
                    }
                    else{

                        if($nconvf == 0 || $nqty == 0) { 
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid QTY or Price entries!!!</div>";
                            die();
                        }

                         $rw = $q->getRowArray();
                         $mmat_rid = $rw['recid'];
                         array_push($ame,$cmat_code_plnt_wshe); 
                         array_push($adatar1,$medata);
                         array_push($adatar2,$mmat_rid);
                         $ntqty = ($ntqty + $nqty);
                         $ntamt = ($ntamt + ($tamt));
                         $nNetamt = ($nNetamt + $netamt);
                         $nTDisc = (int)$nTDisc + (int)$disc;
                    }

                   $q->freeResult();
                }

            }  //end for 
                    
            if(count($adatar1) > 0) { 
                if(!empty($mtkn_mntr)) {       
                     $str = "
                        update {$this->db_erp}.`gw_fg_po_hd` set 
                        `po_cls_id` = '$mpocls_rid',
                        `vend_rid` = '$txtvnd',
                        `vend_add` = '$txtvend_addr',
                        `vend_cont_pers` = '$txtvend_cont_persn',
                        `vend_cp_desig` = '$txtvend_cont_persn_desgn',
                        `vend_cp_contno` = '$txtvend_cont_persn_cnos',
                        `vends_rid` = '$txtvnds',
                        `vends_add` = '$txtvends_addr',
                        `vends_cont_pers` = '$txtvends_cont_persn',
                        `vends_cp_desig` = '$txtvends_cont_persn_desgn',
                        `vends_cp_contno` = '$txtvends_cont_persn_cnos',
                        `rmks` = '$txt_remk',
                        `terms` = '{$terms}',
                        `dr_list` ='$txt_drlist',
                        `tqty` = '$ntqty',
                        `tamt` = '$ntamt',
                        `netamt` = '$nNetamt',
                        `tdisc` = '$nTDisc'
                        where recid = '$mpo_rid' 
                        ";
                      $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                      $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_RM_PURCHASE_EDT_REC','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                } else {     
                    $str = "
                        insert into {$this->db_erp}.`gw_fg_po_hd` (
                        `po_sysctrlno`,
                        `po_type_id`,
                        `po_cls_id`,
                        `po_stat_id`,
                        `trx_date`,
                        `trx_delivery_date`,
                        `rmks`,
                        `vend_rid`,
                        `vend_add`,
                        `vend_cont_pers`,
                        `vend_cp_desig`,
                        `vend_cp_contno`,
                        `vends_rid`,
                        `vends_add`,
                        `vends_cont_pers`,
                        `vends_cp_desig`,
                        `vends_cp_contno`,
                        `tqty`,
                        `tamt`,
                        `netamt`,
                        `tdisc`,
                        `muser`,
                        `encd_date`,
                        `terms`,
                        `plnt_id`,
                        `wshe_id`,
                        `dr_list`
                         
                        ) values(
                        '$cseqn',
                        '0',
                        '$mpocls_rid',
                        '1',
                        date('$txt_tdate'),
                        date('$txt_ddate'),
                        '$txt_remk',
                        '$txtvnd',
                        '$txtvend_addr',
                        '$txtvend_cont_persn',
                        '$txtvend_cont_persn_desgn',
                        '$txtvend_cont_persn_cnos',                     
                        '$txtvnds',
                        '$txtvends_addr',
                        '$txtvends_cont_persn',
                        '$txtvends_cont_persn_desgn',
                        '$txtvends_cont_persn_cnos',
                        '$ntqty',
                        '$ntamt',
                        '$nNetamt',
                        '$nTDisc',
                        '$cuser',
                        '$cur_date',
                        '{$terms}',
                        '$plnt_id',
                        '',
                        '$txt_drlist'
                        )
                        ";
                      $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                      $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_RM_PURCHASE_ADD_REC','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

                      //GET ID
                      $str = "select recid,sha2(concat(aa.recid,'{$mpw_tkn}'),384) mtkn_potr from {$this->db_erp}.`gw_fg_po_hd` aa where `po_sysctrlno` = '$cseqn' ";
                      $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                      $rr = $q->getRowArray();
                      $mpo_rid = $rr['recid'];
                      $__hmtkn_potr = $rr['mtkn_potr'];
                      $q->freeResult();


                    }//endesle

                    for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                        $xdata = $adatar1[$xx];
                        $cmat_code = $xdata[0];
                        $mat_rid = $adatar2[$xx];
                        $nconvf = (empty($xdata[3]) ? 0 : ($xdata[3] + 0));
                        $nqty = (empty($xdata[4]) ? 0 : ($xdata[4] + 0));
                        $nprice = (empty($xdata[6]) ? 0 : ($xdata[6] + 0));
                        $cwshe_plant = trim($xdata[10]);
                        $cwshe_loc = trim($xdata[11]);
                        $cwshe_sbin = trim($xdata[12]);
                        $podt_rid = trim($xdata[13]);

                        //$mktn_plnt_id = trim($xdata[15]);
                        //$mtkn_wshe_id = trim($xdata[16]);
                        $mtkn_wshe_sbin_id = trim($xdata[17]);
                        $mtkn_wshe_grp_id = trim($xdata[18]);
                        $cbm = trim($xdata[19]);

                        //$wshe_barcdng_dt_rid = trim($xdata[20]);
                        $cmtext = trim($xdata[20]);
                        
                       // $gen_id = trim($xdata[22]);
                        
                        // $total_pcs = $nconvf*$nqty;
                        // $tamt = $total_pcs*$nprice;
                        $total_pcs = $xdata[5];
                        $tamt = trim($xdata[7]);
                         $tamt = (empty($tamt) ? 0 : ($tamt + 0));
                         
                        $disc = trim($xdata[8]);
                        $netamt = trim($xdata[9]);

                        $disc = (empty($disc) ? 0 : ($disc + 0));
                        $netamt= (empty($netamt) ? 0 : ($netamt + 0));
                        $disc = (empty($disc) ? 0 : ($disc + 0));
                        $netamt= (empty($netamt) ? 0 : ($netamt + 0));
                       
                        $wshe_sbin_id = 0;
                        $wshe_grp_id = 0;
                        

                        if(empty($plnt_id)){
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Plant</div>";
                            die();
                        }
                        if(empty($mtkn_mntr)) {  
                            $str = "
                            insert into {$this->db_erp}.gw_fg_po_dt ( 
                                `pohd_rid`,
                                `po_sysctrlno`,
                                `art_rid`,
                                `mat_code`,
                                `convf`,
                                `qty`,
                                `rmng_qty`,
                                `price`,
                                `po_plnt_id`,
                                `po_wshe_id`,
                                `po_wshe_sbin_id`,
                                `po_wshe_grp_id`,
                                `po_tamt`,
                                `po_discount`,
                                `po_netamt`,
                                `po_mtext`
                            ) values(
                                '$mpo_rid',
                                '$cseqn',
                                '$mat_rid',
                                '$cmat_code',
                                '$nconvf',
                                '$nqty',
                                '$nqty',
                                '$nprice',
                                '$plnt_id',
                                '',
                                '$wshe_sbin_id',
                                '$wshe_grp_id',
                               '$tamt',
                               '$disc',
                               '$netamt',
                                '$cmtext'
                            )
                            ";
                          $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                          $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_RM_PO_DT_ADD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                        } else { 
                            if(empty($podt_rid)) { 
                                $str = "
                                insert into {$this->db_erp}.gw_fg_po_dt ( 
                                    `pohd_rid`,
                                    `po_sysctrlno`,
                                    `art_rid`,
                                    `mat_code`,
                                    `convf`,
                                    `qty`,
                                    `rmng_qty`,
                                    `cbm`,
                                    `price`,
                                    `po_plnt_id`,
                                    `po_wshe_id`,
                                    `po_wshe_sbin_id`,
                                    `po_wshe_grp_id`,
                                    `po_tamt`,
                                    `po_discount`,
                                    `po_netamt`,
                                    `po_mtext`
                                ) values(
                                    $mpo_rid,
                                    '$cseqn',
                                    $mat_rid,
                                    '$cmat_code',
                                    $nconvf,
                                    $nqty,
                                    $nqty,
                                    '$cbm',
                                    $nprice,
                                    $plnt_id,
                                    $wshe_id,
                                    $wshe_sbin_id,
                                    $wshe_grp_id,
                                    '$tamt',
                                    $disc,
                                    $netamt,
                                    '$cmtext'
                                )
                                ";
                              $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                              $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_RM_PO_DT_ADD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                            } else { // end empty podt_rid 
                                $str = "
                                select recid from {$this->db_erp}.`gw_rm_po_dt` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$podt_rid'
                                ";
                              $qq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                                if($qq->resultID->num_rows > 0) {
                                    $rrw = $qq->getRowArray();
                                    $po_dtrid = $rrw['recid'];
                                    
                                    $str = "
                                    update {$this->db_erp}.`gw_fg_po_dt` set 
                                    `art_rid` = '$mat_rid',
                                    `mat_code` = '$cmat_code',
                                    `convf` = '$nconvf',
                                    `qty` = '$nqty',
                                    `price` = '$nprice',
                                    `po_plnt_id` = $plnt_id,
                                    `po_wshe_id` = $wshe_id,
                                    `po_wshe_sbin_id` = $wshe_sbin_id,
                                    `po_wshe_grp_id` = $wshe_grp_id,
                                    `cbm` = '$cbm',
                                    `po_tamt` = '$tamt',
                                    `po_discount` = '$disc',
                                    `po_netamt` = '$netamt',
                                    `po_mtext` = '$cmtext' 
                                where recid = '$po_dtrid'
                                    ";
                                  $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                                  $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_RM_PO_DT_UPD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                                }//endif
                            }  //end else
                        
                    }//end else
                    
                    
                }  

                    if(empty($mtkn_mntr)) { 
                        echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! PO Transacation:{$cseqn} </div>
                        <script type=\"text/javascript\"> 
                            function __purch_refresh_data() { 
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
                            
                            __purch_refresh_data();
                        </script>
                        ";
                        die();
                    } else { 
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

    } //end fgpurch_entry_save
   
    public function mywh_fg_rcvng_save(){

        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $data_array = $this->request->getVar('data_array');
        $rowCount1 = $this->request->getVar('rowCount1');
        $txtWarehousetkn   =  $this->request->getVar('txtWarehousetkn'); 
        $adata1 = $this->request->getVar('adata1');
        $pono = $this->request->getVar('pono');

        if(count($adata1) > 0) { 
            $ame = array();
            $adatar1 = array();

            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);
                $mitemc = trim($medata[0]);
                $act_qty = (trim($medata[1]));
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
                    $act_qty = $xdata[1];

                    $strInv = "
                    SELECT `mat_code`, `po_sysctrlno`, `po_qty`, `po_rcv_qty` FROM fg_inv_rcv WHERE `mat_code` = '{$mitemc}'
                    ";
                    $qInv = $this->mylibzdb->myoa_sql_exec($strInv,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                    $rw = $qInv->getResultArray();

                    if ($qInv->getNumRows() > 0) {

                        $str = "
                        UPDATE gw_fg_po_dt
                        SET `rcv_tag` = '1',`rmng_qty` = `rmng_qty` - '{$act_qty}', `rqty` = `rqty` + '{$act_qty}'
                        WHERE `po_sysctrlno` = '$pono' AND mat_code = '$mitemc'
                        ";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

                        $strUp = "
                        UPDATE fg_inv_rcv a SET a.`po_qty` = `po_qty` + '{$act_qty}', a.`inbound_qty` = a.`inbound_qty` + '{$act_qty}'  WHERE  a.`mat_code` = '{$mitemc}'
                        ";
                        $qq = $this->mylibzdb->myoa_sql_exec($strUp,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                        
                        $strLogs = "
                        INSERT INTO fg_inv_rcv_logs (`po_sysctrlno`,`mat_code`, `po_qty`,`po_rcv_qty`,`muser`,`encd`) SELECT a.`po_sysctrlno`,a.`mat_code`, a.`qty`,'{$act_qty}','$cuser',now() FROM gw_fg_po_dt a WHERE a.`po_sysctrlno` = '$pono' and mat_code = '{$mitemc}'
                        ";
                        $qLogs = $this->mylibzdb->myoa_sql_exec($strLogs,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                        

                    }else{

                        //update gw_rm_po_dt received tag once receive
                        $str = "
                        UPDATE gw_fg_po_dt
                        SET rcv_tag = '1', `rmng_qty` = `rmng_qty` - '{$act_qty}', `rqty` = `rqty` + '{$act_qty}'
                        WHERE `po_sysctrlno` = '$pono' AND mat_code = '$mitemc'

                        ";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

                        $strUp = "
                        INSERT INTO fg_inv_rcv (`mat_code`, `po_qty`,`inbound_qty`) SELECT a.`mat_code`, '{$act_qty}','{$act_qty}' FROM gw_fg_po_dt a WHERE a.`po_sysctrlno` = '$pono' and mat_code = '{$mitemc}'
                        ";
                        $qq = $this->mylibzdb->myoa_sql_exec($strUp,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 


                        $strLogs = "
                        INSERT INTO fg_inv_rcv_logs (`po_sysctrlno`,`mat_code`, `po_qty`,`po_rcv_qty`,`muser`,`encd`) SELECT a.`po_sysctrlno`,a.`mat_code`, a.`qty`,'{$act_qty}','$cuser',now() FROM gw_fg_po_dt a WHERE a.`po_sysctrlno` = '$pono' and mat_code = '{$mitemc}'
                        ";
                        $qLogs = $this->mylibzdb->myoa_sql_exec($strLogs,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                        
                    }

                }
                
                } 
                
            } 

            
            echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success:</strong>Data Received Successfully!!! </div>
                        <script type=\"text/javascript\"> 
                            function __purch_refresh_data() { 
                                try { 
                                    jQuery('#btn-central-rcv').prop('disabled',true);
                                } catch(err) { 
                                    var mtxt = 'There was an error on this page.\\n';
                                    mtxt += 'Error description: ' + err.message;
                                    mtxt += '\\nClick OK to continue.';
                                    alert(mtxt);
                                    return false;
                                }  //end try 
                            } 
                            
                            __purch_refresh_data();
                        </script>
                        ";
                        die();

    } //end mywh_fg_rcvng_save

    public function fg_view_ent_itm_recs_v2($start,$len){ 

        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $morder = $this->request->getVar('order');
        $search = $this->request->getVar('search');
        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $msearchrec = $search['value'];  
        $mwhere = "";
        $str_order = "";


        $order = $this->order;
        $str_order = " ORDER BY " .key($order)." ". $order[key($order)];

        if($morder['0']['column'] == 0){

        }
        else if($morder['0']['column'] > 0){
        $str_order = " ORDER BY " .$this->column_order[$morder['0']['column']]." ". $morder['0']['dir'];

        }

        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        $mwhere = "WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}' "; 
        // warehouse end
        
        //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
        $str_vwrecs = "AND a.`muser` = '$cuser'";
    
        $str_optn = '';
        if(!empty($msearchrec)){ 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = $this->mymelibzsys->searchFilter($this->column_search,$msearchrec);
        }
        // IF( rcv.`is_out` = 0,rcv.`qty`,0) qty,
        $strqry = "
        SELECT
        rcv.`recid`,
        sha2(concat(rcv.`recid`,'{$mpw_tkn}'),384) txt_mtknr, 
        CASE 
            WHEN rcv.`type` = 'GI'
                THEN '0'
            ELSE
                 CASE
                  WHEN 
                  (IFNULL((SELECT done FROM  {$this->db_erp}.`warehouse_shipdoc_hd` WHERE `crpl_code`  = rcv.`SD_NO` ),0)  = 1) 
                     THEN '0'
                 ELSE
                      '1'
                 END 
            END as qty,   
        rcv.`qty`qty_scanned ,
        rcv.`is_out`,
        rcv.`trx`,
         '' `uprice`,
        rcv.`remarks`,
        pl.`plnt_code`,
        wh.`wshe_code`,
        rcv.`box_no`,
        rcv.`stock_code`,
        art.`ART_CODE`,
        art.`ART_DESC`,
        rcv.`convf`,
        rcv.`total_amount` tamt_scanned,
        '' price,
        rcv.`total_pcs` total_pcs_scanned,
        sbin.`wshe_bin_name`,
        grp.`wshe_grp`,
        rcv.`witb_barcde`  barcde,
        rcv.`muser`,
        rcv.`type`,
        rcv.`SD_NO`,
        rcv.`encd`
        FROM
        {$this->db_erp}.`fg_inv_rcv` rcv
        JOIN {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        JOIN {$this->db_erp}.`mst_wshe_bin` sbin 
            ON rcv.`wshe_sbin_id` = sbin.`recid` AND rcv.`wshe_grp_id` = sbin.`wshegrp_id` 
            AND sbin.`plnt_id`  = rcv.`plnt_id` AND sbin.`wshe_id` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_wshe_grp` grp 
            ON rcv.`wshe_grp_id` = grp.`recid` 
            AND grp.`plnt_id`  = rcv.`plnt_id` AND grp.`wshe_id` = rcv.`wshe_id`
        {$mwhere} {$str_optn}
        GROUP BY rcv.`witb_barcde` {$str_order} limit {$start},{$len} ";

        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $filertedRows = $this->get_totalFilteredRows($mwhere,$str_optn);
        $totalRows = $this->get_totalRows($mwhere);
        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['counts'] = $qry->getNumRows();
         $data['filertedRows'] = $filertedRows;
         $data['totalRows'] = $totalRows;

        } else { 
         $data = array();
         $data['rlist'] = '';
         $data['counts'] = 0;
         $data['filertedRows'] = $filertedRows;
         $data['totalRows'] = $totalRows;

        }
        return $data;
    } //end fg_view_ent_itm_recs_v2

    public function get_totalFilteredRows($mwhere,$str_option){
      
        $strqry = "
        SELECT
        rcv.`recid`
        FROM
        {$this->db_erp}.`fg_inv_rcv` rcv
        JOIN {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        JOIN {$this->db_erp}.`mst_wshe_bin` sbin 
            ON rcv.`wshe_sbin_id` = sbin.`recid` AND rcv.`wshe_grp_id` = sbin.`wshegrp_id` 
            AND sbin.`plnt_id`  = rcv.`plnt_id` AND sbin.`wshe_id` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_wshe_grp` grp 
            ON rcv.`wshe_grp_id` = grp.`recid` 
            AND grp.`plnt_id`  = rcv.`plnt_id` AND grp.`wshe_id` = rcv.`wshe_id`
        {$mwhere} {$str_option}
        GROUP BY rcv.`witb_barcde`";

        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0){ 

         $data = $qry->getNumRows();

        }else{ 
         $data = 0;
        }
        return $data;

    } //end get_totalFilteredRows

    public function get_totalRows($mwhere = ''){
        $strqry = "
        SELECT
        rcv.`recid`
        FROM
        {$this->db_erp}.`fg_inv_rcv` rcv
        JOIN {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        JOIN {$this->db_erp}.`mst_wshe_bin` sbin 
            ON rcv.`wshe_sbin_id` = sbin.`recid` AND rcv.`wshe_grp_id` = sbin.`wshegrp_id` 
            AND sbin.`plnt_id`  = rcv.`plnt_id` AND sbin.`wshe_id` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_wshe_grp` grp 
            ON rcv.`wshe_grp_id` = grp.`recid` 
            AND grp.`plnt_id`  = rcv.`plnt_id` AND grp.`wshe_id` = rcv.`wshe_id`
        {$mwhere}
        GROUP BY rcv.`witb_barcde`";

        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data = $qry->getNumRows();

        } else{ 
         $data = 0;
        }
        return $data;

    } //end get_totalRows

    public function fg_view_box_content_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $mtkn_dt   = $this->request->getVar('mtkn_dt');
        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end

    
        //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
        $str_vwrecs = "AND a.`muser` = '$cuser'";
    
        $str_optn = '';


        $strqry = "
        SELECT
        rcv.*,
        hd.`stock_code`,
        art.`ART_CODE`,
        art.`ART_DESC`
        FROM
        {$this->db_erp}.`fg_inv_rcv_item` rcv
        JOIN {$this->db_erp}.`fg_inv_rcv` hd on rcv.`wshe_inv_id` = hd.`recid` 
        JOIN {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        WHERE hd.`plnt_id` = '{$plntID}' AND  hd.`wshe_id` = '{$whID}'
        AND SHA2(CONCAT(rcv.`wshe_inv_id`,'{$mpw_tkn}'),384) = '{$mtkn_dt}'
        GROUP BY rcv.`mat_rid`";

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
        SELECT * from ({$strqry}) oa ORDER BY `recid` DESC limit {$nstart},{$npagelimit} ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['txtsearchedrec_rl'] = $msearchrec;
            $data['recordsTotal'] = 100;
            $data['recordsFiltered'] = 50;
        } else { 
         $data = array();
         $data['npage_count'] = 1;
         $data['npage_curr'] = 1;
         $data['rlist'] = '';
        $data['recordsTotal'] = 100;
        $data['recordsFiltered'] = 10;
         $data['txtsearchedrec_rl'] = $msearchrec;
        }
        return $data;
    } //end fg_view_box_content_recs

    public function fg_view_ent_itm_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
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
        {$this->db_erp}.`fg_inv_rcv` rcv
        JOIN  {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN  {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        LEFT JOIN  {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}'
        AND rcv.`header` = '{$grno}'
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
    } //end fg_view_ent_itm_recs

    public function fg_view_itm_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $mtkn_dt   = $this->request->getVar('mtkn_dt');
        
        $fgpono   = $this->request->getVar('fgpono');

        $str_optn = '';
        if(!empty($msearchrec)){ 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = "
                AND
                    (a.`po_sysctrlno` LIKE '%{$msearchrec}%' ')
            ";
        }

        $strqry = "
        SELECT
        a.`po_sysctrlno`,
        a.`mat_code`,
        a.`convf`,
        a.`qty`,
        a.`price`
        FROM 
        gw_fg_po_dt a
        JOIN
        gw_fg_po_hd b
        ON
        a.`po_sysctrlno` = b.`po_sysctrlno`
        WHERE 
        a.`po_sysctrlno` = '{$fgpono}'
        ";
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['fgpono'] = $fgpono;
         
        } else { 
         $data = array();
         $data['fgpono'] = $fgpono;
         $data['rlist'] = '';
         $data['txtsearchedrec_rl'] = $msearchrec;
        }
        return $data;
    } //end fg_view_itm_recs

    public function view_fg_rcvng_recs($npages = 1,$npagelimit = 30,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_whse = $this->request->getVar('mtkn_whse');


        $strqry = "
        SELECT aa.`po_sysctrlno`,
            aa.`muser`,
            aa.`encd_date`,
            aa.`recid`,
            dd.`plnt_code`,
            (SELECT SUM(qty) FROM gw_fg_po_dt WHERE po_sysctrlno = aa.`po_sysctrlno`) AS hd_rcv_qty,
            (SELECT SUM(rqty) FROM gw_fg_po_dt WHERE po_sysctrlno = aa.`po_sysctrlno`) AS dt_rcv_qty
        FROM `gw_fg_po_hd` aa
        JOIN `gw_fg_po_dt` bb
        JOIN `mst_plant` dd ON (aa.`plnt_id` = dd.`recid`)
        GROUP BY aa.`po_sysctrlno`
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
    } //end view_fg_rcvng_recs

    public function fg_inv_rec_view($npages = 1,$npagelimit = 10,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');
        
        $str_optn = "";
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " WHERE
            (a.`promo_trxno` LIKE '%{$msearchrec}%') ";
        }
        
        $strqry = "
        SELECT
            a.`mat_code`,
            b.`ART_DESC`,
            b.`ART_UOM`,
            COALESCE((SELECT SUM(qty) FROM gw_fg_po_dt WHERE mat_code = a.`mat_code` AND rcv_tag = '1' AND po_sysctrlno NOT LIKE '%FGRM%'),0.00000) fgpo_qty,
            COALESCE((SELECT SUM(qty) FROM gw_fg_po_dt WHERE mat_code = a.`mat_code` AND rcv_tag = '1' AND po_sysctrlno LIKE '%FGRM%'),0.00000) rm_prod_qty,
            (COALESCE((SELECT SUM(qty) FROM gw_fg_po_dt WHERE mat_code = a.`mat_code` AND rcv_tag = '1' AND po_sysctrlno NOT LIKE '%FGRM%'), 0.00000) +
                COALESCE((SELECT SUM(qty) FROM gw_fg_po_dt WHERE mat_code = a.`mat_code` AND rcv_tag = '1' AND po_sysctrlno LIKE '%FGRM%'), 0.00000)) AS inbound_qty,
            (SELECT SUM(qty_serve) FROM prod_plan_dt WHERE mat_code = a.`mat_code`) demand_qty,
            (SELECT SUM(dt.`qty_perpack`) FROM fgp_inv_rcv a JOIN trx_fgpack_req_dt dt ON a.`fgreq_trxno` = dt.`fgreq_trxno` WHERE dt.`mat_code` = a.`mat_code` GROUP BY dt.`mat_code`) packed_qty,
            (SELECT SUM(dt.`qty_perpack`) FROM fgp_inv_rcv a JOIN trx_fgpack_req_dt dt ON a.`fgreq_trxno` = dt.`fgreq_trxno` WHERE dt.`mat_code` = a.`mat_code` AND a.`is_out` = '1' GROUP BY dt.`mat_code`) outbound_qty,
            SUM(a.`po_qty`) balance_qty
        FROM
            fg_inv_rcv a
        JOIN
            mst_article b
        ON 
            a.`mat_code` = b.`ART_CODE`
        GROUP BY
            a.`mat_code`
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
        SELECT * from ({$strqry}) oa limit {$nstart},{$npagelimit} ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        if($qry->resultID->num_rows > 0) { 
            $data['rlist'] = $qry->getResultArray();
        } else { 
            $data = array();
            $data['npage_count'] = 1;
            $data['npage_curr'] = 1;
            $data['rlist'] = '';
        }
        return $data;
    } //end fg_inv_rec_view

    public function fg_inv_rec_view_recs() {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');
		$fld_dl_dtetoo   = $this->request->getVar('fld_dl_dteto');
		$fld_dl_dteto   = $this->mylibzsys->mydate_yyyymmdd($fld_dl_dtetoo);
		$fld_dl_dtefromm = $this->request->getVar('fld_dl_dtefrom');
		$fld_dl_dtefrom = $this->mylibzsys->mydate_yyyymmdd($fld_dl_dtefromm);
        $opt_type = $this->request->getVar('opt_type');
        $str_date = "";
        $str_optn = "";
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " WHERE
            (a.`promo_trxno` LIKE '%{$msearchrec}%') ";
        }


        if ((!empty($fld_dl_dtefrom) && !empty($fld_dl_dteto)) && (($fld_dl_dtefrom != '--') && ($fld_dl_dteto != '--'))) {
			$str_date .= " AND (SUBSTRING_INDEX(a.`rcv_date`,' ',1) >= DATE('{$fld_dl_dtefrom}') AND  SUBSTRING_INDEX(a.`rcv_date`,' ',1) <= DATE('{$fld_dl_dteto}'))";
		}


        $strqry = "
        SELECT
            a.`mat_code` AS ART_CODE,
            b.`ART_DESC`,
            b.`ART_UOM`,
            a.`po_qty`,
            a.`po_rcv_qty`,
            a.`req_qty`,
            a.`prod_qty`,
            a.`delivered_qty`,
            a.`balance_qty`,
            c.`encd`
        FROM
            fg_inv_rcv a
            JOIN
            mst_article b
            ON
            a.`mat_code` = b.`ART_CODE`
            JOIN
            gw_fg_po_dt c
            ON
            a.`mat_code` = c.`mat_code`
        WHERE  
            b.`ART_UOM` = 'PCS'
            {$str_date}
        GROUP BY a.`mat_code`
        ";

        $str = "
        select count(*) __nrecs from ({$strqry}) oa
        ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $rw = $qry->getRowArray();

        $str = "
        SELECT * from ({$strqry}) oa  ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        if($qry->resultID->num_rows > 0) { 
            $data['rlist'] = $qry->getResultArray();
        } else { 
            $data = array();
            $data['npage_count'] = 1;
            $data['npage_curr'] = 1;
            $data['rlist'] = '';
        }
        return $data;
    } //end fg_inv_rec_view_recs
} 