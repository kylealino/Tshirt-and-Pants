<?php
/*
 * Module      :    MyRMProductionModel.php
 * Type 	   :    Model
 * Program Desc:    MyRMProductionModel
 * Author      :    Kyle P. Alino
 * Date Created:    July. 7, 2023
*/

namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Files\File;

class MyRMProductionModel extends Model
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
        $this->dbx = $this->mylibzdb->dbx;
        $this->request = \Config\Services::request();
    }

    public function rm_prod_view_recs(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $strqry = "
        SELECT 
        a.`rmap_trxno`,
        a.`plnt_id`,
        a.`request_date`,
        SUM(b.`item_qty`) item_qty,
        SUM(b.`produce_release`) produce_release,
        SUM(b.`produce_rmng`) produce_rmng
        FROM
        trx_rmap_req_hd a
        JOIN
        trx_rmap_req_dt b
        ON
        a.`rmap_trxno` = b.`rmap_trxno`
        GROUP BY a.`rmap_trxno`
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
    } //end rm_prod_view_recs

    public function rm_out_view_itm_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_dt   = $this->request->getVar('mtkn_dt');
        $rmapno   = $this->request->getVar('rmapno');

        $strqry = "
        SELECT
        a.`rmap_trxno`, b.`item_code`, c.`ART_DESC`, c.`ART_SKU`,b.`item_qty`
        FROM 
        trx_rmap_req_hd a
        JOIN
        trx_rmap_req_dt b
        ON
        a.`rmap_trxno` = b.`rmap_trxno`
        JOIN
        mst_article c
        ON
        b.`item_code` = c.`ART_CODE`
        WHERE 
        b.`rmap_trxno` = '{$rmapno}'";
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
    } //end rm_out_view_itm_recs

    public function rm_prod_save(){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $rmap_trxno = $this->request->getVar('rmap_trxno');
        $fgreq_trxno = $this->request->getVar('fgreq_trxno');
        $adata1 = $this->request->getVar('adata1');


        if (empty($adata1)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> No Item Data! </div>";
            die();
        }

        $str="
        SELECT a.`plnt_id`, b.`recid` FROM trx_rmap_req_hd a JOIN mst_plant b ON a.`plnt_id` = b.`plnt_code` WHERE `rmap_trxno` = '$rmap_trxno'
        ";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $rw = $q->getRowArray(); 
        $plnt_id = $rw['recid'];

        if (empty($fgreq_trxno)) {

            $cseqn =  $this->mydataz->get_ctr_new_dr('FGRM','',$this->db_erp,'CTRL_GWFGPA');

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


                $str="
                INSERT INTO gw_fg_po_hd (`po_sysctrlno`,`muser`,`encd_date`,`tqty`,`plnt_id`) SELECT '$cseqn', '$cuser', now(),`total_qty`,'$plnt_id' FROM trx_rmap_req_hd WHERE  `rmap_trxno` = '$rmap_trxno'
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

               
                if(count($adatar1) > 0) { 
                    for($xx = 0; $xx < count($adatar1); $xx++) { 
                        
                        $xdata = $adatar1[$xx];
                        $mitemc = $xdata[0];
                        $mrelease = $xdata[1];
                        $mreqqty = $xdata[2];

                        if ($mrelease > $mreqqty) {
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Release Qty cannot be greater than Request Qty! </div>";
                            die();
                        }

                        
                    $str="
                        UPDATE trx_rmap_req_dt SET `produce_rmng` = `produce_rmng` - '$mrelease', `produce_release` = `produce_release` + '$mrelease', `fgreq_trxno` = '$cseqn' WHERE `rmap_trxno` = '$rmap_trxno' AND item_code = '$mitemc'
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    $str="
                    INSERT INTO gw_fg_po_dt (`po_sysctrlno`,`mat_code`,`qty`,`rmng_qty`) VALUES('$cseqn', '$mitemc', '$mrelease','$mrelease')
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
                    }
                    
                } 

                $str="
                UPDATE trx_rmap_req_hd SET `fgreq_trxno` = '$cseqn' WHERE `rmap_trxno` = '$rmap_trxno'
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    
            }

            echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! Series No:{$cseqn} </div>
            <script type=\"text/javascript\"> 
                function __fg_refresh_data() { 
                    try { 
                        $('#fgreq_trxno').val('{$cseqn}');
                        
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
        }
        else{

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
               
                $str="
                INSERT INTO gw_fg_po_hd (`po_sysctrlno`,`muser`,`encd_date`,`tqty`,`plnt_id`) SELECT '$cseqn', '$cuser', now(),`total_qty`,'$plnt_id' FROM trx_rmap_req_hd WHERE  `rmap_trxno` = '$rmap_trxno'
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                if(count($adatar1) > 0) { 
                    for($xx = 0; $xx < count($adatar1); $xx++) { 
                        
                        $xdata = $adatar1[$xx];
                        $mitemc = $xdata[0];
                        $mrelease = $xdata[1];
                        
                        if ($mrelease > $mreqqty) {
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Release Qty cannot be greater than Request Qty! </div>";
                            die();
                        }

    
                        $str="
                        UPDATE trx_rmap_req_dt SET `produce_rmng` = `produce_rmng` - '$mrelease', `produce_release` = `produce_release` + '$mrelease' WHERE `rmap_trxno` = '$rmap_trxno' AND item_code = '$mitemc'
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    $str="
                    INSERT INTO gw_fg_po_dt (`po_sysctrlno`,`mat_code`,`qty`,`rmng_qty`) VALUES('$cseqn', '$mitemc', '$mrelease','$mrelease')
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
                    }
                    
                } 

                $str="
                UPDATE trx_rmap_req_hd SET `fgreq_trxno` = '$fgreq_trxno' WHERE `rmap_trxno` = '$rmap_trxno'
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    
            }

            echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! Series No:{$fgreq_trxno} </div>
            <script type=\"text/javascript\"> 
                function __fg_refresh_data() { 
                    try { 
                        $('#fgreq_trxno').val('{$fgreq_trxno}');
                        
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
        }

    } //end rm_prod_save

} 