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

class MyAlexModel extends Model
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

    public function alex_mdl_save(){
        $fname = $this->request->getVar('fname');
        $lname = $this->request->getVar('lname');

       $strrrrr = "INSERT INTO test_alex (`first_name`, `last_name`) VALUES ('$fname', '$lname')";
       $qqqqqqqq = $this->mylibzdb->myoa_sql_exec($strrrrr,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

       echo "Inserted successfully";

    }
}