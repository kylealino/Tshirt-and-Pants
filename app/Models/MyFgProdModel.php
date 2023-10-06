<?php
/*
 * Module      :    MyFgProdModel.php
 * Type 	   :    Model
 * Program Desc:    MyFgProdModel
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/
namespace App\Models;
use CodeIgniter\Model;

class MyFgProdModel extends Model
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

    public function view_rm_rcvng_recs($npages = 1,$npagelimit = 30,$msearchrec=''){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $mtkn_whse = $this->request->getVar('mtkn_whse');

        $strqry = "
        SELECT 
        b.`recid`,
        b.`fgreq_trxno`,
        a.`plnt_id`,
        a.`tpa_trxno`,
        a.`branch_name`,
        b.`req_date`,
        c.`total_pack`,
        b.`pack_qty`,
        b.`processed_pack`,
        c.`is_packed`

        FROM 
        trx_tpa_hd a
        JOIN
        trx_fgpack_req_hd b
        ON
        a.`tpa_trxno` = b.`tpa_trxno`
        JOIN
        trx_fgpack_req_dt c
        ON
        b.`fgreq_trxno` = c.`fgreq_trxno`
        WHERE
        c.`total_pack` != '0'
        GROUP BY b.`fgreq_trxno`
        ORDER BY recid DESC
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
    } //end view_rm_rcvng_recs

    public function fg_prod_entry_save() {

        $cuser            = $this->mylibzdb->mysys_user();
        $mpw_tkn          = $this->mylibzdb->mpw_tkn();
        $mencd_date       = date("Y-m-d");
        $fgreq_trxno = $this->request->getVar('fgreq_trxno'); 
        $txt_req_pack = $this->request->getVar('txt_req_pack');
        $txt_rmng_pack = $this->request->getVar('txt_rmng_pack');
        $txt_process_pack = $this->request->getVar('txt_process_pack');
        $adata1 = $this->request->getVar('adata1');
        $adata2 = $this->request->getVar('adata2');
        if ($txt_process_pack > $txt_req_pack) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Process pack cannot be greater than requested pack.</div>";
            die();
        }

        if (empty($txt_process_pack)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> PROCESS PACK QUANTITY CANNOT BE NULL!!! </div>";
            die();
        }

        if(count($adata1) > 0) { 
            $ame = array();
            $adatar1 = array();

            for($aa = 0; $aa < count($adata1); $aa++) { 
                $medata = explode("x|x",$adata1[$aa]);
                $mitemc = trim($medata[0]);
                $mdmd = $medata[1];
                $inv = $medata[4];
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
                    $mdmd = $medata[1];
                    $total_processed = $xdata[3];
                    $inv = $medata[4];

                    if ($mdmd > $inv) {
                        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Stocks unavailable! </div>";
                        die();
                    }

                    $str="
                        UPDATE fg_inv_rcv SET po_qty = po_qty - '$total_processed' WHERE mat_code = '$mitemc' 
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
                    $strY = "
                        SELECT `total_pack`,`total_processed` FROM trx_fgpack_req_dt WHERE `fgreq_trxno` = '$fgreq_trxno' 
                        ";
                        $qP = $this->mylibzdb->myoa_sql_exec($strY,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        $rw = $qP->getResultArray();
                        foreach ($rw as $data) {
                            $total_pack = $data['total_pack'];
                            $total_processed = $data['total_processed'];
                        

                        if ($total_pack != $total_processed) {
                            

                            $strU = "
                            UPDATE trx_fgpack_req_dt SET total_processed = `total_processed` - '$total_processed' WHERE mat_code = '{$mitemc}' AND fgreq_trxno = '$fgreq_trxno'
                            ";
                            $qP = $this->mylibzdb->myoa_sql_exec($strU,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                        }else{

                            $strP = "
                            UPDATE trx_fgpack_req_dt SET is_packed = '1' WHERE mat_code = '{$mitemc}' AND fgreq_trxno = '$fgreq_trxno'
                            ";
                            $qP = $this->mylibzdb->myoa_sql_exec($strP,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    
                            $strU = "
                            UPDATE trx_fgpack_req_dt SET total_processed = `total_processed` - '$total_processed' WHERE mat_code = '{$mitemc}' AND fgreq_trxno = '$fgreq_trxno'
                            ";
                            $qP = $this->mylibzdb->myoa_sql_exec($strU,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        }
                    }
                }
                
            } 
                    
            $strU = "
            UPDATE trx_fgpack_req_hd SET `processed_pack` = '$txt_process_pack', `rmng_pack` = `rmng_pack` - '$txt_process_pack' WHERE fgreq_trxno = '$fgreq_trxno'
            ";
            $q = $this->mylibzdb->myoa_sql_exec($strU,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


        
            echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Data Recorded Successfully!!! </div>
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

        } //end fg_prod_entry_save
    }

    public function fg_prod_barcde_gnrtion() {
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $PO_CFRM_TAG='1';
        $po_code = $this->request->getVar('mtkn_potr');
        $fgreq_trxno = $this->request->getVar('fgreq_trxno');

        $str="
            SELECT `is_bcodegen` FROM `trx_fgpack_req_hd` WHERE `fgreq_trxno` = '{$fgreq_trxno}'
        ";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $rr = $q->getRowArray();
        $is_bcodegen = $rr['is_bcodegen'];

        if($is_bcodegen === '1'){
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Failed </strong>Barcode already generated!!!</div>";
            die();
        }

        $str = "
                update {$this->db_erp}.`trx_fgpack_req_hd`
                set `is_bcodegen` = '1'
                WHERE `fgreq_trxno` ='{$fgreq_trxno}'
            ";

        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $this->mylibzdb->user_logs_activity_module($this->db_erp,'GW_PO_BCODEGEN','',$fgreq_trxno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__); 

        $str = "
                SELECT `recid`,`fgreq_trxno`,SUM(`req_pack`) req_pack,`mat_code`,`qty_perpack`,`total_pack`
                FROM
                {$this->db_erp}.`trx_fgpack_req_dt`
                WHERE
                `fgreq_trxno` = '{$fgreq_trxno}'
                GROUP BY `fgreq_trxno`
        ";

        $boxquery = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $rrec = $boxquery->getResultArray();
        foreach($rrec as $rr){
            $fgreq_trxno = $rr['fgreq_trxno'];
            
            $cseqn_stock =  $this->mydataz->get_ctr_5($this->db_erp,'');//TRANSACTION NO
            //insert no of box first
            $box_no = 1;
            $cseqn_new =  $this->mydataz->get_ctr_barcoding($this->db_erp,'CTRL_GWBOXBR');//TRANSACTION NO
            $str = "
                SELECT a.`recid`,a.`tpa_trxno`,a.`fgreq_trxno`,SUM(a.`req_pack`) req_pack,a.`mat_code`,a.`qty_perpack`,a.`total_pack`,b.`pack_qty`
                FROM
                {$this->db_erp}.`trx_fgpack_req_dt` a
                JOIN
                `trx_fgpack_req_hd` b
                ON
                a.`fgreq_trxno` = b.`fgreq_trxno`
                WHERE
                a.`fgreq_trxno` ='{$fgreq_trxno}'
            
            ";

            $boxquery_details = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            $rrec = $boxquery_details->getResultArray();
            foreach($rrec as $row){
                $tpa_trxno = $row['tpa_trxno']; 
                $fgreq_trxno = $row['fgreq_trxno']; 
                $req_pack = $row['req_pack'];
                $mat_code = $row['mat_code']; 
                $qty_perpack = $row['qty_perpack']; 
                $total_pack = $row['total_pack']; 
                $no_of_box = $row['pack_qty'];

            
                for($i = 0; $i < $no_of_box; $i++){
                    
                    $box_id = str_pad($box_no,4, "0", STR_PAD_LEFT);

                    $irb_barcde = '1111'.$cseqn_new.$box_id;    
                    $srb_barcde = '2222'.$cseqn_new.$box_id;    
                    $witb_barcde = '3333'.$cseqn_new.$box_id;    
                    $wob_barcde = '4444'.$cseqn_new.$box_id;    
                    $pob_barcde = '5555'.$cseqn_new.$box_id;    
                    $dmg_barcde = '6666'.$cseqn_new.$box_id;

                    $str = "
                        INSERT INTO
                        {$this->db_erp}.`fg_prod_barcdng_dt`(
                            `tpa_trxno`,
                            `fgreq_trxno`,
                            `stock_code`,
                            `barcde`,
                            `irb_barcde`,
                            `srb_barcde`,
                            `witb_barcde`,
                            `wob_barcde`,
                            `pob_barcde`,
                            `dmg_barcde`
                        )
                        VALUES(
                            '{$tpa_trxno}',
                            '{$fgreq_trxno}',
                            '{$cseqn_stock}',
                            '{$cseqn_new}',
                            '{$irb_barcde}',
                            '{$srb_barcde}',
                            '{$witb_barcde}',
                            '{$wob_barcde}',
                            '{$pob_barcde}',
                            '{$dmg_barcde}'
                        )
                    ";
                    $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                    $box_no++;
                }//for
            }//foreach -------------------------------------------------------------------------------------------------------------------2
            $boxquery_details->freeResult();
        }//foreach -------------------------------------------------------------------------------------------------------------------1
        $boxquery->freeResult();
        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong>Successfully Generated </div>
        <script type=\"text/javascript\"> 
            function __fg_refresh_data() { 
                try { 
                    
                    
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

    } //end fg_prod_barcde_gnrtion

    public function download_fg_prod_barcode(){
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();
        $fgreq_trxno = $this->request->getVar('fgreq_trxno');
        $chtmljs ="";

        if($fgreq_trxno != ''){
            
            $file_name = "fgprod_barcodereports_{$fgreq_trxno}" . $this->mylibzsys->random_string(9);
            $mpathdn   = ROOTPATH;
            $_csv_path = '/public/downloads/me/';
            $filepath = $mpathdn.$_csv_path.$file_name.'.txt';
            $cfilelnk = site_url() . '/downloads/me/' . $file_name.'.txt';
            $file_name_temp = "fgprod_barcodereports.txt";

            $str="  
            SELECT oa.* INTO OUTFILE '{$filepath}'
            FIELDS TERMINATED BY '\t' 
            LINES TERMINATED BY '\r\n'  
            FROM (
                    SELECT
                    a.`tpa_trxno`,
                    (SELECT SUM(`qty_perpack`) FROM trx_fgpack_req_dt WHERE fgreq_trxno = c.`fgreq_trxno` GROUP BY fgreq_trxno) total,
                    c.`fgreq_trxno`,
                    a.`stock_code`,
                    d.`branch_name`,
                    a.`wob_barcde`,
                    CONCAT(RIGHT(a.`wob_barcde`, 1),'/',c.`req_pack`) last_digit,
                    (SELECT GROUP_CONCAT(mat_code) FROM trx_fgpack_req_dt WHERE fgreq_trxno = c.`fgreq_trxno`) item_codes,
                    'W-TAP',
                    (select sum(dt.`qty_perpack` * mst.`ART_UPRICE`) AS TOTAL FROM trx_fgpack_req_dt dt join mst_article mst ON dt.`mat_code` = mst.`ART_CODE` where dt.`fgreq_trxno` = c.`fgreq_trxno`) AS AMOUNT
                    FROM
                    fg_prod_barcdng_dt a
                    JOIN
                    trx_tpa_dt b
                    ON
                    a.`tpa_trxno` = b.`tpa_trxno`
                    JOIN
                    trx_fgpack_req_dt c
                    ON
                    a.`fgreq_trxno` = c.`fgreq_trxno`
                    JOIN
                    trx_tpa_hd d
                    ON
                    b.`tpa_trxno` = d.`tpa_trxno`
                    JOIN
                    mst_article e 
                    ON
                    c.`mat_code` = e.`ART_CODE`
                    WHERE 
                    a.`fgreq_trxno` = '{$fgreq_trxno}'
                    GROUP BY a.`witb_barcde`

            ) oa       

            ";
        
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

            $chtmljs .= "<div class=\"alert alert-success mb-0\"><strong>DOWNLOAD</strong><br>Box Barcode successfully download. <br> FG TRX NO: <p style=\"color:red;display:inline-block; \">{$fgreq_trxno}</p> </div>
                    <script type=\"text/javascript\">
                        //window.parent.document.getElementById('myscrloading').innerHTML = '';
                        // function download(filename, text) {
                        //   var element = document.createElement('a');
                        //   element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
                        //   element.setAttribute('download', filename);

                        //   element.style.display = 'none';
                        //   document.body.appendChild(element);

                        //   element.click();

                        //   document.body.removeChild(element);
                        // } 
                        pobarcode_dl('{$cfilelnk}','{$file_name_temp}');
                        function pobarcode_dl(fileName,file_name) {
                            //Set the File URL.
                            var url = fileName;
                    
                            $.ajax({
                                url: url,
                                cache: false,
                                xhr: function () {
                                    var xhr = new XMLHttpRequest();
                                    xhr.onreadystatechange = function () {
                                        if (xhr.readyState == 2) {
                                            if (xhr.status == 200) {
                                                xhr.responseType = 'blob';
                                            } else {
                                                xhr.responseType = 'text';
                                            }
                                        }
                                    };
                                    return xhr;
                                },
                                success: function (data) {
                                    //Convert the Byte Data to BLOB object.
                                    var blob = new Blob([data], { type: 'application/octetstream' });
                    
                                    //Check the Browser type and download the File.
                                    var isIE = false || !!document.documentMode;
                                    if (isIE) {
                                        window.navigator.msSaveBlob(blob, fileName);
                                    } else {
                                        var url = window.URL || window.webkitURL;
                                        link = url.createObjectURL(blob);
                                        var a = $('<a />');
                                        a.attr('download', file_name);
                                        a.attr('href', link);
                                        $('body').append(a);
                                        a[0].click();
                                        $('body').remove(a);
                                    }
                                }
                            });
                        };

                        
                        //function pobarcode_dl() { 
                            //jQuery('#lnkexportmsexcel_lbbd_sku').click(function() { 
                            //jQuery('#messproc').css({display:''});
                            //window.location = '{$cfilelnk}';
                            //window.location.href='data:application/octet-stream;base64,'+Base64.encode('{$cfilelnk}');
                            // Start file download.
                            //download('barcodereports.txt',load('{$cfilelnk}'));
                            //});
                        //}
                        // jQuery('#lnkexportmsexcel').click(function() { 
                        //  //jQuery('#messproc').css({display:''});
                        //  window.location = '{$cfilelnk}';
                        // });
                        jQuery('#lnktoprint').click(function() { 
                            jQuery('#__mtoexport').css({display:'none'});
                            //jQuery('#__mtoprint').css({display:'none'});
                            window.print();         
                        });
                    </script>
                    
                    ";
            $q->freeResult();
            //file_put_contents( $cfiledest , $chtml , FILE_APPEND | LOCK_EX );
            echo $chtmljs;
        }
    } //end download_fg_prod_barcode

}