<?php namespace App\Controllers;
/*
 * Module      :    Rm_production.php
 * Type 	   :    Controllers
 * Program Desc:    Rm_production
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
use App\Models\MyRMProductionModel;
use App\Libraries\Fpdf\Mypdf;

class Rm_production extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(0);
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->myrmprod = new MyRMProductionModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
   		$this->db = \Config\Database::connect();
	}

	public function index(){

		echo view('templates/myheader');
		echo view('mtap/rm_production/rm_prod_main');
		echo view('templates/myfooter');

	} //end index

	public function alex_page(){

		echo view('templates/myheader');
		echo view('mtap/rm_outbound/rm_alex');
		echo view('templates/myfooter');

	} //end alex_page

    public function rm_prod_recs() { 

		$data = $this->myrmprod->rm_prod_view_recs();
		return view('mtap/rm_production/rm_prod_recs',$data);

	} //end rm_prod_recs

    public function rm_prod_save(){

		$this->myrmprod->rm_prod_save();

	} //end rm_prod_save

} 
