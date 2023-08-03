<?php
/*
 * Module      :    MyFgpOutgoing.php
 * Type 	   :    Model
 * Program Desc:    MyFgpOutgoing
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Files\File;

class MyFgpOutgoing extends Model
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

    public function view_box_content_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $mtkn_dt   = $this->request->getVar('mtkn_dt');
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
        rcv.*,
        hd.`stock_code`,
        art.`ART_CODE`,
        art.`ART_DESC`
        FROM
        {$this->db_erp}.`warehouse_inv_rcv_item` rcv
        JOIN {$this->db_erp}.`warehouse_inv_rcv` hd on rcv.`wshe_inv_id` = hd.`recid` 
        JOIN {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        WHERE hd.`plnt_id` = '{$plntID}' AND  hd.`wshe_id` = '{$whID}'
        AND SHA2(CONCAT(rcv.`wshe_inv_id`,'{$mpw_tkn}'),384) = '{$mtkn_dt}'
        GROUP BY rcv.`mat_rid`";

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
        SELECT * from ({$strqry}) oa ORDER BY `recid` DESC limit {$nstart},{$npagelimit} ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['txtsearchedrec_rl'] = $msearchrec;
        } else { 
         $data = array();
         $data['npage_count'] = 1;
         $data['npage_curr'] = 1;
         $data['rlist'] = '';
         $data['txtsearchedrec_rl'] = $msearchrec;
        }
        return $data;
    }  //end view_box_content_recs

    public function view_fgout_recs(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        // warehouse end


        //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
        $str_vwrecs = "AND a.`muser` = '$cuser'";
    
        $strqry = "
        SELECT
        sd.`recid`,
        dt.`trx`,
        dt.`header`,
        sd.`crpl_code`,
        sd.`plate_no`,
        sd.`brnch`,
        pl.`plnt_code`,
        wr.`wshe_code`,
        sd.`total_qty`,
        sd.`actual_qty`,
        sd.`done`,
        inv.`tpa_trxno`
        FROM
        `warehouse_shipdoc_hd` sd
        JOIN
        `warehouse_shipdoc_dt` dt
        ON
        sd.`crpl_code` = dt.`header`
        LEFT JOIN  
        `mst_plant` pl 
        ON pl.`recid` = sd.`frm_plnt_id`
        LEFT JOIN 
        `mst_wshe` wr
        ON
        sd.`frm_wshe_id` = wr.`recid`
        JOIN
        fgp_inv_rcv inv
        ON
        dt.`trx` = inv.`fgreq_trxno`
        WHERE sd.`crpl_code` NOT LIKE '%CWO%'
        GROUP BY sd.`crpl_code` ORDER BY `recid` DESC";

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
    }  //end view_fgout_recs
    
    public function whrcdout_upld(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $txtprod_type_upld_sub = $this->request->getVar('txtprod_type_upld_sub');
        $txtWarehouse   =  $this->request->getVar('txtWarehouse'); 
        $txtWarehousetkn   =  $this->request->getVar('txtWarehousetkn'); 

        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end

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
            $tbltemp   = $this->db_temp . ".`whoutcd_upld_temp_" . $this->mylibzsys->random_string(15) . "`";

            $str = "drop table if exists {$tbltemp}";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $cfile = $_csv_pubpath . $csv_file;
            //create temp table 
            $str = "
            CREATE table {$tbltemp} ( 
            `recid` int(25) NOT NULL AUTO_INCREMENT,
            wobBarcode varchar(35) default '',
            PRIMARY KEY (`recid`)
            )

            ";


            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

             //create temp table end

            $str = "
            CREATE TABLE IF NOT EXISTS {$this->db_erp}.`warehouse_out_logs`( 
            `recid` int(25) NOT NULL AUTO_INCREMENT,
            `trxNO` varchar(35) default '',
            `wobBarcode` varchar(35) default '',
            `plantID` int(10) default NULL,
            `wsheID` int(10) default NULL ,
            `muser` varchar(35) DEFAULT NULL,
            `encd` datetime DEFAULT NULL,
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
            wobBarcode
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
                wobBarcode
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
                    wobBarcode
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
            wobBarcode  = trim(wobBarcode)
            ";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


            $cseqn = $this->mydataz->get_ctr($this->db_erp,'CTRL_CWO_LOGS');

            $str_log = "INSERT INTO {$this->db_erp}.`warehouse_out_logs`(
            `trxNO`,
            `wobBarcode`,
            `plantID`,
            `wsheID`,
            `muser`,
            `encd`
            ) SELECT
            '$cseqn', 
            REPLACE(REPLACE(REPLACE(`wobBarcode`, ' ', ''), '\t', ''), '\n', ''),
            '$plntID',
            '$whID',
            '$cuser',
            now()
            FROM {$tbltemp} 
            ";

            $this->mylibzdb->myoa_sql_exec($str_log,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            //get barcodes 

            $sep = '"\'"';
            $str = "SELECT GROUP_CONCAT({$sep},`wobBarcode`,{$sep}) brcds from  {$tbltemp} ";
            $brcdq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $brcdr = $brcdq->getRowArray();
            $barcd_upls_list = $brcdr['brcds'];
                
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
                     b.`convf`,
                     b.`total_amount` tamt_scanned,
                     '' price,
                     b.`total_pcs` total_pcs_scanned,                
                     b.`wob_barcde`  barcde,
                    c.`ART_GWEIHGT` `weight`,
                    b.`cbm`

                FROM
                    {$tbltemp} a
                LEFT JOIN
                    {$this->db_erp}.`warehouse_inv_rcv` b
                ON
                    REPLACE(REPLACE(REPLACE(a.`wobBarcode`, ' ', ''), '\t', ''), '\n', '') = b.`wob_barcde`
                LEFT JOIN
                    {$this->db_erp}.`mst_article` c
                ON
                    b.`mat_rid` = c.`recid`
                LEFT JOIN
                    {$this->db_erp}.`mst_plant` d
                ON
                    b.`plnt_id` = d.`recid`
                LEFT JOIN
                    {$this->db_erp}.`mst_wshe` e
                ON
                    b.`wshe_id` = e.`recid`
                LEFT JOIN
                    {$this->db_erp}.`mst_wshe_bin` f
                ON
                    b.`wshe_sbin_id` = f.`recid`
                LEFT JOIN
                    {$this->db_erp}.`wshe_barcdng_hd` g
                ON
                    b.`header` = g.`header`
                WHERE
                    b.`plnt_id` = {$plntID}
                AND
                    b.`wshe_id` = {$whID}
                AND
                    b.`is_out` = 0
                AND
                    REPLACE(REPLACE(REPLACE(a.`wobBarcode`, ' ', ''), '\t', ''), '\n', '') <> ''
                AND
                    b.`wob_barcde` IN ({$barcd_upls_list})
                GROUP BY REPLACE(REPLACE(REPLACE(a.`wobBarcode`, ' ', ''), '\t', ''), '\n', '')
                ORDER BY
                    b.`stock_code`";

            $q3 = $this->mylibzdb->myoa_sql_exec($str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            if($q3->getNumRows() > 0 )
            {
                $data['result'] = $q3->getResultArray();
                $data['count'] = count($q3->getResultArray());
                $data['isdone'] = 0;

            }
            else
            {

                $data['result'] = '';
                $data['count']  = 0;
                $data['isdone'] = 0;

            }
            $data['response'] = true;
            return $data;
     
        
    }  //end whrcdout_upld

    public function fgpout_save(){
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
        $rcvcount     =  $this->request->getVar('rcvcount'); 
        $truck_type   = $this->request->getVar('truck_type');  
        $rems_   = $this->request->getVar('rems_');  

        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        //get company data
        $branchData = $this->mymelibzsys->getCompanyBranch_data_byname($branch_name);
        $branchID = $branchData['recid'];
        //get company data end

        //save to hd
        $_hd_ctrlno = $this->mydataz->get_ctr_new_dr('TAP','',$this->db_erp,'CTRL_CWO'); 
        //"CWO".$this->mydataz->get_ctr($this->db_erp,'CTRL_CWO');
            $str_in = "INSERT INTO  {$this->db_erp}.`warehouse_shipdoc_hd` (
        `crpl_code`,
        `frm_plnt_id`,
        `frm_wshe_id`,
        `plate_no`,
        `driver`,
        `helper_1`,
        `helper_2`,
        `brnch`,
        `brnch_rid`,
        `refno`,
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
            '{$plntID}',
            '{$whID}',
            '{$plate_number}',
            '{$driver}',
            '{$helper_one}',
            '{$helper_two}',
            '{$branch_name}',
            '{$branchID}',
            '{$ref_no}',
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
        `warehouse_shipdoc_dt`(
            `trx`,
            `header`,
            `stock_code`,
            `wob_barcde`
        )
        SELECT 
        a.`fgreq_trxno`,
        '{$_hd_ctrlno}',
        a.`stock_code`,
        a.`wob_barcde`

        FROM 
        `fgp_inv_rcv` a
        WHERE  a.`wob_barcde` IN ($adata1)
        ";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        /***************** AUDIT LOGS *************************/
        $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CD_OUT','DT',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        /***************** AUDIT LOGS *************************/

        $str_up = "
        UPDATE  {$this->db_erp}.`fgp_inv_rcv` rcv, {$this->db_erp}.`warehouse_shipdoc_dt`dt  
        SET  rcv.`is_out` = 1,dt.`is_out` = 1,
        rcv.`SD_NO` = '{$_hd_ctrlno}'
        WHERE  
        rcv.`wob_barcde` = dt.`wob_barcde` 
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

    }  //end fgpout_save

    public function get_entry_data($mktn_hd){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $data  = [];
        $str = "
            SELECT 
              hd.*
            FROM
                {$this->db_erp}.`warehouse_shipdoc_hd` hd
            WHERE
               SHA2(CONCAT(hd.`recid`,'{$mpw_tkn}'),384) = '{$mktn_hd}'";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($q->getNumRows() ==  0){
            echo "No records found";
            die();
        }

        $row =  $q->getRowArray();
        $crpl_code = $row['crpl_code'];
        $data['hdData'] = $row;

        $str_dt = "
            SELECT
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
            b.`convf`,
            b.`total_amount` tamt_scanned,
            f.`wshe_bin_name`,
            '' price,
            b.`total_pcs` total_pcs_scanned,                
            b.`wob_barcde`,
            c.`ART_GWEIHGT` `weight`,
            b.`cbm`,
            g.`tpa_trxno`,
            g.`fgreq_trxno`,
            g.`stock_code`,
            g.`witb_barcde`,
            g.`rcv_date`,
            GROUP_CONCAT(h.`mat_code`) AS mat_code,
            GROUP_CONCAT(h.`qty_perpack`) AS qty_perpack
            FROM
            `warehouse_shipdoc_dt` b
            LEFT JOIN
            `mst_article` c
            ON
            b.`mat_rid` = c.`recid`
            LEFT JOIN
            `mst_plant` d
            ON
            b.`plnt_id` = d.`recid`
            LEFT JOIN
            `mst_wshe` e
            ON
            b.`wshe_id` = e.`recid`
            LEFT JOIN
            `mst_wshe_bin` f
            ON
            b.`wshe_sbin_id` = f.`recid`
            LEFT JOIN
            fgp_inv_rcv g
            ON
            b.`wob_barcde` = g.`wob_barcde`
            LEFT JOIN 
            trx_fgpack_req_dt h
            ON
            g.`fgreq_trxno` = h.`fgreq_trxno`
            WHERE b.`header` = '{$crpl_code}'
            GROUP BY g.`witb_barcde`
            ORDER BY
                b.`stock_code`

        ";

            $qdt = $this->mylibzdb->myoa_sql_exec($str_dt,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        if($qdt->getNumRows() ==  0){
            echo "No records found";
            die();
            }
        $data['dtData'] = $qdt->getResultArray();

        return $data;

    }  //end get_entry_data

    public function fgpout_update(){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $adata1          =  $this->request->getVar('adata1'); 
        $adata2          =  $this->dbx->escapeString($adata1);
        $txtWarehousetkn =  $this->request->getVar('txtWarehousetkn'); 
        $mtkn_trxno      =  $this->request->getVar('mtkn_trxno'); 
        
        
        // warehouse end
        // get header data 
        $crplCode = $this->get_headerData($mtkn_trxno);
        
        // insert to logs
        $str_log = "INSERT INTO {$this->db_erp}.`warehouse_shipdoc_logs` (`crpl_code`,`wob_barcde`,`type`,`muser`,`encd`)
            VALUES('{$crplCode}','{$adata2}','U','{$cuser}',now())";
        $this->mylibzdb->myoa_sql_exec($str_log,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        //update isout in dt 
        $str_up = "UPDATE  {$this->db_erp}.`warehouse_shipdoc_dt` SET  `is_out` = 0 WHERE  `wob_barcde` IN ($adata1) AND `header` = '{$crplCode}' ";
        $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        //update isout in rcv dt
        $str_up = "UPDATE  {$this->db_erp}.`fgp_inv_rcv` SET  `is_out` = 0,`SD_NO` = ''  WHERE  `wob_barcde` IN ($adata1)";
        $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        echo  "Update successfully!";
    }  //end fgpout_update

    public function get_headerData($mktn_hd = ''){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $data  = [];
        $str = "
        SELECT 
          hd.`crpl_code`
        FROM
            {$this->db_erp}.`warehouse_shipdoc_hd` hd
        WHERE
           SHA2(CONCAT(hd.`recid`,'{$mpw_tkn}'),384) = '{$mktn_hd}'";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($q->getNumRows() ==  0){
            echo "No records found";
            die();
        }
        $row = $q->getRowArray();
        return $row['crpl_code'];

    }  //end get_headerData

    public function fgp_done(){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $adata1          =  $this->request->getVar('adata1'); 
        $txtWarehousetkn =  $this->request->getVar('txtWarehousetkn'); 
        $mtkn_trxno      =  $this->request->getVar('mtkn_trxno'); 
        
        // get header data 
        $crplCode = $this->get_headerData($mtkn_trxno);
        
        $str_up = "
        UPDATE  {$this->db_erp}.`warehouse_shipdoc_hd` hd 
        LEFT JOIN(
            SELECT `header`,COUNT(recid) nsum 
            FROM {$this->db_erp}.`warehouse_shipdoc_dt` 
            WHERE header = '{$crplCode}' AND  `is_out` = '1'
            ) act ON act.`header` = hd.`crpl_code`
        SET  
        hd.`done` = 1,
        hd.`done_date` = now(),
        hd.`actual_qty` = act.`nsum` 
        WHERE hd.`crpl_code` = '{$crplCode}' 
        ";

        $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        /***************** AUDIT LOGS *************************/
        $this->mylibzdb->user_logs_activity_module($this->db_erp,'DONE_CD_OUT','DONE',$crplCode,$str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        /***************** AUDIT LOGS *************************/

        echo  "Update successfully!";

    }  //end fgp_done

    public function fgp_out_backload_data(){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mktn_hd = $this->request->getVar('mktn_hd');
        $data  = [];
        $crpl_code = $this->get_headerData($mktn_hd);
        $str_dt = "
        SELECT
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
        b.`convf`,
        b.`total_amount` tamt_scanned,
        '' price,
        b.`total_pcs` total_pcs_scanned,                
        b.`wob_barcde`  barcde,
        c.`ART_GWEIHGT` `weight`,
        b.`cbm`,
        g.`tpa_trxno`,
        g.`fgreq_trxno`,
        g.`stock_code`,
        g.`witb_barcde`,
        GROUP_CONCAT(h.`mat_code`) AS mat_code,
        GROUP_CONCAT(h.`qty_perpack`) AS qty_perpack
        FROM
        {$this->db_erp}.`warehouse_shipdoc_dt` b
        LEFT JOIN
        {$this->db_erp}.`mst_article` c
        ON
        b.`mat_rid` = c.`recid`
        LEFT JOIN
        {$this->db_erp}.`mst_plant` d
        ON
        b.`plnt_id` = d.`recid`
        LEFT JOIN
        {$this->db_erp}.`mst_wshe` e
        ON
        b.`wshe_id` = e.`recid`
        LEFT JOIN
        {$this->db_erp}.`mst_wshe_bin` f
        ON
        b.`wshe_sbin_id` = f.`recid`
        LEFT JOIN
        fgp_inv_rcv g
        ON
        b.`wob_barcde` = g.`wob_barcde`
        LEFT JOIN 
        trx_fgpack_req_dt h
        ON
        g.`fgreq_trxno` = h.`fgreq_trxno`
        WHERE b.`header` = '{$crpl_code}' AND b.`is_out` = '0'
        GROUP BY g.`witb_barcde`
        ORDER BY
            b.`stock_code`
        ";

        $qdt = $this->mylibzdb->myoa_sql_exec($str_dt,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        if($qdt->getNumRows() ==  0){
            echo "No records found";
            die();
            }
        $data['result'] = $qdt->getResultArray();
        return $data;

    }  //end fgp_out_backload_data

    public function fgpout_show(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $prefix = $this->request->getVar('prefix'); 
        //get items
        $str_itm = "
        SELECT
        a.`recid`,
        a.`tpa_trxno`,
        a.`fgreq_trxno`,
        a.`stock_code`,
        a.`wob_barcde`,
        a.`witb_barcde`,
        GROUP_CONCAT(b.`mat_code`) AS mat_code,
        GROUP_CONCAT(b.`qty_perpack`) AS qty_perpack,
        a.`rcv_date`
        
        FROM
        fgp_inv_rcv a
        JOIN
        trx_fgpack_req_dt b
        ON
        a.`fgreq_trxno` = b.`fgreq_trxno`
        JOIN 
        trx_tpa_hd c
        ON
        b.`tpa_trxno` = c.`tpa_trxno`
        WHERE 
        a.`is_out` = '0' AND c.`branch_name` LIKE  '%{$prefix}%'
        GROUP BY a.`witb_barcde`
        ";

    
        $q3 = $this->mylibzdb->myoa_sql_exec($str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($q3->getNumRows() > 0 )
        {
            $data['result'] = $q3->getResultArray();
            $data['count'] = $q3->getNumRows();
            $data['response'] = true;

        }
        else
        {
            $data['response'] = false;
            $data['result'] = '<div class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 "><h5> <i class="bi bi-info-circle-fill  text-dgreen"></i> No records found.</h5></div>';
            $data['count']  = 0;
        }
    
        return $data;
    
        
    }  //end fgpout_show

    public function fgpout_update_hd(){
      $cuser = $this->mylibzdb->mysys_user();
      $mpw_tkn = $this->mylibzdb->mpw_tkn();


      $txtWarehousetkn=  $this->request->getVar('txtWarehousetkn'); 
      $control_number =  $this->request->getVar('control_number'); 
      $branch_name    =  $this->request->getVar('branch_name'); 
      $plate_number   =  $this->request->getVar('plate_number'); 
      $driver         =  $this->request->getVar('driver'); 
      $helper_one     =  $this->request->getVar('helper_one'); 
      $helper_two     =  $this->request->getVar('helper_two'); 
      $ref_no         =  $this->request->getVar('ref_no'); 
      $sm_tag         =  $this->request->getVar('sm_tag'); 
      $rcvcount     =  $this->request->getVar('rcvcount'); 
      $truck_type   = $this->request->getVar('truck_type');  
      $rems_   = $this->request->getVar('rems_');  

      //get warehouse id 
      $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
      $whID = $wshedata['whID'];
      $plntID = $wshedata['plntID'];
      // warehouse end

     //check if transaction is existing befor update

      $str_chk = "SELECT `recid` FROM  {$this->db_erp}.`warehouse_shipdoc_hd` WHERE `crpl_code` = '{$control_number}' AND `frm_wshe_id` = '{$whID}' AND  `frm_plnt_id` ='{$plntID}'";
      $chk_q = $this->mylibzdb->myoa_sql_exec($str_chk,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
      if($chk_q->getNumRows() == 0 ){
        echo "Control number not found!";
        die();
      }

      $row = $chk_q->getRowArray();
      $sd_ID = $row['recid'];

          $str_in = "UPDATE  {$this->db_erp}.`warehouse_shipdoc_hd`
            SET
            `plate_no`   = '{$plate_number}',
            `driver`     = '{$driver}',
            `helper_1`   = '{$helper_one}',
            `helper_2`   = '{$helper_two}',
            `refno`      = '{$ref_no}',
            `truck_type` = '{$truck_type}',
            `me_remk`    = '{$rems_}' WHERE `recid` = '{$sd_ID}'";
      $this->mylibzdb->myoa_sql_exec($str_in,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

      //save to hd end
      /***************** AUDIT LOGS *************************/
      $this->mylibzdb->user_logs_activity_module($this->db_erp,'HEADER_CD_OUT_UPDT','HD RECID',$sd_ID,$str_in,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
      /***************** AUDIT LOGS *************************/

      echo "Header successfully updated! 
          <script type=\"text/javascript\"> 
           __mysys_apps.display_trxno('{$control_number}','control-number','btn-update-header'); 
          </script>";

    }  //end fgpout_update_hd

    public function fgp_out_revert(){
       $cuser = $this->mylibzdb->mysys_user();
       $mpw_tkn = $this->mylibzdb->mpw_tkn();

       $adata1          =  $this->request->getVar('adata1'); 
       $txtWarehousetkn =  $this->request->getVar('txtWarehousetkn'); 
       $mtkn_trxno      =  $this->request->getVar('mtkn_trxno'); 
       
       //get warehouse id 
       $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
       $whID = $wshedata['whID'];
       $plntID = $wshedata['plntID'];
       // warehouse end
      // get header data 
      $crplCode = $this->get_headerData($mtkn_trxno);
      
      $str_up = "
      UPDATE  {$this->db_erp}.`warehouse_shipdoc_hd` hd 
      SET  
      hd.`done` = 0
      WHERE hd.`crpl_code` = '{$crplCode}' 
        ";

      $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

      /***************** AUDIT LOGS *************************/
      $this->mylibzdb->user_logs_activity_module($this->db_erp,'REVERT_CD_OUT','REVERT',$crplCode,$str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
      /***************** AUDIT LOGS *************************/


       echo  "<strong>{$crplCode}</strong> Reverted successfully!";


    }  //end fgp_out_revert

    public function wshe_report_download(){
        
        $cuserlvl=$this->mylibzdb->mysys_userlvl();
        $cuser = $this->mylibzdb->mysys_user();
        $cuser_fullname = $this->mylibzdb->mysys_user_fullname();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $cfilelnk='';
        $file_name='';
        $chtmljs='';
        $fld_report_dteto = $this->mylibzsys->mydate_yyyymmdd($this->request->getVar('fld_report_dteto'));
        $fld_report_dtefrom = $this->mylibzsys->mydate_yyyymmdd($this->request->getVar('fld_report_dtefrom'));
        $file_name = 'crossdocking_out'.'_'.date('Ymd').$this->mylibzsys->random_string(15);
        $mpathdn   = ROOTPATH;
        $_csv_path = '/public/downloads/me/';
        $filepath = $mpathdn.$_csv_path.$file_name.'.csv';
        $cfilelnk = site_url() . 'downloads/me/' . $file_name.'.csv'; 

        
        $str = "
        SELECT *
        INTO OUTFILE '{$filepath}'
        FIELDS TERMINATED BY '\t'
        LINES TERMINATED BY '\r\n'
        FROM(
            SELECT 
            'HEADER',
            'BOX_ITEM_CODE',
            'BOX_ITEM_DESC',
            'PACKAGING',
            'QTY',
            'CONVF',
            'TOTAL_PCS',
            'TOTAL_AMOUNT',
            'PLANT',
            'WAREHOUSE',
            'BRANCH',
            'CREATED DATETIME',
            'POSTED DATETIME',
            'CHECK BY',
            'REMARKS'

            UNION ALL
            
            (SELECT a.`crpl_code`, d.`ART_CODE`, d.`ART_DESC`, d.`ART_SKU`, SUM(b.`qty`) AS qty, SUM(b.`convf`) AS convf,SUM(b.`total_pcs`) AS convf,
            SUM((SELECT SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount`
            FROM
            `warehouse_shipdoc_item` item
            JOIN
            `warehouse_shipdoc_dt` inv
            ON
            item.`wshe_out_id` = inv.`recid`
            JOIN
            `mst_article` artt
            ON
            item.`mat_rid` = artt.`recid`
            WHERE
            b.`recid` = inv.`recid`
            )) AS total_amount, e.`plnt_code`, f.`wshe_code`,a.`brnch`, a.`date_encd`,a.`done_date`, a.`chk_by`, a.`me_remk`
            FROM
            warehouse_shipdoc_hd a
            JOIN
            warehouse_shipdoc_dt b
            ON
            a.`crpl_code` = b.`header`
            JOIN
            mst_article d
            ON
            b.`mat_rid` = d.`recid`
            JOIN 
            mst_plant e
            ON
            b.`plnt_id` = e.`recid`
            JOIN
            mst_wshe f  
            ON
            b.`wshe_id` = f.`recid`
            WHERE
            DATE(a.`done_date`) >= '{$fld_report_dtefrom}'
            AND
            DATE(a.`done_date`) <= '{$fld_report_dteto}'
            GROUP BY d.`ART_CODE`, a.`crpl_code`
            ORDER BY a.`done_date` DESC)
            
            )oa

        ";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

        $chtmljs .= "
                    <script type=\"text/javascript\">
                        //window.parent.document.getElementById('myscrloading').innerHTML = '';
                        wshe_report_dl();
                        function wshe_report_dl(){
                            window.location = '{$cfilelnk}';
                        }
                        
                        jQuery('#lnktoprint').click(function() { 
                            jQuery('#__mtoexport_drtd').css({display:'none'});
                            //jQuery('#__mtoprint').css({display:'none'});
                            window.print();         
                        });
                    </script>
                    
                    ";
            echo $chtmljs;
    }  //end wshe_report_download

} 