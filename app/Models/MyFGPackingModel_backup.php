<?php
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
        $this->mydataz = model('App\Models\MyDatumModel');
        $this->dbx = $this->mylibzdb->dbx;
        $this->request = \Config\Services::request();
    }
    
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
        {$str_optn}
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
    } 
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

    } //end test_entry_save
   
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
                                SELECT recid,fgpack_trxno,noofpack qty,tqty convf,tamt,plnt_id,wshe_id,rmks mtext
                                FROM
                                {$this->db_erp}.`gw_fg_pack_hd`
                                WHERE
                                `recid` = {$_valid_po_id}
                            ";
                            $boxquery_details = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            $rrec = $boxquery_details->getResultArray();
                            foreach($rrec as $row){
                                $_valid_po_dt_id = $row['recid']; 
                                $_valid_po_id = $row['recid']; 
                                $no_of_box = $row['qty'];
                                //$mat_rid = $row['fgpack_trxno']; 
                                $qty = $row['qty']; 
                                $convf = $row['convf']; 
                                $tamt = $row['tamt']; 
                                $price = 0;//$row['price']; 
                                $mat_code = $row['fgpack_trxno']; 

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
                                $remarks = $row['mtext']; 

                                

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
                                            '{$mat_code}',
                                            '{$qty2}',
                                            '{$convf}',
                                            '{$cbm}',
                                            '{$convf}',
                                            '{$total_amount}',
                                            '{$remarks}',
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
    }//po_barcde_gnrtion
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
    } 
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

    }
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
    }
} //end main MyMDCustomerModel