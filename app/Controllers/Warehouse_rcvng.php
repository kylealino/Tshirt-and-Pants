<?php namespace App\Controllers;
/*
 * Module      :    PromoDamage.php
 * Program Desc:    Promo damage entry controller
 * Author      :    Arnel A. Oquien
 * Date Created:    Nov. 23, 2020
*/


use CodeIgniter\Controller;
use App\Models\Mytrx_whcrossing_model;
use App\Models\Mymelibsys_model;
use App\Models\MyDatummodel;
use App\Models\MyDatauaModel;
use App\Models\MyWarehousercvModel;
use App\Models\MyLibzDBModel;
use App\Libraries\Fpdf\Mypdf;
class Warehouse_rcvng extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(1);
		$this->mywhrcvng =  new MyWarehousercvModel();
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
	}
	public function index(){
	$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='184'","myua_trx");
		if($result == 1):
		echo view('templates/myheader');
		echo view('warehouse_rcvng/warehouse_rcvng_main');
		echo view('templates/myfooter');
		else:
    	echo view('templates/myheader');
		echo view('errors/html/error_404',$this->data);
		echo view('templates/myfooter');
		endif;
	
	}	

	public function wshe_rvng_upld(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='185'","myua_trx");
		if($result == 0):
			echo "Upload Failed: You are not authorized.";
			die();
		endif;

		$data = $this->mywhrcvng->whrcvng_upld();

		if($data['response'] == true){
			return view('warehouse_rcvng/tbl_item_scanned',$data);
		}
		else{
			echo $data['result'];
		}

	
	}


	public function wshe_rvng_save()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='186'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcvng->mywhrcvng_save();
	}


	// VIEWING RECORDS	
	public function whcdrcvng_ent_recs() 
	{ 
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='187'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mywhrcvng->view_ent_recs($mpages,10,$txtsearchedrec);
		return view('warehouse_rcvng/warehouse_rcvng_recs',$data);
	}

	public function whcdrcvng_ent_itm_recs() 
	{ 
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='187'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mywhrcvng->view_ent_itm_recs($mpages,50,$txtsearchedrec);
		return view('warehouse_rcvng/warehouse_rcvng_itm_recs',$data);
	}
	

}  //end main class
