<?php
/*
 * Module      :    MyRMOutboundModel.php
 * Type 	   :    Model
 * Program Desc:    MyRMOutboundModel
 * Author      :    Kyle P. Alino
 * Date Created:    July. 7, 2023
 * Last update :    July. 7, 2023
*/

namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Files\File;

class MyRMOutboundModel extends Model
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

    public function rm_out_view_recs(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $strqry = "
        SELECT 
        a.`rmap_trxno`,
        a.`plnt_id`,
        a.`request_date`,
        SUM(b.`item_qty`) item_qty,
        SUM(b.`release_qty`) release_qty,
        SUM(b.`rmng_qty`) rmng_qty
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
    } //end rm_out_view_recs

    public function rm_out_vw_process(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $strqry = "
        SELECT
             
            d.rmap_trxno,
            d.plnt_id,
            d.request_date,
            rmtqty.overall_sum as total_qty,
            d.is_processed
        FROM
            trx_rmap_req_hd d
        JOIN
            (SELECT a.rmap_trxno, SUM(b.item_qty * a.item_qty) AS overall_sum
            FROM trx_rmap_req_dt a
            JOIN mst_item_comp2 b ON a.item_code = b.fg_code
            JOIN trx_rmap_req_hd d ON a.rmap_trxno = d.rmap_trxno
            GROUP BY a.rmap_trxno) AS rmtqty
        ON
            d.rmap_trxno = rmtqty.rmap_trxno
        WHERE
            d.is_processed = '0'
        
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
    } //end rm_out_vw_process

    public function rm_out_vw_produce(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $strqry = "
        SELECT
             
            d.rmap_trxno,
            d.plnt_id,
            d.request_date,
            rmtqty.overall_sum as total_qty,
            d.is_processed
        FROM
            trx_rmap_req_hd d
        JOIN
            (SELECT a.rmap_trxno, SUM(b.item_qty * a.item_qty) AS overall_sum
            FROM trx_rmap_req_dt a
            JOIN mst_item_comp2 b ON a.item_code = b.fg_code
            JOIN trx_rmap_req_hd d ON a.rmap_trxno = d.rmap_trxno
            GROUP BY a.rmap_trxno) AS rmtqty
        ON
            d.rmap_trxno = rmtqty.rmap_trxno
        WHERE
            d.is_processed = '1'
        
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
    } //end rm_out_vw_produce

    public function rm_out_vw_lacking(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $strqry = "
        SELECT
            a.`rmap_trxno`,
            a.`fg_code`,
            a.`rm_code`,
            a.`total_qty`,
            (SELECT po_qty FROM rm_inv_rcv WHERE mat_code = a.`rm_code`) AS rm_inv
        FROM
            `trx_rm_out_lacking` a
        
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
    } //end rm_out_vw_lacking

    public function rm_save(){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $rmap_trxno = $this->request->getVar('rmap_trxno');
        $fgreq_trxno = $this->request->getVar('fgreq_trxno');
        $adata1 = $this->request->getVar('adata1');


        if (empty($adata1)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> No Item Data! </div>";
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
                    array_push($adatar1,$medata);
                }
            }  

            if(count($adatar1) > 0) { 
                for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                    $xdata = $adatar1[$xx];
                    $mitemc = $xdata[0];
                    $mrelease = $xdata[1];
                    $mreqqty = $xdata[2];
                    $minv = $xdata[3];

                    if ($mrelease > $mreqqty) {
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Release Qty cannot be greater than Request Qty for item ['$mitemc']! </div>";
                        die();
                    }

                    if (empty($minv)) {
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Stocks unvailable for item ['$mitemc']</div>";
                        die();
                    }
                    
                    // $str="
                    //     UPDATE trx_rmap_req_dt SET `rmng_qty` = `rmng_qty` - '$mrelease', `release_qty` = `release_qty` + '$mrelease' WHERE `rmap_trxno` = '$rmap_trxno' AND item_code = '$mitemc'
                    // ";
                    // $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                }
                
            }       
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

    public function rm_req_save(){
        
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $rmapno = $this->request->getVar('rmapno');
        $adata1 = $this->request->getVar('adata1');
        $count=0;
        if (empty($adata1)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> No Item Data! </div>";
            die();
        }

        //SELECT ITEM CODE FIRST
        $str="
        SELECT 
            item_code
        FROM 
            trx_rmap_req_dt
        WHERE 
            rmap_trxno = '$rmapno'
        ";

        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $item = array();
        if($qry->getNumRows() > 0){
            foreach($qry->getResultArray() as $row){
                $mitemc = $row['item_code'];

                $item_data = $mitemc;
                array_push($item, $item_data);
            }
        }
        
        //SELECT CORRESPONDING RAW MATS
        for($i = 0; $i < count($item); $i++){
			$data = explode('x|x', $item[$i]);
            $mitemc = $data[0];

            $str="
                SELECT COUNT(rm_code) total_rm FROM mst_item_comp2 WHERE fg_code = '$mitemc'
            ";
            $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $rw = $qry->getRowArray();
            $orig_rm_count = $rw['total_rm'];

            $str="
            SELECT
                b.`rm_code`,
                (SELECT po_qty FROM rm_inv_rcv WHERE mat_code = b.`rm_code`) AS rm_inv,
                (b.`item_qty` * a.`item_qty`) item_qty,
                a.`item_qty` as test_item_qty

            FROM
                trx_rmap_req_dt a
            JOIN
                mst_item_comp2 b
            ON 
                a.`item_code` = b.`fg_code`
            WHERE
                a.`rmap_trxno` = '$rmapno' AND b.`fg_code` = '$mitemc'
            GROUP BY 
                b.`rm_code`
            ";

            $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $rw = $qry->getResultArray();

            //CONDITIONAL EVERY CORRESPONDING RM RESULT
            foreach ($rw as $data) {
                $rm_code = $data['rm_code'];
                $rm_inv = $data['rm_inv'];
                $item_qty = $data['item_qty'];
                $test_item_qty = $data['test_item_qty'];

                if ($item_qty <= $rm_inv) {

                    $produced_qty = $item_qty;
                    $remaining_qty = 0;

                    $str="
                        UPDATE `rm_inv_rcv` SET `po_qty` = `po_qty` - '$produced_qty' WHERE `mat_code` = '$rm_code'
                    ";
                    $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    $str="
                        INSERT trx_rm_out_produce(`rmap_trxno`,`rm_code`,`total_qty`,`fg_code`) VALUES ('$rmapno','$rm_code','$produced_qty','$mitemc');
                    ";
                    $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    $count++;

                    //VALIDATE IF THERE IS A RM THAT IS NOT AVAILABLE IN INVENTORY, DECLARED ITEM QTY MUST BE MINUS 1
                    if ($count != $orig_rm_count) {
                        if ($test_item_qty == 1) {

                            $total_item = $test_item_qty;
                        }
                        else{
                            $total_item = $test_item_qty -1;
                        }

                        $str="
                        UPDATE trx_rmap_req_dt SET `item_qty` = '$total_item', `rmng_qty` = '$total_item', `produce_rmng` = '$total_item' WHERE `item_code` = '$mitemc' AND `rmap_trxno` = '$rmapno'
                        ";
                        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
                    }
                    else{
                        $total_item = $test_item_qty;

                        $str="
                        UPDATE trx_rmap_req_dt SET `item_qty` = '$total_item', `rmng_qty` = '$total_item', `produce_rmng` = '$total_item' WHERE `item_code` = '$mitemc' AND `rmap_trxno` = '$rmapno'
                        ";
                        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    }
                    
                }else{

                    $produced_qty = $rm_inv;

                    $str="
                        UPDATE `rm_inv_rcv` SET `po_qty` = `po_qty` - '$produced_qty' WHERE `mat_code` = '$rm_code'
                    ";
                    $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    $str="
                        INSERT trx_rm_out_lacking(`rmap_trxno`,`rm_code`,`total_qty`,`fg_code`) VALUES ('$rmapno','$rm_code','$item_qty','$mitemc');
                    ";
                    $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                }

            }  
            
        }

        $str="
            UPDATE trx_rmap_req_hd SET `is_processed` = '1' WHERE `rmap_trxno` = '$rmapno'
        ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        

        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Processed Successfully! ['$total_item'] . ['$count'] . ['$orig_rm_count']</div>
        <script type=\"text/javascript\"> 
            function __fg_refresh_data() { 
                try { 
                    
                    jQuery('#btn_process').prop('disabled',true);
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
        
    } //end rm_req_save

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

} 