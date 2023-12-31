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

    public function rm_req_process_view(){
        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");
        $rmap_trxno = $this->request->getVar('rmap_trxno');
        $mtkn_mntr = $this->request->getVar('mtkn_mntr');
        $txt_plant = $this->request->getVar('txt_plant');
        $txt_subcon = $this->request->getVar('txt_subcon');
        $txt_request_date = $this->request->getVar('txt_request_date');
        $txt_total_qty = $this->request->getVar('txt_total_qty');
        $txt_total_amount = $this->request->getVar('txt_total_amount');
        $txt_remarks = $this->request->getVar('txt_remarks');
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');
        $tbltemp   = $this->db_erp . ".`rmap_temp`";

        if (empty($adata1)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> No Items Detected! </div>";
            die();
        }

        $str="DROP TABLE IF EXISTS {$tbltemp}";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $str = "
        CREATE TABLE IF NOT EXISTS {$tbltemp} ( 
        `recid` int(25) NOT NULL AUTO_INCREMENT,
        FG_CODE varchar(35) default '',
        FG_QTY varchar(15) default '',
        PRIMARY KEY (`recid`)
        )

        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


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

                array_push($adatar1,$medata);

            }  //end for 

            if(count($adatar1) > 0) { 
  
                for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                    $xdata = $adatar1[$xx];
                    $mat_code = $xdata[0];
                    $qty = $xdata[2];

                    if (empty($mat_code) || empty($qty) || $qty == '0') {
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please check for invalid items or quantity! </div>";
                        die();
                    }
                    
                    $strqry = "
                        INSERT INTO {$tbltemp}(FG_CODE,FG_QTY) VALUES('$mat_code','$qty');
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


                }  
                        
            } 
        } 

        $str="
        SELECT
        b.`rm_code` RM_CODE,
        SUM(a.`FG_QTY` * b.`item_qty`) RM_QTY,
        (SELECT po_qty FROM rm_inv_rcv WHERE mat_code = b.`rm_code`) AS RM_INV,
        c.`ART_DESC` RM_DESC
        FROM
        rmap_temp a
        JOIN
        mst_item_comp2 b
        ON
        a.`FG_CODE` = b.`fg_code`
        JOIN
        mst_article c
        ON
        b.`rm_code` = c.`ART_CODE`
        GROUP BY 
        b.`rm_code`
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($q->getNumRows() > 0) { 
         $data['rlistP'] = $q->getResultArray();
         
        } else { 
         $data = array();
         $data['rlistP'] = '';
        }


        $str="
            SELECT
                `FG_CODE` FG_CODE,
                `FG_QTY` FG_QTY
            FROM
                rmap_temp
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($q->getNumRows() > 0) { 
            $data2['rlistP2'] = $q->getResultArray();
            
           } else { 
            $data2 = array();
            $data2['rlistP2'] = '';
           }

        return array('data'=> $data,'data2'=>$data2);

    }

    public function rm_req_process_save(){
    
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');
        $txt_plant = $this->request->getVar('txt_plant');
        $txt_subcon = $this->request->getVar('txt_subcon');
        $txt_remarks = $this->request->getVar('txt_remarks');
        $txt_request_date = $this->request->getVar('txt_request_date');
        $txt_total_qty = $this->request->getVar('txt_total_qty');
        $cseqn =  $this->mydataz->get_ctr_new_dr('RMAP','',$this->db_erp,'CTRL_GWFGPA');

        if (empty($txt_plant)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Plant is required!</div>";
            die();
        }
        if (empty($txt_subcon)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Subcon is required!</div>";
            die();
        }
        $tbltemp   = $this->db_erp . ".`rmap_temp_save`";

        $str="DROP TABLE {$tbltemp}";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $str = "
        CREATE TABLE IF NOT EXISTS {$tbltemp} ( 
        `recid` int(25) NOT NULL AUTO_INCREMENT,
        RM_CODE varchar(35) default '',
        RM_QTY decimal(15,2),
        PRIMARY KEY (`recid`)
        )
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if(count($adata1) > 0) { 

            $adatar1 = array();

            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);

                array_push($adatar1,$medata);

            }

            if(count($adatar1) > 0) { 
  
                for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                    $xdata = $adatar1[$xx];
                    $mitemc = $xdata[0];
                    $mqty = $xdata[2];

                    $str = "
                        INSERT INTO {$tbltemp}(`RM_CODE`,`RM_QTY`) VALUES('$mitemc','$mqty');
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                }  
                        
            } 
        }
        
        $str="
            SELECT 
            a.`RM_CODE`,
            a.`RM_QTY`,
            (SELECT po_qty FROM rm_inv_rcv WHERE mat_code = a.`RM_CODE`) AS RM_INV
            FROM 
            {$tbltemp} a
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $rw = $q->getResultArray();
        foreach ($rw as $data) {
            $RM_CODE = $data['RM_CODE'];
            $RM_QTY = $data['RM_QTY'];
            $RM_INV = $data['RM_INV'];
            
            if ($RM_INV < $RM_QTY) {
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Stocks Unvailable! [$RM_CODE] [$RM_QTY] [$RM_INV] </div>";
                die();
            }
    
        }

        //SAVING OF RMAP HD
        $str="
            INSERT INTO `trx_rmap_hd` (
                `rmap_trxno`,
                `plant`,
                `subcon`,
                `remarks`,
                `request_date`,
                `total_fg_qty`
            )
            VALUES
                (
                '$cseqn',
                '$txt_plant',
                '$txt_subcon',
                '$txt_remarks',
                '$txt_request_date',
                '$txt_total_qty'
                )
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if(count($adata2) > 0) { 

            $adatar2 = array();

            for($aa = 0; $aa < count($adata2); $aa++) { 
                $medata = explode("x|x",$adata2[$aa]);

                array_push($adatar2,$medata);

            }

            if(count($adatar2) > 0) { 
  
                for($xx = 0; $xx < count($adatar2); $xx++) { 
                    
                    $xdata = $adatar2[$xx];
                    $mitemc = $xdata[0];
                    $mqty = $xdata[1];

                    $str = "
                        INSERT INTO `d_ap2`.`trx_rmap_dt` (
                            `rmap_trxno`,
                            `fg_code`,
                            `fg_qty`,
                            `fg_rmng`
                        )
                        VALUES
                            (
                            '$cseqn',
                            '$mitemc',
                            '$mqty',
                            '$mqty'
                            );
                      
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                }  
                        
            } 
        }

        $str="
            INSERT INTO `trx_rmap_bom`(
                `rmap_trxno`,
                `rm_code`,
                `rm_qty`
              )SELECT '$cseqn',`RM_CODE`,`RM_QTY` FROM {$tbltemp}
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully! </div>
        <script type=\"text/javascript\"> 
            function __fg_refresh_data() { 
                try { 
                    $('#rmap_trxno').val('{$cseqn}');
                    jQuery('#mbtn_mn_Save').prop('disabled',true);
                    jQuery('#mbtn_mn_Process').prop('disabled',true);
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

    public function rm_req_process_update(){
    
        $rmap_trxno = $this->request->getVar('rmap_trxno');
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');
        $txt_plant = $this->request->getVar('txt_plant');
        $txt_subcon = $this->request->getVar('txt_subcon');
        $txt_remarks = $this->request->getVar('txt_remarks');
        $txt_request_date = $this->request->getVar('txt_request_date');
        $txt_total_qty = $this->request->getVar('txt_total_qty');

        if (empty($txt_plant)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Plant is required!</div>";
            die();
        }
        if (empty($txt_subcon)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Subcon is required!</div>";
            die();
        }
        
        $tbltemp   = $this->db_erp . ".`rmap_temp_save`";

        $str="DROP TABLE {$tbltemp}";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $str = "
        CREATE TABLE IF NOT EXISTS {$tbltemp} ( 
        `recid` int(25) NOT NULL AUTO_INCREMENT,
        RM_CODE varchar(35) default '',
        RM_QTY decimal(15,2),
        PRIMARY KEY (`recid`)
        )
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if(count($adata1) > 0) { 

            $adatar1 = array();

            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);

                array_push($adatar1,$medata);

            }

            if(count($adatar1) > 0) { 
  
                for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                    $xdata = $adatar1[$xx];
                    $mitemc = $xdata[0];
                    $mqty = $xdata[2];

                    $str = "
                        INSERT INTO {$tbltemp}(`RM_CODE`,`RM_QTY`) VALUES('$mitemc','$mqty');
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                }  
                        
            } 
        }
        
        $str="
            SELECT 
            a.`RM_CODE`,
            a.`RM_QTY`,
            (SELECT po_qty FROM rm_inv_rcv WHERE mat_code = a.`RM_CODE`) AS RM_INV
            FROM 
            {$tbltemp} a
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $rw = $q->getResultArray();
        foreach ($rw as $data) {
            $RM_CODE = $data['RM_CODE'];
            $RM_QTY = $data['RM_QTY'];
            $RM_INV = $data['RM_INV'];
            
            if ($RM_INV < $RM_QTY) {
                echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Stocks Unvailable! [$RM_CODE] [$RM_QTY] [$RM_INV] </div>";
                die();
            }
    
        }

        //UPDATING OF RMAP HD
        $str="
            UPDATE `trx_rmap_hd` SET `plant` = '$txt_plant', `subcon` = '$txt_subcon', `remarks` = '$txt_remarks', `request_date` = '$txt_request_date',`total_fg_qty` = '$txt_total_qty' WHERE `rmap_trxno` = '$rmap_trxno'
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        //DELETE CURRENT FG RECORDS
        $str="
            DELETE FROM `trx_rmap_dt` WHERE `rmap_trxno` = '$rmap_trxno'
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        //INSERT NEW RECORDS OF FG DT

        if(count($adata2) > 0) { 

            $adatar2 = array();

            for($aa = 0; $aa < count($adata2); $aa++) { 
                $medata = explode("x|x",$adata2[$aa]);

                array_push($adatar2,$medata);

            }

            if(count($adatar2) > 0) { 
  
                for($xx = 0; $xx < count($adatar2); $xx++) { 
                    
                    $xdata = $adatar2[$xx];
                    $mitemc = $xdata[0];
                    $mqty = $xdata[1];

                    $str = "
                        INSERT INTO `d_ap2`.`trx_rmap_dt` (
                            `rmap_trxno`,
                            `fg_code`,
                            `fg_qty`,
                            `fg_rmng`
                        )
                        VALUES
                            (
                            '$rmap_trxno',
                            '$mitemc',
                            '$mqty',
                            '$mqty'
                            );
                      
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                }  
                        
            } 
        }

        //DELETE CURRENT BOM MATERIALS
        $str="
            DELETE FROM `trx_rmap_bom` WHERE `rmap_trxno` = '$rmap_trxno'
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        //INSERT NEW BOM MATERIALS
        $str="
            INSERT INTO `trx_rmap_bom`(
                `rmap_trxno`,
                `rm_code`,
                `rm_qty`
              )SELECT '$rmap_trxno',`RM_CODE`,`RM_QTY` FROM {$tbltemp}
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Updated Successfully! </div>
        <script type=\"text/javascript\"> 
            function __fg_refresh_data() { 
                try { 
                    $('#rmap_trxno').val('{$rmap_trxno}');
                    jQuery('#mbtn_mn_Update').prop('disabled',true);
                    jQuery('#mbtn_mn_Process').prop('disabled',true);
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


        if(empty($active_plnt_id)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please Select Plant!!!.</div>";
            die();
        }
        else{ 
            
        }

        if(empty($adata1)) { 
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> No Item Data!!!.</div>";
            die();
        }

         $cseqn =  $this->mydataz->get_ctr_new_dr('RMAP','',$this->db_erp,'CTRL_GWFGPA');//TRANSACTION NO

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

                array_push($adatar1,$medata);

            }  //end for 

            if(count($adatar1) > 0) { 
  
                    for($xx = 0; $xx < count($adatar1); $xx++) { 
                    
                        $xdata = $adatar1[$xx];
                        $cmat_code = $xdata[0];
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
                    
                    
                }  //end for
                        
            } 
        } //end if 
        
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


    } //end rm_req_entry_save

    public function rm_rec_process_save() {

        $rmapno = $this->request->getVar('rmapno');

        $str2="
            UPDATE `trx_rmap_hd` SET `is_processed` = '1' WHERE `rmap_trxno` = '$rmapno';
        ";
        $q2 = $this->mylibzdb->myoa_sql_exec($str2,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $str3 = "
        UPDATE `rm_inv_rcv` AS target
            INNER JOIN (
            SELECT
                `rm_code`,
                SUM(`rm_qty`) AS total_rm_qty
            FROM
                `trx_rmap_bom`
            WHERE
                `rmap_trxno` = '$rmapno' and `is_out` = '0'
            GROUP BY
                `rm_code`
            ) AS source
            ON target.`mat_code` = source.`rm_code`
            SET target.`po_qty` = target.`po_qty` - source.`total_rm_qty`
        ";
        $q3 = $this->mylibzdb->myoa_sql_exec($str3, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $str4="
            UPDATE `trx_rmap_bom` SET `is_out` = '1' WHERE `rmap_trxno` = '$rmapno';
        ";
        $q4 = $this->mylibzdb->myoa_sql_exec($str4, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Data Processed Successfully! </div>
        <script type=\"text/javascript\"> 
            function __fg_refresh_data() { 
                try { 
                    jQuery('#mbtn_recs_Process').prop('disabled',true);
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
                        `item_tamount`

                    ) values(
                        '$cseqn',
                        '$cmat_code',
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
            a.`subcon`,
            a.`plant` plnt_id,
            a.`remarks`,
            a.`request_date`,
            a.`total_fg_qty` total_qty,
            a.`is_processed`,
            SUM(b.`fg_qty`) fg_qty,
            SUM(b.`fg_release`) fg_release,
            SUM(b.`fg_rmng`) fg_rmng
        FROM
            `trx_rmap_hd` a
        JOIN
            `trx_rmap_dt` b
        ON
            a.`rmap_trxno` = b.`rmap_trxno`
        GROUP BY 
            `rmap_trxno`
        ORDER BY 
            `request_date` DESC
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