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
use App\Models\MyWarehouseoutModel;
use App\Models\MyLibzDBModel;
use App\Models\Mytrx_gi_Model;
use App\Libraries\Fpdf\Mypdf;

class Mytrx_gi extends BaseController 
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
		$this->mytrxgi = new Mytrx_gi_Model();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
		    // your db connection
   		$this->db = \Config\Database::connect();
	}


	public function index(){
	$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='225'","myua_trx");
		if($result == 1):
		 //echo "string";
		echo view('templates/myheader');
		echo view('gi_entry/warehouse_gi_main');
		echo view('templates/myfooter');
		else:
    	echo view('templates/myheader');
    	
		echo view('errors/html/error_404',$this->data);
		echo view('templates/myfooter');
		endif;
	
	
	}	


	public function gi_ent_recs(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='228'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;

		$data    = $this->mytrxgi->gi_ent_recs();
		if($data['response']):
		return view('gi_entry/warehouse_gi_recs',$data);
		else:
		$dta['msg'] = 'No records found!';
		return view('components/no-records',$dta);
		endif;
	}

	public function gi_ent_upld(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='226'","myua_trx");
		if($result == 0):
			$dta['msg'] = 'Upload Failed: You are not authorized.';
			return view('components/no-records',$dta);
		endif;

		$data = $this->mytrxgi->wh_gi_ent_upld();

		
		return view('gi_entry/warehouse_gi_upload_recs',$data);
	
			//echo $data['result'];
	

	
	}

	public function gi_ent_save()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='227'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to save the data.";
			die();
		endif;
		$this->mytrxgi->mywh_gi_ent_save();
	}

	public function gi_ent_itm_recs() 
	{ 
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='228'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;

		$data    = $this->mytrxgi->view_ent_itm_recs();
		return view('gi_entry/warehouse_gi_item_recs',$data);
	}


	public function gi_approval_vw(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='230'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('gi_entry/warehouse_gi_approval');
	}

	public function gi_approval_recs(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='230'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		$data = $this->mytrxgi->gi_approval_recs();
		return view('gi_entry/warehouse_gi_approval_recs',$data);
		
	}

	public function gi_approval_sv(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='231'","myua_trx");
		if($result == 0):
			echo $this->memelibsys->warning_msg("#dc3545","text-danger","It appears that you don't have permission to post");
			die();
		endif;
		$this->mytrxgi->gi_approving();
	}

	public function gi_print(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='229'","myua_trx");
		if($result == 0):
		echo view('errors/html/error_404',$this->data);
		die();
		endif;
	
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('gi_entry/warehouse_gi_print');

	
	}

}  //end main class
