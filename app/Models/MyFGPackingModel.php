<?php
/*
 * Module      :    MyFGPackingModel.php
 * Type 	   :    Model
 * Program Desc:    MyFGPackingModel
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/
namespace App\Models;
use CodeIgniter\Model;

class MyFGPackingModel extends Model
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
    }

    public function fgp_inv_rec_view($npages = 1,$npagelimit = 10,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');

        $strqry = "
        SELECT 
        a.`tpa_trxno`,
        a.`fgreq_trxno`,
        a.`stock_code`,
        a.`barcde`,
        a.`wob_barcde`,
        b.`branch_name`,
        CASE
            WHEN `is_out` = '0'
                THEN  'N'
            ELSE
                'Y' 
            END
            AS is_out,
        `SD_NO`,
        `rcv_date`
        FROM
        fgp_inv_rcv a
        JOIN
        trx_tpa_hd b
        ON
        a.`tpa_trxno` = b.`tpa_trxno`
        GROUP BY wob_barcde
        ORDER BY rcv_date DESC
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
    } // end fgp_inv_rec_view
    
    public function fgpack_rec_view($npages = 1,$npagelimit = 30,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');
       
        $str_optn = "";
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " WHERE
            (a.`fgpack_trxno` LIKE '%{$msearchrec}%' OR a.`rmks` LIKE '%{$msearchrec}%')";
        }


        $strqry = "
        SELECT 
          a.`fgpack_trxno`,
          e.`plnt_code`,
          d.`wshe_code`,
          f.`BRNCH_NAME`,
          a.`recid`,
          a.`plnt_id`,
          a.`wshe_id`,
          a.`noofpack`,
          a.`rmks`,
          a.`is_bcodegen`,
          a.`fgpack_tag`,
          a.`is_approved`,
          a.`user_approved`,
          a.`date_approved`,
          a.`muser`,
          a.`encd_date`
        FROM
            {$this->db_erp}.`gw_fg_pack_hd` a
        JOIN  {$this->db_erp}.`mst_plant`  e
        ON (a.`plnt_id` = e.`recid`)
        JOIN  {$this->db_erp}.`mst_wshe`  d
        ON (a.`wshe_id` = d.`recid`)
        JOIN  {$this->db_erp}.`mst_companyBranch`  f
        ON (a.`branch_rid` = f.`recid`)
        {$str_optn}
        ORDER BY a.`encd_date` ASC
        ";
        
        //var_dump($strqry);
        
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
    } // end promo_rec_fgpack_rec_viewview

    public function fgpack_entry_save() {
        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");  
      
        $mtkn_mntr = $this->request->getVar('mtkn_mntr');
        $txt_packtrxno = $this->request->getVar('txt_packtrxno');
        $active_plnt_id = $this->request->getVar('active_plnt_id');
        $active_wshe_id = $this->request->getVar('active_wshe_id');

        $__hmtkn_fgpacktr = '';
        $noofpacks = $this->request->getVar('noofpacks');
        $txt_remk = $this->request->getVar('txt_remk');
        //$txtpack_totals = $this->request->getVar('txtpack_totals');
        //$txtpack_qty = $this->request->getVar('txtpack_qty');
        
        $mktn_plnt_id = $this->request->getVar('active_plnt_id');
        $mtkn_wshe_id = $this->request->getVar('active_wshe_id');

        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');
        
        $txt_branch = $this->request->getVar('txt_branch');
        $mtkn_branch = $this->request->getVar('mtkn_branch');

        $txt_branch_id = '';
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
        //BRANCH
        if((!empty($txt_branch)) && !empty($mtkn_branch)) {
            $str = "SELECT `recid` FROM {$this->db_erp}.`mst_companyBranch` WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '{$mtkn_branch}' ";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
           
            if($q->resultID->num_rows == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please Select Plant!!!.</div>";
                die();
            }

            $rw = $q->getRowArray();
            $txt_branch_id = $rw['recid'];
           
            $q->freeResult();
        
            //END BRANCH
        }
        else{ 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please Select Branch!!!.</div>";
            die();
        }
       
        $mfgp_rid = '';
        $cseqn = '';
        
     
        if(empty($adata1)) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No Item Data!!!.</div>";
            die();
        }

        //UPDATE
        if(!empty($mtkn_mntr)) { 
           //CHECK IF VALID PO
            $str = "select aa.recid,aa.fgpack_trxno from {$this->db_erp}.gw_fg_pack_hd aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_mntr'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->resultID->num_rows == 0) {
                echo "No Records Found!!!";
                die();
            }
            $rw = $q->getRowArray();
            $mfgp_rid = $rw['recid'];
            $cseqn = $rw['fgpack_trxno'];
            $q->freeResult();

        }//endif
        //INSERT
        else{
            
            $cseqn =  $this->mydataz->get_ctr_new_dr('FG','',$this->db_erp,'CTRL_GWFGPA');//TRANSACTION NO
        } //end else

        if(count($adata1) > 0) { 
            $ame = array();
            $adatar1 = array();
            $adatar2 = array();
            $ntqty = 0;
            $ntamt = 0;

            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);
                $cmat_code = trim($medata[0]);
                $mat_mtkn = $adata2[$aa];
                $nqty = (empty($medata[2]) ? 0 : ($medata[2] + 0));
                $mprice = (empty($medata[3]) ? 0 : ($medata[3] + 0));
                $tamt = (empty($medata[4]) ? 0 : ($medata[4] + 0));
                $mremks = $medata[5];

               $cmat_code_plnt_wshe = trim($medata[0]) . $plnt_id . $wshe_id;

                $amatnr = array();
                if(!empty($cmat_code)) { 
                    $str = "select aa.recid,aa.ART_CODE from {$this->db_erp}.`mst_article` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mat_mtkn' and ART_CODE = '$cmat_code' ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    
                    if($q->resultID->num_rows == 0) {
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Material Data!!!<br/>[$cmat_code]</div>";
                        die();
                    }
                    else{
                        
                        if($nqty == 0) { 
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
                    }

                   $q->freeResult();
                }

            }  //end for 
                    
            if(count($adatar1) > 0) { 
                if(!empty($mtkn_mntr)) {       
                     $str = "
                        update {$this->db_erp}.`gw_fg_pack_hd` set 
                        `noofpack` = '$noofpacks',
                        `rmks` = '$txt_remk',
                        `tqty` = '$ntqty',
                        `tamt` = '$ntamt',
                        `branch_rid`= '$txt_branch_id'
                        where recid = '$mfgp_rid' 
                        ";
                      $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                      $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_FG_PACKING_EDT_REC','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                } else {     
                    $str = "
                        insert into {$this->db_erp}.`gw_fg_pack_hd` (
                        `fgpack_trxno`,
                        `noofpack`,
                        `rmks`,
                        `tqty`,
                        `tamt`,
                        `plnt_id`,
                        `wshe_id`,
                        `branch_rid`,
                        `muser`,
                        `encd_date`
                         
                        ) values(
                        '$cseqn',
                        '$noofpacks',
                        '$txt_remk',
                        '$ntqty',
                        '$ntamt',
                        '$plnt_id',
                        '$wshe_id',
                        '$txt_branch_id',
                        '$cuser',
                        now()
                        )
                        ";
                      $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                      $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_FG_PACKING_ADD_REC','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

                      //GET ID
                      $str = "select recid,sha2(concat(aa.recid,'{$mpw_tkn}'),384) mtkn_fgpacktr from {$this->db_erp}.`gw_fg_pack_hd` aa where `fgpack_trxno` = '$cseqn' ";
                      $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                      $rr = $q->getRowArray();
                      $mfgp_rid = $rr['recid'];
                      $__hmtkn_fgpacktr = $rr['mtkn_fgpacktr'];
                      $q->freeResult();


                    }//endesle

                    for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                        $xdata = $adatar1[$xx];
                        $cmat_code = $xdata[0];
                        $mat_rid = $adatar2[$xx];
                        $nqty = (empty($xdata[2]) ? 0 : ($xdata[2] + 0));
                        $nprice = (empty($xdata[3]) ? 0 : ($xdata[3] + 0));
                        $tamt = trim($xdata[4]);
                        $tamt = (empty($tamt) ? 0 : ($tamt + 0));
                        $cmtext = trim($xdata[5]);
                        
                        $fgpackdt_rid = trim($xdata[6]);
                         
                       
                        
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
                            insert into {$this->db_erp}.`gw_fg_pack_dt` ( 
                                `fgpackhd_rid`,
                                `fgpack_trxno`,
                                `mat_rid`,
                                `mat_code`,
                                `qty`,
                                `uprice`,
                                `plnt_id`,
                                `wshe_id`,
                                `branch_rid`,
                                `tamt`,
                                `rems`
                            ) values(
                                '$mfgp_rid',
                                '$cseqn',
                                '$mat_rid',
                                '$cmat_code',
                                '$nqty',
                                '$nprice',
                                '$plnt_id',
                                '$wshe_id',
                                '$txt_branch_id',
                                '$tamt',
                                '$cmtext'
                            )
                            ";
                          $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                          $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_DT_ADD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                        } else { 
                            if(empty($fgpackdt_rid)) { 
                                $str = "
                                insert into {$this->db_erp}.`gw_fg_pack_dt` ( 
                                    `fgpackhd_rid`,
                                    `fgpack_trxno`,
                                    `mat_rid`,
                                    `mat_code`,
                                    `qty`,
                                    `uprice`,
                                    `plnt_id`,
                                    `wshe_id`,
                                    `branch_rid`,
                                    `tamt`,
                                    `rems`
                                ) values(
                                    '$mfgp_rid',
                                    '$cseqn',
                                    '$mat_rid',
                                    '$cmat_code',
                                    '$nqty',
                                    '$nprice',
                                    '$plnt_id',
                                    '$wshe_id',
                                    '$txt_branch_id',
                                    '$tamt',
                                    '$cmtext'
                                )
                                ";
                              $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                              $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_DT_ADD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                            } else { // end empty fgpackdt_rid 
                                $str = "
                                select recid from {$this->db_erp}.`gw_fg_pack_dt` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$fgpackdt_rid'
                                ";
                              $qq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                                if($qq->resultID->num_rows > 0) {
                                    $rrw = $qq->getRowArray();
                                    $fgpack_dtrid = $rrw['recid'];
                                    
                                    $str = "
                                    update {$this->db_erp}.`gw_fg_pack_dt` set 
                                    `mat_rid` = '$mat_rid',
                                    `mat_code` = '$cmat_code',
                                    `qty` = '$nqty',
                                    `uprice` = '$nprice',
                                    `plnt_id` = $plnt_id,
                                    `wshe_id` = $wshe_id,
                                    `branch_rid`= '$txt_branch_id',
                                    `tamt` = '$tamt',
                                    `rems` = '$cmtext' 
                                    where recid = '$fgpack_dtrid'
                                    ";
                                  $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                                  $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_DT_UPD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                                }//endif
                            }  //end else
                        
                    }//end else
                    
                    
                }  //end for 
                        
                   
                    if(empty($mtkn_mntr)) { 
                        echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! Packing Series No:{$cseqn} </div>
                        <script type=\"text/javascript\"> 
                            function __fg_refresh_data() { 
                                try { 
                                    $('#txt_packtrxno').val('{$cseqn}');
                                    $('#__hmpacktrxnoid').val('{$__hmtkn_fgpacktr}');
                                    
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

    } // end fgpack_entry_save

    public function fgpack_barcde_gnrtion() {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $PO_CFRM_TAG='Y';
        $po_code = $this->request->getVar('mtkn_fgpacktr');
        
        
        if(!empty($po_code)) { 
                $str2 = "
                select `is_bcodegen`,`is_approved`,`fgpack_trxno` FROM {$this->db_erp}.`gw_fg_pack_hd`
                where `is_approved` = '$PO_CFRM_TAG' AND sha2(concat(`recid`,'{$mpw_tkn}'),384) = '{$po_code}'";
                $q = $this->mylibzdb->myoa_sql_exec($str2,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                if($q->resultID->num_rows == 0) { 
                    echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid PO Data!!!.</div>". $str2;
                    die();
                }
                $rw = $q->getRowArray();
                $fgpack_trxno = $rw['fgpack_trxno'];
                $PO_CFRM_TAG = $rw['is_approved'];
                $is_bcodegen = $rw['is_bcodegen'];
                $q->freeResult();
                if($is_bcodegen === 'Y'){
                    echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Failed </strong>Barcode already generated!!!</div>";
                    die();
                }
                
                // }//end if


                if($PO_CFRM_TAG == 'Y'){ 
                        //barcoding
                        //get po data
                       $str = "
                            SELECT recid,fgpack_trxno FROM
                            {$this->db_erp}.`gw_fg_pack_hd`
                            WHERE
                                `is_approved` = 'Y'
                            AND
                                `fgpack_trxno` = '{$fgpack_trxno}'
                        ";

                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        if($q->resultID->num_rows == 0) { 
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid PO Data!!!.</div>";
                            die();
                        }
                        $rr = $q->getRowArray();
                        $_valid_po_id = $rr['recid'];
                        $_valid_po_code = $rr['fgpack_trxno'];
                        //$mpocls_rid = $rr['po_cls_id'];
                        //$txtvnd = $rr['vend_rid'];

                        //UPDATE TAG FOR DONE GENERATION
                        $str = "
                                update {$this->db_erp}.`gw_fg_pack_hd`
                                set `is_bcodegen` = 'Y'
                                WHERE `fgpack_trxno` ='$_valid_po_code'
                                ";

                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_BCODEGEN','',$_valid_po_code,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

                        $str = "
                                SELECT recid,fgpack_trxno,noofpack qty,tqty convf,tamt,plnt_id,wshe_id,rmks mtext
                                FROM
                                {$this->db_erp}.`gw_fg_pack_hd`
                                WHERE
                                `recid` = {$_valid_po_id}
                                GROUP BY fgpack_trxno
                            ";


                        $boxquery = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        
                        $str = "
                            INSERT INTO
                            {$this->db_erp}.`gw_fg_wshe_barcdng_hd`(
                                `trx`,
                                `header`,
                                `muser`,
                                `encd`
                            )
                            VALUES(
                                'FGP',
                                '{$_valid_po_code}',
                                '{$cuser}',
                                now()
                            )
                        ";
                       $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                       $str = "
                            SELECT recid,header
                            FROM
                            {$this->db_erp}.`gw_fg_wshe_barcdng_hd`
                            WHERE
                            `trx` = 'FGP'
                            AND
                            `header` = '{$_valid_po_code}'
                        ";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                        $rr = $q->getRowArray();
                        $_wshe_barcdng_rid = $rr['recid'];
                        $_wshe_barcdng_header = $rr['header'];

                        $rrec = $boxquery->getResultArray();
                        //$rrec = $boxquery->getRowArray();
                        //$_wshe_barcdng_rid = $rrec['qty'];
                        foreach($rrec as $rr){
                            $mat_rid_hd = $rr['fgpack_trxno'];
                            $qty2 = $rr['qty']; 
                            //PARA SA STOCKCODE
                            $cseqn_stock =  $this->mydataz->get_ctr_new_dr('','',$this->db_erp,'CTRL_GWFGPASTCKCDE');//TRANSACTION NO
                            //insert no of box first
                            $box_no = 1;
                            $cseqn_new =  $this->mydataz->get_ctr_barcoding($this->db_erp,'CTRL_GWFGBOXBR');//TRANSACTION NO
                            $str = "
                                SELECT a.`fgpack_trxno`,a.`noofpack`,a.`tqty`,a.`tamt`,a.`plnt_id`,a.`wshe_id`,b.`mat_rid`,a.`rmks`
                                FROM
                                {$this->db_erp}.`gw_fg_pack_hd` a
                                join 
                                `gw_fg_pack_dt` b
                                on
                                a.`fgpack_trxno` = b.`fgpack_trxno`

                            ";
                            $boxquery_details = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            $rrec = $boxquery_details->getResultArray();
                            foreach($rrec as $row){
                                $no_of_box = $row['noofpack'];
                                $mat_rid = $row['mat_rid']; 
                                $tamt = $row['tamt']; 
                                $price = 0;//$row['price']; 
                                $mat_code = $row['fgpack_trxno']; 
                                $tqty = $row['tqty'];
                                // if($convf*$price == 0){
                                //     $total_amount = $tamt/$qty;
                                // }
                                // else{
                                //     $total_amount = $convf*$price;  
                                // }

                                $total_amount = $tamt;

                                $cbm = ''; //$row['cbm']; 
                                $plnt_id = $row['plnt_id']; 
                                $wshe_id = $row['wshe_id']; 
                                $wshe_sbin_id = '';// $row['wshe_sbin_id']; 
                                $wshe_grp_id = '';//$row['wshe_grp_id']; 
                                $rmks = $row['rmks']; 

                                

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
                                        {$this->db_erp}.`gw_fg_wshe_barcdng_dt`(
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
                                            `mat_code`,
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
                                            'FGP',
                                            '{$_wshe_barcdng_rid}',
                                            '{$_wshe_barcdng_header}',
                                            '{$cseqn_stock}',
                                            '{$cseqn_new}',
                                            '{$irb_barcde}',
                                            '{$srb_barcde}',
                                            '{$witb_barcde}',
                                            '{$wob_barcde}',
                                            '{$pob_barcde}',
                                            '{$dmg_barcde}',
                                            '{$plnt_id}',
                                            '{$wshe_id}',
                                            '{$wshe_sbin_id}',
                                            '{$wshe_grp_id}',
                                            '{$plnt_id}',
                                            '{$wshe_id}',
                                            '{$wshe_sbin_id}',
                                            '{$wshe_grp_id}',
                                            '{$box_no}',
                                            '{$mat_rid}',
                                            '{$mat_code}',
                                            '{$qty2}',
                                            '{$tqty}',
                                            '{$cbm}',
                                            '{$no_of_box}',
                                            '{$total_amount}',
                                            '{$rmks}',
                                            '{$cuser}',
                                            now()
                                        )
                                    ";
                                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                    

                                     //get barcoding data first
                                    $str = "
                                        SELECT recid,header_id,header,mat_code
                                        FROM
                                        {$this->db_erp}.`gw_fg_wshe_barcdng_dt`
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
                                    $_mat_code = $rr['mat_code'];

                                    //insert into warehouse inventory
                                   
                                    //get order item
                                    $str = "
                                        SELECT a.`mat_code` mat_code,a.`qty`,a.`uprice` price,a.`tamt` total_amount
                                        FROM
                                        {$this->db_erp}.`gw_fg_pack_dt` a
                                        WHERE
                                        a.`fgpackhd_rid` = {$_valid_po_id}
                                        GROUP BY a.`mat_code`
                                       
                                    ";
                                    $itemq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                     if($itemq->resultID->num_rows > 0) { 
                                        $rrec = $itemq->getResultArray();
                                        foreach($rrec as $row){
                                            $_mat_code = $row['mat_code']; 
                                            $_qty = $row['qty'];  
                                            $_price = $row['price']; 
                                            $_total_amount = $row['total_amount']; 

                                           $str = "
                                                INSERT INTO {$this->db_erp}.`gw_fg_wshe_barcdng_item`(
                                                    `header`,
                                                    `header_id`,
                                                    `dt_id`,
                                                    `mat_code`,
                                                    `qty`,
                                                    `price`,
                                                    `total_amount`,
                                                    -- `remarks`,
                                                    `muser`,
                                                    `encd`
                                                ) 
                                                VALUES(
                                                    '{$_wshe_hd}',
                                                    '{$_wshe_hd_id}',
                                                    '{$_wshe_dt_id}',
                                                    '{$_mat_code}',
                                                    '{$_qty}',
                                                    '{$_price}',
                                                    '{$_total_amount}',
                                                    '{$cuser}',
                                                    now()
                                                )
                                            ";
                                            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                        }//end if foreach($itemq->result_array()
                                    }//end if $itemq->num_rows
                                    else{
                                        $str = "
                                            INSERT INTO {$this->db_erp}.`gw_fg_wshe_barcdng_item`(
                                                `header`,
                                                `header_id`,
                                                `dt_id`,
                                                `mat_code`,
                                                `qty`,
                                                `price`,
                                                `total_amount`,
                                                -- `remarks`,
                                                `muser`,
                                                `encd`
                                            ) 
                                            VALUES(
                                                '{$_wshe_hd}',
                                                '{$_wshe_hd_id}',
                                                '{$_wshe_dt_id}',
                                                '{$mat_code}',
                                                '{$convf}',
                                                '{$price}',
                                                '{$total_amount}',
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
            else{
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Failes.<br/></strong><strong>Info.</strong> Not Found!!!.</div>";
            }
    } // end fgpack_barcde_gnrtion

    public function fgpack_post_view($npages = 1,$npagelimit = 30,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');
       
        $str_optn = "";
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " AND
            (a.`fgpack_trxno` LIKE '%{$msearchrec}%' OR b.`VEND_NAME` LIKE '%{$msearchrec}%' OR a.`rmks` LIKE '%{$msearchrec}%')";
        }


        $strqry = "
         SELECT 
          e.`plnt_code`,
          d.`wshe_code`,
          f.`BRNCH_NAME`,
          a.`recid`,
          a.`fgpack_trxno`,
          a.`plnt_id`,
          a.`wshe_id`,
          a.`noofpack`,
          a.`rmks`,
          a.`is_bcodegen`,
          a.`fgpack_tag`,
          a.`is_approved`,
          a.`user_approved`,
          a.`date_approved`,
          a.`muser`,
          a.`encd_date`
        FROM
            {$this->db_erp}.`gw_fg_pack_hd` a
        JOIN  {$this->db_erp}.`mst_plant`  e
        ON (a.`plnt_id` = e.`recid`)
        JOIN  {$this->db_erp}.`mst_wshe`  d
        ON (a.`wshe_id` = d.`recid`)
        JOIN  {$this->db_erp}.`mst_companyBranch`  f
        ON (a.`branch_rid` = f.`recid`)
        WHERE a.`is_approved` = 'N'
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
    } // end fgpack_post_view

    public function fgpack_for_approval() {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_fgpacktr = $this->request->getVar('mtkn_fgpacktr');
        $fgpack_trxno = '';
        
        if(!empty($mtkn_fgpacktr)) { 
            //SELECT IF ALREADY POSTED
            $str = "select is_approved,fgpack_trxno from {$this->db_erp}.`gw_fg_pack_hd` aa WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$mtkn_fgpacktr' AND `is_approved` = 'N'";
            $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            if($qry->resultID->num_rows == 0) { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Failed</strong> Already approved!!!.</div>";
                die();
            }
            else{
                $rr = $qry->getRowArray();
                $fgpack_trxno = $rr['fgpack_trxno'];
            }
            $str = "
            update {$this->db_erp}.`gw_fg_pack_hd`
            SET `is_approved` = 'Y',
            `user_approved` = '$cuser',
            `date_approved` = now()
            WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$mtkn_fgpacktr'
            AND `is_approved` = 'N';
            ";
            $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_APPROVAL','',$fgpack_trxno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

            echo  "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Data Approved Successfully!!!</div>
                    
                    ";

        }//endif

    } // end fgpack_for_approval

    public function download_fgpack_barcode($mpohd_rid,$active_wshe_id){


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
            $file_name = 'fg_barcodereports_'.$mpohd_rid.'_'.$cuser.'_'.date('Ymd').$this->mylibzsys->random_string(15);
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
                    (SELECT bb.`qty` FROM {$this->db_erp}.`gw_fg_wshe_barcdng_dt` bb WHERE bb.`stock_code` = a.`stock_code` AND bb.`mat_code`= a.`mat_code` AND bb.`header` = '{$mpohd_rid}'
                    AND bb.`to_wshe_id` = '{$_valid_wshe_id}' GROUP BY bb.`stock_code`) tqty,
                    a.`stock_code`,
                    a.`mat_code`,
                    a.`mat_code` ART_DESC,
                    'SACK' ART_SKU,
                    a.`irb_barcde`,
                    a.`srb_barcde`,
                    a.`witb_barcde`,
                    a.`wob_barcde`,
                    a.`pob_barcde`,
                    a.`dmg_barcde`,
                    CONCAT(a.`box_no`,'/',(SELECT bb.`qty` FROM {$this->db_erp}.`gw_fg_wshe_barcdng_dt` bb WHERE bb.`stock_code` = a.`stock_code` AND bb.`mat_code`= a.`mat_code` AND bb.`header` = '{$mpohd_rid}'
                    AND bb.`to_wshe_id` = '{$_valid_wshe_id}' GROUP BY bb.`stock_code`)) box_no,
                    GROUP_CONCAT(ee.`ART_CODE` ORDER BY ee.`ART_CODE` ASC SEPARATOR ', ') __boxcontent,
                    c.`wshe_code`,
                    a.`convf`
                    FROM
                    {$this->db_erp}.`gw_fg_wshe_barcdng_dt` a
                    JOIN {$this->db_erp}.`gw_fg_wshe_barcdng_item` dd
                    ON (a.`recid` = dd.`dt_id`)
                    
                    JOIN {$this->db_erp}.`mst_article` ee
                    ON (dd.`mat_code` = ee.`ART_CODE`)
                    
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
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_FG_PACKING_BARCODE_DL','',$mpohd_rid,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
        }//endif

            $chtmljs .= "
                        <a href=\"{$cfilelnk}\" download=" . $file_name ." class='btn btn-dgreen btn-sm col-lg-12' onclick='$(this).remove()'> <i class='bi bi-save'></i> DOWNLOAD IT!</a>        
                        ";
            echo $chtmljs;
    } // end download_fgpack_barcode

    public function view_fg_rcvng_recs($npages = 1,$npagelimit = 30,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_whse = $this->request->getVar('mtkn_whse');

        $strqry = "
        SELECT 
        a.`recid`,
        a.`fgreq_trxno`,
        a.`tpa_trxno`, 
        b.`plnt_id`, 
        b.`branch_name`, 
        c.`req_pack`,
        (SELECT COUNT(`witb_barcde`) FROM fg_prod_barcdng_dt WHERE fgreq_trxno = a.`fgreq_trxno`) AS witb_barcde_gen,
        (SELECT COUNT(`witb_barcde`) FROM fgp_inv_rcv WHERE fgreq_trxno = a.`fgreq_trxno`) AS witb_barcde_inv
        
        FROM
        fg_prod_barcdng_dt a
        LEFT JOIN
        trx_tpa_hd b
        ON 
        a.`tpa_trxno` = b.`tpa_trxno`
        LEFT JOIN
        trx_fgpack_req_dt c
        ON
        a.`fgreq_trxno` = c.`fgreq_trxno`
        GROUP BY a.`fgreq_trxno`
        ORDER BY recid DESC

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
    } // end view_fg_rcvng_recs

    public function wh_fg_rcvng_upld(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $fgNo   =  $this->request->getVar('fgNo'); 
        //get items
        $str_itm = "

         ";



        $q3 = $this->mylibzdb->myoa_sql_exec($str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($q3->getNumRows() > 0 )
        {

            $data['result'] = $q3->getResultArray();
            $data['count'] = count($q3->getResultArray());
            $data['fg_code'] = $fgNo;

        }
        else
        {

            $data['result'] = '';
            $data['count']  = 0;
            $data['fg_code'] = $fgNo;

        }
        $data['response'] = true;
        return $data;
 
        
    } // end wh_fg_rcvng_upld

    public function mywh_fg_rcvng_save(){

        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $data_array = $this->request->getVar('data_array');
        $rowCount = $this->request->getVar('rowCount');
        $pono = $this->request->getVar('pono');


        if(empty($data_array))
        {
            $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>No items to be receive.</div>";
        }

        $_hd_ctrlno = $this->mydataz->get_ctr($this->db_erp,'CTRL_CWR');
        $qty = 1;
        //insert to logs
        $str = "
            INSERT INTO
        {$this->db_erp}.`fgp_inv_rcv`(
            `fgreq_trxno`,
            `tpa_trxno`,
            `stock_code`,
            `barcde`,
            `irb_barcde`,
            `srb_barcde`,
            `witb_barcde`,
            `wob_barcde`,
            `pob_barcde`,
            `dmg_barcde`,
            `rcv_date`
        )
        SELECT 
            `fgreq_trxno`,
            `tpa_trxno`,
            `stock_code`,
            `irb_barcde`,
            `irb_barcde`,
            `srb_barcde`,
            `witb_barcde`,
            `wob_barcde`,
            `pob_barcde`,
            `dmg_barcde`,
             now()
            FROM {$this->db_erp}.`fg_prod_barcdng_dt`
            WHERE  `witb_barcde` IN ($data_array) 
            ";

            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            /***************** AUDIT LOGS *************************/

            $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CENTRAL_RCVNG','OLD PROCESS SAVING CENTRAL',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            //update rcv tag in gr barcoding dt

            $str_up = "
            UPDATE  {$this->db_erp}.`fg_prod_barcdng_dt` dt,{$this->db_erp}.`fgp_inv_rcv` rcv
            SET  dt.`rcv_tag` = 1  
            WHERE  dt.`witb_barcde` IN ($data_array) AND  dt.`witb_barcde` =  rcv.`witb_barcde`
            ";
            $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    

            /***************** AUDIT LOGS *************************/

            $data = "<div class=\"alert alert-success mb-0\"><strong>SAVE</strong><br>Transaction successfully saved. <br> TRANSACTION NO: <span style=\"color:red;display:inline-block; \">{$pono}</span>
                <p>TOTAL QTY: <span style=\"color:red;display:inline-block; \">{$rowCount}</span></p>
                </div>
                
                ";
            echo $data;   
    } // end mywh_fg_rcvng_save

    public function view_ent_itm_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $mtkn_dt   = $this->request->getVar('mtkn_dt');
        
        $fgno   = $this->request->getVar('fgno');
        var_dump($fgno);
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
        LEFT JOIN  {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}'
        AND rcv.`header` = '{$fgno}'
        GROUP BY rcv.`witb_barcde` ORDER BY `recid` DESC";
        var_dump($strqry);
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['fgno'] = $fgno;
         
        } else { 
         $data = array();
         $data['fgno'] = $fgno;
         $data['rlist'] = '';
         $data['txtsearchedrec_rl'] = $msearchrec;
        }
        return $data;
    } // end view_ent_itm_recs

    public function fg_whrcdout_show(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $txtprod_type_upld_sub = $this->request->getVar('txtprod_type_upld_sub');
        $txtWarehouse   =  $this->request->getVar('txtWarehouse'); 
        $txtWarehousetkn   =  $this->request->getVar('txtWarehousetkn'); 
        $prefix = $this->request->getVar('prefix'); 
        $txt_drlist = $this->request->getVar('txt_drlist'); 
        

        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end

            //get items
            $str_itm = "SELECT
                     b.`recid`,
                     b.`qty`,
                     b.`qty`qty_scanned ,
                     b.`is_out`,
                     b.`trx`,
                      '' `uprice`,
                     b.`remarks`,
                     d.`plnt_code`,
                     e.`wshe_code`,
                     b.`box_no`,
                     b.`stock_code`,
                     c.`ART_CODE`,
                     c.`ART_DESC`,
                     b.`total_amount` tamt_scanned,
                     '' price,
                     b.`total_pcs` total_pcs_scanned,                
                     b.`wob_barcde`  barcde,
                    c.`ART_GWEIHGT` `weight`,
                    b.`cbm`

                FROM
                   {$this->db_erp}.`warehouse_inv_rcv` b
                 JOIN
                    {$this->db_erp}.`mst_article` c
                ON
                    b.`mat_rid` = c.`recid`
                 JOIN
                    {$this->db_erp}.`mst_plant` d
                ON
                    b.`plnt_id` = d.`recid`
                 JOIN
                    {$this->db_erp}.`mst_wshe` e
                ON
                    b.`wshe_id` = e.`recid`
                WHERE
                    b.`plnt_id` = {$plntID}
                AND
                    b.`wshe_id` = {$whID}
                AND
                    b.`is_out` = 0
                ORDER BY
                    b.`stock_code`
                             ";

           
            $q3 = $this->mylibzdb->myoa_sql_exec($str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            if($q3->getNumRows() > 0 )
            {
                $data['result'] = $q3->getResultArray();
                $data['count'] = $q3->getNumRows();
                $data['isdone'] = 0;
                $data['response'] = true;

            }
            else
            {
                $data['response'] = false;
                $data['result'] = '<div class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 "><h5> <i class="bi bi-info-circle-fill  text-dgreen"></i> No records found.</h5></div>';
                $data['count']  = 0;
                $data['isdone'] = 0;

            }
          
            return $data;
     
        
    } // end fg_whrcdout_show

    public function fg_mywhout_save(){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
    
        $adata1         =  $this->request->getVar('adata1');
        $adata2         =  $this->dbx->escapeString($adata1);
        $txtWarehousetkn=  $this->request->getVar('txtWarehousetkn'); 
        $control_number =  $this->request->getVar('control_number'); 
        $branch_name    =  $this->request->getVar('branch_name'); 
        $plate_number   =  $this->request->getVar('plate_number'); 
        $driver         =  $this->request->getVar('driver'); 
        $helper_one     =  $this->request->getVar('helper_one'); 
        $helper_two     =  $this->request->getVar('helper_two'); 
        $ref_no         =  $this->request->getVar('ref_no');
        $chk_by         =  $this->request->getVar('chk_by'); 
        $sm_tag         =  $this->request->getVar('sm_tag'); 
        $is_mkg         =  $this->request->getVar('is_mkg'); 
        $rcvcount     =  $this->request->getVar('rcvcount'); 
        $truck_type   = $this->request->getVar('truck_type');  
        $rems_   = $this->request->getVar('rems_');  

        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end
    
        //get company data
        $branchData = $this->mymelibzsys->getCompanyBranch_data_byname($branch_name);
        $branchID = $branchData['recid'];
        //get company data end
    
    
        //CHECK ITEMS IF ITS AVAILABLE
        $str_ck = "    SELECT `wob_barcde`FROM {$this->db_erp}.`warehouse_inv_rcv` WHERE  `wob_barcde` IN ($adata1) AND `wshe_id` = '{$whID}' AND `plnt_id` = '{$plntID}' AND `is_out` = 0 ";
        $qc = $this->mylibzdb->myoa_sql_exec($str_ck,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        if($qc->getNumRows() == 0){
          echo "Error's were detected while saving. Kindly report to system administrator. <br> <hr> <span class=\"fw-bold\"> <i class=\"text-danger bi bi-info-circle-fill\"> </i> Info: </span> It's possible that the selected barcodes has already been selected.";
         
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CD_OUT_ERROR','HEADER','',$str_ck,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
          die();
        }// checking end
    
    
    
        //save to hd
        $_hd_ctrlno = $this->mydataz->get_ctr_new_dr('TAP','',$this->db_erp,'CTRL_CWO'); 
        //"CWO".$this->mydataz->get_ctr($this->db_erp,'CTRL_CWO');
            $str_in = "INSERT INTO  {$this->db_erp}.`warehouse_shipdoc_hd` (
          `crpl_code`,
          `plate_no`,
          `frm_plnt_id`,
          `frm_wshe_id`,
          `driver`,
          `helper_1`,
          `helper_2`,
          `brnch`,
          `brnch_rid`,
          `refno`,
          `mkg_tag`,
          `chk_by`,
          `sm_tag`,
          `truck_type`,
          `me_remk`,
          `total_qty`,
          `user`,
          `date_encd`
            )
            VALUES
              (
            '{$_hd_ctrlno}',
            '{$plate_number}',
            '{$plntID}',
            '{$whID}',
            '{$driver}',
            '{$helper_one}',
            '{$helper_two}',
            '{$branch_name}',
            '{$branchID}',
            '{$ref_no}',
            '{$is_mkg}',
            '{$chk_by}',
            '{$sm_tag}',
            '{$truck_type}',
            '{$rems_}',
            '{$rcvcount}',
            '{$cuser}',
            now()
              );
       ";
        $this->mylibzdb->myoa_sql_exec($str_in,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        //save to hd end
        /***************** AUDIT LOGS *************************/
        $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CD_OUT','HEADER',$_hd_ctrlno,$str_in,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        /***************** AUDIT LOGS *************************/
    
        $str = "
        INSERT INTO
        {$this->db_erp}.`warehouse_shipdoc_dt`(
            `trx`,
            `header`,
            `stock_code`,
            `wob_barcde`,
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
            `encd`,
            `is_out`
        )
        SELECT 
        `trx`,
        '{$_hd_ctrlno}',
        `stock_code`,
        `wob_barcde`,
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
        '{$cuser}',
        now(),
        '1'
        FROM {$this->db_erp}.`warehouse_inv_rcv`
        WHERE  `wob_barcde` IN ($adata1) AND `wshe_id` = '{$whID}' AND `plnt_id` = '{$plntID}' AND `is_out` = 0 ";
    
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
        /***************** AUDIT LOGS *************************/
        $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CD_OUT','DT',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        /***************** AUDIT LOGS *************************/

            /***************** AUDIT LOGS *************************/
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CD_OUT','ITEM',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            /***************** AUDIT LOGS *************************/
    
            $str_up = "
            UPDATE  {$this->db_erp}.`warehouse_inv_rcv` rcv, {$this->db_erp}.`warehouse_shipdoc_dt`dt  
            SET  rcv.`is_out` = 1,
            rcv.`SD_NO` = '{$_hd_ctrlno}',
            rcv.`type` ='SD'  
            WHERE  
            rcv.`wshe_id` = '{$whID}' 
            AND rcv.`plnt_id` = '{$plntID}' 
            AND rcv.`wob_barcde` = dt.`wob_barcde` 
            AND dt.`header` = '{$_hd_ctrlno}'";
            $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
            /***************** AUDIT LOGS *************************/
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CD_OUT','RCV_UPDT',$_hd_ctrlno,$str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            /***************** AUDIT LOGS *************************/
    
         // insert to logs
         $str_log = "INSERT INTO {$this->db_erp}.`warehouse_shipdoc_logs` (`crpl_code`,`wob_barcde`,`type`,`muser`,`encd`)
                VALUES('{$_hd_ctrlno}','{$adata2}','S','{$cuser}',now())";
        $this->mylibzdb->myoa_sql_exec($str_log,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        //display tranaction number disabled save button
        echo "<script></script>";
    
        echo "Successfully save! 
            <script type=\"text/javascript\"> 
             __mysys_apps.display_trxno('{$_hd_ctrlno}','control-number','btn-pl-sv'); 
            </script>";
    
    } // end fg_mywhout_save

    public function fgp_box_content_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_dt = $this->request->getVar('mtkn_dt');
        // var_dump($mtkn_dt);
        // die();
        $strqry = "
            SELECT 
            a.`fgreq_trxno`,
            a.`stock_code`,
            b.`mat_code`,
            c.`ART_DESC`,
            c.`ART_UPRICE`,
            c.`ART_UCOST`,
            c.`ART_SKU`
            FROM
            fgp_inv_rcv a
            JOIN 
            trx_fgpack_req_dt b
            ON
            b.`fgreq_trxno` = a.`fgreq_trxno`
            JOIN
            mst_article c
            ON
            b.`mat_code` = c.`ART_CODE`
            WHERE
            a.`fgreq_trxno` = '$mtkn_dt'
            GROUP BY wob_barcde
            ORDER BY rcv_date DESC
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
    } // end fgp_box_content_recs

}
