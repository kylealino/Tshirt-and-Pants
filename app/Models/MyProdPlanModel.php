<?php
/*
 * Module      :    MyProdPlanModel.php
 * Type 	   :    Model
 * Program Desc:    MyProdPlanModel
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/
namespace App\Models;
use CodeIgniter\Model;

class MyProdPlanModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(0);
        $this->db_erpbrnch = $this->mydbname->medb(2);
        $this->mylibzdb = model('App\Models\MyLibzDBModel');
        $this->mylibzsys = model('App\Models\MyLibzSysModel');
        $this->mymelibzsys = model('App\Models\Mymelibsys_model');
        $this->mydataz = model('App\Models\MyDatumModel');
        $this->dbx = $this->mylibzdb->dbx;
        $this->request = \Config\Services::request();

    }

    public function prod_plan_upld(){ 

        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $branch_name = $this->request->getVar('branch_name');
        $firstWord = explode(" ", $branch_name);
        $branchFirstName = $firstWord[0];
        $date_range = $this->request->getVar('date_range');
        $month_cap = $this->request->getVar('month_cap');
        $str_date_range = "";
        $tblbranch = "";

        if (empty($branch_name)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please select a branch! </div>";
            die();
        }

        $type = "";
        $insertSubTag = 0;
        $nrecs_pb     = 0;
        $invalidUnit  = 0 ;
        $tableName = '';
        
        $csv_file = "";
        $csv_ofile = "";
        $_csv_path = './whrcvngcd_upld/';
        $_csv_upath = './whrcvngcd_upld/';
        $_csv_pubpath = './uploads/whrcvngcd_upld/';
    
         $this->validate([
                'userfile' => 'uploaded[userfile]|max_size[userfile,100]'
                               . '|mime_in[userfile,text/x-comma-separated-values, text/comma-separated-values, application/octet-stream, application/vnd.ms-excel,application/x-csv,text/x-csv,text/csv,application/csv,application/excel,application/vnd.msexcel,text/plain]'
                               . '|ext_in[userfile,csv,xls,text,txt,xlsx]|max_dims[userfile,1024,768]',
            ]);
    
            if(!is_dir($_csv_pubpath)) mkdir($_csv_pubpath, '0755', true);
            $file = $this->request->getFile('rcv_file');

            $file->move($_csv_pubpath,$file->getName());
    
            if(! $file->hasMoved())
            {
                echo "Error File Uploading/Process";
                die();
            }
            
            $csv_file  = $file->getName();
            $csv_ofile = $file->getName();
            $tbltemp   = $this->db_erp . ".`whrcvngcd_upld_temp_" . $this->mylibzsys->random_string(15) . "`";
    
            $str = "drop table if exists {$tbltemp}";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $cfile = $_csv_pubpath . $csv_file;
            //create temp table 
            $str = "
            CREATE table {$tbltemp} ( 
            `recid` int(25) NOT NULL AUTO_INCREMENT,
            ART_CODE varchar(35) default '',
            INTRANSIT varchar(15) default '',
            FOR_PCKING varchar(15) default '',
            PRIMARY KEY (`recid`)
            )
    
            ";
    
    
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
             //create temp table end
        
    
            $str = "
            LOAD DATA LOCAL INFILE '$cfile' INTO TABLE {$tbltemp} 
            FIELDS TERMINATED BY '\t' 
              LINES TERMINATED BY '\n' 
             
            (
            ART_CODE,INTRANSIT,FOR_PCKING
            ) 
             ";         
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
            $str = "SELECT count(*) __nrecs from {$tbltemp}";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() == 0){ 
                $str = "
                LOAD DATA LOCAL INFILE '$cfile' INTO TABLE {$tbltemp} 
                FIELDS TERMINATED BY '\t' 
                  LINES TERMINATED BY '\r\n' 
                 
                (
                ART_CODE,INTRANSIT,FOR_PCKING
                ) 
                 ";         
                $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                
                $str = "SELECT count(*) __nrecs from {$tbltemp}";
                $qq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                if($qq->getNumRows() == 0) { 
                    $str = "
                    LOAD DATA LOCAL INFILE '$cfile' INTO TABLE {$tbltemp} 
                    FIELDS TERMINATED BY '\t' 
                      LINES TERMINATED BY '\r' 
                     
                    (
                    ART_CODE,INTRANSIT,FOR_PCKING
                    ) 
                     "; 
                    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                }
                $qq->freeResult();
                
                
            }
            $q->freeResult();
            
            
            $str = "SELECT count(*) __nrecs from {$tbltemp}";
    
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $rw = $q->getRowArray();
            $nrecs = $rw['__nrecs'];
            $q->freeResult();
    
      
            $str = "UPDATE {$tbltemp} SET 
            ART_CODE  = TRIM(ART_CODE),
            INTRANSIT  = TRIM(INTRANSIT),
            FOR_PCKING  = TRIM(FOR_PCKING)

            ";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
            $str = "insert into temp_upld (
                ART_CODE,
                INTRANSIT,
                FOR_PCKING
                ) SELECT 
                ART_CODE,
                INTRANSIT,
                FOR_PCKING
                FROM {$tbltemp} 
                
                ";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
    
            $sep = '"\'"';
            $str = "SELECT GROUP_CONCAT({$sep},`ART_CODE`,{$sep}) ART_CODE from  {$tbltemp} ";
            $itemq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $item = $itemq->getRowArray();
            $item_code_list = $item['ART_CODE'];


            $str="SELECT `BRNCH_MBCODE`,`BRNCH_MBCOLDF`,SUBSTRING_INDEX(`BRNCH_NAME`, ' ', 1) AS first_word FROM mst_companyBranch WHERE BRNCH_NAME = '$branch_name'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $rw = $q->getRowArray();
            $mb_code = $rw['BRNCH_MBCODE'];
            $BRNCH_MBCOLDF = $rw['BRNCH_MBCOLDF'];
            $branchFirstName = $rw['first_word'];
            $tblstorebal = "`trx_{$mb_code}_myivty_lb_dtl`";
            $tblbrnchsalesout = "trx_{$mb_code}_salesout";
    
            if (!empty($BRNCH_MBCOLDF)) {
    
                $str="
                    SELECT `BRNCH_MBCODE` FROM mst_companyBranch WHERE BRNCH_CODE = '$BRNCH_MBCOLDF'
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                $rw = $q->getRowArray();
                $BRNCH_MBCODE = $rw['BRNCH_MBCODE'];
                $tblsalesoutold = "`trx_{$BRNCH_MBCODE}_salesout`";
    
                $tblsalesout = "`trx_{$BRNCH_MBCODE}_salesout`";
                $tblstorebal = "`trx_{$BRNCH_MBCODE}_myivty_lb_dtl`";
                $tbltempsalesout = "`trx_{$branchFirstName}_salesout`";
    
                $str="
                    DROP TABLE IF EXISTS {$tbltempsalesout};
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
                $str="
                    CREATE TABLE IF NOT EXISTS {$tbltempsalesout}
                    AS
                    SELECT *
                    FROM {$tblsalesout}
                    WHERE 1 = 0;
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                

                $str="
                    INSERT INTO {$tbltempsalesout}
                    SELECT * FROM {$tblsalesout} WHERE `SO_ITEMCODE` IN (SELECT `ART_CODE` FROM {$tbltemp})
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                $str="
                    INSERT INTO {$tbltempsalesout}
                    SELECT * FROM {$tblbrnchsalesout} WHERE `SO_ITEMCODE` IN (SELECT `ART_CODE` FROM {$tbltemp})
                ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                

                if ($date_range == '2022') {
                    $str_date_range = " (SELECT SUM(IF(`SO_QTY` IS NULL, 0, `SO_QTY`)) FROM $tbltempsalesout WHERE (SO_DATE >= '$date_range-01-01 00:00:00' AND SO_DATE <= '$date_range-12-31 00:00:00') AND `SO_ITEMCODE` = a.`ART_CODE`) ";
                }elseif ($date_range == '2021') {
                    $str_date_range = " (SELECT SUM(IF(`SO_QTY` IS NULL, 0, `SO_QTY`)) FROM $tbltempsalesout WHERE (SO_DATE >= '$date_range-01-01 00:00:00' AND SO_DATE <= '$date_range-12-31 00:00:00') AND `SO_ITEMCODE` = a.`ART_CODE`) ";
                }elseif ($date_range == '2020') {
                    $str_date_range = " (SELECT SUM(IF(`SO_QTY` IS NULL, 0, `SO_QTY`)) FROM $tbltempsalesout WHERE (SO_DATE >= '$date_range-01-01 00:00:00' AND SO_DATE <= '$date_range-12-31 00:00:00') AND `SO_ITEMCODE` = a.`ART_CODE`) ";
                }
    
            }else{
                if ($date_range == '2022') {
                    $str_date_range = " (SELECT SUM(IF(`SO_QTY` IS NULL, 0, `SO_QTY`)) FROM $tblbrnchsalesout WHERE (SO_DATE >= '$date_range-01-01 00:00:00' AND SO_DATE <= '$date_range-12-31 00:00:00') AND `SO_ITEMCODE` = a.`ART_CODE`) ";
                }elseif ($date_range == '2021') {
                    $str_date_range = " (SELECT SUM(IF(`SO_QTY` IS NULL, 0, `SO_QTY`)) FROM $tblbrnchsalesout WHERE (SO_DATE >= '$date_range-01-01 00:00:00' AND SO_DATE <= '$date_range-12-31 00:00:00') AND `SO_ITEMCODE` = a.`ART_CODE`) ";
                }elseif ($date_range == '2020') {
                    $str_date_range = " (SELECT SUM(IF(`SO_QTY` IS NULL, 0, `SO_QTY`)) FROM $tblbrnchsalesout WHERE (SO_DATE >= '$date_range-01-01 00:00:00' AND SO_DATE <= '$date_range-12-31 00:00:00') AND `SO_ITEMCODE` = a.`ART_CODE`) ";
                }
            }


            
            //get items
            $str_itm = "SELECT
            a.`ART_CODE`,
            IF(
                (SELECT SUM(
                        (CASE
                            WHEN (IF(`MTYPE` = 'GEN-IVTYC', `MQTY`, 0) != 0 ) AND IF(`MTYPE` = 'BEG-BAL', `MQTY`, 0) > 0 THEN (IF(`MTYPE` = 'GEN-IVTYC', `MQTY`, 0) - IF(`MTYPE` = 'BEG-BAL', `MQTY`, 0))
                            WHEN (IF(`MTYPE` = 'GEN-IVTYC', `MQTY`, 0) != 0 ) AND IF(`MTYPE` = 'BEG-BAL', `MQTY`, 0) <= 0 THEN (IF(`MTYPE` = 'GEN-IVTYC', `MQTY`, 0) + IF(`MTYPE` = 'BEG-BAL', `MQTY`, 0))
                            ELSE IF(`MTYPE` = 'BEG-BAL', `MQTY`, 0)
                        END) +
                        IF(`MTYPE` = 'RCV', `MQTY`, 0) +
                        IF(`MTYPE` = 'CYC-ADJ', `MQTY`, 0) +
                        IF(`MTYPE` = 'RCV', (0 - (`MQTY` - `MQTY_CORRECTED`)), 0) +
                        IF(`MTYPE` = 'RCV-S', `MQTY`, 0) +
                        IF(`MTYPE` = 'RCV-M', `MQTY`, 0) +
                        IF(`MTYPE` = 'RCV-C', `MQTY`, 0) +
                        IF(`MTYPE` = 'RCV-R', `MQTY`, 0) +
                        IF(`MTYPE` = 'SALES', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-B1T1', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-DSP', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-BRG', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-GVA', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-TO', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-TOB', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-RTML', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-SU', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-OTHERS', `MQTY`, 0)
                    )
                FROM {$this->db_erpbrnch}.{$tblstorebal} WHERE `ITEMC` = a.`ART_CODE`
            ) < 0, 0, (
                SELECT SUM(
                        (CASE
                            WHEN (IF(`MTYPE` = 'GEN-IVTYC', `MQTY`, 0) != 0 ) AND IF(`MTYPE` = 'BEG-BAL', `MQTY`, 0) > 0 THEN (IF(`MTYPE` = 'GEN-IVTYC', `MQTY`, 0) - IF(`MTYPE` = 'BEG-BAL', `MQTY`, 0))
                            WHEN (IF(`MTYPE` = 'GEN-IVTYC', `MQTY`, 0) != 0 ) AND IF(`MTYPE` = 'BEG-BAL', `MQTY`, 0) <= 0 THEN (IF(`MTYPE` = 'GEN-IVTYC', `MQTY`, 0) + IF(`MTYPE` = 'BEG-BAL', `MQTY`, 0))
                            ELSE IF(`MTYPE` = 'BEG-BAL', `MQTY`, 0)
                        END) +
                        IF(`MTYPE` = 'RCV', `MQTY`, 0) +
                        IF(`MTYPE` = 'CYC-ADJ', `MQTY`, 0) +
                        IF(`MTYPE` = 'RCV', (0 - (`MQTY` - `MQTY_CORRECTED`)), 0) +
                        IF(`MTYPE` = 'RCV-S', `MQTY`, 0) +
                        IF(`MTYPE` = 'RCV-M', `MQTY`, 0) +
                        IF(`MTYPE` = 'RCV-C', `MQTY`, 0) +
                        IF(`MTYPE` = 'RCV-R', `MQTY`, 0) +
                        IF(`MTYPE` = 'SALES', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-B1T1', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-DSP', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-BRG', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-GVA', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-TO', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-TOB', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-RTML', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-SU', `MQTY`, 0) +
                        IF(`MTYPE` = 'PO-OTHERS', `MQTY`, 0)
                    )
                FROM {$this->db_erpbrnch}.{$tblstorebal} WHERE `ITEMC` = a.`ART_CODE`
            )) AS store_balance,
            IFNULL({$str_date_range}, 0) AS sales,
            (({$str_date_range}/12)*(13/12)) AS cap_target,
            a.`INTRANSIT`,
            a.`FOR_PCKING`,
            b.`ART_DESC`,
            b.`ART_UPRICE`
        FROM
            {$tbltemp} a
        JOIN 
            mst_article b
        ON
            REPLACE(REPLACE(REPLACE(a.`ART_CODE`, ' ', ''), '\t', ''), '\n', '') = b.`ART_CODE`;
        
                             ";

            $q3 = $this->mylibzdb->myoa_sql_exec($str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
            if($q3->getNumRows() > 0 )
            {
    
                $data['result'] = $q3->getResultArray();
                $data['count'] = count($q3->getResultArray());
    
            }
            else
            {
    
                $data['result'] = '';
                 $data['count']  = 0;
    
            }
            $data['response'] = true;
            return $data;
     
    } //end prod_plan_upld

    public function prod_plan_entry_delete(){
        $prodtrxno = $this->request->getVar('prodtrxno');

        $str="
            DELETE FROM `prod_plan_hd` WHERE `prod_plan_trxno` = '$prodtrxno'
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $str="
            DELETE FROM `prod_plan_dt` WHERE `prod_plan_trxno` = '$prodtrxno'
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Data Deleted Successfully!!! </div>
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
    }

    public function prod_plan_entry_save() {


        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");
        $prod_plan_trxno = $this->request->getVar('prod_plan_trxno');
        $branch_name = $this->request->getVar('branch_name');
        $opt_df = $this->request->getVar('opt_df');
        $txt_request_date = $this->request->getVar('txt_request_date');
        $txt_qty_serve = $this->request->getVar('txt_qty_serve');
        $txt_amount_serve = $this->request->getVar('txt_amount_serve');
        $adata1 = $this->request->getVar('adata1');

        if (empty($branch_name)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please select a branch! </div>";
            die();
        }
        
        $cseqn =  $this->mydataz->get_ctr_new_dr('PRPL','',$this->db_erp,'CTRL_GWFGPA');
        $strHd = "
        insert into `prod_plan_hd` (
            `prod_plan_trxno`,
            `brnch_name`,
            `type`,
            `entry_date`,
            `cuser`,
            `qty_serve`,
            `amount_serve`
          )
          values
            (
              '$cseqn',
              '$branch_name',
              '$opt_df',
              now(),
              '$cuser',
              '$txt_qty_serve',
              '$txt_amount_serve'
            );
        ";

        $q = $this->mylibzdb->myoa_sql_exec($strHd,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
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
                    $mstdrd = $xdata[1];
                    $msbal = $xdata[2];
                    $msales = $xdata[3];
                    $mtrnst = $xdata[4];
                    $mfpckng = $xdata[5];
                    $mlck = $xdata[6];
                    $mqsrv = $xdata[7];
                    $mamt = $xdata[8];



                    $str="
                    INSERT INTO `prod_plan_dt` (
                        `prod_plan_trxno`,
                        `mat_code`,
                        `stdrd_cap`,
                        `store_bal`,
                        `sales`,
                        `intransit`,
                        `for_packing`,
                        `lacking`,
                        `qty_serve`,
                        `amount_serve`
                      )
                      VALUES
                        (
                          '$cseqn',
                          '$mitemc',
                          '$mstdrd',
                          '$msbal',
                          '$msales',
                          '$mtrnst',
                          '$mfpckng',
                          '$mlck',
                          '$mqsrv',
                          '$mamt'
                        );
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                }
                
                } 
                
            } 

            echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! Series No:{$cseqn} </div>
            <script type=\"text/javascript\"> 
                function __fg_refresh_data() { 
                    try { 
                        $('#prod_plan_trxno').val('{$cseqn}');
                        
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

    } //end prod_plan_entry_save

    public function prod_plan_rec_view($npages = 1,$npagelimit = 30,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');

        $strqry = "
        SELECT
          a.`prod_plan_trxno`,
          a.`brnch_name`,
          a.`entry_date`,
          a.`qty_serve`,
          a.`amount_serve`

        FROM
        `prod_plan_hd` a
        ORDER BY a.`entry_date` DESC
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
    } //end prod_plan_rec_view

    public function prod_plan_view_itm_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $pptrxno   = $this->request->getVar('pptrxno');

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
            a.`mat_code`,
            b.`ART_DESC`,
            a.`stdrd_cap`,
            b.`ART_UPRICE`,
            a.`store_bal`,
            a.`rcv_stock`,
            a.`sales`,
            a.`intransit`,
            a.`for_packing`,
            a.`lacking`,
            a.`qty_serve`,
            a.`amount_serve`
        FROM
            `prod_plan_dt` a
        JOIN
        mst_article b
        ON
        a.`mat_code` = b.`ART_CODE`
        WHERE 
        a.`prod_plan_trxno` = '{$pptrxno}'";
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['pptrxno'] = $pptrxno;
         
        } else { 
         $data = array();
         $data['pptrxno'] = $pptrxno;
         $data['rlist'] = '';
         $data['txtsearchedrec_rl'] = $msearchrec;
        }
        return $data;
    } //end prod_plan_view_itm_recs
}