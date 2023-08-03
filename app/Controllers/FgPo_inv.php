<?php namespace App\Controllers;
/*
 * Module      :    FgPo_inv.php
 * Type 	   :    Controllers
 * Program Desc:    FgPo_inv
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/


use CodeIgniter\Controller;
use App\Models\Mytrx_whcrossing_model;
use App\Models\Mymelibsys_model;
use App\Models\MyDatummodel;
use App\Models\MyDatauaModel;
use App\Models\MyFGPurchaseModel;
use App\Models\MyLibzSysModel;
use App\Models\MyLibzDBModel;
use App\Libraries\Fpdf\Mypdf;

class FgPo_inv extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(1);
		$this->mywhrcinv =  new MyFGPurchaseModel();
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

		$data = $this->mywhrcinv->fg_inv_rec_view(1,50);
		echo view('templates/myheader');
		echo view('mtap/fgpo_inv/warehouse_fg_inv_main',$data);
		echo view('templates/myfooter');
	
	} //end index

	public function wshe_rvng_upld(){

		$data = $this->mywhrcinv->whrcvng_upld();

		if($data['response'] == true){
			return view('warehouse_rcvng/tbl_item_scanned',$data);
		}
		else{
			echo $data['result'];
		}

	} //end wshe_rvng_upld

	public function fgpo_report_vw(){

		return view('mtap/fgpo_inv/fgp_report');

	} //end fgpo_report_vw

	public function fgpo_report_recs(){

		$data = $this->mywhrcinv->fg_inv_rec_view_recs();
		return view('mtap/fgpo_inv/fgp_report_recs',$data);

	} //end fgpo_report_recs

	public function wshe_rvng_save(){

		$this->mywhrcinv->mywhrcinv_save();

	} //end wshe_rvng_save

	public function whcdrcvng_ent_recs() { 

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mywhrcinv->view_ent_recs($mpages,10,$txtsearchedrec);
		return view('warehouse_rcvng/warehouse_rcvng_recs',$data);

	} //end whcdrcvng_ent_recs

	public function fgpo_itm_recs() { 

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='196'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
	
		return view('mtap/fgpo_inv/warehouse_fg_itm_recs');

	} //end fgpo_itm_recs

	public function whcdinv_items_api(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='196'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;

		$data    = $this->mywhrcinv->view_ent_itm_recs();

	
		echo json_encode($data);

	} //end whcdinv_items_api
	
	public function fgpo_box_content(){

		$data    = $this->mywhrcinv->fg_view_box_content_recs();
		return view('mtap/fgpo_inv/warehouse_fg_box_content_recs',$data);

	} //end fgpo_box_content

	public function whcdinv_report_show(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='197'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('warehouse_inv/warehouse_inv_reports');
	
	} //end whcdinv_report_show

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
	
	} //end generate_report

	public function whcdinv_incoming(){

		$data    = $this->mywhrcinv->incoming_items();
		return view('warehouse_inv/warehouse_inv_incoming',$data);

	} //end whcdinv_incoming

	public function whcdinv_outbound(){
		
		$data    = $this->mywhrcinv->outbound_items();
		return view('warehouse_inv/warehouse_inv_outbound',$data);
	} //end whcdinv_outbound

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

	} //end whcdinv_tranfer_vw

	public function whcdinv_rackbintrans(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='199'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('warehouse_inv/warehouse_inv_rack_transfer');

	} //end whcdinv_rackbintrans

	public function get_barcode_inv(){

		 $this->mywhrcinv->get_wshe_inv_barcode();

	} //end get_barcode_inv

	public function whcdinv_save_transfer(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='200'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		 $this->mywhrcinv->save_transfer();

	} //end whcdinv_save_transfer

	public function whcdinv_transfer_recs(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='201'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		 $data = $this->mywhrcinv->view_rack_transfer_recs();
		 return view('warehouse_inv/warehouse_inv_rack_transfer_recs',$data);

	} //end whcdinv_transfer_recs

	public function whcdinv_transfer_print(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='204'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('warehouse_inv/warehouse_inv_rack_transfer_print');

	} //end whcdinv_transfer_print

	public function whcdinv_transfer_upload(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='202'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('warehouse_inv/warehouse_inv_rack_transfer_upload');

	} //end whcdinv_transfer_upload

	public function whcdinv_transfer_upload_recs(){

		$result = $this->mywhrcinv->rack_transfer_upld();
		if($result['result'] === false){
			echo $result['data'];
		}
		else{
			return view('warehouse_inv/warehouse_inv_rack_scanned_recs',$result);
		}
		
	} //end whcdinv_transfer_upload_recs

	public function whcdinv_transfer_upload_sv(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='203'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhrcinv->save_transfer_upload();

	} //end whcdinv_transfer_upload_sv

	public function show(){

		return view('warehouse_inv/sample');

	} //end show

	public function fgpo_items_api2(){

		$draw   = $this->request->getVar('draw');
		$start  = $this->request->getVar('start');
		$length = $this->request->getVar('length');
		$data   = $this->mywhrcinv->fg_view_ent_itm_recs_v2($start,$length);

		$json = array();
		if($data['counts'] > 0){
		foreach($data['rlist'] as $row){
			$txt_mtknr = $row['txt_mtknr'];
			$viewBtn = "<button class=\"btn btn-dgreen\" > <i class=\"bi bi-box-seam\"></i> View </button>";
			if($row['qty'] == 0){
				$viewBtn = '<i class="bi bi-dash-lg text-success p-4"></i>';
			}
			$json[] = array(
				$viewBtn,
				$row['stock_code'],
				$row['ART_CODE'],
				$row['ART_DESC'],
				'BOX',
				$row['qty'],
				$row['convf'],
				$row['total_pcs_scanned'],
				$row['tamt_scanned'],
				$row['remarks'],
				$row['plnt_code'],
				$row['wshe_code'],
				$row['wshe_bin_name'],
				$row['wshe_grp'],
				$row['barcde'],
				$row['box_no'],
				$row['muser'],
				$row['encd'],
				$row['type'],
				$row['SD_NO'],
				$txt_mtknr
			);

			}
		}
		else{
			echo('no record found');
		}
			$response = array(
				'draw' => $draw,
				'recordsTotal' => $data['totalRows'], 
				'recordsFiltered'=> $data['filertedRows'],
				'data' => $json

			);

			echo json_encode($response);
	} //end fgpo_items_api2

}
