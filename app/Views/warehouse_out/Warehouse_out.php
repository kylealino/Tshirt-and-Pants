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
use App\Libraries\Fpdf\Mypdf;
class Warehouse_out extends BaseController 
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
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
	}
	public function index(){
	$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='177'","myua_trx");
		if($result == 1):
		 //echo "string";
		echo view('templates/myheader');
		echo view('warehouse_out/warehouse_out_main');
		echo view('templates/myfooter');
		else:
    	echo view('templates/myheader');
		echo view('errors/html/error_404',$this->data);
		echo view('templates/myfooter');
		endif;
	
	
	}	

	public function wshe_out_upld() 
	{ 
		$data    = $this->mywhrcdout->whrcdout_upld();

		if($data['response'] == true){
			return view('warehouse_out/warehouse_out_item_scanned',$data);
		}
		else{
			echo $data['result'];
		}
	}

	public function wshe_out_save()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='178'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcdout->mywhout_save();
	
	
	}

	public function wshe_out_update()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='179'","myua_trx");
		if($result == 0):
			echo "Update Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcdout->mywhout_update();
	}

		public function wshe_out_hd_update()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='179'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcdout->mywhout_update_hd();
	
	
	}

	public function wshe_out_hd_updt(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='179'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcdout->mywhout_update_hd();
	}

	// VIEWING RECORDS	
	public function whcdout_ent_recs() 
	{ 

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='180'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;

		$data    = $this->mywhrcdout->view_ent_recs();
		if($data['response']):
		return view('warehouse_out/warehouse_out_recs',$data);
		else:
		$dta['msg'] = 'No records found!';
		return view('components/no-records',$dta);
		endif;
	}



	public function wshe_out_done()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='183'","myua_trx");
		if($result == 0):
			echo "Update Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcdout->mywhout_done();
	}

	
	public function whcdinv_box_content()
	{

		$data    = $this->mywhrcdout->view_box_content_recs();
		return view('warehouse_out/warehouse_box_content_recs',$data);

	}

	public function mywhout_print(){
	
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='181'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('warehouse_out/warehouse_out_print');
	}

	public function mywhout_print_mkg(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='181'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
			$this->response->setHeader('Content-Type', 'application/pdf');
			return view('warehouse_out/warehouse_out_print_mkg');

	}

	public function mywhout_fprint(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='182'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('warehouse_out/warehouse_out_fprint');
	
	}


	public function mywhout_fprint_mkg(){
			$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='182'","myua_trx");
			if($result == 0):
				echo view('errors/html/error_404',$this->data);
				die();
			endif;
			$this->response->setHeader('Content-Type', 'application/pdf');
			return view('warehouse_out/warehouse_out_fprint_mkg');
	
	}

	public function get_backload_item(){

		$data    = $this->mywhrcdout->get_backload_data();
		//var_dump($data);
		echo view('warehouse_out/warehouse_out_backload_recs',$data);
	}

	public function wshe_out_show() 
	{ 
		$data = $this->mywhrcdout->whrcdout_show();

		if($data['response'] == true){
			return view('warehouse_out/warehouse_out_item_scanned',$data);
		}
		else{
			echo $data['result'];
		}
	}

}  //end main class
