<?php
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
    
    public function purch_rec_view($npages = 1,$npagelimit = 30,$msearchrec='') {
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
    } 
    public function purch_entry_save() {
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

         //WSHE
        if(!empty($mtkn_wshe_id)) {
            $str = "SELECT `recid` FROM {$this->db_erp}.`mst_wshe` WHERE wshe_code = '{$mtkn_wshe_id}'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
           
            if($q->resultID->num_rows == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please Select Warehouse!!!.</div>";
                die();
            }

            $rw = $q->getRowArray();
            $wshe_id = $rw['recid'];
            $q->freeResult();
        
            //END BRANCH
        }
        else { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please Select Warehouse!!!.</div>";
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
            
            $cseqn =  $this->mydataz->get_ctr_new_dr('FG','',$this->db_erp,'CTRL_GWPO');//TRANSACTION NO
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

                $cmat_code_plnt_wshe = trim($medata[0]) . $plnt_id . $wshe_id . $mtkn_wshe_sbin_id . $mtkn_wshe_grp_id;

                $amatnr = array();
                if(!empty($cmat_code)) { 
                    $str = "select aa.recid,aa.ART_CODE from {$this->db_erp}.`mst_article` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mat_mtkn' and ART_CODE = '$cmat_code' ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    if($q->resultID->num_rows == 0) {
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Material Data!!!<br/>[$cmat_code]</div>";
                        die();
                    }
                    else{
                        if($cbm == '' || $cbm == 'NaN' ) { 
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid CBM entries [ $cmat_code ] !!!</div>";
                            die();
                        }
                       $strv = "
                            select recid from {$this->db_erp}.`mst_wshe_bin`
                            where SHA2(CONCAT(`recid`,'{$mpw_tkn}'),384) = '$mtkn_wshe_sbin_id'
                        ";
                        $qv = $this->mylibzdb->myoa_sql_exec($strv,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                         if($qv->resultID->num_rows == 0) {
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Storage Bin entries [$cmat_code ]!!!</div>";
                            die();
                         }
                         $qv->freeResult();

                        $strv2 = "
                            select recid from {$this->db_erp}.`mst_wshe_grp`
                            where SHA2(CONCAT(`recid`,'{$mpw_tkn}'),384) = '$mtkn_wshe_grp_id'
                        ";
                        $qv = $this->mylibzdb->myoa_sql_exec($strv2,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                         if($qv->resultID->num_rows == 0) {
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Warehouse Grouping [ $cmat_code ]!!!</div>";
                            die();
                         }
                         $qv->freeResult();

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
                      $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PURCHASE_EDT_REC','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
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
                        now(),
                        '{$terms}',
                        '$plnt_id',
                        '$wshe_id',
                        '$txt_drlist'
                        )
                        ";
                      $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                      $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PURCHASE_ADD_REC','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

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
                        
                        // check if not zero value
                        if($mtkn_wshe_sbin_id != '0' || $mtkn_wshe_sbin_id != ''){
                           $str = "select recid from {$this->db_erp}.`mst_wshe_bin`
                                where SHA2(CONCAT(`recid`,'{$mpw_tkn}'),384) = '$mtkn_wshe_sbin_id'
                            ";
                            $qv = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            if($qv->resultID->num_rows == 0) {
                                echo "No Bin Found!!!";
                                die();
                            }
                            $rr = $qv->getRowArray();
                            $wshe_sbin_id = $rr['recid'];
                            $qv->freeResult();
                        }
                        // check if not zero value
                        if($mtkn_wshe_grp_id != '0' || $mtkn_wshe_grp_id != ''){
                            $str = "select recid from {$this->db_erp}.`mst_wshe_grp`
                            where SHA2(CONCAT(`recid`,'{$mpw_tkn}'),384) = '$mtkn_wshe_grp_id'";
                            $qv = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            if($qv->resultID->num_rows == 0) {
                                echo "No Group/Rack Found!!!";
                                die();
                            }
                            $rr = $qv->getRowArray();
                            $wshe_grp_id = $rr['recid'];
                            $qv->freeResult();
                        }
                        if(empty($plnt_id)){
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Plant</div>";
                            die();
                        }
                        else if(empty($wshe_id)){
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid warehouse</div>";
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
                                `price`,
                                `po_plnt_id`,
                                `po_wshe_id`,
                                `po_wshe_sbin_id`,
                                `po_wshe_grp_id`,
                                `cbm`,
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
                                '$nprice',
                                $plnt_id,
                                $wshe_id,
                                $wshe_sbin_id,
                                $wshe_grp_id,
                                $cbm,
                               '$tamt',
                               $disc,
                               $netamt,
                                '$cmtext'
                            )
                            ";
                          $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                          $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_DT_ADD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
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
                              $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_DT_ADD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                            } else { // end empty podt_rid 
                                $str = "
                                select recid from {$this->db_erp}.`gw_fg_po_dt` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$podt_rid'
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
                                  $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_DT_UPD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                                }//endif
                            }  //end else
                        
                    }//end else
                    
                    
                }  //end for 
                //FOR APPROVAL
                // $str = "select * from {$this->db_erp}.mst_po_wf_urcpt where (trim(`URCPT_ID`) != '' or `URCPT_ID` is not null) and (`URCPT_CUMM_APP` = 'Y' or `URCPT_VW_TAG` = 'Y') and `URCPT_PLNT_TAG`='$plnt_id' 
                //         ";
                // $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                //  if($qry->resultID->num_rows > 0) {

                //     foreach($q->getResultArray() as $rw): 
                //         $m_urid = $rw['URCPT_ID'];
                //         $m_dept = $rw['URCPT_DEPT'];
                //         $m_desg = $rw['URCPT_DESG'];
                //         $m_site = $rw['URCPT_SITE'];

                //         $str = "select `PO_CTRLNO` from {$this->db_erp}.trx_po_wf_urcpt where PO_CTRLNO = '$cseqn' and PO_URID = '$m_urid'
                //         ";
                //         $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                //         if($q->resultID->num_rows == 0) {
                //             $str = "insert into {$this->db_erp}.trx_po_wf_urcpt (
                //             `PO_CTRLNO`,`PO_URID`,`PO_URID_DEPT`,`PO_URID_DESG`,`PO_URID_SITE`) 
                //             values ('$cseqn','$m_urid','$m_dept','$m_desg','$m_site')";
                //         } else { 
                //             $str = "
                //             update {$this->db_erp}.trx_po_wf_urcpt set 
                //             `PO_URID_DEPT` = '$m_dept',`PO_URID_DESG` = '$m_desg',`PO_URID_SITE` = '$m_site',
                //             M_CDTE = now() 
                //             where PO_CTRLNO = '$cseqn' and PO_URID = '$m_urid'
                //             ";
                //         }
                //         $q->freeResult();
                //         $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                //         $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_APPROVAL','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                //         endforeach;
                // }
                // $qry->freeResult();

                                
                   
                    if(empty($mtkn_mntr)) { 
                        echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! PO Tranacation:{$cseqn} </div>
                        <script type=\"text/javascript\"> 
                            function __purch_refresh_data() { 
                                try { 
                                    $('#txt_ponumb').val('{$cseqn}');
                                    $('#__hmpotrxnoid').val('{$__hmtkn_potr}');
                                    
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

    } //end test_entry_save
   
    public function po_barcde_gnrtion() {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $PO_CFRM_TAG='1';
        $po_code = $this->request->getVar('mtkn_potr');
        
        
        if(!empty($po_code)) { 
                $str2 = "
                select `is_bcodegen`,`is_approved`,`po_sysctrlno` FROM {$this->db_erp}.`gw_fg_po_hd`
                where `is_approved` = '$PO_CFRM_TAG' AND sha2(concat(`recid`,'{$mpw_tkn}'),384) = '{$po_code}'";
                $q = $this->mylibzdb->myoa_sql_exec($str2,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                // if($q->resultID->num_rows == 0) { 
                //     echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid PO Data!!!.</div>";
                //     die();
                // }
                $rw = $q->getRowArray();
                $po_sysctrlno = $rw['po_sysctrlno'];
                $PO_CFRM_TAG = $rw['is_approved'];
                $is_bcodegen = $rw['is_bcodegen'];
                $q->freeResult();
                if($is_bcodegen === '1'){
                    echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Failed </strong>Barcode already generated!!!</div>";
                    die();
                }
                
                // }//end if


                if($PO_CFRM_TAG == 1){ 
                        //barcoding
                        //get po data
                       $str = "
                            SELECT recid,po_sysctrlno,po_cls_id,vend_rid FROM
                            {$this->db_erp}.`gw_fg_po_hd`
                            WHERE
                                `is_approved` = 1
                            AND
                                `po_sysctrlno` = '{$po_sysctrlno}'
                        ";

                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        if($q->resultID->num_rows == 0) { 
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid PO Data!!!.</div>";
                            die();
                        }
                        $rr = $q->getRowArray();
                        $_valid_po_id = $rr['recid'];
                        $_valid_po_code = $rr['po_sysctrlno'];
                        $mpocls_rid = $rr['po_cls_id'];
                        $txtvnd = $rr['vend_rid'];

                        //UPDATE TAG FOR DONE GENERATION
                        $str = "
                                update {$this->db_erp}.`gw_fg_po_hd`
                                set `is_bcodegen` = '1'
                                WHERE `po_sysctrlno` ='$_valid_po_code'
                            ";

                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_BCODEGEN','',$_valid_po_code,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

                        $str = "
                                SELECT recid,pohd_rid,SUM(qty) qty,art_rid,convf,po_tamt,price,cbm,po_plnt_id,po_wshe_id,po_wshe_sbin_id,po_wshe_grp_id,po_mtext 
                                FROM
                                {$this->db_erp}.`gw_fg_po_dt`
                                WHERE
                                `pohd_rid` = {$_valid_po_id}
                                GROUP BY art_rid
                            ";


                        $boxquery = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        
                        $str = "
                            INSERT INTO
                            {$this->db_erp}.`gw_fgpo_wshe_barcdng_hd`(
                                `trx`,
                                `header`,
                                `muser`,
                                `encd`
                            )
                            VALUES(
                                'PO',
                                '{$_valid_po_code}',
                                '{$cuser}',
                                now()
                            )
                        ";
                       $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                       $str = "
                            SELECT recid,header
                            FROM
                            {$this->db_erp}.`gw_fgpo_wshe_barcdng_hd`
                            WHERE
                            `trx` = 'PO'
                            AND
                            `header` = '{$_valid_po_code}'
                        ";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                        $rr = $q->getRowArray();
                        $_wshe_barcdng_rid = $rr['recid'];
                        $_wshe_barcdng_header = $rr['header'];

                        $rrec = $boxquery->getResultArray();
                        foreach($rrec as $rr){
                            $mat_rid_hd = $rr['art_rid'];
                            $qty2 = $rr['qty']; 
                            //PARA SA STOCKCODE
                            $cseqn_stock =  $this->mydataz->get_ctr_new_dr($mpocls_rid,'',$this->db_erp,'CTRL_GWSTCKCDE');//TRANSACTION NO
                            //insert no of box first
                            $box_no = 1;
                            $cseqn_new =  $this->mydataz->get_ctr_barcoding($this->db_erp,'CTRL_GWBOXBR');//TRANSACTION NO
                            $str = "
                                SELECT recid,pohd_rid,qty,art_rid,convf,po_tamt,price,cbm,po_plnt_id,po_wshe_id,po_wshe_sbin_id,po_wshe_grp_id,po_mtext 
                                FROM
                                {$this->db_erp}.`gw_fg_po_dt`
                                WHERE
                                `pohd_rid` = {$_valid_po_id}
                                AND 
                                `art_rid` ={$mat_rid_hd}
                            
                            ";
                            $boxquery_details = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            $rrec = $boxquery_details->getResultArray();
                            foreach($rrec as $row){
                                $_valid_po_dt_id = $row['recid']; 
                                $_valid_po_id = $row['pohd_rid']; 
                                $no_of_box = $row['qty'];
                                $mat_rid = $row['art_rid']; 
                                $qty = $row['qty']; 
                                $convf = $row['convf']; 
                                $po_tamt = $row['po_tamt']; 
                                $price = $row['price']; 
                                

                                if($convf*$price == 0){
                                    $total_amount = $po_tamt/$qty;
                                }
                                else{
                                    $total_amount = $convf*$price;  
                                }

                                $cbm = $row['cbm']; 
                                $plnt_id = $row['po_plnt_id']; 
                                $wshe_id = $row['po_wshe_id']; 
                                $wshe_sbin_id = $row['po_wshe_sbin_id']; 
                                $wshe_grp_id = $row['po_wshe_grp_id']; 
                                $remarks = $row['po_mtext']; 

                                

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
                                        {$this->db_erp}.`gw_fgpo_wshe_barcdng_dt`(
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
                                            'PO',
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
                                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                                     //get barcoding data first
                                    $str = "
                                        SELECT recid,header_id,header,mat_rid
                                        FROM
                                        {$this->db_erp}.`gw_fgpo_wshe_barcdng_dt`
                                        WHERE
                                        `irb_barcde` = '{$irb_barcde}'
                                        AND
                                        `header_id` = {$_wshe_barcdng_rid}
                                        AND
                                        `header` = '{$_wshe_barcdng_header}'
                                    ";
                                    $boxqq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                    $rr = $boxqq->getRowArray();
                                    $_wshe_dt_id = $rr['recid'];
                                    $_wshe_hd_id = $rr['header_id'];
                                    $_wshe_hd = $rr['header'];
                                    $_mat_rid = $rr['mat_rid'];

                                    //get order item
                                    $str = "
                                        SELECT a.`art_rid` mat_rid,SUM(a.`qty`* a.`convf`) qty,b.`ART_UCOST` price,SUM(a.`qty`* a.`convf` * b.`ART_UCOST` ) total_amount
                                        FROM
                                        {$this->db_erp}.`gw_fg_po_dt` a
                                        JOIN {$this->db_erp}.`mst_article` b
                                        ON (a.`art_rid` =  b.`recid`)
                                        WHERE
                                        a.`pohd_rid` = {$_valid_po_id}
                                        AND
                                        a.`recid` = {$_valid_po_dt_id}
                                    ";
                                    $itemq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                     if($itemq->resultID->num_rows > 0) { 
                                        $rrec = $itemq->getResultArray();
                                        foreach($rrec as $row){
                                            $_mat_rid = $row['mat_rid']; 
                                            $_qty = $row['qty'];  
                                            $_price = $row['price']; 
                                            $_total_amount = $row['total_amount']; 

                                           $str = "
                                                INSERT INTO {$this->db_erp}.`gw_fgpo_wshe_barcdng_item`(
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
                                        }//end if foreach($itemq->result_array()
                                    }//end if $itemq->num_rows
                                    else{
                                        $str = "
                                            INSERT INTO {$this->db_erp}.`gw_fgpo_wshe_barcdng_item`(
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

                                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                    }//else
                                    $itemq->freeResult();
                                    $box_no++;
                                }//for
                            }//foreach -------------------------------------------------------------------------------------------------------------------2
                            $boxquery_details->freeResult();
                        }//foreach -------------------------------------------------------------------------------------------------------------------1
                        $boxquery->freeResult();
                        echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Info.</strong> Successfully generated!!!.</div>";
                    }//if($PO_CFRM_TAG == 1){
                            
               }//endif $so_code
    }//po_barcde_gnrtion
     public function purch_post_view($npages = 1,$npagelimit = 30,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');
       
        $str_optn = "";
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " AND
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
        WHERE a.`is_approved` = '2'
        {$str_optn}
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
    } 
    public function po_for_approval() {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_potr = $this->request->getVar('mtkn_potr');
        $po_sysctrlno = '';
        
        if(!empty($mtkn_potr)) { 
            //SELECT IF ALREADY POSTED
            $str = "select is_approved,po_sysctrlno from {$this->db_erp}.`gw_fg_po_hd` aa WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$mtkn_potr' AND `is_approved` = '2'";
            $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            if($qry->resultID->num_rows == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Failed</strong> Already approved!!!.</div>";
                die();
            }
            else{
                $rr = $qry->getRowArray();
                $po_sysctrlno = $rr['po_sysctrlno'];
            }
            $str = "
            update {$this->db_erp}.`gw_fg_po_hd`
            SET `is_approved` = '1',
            `user_approved` = '$cuser',
            `date_approved` = now()
            WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$mtkn_potr'
            AND `is_approved` = '2';
            ";
            $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_APPROVAL','',$po_sysctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

            echo  "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Data Approved Successfully!!!</div>
                    
                    ";

        }//endif

    }
    public function download_purch_barcode($mpohd_rid,$active_wshe_id){


        $cuser       = $this->mylibzdb->mysys_user();
        $mpw_tkn     = $this->mylibzdb->mpw_tkn();
        $chtmljs ="";

        $str = "
            SELECT 
                `recid`,`wshe_code`
            FROM
                {$this->db_erp}.`mst_wshe`
            WHERE
                `recid` = '{$active_wshe_id}'
        ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->resultID->num_rows == 0) { 
            $data = "<div class=\"alert alert-danger\"><strong>Invalid Input</strong><br>Invalid warehouse.</div>";
            echo $data;
            die();
        }
        else{
            $rr = $qry->getRowArray();
            $_valid_wshe_id = $rr['recid'];
        }

        if($mpohd_rid != ''){
            $file_name = 'purch_barcodereports_'.$mpohd_rid.'_'.$cuser.'_'.date('Ymd').$this->mylibzsys->random_string(15);
            $mpathdn   = ROOTPATH;
            $_csv_path = '/public/downloads/me/';
            //if(!is_dir($_csv_path)) mkdir($_csv_path, '0755', true);
            $filepath = $mpathdn.$_csv_path.$file_name.'.csv';
            $cfilelnk = site_url() . 'downloads/me/' . $file_name.'.csv'; 


            $str = "
              SELECT *
              INTO OUTFILE '{$filepath}'
              FIELDS TERMINATED BY '\t'
              LINES TERMINATED BY '\r\n'
              FROM(
                SELECT
                    a.`header`,
                    (SELECT bb.`qty` FROM {$this->db_erp}.`gw_wshe_barcdng_dt` bb WHERE bb.`stock_code` = a.`stock_code` AND bb.`mat_rid`= a.`mat_rid` AND bb.`header` = '{$mpohd_rid}'
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
                    CONCAT(a.`box_no`,'/',(SELECT bb.`qty` FROM {$this->db_erp}.`gw_wshe_barcdng_dt` bb WHERE bb.`stock_code` = a.`stock_code` AND bb.`mat_rid`= a.`mat_rid` AND bb.`header` = '{$mpohd_rid}'
                    AND bb.`to_wshe_id` = '{$_valid_wshe_id}' GROUP BY bb.`stock_code`)) box_no,
                    GROUP_CONCAT(ee.`ART_CODE` ORDER BY ee.`ART_CODE` ASC SEPARATOR ', ') __boxcontent,
                    c.`wshe_code`,
                    a.`convf`
                    FROM
                    {$this->db_erp}.`gw_wshe_barcdng_dt` a
                    JOIN {$this->db_erp}.`gw_wshe_barcdng_item` dd
                    ON (a.`recid` = dd.`dt_id`)
                    
                    JOIN {$this->db_erp}.`mst_article` b
                    ON (a.`mat_rid` = b.`recid`)
                    
                    JOIN {$this->db_erp}.`mst_article` ee
                    ON (dd.`mat_rid` = ee.`recid`)
                    
                    JOIN {$this->db_erp}.`mst_wshe` c
                    ON (a.`to_wshe_id` = c.`recid`)
                    WHERE
                    a.`header` = '{$mpohd_rid}'
                    AND a.`to_wshe_id` = '{$_valid_wshe_id}'
                    GROUP BY a.`witb_barcde`
                    ORDER BY a.`recid`
              ) oa
            ";

            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PURCHASE_BARCODE_DL','',$mpohd_rid,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
        }//endif

            $chtmljs .= "
                        <a href=\"{$cfilelnk}\" download=" . $file_name ." class='btn btn-dgreen btn-sm col-lg-12' onclick='$(this).remove()'> <i class='bi bi-save'></i> DOWNLOAD IT!</a>        
                        ";
            echo $chtmljs;
    }

    public function view_fg_rcvng_recs($npages = 1,$npagelimit = 30,$msearchrec=''){ 
    $cuser = $this->mylibzdb->mysys_user();
    $mpw_tkn = $this->mylibzdb->mpw_tkn();
    $mtkn_whse = $this->request->getVar('mtkn_whse');
    $wsheData = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
    $fld_plant = $wsheData['plntID'];
    $fld_wshe = $wsheData['whID'];

    $strqry = "
    SELECT aa.`po_sysctrlno`,aa.`muser`,aa.`encd_date`,aa.`recid`,
    dd.`plnt_code`,
    ee.`wshe_code`,
    (SELECT COUNT(b.`witb_barcde`) dt_qty FROM `gw_fgpo_wshe_barcdng_dt` b WHERE b.`header`  = aa.`po_sysctrlno` AND b.`to_plnt_id` = aa.`plnt_id` AND b.`to_wshe_id` = aa.`wshe_id`) AS dt_qty,
    (SELECT COUNT(b.`witb_barcde`) qty_rcv FROM `fg_inv_rcv` b WHERE b.`header`  = aa.`po_sysctrlno` AND b.`plnt_id` = aa.`plnt_id` AND b.`wshe_id` = aa.`wshe_id` ) AS rcv_qty
    FROM `gw_fg_po_hd` aa
    JOIN `mst_plant` dd ON (aa.`plnt_id` = dd.`recid`)
    JOIN `mst_wshe` ee ON (aa.`wshe_id` = ee.`recid`)
    WHERE aa.`is_approved` = '1' AND aa.`is_bcodegen` = '1' AND  aa.`wshe_id` = '{$fld_wshe}' AND aa.`plnt_id` = '{$fld_plant}'
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

    public function wh_fg_rcvng_upld(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $txtprod_type_upld_sub = $this->request->getVar('txtprod_type_upld_sub');
        $txtWarehouse   =  $this->request->getVar('txtWarehouse'); 
        $txtWarehousetkn   =  $this->request->getVar('txtWarehousetkn'); 
        $fgpoNo   =  $this->request->getVar('fgpoNo'); 

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
                    {$this->db_erp}.`gw_fgpo_wshe_barcdng_dt` b
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
                    {$this->db_erp}.`gw_fgpo_wshe_barcdng_hd` g
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
                    b.`header` = '{$fgpoNo}'
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
            $data['fgpo_code'] = $fgpoNo;

        }
        else
        {

            $data['result'] = '';
            $data['count']  = 0;
            $data['fgpo_code'] = $fgpoNo;

        }
        $data['response'] = true;
        return $data;
 
        
    }  //end simpleupld_proc

    public function mywh_fg_rcvng_save(){

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
                {$this->db_erp}.`gw_fgpo_wshe_barcdng_dt`
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
                {$this->db_erp}.`fg_inv_rcv`
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
        {$this->db_erp}.`fg_inv_rcv`(
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
            FROM {$this->db_erp}.`gw_fgpo_wshe_barcdng_dt`
            WHERE  `witb_barcde` IN ($data_array) 
            AND `to_plnt_id` = {$plntID}
            AND `to_wshe_id` = {$whID}
            AND YEAR(`encd`) >= '2022'  ";

            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            //insert to logs end

        //insert in central

        $str = "
        INSERT INTO
        {$this->db_erp}.`fg_inv_rcv_logs`(
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
            FROM {$this->db_erp}.`fg_inv_rcv`
            WHERE `trx`  = '{$_hd_ctrlno}' AND `plnt_id`= $plntID AND  `wshe_id` = $whID ";

            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
            /***************** AUDIT LOGS *************************/

            $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CENTRAL_RCVNG','OLD PROCESS SAVING CENTRAL',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            /***************** AUDIT LOGS *************************/

            $str = "
                INSERT INTO
                {$this->db_erp}.`fg_inv_rcv_item`(
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
                    FROM  {$this->db_erp}.`gw_fgpo_wshe_barcdng_dt` dt 
                    JOIN  {$this->db_erp}.`fg_inv_rcv` wdt ON dt.`witb_barcde` = wdt.`witb_barcde`
                    JOIN  {$this->db_erp}.`gw_fgpo_wshe_barcdng_item` im ON dt.`recid` = im.`dt_id`
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
                    {$this->db_erp}.`fg_inv_rcv_item_logs`(
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
                    FROM  {$this->db_erp}.`fg_inv_rcv_item` dt 
                    JOIN  {$this->db_erp}.`fg_inv_rcv` wdt ON dt.`wshe_inv_id` = wdt.`recid`
                    WHERE wdt.`trx` = '{$_hd_ctrlno}'

                            ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
      

                //update rcv tag in gr barcoding dt

                $str_up = "
                UPDATE  {$this->db_erp}.`gw_fgpo_wshe_barcdng_dt` dt,{$this->db_erp}.`fg_inv_rcv` rcv
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
    } //endfunc

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

    }

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

    }

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
    } //endfunc

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
    } //endfunc
} //end main MyMDCustomerModel