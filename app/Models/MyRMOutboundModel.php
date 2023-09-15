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
            a.`rmap_trxno`,
            a.`plnt_id`,
            a.`request_date`,
            SUM(b.`produce_qty`) total_qty,
            a.`is_processed`
        FROM 
            `trx_rmap_req_hd` a
        JOIN
            `trx_rmap_req_dt` b
        ON
            a.`rmap_trxno` = b.`rmap_trxno`
        WHERE 
            a.`is_processed` = '1'
        GROUP BY
            a.`rmap_trxno`
        
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
	        a.`recid`,
            a.`rmap_trxno`,
            a.`fg_code`,
            a.`rm_code`,
            a.`total_qty`,
            COALESCE((SELECT po_qty FROM rm_inv_rcv WHERE mat_code = a.`rm_code`), 0.0000) AS rm_inv
        FROM
            `trx_rm_out_lacking` a
        ORDER BY 
	        a.`recid` DESC
        
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
        $tbltemp_produce   = $this->db_temp . ".`temp_Produce`";
        $tbltemp_lacking   = $this->db_temp . ".`temp_Lacking`";

        if (empty($adata1)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> No Item Data! </div>";
            die();
        }

        $str = "
        CREATE TABLE IF NOT EXISTS {$tbltemp_produce} ( 
            `recid` int(25) NOT NULL AUTO_INCREMENT,
            rmap_trxno varchar(35) default '',
            fg_code varchar(35) default '',
            rm_code varchar(35) default '',
            temp_qty int(25) default 0,
            PRIMARY KEY (`recid`)
        )

        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        $str = "
        CREATE TABLE IF NOT EXISTS {$tbltemp_lacking} ( 
            `recid` int(25) NOT NULL AUTO_INCREMENT,
            rmap_trxno varchar(35) default '',
            fg_code varchar(35) default '',
            rm_code varchar(35) default '',
            temp_qty int(25) default 0,
            PRIMARY KEY (`recid`)
        )

        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        //KUNIN YUNG MGA FG CODE SA SPECIFIC TRANSACTION NO PARA MAHANAP YUNG CORRESPONDING BOM MATERIALS NIYA
        $str="
            SELECT 
                `item_code`,
                `item_qty`
            FROM 
                `trx_rmap_req_dt` 
            WHERE 
            `rmap_trxno` = '$rmapno'
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $rw = $q->getResultArray();
        $count_fg_success = 1;
        foreach ($rw as $data) {
            
            $fg_code = $data['item_code'];
            $fg_qty = $data['item_qty'];
            
            //ETO NAMAN YUNG IKOT NA NAKA DEPENDE KUNG ILAN QTY NG FG
            for ($i=1; $i <= $fg_qty; $i++) { 
                
                //DITO NA KUKUNIN YUNG CORRESPONDING RM PER IKOT NG UNANG FOREACH NG FG CODE
                $str="
                SELECT a.`rm_code`,a.`item_qty` AS rm_qty,(SELECT po_qty FROM rm_inv_rcv WHERE mat_code = a.`rm_code`) AS rm_inv FROM `mst_item_comp2` a WHERE a.`fg_code` = '$fg_code'
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                $rw = $q->getResultArray();
                $total_rm = $q->getNumRows();

                //DELETE KO MUNA YUNG LAMAN NG TEMPORARY TABLE PARA PAG PUMASOK YUNG DELETE DI MADUDUPLICATE
                $str="
                    DELETE FROM {$tbltemp_produce} WHERE `rmap_trxno` = '$rmapno';
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                $str="
                    DELETE FROM {$tbltemp_lacking} WHERE `rmap_trxno` = '$rmapno';
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                //ETO NA YUNG LOOPING NG BAWAT RM MATERIAL
                foreach ($rw as $data) {
                    $rm_code = $data['rm_code'];
                    $rm_inv = $data['rm_inv'];
                    $rm_qty = $data['rm_qty'];


                    if($rm_inv >= $rm_qty){
                        $str="
                            INSERT INTO {$tbltemp_produce} (`rmap_trxno`,`fg_code`,`rm_code`,`temp_qty`) VALUES ('$rmapno','$fg_code','$rm_code','$rm_qty');
                        ";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    }else{
                        $str="
                            INSERT INTO {$tbltemp_lacking} (`rmap_trxno`,`fg_code`,`rm_code`,`temp_qty`) VALUES ('$rmapno','$fg_code','$rm_code','$rm_qty');
                        ";
                        $q1 = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    }

                }

                //ETO NAMAN YUNG TOTAL NA BILANG NG MAAVAILABILITY NG RM PER FG CODE
                //BALI DITO KO BINABANGGA KUNG LAHAT BA NG MATERIALS IS MERON SA INVENTORY BAGO MAGAWA YUNG ISANG FG ITEM
                $str = "
                select count(`rm_code`) total_rm_produce from {$tbltemp_produce} WHERE `rmap_trxno` = '$rmapno'
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                $rw = $q->getRowArray();

                $total_rm_produce = $rw['total_rm_produce'];

                $str = "
                    select count(`rm_code`) total_rm_lacking from {$tbltemp_lacking} WHERE `rmap_trxno` = '$rmapno'
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                $rw = $q->getRowArray();

                $total_rm_lacking = $rw['total_rm_lacking'];

                //DITO NAMAN NIYA IINSERT SA TAMANG TABLE KAPAG KUMPLETO LAHAT NG MATERIALS
                if ($total_rm == $total_rm_produce) {
                    $count_fg_success++;
                    //IINSERT NIYA DITO YUNG MGA KUMPLETONG MATERIALS
                    $str="
                        INSERT INTO trx_rm_out_produce (`rmap_trxno`,`fg_code`,`rm_code`,`total_qty`) SELECT `rmap_trxno`,`fg_code`,`rm_code`,`temp_qty` FROM {$tbltemp_produce} WHERE `rmap_trxno` = '$rmapno';
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    //KUNIN SA PRODUCE TABLE YUNG MGA RM TSAKA QTY NG RM PARA ETO NA GAGAMITIN PAMBAWAS SA RM INV
                    //KUMBAGA YUNG MGA KUMPLETO LANG MAKAKAPAG BAWAS
                    $str="
                        SELECT `rm_code`,`total_qty` FROM trx_rm_out_produce WHERE `rmap_trxno` = '$rmapno'
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $rw = $q->getResultArray();
                    foreach ($rw as $data) {
                        $rm_code = $data['rm_code'];
                        $total_qty = $data['total_qty'];

                        //IBABAWAS NIYA SA INVENTORY IN CASE NG KUMPLETO
                        $str="
                            UPDATE `rm_inv_rcv`
                            SET `po_qty` = CASE
                                WHEN `po_qty` > 0.00000 THEN `po_qty` - '$total_qty'
                                ELSE `po_qty`
                            END
                            WHERE `mat_code` = '$rm_code'
                        ";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    }

                    $str="
                    UPDATE `trx_rmap_req_dt` SET `produce_qty` = '$count_fg_success', `produce_rmng` = '$count_fg_success' WHERE `rmap_trxno` = '$rmapno' AND `item_code` = '$fg_code';
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                }

            }//FG QTY LOOP
            
        }//FG FOREACH LOOP

        $str="
            UPDATE trx_rmap_req_hd SET `is_processed` = '1' WHERE `rmap_trxno` = '$rmapno'
        ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        

        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Processed Successfully! ['$total_rm'], ['$total_rm_produce'], ['$total_rm_lacking']</div>
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