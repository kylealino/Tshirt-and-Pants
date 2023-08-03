<?php namespace App\Controllers;
/*
 * Module      :    Rm_rcvng.php
 * Type 	   :    Controllers
 * Program Desc:    Rm_rcvng
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
use App\Models\MyRMPurchaseModel;
use App\Libraries\Fpdf\Mypdf;

class Rm_rcvng extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(1);
		$this->mywhrcdout =  new MyWarehouseoutModel();
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->mytrxgr = new MyRMPurchaseModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
   		$this->db = \Config\Database::connect();
	}

	public function index(){

		echo view('templates/myheader');
		echo view('mtap/rm_rcvng/warehouse_rm_rcvng_main');
		echo view('templates/myfooter');

	} //end index

	public function rm_ent_rcvng_recs(){

		$data    = $this->mytrxgr->view_rm_rcvng_recs();
		return view('mtap/rm_rcvng/warehouse_rm_rcvng_recs',$data);

	} //end rm_ent_rcvng_recs

	public function wshe_rm_rcvng_save(){

		$this->mytrxgr->mywh_rm_rcvng_save();

	} //end wshe_rm_rcvng_save

	public function wshe_rm_rcvng_itm_recs() { 

		$data    = $this->mytrxgr->rm_view_ent_itm_recs();
		return view('mtap/rm_rcvng/warehouse_rm_rcvng_item_recs',$data);

	} //end wshe_rm_rcvng_itm_recs

} 
