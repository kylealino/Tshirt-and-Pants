<?php namespace App\Controllers;
/*
 * Module      :    Allocation.php
 * Type 	   :    Controllers
 * Program Desc:    Allocation
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/

use CodeIgniter\Controller;
use App\Models\Mytrx_whcrossing_model;
use App\Models\Mymelibsys_model;
use App\Models\MyDatummodel;
use App\Models\MyDatauaModel;
use App\Models\MyWarehouseoutModel;
use App\Models\MyLibzDBModel;
use App\Models\MyTPAModel;
use App\Models\MyAlexModel;
use App\Libraries\Fpdf\Mypdf;
class Alex_Controller extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(0);
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
        $this->myalexmdl = new MyAlexModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
   		$this->db = \Config\Database::connect();
	}

	public function index(){
		echo view('templates/myheader');
        echo view('mtap/alex_folder/alex_page_main');
		echo view('templates/myfooter');
	} //end index

    public function alex_saving(){
        $this->myalexmdl->alex_mdl_save();
    }
} 
