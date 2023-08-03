<?php

namespace App\Models;
use CodeIgniter\Model;

class MySubItemsModel extends Model
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

    public function sub_items_entry_save() {

        $main_itemc = $this->request->getVar('main_itemc');
        $sub_itemc = $this->request->getVar('sub_itemc');
        $barcode = $this->request->getVar('barcode');
        $convf = $this->request->getVar('convf');
        $uom = $this->request->getVar('uom');
        $srp = $this->request->getVar('srp');

        //validate empty fields
        if (empty($main_itemc) || empty($sub_itemc) || empty($barcode) || empty($convf) || empty($uom) || empty($srp)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please fill up the required fields! </div>";
            die();
        }

        //limit to 13 characters
        $characterCount = strlen($barcode);
        if ($characterCount >13) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Only 13 digits of barcode is required! </div>";
            die();
        }

        //validate spaces
        $trimmed_sub_itemc = trim($sub_itemc);
        if (empty($trimmed_sub_itemc)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Spaces is not allowed! </div>";
            die();
        }

        //validate regex
        $pattern = '/^[A-Za-z0-9]+$/';
        if (!preg_match($pattern, $sub_itemc) || !preg_match($pattern, $barcode) || !preg_match($pattern, $uom))  {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Spaces or Special characters are not allowed! </div>";
            die();
        }

        //validate regex for conv and srp
        $pattern = '/^[A-Za-z0-9.]+$/';
        if (!preg_match($pattern, $convf) || !preg_match($pattern, $srp)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Spaces or Special characters are not allowed! </div>";
            die();
        }

        //check if sub item code is existing
        $str="
            SELECT `SUB_ART_CODE` FROM mst_sub_article WHERE `SUB_ART_CODE` = '$sub_itemc'
        ";

        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> '$sub_itemc' is already existing! </div>";
            die();
        }

        //check if barcode is existing
        $str="
            SELECT `BARCODE` FROM mst_sub_article WHERE `BARCODE` = '$barcode'
        ";

        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        if($qry->getNumRows() > 0) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> '$barcode' is already existing! </div>";
            die();
        }

        //check if barcode in main article master
        // $str="
        //     SELECT `ART_BARCODE1` FROM mst_article WHERE `ART_BARCODE1` = '$barcode'
        // ";

        // $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        // if($qry->getNumRows() > 0) {
        //     echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> '$barcode' is already existing in MAIN masterdata! </div>";
        //     die();
        // }

        //check if sub item existing in main article master
        // $str="
        //     SELECT `ART_CODE` FROM mst_article WHERE `ART_CODE` = '$sub_itemc'
        // ";

        // $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        // if($qry->getNumRows() > 0) {
        //     echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> '$sub_itemc' is already existing in MAIN masterdata! </div>";
        //     die();
        // }


        $str="
        
        INSERT INTO `mst_sub_article` (
            `ART_CODE`,
            `SUB_ART_CODE`,
            `BARCODE`,
            `CONVF`,
            `UOM`,
            `SRP`,
            `DATE`
        )
        VALUES
            (
            '$main_itemc',
            '$sub_itemc',
            '$barcode',
            '$convf',
            '$uom',
            '$srp',
            now()
            );
  
        ";
        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Data Recorded Successfully!!! </div>
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
    } //end sub_items_entry_save

    public function sub_items_view_recs(){ 
        $cuser = $this->mylibzdb->mysys_user();
        $mpw_tkn = $this->mylibzdb->mpw_tkn();

        $strqry = "
        SELECT
            `ART_CODE` as main_itemc,
            `SUB_ART_CODE` as sub_itemc,
            `BARCODE` as barcode,
            `CONVF` as convf,
            `UOM` as uom,
            `SRP` as srp,
            `DATE`
        FROM
            `mst_sub_article`
        ORDER BY `DATE` DESC
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
    } //end sub_items_view_recs

    public function sub_items_update() {

        $cuser = $this->mylibzdb->mysys_user();
        $main_itemc = $this->request->getVar('main_itemc');
        $sub_itemc = $this->request->getVar('sub_itemc');
        $barcode = $this->request->getVar('barcode');
        $convf = $this->request->getVar('convf');
        $uom = $this->request->getVar('uom');
        $srp = $this->request->getVar('srp');
        $recid = $this->request->getVar('recid');

        //validate empty fields
        if (empty($main_itemc) || empty($sub_itemc) || empty($barcode) || empty($convf) || empty($uom) || empty($srp)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Please fill up the required fields! </div>";
            die();
        }

        //validate maximum length
        $characterCount = strlen($barcode);
        if ($characterCount >13) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Only 13 digits of barcode is required! </div>";
            die();
        }

        //validate spaces
        $trimmed_sub_itemc = trim($sub_itemc);
        if (empty($trimmed_sub_itemc)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Spaces is not allowed! </div>";
            die();
        }

        //validate regex
        $pattern = '/^[A-Za-z0-9]+$/';
        if (!preg_match($pattern, $sub_itemc) || !preg_match($pattern, $barcode) || !preg_match($pattern, $uom))  {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Spaces or Special characters are not allowed! </div>";
            die();
        }

        //validate regex for conv and srp
        $pattern = '/^[A-Za-z0-9.]+$/';
        if (!preg_match($pattern, $convf) || !preg_match($pattern, $srp)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> Spaces or Special characters are not allowed! </div>";
            die();
        }

        //check if barcode in main article master
        // $str="
        //     SELECT `ART_BARCODE1` FROM mst_article WHERE `ART_BARCODE1` = '$barcode'
        // ";

        // $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        // if($qry->getNumRows() > 0) {
        //     echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> '$barcode' is already existing in MAIN masterdata! </div>";
        //     die();
        // }

        //check if sub item existing in main article master
        // $str="
        //     SELECT `ART_CODE` FROM mst_article WHERE `ART_CODE` = '$sub_itemc'
        // ";

        // $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        // if($qry->getNumRows() > 0) {
        //     echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>User Error</strong> '$sub_itemc' is already existing in MAIN masterdata! </div>";
        //     die();
        // }
        
        $str="
        
        UPDATE
        `mst_sub_article`
        SET
            `ART_CODE` = '$main_itemc',
            `SUB_ART_CODE` = '$sub_itemc',
            `BARCODE` = '$barcode',
            `CONVF` = '$convf',
            `UOM` = '$uom',
            `SRP` = '$srp'
        WHERE `recid` = '$recid'
  
        ";

        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $str="
        INSERT INTO `mst_sub_article_logs` (

            `ART_CODE`,
            `SUB_ART_CODE`,
            `BARCODE`,
            `CONVF`,
            `UOM`,
            `SRP`,
            `ACTION`,
            `CUSER`,
            `DATE`
          )
          VALUES
            (
              '$main_itemc',
              '$sub_itemc',
              '$barcode',
              '$convf',
              '$uom',
              '$srp',
              'UPDATE',
              '$cuser',
              now()
            )
        ";

        $qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        echo "<div class=\"alert alert-success mb-0\" role=\"alert\"><strong>Info.<br/></strong><strong>Success</strong> Data Updated Successfully!!! </div>
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

    } //end sub_items_update

}