<?php namespace App\Controllers;
/*
 * Module      :    Fg_prod.php
 * Type 	   :    Controllers
 * Program Desc:    Fg_prod
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
use App\Models\MyFgProdModel;
use App\Libraries\Fpdf\Mypdf;

class Fg_prod extends BaseController 
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
		$this->mytrxfg = new MyFgProdModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
		    // your db connection
   		$this->db = \Config\Database::connect();
	}

	public function index(){
		echo view('templates/myheader');
		echo view('mtap/fg_prod/fg_prod_main');
		echo view('templates/myfooter');
	}

	public function fg_prod_recs(){

		$data    = $this->mytrxfg->view_rm_rcvng_recs();
		return view('mtap/fg_prod/fg_prod_recs',$data);
		
	} //end fg_prod_recs

	public function fg_prod_bcode_dl(){

		$this->mytrxfg->download_fg_prod_barcode();
	} //end fg_prod_bcode_dl

	public function fg_prod_save() { 
        $this->mytrxfg->fg_prod_entry_save();
    } //end fg_prod_save

	public function fg_prod_barcode_generation(){

		$this->mytrxfg->fg_prod_barcde_gnrtion();
	} //end fg_prod_barcode_generation
	
	public function fg_prod_print(){
	
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('mtap/fg_prod/fg_prod_print');
	} //end fg_prod_print

	public function fg_prod_bom_print(){
	
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('mtap/fg_prod/fg_prod_bom_print');
	} //end fg_prod_bom_print

}
