<?php namespace App\Controllers;
/*
 * Module      :    Fgp_out.php
 * Type 	   :    Controllers
 * Program Desc:    Fgp_out
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/


use CodeIgniter\Controller;
use App\Models\Mytrx_whcrossing_model;
use App\Models\Mymelibsys_model;
use App\Models\MyDatummodel;
use App\Models\MyDatauaModel;
use App\Models\MyFgpOutgoing;
use App\Models\MyLibzDBModel;
use App\Libraries\Fpdf\Mypdf;
class Fgp_out extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(1);
		$this->mywhrcdout =  new MyFgpOutgoing();
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
		echo view('mtap/fgp_out/fgp_out_main');
		echo view('templates/myfooter');
		else:
    	echo view('templates/myheader');
		echo view('errors/html/error_404',$this->data);
		echo view('templates/myfooter');
		endif;
	
	
	} //end index

	public function wshe_out_upld() { 
		$data    = $this->mywhrcdout->whrcdout_upld();

		if($data['response'] == true){
			return view('warehouse_out/warehouse_out_item_scanned',$data);
		}
		else{
			echo $data['result'];
		}
	} //end wshe_out_upld

	public function fgp_out_save(){

		$this->mywhrcdout->fgpout_save();
	
	} //end fgp_out_save

	public function wshe_out_report(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='232'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('warehouse_out/warehouse_out_report');
	} //end wshe_out_report

	public function  wshe_report_dl(){
		$this->mywhrcdout->wshe_report_download();
	} //end wshe_report_dl

	public function fgp_out_update(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='179'","myua_trx");
		if($result == 0):
			echo "Update Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcdout->fgpout_update();
	} //end fgp_out_update

	public function fgp_out_hd_update(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='179'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcdout->fgpout_update_hd();
	} //end fgp_out_hd_update

	public function fgp_out_hd_updt(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='179'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcdout->fgpout_update_hd();
	} //end fgp_out_hd_updt

	public function fgp_out_recs() { 

		$data    = $this->mywhrcdout->view_fgout_recs();
		if($data['response']):
		return view('mtap/fgp_out/fgp_out_recs',$data);
		else:
		$dta['msg'] = 'No records found!';
		return view('components/no-records',$dta);
		endif;
	} //end fgp_out_recs

	public function fgp_out_done(){

		$this->mywhrcdout->fgp_done();
	} //end fgp_out_done

	public function fgp_out_revert(){

		$this->mywhrcdout->fgp_out_revert();
	} //end fgp_out_revert

	public function whcdinv_box_content(){

		$data    = $this->mywhrcdout->view_box_content_recs();
		return view('warehouse_out/warehouse_box_content_recs',$data);

	} //end whcdinv_box_content

	public function fgp_out_print(){
	
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('mtap/fgp_out/fgp_out_print');
	} //end fgp_out_print

	public function fgp_out_print_mkg(){

		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('warehouse_out/warehouse_out_print_mkg');

	} //end fgp_out_print_mkg

	public function fgp_out_fprint(){

		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('mtap/fgp_out/fgp_out_fprint');
	} //end fgp_out_fprint

	public function mywhout_fprint_mkg(){

		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('warehouse_out/warehouse_out_fprint_mkg');
	
	} //end mywhout_fprint_mkg

	public function fgp_out_backload_item(){

		$data    = $this->mywhrcdout->fgp_out_backload_data();
		//var_dump($data);
		echo view('mtap/fgp_out/fgp_out_backload_recs',$data);
	} //end fgp_out_backload_item

	public function fgp_out_show() { 
		$data = $this->mywhrcdout->fgpout_show();

		if($data['response'] == true){
			return view('mtap/fgp_out/fgp_out_item_scanned',$data);
		}
		else{
			echo $data['result'];
		}
	} //end fgp_out_show

}