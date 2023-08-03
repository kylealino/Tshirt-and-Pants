<?php namespace App\Controllers;
/*
 * Module      :    Fgp_inv.php
 * Type 	   :    Controllers
 * Program Desc:    Fgp_inv
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/

use CodeIgniter\Controller;
use App\Models\Mytrx_whcrossing_model;
use App\Models\Mymelibsys_model;
use App\Models\MyDatummodel;
use App\Models\MyDatauaModel;
use App\Models\MyFGPackingModel;
use App\Models\MyLibzSysModel;
use App\Models\MyLibzDBModel;
use App\Libraries\Fpdf\Mypdf;

class Fgp_inv extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(1);
		$this->mywhrcinv =  new MyFGPackingModel();
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mylibzsys = new  MyLibzSysModel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
		$this->db = \Config\Database::connect();

	}

	public function index(){
		$data = $this->mywhrcinv->fgp_inv_rec_view(1,50);
		echo view('templates/myheader');
		echo view('mtap/fgp_inv/fgp_inv_main',$data);
		echo view('templates/myfooter');
	
	} //end index

	public function fgpinv_box_content(){

		$data    = $this->mywhrcinv->fgp_box_content_recs();
		return view('mtap/fgp_inv/fgp_inv_box_content_recs',$data);

	} //end fgpinv_box_content

}