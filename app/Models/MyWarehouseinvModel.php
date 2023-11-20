<?php
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Files\File;

class MyWarehouseinvModel extends Model
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

        // Set orderable column fields
        $this->column_order = array(null, 'stock_code','ART_CODE',`ART_DESC`,null,null,'convf','total_pcs_scanned','tamt_scanned','remarks',null,null,'wshe_bin_name','wshe_grp','barcde','box_no',null,'encd',null,'SD_NO');
        // Set searchable column fields
        $this->column_search = array('rcv.`remarks`','rcv.`stock_code`','art.`ART_CODE`','rcv.`SD_NO`','rcv.`witb_barcde`','sbin.`wshe_bin_name`','grp.`wshe_grp`',
    );
        // Set default order
        $this->order = array('encd' => 'desc');
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
    } //endfunc


    public function view_ent_itm_recs(){ 
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
        //IF( rcv.`is_out` = 0,rcv.`qty`,0) qty,
        $strqry = "
        SELECT
        rcv.`recid`,
        sha2(concat(rcv.`recid`,'{$mpw_tkn}'),384) txt_mtknr,
        CASE 
            WHEN rcv.`type` = 'GI'
                THEN '0'
            ELSE
                CASE
                  WHEN 
                  (IFNULL((SELECT done FROM  {$this->db_erp}.`warehouse_shipdoc_hd` WHERE `crpl_code`  = rcv.`SD_NO` ),0)  = 1) 
                     THEN '0'
                ELSE
                      '1'
            END 
        END as qty, 
        rcv.`qty`qty_scanned ,
        rcv.`is_out`,
        rcv.`trx`,
         '' `uprice`,
        rcv.`remarks`,
        pl.`plnt_code`,
        wh.`wshe_code`,
        rcv.`box_no`,
        rcv.`stock_code`,
        art.`ART_CODE`,
        art.`ART_DESC`,
        rcv.`convf`,
        rcv.`total_amount` tamt_scanned,
        '' price,
        rcv.`total_pcs` total_pcs_scanned,
         sbin.`wshe_bin_name`,
         grp.`wshe_grp`,
        rcv.`witb_barcde`  barcde,
        rcv.`muser`,
        rcv.`type`,
        rcv.`SD_NO`,
        rcv.`encd`
        FROM
        {$this->db_erp}.`warehouse_inv_rcv` rcv
        JOIN {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        JOIN {$this->db_erp}.`mst_wshe_bin` sbin 
            ON rcv.`wshe_sbin_id` = sbin.`recid` AND rcv.`wshe_grp_id` = sbin.`wshegrp_id` 
            AND sbin.`plnt_id`  = rcv.`plnt_id` AND sbin.`wshe_id` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_wshe_grp` grp 
            ON rcv.`wshe_grp_id` = grp.`recid` 
            AND grp.`plnt_id`  = rcv.`plnt_id` AND grp.`wshe_id` = rcv.`wshe_id`
        WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}'
        GROUP BY rcv.`witb_barcde` ORDER BY `encd` desc";

        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['counts'] = $qry->getNumRows();

        } else { 
         $data = array();
         $data['rlist'] = '';
         $data['counts'] = 0;

        }
        return $data;
    } //endfunc


   public function generate_report_stkcode(){


        $cuser       = $this->mylibzdb->mysys_user();
        $mpw_tkn     = $this->mylibzdb->mpw_tkn();
        $report_type = $this->request->getVar('report_type');
        $warehouse   = $this->request->getVar('warehouse');
        $filter      = $this->request->getVar('filter');
        $cat_one     = $this->request->getVar('cat_one');
        $cat_one_dt  = $this->request->getVar('cat_one_dt');
        $cat_two     = $this->request->getVar('cat_two');
        $cat_three   = $this->request->getVar('cat_three');
        $cat_four    = $this->request->getVar('cat_four');
        $from_date   = $this->request->getVar('from_date');
        $to_date     = $this->request->getVar('to_date');
        $__rack      = $this->request->getVar('__rack');
        $__bin       = $this->request->getVar('__bin');
        $str_branch = "";
        $chtmljs = "";

        $__fld_area_code = $this->request->getVar('__fld_area_code');
        $mybranch    = $__fld_area_code;
        $myindex = strpos($mybranch,'-');
        if($myindex > 0){
          $mybranch = trim(substr($mybranch,0,$myindex));
        }

        $str_opt = '';
        if($warehouse != "ALL"){
            $wshe_data      = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($warehouse);
         
            $active_plnt_id = $wshe_data['plntID'];
            $active_wshe_id = $wshe_data['whID'];
            $str_opt = "
                WHERE aa.`wshe_id` = '{$active_wshe_id}'
            ";
        }


        $concat = 'WHERE';
        
        $str_cat_one    = '';
        $str_cat_one_dt = '';
        $str_cat_two    = '';
        $str_cat_three  = '';
        $str_rack       = '';
        $str_bin        ='';
        
        if($warehouse != "ALL"){
            $concat = "AND";
        }
        
        if(!empty($warehouse)){
            $concat = "AND";
        }
        if($cat_one != ""){
            $str_cat_one = "{$concat} art.`ART_HIERC1` = '{$cat_one}' ";
        }

        if($cat_one_dt != ""){
            $str_cat_one_dt = "{$concat} art.`ART_PRODT` = '{$cat_one_dt}' "; 
        }

        if($cat_two != ""){
            $str_cat_two = "{$concat} art.`ART_HIERC2` = '{$cat_two}' ";
        }

        if($cat_three != ""){
            $str_cat_three = "{$concat} art.`ART_HIERC3` = '{$cat_three}' "; 
        }

        if($cat_four != ""){
          $str_cat_four = "{$concat} art.`ART_HIERC4` = '{$cat_four}' ";
        }


        //RACK
        if($__rack != ""){
            $whGRPData = $this->mymelibzsys->getWhGroupByname($__rack,$active_wshe_id,$active_plnt_id);
            $valid_grp_id = $whGRPData['recid'];
            $str_rack = "{$concat} aa.`wshe_grp_id` = '{$valid_grp_id}' "; 
        }
        else{

          if($mybranch != ''){
            $str_branch = "{$concat} grp.`wshe_grp` like '%{$mybranch}%' ";
          }

        }


        //BIN
        if($__bin != ""){
            $whSbinData = $this->mymelibzsys->getWhSbinByname($__bin,$valid_grp_id,$active_wshe_id,$active_plnt_id);
            $valid_sbin_id = $whSbinData['recid'];
            $str_bin = "{$concat} aa.`wshe_sbin_id`= '{$valid_sbin_id}' "; 
        }

        $opt_filter = '';

        if($filter == "lessqtyzero"){
            $opt_filter = "
                HAVING `qty` > 0
            ";
        }


        $str_date = '';
        if($to_date != '' && $from_date != ''){

            $this->mymelibzsys->checkDaterange($from_date,$to_date);

            $str_date = "
                {$concat}
                    (DATE(aa.`encd`) >= DATE('{$from_date}') AND DATE(aa.`encd`) <= DATE('{$to_date}'))
                
            ";

        }
        
        if($warehouse == ""){
            $data = array(
                'result' => false,
                'data' => "<div class=\"alert alert-danger\"><strong>Invalid Input</strong><br>Please select warehouse.</div>"
            );

            return $data;
            die();
        }


        //create table first tmp with select

        $tbltemp = $this->db_temp . ".`STOCKCODELIST_".date('Y-m-d').'_'.$this->mylibzsys->random_string(15) . "`";
        $str = "DROP TABLE IF EXISTS {$tbltemp}";
        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $strqry = "
            CREATE TABLE IF NOT EXISTS {$tbltemp}
            SELECT 
                aa.`trx`,
                 aa.`header`,
                CASE 
                  WHEN aa.`type` = 'GI'
                     THEN '0'
                ELSE
                   CASE
                    WHEN 
                    (IFNULL((SELECT done FROM  {$this->db_erp}.`warehouse_shipdoc_hd` WHERE `crpl_code`  = aa.`SD_NO` ),0)  = 1) 
                       THEN '0'
                   ELSE
                        '1'
                   END 
                END as qty,  
                art.`ART_CODE` AS `box_item_code`,
                art.`ART_DESC` AS `box_item_desc`,
                art.`ART_SKU` AS `box_sku`,
                art.`ART_HIERC1`,
                art.`ART_HIERC2`,
                art.`ART_HIERC3`,
                art.`ART_HIERC4`,
                art.`ART_PRODT`,
                art.`ART_DESC_CODE`,
                aa.`recid`,
                aa.`mat_rid`,
                aa.`stock_code`,
                aa.`convf`,
                aa.`remarks`,
                aa.`total_pcs`,

                (SELECT
                    SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount`
                FROM
                    {$this->db_erp}.`warehouse_inv_rcv_item` item
                JOIN
                    {$this->db_erp}.`warehouse_inv_rcv` inv
                ON
                    item.`wshe_inv_id` = inv.`recid`
                JOIN
                    {$this->db_erp}.`mst_article` artt
                ON
                    item.`mat_rid` = artt.`recid`
                WHERE
                    aa.`recid` = inv.`recid`
                ) AS `total_amount`,
                CASE
                    WHEN
                        art.`ART_CODE` LIKE '%ASSTD%'
                    THEN
                        (SELECT
                            SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount`
                        FROM
                            {$this->db_erp}.`warehouse_inv_rcv_item` item
                        JOIN
                            {$this->db_erp}.`warehouse_inv_rcv` inv
                        ON
                            item.`wshe_inv_id` = inv.`recid`
                        JOIN
                            {$this->db_erp}.`mst_article` artt
                        ON
                            item.`mat_rid` = artt.`recid`
                        WHERE
                            aa.`recid` = inv.`recid`
                        ) 
                    ELSE
                        art.`ART_UPRICE`
                END
                AS `ART_UPRICE`,
                aa.`witb_barcde` AS `barcde`,
                aa.`box_no`,
                aa.`encd`,
                aa.`lessFlag`,
                aa.`muser`,
                pl.`plnt_code`, 
                wshe.`wshe_code`, 
                sbin.`wshe_bin_name`,
                grp.`wshe_grp`,
                aa.`type` AS `out_type`,
                aa.`SD_NO`AS `out_header`
            FROM
                {$this->db_erp}.`warehouse_inv_rcv` aa

            JOIN
                {$this->db_erp}.`mst_article` art
            ON
                aa.`mat_rid` = art.`recid`
            JOIN
                {$this->db_erp}.`mst_plant` pl
            ON
                aa.`plnt_id` = pl.`recid`
            JOIN
                {$this->db_erp}.`mst_wshe` wshe
            ON
                aa.`wshe_id` = wshe.`recid`
             JOIN
                {$this->db_erp}.`mst_wshe_bin` sbin
            ON
                aa.`wshe_sbin_id` = sbin.`recid`
             JOIN
                {$this->db_erp}.`mst_wshe_grp` grp
            ON
                aa.`wshe_grp_id` = grp.`recid`
            {$str_opt}
            {$str_cat_one}
            {$str_cat_one_dt}
            {$str_cat_two}
            {$str_cat_three}
            {$str_rack}
            {$str_bin}
            {$str_branch}
            {$str_date}
            {$opt_filter}";
         

        $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        /***************** AUDIT LOGS *************************/
        $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_INV_R_STOCKCODE','',$tbltemp,$strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        //generate file

        $file_name = 'STOCKCODELIST_'.date('Ymd').$this->mylibzsys->random_string(15);
        $mpathdn   = ROOTPATH;
        $_csv_path = '/public/downloads/me/';
        //if(!is_dir($_csv_path)) mkdir($_csv_path, '0755', true);
        $filepath = $mpathdn.$_csv_path.$file_name.'.csv';
        $cfilelnk = site_url() . 'downloads/me/' . $file_name.'.csv'; 


        $str = "
          SELECT *
          INTO OUTFILE '{$filepath}'
          FIELDS TERMINATED BY '\t'
          LINES TERMINATED BY '\r\n'
          FROM(
            SELECT
              'STOCK CODE',
              'ITEM CODE',
              'ITEM DESCRIPTION',
              'PACKAGING',
              'QTY',
              'CONVF',
              'TOTAL PCS',
              'UNIT PRICE',
              'TOTAL AMOUNT',
              'ART_HIERC1',
              'ART_HIERC2',
              'ART_HIERC3',
              'ART_HIERC4',
              'DESC_CODE',
              'ART_PRODT',
              'S. TEXT',
              'PLANT',
              'WAREHOUSE',
              'STORAGE BIN',
              'WAREHOUSE GROUP',
              'BOX BARCODE',
              'BOX NO',
              'OUT TYPE',
              'OUT HEADER',
              'USER',
              'DATE_TIME'
            UNION ALL
            SELECT
              TRIM(IFNULL(REPLACE(REPLACE(`stock_code`,'\r',''),'\n',''),'')) `stock_code`,
              TRIM(IFNULL(REPLACE(REPLACE(`box_item_code`,'\r',''),'\n',''),'')) `box_item_code`,
              TRIM(IFNULL(REPLACE(REPLACE(`box_item_desc`,'\r',''),'\n',''),'')) `box_item_desc`,
              TRIM(IFNULL(REPLACE(REPLACE(`box_sku`,'\r',''),'\n',''),'')) `box_sku`,
              TRIM(IFNULL(REPLACE(REPLACE(`qty`,'\r',''),'\n',''),'')) `qty`,
              TRIM(IFNULL(REPLACE(REPLACE(`convf`,'\r',''),'\n',''),'')) `convf`,
              TRIM(IFNULL(REPLACE(REPLACE(`total_pcs`,'\r',''),'\n',''),'')) `total_pcs`,
              TRIM(IFNULL(REPLACE(REPLACE(`ART_UPRICE`,'\r',''),'\n',''),'')) `ART_UPRICE`,
              TRIM(IFNULL(REPLACE(REPLACE(`total_amount`,'\r',''),'\n',''),'')) `total_amount`,
              TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC1`,'\r',''),'\n',''),'')) `ART_HIERC1`,
              TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC2`,'\r',''),'\n',''),'')) `ART_HIERC2`,
              TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC3`,'\r',''),'\n',''),'')) `ART_HIERC3`,
              TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC4`,'\r',''),'\n',''),'')) `ART_HIERC4`,
              TRIM(IFNULL(REPLACE(REPLACE(`ART_DESC_CODE`,'\r',''),'\n',''),'')) `ART_DESC_CODE`,
              TRIM(IFNULL(REPLACE(REPLACE(`ART_PRODT`,'\r',''),'\n',''),'')) `ART_PRODT`,
              TRIM(IFNULL(REPLACE(REPLACE(`remarks`,'\r',''),'\n',''),'')) `remarks`,
              TRIM(IFNULL(REPLACE(REPLACE(`plnt_code`,'\r',''),'\n',''),'')) `plnt_code`,
              TRIM(IFNULL(REPLACE(REPLACE(`wshe_code`,'\r',''),'\n',''),'')) `wshe_code`,
              TRIM(IFNULL(REPLACE(REPLACE(`wshe_bin_name`,'\r',''),'\n',''),'')) `wshe_bin_name`,
              TRIM(IFNULL(REPLACE(REPLACE(`wshe_grp`,'\r',''),'\n',''),'')) `wshe_grp`,
              TRIM(IFNULL(REPLACE(REPLACE(`barcde`,'\r',''),'\n',''),'')) `barcde`,
              TRIM(IFNULL(REPLACE(REPLACE(`box_no`,'\r',''),'\n',''),'')) `box_no`,
              TRIM(IFNULL(REPLACE(REPLACE(`out_type`,'\r',''),'\n',''),'')) `out_type`,
              TRIM(IFNULL(REPLACE(REPLACE(`out_header`,'\r',''),'\n',''),'')) `out_header`,
              TRIM(IFNULL(REPLACE(REPLACE(`muser`,'\r',''),'\n',''),'')) `muser`,
              TRIM(IFNULL(REPLACE(REPLACE(`encd`,'\r',''),'\n',''),'')) `encd`

            FROM
            {$tbltemp}
          )INV_SUMMARY
        ";

        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        // if(!file_exists($filepath)) { 
        //     $sourceFile     = 'http://192.168.8.38/mynlinksc/downloads/me/' . $file_name.'.csv';
        //     $this->mymelibzsys->saveFileByUrl($sourceFile, $filepath);
        // }

        // $data = array(
        //     'result' => true,
        //     'data' => site_url().'downloads/me/'.$file_name.'.csv'
        // );

        // return $data;


          $chtmljs .= "
                    <a href=\"{$cfilelnk}\" download=" . $file_name ." class='btn btn-dgreen btn-sm col-lg-12' onclick='$(this).remove()'> <i class='bi bi-save'></i> DOWNLOAD IT!</a>        
                    ";
        echo $chtmljs;
   }

    public function generate_report_in(){

        $cuser       = $this->mylibzdb->mysys_user();
        $mpw_tkn     = $this->mylibzdb->mpw_tkn();
        $report_type = $this->request->getVar('report_type');
        $warehouse   = $this->request->getVar('warehouse');
        $filter      = $this->request->getVar('filter');
        $cat_one     = $this->request->getVar('cat_one');
        $cat_one_dt  = $this->request->getVar('cat_one_dt');
        $cat_two     = $this->request->getVar('cat_two');
        $cat_three   = $this->request->getVar('cat_three');
        $cat_four    = $this->request->getVar('cat_four');
        $from_date   = $this->request->getVar('from_date');
        $to_date     = $this->request->getVar('to_date');
        $__rack      = $this->request->getVar('__rack');
        $__bin       = $this->request->getVar('__bin');
        $str_branch = "";

        $__fld_area_code = $this->request->getVar('__fld_area_code');
        $mybranch    = $__fld_area_code;
        $myindex = strpos($mybranch,'-');
        if($myindex > 0){
          $mybranch = trim(substr($mybranch,0,$myindex));
        }
        

        $str_opt = '';
        if($warehouse != "ALL"){
            $wshe_data      = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($warehouse);
         
            $active_plnt_id = $wshe_data['plntID'];
            $active_wshe_id = $wshe_data['whID'];
            $str_opt = "
                WHERE aa.`wshe_id` = '{$active_wshe_id}'
            ";
        }


        $concat = 'WHERE';
        
        $str_cat_one    = '';
        $str_cat_one_dt = '';
        $str_cat_two    = '';
        $str_cat_three  = '';
        $str_rack       = '';
        $str_bin        ='';
        
        if($warehouse != "ALL"){
            $concat = "AND";
        }
        
        if(!empty($warehouse)){
            $concat = "AND";
        }
        if($cat_one != ""){
            $str_cat_one = "{$concat} art.`ART_HIERC1` = '{$cat_one}' ";
        }

        if($cat_one_dt != ""){
            $str_cat_one_dt = "{$concat} art.`ART_PRODT` = '{$cat_one_dt}' "; 
        }

        if($cat_two != ""){
            $str_cat_two = "{$concat} art.`ART_HIERC2` = '{$cat_two}' ";
        }

        if($cat_three != ""){
            $str_cat_three = "{$concat} art.`ART_HIERC3` = '{$cat_three}' "; 
        }

        if($cat_four != ""){
          $str_cat_four = "{$concat} art.`ART_HIERC4` = '{$cat_four}' ";
        }


        //RACK
        if($__rack != ""){
            $whGRPData = $this->mymelibzsys->getWhGroupByname($__rack,$active_wshe_id,$active_plnt_id);
            $valid_grp_id = $whGRPData['recid'];
            $str_rack = "{$concat} aa.`wshe_grp_id` = '{$valid_grp_id}' "; 
        }
        else{

          if($mybranch != ''){
            $str_branch = "{$concat} f.`wshe_grp` like '%{$mybranch}%' ";
          }

        }
        //BIN
        if($__bin != ""){
            $whSbinData = $this->mymelibzsys->getWhSbinByname($__bin,$valid_grp_id,$active_wshe_id,$active_plnt_id);
            $valid_sbin_id = $whSbinData['recid'];
            $str_bin = "{$concat} aa.`wshe_sbin_id`= '{$valid_sbin_id}' "; 
        }

        $opt_filter = '';

        if($filter == "lessqtyzero"){
            $opt_filter = "
                HAVING `qty` > 0
            ";
        }


        $str_date = '';
        if($to_date != '' && $from_date != ''){
            $this->mymelibzsys->checkDaterange($from_date,$to_date);
            $str_date = "
                {$concat}
                    (DATE(aa.`encd`) >= DATE('{$from_date}') AND DATE(aa.`encd`) <= DATE('{$to_date}'))
                
            ";

        }

        if($warehouse == ""){
            $data = array(
                'result' => false,
                'data' => "<div class=\"alert alert-danger\"><strong>Invalid Input</strong><br>Please select warehouse.</div>"
            );

            return $data;
            die();
        }


        //create table first tmp with select

          $tbltemp = $this->db_temp . ".`_WSHEINV_RCVD_".date('Y-m-d').'_'.$this->mylibzsys->random_string(15) . "`";
          $str = "DROP TABLE IF EXISTS {$tbltemp}";
          $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

 
    $strqry = "
    CREATE TABLE IF NOT EXISTS {$tbltemp}
    SELECT
        IFNULL(aa.`trx`,'WINV') trx,
        aa.`stock_code`,
        aa.`header`,
        REPLACE(REPLACE(REPLACE(art.`ART_CODE`, ' ', ''), '\t', ''), '\n', '') AS `ART_CODE`,
        REPLACE(REPLACE(REPLACE(art.`ART_DESC`, ' ', ''), '\t', ''), '\n', '') AS `ART_DESC`,
        REPLACE(REPLACE(REPLACE(art.`ART_SKU`, ' ', ''), '\t', ''), '\n', '') AS `ART_SKU`,
        REPLACE(REPLACE(REPLACE(art.`ART_HIERC1`, ' ', ''), '\t', ''), '\n', '') AS `ART_HIERC1`,
        REPLACE(REPLACE(REPLACE(art.`ART_HIERC2`, ' ', ''), '\t', ''), '\n', '') AS `ART_HIERC2`,
        REPLACE(REPLACE(REPLACE(art.`ART_HIERC3`, ' ', ''), '\t', ''), '\n', '') AS `ART_HIERC3`,
        REPLACE(REPLACE(REPLACE(art.`ART_HIERC4`, ' ', ''), '\t', ''), '\n', '') AS `ART_HIERC4`,
        REPLACE(REPLACE(REPLACE(art.`ART_PRODT`, ' ', ''), '\t', ''), '\n', '') AS `ART_PRODT`,
        REPLACE(REPLACE(REPLACE(art.`ART_DESC_CODE`, ' ', ''), '\t', ''), '\n', '') AS `ART_DESC_CODE`,
        CASE
            WHEN
                aa.`lessFlag` = 1
            THEN
                '-'
            ELSE
                aa.`qty`
        END
        AS `qty`,
        aa.`convf`,
        aa.`total_pcs`,
        (SELECT
            SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount`
        FROM
            {$this->db_erp}.`warehouse_inv_rcv_item` item
        JOIN
            {$this->db_erp}.`warehouse_inv_rcv` inv
        ON
            item.`wshe_inv_id` = inv.`recid`
        JOIN
            {$this->db_erp}.`mst_article` artt
        ON
            item.`mat_rid` = artt.`recid`
        WHERE
            aa.`wshe_inv_id` = inv.`recid`
        ) AS `total_amount`,
        CASE
            WHEN
                art.`ART_CODE` LIKE '%ASSTD%'
            THEN
                (SELECT
                    SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount`
                FROM
                    {$this->db_erp}.`warehouse_inv_rcv_item` item
                JOIN
                    {$this->db_erp}.`warehouse_inv_rcv` inv
                ON
                    item.`wshe_inv_id` = inv.`recid`
                JOIN
                    {$this->db_erp}.`mst_article` artt
                ON
                    item.`mat_rid` = artt.`recid`
                WHERE
                    aa.`wshe_inv_id` = inv.`recid`
                ) 
            ELSE
                art.`ART_UPRICE`
        END
        AS `ART_UPRICE`,
        aa.`barcde`,
        aa.`box_no`,
        aa.`encd`,
        b.`plnt_code`,
        c.`wshe_code`,
        REPLACE(REPLACE(REPLACE(f.`wshe_grp`, ' ', ''), '\t', ''), '\n', '') AS `wshe_grp`,
        REPLACE(REPLACE(REPLACE(e.`wshe_bin_name`, ' ', ''), '\t', ''), '\n', '') AS `wshe_bin_name`,
        REPLACE(REPLACE(REPLACE(aa.`remarks`, ' ', ''), '\t', ''), '\n', '') AS `remarks`
    FROM
        {$this->db_erp}.`warehouse_inv_rcv_logs` aa
    JOIN
        {$this->db_erp}.`mst_plant` b
    ON
        aa.`plnt_id` = b.`recid`
    JOIN
        {$this->db_erp}.`mst_wshe` c
    ON
        aa.`wshe_id` = c.`recid`    
    JOIN
        {$this->db_erp}.`mst_article` art
    ON
        aa.`mat_rid` = art.`recid`
    JOIN
        {$this->db_erp}.`mst_wshe_bin` e
    ON
        aa.`wshe_sbin_id` = e.`recid`
    JOIN
        {$this->db_erp}.`mst_wshe_grp` f
    ON
        e.`wshegrp_id` = f.`recid` 
    {$str_opt}
    {$str_cat_one}
    {$str_cat_one_dt}
    {$str_cat_two}
    {$str_cat_three}
    {$str_rack}
    {$str_bin}
    {$str_date}
    {$str_branch}
    GROUP BY
        aa.`plnt_id`,aa.`wshe_id`,aa.`witb_barcde` {$opt_filter} ";

    $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    /***************** AUDIT LOGS *************************/
    $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_INV_R_IN','',$tbltemp,$strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    //generate file

    //var_dump($strqry);
      
      $file_name = 'WAREHOUSE_IN_'.date('Ymd').$this->mylibzsys->random_string(15);
      $mpathdn   = ROOTPATH;
      $_csv_path = '/public/downloads/me/';
       //if(!is_dir($_csv_path)) mkdir($_csv_path, '0755', true);
       $filepath = $mpathdn.$_csv_path.$file_name.'.csv';
       $cfilelnk = site_url() . 'downloads/me/' . $file_name.'.csv'; 



      $str = "
               SELECT *
               FROM(
                 SELECT
                'TRX_TAG',
                   'TRANSFER/GR CODE',
                   'STOCK_CODE',
                   'BOX_ITEM_CODE',
                   'BOX_ITEM_DESC',
                   'PACKAGING',
                   'QTY',
                   'CONVF',
                   'TOTAL_PCS',
                   'TOTAL_AMOUNT',
                   'ART_HIERC1',
                   'ART_HIERC2',
                   'ART_HIERC3',
                   'ART_HIERC4',
                   'DESC_CODE',
                   'ART_PRODT',
                   'BARCODE',
                   'BOX_NO',
                   'PLANT',
                   'WAREHOUSE',
                   'RACK',
                   'BIN',
                   'STEXT',
                   'DATE TIME'
                 UNION ALL
                 SELECT
                        TRIM(IFNULL(REPLACE(REPLACE(`trx`,'\r',''),'\n',''),'')) `trx`,
                   TRIM(IFNULL(REPLACE(REPLACE(`header`,'\r',''),'\n',''),'')) `header`,
                   TRIM(IFNULL(REPLACE(REPLACE(`stock_code`,'\r',''),'\n',''),'')) `stock_code`,
                   TRIM(IFNULL(REPLACE(REPLACE(`ART_CODE`,'\r',''),'\n',''),'')) `ART_CODE`,
                   TRIM(IFNULL(REPLACE(REPLACE(`ART_DESC`,'\r',''),'\n',''),'')) `ART_DESC`,
                   TRIM(IFNULL(REPLACE(REPLACE(`ART_SKU`,'\r',''),'\n',''),'')) `ART_SKU`,
                   TRIM(IFNULL(REPLACE(REPLACE(`qty`,'\r',''),'\n',''),'')) `qty`,
                   TRIM(IFNULL(REPLACE(REPLACE(`convf`,'\r',''),'\n',''),'')) `convf`,
                   TRIM(IFNULL(REPLACE(REPLACE(`total_pcs`,'\r',''),'\n',''),'')) `total_pcs`,
                   TRIM(IFNULL(REPLACE(REPLACE(`total_amount`,'\r',''),'\n',''),'')) `total_amount`,
                   TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC1`,'\r',''),'\n',''),'')) `ART_HIERC1`,
                   TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC2`,'\r',''),'\n',''),'')) `ART_HIERC2`,
                   TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC3`,'\r',''),'\n',''),'')) `ART_HIERC3`,
                   TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC4`,'\r',''),'\n',''),'')) `ART_HIERC4`,
                   TRIM(IFNULL(REPLACE(REPLACE(`ART_DESC_CODE`,'\r',''),'\n',''),'')) `ART_DESC_CODE`,
                   TRIM(IFNULL(REPLACE(REPLACE(`ART_PRODT`,'\r',''),'\n',''),'')) `ART_PRODT`,
                   TRIM(IFNULL(REPLACE(REPLACE(`barcde`,'\r',''),'\n',''),'')) `barcde`,
                   TRIM(IFNULL(REPLACE(REPLACE(`box_no`,'\r',''),'\n',''),'')) `box_no`,
                   TRIM(IFNULL(REPLACE(REPLACE(`plnt_code`,'\r',''),'\n',''),'')) `plnt_code`,
                   TRIM(IFNULL(REPLACE(REPLACE(`wshe_code`,'\r',''),'\n',''),'')) `wshe_code`,
                   TRIM(REPLACE(REPLACE(`wshe_grp`,'\r',''),'\n','')) AS `wshe_grp`,
                   TRIM(REPLACE(REPLACE(`wshe_bin_name`,'\r',''),'\n','')) AS `wshe_bin_name`,
                   TRIM(REPLACE(REPLACE(`remarks`,'\r',''),'\n','')) AS `remarks`,
                   TRIM(REPLACE(REPLACE(`encd`,'\r',''),'\n','')) AS `encd`
                 FROM
                 {$tbltemp}
               ) INV_SUMMARY

               INTO OUTFILE '{$filepath}'
               FIELDS TERMINATED BY '\t'
               LINES TERMINATED BY '\r';
             ";

    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

     $chtmljs = "
                    <a href=\"{$cfilelnk}\" download=" . $file_name ." class='btn btn-dgreen btn-sm col-lg-12' onclick='$(this).remove()' > <i class='bi bi-save'></i> DOWNLOAD IT!</a>        
                    ";
        echo $chtmljs;

   }

    public function generate_report_out(){

        $cuser       = $this->mylibzdb->mysys_user();
        $mpw_tkn     = $this->mylibzdb->mpw_tkn();
        $report_type = $this->request->getVar('report_type');
        $warehouse   = $this->request->getVar('warehouse');
        $filter      = $this->request->getVar('filter');
        $cat_one     = $this->request->getVar('cat_one');
        $cat_one_dt  = $this->request->getVar('cat_one_dt');
        $cat_two     = $this->request->getVar('cat_two');
        $cat_three   = $this->request->getVar('cat_three');
        $cat_four    = $this->request->getVar('cat_four');
        $from_date   = $this->request->getVar('from_date');
        $to_date     = $this->request->getVar('to_date');
        $__rack      = $this->request->getVar('__rack');
        $__bin       = $this->request->getVar('__bin');
        $str_branch = "";

        $__fld_area_code = $this->request->getVar('__fld_area_code');
        $mybranch    = $__fld_area_code;
        $myindex = strpos($mybranch,'-');
        if($myindex > 0){
          $mybranch = trim(substr($mybranch,0,$myindex));
        }
        
        $str_opt = '';
        if($warehouse != "ALL"){
            $wshe_data      = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($warehouse);
         
            $active_plnt_id = $wshe_data['plntID'];
            $active_wshe_id = $wshe_data['whID'];
            $str_opt = "
                WHERE aa.`wshe_id` = '{$active_wshe_id}'
            ";
        }


        $concat = 'WHERE';
        
        $str_cat_one    = '';
        $str_cat_one_dt = '';
        $str_cat_two    = '';
        $str_cat_three  = '';
        $str_rack       = '';
        $str_bin        ='';
        
        if($warehouse != "ALL"){
            $concat = "AND";
        }
        
        if(!empty($warehouse)){
            $concat = "AND";
        }
        if($cat_one != ""){
            $str_cat_one = "{$concat} art.`ART_HIERC1` = '{$cat_one}' ";
        }

        if($cat_one_dt != ""){
            $str_cat_one_dt = "{$concat} art.`ART_PRODT` = '{$cat_one_dt}' "; 
        }

        if($cat_two != ""){
            $str_cat_two = "{$concat} art.`ART_HIERC2` = '{$cat_two}' ";
        }

        if($cat_three != ""){
            $str_cat_three = "{$concat} art.`ART_HIERC3` = '{$cat_three}' "; 
        }

        if($cat_four != ""){
          $str_cat_four = "{$concat} art.`ART_HIERC4` = '{$cat_four}' ";
        }


        //RACK
        if($__rack != ""){
            $whGRPData = $this->mymelibzsys->getWhGroupByname($__rack,$active_wshe_id,$active_plnt_id);
            $valid_grp_id = $whGRPData['recid'];
            $str_rack = "{$concat} aa.`wshe_grp_id` = '{$valid_grp_id}' "; 
        }
        else{

          if($mybranch != ''){
            $str_branch = "{$concat} f.`wshe_grp` like '%{$mybranch}%' ";
          }

        }
        //BIN
        if($__bin != ""){
            $whSbinData = $this->mymelibzsys->getWhSbinByname($__bin,$valid_grp_id,$active_wshe_id,$active_plnt_id);
            $valid_sbin_id = $whSbinData['recid'];
            $str_bin = "{$concat} aa.`wshe_sbin_id`= '{$valid_sbin_id}' "; 
        }

        $opt_filter = '';

        if($filter == "lessqtyzero"){
            $opt_filter = "
                AND `qty` > 0
            ";
        }

        $str_date  = '';
        $str_date2 = '';
        if($to_date != '' && $from_date != ''){
            $this->mymelibzsys->checkDaterange($from_date,$to_date);
            $str_date = "
                {$concat}
                    (DATE(hd.`done_date`) >= DATE('{$from_date}') AND DATE(hd.`done_date`) <= DATE('{$to_date}'))
                
            ";
            $str_date2 = "
                {$concat}
                    (DATE(hd.`apprvd_date`) >= DATE('{$from_date}') AND DATE(hd.`apprvd_date`) <= DATE('{$to_date}'))
                
            ";

        }

        if($warehouse == ""){
            $data = array(
                'result' => false,
                'data' => "<div class=\"alert alert-danger\"><strong>Invalid Input</strong><br>Please select warehouse.</div>"
            );

            return $data;
            die();
        }


        //create table first tmp with select

          $tbltemp = $this->db_temp . ".`_WSHEINV_RCVD_".date('Y-m-d').'_'.$this->mylibzsys->random_string(15) . "`";
          $str = "DROP TABLE IF EXISTS {$tbltemp}";
          $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

          $strqry = "
          CREATE TABLE IF NOT EXISTS {$tbltemp}
          SELECT
              aa.`trx`,
              aa.`header`,
              aa.`stock_code`,
              REPLACE(REPLACE(REPLACE(art.`ART_CODE`, ' ', ''), '\t', ''), '\n', '') AS `ART_CODE`,
              REPLACE(REPLACE(REPLACE(art.`ART_DESC`, ' ', ''), '\t', ''), '\n', '') AS `ART_DESC`,
              REPLACE(REPLACE(REPLACE(art.`ART_SKU`, ' ', ''), '\t', ''), '\n', '') AS `ART_SKU`,
              REPLACE(REPLACE(REPLACE(art.`ART_HIERC1`, ' ', ''), '\t', ''), '\n', '') AS `ART_HIERC1`,
              REPLACE(REPLACE(REPLACE(art.`ART_HIERC2`, ' ', ''), '\t', ''), '\n', '') AS `ART_HIERC2`,
              REPLACE(REPLACE(REPLACE(art.`ART_HIERC3`, ' ', ''), '\t', ''), '\n', '') AS `ART_HIERC3`,
              REPLACE(REPLACE(REPLACE(art.`ART_HIERC4`, ' ', ''), '\t', ''), '\n', '') AS `ART_HIERC4`,
              REPLACE(REPLACE(REPLACE(art.`ART_PRODT`, ' ', ''), '\t', ''), '\n', '') AS `ART_PRODT`,
              REPLACE(REPLACE(REPLACE(art.`ART_DESC_CODE`, ' ', ''), '\t', ''), '\n', '') AS `ART_DESC_CODE`,
              aa.`qty` AS `qty`,
              aa.`convf`,
              aa.`total_pcs`,
              (SELECT
                  SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount`
              FROM
                  {$this->db_erp}.`warehouse_shipdoc_item` item
              JOIN
                  {$this->db_erp}.`warehouse_shipdoc_dt` inv
              ON
                  item.`wshe_out_id` = inv.`recid`
              JOIN
                  {$this->db_erp}.`mst_article` artt
              ON
                  item.`mat_rid` = artt.`recid`
              WHERE
                  aa.`recid` = inv.`recid`
              ) AS `total_amount`,
              CASE
                  WHEN
                      art.`ART_CODE` LIKE '%ASSTD%'
                  THEN
                      (SELECT
                          SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount`
                      FROM
                          {$this->db_erp}.`warehouse_shipdoc_item` item
                      JOIN
                          {$this->db_erp}.`warehouse_shipdoc_dt` inv
                      ON
                          item.`wshe_out_id` = inv.`recid`
                      JOIN
                          {$this->db_erp}.`mst_article` artt
                      ON
                          item.`mat_rid` = artt.`recid`
                      WHERE
                          aa.`recid` = inv.`recid`
                      ) 
                  ELSE
                      art.`ART_UPRICE`
              END
              AS `ART_UPRICE`,
              aa.`wob_barcde` as `barcde`,
              aa.`box_no`,
              aa.`is_out`,
              hd.`done`,
              IF(hd.`done_date` = '0000-00-00 00:00:00',hd.`done_date`,hd.`done_date`) AS `encd`,
              b.`plnt_code`,
              c.`wshe_code`,
              REPLACE(REPLACE(REPLACE(f.`wshe_grp`, ' ', ''), '\t', ''), '\n', '') AS `wshe_grp`,
              REPLACE(REPLACE(REPLACE(e.`wshe_bin_name`, ' ', ''), '\t', ''), '\n', '') AS `wshe_bin_name`,
              REPLACE(REPLACE(REPLACE(aa.`remarks`, ' ', ''), '\t', ''), '\n', '') AS `remarks`,
              hd.`me_remk`
          FROM
              {$this->db_erp}.`warehouse_shipdoc_dt` aa
          JOIN
              {$this->db_erp}.`warehouse_shipdoc_hd` hd
          ON
              hd.`crpl_code` = aa.`header`
          JOIN
              {$this->db_erp}.`mst_plant` b
          ON
              aa.`plnt_id` = b.`recid`
          JOIN
              {$this->db_erp}.`mst_wshe` c
          ON
              aa.`wshe_id` = c.`recid`    
          JOIN
              {$this->db_erp}.`mst_article` art
          ON
              aa.`mat_rid` = art.`recid`
          JOIN
              {$this->db_erp}.`mst_wshe_bin` e
          ON
              aa.`wshe_sbin_id` = e.`recid`
          JOIN
              {$this->db_erp}.`mst_wshe_grp` f
          ON
              e.`wshegrp_id` = f.`recid` 
          {$str_opt}
          AND hd.`done` = 1
          {$str_cat_one}
          {$str_cat_one_dt}
          {$str_cat_two}
          {$str_cat_three}
          {$str_rack}
          {$str_bin}
          {$str_date}
          {$str_branch}
          HAVING aa.`is_out` = 1 {$opt_filter}

          UNION ALL

           SELECT aa.`trx`, aa.`header`, aa.`stock_code`, REPLACE(REPLACE(REPLACE(art.`ART_CODE`, ' ', ''), ' ', ''), ' ', '') AS `ART_CODE`, 
           REPLACE(REPLACE(REPLACE(art.`ART_DESC`, ' ', ''), ' ', ''), ' ', '') AS `ART_DESC`, 
           REPLACE(REPLACE(REPLACE(art.`ART_SKU`, ' ', ''), ' ', ''), ' ', '') AS `ART_SKU`, 
           REPLACE(REPLACE(REPLACE(art.`ART_HIERC1`, ' ', ''), ' ', ''), ' ', '') AS `ART_HIERC1`, 
           REPLACE(REPLACE(REPLACE(art.`ART_HIERC2`, ' ', ''), ' ', ''), ' ', '') AS `ART_HIERC2`, 
           REPLACE(REPLACE(REPLACE(art.`ART_HIERC3`, ' ', ''), ' ', ''), ' ', '') AS `ART_HIERC3`, 
           REPLACE(REPLACE(REPLACE(art.`ART_HIERC4`, ' ', ''), ' ', ''), ' ', '') AS `ART_HIERC4`, 
           REPLACE(REPLACE(REPLACE(art.`ART_PRODT`, ' ', ''), ' ', ''), ' ', '') AS `ART_PRODT`, 
           REPLACE(REPLACE(REPLACE(art.`ART_DESC_CODE`, ' ', ''), ' ', ''), ' ', '') AS `ART_DESC_CODE`, 
           aa.`qty` AS `qty`, aa.`convf`, aa.`total_pcs`, 
              (
               SELECT SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount` 
               FROM 
                 {$this->db_erp}.`warehouse_inv_rcv_item` item 
               JOIN 
                 {$this->db_erp}.`warehouse_gi_dt` inv 
               ON 
                  item.`witb_barcde` = inv.`witb_barcde` 
               JOIN 
                 {$this->db_erp}.`mst_article` artt 
               ON 
                  item.`mat_rid` = artt.`recid` 
               WHERE aa.`witb_barcde` = inv.`witb_barcde` ) 
           AS `total_amount`, 
           
           CASE WHEN art.`ART_CODE` LIKE '%ASSTD%' 
               THEN(
               SELECT 
                  SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount` 
               FROM 
                 {$this->db_erp}.`warehouse_inv_rcv_item` item 
               JOIN 
                 {$this->db_erp}.`warehouse_gi_dt` inv 
               ON 
                  item.`witb_barcde` = inv.`witb_barcde` 
               JOIN 
                 {$this->db_erp}.`mst_article` artt 
               ON 
                  item.`mat_rid` = artt.`recid`
               WHERE aa.`witb_barcde` = inv.`witb_barcde`) 
               ELSE art.`ART_UPRICE` 
           END AS `ART_UPRICE`,
            aa.`wob_barcde` AS `barcde`, aa.`box_no`, aa.`is_out`, IF(hd.`is_approved` = 'Y',1,0) as `done`, 
          hd.`apprvd_date`  AS `encd`,
          b.`plnt_code`, c.`wshe_code`, 
          REPLACE(REPLACE(REPLACE(f.`wshe_grp`, ' ', ''), ' ', ''), ' ', '') AS `wshe_grp`, 
          REPLACE(REPLACE(REPLACE(e.`wshe_bin_name`, ' ', ''), ' ', ''), ' ', '') AS `wshe_bin_name`, 
          REPLACE(REPLACE(REPLACE(aa.`remarks`, ' ', ''), ' ', ''), ' ', '') AS `remarks`, hd.`remarks` 
           FROM {$this->db_erp}.`warehouse_gi_dt` aa 
           JOIN {$this->db_erp}.`warehouse_gi_hd` hd ON hd.`header` = aa.`trx` 
           JOIN {$this->db_erp}.`mst_plant` b ON aa.`plnt_id` = b.`recid` 
           JOIN {$this->db_erp}.`mst_wshe` c ON aa.`wshe_id` = c.`recid` 
           JOIN {$this->db_erp}.`mst_article` art ON aa.`mat_rid` = art.`recid` 
           JOIN {$this->db_erp}.`mst_wshe_bin` e ON aa.`wshe_sbin_id` = e.`recid` 
           JOIN {$this->db_erp}.`mst_wshe_grp` f ON e.`wshegrp_id` = f.`recid` 
           {$str_opt} 
           AND hd.`is_approved` = 'Y' 
           {$str_cat_one}
           {$str_cat_one_dt}
           {$str_cat_two}
           {$str_cat_three}
           {$str_rack}
           {$str_bin}
           {$str_date2}
           {$str_branch}
           {$opt_filter}
           -- GROUP BY aa.`plnt_id`,aa.`wshe_id`,aa.`wob_barcde`
           ";

        
    $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    /***************** AUDIT LOGS *************************/
    $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_INV_R_OUT','',$tbltemp,$strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    //generate file

    //var_dump($strqry);
      
      $file_name = 'WAREHOUSE_OUT_'.date('Ymd').$this->mylibzsys->random_string(15);
      $mpathdn   = ROOTPATH;
      $_csv_path = '/public/downloads/me/';
      //if(!is_dir($_csv_path)) mkdir($_csv_path, '0755', true);
      $filepath = $mpathdn.$_csv_path.$file_name.'.csv';
      $cfilelnk = site_url() . 'downloads/me/' . $file_name.'.csv'; 


                $str = "
                  SELECT *
                  FROM(
                    SELECT
                    'TRX_TAG',
                    'HEADER',
                      'STOCK_CODE',
                      'BOX_ITEM_CODE',
                      'BOX_ITEM_DESC',
                      'PACKAGING',
                      'QTY',
                      'CONVF',
                      'TOTAL_PCS',
                      'TOTAL_AMOUNT',
                      'BARCODE',
                      'BOX_NO',
                      'ART_HIERC1',
                      'ART_HIERC2',
                      'ART_HIERC3',
                      'ART_HIERC4',
                      'DESC_CODE',
                      'ART_PRODT',
                      'PLANT',
                      'WAREHOUSE',
                      'RACK',
                      'BIN',
                      'STEXT',
                      'DATETIME',
                      'REMARKS'
                    UNION ALL
                    SELECT
                    TRIM(IFNULL(REPLACE(REPLACE(`trx`,'\r',''),'\n',''),'')) `trx`,
                    TRIM(IFNULL(REPLACE(REPLACE(`header`,'\r',''),'\n',''),'')) `header`,
                      TRIM(IFNULL(REPLACE(REPLACE(`stock_code`,'\r',''),'\n',''),'')) `stock_code`,
                      TRIM(IFNULL(REPLACE(REPLACE(`ART_CODE`,'\r',''),'\n',''),'')) `ART_CODE`,
                      TRIM(IFNULL(REPLACE(REPLACE(`ART_DESC`,'\r',''),'\n',''),'')) `ART_DESC`,
                      TRIM(IFNULL(REPLACE(REPLACE(`ART_SKU`,'\r',''),'\n',''),'')) `ART_SKU`,
                      TRIM(IFNULL(REPLACE(REPLACE(`qty`,'\r',''),'\n',''),'')) `qty`,
                      TRIM(IFNULL(REPLACE(REPLACE(`convf`,'\r',''),'\n',''),'')) `convf`,
                      TRIM(IFNULL(REPLACE(REPLACE(`total_pcs`,'\r',''),'\n',''),'')) `total_pcs`,
                      TRIM(IFNULL(REPLACE(REPLACE(`total_amount`,'\r',''),'\n',''),'')) `total_amount`,
                      TRIM(IFNULL(REPLACE(REPLACE(`barcde`,'\r',''),'\n',''),'')) `barcde`,
                      TRIM(IFNULL(REPLACE(REPLACE(`box_no`,'\r',''),'\n',''),'')) `box_no`,
                      TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC1`,'\r',''),'\n',''),'')) `ART_HIERC1`,
                      TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC2`,'\r',''),'\n',''),'')) `ART_HIERC2`,
                      TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC3`,'\r',''),'\n',''),'')) `ART_HIERC3`,
                      TRIM(IFNULL(REPLACE(REPLACE(`ART_HIERC4`,'\r',''),'\n',''),'')) `ART_HIERC4`,
                      TRIM(IFNULL(REPLACE(REPLACE(`ART_DESC_CODE`,'\r',''),'\n',''),'')) `ART_DESC_CODE`,
                      TRIM(IFNULL(REPLACE(REPLACE(`ART_PRODT`,'\r',''),'\n',''),'')) `ART_PRODT`,
                      TRIM(IFNULL(REPLACE(REPLACE(`plnt_code`,'\r',''),'\n',''),'')) `plnt_code`,
                      TRIM(IFNULL(REPLACE(REPLACE(`wshe_code`,'\r',''),'\n',''),'')) `wshe_code`,
                      TRIM(REPLACE(REPLACE(`wshe_grp`,'\r',''),'\n','')) AS `wshe_grp`,
                      TRIM(REPLACE(REPLACE(`wshe_bin_name`,'\r',''),'\n','')) AS `wshe_bin_name`,
                      TRIM(REPLACE(REPLACE(`remarks`,'\r',''),'\n','')) AS `remarks`,
                      TRIM(REPLACE(REPLACE(`encd`,'\r',''),'\n','')) AS `encd`,
                      TRIM(REPLACE(REPLACE(`me_remk`,'\r',''),'\n','')) AS `me_remk`
                    FROM
                    {$tbltemp}
                  ) INV_SUMMARY

                  INTO OUTFILE '{$filepath}'
                  FIELDS TERMINATED BY '\t'
                  LINES TERMINATED BY '\n\r';
                ";

    $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    // if(!file_exists($filepath)) { 
    //     $sourceFile     = 'http://192.168.8.38/mynlinksc/downloads/me/' . $file_name.'.csv';
    //     $this->mymelibzsys->saveFileByUrl($sourceFile, $filepath);
    // }

     $chtmljs = "
                <a href=\"{$cfilelnk}\" download=" . $file_name ." class='btn btn-dgreen btn-sm col-lg-12' onclick='$(this).remove()'> <i class='bi bi-save'></i> DOWNLOAD IT!</a>        
                    ";
        echo $chtmljs;

   }

    public function generate_report_summary(){
     $data = array(
            'result' => false,
            'data' => "Ongoing"
        );

    return $data;
    }


    public function incoming_items(){
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
           dt.`recid`,
           dt.`stock_code`,
           dt.`qty`,
           dt.`convf`,
           dt.`total_pcs`,
           dt.`total_amount`,
           dt.`barcde`,
           art.`ART_CODE`,
           art.`ART_DESC`,
           art.`ART_SKU`,
           pl.`plnt_code`,
           wh.`wshe_code`,
           '' barc_type,
           '' price,
           '' tamt 
        FROM
        {$this->db_erp}.`wshe_barcdng_dt` dt
        JOIN {$this->db_erp}.`mst_plant` pl ON pl.`recid` = dt.`to_plnt_id`
        JOIN {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = dt.`to_wshe_id`
        JOIN {$this->db_erp}.`mst_article` art ON art.`recid` =  dt.`mat_rid`
        LEFT JOIN  {$this->db_erp}.`warehouse_inv_rcv` rcv  ON dt.`stock_code` = rcv.`stock_code`  
        AND  dt.`to_plnt_id` = rcv.`plnt_id` AND  dt.`to_wshe_id` = rcv.`wshe_id`
        WHERE rcv.`stock_code` IS NULL AND (dt.`to_plnt_id` = '{$plntID}' AND  dt.`to_wshe_id` = '{$whID}')
        GROUP BY dt.`stock_code`,dt.`to_plnt_id`,dt.`to_wshe_id`";

        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();

        } else { 
         $data = array();
         $data['rlist'] = '';

        }
        return $data;
    } //endfunc

    public function outbound_items(){
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
        aa.`recid`,
        aa.`header`,
        aa.`stock_code`,
        art.`ART_CODE` AS `ART_CODE`,
        art.`ART_DESC` AS `ART_DESC`,
        art.`ART_SKU` AS `ART_SKU`,
        aa.`qty` AS `qty`,
        aa.`convf`,
        aa.`total_pcs`,
        (SELECT
            SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount`
        FROM
            {$this->db_erp}.`warehouse_shipdoc_item` item
        JOIN
            {$this->db_erp}.`warehouse_shipdoc_dt` inv
        ON
            item.`wshe_out_id` = inv.`recid`
        JOIN
            {$this->db_erp}.`mst_article` artt
        ON
            item.`mat_rid` = artt.`recid`
        WHERE
            aa.`recid` = inv.`recid`
        ) AS `total_amount`,
        CASE
            WHEN
                art.`ART_CODE` LIKE '%ASSTD%'
            THEN
                (SELECT
                    SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount`
                FROM
                    {$this->db_erp}.`warehouse_shipdoc_item` item
                JOIN
                    {$this->db_erp}.`warehouse_shipdoc_dt` inv
                ON
                    item.`wshe_out_id` = inv.`recid`
                JOIN
                    {$this->db_erp}.`mst_article` artt
                ON
                    item.`mat_rid` = artt.`recid`
                WHERE
                    aa.`recid` = inv.`recid`
                ) 
            ELSE
                art.`ART_UPRICE`
        END
    AS `ART_UPRICE`,
    aa.`muser`,
    aa.`encd`,
    aa.`wob_barcde` as `barcde`,
    aa.`box_no`,
    aa.`encd`,
    b.`plnt_code`,
    c.`wshe_code`,
    f.`wshe_grp` AS `wshe_grp`,
    e.`wshe_bin_name` AS `wshe_bin_name`,
    aa.`remarks` AS `remarks`
    FROM
        {$this->db_erp}.`warehouse_shipdoc_dt` aa
    JOIN
        {$this->db_erp}.`mst_plant` b
    ON
        aa.`plnt_id` = b.`recid`
    JOIN
        {$this->db_erp}.`mst_wshe` c
    ON
        aa.`wshe_id` = c.`recid`    
    JOIN
        {$this->db_erp}.`mst_article` art
    ON
        aa.`mat_rid` = art.`recid`
    JOIN
        {$this->db_erp}.`mst_wshe_bin` e
    ON
        aa.`wshe_sbin_id` = e.`recid`
    JOIN
        {$this->db_erp}.`mst_wshe_grp` f
    ON
    e.`wshegrp_id` = f.`recid` 
    WHERE  aa.`plnt_id` = '{$plntID}' AND  aa.`wshe_id` = '{$whID}'
    GROUP BY aa.`wob_barcde`,aa.`plnt_id`,aa.`wshe_id`";

        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();

        } else { 
         $data = array();
         $data['rlist'] = '';

        }
        return $data;
    } //endfunc


      public function get_wshe_inv_barcode(){

      $mpw_tkn = $this->mylibzdb->mpw_tkn();
      $terms = $this->request->getVar('term');
      $frm_wshe_grp_id = $this->request->getVar('frm_wshe_grp_id');
      $frm_wshe_sbin_id = $this->request->getVar('frm_wshe_sbin_id');
      $mtkn_whse = $this->request->getVar('mtkn_uid');

     //get warehouse id 
     $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
     $whID = $wshedata['whID'];
     $plntID = $wshedata['plntID'];
     // warehouse end

     $sbinData  = $this->mymelibzsys->getWhBinDetailsByTkn($frm_wshe_sbin_id);
     $sbinID    = $sbinData['recid'];
     $rackgrpID = $sbinData['wshegrp_id'];

      $str = "
        SELECT
          SUM(a.`qty`) AS `qty_rcv`,
          0 AS `qty_out`,
          SUM(a.`qty`)  AS `qty_inv`,
          a.*,
          b.`plnt_code`,
          c.`wshe_code`,
          d.`ART_CODE`,
          d.`ART_DESC`,
          d.`ART_SKU`,
          e.`wshe_bin_name`,
          f.`wshe_grp`
        FROM
          {$this->db_erp}.`warehouse_inv_rcv` a
        JOIN
          {$this->db_erp}.`mst_plant` b
        ON
          a.`plnt_id` = b.`recid`
        JOIN
          {$this->db_erp}.`mst_wshe` c
        ON
          a.`wshe_id` = c.`recid`
        JOIN
          {$this->db_erp}.`mst_article` d
        ON
          a.`mat_rid` = d.`recid`
        JOIN
          {$this->db_erp}.`mst_wshe_bin` e
        ON
          a.`wshe_sbin_id` = e.`recid`
        JOIN
          {$this->db_erp}.`mst_wshe_grp` f
        ON
          a.`wshe_grp_id` = f.`recid`
        WHERE
          a.`plnt_id` = {$plntID}
        AND
          a.`wshe_id` = {$whID}
        AND
          a.`wshe_grp_id` = {$rackgrpID}
        AND
          a.`wshe_sbin_id` = {$sbinID}
        AND
          (a.`witb_barcde` LIKE '%{$terms}%')
        GROUP BY
          a.`witb_barcde`,a.`plnt_id`,a.`wshe_id`
        HAVING
          `is_out` = 0
        LIMIT 50
      ";

      $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


      $autoCompleteResult = array();

      if($q->getNumRows() > 0){

        foreach($q->getResultArray() as $row){
          $mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn);
          array_push($autoCompleteResult,array(
            "value" => $row['witb_barcde'],
            "stock_code" => $row['stock_code'],
            "ART_CODE" => $row['ART_CODE'],
            "ART_DESC" => $row['ART_DESC'],
            "ART_SKU" => $row['ART_SKU'],
            "convf" => $row['convf'],
            "qty" => $row['qty_inv'],
            "total_pcs" => $row['total_pcs'],
            "cbm" => $row['cbm'],
            "mtkn_rid" => $mtkn_rid
          ));
        }

      

      }
      else{

        array_push($autoCompleteResult,array(
          "value" => 'NO RECORDS FOUND',
          "stock_code" =>  'NO RECORDS FOUND',
          "ART_CODE" =>  'NO RECORDS FOUND',
          "ART_DESC" =>  'NO RECORDS FOUND',
          "ART_SKU" =>  'NO RECORDS FOUND',
          "convf" =>  'NO RECORDS FOUND',
          "qty" =>  'NO RECORDS FOUND',
          "total_pcs" =>  'NO RECORDS FOUND',
          "cbm" => 'NO RECORDS FOUND',
          "mtkn_rid" => ''
        ));
      }
  echo json_encode($autoCompleteResult);

    }//end



public function save_transfer(){
    $cuser = $this->mylibzdb->mysys_user();
    $mpw_tkn = $this->mylibzdb->mpw_tkn();
    $adata1         =  $this->request->getVar('data_arr');
    $adata2         =  $this->dbx->escapeString($adata1);
    $txtWarehousetkn =  $this->request->getVar('txtWarehousetkn');
    $to_rack        =  $this->request->getVar('to_rack');
    $to_bin         =  $this->request->getVar('to_bin');
    $from_bin       =  $this->request->getVar('from_bin');
    $to_rack_name   = $this->request->getVar('to_rack_name');
    $to_bin_name    = $this->request->getVar('to_bin_name');
    $from_rack_name = $this->request->getVar('from_rack_name');
    $from_bin_name  = $this->request->getVar('from_bin_name');
    $itemCount      = $this->request->getVar('itemCount');

        
    //get warehouse id 
    $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
    $whID     = $wshedata['whID'];
    $plntID   = $wshedata['plntID'];
    // warehouse end

    $sbinData     = $this->mymelibzsys->getWhBinDetailsByTkn($to_bin);
    $to_sbinID    = $sbinData['recid'];
    $to_rackgrpID = $sbinData['wshegrp_id'];

    $fromsbinData   = $this->mymelibzsys->getWhBinDetailsByTkn($from_bin);
    $from_sbinID    = $fromsbinData['recid'];
    $from_rackgrpID = $fromsbinData['wshegrp_id'];



    $str_chk ="
            SELECT count(aa.`witb_barcde`) validCount
            FROM {$this->db_erp}.`warehouse_inv_rcv` aa
            WHERE aa.`witb_barcde` IS NOT NULL AND  aa.`witb_barcde` IN ($adata1) 
            AND aa.`wshe_id` = '$whID' 
            AND   aa.`wshe_grp_id`   = '$from_rackgrpID' AND  aa.`wshe_sbin_id`  = '$from_sbinID'";
    $q_chk = $this->mylibzdb->myoa_sql_exec($str_chk,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__ . chr(13) . chr(10) . 'User: ' . $cuser);
    $row = $q_chk->getRowArray();
    $validCount = $row['validCount'];
    if($itemCount != $validCount){
        die("Invalid barcodes");

    }


     //save to hd
     $_hd_ctrlno = "CRTM".$this->mydataz->get_ctr($this->db_erp,'CTRL_CWT');  
     //$this->mydataz->get_ctr_new_dr('CWO','',$this->db_erp,'CTRL_CWO');
         $str_in = "INSERT INTO  {$this->db_erp}.`warehouse_rack_transfer_hd` (
       `header`,
        `plnt_id`,
      `wshe_id`,
      `to_wshe_grp_id`,
      `to_wshe_sbin_id`,
      `from_wshe_grp_name`,
      `from_wshe_sbin_name`,
      `to_wshe_grp_name`,
      `to_wshe_sbin_name`,
      `remarks`,
      `muser`,
      `encd`
         )
         VALUES
       (
     '{$_hd_ctrlno}',
     '{$plntID}',
     '{$whID}',
     '{$to_rackgrpID}',
     '{$to_sbinID}',
     '{$from_rack_name}',
     '{$from_bin_name}',
     '{$to_rack_name}',
     '{$to_bin_name}',
     '',
     '{$cuser}',
     now()
       );
    ";
     $this->mylibzdb->myoa_sql_exec($str_in,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
     //save to hd end

     $str = "
     INSERT INTO
     {$this->db_erp}.`warehouse_rack_transfer`(
      `rack_transfer_hd`,
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
      `frm_wshe_sbin_id`,
      `frm_wshe_grp_id`,
      `to_wshe_sbin_id`,
      `to_wshe_grp_id`,
      `box_no`,
      `mat_rid`,
      `qty`,
      `convf`,
      `uom`,
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
     '{$to_sbinID}',
     '{$to_rackgrpID}',
     `box_no`,
     `mat_rid`,
     `qty`,
     `convf`,
     `uom`,
     `cbm`,
     `total_pcs`,
     `total_amount`,
     `remarks`,
     '{$cuser}',
     now()
     FROM {$this->db_erp}.`warehouse_inv_rcv`
     WHERE  `witb_barcde` IN ($adata1)
     AND `wshe_id` = '$whID' 
     AND  `wshe_grp_id`   = '$from_rackgrpID' AND `wshe_sbin_id`  = '$from_sbinID'
      ";

     $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

     /***************** AUDIT LOGS *************************/
     $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_TRANSFER_MN','',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


    $str_up = "UPDATE  {$this->db_erp}.`warehouse_inv_rcv` SET  `is_transferred` = 1,`wshe_sbin_id` = '{$to_sbinID}',`wshe_grp_id` ='{$to_rackgrpID}'  WHERE  `witb_barcde` IN ($adata1)      
    AND `wshe_id` = '$whID' 
    AND  `wshe_grp_id`   = '$from_rackgrpID' AND `wshe_sbin_id`  = '$from_sbinID'";
    $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    /***************** AUDIT LOGS *************************/
    $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_TRANSFER_U_INV','',$_hd_ctrlno,$str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    echo "[$_hd_ctrlno] Transferred Successfully!";


    }

    public function view_rack_transfer_recs(){

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
                        (aa.`trx` LIKE '%{$msearchrec}%' OR aa.`encd` LIKE '%{$msearchrec}%')
                ";
            }

            $strqry = "
            SELECT
            aa.`encd`,
            aa.`recid`,
            aa.`header`,
            aa.`muser`,
            b.`plnt_code`,
            c.`wshe_code`,
            aa.`remarks` AS `remarks`,
            CONCAT(aa.`to_wshe_grp_name` ,' - ',aa.`to_wshe_sbin_name`) transfer_to,
            CONCAT(aa.`from_wshe_grp_name` ,' - ',aa.`from_wshe_sbin_name`) transfer_from
            FROM
                {$this->db_erp}.`warehouse_rack_transfer_hd` aa
            JOIN
                {$this->db_erp}.`mst_plant` b
            ON
                aa.`plnt_id` = b.`recid`
            JOIN
                {$this->db_erp}.`mst_wshe` c
            ON
                aa.`wshe_id` = c.`recid`    
            WHERE  aa.`plnt_id` = '{$plntID}' AND  aa.`wshe_id` = '{$whID}'
            GROUP BY aa.`header`,aa.`plnt_id`,aa.`wshe_id`";

            $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


            if($qry->getNumRows() > 0) { 
             $data['rlist'] = $qry->getResultArray();

            } else { 
             $data = array();
             $data['rlist'] = '';

            }
            return $data;

    }

    public function rack_transfer_upld(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $txtprod_type_upld_sub = $this->request->getVar('txtprod_type_upld_sub');
        $txtWarehouse   =  $this->request->getVar('txtWarehouse'); 
        $txtWarehousetkn  =  $this->request->getVar('mtkn_wshe'); 
        $to_rack        =  $this->request->getVar('to_rack');
        $to_bin         =  $this->request->getVar('to_bin');
        $from_bin       =  $this->request->getVar('from_bin');
        $to_rack_name   = $this->request->getVar('sto_rack');
        $to_bin_name    = $this->request->getVar('sto_bin');
        $from_rack_name = $this->request->getVar('sfrom_rack');
        $from_bin_name  = $this->request->getVar('sfrom_bin');

        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end


        $fromsbinData = $this->mymelibzsys->getWhBinDetailsByTkn($from_bin);
        $from_sbinID = $fromsbinData['recid'];
        $from_rackgrpID = $fromsbinData['wshegrp_id'];
                

        $type = "";
        $insertSubTag = 0;
        $nrecs_pb     = 0;
        $invalidUnit  = 0 ;
        $tableName = '';
        
        $csv_file = "";
        $csv_ofile = "";
        $_csv_path = './whinvtransfer_upld/';
        $_csv_upath = './whinvtransfer_upld/';
        $_csv_pubpath = './uploads/whinvtransfer_upld/';

         $this->validate([
                'userfile' => 'uploaded[userfile]|max_size[userfile,100]'
                               . '|mime_in[userfile,text/x-comma-separated-values, text/comma-separated-values, application/octet-stream, application/vnd.ms-excel,application/x-csv,text/x-csv,text/csv,application/csv,application/excel,application/vnd.msexcel,text/plain]'
                               . '|ext_in[userfile,csv,xls,text,txt,xlsx]|max_dims[userfile,1024,768]',
            ]);

            if(!is_dir($_csv_pubpath)) mkdir($_csv_pubpath, '0755', true);
            $file = $this->request->getFile('transfer_rack_file');


            $file->move($_csv_pubpath,$file->getName());

            if(! $file->hasMoved())
            {
                echo "Error File Uploading/Process";
                die();
            }
            
            $csv_file  = $file->getName();
            $csv_ofile = $file->getName();
            $tbltemp   = $this->db_temp . ".`whinvcd_transfer_upld_temp_" . $this->mylibzsys->random_string(15) . "`";

            $str = "drop table if exists {$tbltemp}";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $cfile = $_csv_pubpath . $csv_file;
            //create temp table 
            $str = "
            CREATE table {$tbltemp} ( 
            `recid` int(25) NOT NULL AUTO_INCREMENT,
            `barcode` varchar(30) DEFAULT NULL,
            `remarks` text DEFAULT NULL,
            PRIMARY KEY (`recid`),
            KEY idx01 (`barcode`)
            )";


            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        

            $str = "
              LOAD DATA LOCAL INFILE '$cfile' INTO TABLE {$tbltemp}
              CHARACTER SET UTF8
              FIELDS TERMINATED BY '\t'
              LINES TERMINATED BY '\n'
              IGNORE 1 LINES
              (
                barcode,remarks
              )
            ";       
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
            $str = "SELECT count(*) __nrecs from {$tbltemp}";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() == 0){ 
                $str = "
                  LOAD DATA LOCAL INFILE '$cfile' INTO TABLE {$tbltemp}
                  CHARACTER SET UTF8
                  FIELDS TERMINATED BY '\t'
                  LINES TERMINATED BY '\r\n'
                  IGNORE 1 LINES
                  (
                    barcode,remarks
                  )
                 ";       
                $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                
                $str = "SELECT count(*) __nrecs from {$tbltemp}";
                $qq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                if($qq->getNumRows() == 0) { 
                    $str = "
                      LOAD DATA LOCAL INFILE '$cfile' INTO TABLE {$tbltemp}
                      CHARACTER SET UTF8
                      FIELDS TERMINATED BY '\t'
                      LINES TERMINATED BY '\r'
                      IGNORE 1 LINES
                      (
                        barcode,remarks
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
            barcode  = TRIM(REGEXP_REPLACE(barcode, '[^\\x20-\\x7E]', ''))
            ";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);






            //get barcodes 

            // $sep = '"\'"';
            // $str = "SELECT GROUP_CONCAT({$sep},`wobBarcode`,{$sep}) brcds from  {$tbltemp} ";
            // $brcdq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            // $brcdr = $brcdq->getRowArray();
            // $barcd_upls_list = $brcdr['brcds'];


    $str_chk =" 
        SELECT
         GROUP_CONCAT(a.`witb_barcde` SEPARATOR '<br>') invalid_barcdes,
         COUNT(a.`witb_barcde`) barcode_count
        FROM
          {$this->db_erp}.`warehouse_inv_rcv` a
        WHERE a.`plnt_id` =  '{$plntID}'
        AND  a.`wshe_id` = '{$whID}' 
        AND a.`wshe_grp_id` =  '{$from_rackgrpID}'
        AND a.`wshe_sbin_id` = '{$from_sbinID}'
        AND a.`is_out` = 1 
        AND a.`witb_barcde` IN (SELECT TRIM(REPLACE(REPLACE(`barcode`,'\r',''),'\n','')) FROM {$tbltemp})";
        $brcdq = $this->mylibzdb->myoa_sql_exec($str_chk,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $brcdr = $brcdq->getRowArray();
        $invalid_barcd_list = $brcdr['invalid_barcdes'];
        $barcode_count = $brcdr['barcode_count'];

        if(!empty($invalid_barcd_list)){

        return array(
          'result' => false,
          'data' => "<div class=\"alert alert-danger\"><strong>[ {$barcode_count} ] Barcode/s not found or 0 QTY.</strong><br>{$invalid_barcd_list}</div>"
        );
        die();
        }

            //get items
            $str_itm = "
            SELECT
            a.`witb_barcde` AS `barcde`,
            a.`recid` AS `wshe_rcv_rid`,
            a.`qty`,
            a.`stock_code`,
            a.`convf`,
            a.`total_pcs`,
            c.`ART_CODE`,
            c.`ART_DESC`,
            c.`ART_SKU`,
            (SELECT `remarks` FROM {$tbltemp} aa WHERE aa.`barcode` = a.`witb_barcde` limit 1) AS `remarks`
              FROM
                {$this->db_erp}.`warehouse_inv_rcv` a
              JOIN
                {$this->db_erp}.`mst_article` c
              ON
                a.`mat_rid` = c.`recid`
              WHERE a.`plnt_id` =  '{$plntID}'
              AND  a.`wshe_id` = '{$whID}' 
              AND a.`wshe_grp_id` =  '{$from_rackgrpID}'
              AND a.`wshe_sbin_id` = '{$from_sbinID}'
              AND a.`witb_barcde` 
              IN (SELECT TRIM(REPLACE(REPLACE(`barcode`,'\r',''),'\n','')) FROM {$tbltemp})";

            $q3 = $this->mylibzdb->myoa_sql_exec($str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            /***************** AUDIT LOGS *************************/
            $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_TRANSFER_UPLD','',$tbltemp,$str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            if($q3->getNumRows() > 0 )
            {

                $data['result'] = $q3->getResultArray();
                $data['count'] = count($q3->getResultArray());
                $data['isdone'] = 0;
                $data['tbltemp'] = $tbltemp;

            }
            else
            {

                $data['result'] = '';
                $data['count']  = 0;
                $data['isdone'] = 0;
                $data['tbltemp'] = $tbltemp;

            }
            $data['response'] = true;
            return $data;
     
        
    }  //end simpleupld_proc


    public function save_transfer_upload(){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $adata1         =  $this->request->getVar('data_arr');
        $adata2         =  $this->dbx->escapeString($adata1);
        $txtWarehousetkn =  $this->request->getVar('txtWarehousetkn');
        $to_rack        =  $this->request->getVar('to_rack');
        $to_bin         =  $this->request->getVar('to_bin');
        $from_bin       =  $this->request->getVar('from_bin');
        $to_rack_name   = $this->request->getVar('to_rack_name');
        $to_bin_name    = $this->request->getVar('to_bin_name');
        $from_rack_name = $this->request->getVar('from_rack_name');
        $from_bin_name  = $this->request->getVar('from_bin_name');
        $tbltemp = $this->request->getVar('tbltemp');
        $trCount = $this->request->getVar('trCount');
    
        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txtWarehousetkn);
        $whID   = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        // warehouse end

        $fromsbinData   = $this->mymelibzsys->getWhBinDetailsByTkn($from_bin);
        $from_sbinID    = $fromsbinData['recid'];
        $from_rackgrpID = $fromsbinData['wshegrp_id'];

        $sbinData     = $this->mymelibzsys->getWhBinDetailsByTkn($to_bin);
        $to_sbinID    = $sbinData['recid'];
        $to_rackgrpID = $sbinData['wshegrp_id'];
        
        $tbltransrack   = $this->db_temp . ".`trans_rack_{$cuser}_track_temp_" . $this->mylibzsys->random_string(15) . "`";
        $str = "drop table if exists {$tbltransrack}";
        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $str_chk ="SELECT
                count(aa.`witb_barcde`) validCount
                FROM {$this->db_erp}.`warehouse_inv_rcv` aa
                JOIN {$tbltemp} b
                ON (aa.`witb_barcde` = REPLACE(REPLACE(b.`barcode`, '\r', ''), '\n', ''))
                WHERE b.`barcode` IS NOT NULL AND aa.`wshe_id` = '$whID' AND   aa.`wshe_grp_id`   = '$from_rackgrpID' AND  aa.`wshe_sbin_id`  = '$from_sbinID'";
   
        $q_chk = $this->mylibzdb->myoa_sql_exec($str_chk,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__ . chr(13) . chr(10) . 'User: ' . $cuser);
        $row = $q_chk->getRowArray();
        $validCount = $row['validCount'];
        if($trCount != $validCount){
            die("Invalid barcodes");

        }

        $str = "CREATE TABLE IF NOT EXISTS {$tbltransrack} 
                SELECT
                  aa.`recid`,
                  aa.`header`,
                  aa.`stock_code`,
                  aa.`barcde`,
                  aa.`irb_barcde`,
                  aa.`srb_barcde`,
                  aa.`witb_barcde`,
                  aa.`wob_barcde`,
                  aa.`pob_barcde`,
                  aa.`dmg_barcde`,
                  aa.`mat_rid`,
                  aa.`qty`,
                  aa.`convf`,
                  aa.`uom`,
                  aa.`cbm`,
                  aa.`total_pcs`,
                  aa.`total_amount`,
                  aa.`box_no`,
                  aa.`remarks`,
                  aa.`plnt_id`,
                  aa.`wshe_id`,
                  aa.`wshe_grp_id`,
                  aa.`wshe_sbin_id` 
                FROM {$this->db_erp}.`warehouse_inv_rcv` aa
                JOIN {$tbltemp} b
                ON (aa.`witb_barcde` = REPLACE(REPLACE(b.`barcode`, '\r', ''), '\n', ''))
                WHERE b.`barcode` IS NOT NULL AND aa.`wshe_id` = '$whID' AND   aa.`wshe_grp_id`   = '$from_rackgrpID' AND  aa.`wshe_sbin_id`  = '$from_sbinID'
                GROUP BY aa.`witb_barcde`
            ";
    
        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__ . chr(13) . chr(10) . 'User: ' . $cuser);
        $str = "ALTER table {$tbltransrack} add index idx01 (witb_barcde),add index idx02 (wshe_id),add index idx03 (header)";
        $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__ . chr(13) . chr(10) . 'User: ' . $cuser);


        //save to hd
        $_hd_ctrlno = "CRTM".$this->mydataz->get_ctr($this->db_erp,'CTRL_CWT');  
        //$this->mydataz->get_ctr_new_dr('CWO','',$this->db_erp,'CTRL_CWO');
        $str_in = "INSERT INTO  {$this->db_erp}.`warehouse_rack_transfer_hd` (
           `header`,
            `plnt_id`,
          `wshe_id`,
          `to_wshe_grp_id`,
          `to_wshe_sbin_id`,
          `from_wshe_grp_name`,
          `from_wshe_sbin_name`,
          `to_wshe_grp_name`,
          `to_wshe_sbin_name`,
          `remarks`,
          `muser`,
          `encd`
             )
             VALUES
           (
         '{$_hd_ctrlno}',
         '{$plntID}',
         '{$whID}',
         '{$to_rackgrpID}',
         '{$to_sbinID}',
         '{$from_rack_name}',
         '{$from_bin_name}',
         '{$to_rack_name}',
         '{$to_bin_name}',
         '',
         '{$cuser}',
         now()
           );
        ";
         $this->mylibzdb->myoa_sql_exec($str_in,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
         //save to hd end

         $str = "
         INSERT INTO
         {$this->db_erp}.`warehouse_rack_transfer`(
          `rack_transfer_hd`,
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
          `frm_wshe_sbin_id`,
          `frm_wshe_grp_id`,
          `to_wshe_sbin_id`,
          `to_wshe_grp_id`,
          `box_no`,
          `mat_rid`,
          `qty`,
          `convf`,
          `uom`,
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
         '{$to_sbinID}',
         '{$to_rackgrpID}',
         `box_no`,
         `mat_rid`,
         `qty`,
         `convf`,
         `uom`,
         `cbm`,
         `total_pcs`,
         `total_amount`,
         `remarks`,
         '{$cuser}',
         now()
         FROM {$tbltransrack} 
         ";

         $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

         /***************** AUDIT LOGS *************************/
         $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_TRANSFER_UPLD_SV','',$_hd_ctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

             $str_up = "UPDATE  {$this->db_erp}.`warehouse_inv_rcv` a,{$tbltransrack} b
             SET  `is_transferred` = 1,
             a.`wshe_sbin_id` = '{$to_sbinID}',
             a.`wshe_grp_id` ='{$to_rackgrpID}'  
             WHERE   (a.`witb_barcde` = b.`witb_barcde`) AND a.`wshe_id` = '$whID' AND   a.`wshe_grp_id`   = '$from_rackgrpID' AND  a.`wshe_sbin_id`  = '$from_sbinID' ";
        $this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        /***************** AUDIT LOGS *************************/
        $this->mylibzdb->user_logs_activity_module($this->db_erp,'CD_TRANSFER_UPLD_INV_U','',$_hd_ctrlno,$str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        //echo $str_up;
        echo "[$_hd_ctrlno] Transferred Successfully!";


        }

    public function view_ent_itm_recs_v2($npages = 1,$npagelimit = 10,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $morder = $this->request->getVar('order');
        $search = $this->request->getVar('search');
        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $mtkn_wshe_page = $this->request->getVar('mtkn_wshe_page');
        $txt_warehouse = $this->request->getVar('txt_warehouse');
        $mwhere = "";
        $str_order = "";

        //var_dump($mtkn_whse,'-',$mtkn_wshe_page,'-',$txt_warehouse);

        //get warehouse id 
        if(empty($mtkn_whse) && !empty($mtkn_wshe_page)) { 
            $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_wshe_page);
            $whID = $wshedata['whID'];
            $plntID = $wshedata['plntID'];
            $mwhere = "WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}' "; 
        }elseif(empty($mtkn_whse) && empty($mtkn_wshe_page)){
            $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($txt_warehouse);
            $whID = $wshedata['whID'];
            $plntID = $wshedata['plntID'];
            $mwhere = "WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}' "; 
        }elseif(!empty($msearchrec)){
            $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
            $whID = $wshedata['whID'];
            $plntID = $wshedata['plntID'];
            $mwhere = "WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}' "; 
        }else{
            $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
            $whID = $wshedata['whID'];
            $plntID = $wshedata['plntID'];
            $mwhere = "WHERE rcv.`plnt_id` = '{$plntID}' AND  rcv.`wshe_id` = '{$whID}' "; 
        }

        //IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
        // $str_vwrecs = "AND a.`muser` = '$cuser'";
    
        $str_optn = "";
        $str_item = "";
        $str_rcv = "";
        $str_grp = "";
        $str_end = "";
        if(!empty($msearchrec) ) { 
            $msearchrec = $this->dbx->escapeString($msearchrec);
            //warehouse receiving table
            $str_rcv = "AND (rcv.`witb_barcde` LIKE '{$msearchrec}%' OR rcv.`stock_code` LIKE '{$msearchrec}%'  OR rcv.`remarks` LIKE '{$msearchrec}%'  OR rcv.`SD_NO` LIKE '{$msearchrec}%'";

            //mst_article table
            $str = "select recid from {$this->db_erp}.mst_article where ART_CODE LIKE '%{$msearchrec}%'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() > 0):
                foreach($q->getResultArray() as $rw):
                    $mat_rid = $rw['recid'];
                    $str_item .= " rcv.`mat_rid` = '{$mat_rid}%' or ";
                endforeach;
                    $str_item = " OR (" . substr($str_item,0,strlen($str_item) - 3) . "))";

            endif;

            //mst_wshe_grp table
            $str = "select recid from {$this->db_erp}.mst_wshe_grp where wshe_grp LIKE '%{$msearchrec}%'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() > 0):

                foreach($q->getResultArray() as $rw):
                    $grp_id = $rw['recid'];
                    $str_grp .= " rcv.wshe_grp_id = '{$grp_id}%' or ";
                endforeach;
                    $str_grp = " OR " . substr($str_grp,0,strlen($str_grp) - 3) . ")";

            endif;

            if (empty($str_item) && !empty($str_grp)) {
                $str_item = "";
            }elseif(!empty($str_item) && empty($str_grp)){
                $str_grp = "";
            }elseif(empty($str_item) && empty($str_grp)){
                $str_grp = "";
                $str_item = "";
                $str_end = ")";
            }

           
        }
        //. $str_item . $str_grp . $str_end
        $str_optn = $mwhere . $str_rcv. $str_item . $str_grp . $str_end ;
        // OR grp.`wshe_grp` LIKE '%{$msearchrec}%'
        // IF( rcv.`is_out` = 0,rcv.`qty`,0) qty,
        // OR art.`ART_CODE` LIKE '%{$msearchrec}%'
        $strqry = "
        SELECT
        rcv.`recid`,
        sha2(concat(rcv.`recid`,'{$mpw_tkn}'),384) txt_mtknr, 
        CASE 
            WHEN rcv.`type` = 'GI'
                THEN '0'
            ELSE
                 CASE
                  WHEN 
                  (IFNULL((SELECT done FROM  {$this->db_erp}.`warehouse_shipdoc_hd` WHERE `crpl_code`  = rcv.`SD_NO` ),0)  = 1) 
                     THEN '0'
                 ELSE
                      '1'
                 END 
            END as qty,   
        rcv.`qty`qty_scanned ,
        rcv.`is_out`,
        rcv.`trx`,
         '' `uprice`,
        rcv.`remarks`,
        pl.`plnt_code`,
        wh.`wshe_code`,
        rcv.`box_no`,
        'BOX' BOX,
        rcv.`stock_code`,
        art.`ART_CODE`,
        art.`ART_DESC`,
        rcv.`convf`,
        rcv.`total_amount` tamt_scanned,
        '' price,
        rcv.`total_pcs` total_pcs_scanned,
        rcv.`witb_barcde`  barcde,
        rcv.`muser`,
        rcv.`type`,
        rcv.`SD_NO`,
        rcv.`encd`,
        rcv.`wshe_sbin_id`,
        rcv.`wshe_grp_id`,
        rcv.`plnt_id`,
        rcv.`wshe_id`,
        (SELECT wshe_bin_name FROM mst_wshe_bin WHERE  recid = rcv.`wshe_sbin_id` ) wshe_bin_name,
        (SELECT wshe_grp FROM mst_wshe_grp WHERE  recid = rcv.`wshe_grp_id`) wshe_grp
        FROM
        {$this->db_erp}.`warehouse_inv_rcv` rcv
        JOIN {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        {$str_optn}
        GROUP BY rcv.`witb_barcde`";

        if($cuser == 'arman'):
            echo $strqry . '<br/>';
        endif;

        $str = "
		select count(*) __nrecs from ({$strqry}) oa
		";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rw = $qry->getRowArray();
		$npagelimit = ($npagelimit > 0 ? $npagelimit : 30);
		$nstart = ($npagelimit * ($npages - 1));
        $nstart = $nstart < 0 ? 0 : $nstart; 
		
		
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
			$data['rlist'] = 0;
		}
		return $data;
    } //endfunc

    public function get_totalRows($mwhere = ''){
        $strqry = "
        SELECT
        rcv.`recid`
        FROM
        {$this->db_erp}.`warehouse_inv_rcv` rcv
        JOIN {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        JOIN {$this->db_erp}.`mst_wshe_bin` sbin 
            ON rcv.`wshe_sbin_id` = sbin.`recid` AND rcv.`wshe_grp_id` = sbin.`wshegrp_id` 
            AND sbin.`plnt_id`  = rcv.`plnt_id` AND sbin.`wshe_id` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_wshe_grp` grp 
            ON rcv.`wshe_grp_id` = grp.`recid` 
            AND grp.`plnt_id`  = rcv.`plnt_id` AND grp.`wshe_id` = rcv.`wshe_id`
        {$mwhere}
        GROUP BY rcv.`witb_barcde`";

        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data = $qry->getNumRows();

        } else{ 
         $data = 0;
        }
        return $data;

    }


    public function get_totalFilteredRows($mwhere,$str_option){
    
        $strqry = "
        SELECT
        rcv.`recid`
        FROM
        {$this->db_erp}.`warehouse_inv_rcv` rcv
        JOIN {$this->db_erp}.`mst_plant` pl ON pl.`recid` = rcv.`plnt_id`
        JOIN {$this->db_erp}.`mst_wshe` wh ON wh.`recid` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_article` art ON art.`recid` =  rcv.`mat_rid`
        JOIN {$this->db_erp}.`mst_wshe_bin` sbin 
            ON rcv.`wshe_sbin_id` = sbin.`recid` AND rcv.`wshe_grp_id` = sbin.`wshegrp_id` 
            AND sbin.`plnt_id`  = rcv.`plnt_id` AND sbin.`wshe_id` = rcv.`wshe_id`
        JOIN {$this->db_erp}.`mst_wshe_grp` grp 
            ON rcv.`wshe_grp_id` = grp.`recid` 
            AND grp.`plnt_id`  = rcv.`plnt_id` AND grp.`wshe_id` = rcv.`wshe_id`
        {$mwhere} {$str_option}
        GROUP BY rcv.`witb_barcde`";

        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0){ 

         $data = $qry->getNumRows();

        }else{ 
         $data = 0;
        }
        return $data;

    }

} //end main MyWarehouseModel