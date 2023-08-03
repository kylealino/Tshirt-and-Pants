<?php
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Files\File;

class MyWarehousercvModel extends Model
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

    public function view_ent_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $mtkn_whse = $this->request->getVar('mtkn_whse');
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
        rcv.`recid`,
        `trx`,
        pl.`plnt_code`,
        wh.`wshe_code`,
        rcv.`muser`,
        rcv.`encd`
        FROM
        {$this->db_erp}.`warehouse_inv_rcv` rcv
        JOIN  {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN  {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}'
        GROUP BY rcv.`trx`";

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
    } //endfunc


    public function view_ent_itm_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
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
      
        rcv.`witb_barcde`  barcde
        FROM
        {$this->db_erp}.`warehouse_inv_rcv` rcv
        JOIN  {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN  {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        JOIN  {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}'
        AND SHA2(CONCAT(rcv.`trx`,'{$mpw_tkn}'),384) = '{$mtkn_dt}'
        GROUP BY rcv.`witb_barcde` ORDER BY `recid` DESC";

        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

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
    } //endfunc


    public function whrcvng_upld(){ 
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
        $tbltemp   = $this->db_temp . ".`whrcvngcd_upld_temp_" . $this->mylibzsys->random_string(15) . "`";

        $str = "drop table if exists {$tbltemp}";
        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $cfile = $_csv_pubpath . $csv_file;
        //create temp table 
        $str = "
        CREATE table {$tbltemp} ( 
        `recid` int(25) NOT NULL AUTO_INCREMENT,
        witbBarcode varchar(35) default '',
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
        witbBarcode
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
            witbBarcode
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
                witbBarcode
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
        witbBarcode  = TRIM(REGEXP_REPLACE(witbBarcode, '[^\\x20-\\x7E]', ''))
        ";
        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


        //get barcodes 

        $sep = '"\'"';
        $str = "SELECT GROUP_CONCAT({$sep},`witbBarcode`,{$sep}) brcds from  {$tbltemp} ";
        $brcdq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $brcdr = $brcdq->getRowArray();
        $barcd_upls_list = $brcdr['brcds'];


        //get items
        $str_itm = "SELECT
                c.`ART_CODE` AS `mat_code`,
                c.`ART_DESC`,
                c.`ART_SKU` as `ART_UOM`,
                b.`convf`,
                CASE
                    WHEN
                        COUNT(REPLACE(REPLACE(REPLACE(a.`witbBarcode`, ' ', ''), '\t', ''), '\n', '')) > b.`qty`
                    THEN
                        b.`qty`
                    ELSE
                        COUNT(REPLACE(REPLACE(REPLACE(a.`witbBarcode`, ' ', ''), '\t', ''), '\n', ''))
                END     
                AS `qty_scanned`,
                b.`total_pcs` AS `total_pcs_scanned`,
                b.`total_amount` as `price`,
                b.`total_amount` as `tamt`,
                (b.`convf` * COUNT(REPLACE(REPLACE(REPLACE(a.`witbBarcode`, ' ', ''), '\t', ''), '\n', ''))) * b.`total_amount` AS `tamt_scanned`,
                d.`plnt_code`,
                e.`wshe_code`,
                f.`wshe_bin_name`,
                b.`barcde` AS `barcde`,
                b.`irb_barcde`,
                b.`remarks`,
                b.`witb_barcde` AS `witb_barcde`,
                b.`srb_barcde` AS `srb_barcde`,
                b.`wob_barcde` AS `wob_barcde`,
                b.`pob_barcde` AS `pob_barcde`,
                b.`dmg_barcde` AS `dmg_barcde`,
                '' AS `barc_type`,
                b.`stock_code`,
                b.`header` as `barcde_series`,
                b.`cbm`,
                b.`recid` as `wshe_barcdng_dt_id`,

                c.`recid` AS `mat_rid`,
                d.`recid` AS `plnt_id`,
                e.`recid` AS `wshe_id`,
                f.`recid` AS `wshe_sbin_id`

            FROM
                {$tbltemp} a
            LEFT JOIN
                {$this->db_erp}.`wshe_barcdng_dt` b
            ON
                REPLACE(REPLACE(REPLACE(a.`witbBarcode`, ' ', ''), '\t', ''), '\n', '') = b.`witb_barcde`
            LEFT JOIN
                {$this->db_erp}.`mst_article` c
            ON
                b.`mat_rid` = c.`recid`
            LEFT JOIN
                {$this->db_erp}.`mst_plant` d
            ON
                b.`to_plnt_id` = d.`recid`
            LEFT JOIN
                {$this->db_erp}.`mst_wshe` e
            ON
                b.`to_wshe_id` = e.`recid`
            LEFT JOIN
                {$this->db_erp}.`mst_wshe_bin` f
            ON
                b.`to_wshe_sbin_id` = f.`recid`
            LEFT JOIN
                {$this->db_erp}.`wshe_barcdng_hd` g
            ON
                b.`header` = g.`header`
            WHERE
                 b.`to_plnt_id` = {$plntID}
             AND
                 b.`to_wshe_id` = {$whID}
            AND
                REPLACE(REPLACE(REPLACE(a.`witbBarcode`, ' ', ''), '\t', ''), '\n', '') <> ''
            AND
                b.`witb_barcde` IN ({$barcd_upls_list})
             AND YEAR(b.`encd`) >= '2022'
            GROUP BY REPLACE(REPLACE(REPLACE(a.`witbBarcode`, ' ', ''), '\t', ''), '\n', '')
            ORDER BY
                b.`stock_code`
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
 
    
}  //end simpleupld_proc

public function mywhrcvng_save(){

    $cuser = $this->mylibzdb->mysys_user();
    $mpw_tkn = $this->mylibzdb->mpw_tkn();
    $data_array = $this->request->getVar('data_array');
    $rowCount = $this->request->getVar('rowCount');
    $txtWarehousetkn   =  $this->request->getVar('txtWarehousetkn'); 

    //get warehouse id 
    $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
    $whID = $wshedata['whID'];
    $plntID = $wshedata['plntID'];
    // warehouse end


    if(empty($data_array))
    {
        $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>No items to be receive.</div>";
    }
    //CHECK BARCODES IF EXIST IN BARCODING DT
    $str = "
        SELECT 
            count(`irb_barcde`) brcde_count
        FROM
            {$this->db_erp}.`wshe_barcdng_dt`
        WHERE
           `witb_barcde` IN ($data_array)
    ";

    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    if($q->getNumRows() == 0){
        $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Invalid barcoding data.</div>";
        echo $data;
        die();
    
    }
 
    $brcd_ck = $q->getRowArray();
    $dcount = $brcd_ck['brcde_count'];

    if($dcount != $rowCount){
        $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Invalid barcoding data.</div>";
        echo $data;
        die();
    }

    //CHECK BARCODES IF EXIST IN BARCODING DT END

    //CHECKING IF ALREADY RECIEVED
    $str = "
        SELECT 
           GROUP_CONCAT(`witb_barcde`) witb_exist
        FROM
            {$this->db_erp}.`warehouse_inv_rcv`
        WHERE
            `witb_barcde` IN ($data_array)
    ";

    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    $exrow = $q->getRowArray();
    $witb_exist = $exrow['witb_exist'];
    if(!empty($witb_exist)){
    $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Barcode [ {$witb_exist} ] is already received.</div>";
    echo $data;
    die();
    }
    
    //CHECKING IF ALREADY RECIEVED END


    //create header transaction

    $_hd_ctrlno = $this->mydataz->get_ctr($this->db_erp,'CTRL_CWR');
    $qty = 1;
    //insert to logs
    $str = "
        INSERT INTO
    {$this->db_erp}.`warehouse_inv_rcv`(
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
        `irb_barcde`,
        `irb_barcde` irb,
        `srb_barcde`,
        `witb_barcde`,
        `wob_barcde`,
        `pob_barcde`,
        `dmg_barcde`,
        `frm_plnt_id`,
        `frm_wshe_id`,
        `frm_wshe_sbin_id`,
        `frm_wshe_grp_id`,
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
        FROM {$this->db_erp}.`wshe_barcdng_dt`
        WHERE  `witb_barcde` IN ($data_array)";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        //insert to logs end

    //insert in central

    $str = "
    INSERT INTO
    {$this->db_erp}.`warehouse_inv_rcv_logs`(
        `wshe_inv_id`,
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
        `recid`,
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
        FROM {$this->db_erp}.`warehouse_inv_rcv`
        WHERE `trx`  = '{$_hd_ctrlno}' ";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        /***************** AUDIT LOGS *************************/

        $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CENTRAL_RCVNG','OLD PROCESS SAVING CENTRAL',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        /***************** AUDIT LOGS *************************/

        $str = "
            INSERT INTO
            {$this->db_erp}.`warehouse_inv_rcv_item`(
                `wshe_inv_id`,
                `witb_barcde`,
                `mat_rid`,
                `qty`,
                `price`,
                `total_amount`,
                `mat_code`,
                `uprice`,
                `muser`,
                `encd`)
            SELECT 
            wdt.`recid`,
            wdt.`witb_barcde`,
            im.`mat_rid`,
            im.`qty`,
            im.`price`,
            im.`total_amount`,
            art.`ART_CODE`,
            art.`ART_UPRICE`,
            '{$cuser}',
            NOW()
            FROM  {$this->db_erp}.`wshe_barcdng_dt` dt
            JOIN  {$this->db_erp}.`warehouse_inv_rcv` wdt ON dt.`witb_barcde` = wdt.`witb_barcde`
            JOIN  {$this->db_erp}.`wshe_barcdng_item` im ON dt.`recid` = im.`dt_id` AND im.`header_id` = dt.`header_id` AND dt.`recid` = im.`dt_id`
            JOIN  {$this->db_erp}.`mst_article` art on im.`mat_rid` = art.`recid`  
            WHERE wdt.`trx` = '{$_hd_ctrlno}' 
            AND  dt.`to_plnt_id` = {$plntID}
            AND dt.`to_wshe_id` = {$whID}
            AND YEAR(dt.`encd`) >= '2022'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            /***************** AUDIT LOGS *************************/

            $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_RCVNG_ITEM','',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            $str = "
                INSERT INTO
                {$this->db_erp}.`warehouse_inv_rcv_item_logs`(
                    `wshe_inv_id`,
                    `witb_barcde`,
                    `mat_rid`,
                    `qty`,
                    `price`,
                    `total_amount`,
                    `muser`,
                    `encd`
                )

                SELECT 
                dt.`wshe_inv_id`,
                dt.`witb_barcde`,
                dt.`mat_rid`,
                dt.`qty`,
                dt.`price`,
                dt.`total_amount`,
                dt.`muser`,
                dt.`encd`
                FROM  {$this->db_erp}.`warehouse_inv_rcv_item` dt 
                JOIN  {$this->db_erp}.`warehouse_inv_rcv` wdt ON dt.`wshe_inv_id` = wdt.`recid`
                WHERE wdt.`trx` = '{$_hd_ctrlno}'

                        ";
                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
  

            /***************** AUDIT LOGS *************************/

            $data = "<div class=\"alert alert-success\"><strong>SAVE</strong><br>Transaction successfully saved. <br> TRANSACTION NO: <span style=\"color:red;display:inline-block; \">{$_hd_ctrlno}</span>
                <p>TOTAL QTY: <span style=\"color:red;display:inline-block; \">{$rowCount}</span></p>
             </div>";
            echo $data;

        
        
    
}

public function mywhrcvng_save2(){

    $cuser = $this->mylibzdb->mysys_user();
    $mpw_tkn = $this->mylibzdb->mpw_tkn();
    $data_array = $this->request->getVar('data_array');

    if(empty($data_array)){
        $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>No items to be receive.</div>";
    }
    else{

        $vdata = array();

        for($i = 0; $i < count($data_array); $i++){

            $data = explode('x|x',$data_array[$i]);

            $mktn_barcdng_id = $this->dbx->escapeString(trim($data[0]));
            $qty_scanned = $this->dbx->escapeString(trim($data[1]));

            $str = "
                SELECT 
                    `recid`,
                    `irb_barcde`
                FROM
                    {$this->db_erp}.`wshe_barcdng_dt`
                WHERE
                    SHA2(CONCAT(`recid`,'{$mpw_tkn}'),384) = '{$mktn_barcdng_id}'
            ";

            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            if($q->getNumRows() == 0){
                $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Invalid barcoding data.</div>";
                echo $data;
                die();
                break;
            }
            else{

                $rr = $q->getRowArray();
                $_valid_barcdng_id = $rr['recid'];
                $irb_barcde = $rr['irb_barcde'];

                $str = "
                    SELECT 
                        `recid`
                    FROM
                        {$this->db_erp}.`warehouse_inv_rcv`
                    WHERE
                        `irb_barcde` = '{$irb_barcde}'
                ";

                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                if($q->getNumRows() > 0){

                    $data = "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Barcode [ {$irb_barcde} ] is already received.</div>";
                    echo $data;
                    die();
                }
                else{
                    array_push($vdata,$data);   
                }
                
            }

        }//endfor

        if(count($vdata) > 0){

            //create header transaction

            $_hd_ctrlno = $this->mydataz->get_ctr($this->db_erp,'TRANSFER_RACK');

            // $str = "
            //     INSERT INTO
            //     {$this->db_erp}.`wshe_central_hd`(
            //         `header`,
            //         `user`,
            //         `encd`
            //     )
            //     VALUES(
            //         '{$central_hd_ctrlno}',
            //         '{$cuser}',
            //         now()
            //     )
            // ";

            // $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            // $str = "
            //     SELECT 
            //         `recid`,
            //         `header`
            //     FROM
            //         {$this->db_erp}.`wshe_central_hd`
            //     WHERE
            //         `header` = '{$central_hd_ctrlno}'
            //     LIMIT 1
            // ";

            // $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            // $rr = $q->getRowArray();

            $_valid_central_hd_id = 'null';//$rr['recid'];
            $_valid_central_header = 'null';//$rr['header'];

            for($i = 0; $i < count($vdata); $i++){

                $datai = $vdata[$i];
                $mktn_barcdng_id = $datai[0];
                // $qty = $datai[1];
                $qty = 1;

                $str = "
                    SELECT 
                        `recid`,
                        `header`,
                        `stock_code`,
                        `barcde`,
                        `irb_barcde`,
                        `srb_barcde`,
                        `witb_barcde`,
                        `wob_barcde`,
                        `pob_barcde`,
                        `dmg_barcde`,
                        `mat_rid`,
                        `convf`,
                        `uom`,
                        `cbm`,
                        `total_pcs`,
                        `total_amount`,
                        `frm_plnt_id`,
                        `frm_wshe_id`,
                        `frm_wshe_sbin_id`,
                        `frm_wshe_grp_id`,
                        `to_plnt_id`,
                        `to_wshe_id`,
                        `to_wshe_sbin_id`,
                        `to_wshe_grp_id`,
                        `box_no`,
                        `remarks`
                    FROM
                        {$this->db_erp}.`wshe_barcdng_dt`
                    WHERE
                        SHA2(CONCAT(`recid`,'{$mpw_tkn}'),384) = '{$mktn_barcdng_id}'
                ";

                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                $rr = $q->getRowArray();

                $_valid_recid = $rr['recid'];

                $header = $rr['header'];

                $stock_code = $rr['stock_code'];

                $barcde = $rr['barcde'];
                $irb_barcde = $rr['irb_barcde'];
                $srb_barcde = $rr['srb_barcde'];
                $witb_barcde = $rr['witb_barcde'];
                $wob_barcde = $rr['wob_barcde'];
                $pob_barcde = $rr['pob_barcde'];
                $dmg_barcde = $rr['dmg_barcde'];
                $mat_rid = $rr['mat_rid'];
                //$qty = $rr['qty'];
                $convf = $rr['convf'];
                $uom = $rr['uom'];
                $cbm = $rr['cbm'];
                $total_pcs = $rr['total_pcs'];
                $total_amount = $rr['total_amount'];
                $frm_plnt_id = $rr['frm_plnt_id'];
                $frm_wshe_id = $rr['frm_wshe_id'];
                $frm_wshe_sbin_id = $rr['frm_wshe_sbin_id'];
                $frm_wshe_grp_id = $rr['frm_wshe_grp_id'];
                $box_no = $rr['box_no'];
                $remarks = $rr['remarks'];

                //insert in central

                $str = "
                    INSERT INTO
                    {$this->db_erp}.`warehouse_inv_rcv`(
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
                    VALUES(
                        '{$_hd_ctrlno}',
                        '{$header}',
                        '{$stock_code}',
                        '{$irb_barcde}',
                        '{$irb_barcde}',
                        '{$srb_barcde}',
                        '{$witb_barcde}',
                        '{$wob_barcde}',
                        '{$pob_barcde}',
                        '{$dmg_barcde}',
                        '{$frm_plnt_id}',
                        '{$frm_wshe_id}',
                        '{$frm_wshe_sbin_id}',
                        '{$frm_wshe_grp_id}',
                        '{$box_no}',
                        '{$mat_rid}',
                        '{$qty}',
                        '{$convf}',
                        '{$cbm}',
                        '{$convf}',
                        '{$total_amount}',
                        '{$remarks}',
                        '{$cuser}',
                        now()
                    )
                ";

                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                
                /***************** AUDIT LOGS *************************/

                $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CENTRAL_RCVNG','OLD PROCESS SAVING CENTRAL',$_valid_central_header,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                /***************** AUDIT LOGS *************************/

                $str = "
                    SELECT 
                        `recid`
                    FROM
                        {$this->db_erp}.`warehouse_inv_rcv`
                    WHERE
                        `barcde` = '{$irb_barcde}'
                ";

                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                $rr = $q->getRowArray();
                $_valid_central_id = $rr['recid'];


                //get item

                $str = "
                    SELECT 
                        `recid`
                    FROM
                        {$this->db_erp}.`wshe_barcdng_item`
                    WHERE
                        `dt_id` = '{$_valid_recid}'
                ";

                $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                if($q->getNumRows() > 0){

                    //insert w/o loop


                    $str = "
                        INSERT INTO
                        {$this->db_erp}.`warehouse_inv_rcv_item`(
                            `wshe_inv_id`,
                            `mat_rid`,
                            `qty`,
                            `price`,
                            `total_amount`,
                            `muser`,
                            `encd`
                        )
                        SELECT 
                            '{$_valid_central_id}',
                            `mat_rid`,
                            `qty`,
                            `price`,
                            `total_amount`,
                            '{$cuser}',
                            now()
                        FROM
                            {$this->db_erp}.`wshe_barcdng_item`
                        WHERE
                            `dt_id` = '{$_valid_recid}'
                    ";

                    /***************** AUDIT LOGS *************************/

                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CENTRAL_RCVNG_ITEM','',$_valid_central_header,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    /***************** AUDIT LOGS *************************/

                    /*foreach($q->result_array() as $row){

                        $_mat_rid = $row['mat_rid'];
                        $_qty = $row['qty'];
                        $_price = $row['price'];
                        $_total_amount = $row['total_amount'];

                        $str = "
                            INSERT INTO
                            {$this->db_erp}.`warehouse_inv_rcv_item`(
                                `central_id`,
                                `mat_rid`,
                                `qty`,
                                `price`,
                                `total_amount`,
                                `muser`,
                                `encd`
                            )
                            VALUES(
                                {$_valid_central_id},
                                {$_mat_rid},
                                {$_qty},
                                {$_price},
                                {$_total_amount},
                                '{$cuser}',
                                now()
                            )
                        ";

                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        
                        //AUDIT LOGS

                        $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CENTRAL_RCVNG_ITEM','',$_valid_central_header,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                        //AUDIT LOGS

                    }//endforeach*/

                }
                else{

                    // $str = "
                    //     INSERT INTO
                    //     {$this->db_erp}.`warehouse_inv_rcv_item`(
                    //         `central_id`,
                    //         `mat_rid`,
                    //         `qty`,
                    //         `price`,
                    //         `total_amount`,
                    //         `muser`,
                    //         `encd`
                    //     )
                    //     VALUES(
                    //         '{$_valid_central_id}',
                    //         '{$mat_rid}',
                    //         '{$qty}',
                    //         '{$price}',
                    //         '{$total_amount}',
                    //         '{$cuser}',
                    //         now()
                    //     )
                    // ";

                    //$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    
                    /***************** AUDIT LOGS *************************/

                    $this->mylibzdb->user_logs_activity_module($this->db_erp,'SAVE_CENTRAL_RCVNG_ITEM','',$_valid_central_header,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                    /***************** AUDIT LOGS *************************/

                }
            }//endfor

            $data = "<div class=\"alert alert-success\"><strong>SAVE</strong><br>Transaction successfully saved. <br> TRANSACTION NO: <p style=\"color:red;display:inline-block; \">{$central_hd_ctrlno}</p> </div>";
            echo $data;

        }
        
    }
}


} //end main MyMDCustomerModel