<?php
/*
 * Module      :    MyStandardCapModel.php
 * Type 	   :    Model
 * Program Desc:    MyStandardCapModel
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/
namespace App\Models;
use CodeIgniter\Model;

class MyStandardCapModel extends Model
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

    public function standard_cap_upld(){ 

        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
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
            CAP_QTY varchar(35) default '',
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
            ART_CODE,CAP_QTY
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
                ART_CODE,CAP_QTY
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
                    ART_CODE,CAP_QTY
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
            CAP_QTY  = TRIM(CAP_QTY)
            ";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            
            $str = "insert into temp_upld (
                ART_CODE,
                CAP_QTY
                ) SELECT 
                ART_CODE,
                CAP_QTY
                FROM {$tbltemp} 
                
                ";
            $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
    
            $sep = '"\'"';
            $str = "SELECT GROUP_CONCAT({$sep},`ART_CODE`,{$sep}) ART_CODE from  {$tbltemp} ";
            $itemq = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $item = $itemq->getRowArray();
            $item_code_list = $item['ART_CODE'];
    
            //get items
            $str_itm = "SELECT
                    a.`ART_CODE`,
                    a.`CAP_QTY`
                FROM
                    {$tbltemp} a
                JOIN 
                    mst_article b
                ON
                    REPLACE(REPLACE(REPLACE(a.`ART_CODE`, ' ', ''), '\t', ''), '\n', '') = b.`ART_CODE`

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
    } //end standard_cap_upld

    public function standard_cap_entry_save() {
        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");
        $stan_cap_trxno = $this->request->getVar('stan_cap_trxno');
        $branch_name = $this->request->getVar('branch_name');
        $opt_type = $this->request->getVar('opt_type');
        $opt_cat = $this->request->getVar('opt_cat');
        $adata1 = $this->request->getVar('adata1');

        if (empty($branch_name)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please select a branch! </div>";
            die();
        }

        $cseqn =  $this->mydataz->get_ctr_new_dr('STCP','',$this->db_erp,'CTRL_GWFGPA');
        $strHd = "
        insert into `standard_cap_hd` (
            `stan_cap_trxno`,
            `branch_name`,
            `opt_cat`,
            `opt_type`
          )
          values
            (
              '$cseqn',
              '$branch_name',
              '$opt_cat',
              '$opt_type'
            );
        ";

        $q = $this->mylibzdb->myoa_sql_exec($strHd,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        if ($opt_type == 'NEW') {
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
                        
                        $str = "SELECT ('$mitemc') <> (b.`mat_code`) _bcode FROM `standard_cap_hd` a JOIN `standard_cap_dt` b ON a.`stan_cap_trxno` = b.`stan_cap_trxno`  WHERE a.`branch_name` = '$branch_name'" ;
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        
                        if($q->resultID->num_rows > 0) { 
                            $rw = $q->getResultArray();
                            foreach ($rw as  $data) {
                                $bcode = $data['_bcode'];
                                if ($bcode == 0) {
                                    echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Item is already existing. <br/> [$mitemc]</div>";
                                    die();
                                }  
                            }
                            
                        }

                    }
                    array_push($adatar1,$medata);
                }  
               
                if(count($adatar1) > 0) { 
                    for($xx = 0; $xx < count($adatar1); $xx++) { 
                        
                        $xdata = $adatar1[$xx];
                        $mitemc = $xdata[0];
                        $mstdrd = $xdata[1];
    
                        $str="
                        INSERT INTO `standard_cap_dt` (
                            `stan_cap_trxno`,
                            `branch_name`,
                            `mat_code`,
                            `cap_qty`
                          )
                          VALUES
                            (
                              '$cseqn',
                              '$branch_name',
                              '$mitemc',
                              '$mstdrd'
                            );
                        ";
                        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    }
                    
                } 
                    
            }

        }elseif($opt_type == 'UPDATE'){

            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Upload-update is under maintenance. Please proceed to specific item update in item list module below.</div>";
            die();
            // if(count($adata1) > 0) { 
            //     $ame = array();
            //     $adatar1 = array();
    
            //     for($aa = 0; $aa < count($adata1); $aa++) { 
            //         $medata = explode("x|x",$adata1[$aa]);
            //         $mitemc = trim($medata[0]);
            //         $amatnr = array();
    
            //         if(!empty($mitemc)) { 
            //             $str = "select aa.recid,aa.ART_CODE from {$this->db_erp}.`mst_article` aa where ART_CODE = '$mitemc' ";
            //             $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            //             $rw = $q->getRowArray(); 
                        
            //             $str = "SELECT ('$mitemc') = (b.`mat_code`) _bcode FROM `standard_cap_hd` a JOIN `standard_cap_dt` b ON a.`stan_cap_trxno` = b.`stan_cap_trxno`  WHERE a.`branch_name` = '$branch_name'" ;
            //             $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        
            //             if($q->resultID->num_rows > 0) { 
            //                 $rw = $q->getResultArray();
            //                 foreach ($rw as  $data) {
            //                     $bcode = $data['_bcode'];
            //                     if ($bcode == 0) {
            //                         echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Item is not existing. <br/> [$mitemc]</div>";
            //                         die();
            //                     }
            //                 }
            //                 die();
            //             }
            //         }
            //         array_push($adatar1,$medata);
            //     }  
               
            //     if(count($adatar1) > 0) { 
            //         for($xx = 0; $xx < count($adatar1); $xx++) { 
                        
            //             $xdata = $adatar1[$xx];
            //             $mitemc = $xdata[0];
            //             $mqty = $xdata[1];
    
            //             $str="
            //             UPDATE standard_cap_dt a 
            //             JOIN 
            //             standard_cap_hd b 
            //             ON
            //             a.`branch_name` = b.`branch_name`
            //             SET a.`cap_qty` = '$mqty' 
            //             WHERE  a.`mat_code` = '$mitemc'
            //             ";
            //             var_dump($str);
            //             $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            //         }
                    
            //     } 
                    
            // }

        }

        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data saved successfully!!! Series No:{$cseqn} </div>
        <script type=\"text/javascript\"> 
            function __fg_refresh_data() { 
                try { 
                    $('#stan_cap_trxno').val('{$cseqn}');
                    
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


    } //end standard_cap_entry_save

    public function standard_cap_rec_view($npages = 1,$npagelimit = 30,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');


        $strqry = "
        SELECT
            a.`stan_cap_trxno`,
            (SELECT COUNT(`stan_cap_trxno`) FROM standard_cap_dt WHERE stan_cap_trxno = a.`stan_cap_trxno`) AS total_items,
            a.`opt_cat`,
            a.`opt_type`,
            a.`encd`
        FROM
            standard_cap_hd a
        JOIN
            standard_cap_dt b
        ON
            a.`stan_cap_trxno` = b.`stan_cap_trxno`
        GROUP BY
            a.`stan_cap_trxno`
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
    } //end standard_cap_rec_view

    public function standard_cap_view_itm_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 

        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $stcptrxno   = $this->request->getVar('stcptrxno');

        $strqry = "
        SELECT
            b.`stan_cap_trxno`,
            b.`mat_code`,
            b.`cap_qty`,
            a.`opt_type`,
            a.`branch_name`
        FROM
            standard_cap_hd a
        JOIN
            standard_cap_dt b
        ON
            a.`stan_cap_trxno` = b.`stan_cap_trxno`
        WHERE 
        a.`stan_cap_trxno` = '{$stcptrxno}'";
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['stcptrxno'] = $stcptrxno;
         
        } else { 
         $data = array();
         $data['stcptrxno'] = $stcptrxno;
         $data['rlist'] = '';
         $data['txtsearchedrec_rl'] = $msearchrec;
        }
        return $data;

    } //end standard_cap_view_itm_recs

    public function standard_cap_rec_view_list($npages = 1,$npagelimit = 30,$msearchrec='') {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_etr = $this->request->getVar('mtkn_etr');

        $strqry = "
        SELECT
            b.`mat_code`,
            c.`ART_DESC`,
            a.`opt_cat`
        FROM
            standard_cap_hd a
        JOIN
            standard_cap_dt b
        ON
            a.`stan_cap_trxno` = b.`stan_cap_trxno`
        JOIN
            mst_article c
        ON
            b.`mat_code` = c.`ART_CODE`
        GROUP BY
            b.`mat_code`, a.`opt_cat`
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
    } //end standard_cap_rec_view_list

    public function standard_cap_view_itm_recs_list($npages = 1,$npagelimit = 20,$msearchrec=''){ 

        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $stcpitem   = $this->request->getVar('stcpitem');

        $strqry = "
        SELECT
            b.`mat_code`,
            a.`opt_cat`,
            a.`branch_name`,
            b.`cap_qty`
        FROM
            standard_cap_hd a
        JOIN
            standard_cap_dt b
        ON
            a.`stan_cap_trxno` = b.`stan_cap_trxno`
        WHERE 
        b.`mat_code` = '{$stcpitem}'";
        $qry = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) { 
         $data['rlist'] = $qry->getResultArray();
         $data['stcpitem'] = $stcpitem;
         
        } else { 
         $data = array();
         $data['stcpitem'] = $stcpitem;
         $data['rlist'] = '';
         $data['txtsearchedrec_rl'] = $msearchrec;
        }
        return $data;

    } //end standard_cap_view_itm_recs

    public function standard_cap_update() {
        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();

        $adata1 = $this->request->getVar('adata1');

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
                    $mcat = $xdata[1];
                    $mbranch = $xdata[2];
                    $mqty = $xdata[3];

                    $str="
                    UPDATE standard_cap_dt a 
                    JOIN 
                    standard_cap_hd b 
                    ON
                    a.`stan_cap_trxno` = b.`stan_cap_trxno`
                    SET a.`cap_qty` = '$mqty' 
                    WHERE b.`branch_name` = '$mbranch' AND a.`mat_code` = '$mitemc'
                    ";

                    var_dump($str);
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                }
                
                } 
                
            } 

            echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Updated Successfully!!! </div>
            <script type=\"text/javascript\"> 
                function __fg_refresh_data() { 
                    try { 
                        
                        jQuery('#mbtn_mn_Update').prop('disabled',true);
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

    } //end standard_cap_entry_save

}