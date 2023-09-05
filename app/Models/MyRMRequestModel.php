<?php
/*
 * Module      :    MyRMRequestModel.php
 * Type 	   :    Model
 * Program Desc:    MyRMRequestModel
 * Author      :    Kyle P. Alino
 * Date Created:    July. 7, 2023
*/
namespace App\Models;
use CodeIgniter\Model;

class MyRMRequestModel extends Model
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
    
    public function rm_req_entry_save() {

        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");
        $rmap_trxno = $this->request->getVar('rmap_trxno');
        $mtkn_mntr = $this->request->getVar('mtkn_mntr');
        $active_plnt_id = $this->request->getVar('active_plnt_id');
        $txt_request_date = $this->request->getVar('txt_request_date');
        $txt_total_qty = $this->request->getVar('txt_total_qty');
        $txt_total_amount = $this->request->getVar('txt_total_amount');
        $remarks = $this->request->getVar('remarks');
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');

        if(!empty($active_plnt_id)) {
            $str = "SELECT `recid` FROM {$this->db_erp}.`mst_plant` WHERE plnt_code = '{$active_plnt_id}' ";
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

        if(empty($adata1)) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No Item Data!!!.</div>";
            die();
        }

        $mfgp_rid = '';
        $cseqn = '';

        if(!empty($mtkn_mntr)) { 
            //CHECK IF VALID PO
             $str = "select aa.recid,aa.rmap_trxno from {$this->db_erp}.trx_rmap_req_hd aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_mntr'";
             $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
             if($q->resultID->num_rows == 0) {
                 echo "No Records Found!!!";
                 die();
             }
             $rw = $q->getRowArray();
             $mfgp_rid = $rw['recid'];
             $cseqn = $rw['rmap_trxno'];
             $q->freeResult();
 
         }//endif
         //INSERT
         else{
             
             $cseqn =  $this->mydataz->get_ctr_new_dr('RMAP','',$this->db_erp,'CTRL_GWFGPA');//TRANSACTION NO
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

                $nqty = trim($medata[2]);
                $mremks = $medata[3];

      

               $cmat_code_plnt_wshe = trim($medata[0]) . $plnt_id ;

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
                    }

                   $q->freeResult();
                }

            }  //end for 

            if(count($adatar1) > 0) { 
                if(!empty($mtkn_mntr)) {       
                     $str = "
                        update {$this->db_erp}.`trx_rmap_req_hd` set 
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
                        insert into {$this->db_erp}.`trx_rmap_req_hd` (
                        `rmap_trxno`,
                        `plnt_id`,
                        `request_date`,
                        `total_qty`,
                        `remarks`,
                        `total_amount`
                        ) values(
                        '$cseqn',
                        '$active_plnt_id',
                        '$txt_request_date',
                        '$txt_total_qty',
                        '$remarks',
                        '$txt_total_amount'
                        
                        )
                        ";
                      $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                      $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_FG_PACKING_ADD_REC','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 


                    }//endesle

                    for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                        $xdata = $adatar1[$xx];
                        $cmat_code = $xdata[0];
                        $mat_rid = $adatar2[$xx];
                        $nqty = trim($xdata[2]);
                        $cmtext = trim($xdata[3]);
                        $mamount = trim($xdata[4]);
                        
                         
                        if(empty($plnt_id)){
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Plant</div>";
                            die();
                        }

                        if(empty($mtkn_mntr)) {  
                            $str = "
                            insert into {$this->db_erp}.`trx_rmap_req_dt` ( 
                                `rmap_trxno`,
                                `item_code`,
                                `item_qty`,
                                `rmng_qty`,
                                `produce_rmng`,
                                `item_tamount`
      
                            ) values(
                                '$cseqn',
                                '$cmat_code',
                                '$nqty',
                                '$nqty',
                                '$nqty',
                                '$mamount'
              
                            )
                            ";
                          $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                          
                          $str="
                            UPDATE `trx_rmap_req_dt` SET WHERE `item_code` = '$cmat_code';
                          ";
                          $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                          $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_DT_ADD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                        } else { 
                            if(empty($fgpackdt_rid)) { 
                                $str = "
                                insert into {$this->db_erp}.`gw_fg_pack_dt` ( 
                                    `rmap_trxno`,
                                    `item_code`,
                                    `item_qty`,
                                ) values(
                                    '$cseqn',
                                    '$cmat_code',
                                    '$nqty',
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
                                    $('#rmap_trxno').val('{$cseqn}');
                                    
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

    } //end rm_req_entry_save

    public function rm_save(){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");
        $rmap_trxno = $this->request->getVar('rmap_trxno');
        $mtkn_mntr = $this->request->getVar('mtkn_mntr');
        $active_plnt_id = $this->request->getVar('active_plnt_id');
        $txt_request_date = $this->request->getVar('txt_request_date');
        $txt_total_qty = $this->request->getVar('txt_total_qty');
        $txt_total_amount = $this->request->getVar('txt_total_amount');
        $remarks = $this->request->getVar('remarks');
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');

        if(!empty($active_plnt_id)) {
            $str = "SELECT `recid` FROM {$this->db_erp}.`mst_plant` WHERE plnt_code = '{$active_plnt_id}' ";
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

        if (empty($adata1)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> No Item Data! </div>";
            die();
        }

        $cseqn =  $this->mydataz->get_ctr_new_dr('RMAP','',$this->db_erp,'CTRL_GWFGPA');//TRANSACTION NO

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
                    array_push($adatar1,$medata);
                }
            }  

            $str = "
            insert into {$this->db_erp}.`trx_rmap_req_hd` (
            `rmap_trxno`,
            `plnt_id`,
            `request_date`,
            `total_qty`,
            `remarks`,
            `total_amount`
            ) values(
            '$cseqn',
            '$active_plnt_id',
            '$txt_request_date',
            '$txt_total_qty',
            '$remarks',
            '$txt_total_amount'
            
            )
            ";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  

            if(count($adatar1) > 0) { 
                for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                    $xdata = $adatar1[$xx];
                    $cmat_code = $xdata[0];
                    $mat_rid = $adatar2[$xx];
                    $nqty = trim($xdata[2]);
                    $cmtext = trim($xdata[3]);
                    $mamount = trim($xdata[4]);


                    $str = "
                    insert into {$this->db_erp}.`trx_rmap_req_dt` ( 
                        `rmap_trxno`,
                        `item_code`,
                        `item_qty`,
                        `rmng_qty`,
                        `produce_rmng`,
                        `item_tamount`

                    ) values(
                        '$cseqn',
                        '$cmat_code',
                        '$nqty',
                        '$nqty',
                        '$nqty',
                        '$mamount'
      
                    )
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                    

                }
                
            }else { 
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No VALID Item Data!!!.</div>";
                die();
            } //end adatar1        
        }

        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! Series No:{$cseqn} </div>
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
        die();
        
    } //end rm_save

    public function rm_req_rec_view($npages = 1,$npagelimit = 30,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');
       
        $strqry = "
        SELECT 
          a.`recid`,
          a.`rmap_trxno`,
          a.`plnt_id`,
          a.`request_date`,
          a.`total_qty`,
          a.`remarks`

        FROM
            {$this->db_erp}.`trx_rmap_req_hd` a
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
    } //end rm_req_rec_view

    public function rm_req_view_itm_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $mtkn_dt   = $this->request->getVar('mtkn_dt');
        
        $rmapno   = $this->request->getVar('rmapno');

        $str_optn = '';
        if(!empty($msearchrec)){ 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = "
                AND
                    (a.`rmap_trxno` LIKE '%{$msearchrec}%' ')
            ";
        }

        $strqry = "
        SELECT
        b.`fabric_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`fabric_code`) AS fabric_desc,
        (SELECT (`fabric_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) fabric_qty,
        b.`lining_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`lining_code`) AS lining_desc,
        (SELECT (`lining_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) lining_qty,
        b.`btn_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`btn_code`) AS btn_desc,
        (SELECT (`btn_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) btn_qty,
        b.`rivets_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`rivets_code`) AS rivets_desc,
        (SELECT (`rivets_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) rivets_qty,
        b.`leather_patch_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`leather_patch_code`) AS leather_patch_desc,
        (SELECT (`leather_patch_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) leather_patch_qty,
        b.`plastic_btn_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`plastic_btn_code`) AS plastic_btn_desc,
        (SELECT (`plastic_btn_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) plastic_btn_qty,
        b.`inside_garter_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`inside_garter_code`) AS inside_garter_desc,
        (SELECT (`inside_garter_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) inside_garter_qty,
        b.`hang_tag_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`hang_tag_code`) AS hang_tag_desc,
        (SELECT (`hang_tag_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) hang_tag_qty,
        b.`zipper_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`zipper_code`) AS zipper_desc,
        (SELECT (`zipper_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) zipper_qty,
        b.`size_lbl_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`size_lbl_code`) AS size_lbl_desc,
        (SELECT (`size_lbl_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) size_lbl_qty,
        b.`size_care_lbl_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`size_care_lbl_code`) AS size_care_lbl_desc,
        (SELECT (`size_care_lbl_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) size_care_lbl_qty,
        b.`side_lbl_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`side_lbl_code`) AS side_lbl_desc,
        (SELECT (`side_lbl_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) side_lbl_qty,
        b.`kids_lbl_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`kids_lbl_code`) AS kids_lbl_desc,
        (SELECT (`kids_lbl_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) kids_lbl_qty,
        b.`kids_side_lbl_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`kids_side_lbl_code`) AS kids_side_lbl_desc,
        (SELECT (`kids_side_lbl_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) kids_side_lbl_qty,
        b.`plastic_bag_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`plastic_bag_code`) AS plastic_bag_desc,
        (SELECT (`plastic_bag_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) plastic_bag_qty,
        b.`barcode_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`barcode_code`) AS barcode_desc,
        (SELECT (`barcode_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) barcode_qty,
        b.`fitting_sticker_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`fitting_sticker_code`) AS fitting_sticker_desc,
        (SELECT (`fitting_sticker_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) fitting_sticker_qty,
        b.`tag_pin_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`tag_pin_code`) AS tag_pin_desc,
        (SELECT (`tag_pin_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) tag_pin_qty,
        b.`chip_board_code`,
        (SELECT ART_DESC FROM mst_article WHERE ART_CODE = b.`tag_pin_code`) AS chip_board_desc,
        (SELECT (`chip_board_qty` * a.`item_qty`) FROM mst_item_comp WHERE ART_CODE = a.`item_code`) chip_board_qty
        FROM 
        trx_rmap_req_dt a
        JOIN
        mst_item_comp b
        ON
        a.`item_code` = b.`ART_CODE`
        WHERE 
        a.`rmap_trxno` = '{$rmapno}'";
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['rmapno'] = $rmapno;
         
        } else { 
         $data = array();
         $data['rmapno'] = $rmapno;
         $data['rlist'] = '';
         $data['txtsearchedrec_rl'] = $msearchrec;
        }
        return $data;
    } //end rm_req_view_itm_recs
           
}