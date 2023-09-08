<?php namespace App\Controllers;
/*
 * Module      :    Rm_outbound.php
 * Type 	   :    Controllers
 * Program Desc:    Rm_outbound
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/


use CodeIgniter\Controller;
use App\Models\Mytrx_whcrossing_model;
use App\Models\Mymelibsys_model;
use App\Models\MyDatummodel;
use App\Models\MyDatauaModel;
use App\Models\MyRMOutboundModel;
use App\Models\MyLibzDBModel;
use App\Libraries\Fpdf\Mypdf;
class Rm_outbound extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(1);
		$this->myrmout =  new MyRMOutboundModel();
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
	}

	public function index(){

		echo view('templates/myheader');
		echo view('mtap/rm_outbound/rm_out_main');
		echo view('templates/myfooter');
	} //end index

	public function index_v2(){

		echo view('templates/myheader');
		echo view('mtap/rm_outbound/rm_out_main_v2');
		echo view('templates/myfooter');

	} //end index_v2

	public function rm_out_save(){

		$this->myrmout->rm_save();
	
	} //end rm_out_save

	public function rm_out_req_save(){

		$this->myrmout->rm_req_save();
	
	} //end rm_out_req_save

	public function rm_out_recs() { 

		$data = $this->myrmout->rm_out_view_recs();
		return view('mtap/rm_outbound/rm_out_recs',$data);

	} //end rm_out_recs

	public function rm_out_itm_recs() { 

		$data = $this->myrmout->rm_out_view_itm_recs();
		return view('mtap/rm_outbound/rm_out_item_recs',$data);

	} //end rm_out_itm_recs

	public function rm_out_vw_process() { 

		$data = $this->myrmout->rm_out_vw_process();
		return view('mtap/rm_outbound/rm_out_request',$data);

	} //end rm_out_vw_process

	public function rm_out_vw_produce() { 

		$data = $this->myrmout->rm_out_vw_produce();
		return view('mtap/rm_outbound/rm_out_produce',$data);

	} //end rm_out_vw_produce

	public function rm_out_vw_lacking() { 

		$data = $this->myrmout->rm_out_vw_lacking();
		return view('mtap/rm_outbound/rm_out_lacking',$data);

	} //end rm_out_vw_lacking

	public function rm_out_print(){
	
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('mtap/rm_outbound/rm_out_print');

	} //end rm_out_print

	public function fg_out_print(){
	
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('mtap/rm_outbound/rm_out_print_fg');

	} //end rm_out_print

}  
