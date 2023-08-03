<?php
/*
 * Module      :    MyFGRequestModel.php
 * Type 	   :    Model
 * Program Desc:    MyFGRequestModel
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/
namespace App\Models;
use CodeIgniter\Model;

class MyFGRequestModel extends Model
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
    
    public function fgpack_req_entry_save() {

        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");
        $fgreq_trxno = $this->request->getVar('fgreq_trxno');
        $tpa_trxno = $this->request->getVar('tpa_trxno');
        $txt_process_date = $this->request->getVar('txt_process_date');
        $txt_req_date = $this->request->getVar('txt_req_date');
        $txt_pack = $this->request->getVar('txt_pack');
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');

        if (empty($txt_pack)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> PACK QUANTITY CANNOT BE NULL!!! </div>";
            die();
        }

        if (empty($adata1)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> No Item Data! </div>";
            die();
        }
        
        $cseqn =  $this->mydataz->get_ctr_new_dr('FGPR','',$this->db_erp,'CTRL_GWFGPA');
        $strHd = "
        UPDATE trx_tpa_hd
        SET `is_processed` = '1'
        WHERE `tpa_trxno` = '$tpa_trxno'

        ";

        $q = $this->mylibzdb->myoa_sql_exec($strHd,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        if(count($adata1) > 0) { 
            $ame = array();
            $adatar1 = array();

            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);
                $mitemc = trim($medata[0]);
                $qty_pack = $medata[1];
                $act_qty = $medata[2];
                $rmng_pack = $medata[3];
                $demand_qty = $medata[4];
                $amatnr = array();

                if ($qty_pack > $demand_qty) {
                    echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Quantity per pack must not be greater than demand! </div>";
                    die();
                }
                if ($act_qty > $demand_qty) {
                    echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Total pack cannot be greater than demand! </div>";
                    die();
                }

                if(!empty($mitemc)) { 
                    $str = "select aa.recid,aa.ART_CODE from {$this->db_erp}.`mst_article` aa where ART_CODE = '$mitemc' ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $rw = $q->getRowArray(); 
                    array_push($ame,$mitemc); 
                    array_push($adatar1,$medata);

                }
            }  
           
            $strP = "
            INSERT INTO trx_fgpack_req_hd (`fgreq_trxno`,`tpa_trxno`,`req_date`,`process_date`,`pack_qty`,`rmng_pack`) VALUES ('{$cseqn}', '{$tpa_trxno}', '{$txt_req_date}','{$txt_process_date}','{$txt_pack}','$txt_pack}')
            ";
            $qP = $this->mylibzdb->myoa_sql_exec($strP,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

            if(count($adatar1) > 0) { 
                for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                    $xdata = $adatar1[$xx];
                    $mitemc = $xdata[0];
                    $qty_pack = $xdata[1];
                    $act_qty = $xdata[2];
                    $rmng_pack = $xdata[3];
                    $demand_qty = $xdata[4];
                    $inv_qty = $xdata[5];
                    $notZero = 0;

                    if ($rmng_pack != '0') {
                        if ($act_qty > $rmng_pack) {
                            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Stocks cannot meet demand. </div>";
                            die();
                        } 

                    }

                    $strInv = "
                    SELECT * FROM trx_tpa_dt WHERE (`tpa_trxno` = '{$tpa_trxno}' and `mat_code` = '{$mitemc}') and `rcv_tag` = '0'
                    ";
                    $qInv = $this->mylibzdb->myoa_sql_exec($strInv,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                    $rw = $qInv->getResultArray();

                    if ($qInv->getNumRows() > 0) {
                        $str = "
                        UPDATE trx_tpa_dt
                        SET `rcv_tag` = '1', `rmng_qty` = `demand_qty` - '{$act_qty}', `rmng_rcv` = `rmng_rcv` + '{$act_qty}', `proceeded_qty` = `proceeded_qty` + '$act_qty'
                        WHERE `tpa_trxno` = '$tpa_trxno' AND `mat_code` = '$mitemc'
    
                        ";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                        if (!empty($qty_pack)) {

                            $strI = "
                            INSERT INTO trx_fgpack_req_dt (`fgreq_trxno`,`tpa_trxno`,`mat_code`,`req_pack`,`qty_perpack`,`total_pack`,`total_processed`) VALUES('{$cseqn}', '{$tpa_trxno}','{$mitemc}','{$txt_pack}','{$qty_pack}','{$act_qty}','{$act_qty}')
                            ";
                            $qI = $this->mylibzdb->myoa_sql_exec($strI,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);     
                        }

                    }else{

                        $strR = "
                        UPDATE trx_tpa_dt
                        SET `rmng_qty` = `rmng_qty` - '{$act_qty}',`rmng_rcv` = `rmng_rcv` + '{$act_qty}', `proceeded_qty` = `proceeded_qty` + '$act_qty'
                        WHERE `tpa_trxno` = '$tpa_trxno' AND mat_code = '$mitemc'
                        ";
                        $qq = $this->mylibzdb->myoa_sql_exec($strR,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

                        if (!empty($qty_pack)) {
                            $strI = "
                            INSERT INTO trx_fgpack_req_dt (`fgreq_trxno`,`tpa_trxno`,`mat_code`,`req_pack`,`qty_perpack`,`total_pack`,`total_processed`) VALUES('{$cseqn}', '{$tpa_trxno}','{$mitemc}','{$txt_pack}','{$qty_pack}','{$act_qty}','{$act_qty}')
                            ";
                            $qI = $this->mylibzdb->myoa_sql_exec($strI,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 
                        }


                        
                    }


 
                }
                
                } 
                
            } 

            echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! Packing Series No:{$cseqn} </div>
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

    } //end fgpack_req_entry_save

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
        a.`recid`,
        a.`tpa_trxno`,
        a.`branch_name`,
        a.`req_date`,
        a.`total_qty`,
        SUM(b.`proceeded_qty`) AS proceeded_qty

        FROM
        `trx_tpa_hd` a
        JOIN
        `trx_tpa_dt` b
        ON 
        a.`tpa_trxno` = b.`tpa_trxno`
        {$str_optn}
        GROUP BY a.`tpa_trxno`
        ORDER BY recid DESC
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
                    (a.`fgpack_trxno` LIKE '%{$msearchrec}%' ')
            ";
        }

        $strqry = "
        SELECT
        a.`fgpack_trxno`, b.`item_code`, c.`ART_DESC`, c.`ART_SKU`,b.`item_qty`
        FROM 
        trx_fgpack_req_hd a
        JOIN
        trx_fgpack_req_dt b
        ON
        a.`fgpack_trxno` = b.`fgpack_trxno`
        JOIN
        mst_article c
        ON
        b.`item_code` = c.`ART_CODE`
        WHERE 
        b.`fgpack_trxno` = '{$rmapno}'";
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