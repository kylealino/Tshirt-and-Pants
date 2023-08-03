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
use App\Models\MyWarehouseinvModel;
use App\Models\MyLibzSysModel;
use App\Models\MyLibzDBModel;
use App\Libraries\Fpdf\Mypdf;

class Warehouse_inv extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(1);
		$this->mywhrcinv =  new MyWarehouseinvModel();
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mylibzsys = new  MyLibzSysModel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
	}
	public function index(){
	$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='195'","myua_trx");
		if($result == 1):
		 echo "string";
		echo view('templates/myheader');
		echo view('warehouse_inv/warehouse_inv_main');
		echo view('templates/myfooter');
		else:
	    	echo view('templates/myheader');
			echo view('errors/html/error_404',$this->data);
			echo view('templates/myfooter');
		endif;
	
	}	

	public function wshe_rvng_upld(){
		$data = $this->mywhrcinv->whrcvng_upld();

		if($data['response'] == true){
			return view('warehouse_rcvng/tbl_item_scanned',$data);
		}
		else{
			echo $data['result'];
		}

	
	}


	public function wshe_rvng_save()
	{

		$this->mywhrcinv->mywhrcinv_save();
	}


	// VIEWING RECORDS	
	public function whcdrcvng_ent_recs() 
	{ 

	

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mywhrcinv->view_ent_recs($mpages,10,$txtsearchedrec);
		return view('warehouse_rcvng/warehouse_rcvng_recs',$data);
	}

	public function whcdinv_itm_recs() 
	{ 
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='196'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		$data    = $this->mywhrcinv->view_ent_itm_recs();

		if($data['counts'] == 0 ):
			return view('errors/html/norecords');
		endif;
		
		return view('warehouse_inv/warehouse_inv_itm_recs',$data);
	}
	
	public function whcdinv_box_content(){

		$data    = $this->mywhrcinv->view_box_content_recs();
		return view('warehouse_inv/warehouse_box_content_recs',$data);


	}

	public function whcdinv_report_show(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='197'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('warehouse_inv/warehouse_inv_reports');
	
	}

	public function generate_report(){

		$rtype = $this->request->getVar('report_type');
		if($rtype == 'stockcodelist'):
			 $result = $this->mywhrcinv->generate_report_stkcode();
		elseif($rtype == 'in'):
 			$result = $this->mywhrcinv->generate_report_in();
		elseif($rtype == 'out'):
 			$result = $this->mywhrcinv->generate_report_out();
		else:
			$result = $this->mywhrcinv->generate_report_summary();
		endif;
	
		// if(!$result['result']){
		// 	$data = array(
		// 		'result' => false,
		// 		'data' => $result['data']
		// 	);
		// }
		// else{
		// 	$data = array(
		// 		'result' => true,
		// 		'data' => $result['data']
		// 	);
		// }

		// echo json_encode($data);
	
	}

	public function whcdinv_incoming(){
		$data    = $this->mywhrcinv->incoming_items();
		return view('warehouse_inv/warehouse_inv_incoming',$data);
	}

	public function whcdinv_outbound(){
		
		$data    = $this->mywhrcinv->outbound_items();
		return view('warehouse_inv/warehouse_inv_outbound',$data);
	}


	//transfer//

	// load view
	public function whcdinv_tranfer_vw(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='198'","myua_trx");
		if($result == 1):
		echo view('templates/myheader');
		echo view('warehouse_inv/warehouse_inv_transfer_main');
		echo view('templates/myfooter');
		else:
    	echo view('templates/myheader');
		echo view('errors/html/error_404',$this->data);
		echo view('templates/myfooter');
		endif;
	}

	public function whcdinv_rackbintrans(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='199'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('warehouse_inv/warehouse_inv_rack_transfer');
	}

	public function get_barcode_inv(){
		 $this->mywhrcinv->get_wshe_inv_barcode();
	}

	public function whcdinv_save_transfer(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='200'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		 $this->mywhrcinv->save_transfer();
	}

	public function whcdinv_transfer_recs(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='201'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		 $data = $this->mywhrcinv->view_rack_transfer_recs();
		 return view('warehouse_inv/warehouse_inv_rack_transfer_recs',$data);
	

	}

	public function whcdinv_transfer_print(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='204'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('warehouse_inv/warehouse_inv_rack_transfer_print');

	}

	public function whcdinv_transfer_upload(){//load view
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='202'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('warehouse_inv/warehouse_inv_rack_transfer_upload');
	}

	public function whcdinv_transfer_upload_recs(){//load view
		$result = $this->mywhrcinv->rack_transfer_upld();
		if($result['result'] === false){
			echo $result['data'];
		}
		else{
			return view('warehouse_inv/warehouse_inv_rack_scanned_recs',$result);
		}
		
	}

	public function whcdinv_transfer_upload_sv(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='203'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcinv->save_transfer_upload();
	}

}  //end main class
