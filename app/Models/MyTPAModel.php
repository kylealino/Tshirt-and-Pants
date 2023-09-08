<?php
/*
 * Module      :    MyTPAModel.php
 * Type 	   :    Model
 * Program Desc:    MyTPAModel
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/
namespace App\Models;
use CodeIgniter\Model;

class MyTPAModel extends Model
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
    
    public function tpa_entry_save1() {

        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");
        $tpa_trxno = $this->request->getVar('tpa_trxno');
        $mtkn_mntr = $this->request->getVar('mtkn_mntr');
        $active_plnt_id = $this->request->getVar('active_plnt_id');
        $branch_name = $this->request->getVar('branch_name');
        $txt_request_date = $this->request->getVar('txt_request_date');
        $txt_total_qty = $this->request->getVar('txt_total_qty');
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');

        if (empty($branch_name)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Please select a Branch!!! </div>";
            die();
        }

        if (empty($active_plnt_id)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Please select a Plant!!! </div>";
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
             $str = "select aa.recid,aa.tpa_trxno from {$this->db_erp}.trx_tpa_hd aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_mntr'";
             $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
             if($q->resultID->num_rows == 0) {
                 echo "No Records Found!!!";
                 die();
             }
             $rw = $q->getRowArray();
             $mfgp_rid = $rw['recid'];
             $cseqn = $rw['tpa_trxno'];
             $q->freeResult();
 
         }//endif
         //INSERT
         else{
             
             $cseqn =  $this->mydataz->get_ctr_new_dr('TPA','',$this->db_erp,'CTRL_GWFGPA');//TRANSACTION NO
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

                $mdmd = trim($medata[2]);

                $amatnr = array();
                if(!empty($cmat_code)) { 
                    $str = "select aa.recid,aa.ART_CODE from {$this->db_erp}.`mst_article` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mat_mtkn' and ART_CODE = '$cmat_code' ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    
                    if($q->resultID->num_rows == 0) {
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Invalid Material Data!!!<br/>[$cmat_code]</div>";
                        die();
                    }
                    else{

                         $rw = $q->getRowArray();
                         $mmat_rid = $rw['recid'];

                         array_push($adatar1,$medata);
                         array_push($adatar2,$mmat_rid);
                         $ntqty = ($ntqty + $mdmd);
                    }

                   $q->freeResult();
                }

            }  //end for 

            if(count($adatar1) > 0) { 
                if(!empty($mtkn_mntr)) {       
                     $str = "
                        update {$this->db_erp}.`trx_tpa_hd` set 
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
                        insert into {$this->db_erp}.`trx_tpa_hd` (
                        `tpa_trxno`,
                        `plnt_id`,
                        `branch_name`,
                        `req_date`,
                        `total_qty`
                        ) values(
                        '$cseqn',
                        '$active_plnt_id',
                        '$branch_name',
                        '$txt_request_date',
                        '$txt_total_qty'
                        
                        )
                        ";
                      $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                      $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_FG_PACKING_ADD_REC','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 


                    }//endesle

                    for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                        $xdata = $adatar1[$xx];
                        $cmat_code = $xdata[0];
                        $mat_rid = $adatar2[$xx];
                        $mdmd = trim($xdata[2]);
                        $cmtext = trim($xdata[3]);

                        if (empty($mdmd)) {
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Demand must not be null. </div>";
                            die();
                        }

                        if(empty($mtkn_mntr)) {  
                            $str = "
                            insert into {$this->db_erp}.`trx_tpa_dt` ( 
                                `tpa_trxno`,
                                `mat_code`,
                                `demand_qty`
      
                            ) values(
                                '$cseqn',
                                '$cmat_code',
                                '$mdmd'
                            )
                            ";
                          $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                          $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_DT_ADD','',$cseqn,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                        } else { 
                            if(empty($fgpackdt_rid)) { 
                                $str = "
                                insert into {$this->db_erp}.`gw_fg_pack_dt` ( 
                                    `tpa_trxno`,
                                    `item_code`,
                                    `item_qty`
                                ) values(
                                    '$cseqn',
                                    '$cmat_code',
                                    '$nqty'
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
                        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! Packing Series No:{$cseqn} </div>
                        <script type=\"text/javascript\"> 
                            function __fg_refresh_data() { 
                                try { 
                                    $('#tpa_trxno').val('{$cseqn}');
                                    
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

    } //end tpa_entry_save1

    public function tpa_entry_save() {


        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");
        $prod_plan_trxno = $this->request->getVar('prod_plan_trxno');
        $tpa_trxno = $this->request->getVar('tpa_trxno');
        $mtkn_mntr = $this->request->getVar('mtkn_mntr');
        $active_plnt_id = $this->request->getVar('active_plnt_id');
        $branch_name = $this->request->getVar('branch_name');
        $txt_request_date = $this->request->getVar('txt_request_date');
        $txt_total_qty = $this->request->getVar('txt_total_qty');
        $entry_date = $this->request->getVar('entry_date');
        $adata1 = $this->request->getVar('adata1');

        if (empty($branch_name)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Please select a Branch!!! </div>";
            die();
        }

        if (empty($active_plnt_id)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Please select a Plant!!! </div>";
            die();
        }
        if(empty($adata1)) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No Item Data!!!.</div>";
            die();
        }
        
        $cseqn =  $this->mydataz->get_ctr_new_dr('TPA','',$this->db_erp,'CTRL_GWFGPA');

        $strHd = "
        insert into {$this->db_erp}.`trx_tpa_hd` (
            `tpa_trxno`,
            `plnt_id`,
            `branch_name`,
            `req_date`,
            `total_qty`
            ) values(
            '$cseqn',
            '$active_plnt_id',
            '$branch_name',
            '$entry_date',
            '$txt_total_qty'
            
            )
        ";

        $q = $this->mylibzdb->myoa_sql_exec($strHd,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $str = "
            UPDATE prod_plan_hd SET is_processed = 1 WHERE prod_plan_trxno = '$prod_plan_trxno';
        ";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
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
           
            if(count($adatar1) > 0) { 
                for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                    $xdata = $adatar1[$xx];
                    $cmat_code = $xdata[0];
                    $mdmd = trim($xdata[2]);
                    $cmtext = trim($xdata[3]);



                    $str = "
                    insert into {$this->db_erp}.`trx_tpa_dt` ( 
                        `tpa_trxno`,
                        `prod_plan_trxno`,
                        `mat_code`,
                        `demand_qty`

                    ) values(
                        '$cseqn',
                        '$prod_plan_trxno',
                        '$cmat_code',
                        '$mdmd'
                    )
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                }
                
                } 
                
            } 

            echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! Series No:{$cseqn} </div>
            <script type=\"text/javascript\"> 
                function __fg_refresh_data() { 
                    try { 
                        $('#tpa_trxno').val('{$cseqn}');
                        
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

    } //end tpa_entry_save

    public function tpa_update() {

        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");
        $tpa_trxno = $this->request->getVar('tpa_trxno');
        $mtkn_mntr = $this->request->getVar('mtkn_mntr');
        $active_plnt_id = $this->request->getVar('active_plnt_id');
        $branch_name = $this->request->getVar('branch_name');
        $txt_request_date = $this->request->getVar('txt_request_date');
        $txt_total_qty = $this->request->getVar('txt_total_qty');
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');

        if (empty($branch_name)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Please select a Branch!!! </div>";
            die();
        }

        if (empty($active_plnt_id)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Please select a Plant!!! </div>";
            die();
        }
        if(empty($adata1)) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No Item Data!!!.</div>";
            die();
        }

        if (!empty($tpa_trxno)) {

            $strhd = "
            UPDATE `trx_tpa_hd` SET
                `plnt_id` = '$active_plnt_id',
                `branch_name` = '$branch_name'
            WHERE
                `tpa_trxno` = '$tpa_trxno'
            ";

            $q = $this->mylibzdb->myoa_sql_exec($strhd,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  

        }


        if(count($adata1) > 0) { 
            $ame = array();
            $adatar1 = array();

            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);
                $cmat_code = trim($medata[0]);
                $mat_mtkn = $adata2[$aa];

                $mdmd = trim($medata[2]);

                $amatnr = array();
                
                if(!empty($cmat_code)) { 
                    $str = "select aa.recid,aa.ART_CODE from {$this->db_erp}.`mst_article` aa where ART_CODE = '$cmat_code' ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $rw = $q->getRowArray(); 
                    array_push($ame,$cmat_code); 
                    array_push($adatar1,$medata);

                }
                }
            }  
           
            if(count($adatar1) > 0) { 
                for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                    $xdata = $adatar1[$xx];
                    $cmat_code = $xdata[0];
                    $mdmd = trim($xdata[2]);
                    $cmtext = trim($xdata[3]);
                    
                    if (empty($mdmd)) {
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Demand must not be null. </div>";
                        die();
                    }

                    $strdt="
                        UPDATE `trx_tpa_dt` 
                        SET
                            `mat_code` = '$cmat_code',
                            `demand_qty` = '$mdmd'
                        WHERE 
                            `tpa_trxno` = '$tpa_trxno' AND `mat_code` = '$cmat_code'
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($strdt,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                

                    }
                
                } 

                
                $str =" 
                SELECT SUM(`demand_qty`) as demand_qty
                FROM
                trx_tpa_dt
                WHERE 
                    `tpa_trxno` = '$tpa_trxno'
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);  
                $rw = $q->getRowArray(); 
        
                $demand = $rw['demand_qty'];
                
                $strd="
                UPDATE `trx_tpa_hd` 
                SET
                    `total_qty` = '$demand'
                WHERE 
                    `tpa_trxno` = '$tpa_trxno'
                ";
                $q = $this->mylibzdb->myoa_sql_exec($strd,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
        
            

            echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Updated Successfully!!!</div>
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

    } //end tpa_update

    public function rm_req_rec_view($npages = 1,$npagelimit = 30,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');

        $str_optn = "";
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " WHERE
            (a.`tpa_trxno` LIKE '%{$msearchrec}%') ";
        }


        $strqry = "
        SELECT
          a.`tpa_trxno`,
          a.`branch_name`,
          a.`req_date`,
          (SELECT SUM(`demand_qty`) FROM trx_tpa_dt WHERE `tpa_trxno` = a.`tpa_trxno`) AS total_qty,
          a.`is_processed`,
          b.`prod_plan_trxno`
        FROM
            `trx_tpa_hd` a
        JOIN
            `trx_tpa_dt` b
        ON 
            a.`tpa_trxno` = b.`tpa_trxno`   
        GROUP BY a.`tpa_trxno`
        
        

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
    } //end rm_req_rec_view

    public function rm_req_view_itm_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $mtkn_dt   = $this->request->getVar('mtkn_dt');
        
        $tpano   = $this->request->getVar('tpano');

        $str_optn = '';
        if(!empty($msearchrec)){ 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = "
                AND
                    (a.`tpa_trxno` LIKE '%{$msearchrec}%' ')
            ";
        }

        $strqry = "
        SELECT

        a.`tpa_trxno`, 
        b.`mat_code`, 
        c.`ART_DESC`, 
        b.`demand_qty`

        FROM 
        trx_tpa_hd a
        JOIN
        trx_tpa_dt b
        ON
        a.`tpa_trxno` = b.`tpa_trxno`
        JOIN
        mst_article c
        ON
        b.`mat_code` = c.`ART_CODE`
        WHERE 
        b.`tpa_trxno` = '{$tpano}'";
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['tpano'] = $tpano;
         
        } else { 
         $data = array();
         $data['tpano'] = $tpano;
         $data['rlist'] = '';
         $data['txtsearchedrec_rl'] = $msearchrec;
        }
        return $data;
    } //end rm_req_view_itm_recs

    public function req_rec_view($npages = 1,$npagelimit = 30,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');


        $strqry = "
        SELECT
          a.`prod_plan_trxno`,
          a.`brnch_name`,
          a.`entry_date`,
          a.`qty_serve`,
          a.`amount_serve`,
          a.`is_processed`

        FROM
        `prod_plan_hd` a

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
    } //end req_rec_view
    
} 