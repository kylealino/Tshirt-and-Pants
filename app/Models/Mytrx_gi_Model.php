<?php
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Files\File;

class Mytrx_gi_Model extends Model
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
        $this->mydatazua = model('App\Models\MyDatauaModel');
        $this->dbx = $this->mylibzdb->dbx;
        $this->request = \Config\Services::request();
    }


    public function view_recs($npages = 1,$npagelimit = 30,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();


        //PARA SA MGA ADMINSITRATOR LANG
        $cusergrp = $this->mylibzdb->mysys_usergrp();
        /*if($cusergrp != 'SA'){
            $data = "</br><div class=\"col-md-3 alert alert-warning\"><strong>Note:</strong><br>Only administrative users can view this records.</div>";
            echo $data;
            $data = array();
            $data['npage_count'] = 1;
            $data['npage_curr'] = 1;
            $data['rlist'] = '';
            return $data;
        }*/



        $__flag="C";
        $str_optn = "";
        //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
        $str_vwrecs = "AND aa.`muser` = '$cuser'";
        if($cusergrp == 'SA'){
            $str_vwrecs = "";
        }
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " AND (aa.`grtrx_no` like '%$msearchrec%' OR aa.`muser` like '%$msearchrec%' OR ee.`wshe_code` like '%$msearchrec%' OR bb.`COMP_NAME` like '%$msearchrec%') AND aa.flag != '$__flag' {$str_vwrecs}";
        }
        if(empty($msearchrec)) {
            $str_optn = " AND aa.flag != '$__flag' {$str_vwrecs}";
        } 

       
        $strqry = "
        SELECT aa.*,
        bb.`COMP_NAME`,
        dd.`plnt_code`,
        ee.`wshe_code`,
        sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) mtkn_arttr 
        FROM {$this->db_erp}.`trx_wshe_gr_hd` aa
        JOIN {$this->db_erp}.`mst_company` bb ON (aa.`comp_id` = bb.`recid`)
        JOIN {$this->db_erp}.`mst_plant` dd ON (aa.`plant_id` = dd.`recid`)
        JOIN {$this->db_erp}.`mst_wshe` ee ON (aa.`wshe_id` = ee.`recid`)
        WHERE aa.`cd_tag` = 'Y'
        {$str_optn} 
        order by recid desc 
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
    } 




    public function cancel_recs(){
            $cuser          = $this->mylibzdb->mysys_user();
            $mpw_tkn        = $this->mylibzdb->mpw_tkn();
            //$mtkn_mndt_rid: = $this->request->get_post('mtkn_podt_rid');
            $mtkn_rid  = $this->request->getVar('mtkn_itm');
            

            $str = "SELECT * from {$this->db_erp}.`trx_wshe_gr_hd` where sha2(concat(recid,'{$mpw_tkn}'),384) = '$mtkn_rid' AND `cd_tag` = 'Y'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);



            if($q->getNumRows() > 0) { 
                $rw         = $q->getRowArray();
                $txtrecid   = $rw['recid'];
                $__flag="C";

                $str = "UPDATE {$this->db_erp}.`trx_wshe_gr_hd` SET `flag` = '$__flag' WHERE `recid` = '$txtrecid' AND `cd_tag` = 'Y'";
                $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                $this->mylibzdb->user_logs_activity_module($this->db_erp,'GR_CREC','',$txtrecid,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                echo "
                <div class=\" alert alert-success\">
                <strong>Success</strong> 
                <p>Records successfully deleted!!!</p>
                </div>
                
                ";
            } 
            else 
            {
                echo "
                <div class=\" alert alert-danger\">
                <strong>Error</strong> 
                <p>Records already deleted!!!</p>
                </div>
                ";
            }
    }



        public function gi_approving(){
            $mtkn_trxno = $this->request->getVar('mtkn_trxno');
            $id_post = $this->request->getVar('id_appr');
            $gi_code = $this->request->getVar('gi_code');
            $wshe_id = $this->request->getVar('wshe_id');
            $type = $this->request->getVar('type');
            $cuser = $this->mylibzdb->mysys_user();
            $mpw_tkn = $this->mylibzdb->mpw_tkn();
            $rems = '';
        
            if(empty($mtkn_trxno)){
                $this->mymelibzsys->warning_msg("#dc3545","text-danger","Transaction no. is empty.");
                die();
            }

            //CHECK KUNG NKAPAGAPPROVED NA SYA
            $str = "SELECT recid from {$this->db_erp}.`warehouse_gi_hd` 
            WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$mtkn_trxno' AND `is_approved` = 'Y'";
            
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() > 0) { 
                $this->mymelibzsys->warning_msg("#dc3545","text-danger","You already approved this transaction!");
                die();
            }
                
            $str = "
            UPDATE {$this->db_erp}.`warehouse_gi_hd`
            SET `is_approved` = 'Y',
                `apprvd_by` = '{$cuser}',
                `apprvd_date` = now()
            WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$mtkn_trxno' AND `is_approved` = 'N';
            ";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_GI_APPROVING',$cuser,$gi_code,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            

            // ADD MODULE HERE FOR UPDATE
            $str = "SELECT `header`,`plnt_id`,`wshe_id` FROM {$this->db_erp}.`warehouse_gi_hd` 
            WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) = '$mtkn_trxno' AND `is_approved` = 'Y'";
            $q6 = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            if($q6->getNumRows() > 0) {
                $rw6        = $q6->getRowArray();
              
                $header = $rw6['header'];
                $plntID = $rw6['plnt_id'];
                $whID   = $rw6['wshe_id'];
          
                $str_up = "
                UPDATE  {$this->db_erp}.`warehouse_inv_rcv` dt,{$this->db_erp}.`warehouse_gi_dt` rcv
                SET  dt.`type` = 'GI',
                     dt.`SD_NO` =  '{$header}'
                WHERE  dt.`witb_barcde` =  rcv.`witb_barcde` AND rcv.`trx` = '{$header}'
                AND dt.`wshe_id` = $whID AND dt.`plnt_id` = {$plntID} ";

                $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_UPD_TAG_GI_TO_RCV',$cuser,$header,$str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
  
              
            }//q6

            echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Approved/Posted successfully!!!</div>";

        }//end func


    public function gi_ent_recs(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_whse = $this->request->getVar('mtkn_whse');


        //PARA SA MGA ADMINSITRATOR LANG
        $cusergrp = $this->mylibzdb->mysys_usergrp();
        /*if($cusergrp != 'SA'){
            $data = "</br><div class=\"col-md-3 alert alert-warning\"><strong>Note:</strong><br>Only administrative users can view this records.</div>";
            echo $data;
            $data = array();
            $data['npage_count'] = 1;
            $data['npage_curr'] = 1;
            $data['rlist'] = '';
            return $data;
        }*/

        $wsheData = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
        $fld_plant = $wsheData['plntID'];
        $fld_wshe = $wsheData['whID'];


        $__flag="C";
        $str_optn = "";
        
        //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
        $str_vwrecs = "AND aa.`muser` = '$cuser'";
        if($cusergrp == 'SA'){
            $str_vwrecs = "";
        }
        if(!empty($msearchrec)) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            $str_optn = " AND (aa.`grtrx_no` like '%$msearchrec%' OR aa.`muser` like '%$msearchrec%' OR ee.`wshe_code` like '%$msearchrec%' OR bb.`COMP_NAME` like '%$msearchrec%') AND aa.flag != '$__flag' {$str_vwrecs}";
        }

        if(empty($msearchrec)) {
            $str_optn = " AND aa.flag != '$__flag' {$str_vwrecs}";
        } 

       
        $strqry = "
        SELECT aa.`header`,aa.`muser`,aa.`encd`,aa.`recid`,aa.`type`,aa.`remarks`,aa.`is_approved`,
        dd.`plnt_code`,
        ee.`wshe_code`,
        sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) mtkn_arttr
        FROM {$this->db_erp}.`warehouse_gi_hd` aa
        JOIN {$this->db_erp}.`mst_plant` dd ON (aa.`plnt_id` = dd.`recid`)
        JOIN {$this->db_erp}.`mst_wshe` ee ON (aa.`wshe_id` = ee.`recid`)
        WHERE aa.`wshe_id` = '{$fld_wshe}' AND aa.`plnt_id` = '{$fld_plant}'
        {$str_optn} 
        order by recid desc 
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
    }

    public function wh_gi_ent_upld(){ 
    $cuser = $this->mylibzdb->mysys_user();
    $mpw_tkn = $this->mylibzdb->mpw_tkn();
    $txtprod_type_upld_sub = $this->request->getVar('txtprod_type_upld_sub');
    $txtWarehouse   =  $this->request->getVar('txtWarehouse'); 
    $txtWarehousetkn   =  $this->request->getVar('txtWarehousetkn'); 
    $giType   =  $this->request->getVar('giType'); 
    $giRemarks   =  $this->request->getVar('giRemarks');
    $barcdeCol = "b.`witb_barcde`";

    if(!empty($giType) && $giType == 'DAMAGE'){
        $barcdeCol = "b.`dmg_barcde`";
    }
    //get warehouse id 
    $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
    $whID = $wshedata['whID'];
    $plntID = $wshedata['plntID'];
    // warehouse end

    $type = "";
    $insertSubTag = 0;
    $nrecs_pb     = 0;
    $invalidUnit  = 0 ;

    $csv_file = "";
    $csv_ofile = "";
    $_csv_path = './whgientcd_upld/';
    $_csv_upath = './whgientcd_upld/';
    $_csv_pubpath = './uploads/whgientcd_upld/';

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
        $tbltemp   = $this->db_temp . ".`whgientcd_upld_temp_" . $this->mylibzsys->random_string(15) . "`";

        $str = "drop table if exists {$tbltemp}";
        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $cfile = $_csv_pubpath . $csv_file;
        //create temp table 
        $str = "
        CREATE table {$tbltemp} ( 
        `recid` int(25) NOT NULL AUTO_INCREMENT,
        whseBarcode varchar(35) default '',
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
        whseBarcode
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
            whseBarcode
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
                whseBarcode
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

        //remove special char
        $str = "UPDATE {$tbltemp} SET 
        whseBarcode  = TRIM(REGEXP_REPLACE(whseBarcode, '[^\\x20-\\x7E]', '')) 
        ";
        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


        // //get barcodes 

        // $sep = '"\'"';
        // $str = "SELECT GROUP_CONCAT({$sep},`whseBarcode`,{$sep}) brcds from  {$tbltemp} ";
        // $brcdq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        // $brcdr = $brcdq->getRowArray();
        // $barcd_upls_list = $brcdr['brcds'];


        $str_chk =" 
            SELECT
             GROUP_CONCAT({$barcdeCol} SEPARATOR '<br>') invalid_barcdes,
             COUNT(b.`witb_barcde`) barcode_count
            FROM
              {$this->db_erp}.`warehouse_inv_rcv` b
            WHERE b.`plnt_id` =  '{$plntID}'
            AND  b.`wshe_id` = '{$whID}' 
            AND b.`is_out` = 1 
            AND {$barcdeCol} IN (SELECT TRIM(REPLACE(REPLACE(`whseBarcode`,'\r',''),'\n','')) FROM {$tbltemp})";
            $brcdq = $this->mylibzdb->myoa_sql_exec($str_chk,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $brcdr = $brcdq->getRowArray();
            $invalid_barcd_list = $brcdr['invalid_barcdes'];
            $barcode_count = $brcdr['barcode_count'];

            if(!empty($invalid_barcd_list)){

            // return array(
            //   'result' => false,
            //   'data' => "<div class=\"alert alert-danger\"><strong>[ {$barcode_count} ] Barcode/s not found or 0 QTY.</strong><br>{$invalid_barcd_list}</div>"
            // );

           echo  "<div class=\"col-lg-6 offset-lg-3 alert alert-danger\"><strong>[ {$barcode_count} ] Barcode(s) not available for GI.</strong><br>{$invalid_barcd_list}</div>";
        
            }

            //get items
               $str_itm = "SELECT
                        b.`recid`,
                        b.`qty`,
                        b.`qty`qty_scanned ,
                        b.`is_out`,
                        b.`mat_rid`,
                        b.`trx`,
                         '' `uprice`,
                        b.`remarks`,
                        d.`plnt_code`,
                        e.`wshe_code`,
                        b.`box_no`,
                        b.`stock_code`,
                        c.`ART_CODE` mat_code,
                        c.`ART_DESC`,
                        c.`ART_UOM`,
                        b.`convf`,
                        f.`wshe_bin_name`,
                        b.`total_amount` tamt_scanned,
                        '' price,
                        b.`total_pcs` total_pcs_scanned,
                        b.`witb_barcde`, 
                        b.`irb_barcde`,
                        b.`srb_barcde`,
                        b.`dmg_barcde`,
                        b.`wob_barcde`,
                        b.`pob_barcde`,               
                        {$barcdeCol}  barcde,
                       c.`ART_GWEIHGT` `weight`,
                       b.`cbm`,
                       '' barcde_series,
                       '' barc_type

                   FROM
                      {$this->db_erp}.`warehouse_inv_rcv` b
                    JOIN
                       {$this->db_erp}.`mst_article` c
                   ON
                       b.`mat_rid` = c.`recid`
                    JOIN
                       {$this->db_erp}.`mst_plant` d
                   ON
                       b.`plnt_id` = d.`recid`
                    JOIN
                       {$this->db_erp}.`mst_wshe` e
                   ON
                       b.`wshe_id` = e.`recid`
                   JOIN
                       {$this->db_erp}.`mst_wshe_bin` f
                   ON
                       b.`wshe_sbin_id` = f.`recid`

                   WHERE
                      {$barcdeCol} IN (SELECT TRIM(REPLACE(REPLACE(`whseBarcode`,'\r',''),'\n','')) FROM {$tbltemp})
                   
                   AND 
                        b.`is_out` = 0 
                   AND
                       b.`plnt_id` = {$plntID}
                   AND
                       b.`wshe_id` = {$whID}
         
                   AND REPLACE(REPLACE(REPLACE({$barcdeCol}, ' ', ''), '\t', ''), '\n', '') <> ''
                   GROUP BY REPLACE(REPLACE(REPLACE({$barcdeCol}, ' ', ''), '\t', ''), '\n', '')
                   ORDER BY
                       b.`stock_code`";

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
       
        return $data;
 
        
    }  //end simpleupld_proc

    public function mywh_gi_ent_save(){

        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $data_array = $this->request->getVar('data_array');
        $rowCount = $this->request->getVar('rowCount');
        $txtWarehousetkn   =  $this->request->getVar('txtWarehousetkn'); 
        $giType   =  $this->request->getVar('giType'); 
        $giRemarks   =  $this->request->getVar('giRemarks');
        $barcdeCol = "`witb_barcde`";

        if(!empty($giType) && $giType == 'DAMAGE'){
            $barcdeCol = "`dmg_barcde`";
        }
        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end
      
        if(empty($data_array))
        {
            $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>No items to be receive.</div>";
        }



        //CHECKING IF ALREADY RECIEVED
        $str = "
            SELECT 
               GROUP_CONCAT({$barcdeCol} SEPARATOR '<br>') barcode_exist
            FROM
                {$this->db_erp}.`warehouse_gi_dt`
            WHERE
                {$barcdeCol} IN ($data_array) ";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $exrow = $q->getRowArray();
        $barcode_exist = $exrow['barcode_exist'];
         
        if(!empty($barcode_exist)){
        $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Barcode [ {$barcode_exist} ] is already exist.</div>";
        echo $data;
     
        die();
        }

        //CHECKING IF ALREADY RECIEVED END



        //create header transaction
        $_hd_ctrlno = "CDGI".$this->mydataz->get_ctr($this->db_erp,'CTRL_CDGI');  
        //$this->mydataz->get_ctr_new_dr('CWO','',$this->db_erp,'CTRL_CWO');
        $str_in = "INSERT INTO  {$this->db_erp}.`warehouse_gi_hd` (
           `header`,
            `plnt_id`,
            `wshe_id`,
            `type`,
            `remarks`,
            `muser`,
            `encd`
             )
             VALUES
           (
         '{$_hd_ctrlno}',
         '{$plntID}',
         '{$whID}',
         '{$giType}',
         '{$giRemarks}',
         '{$cuser}',
         now()
           );
        ";
         $this->mylibzdb->myoa_sql_exec($str_in,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
         //save to hd end

        
        $qty = 1;
        //insert to logs
        $str = "
            INSERT INTO
        {$this->db_erp}.`warehouse_gi_dt`(
            `trx`,
            `header`,
            `stock_code`,
            `barcde`,
            `irb_barcde`,
            `srb_barcde`,
            `witb_barcde`,
            `wob_barcde`,
            `pob_barcde`,
            `dmg_barcde`,
            `plnt_id`,
            `wshe_id`,
            `wshe_sbin_id`,
            `wshe_grp_id`,
            `box_no`,
            `mat_rid`,
            `qty`,
            `convf`,
            `cbm`,
            `total_pcs`,
            `total_amount`,
            `remarks`,
            `muser`,
            `encd`
        )
        SELECT 
            '{$_hd_ctrlno}',
            `header`,
            `stock_code`,
            {$barcdeCol},
            `irb_barcde` irb,
            `srb_barcde`,
            `witb_barcde`,
            `wob_barcde`,
            `pob_barcde`,
            `dmg_barcde`,
            `plnt_id`,
            `wshe_id`,
            `wshe_sbin_id`,
            `wshe_grp_id`,
            `box_no`,
            `mat_rid`,
            '{$qty}',
            `convf`,
             `cbm`,
            `convf` conv,
            `total_amount`,
            `remarks`,
            '{$cuser}',
            now() 
            FROM {$this->db_erp}.`warehouse_inv_rcv`
            WHERE  {$barcdeCol} IN ($data_array) 
            AND `plnt_id` = {$plntID}
            AND `wshe_id` = {$whID}
            AND YEAR(`encd`) >= '2022'  ";

            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            //insert to logs end

            //update is_out tag in gr barcoding dt
            $str_up = "
            UPDATE  {$this->db_erp}.`warehouse_inv_rcv` dt,{$this->db_erp}.`warehouse_gi_dt` rcv
            SET  dt.`is_out` = 1  
            WHERE  dt.{$barcdeCol} IN ($data_array) AND  dt.{$barcdeCol} =  rcv.{$barcdeCol} AND rcv.`trx` = '{$_hd_ctrlno}'
            AND dt.`wshe_id` = $whID AND dt.`plnt_id` = {$plntID} ";
            $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
   

            /***************** AUDIT LOGS *************************/

            $data = "<div class=\"alert alert-success\"><strong>SAVE</strong><br>Transaction successfully saved. <br> TRANSACTION NO: <span style=\"color:red;display:inline-block; \">{$_hd_ctrlno}</span>
                <p>TOTAL QTY: <span style=\"color:red;display:inline-block; \">{$rowCount}</span></p>
             </div>";
            echo $data;

            
        
    }

    public function view_ent_itm_recs(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $mtkn_dt   = $this->request->getVar('mtkn_dt');
        $gino   = $this->request->getVar('gino');
        
        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end

        
        //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
        $str_vwrecs = "AND a.`muser` = '$cuser'";
    
        $str_optn = '';
   

        $strqry = "
        SELECT
        rcv.`recid`,
        rcv.`qty`,
        rcv.`qty`qty_scanned ,
         CASE
            WHEN
                IFNULL(rcv.`qty`,0) = 0
            THEN
                'N/A'
            WHEN
                1 > rcv.`qty`
            THEN
                'LESS'
            WHEN
               1 < rcv.`qty`
            THEN
                'OVER'
            WHEN
               1  = rcv.`qty`
            THEN
                'TALLY'
         END
         AS
         `variance`,
        rcv.`stock_code`,
        art.`ART_CODE`,
        art.`ART_DESC`,
        rcv.`convf`,
        rcv.`total_amount` tamt_scanned,
        '' price,
        rcv.`total_pcs` total_pcs_scanned,
        rcv.`barcde`  barcde
        FROM
        {$this->db_erp}.`warehouse_gi_dt` rcv
        JOIN  {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN  {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        JOIN  {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}'
        AND SHA2(CONCAT(rcv.`trx`,'{$mpw_tkn}'),384) = '{$mtkn_dt}'
        GROUP BY rcv.`barcde` ORDER BY `recid` DESC";
     
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['gino'] = $gino;
         
        } else { 
         $data = array();
         $data['gino'] = $gino;
         $data['rlist'] = '';
        
        }
        return $data;
    } //endfunc

    public function gi_approval_recs($npages = 1,$npagelimit = 30){
           $cuser = $this->mylibzdb->mysys_user();
           $mpw_tkn = $this->mylibzdb->mpw_tkn();
           /*var_dump($fld_grbranch);
          */
             $cusergrp = $this->mylibzdb->mysys_usergrp();

           $fld_grdtfrm = $this->mylibzsys->mydate_yyyymmdd($this->request->getVar('fld_wf_dtefrom'));
           $fld_grdtto = $this->mylibzsys->mydate_yyyymmdd($this->request->getVar('fld_wf_dteto'));

           $__flag="C";
           $str_brnch = "";
           $str_date = "";
           //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
           $str_vwrecs = "";

          
           if((!empty($fld_grdtfrm) && !empty($fld_grdtto)) && (($fld_grdtfrm != '--') && ($fld_grdtto != '--'))) {
               $str_date = " AND (SUBSTRING_INDEX(aa.`encd`,' ',1) >= DATE('{$fld_grdtfrm}') AND  SUBSTRING_INDEX(aa.`encd`,' ',1) <= DATE('{$fld_grdtto}'))";
           }
           if(((!empty($fld_grdtfrm) && !empty($fld_grdtto)) && (($fld_grdtfrm != '--') && ($fld_grdtto != '--'))) || (!empty($fld_grbranch) && !empty($fld_grbranch_id))){
               $strqry = "
               SELECT
               aa.`recid` __arid,
               aa.`header`,
               aa.`type`,
               aa.`remarks`,
               aa.`plnt_id`,
               aa.`encd`,
               aa.`muser`,
               aa.`is_approved`,
               dd.`plnt_code`,
               ee.`wshe_code`,
               sha2(concat(aa.`recid`,'{$mpw_tkn}'),384) mtkn_arttr,
               sha2(concat(aa.`wshe_id`,'{$mpw_tkn}'),384) wshe_id 
               FROM {$this->db_erp}.`warehouse_gi_hd` aa
               JOIN {$this->db_erp}.`mst_plant` dd
               ON (aa.`plnt_id` = dd.`recid`)
               JOIN {$this->db_erp}.`mst_wshe` ee
               ON (aa.`wshe_id` = ee.`recid`)
               where aa.`flag` != '$__flag'  AND aa.`is_approved` = 'N'
               {$str_brnch} {$str_date} {$str_vwrecs}
               ";
               
    
               $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
       
               
               if($qry->getNumRows() > 0) { 
                   $data['rlist'] = $qry->getResultArray();
                   $data['fld_grdtfrm'] = $fld_grdtfrm;
                   $data['fld_grdtto'] = $fld_grdtto;
               } else { 
                   $data = array();
                   $data['rlist'] = '';
                   $data['fld_grdtfrm'] = $fld_grdtfrm;
                   $data['fld_grdtto'] = $fld_grdtto;
               }
               return $data;
           }
           else{
               $data = array();
               $data['rlist'] = '';
               $data['fld_grdtfrm'] = $fld_grdtfrm;
               $data['fld_grdtto'] = $fld_grdtto;

               return $data;
           }
           
       }//endfunc


} //end main MyMDCustomerModel