<?php namespace App\Controllers;
  
use CodeIgniter\Controller;


class Dashboard extends BaseController
{

    public function __construct()
    {
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(1);
        $this->mylibzdb = model('App\Models\MyLibzDBModel');
        $this->mymelibzsys = model('App\Models\Mymelibsys_model');
        $this->mydataz = model('App\Models\MyDatumModel');
        $this->request = \Config\Services::request();
    }

    public function index()
    {
        echo view('templates/myheader');
        echo view('mebod');
        echo view('templates/myfooter');
    } 

    public function reportChart(){
        $mtkn_whse = $this->request->getVar('mtkn_whse');
        $db_from   = $this->request->getVar('db_from');
        $db_to     = $this->request->getVar('db_to');
        $str_whse;$txtmonth;$txtyear;
   
        if(empty($db_from) && empty($db_to)){
            $curDate  = $this->mydataz->__get_mysysdatetime();
            $txtmonth = $curDate[4];
            $txtyear  = $curDate[5]; 
            $number_of_days = $curDate[6];
            $max_date = $txtyear.'-'.$txtmonth.'-'.$number_of_days;
            $min_date = $txtyear.'-'.$txtmonth.'-01';
        }
        else{
            $max_date = $db_to;
            $min_date = $db_from;
        }

        //get warehouse id 
        $wshedata = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_whse);
        $whID = $wshedata['whID'];
        $plntID = $wshedata['plntID'];
        $str_whse = "AND ( sdt.`plnt_id` = '{$plntID}'  AND sdt.`wshe_id` = '{$whID}' )";
        // warehouse end

        $dates = array();
        $str_hd  = "";
        $str_qry = "";
      
        $mdates = $this->mymelibzsys->getEachDate($min_date,$max_date);
         foreach($mdates as $date){
            $mdate = $date['s_date'];
            $str_hd  .= "IFNULL(SUM(aa.`{$mdate}`),0) as '{$mdate}', ";
            $str_qry .= "CASE WHEN DATE(sdt.`encd`) =  '{$mdate}' THEN SUM(sdt.`qty`) END AS  '{$mdate}', ";
            $dates[] = $mdate;
         }


        $str ="
            SELECT
            {$str_hd}
            aa.`mDate`
            FROM 
            ( 
            SELECT 
            {$str_qry}
            day(sdt.`encd`) mDate
                FROM  $this->db_erp.`warehouse_shipdoc_dt` sdt
                JOIN warehouse_shipdoc_hd hd ON sdt.`header` = hd.`crpl_code` 
                WHERE DATE(sdt.`encd`) >= '{$min_date}' AND DATE(sdt.`encd`) <= '{$max_date}' {$str_whse}
                AND sdt.`is_out` = 1 AND hd.`done` = 1  
                GROUP BY DATE(sdt.`encd`)
            ) aa 
            UNION ALL
            SELECT
            {$str_hd}
            aa.`mDate`
            FROM 
            ( 
            SELECT 
            {$str_qry}
            day(sdt.`encd`) mDate
                FROM   $this->db_erp.`warehouse_inv_rcv` sdt 
                WHERE DATE(sdt.`encd`) >= '{$min_date}' AND DATE(sdt.`encd`) <= '{$max_date}'  {$str_whse}
                GROUP BY DATE(sdt.`encd`)
            ) aa
            ";

        //var_dump($str);
         $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        $outbound = $q->getRowArray(0);
        $inbound = $q->getRowArray(1);
     
        $mdataInbound = [];
        $mdataOutbound = [];

         foreach($mdates as $date){
            $mdate = $date['s_date'];
            $mdataInbound[] = floatval($inbound[$mdate]);
            $mdataOutbound[] = floatval($outbound[$mdate]);
         }
 

        $this->response->setHeader('Content-Type', 'application/json');
        $data = array();
        $data['inbound'] = $mdataInbound;
        $data['outbound'] = $mdataOutbound;
        $data['dates'] = $dates;
        $data['mtkn_whse'] = $mtkn_whse;
        return json_encode($data);
    }

    public function scroll(){

        return view('scroll');
    }

    public function dashboard_qty(){

        $str ="
        SELECT 
        zz.`rcv_qty`,
        xx.`awt_qty`,
        cc.`sd_qty`,
        vv.`del_qty`
      FROM 
      (SELECT  IFNULL(SUM(qty),0) rcv_qty FROM   $this->db_erp.`warehouse_inv_rcv` )zz,
      (SELECT  IFNULL(SUM(qty),0) awt_qty FROM $this->db_erp.`warehouse_shipdoc_dt`)xx,
      (SELECT  IFNULL(SUM(qty),0) sd_qty FROM $this->db_erp.`warehouse_shipdoc_dt` dt JOIN warehouse_shipdoc_hd hd ON dt.`header` = hd.`crpl_code` WHERE dt.`is_out` =1 AND hd.`done` = 1 )  cc,    
      (SELECT IFNULL(SUM(hd.`actual_qty`),0) del_qty FROM trx_manrecs_hd md JOIN warehouse_shipdoc_hd hd ON md.`drno` = hd.`crpl_code`) vv";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $mdata = $q->getRowArray();
        $data = array();
        $data['rcvdel_qty']   = number_format($mdata['del_qty']);
        $data['shipdoc_qty']  = number_format($mdata['sd_qty']);
        $data['outbound_qty'] = number_format($mdata['awt_qty']);
        $data['inbound_qty']  = number_format($mdata['rcv_qty']);

       $this->response->setHeader('Content-Type', 'application/json');
       return json_encode($data);
        
    }

}  //end main Dashboard