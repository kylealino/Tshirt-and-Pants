<?php namespace App\Controllers;
/*
 * Module      :    FgPo_rcvng.php
 * Type 	   :    Controllers
 * Program Desc:    FgPo_rcvng
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
use App\Models\MyFGPurchaseModel;
use App\Libraries\Fpdf\Mypdf;

class FgPo_rcvng extends BaseController 
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
		$this->mytrxgr = new MyFGPurchaseModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
		    // your db connection
   		$this->db = \Config\Database::connect();
	}

	public function index(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='206'","myua_trx");
		if($result == 1):
		 //echo "string";
		echo view('templates/myheader');
		echo view('mtap/fgpo_rcvng/warehouse_fg_rcvng_main');
		echo view('templates/myfooter');
		else:
    	echo view('templates/myheader');
		echo view('errors/html/error_404',$this->data);
		echo view('templates/myfooter');
		endif;
	
	
	} //end index

	public function auto_add_lines_gr(){ 

		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$total_line = $this->request->getVar('total_line');
		$itemcode = $this->request->getVar('itemcode');
		$itemdesc = $this->request->getVar('itemdesc');
		$sku = $this->request->getVar('sku');
		$ucost = $this->request->getVar('ucost');
		$uprice = $this->request->getVar('uprice');
		$__rid = $this->request->getVar('__rid');
		
		for($xx = 0; $xx < $total_line; ++$xx) {
			
				$chtml = "
					<script>
						my_add_line_item('$itemcode','$itemdesc','$sku','$ucost','$uprice','$__rid');
					</script>
					

					";
				echo $chtml;
		}

	} //end auto_add_lines_gr

	public function fg_ent_rcvng_recs(){

		$data    = $this->mytrxgr->view_fg_rcvng_recs();
		if($data['response']):
		return view('mtap/fgpo_rcvng/warehouse_fg_rcvng_recs',$data);
		else:
		$dta['msg'] = 'No records found!';
		return view('components/no-records',$dta);
		endif;

	} //end fg_ent_rcvng_recs

	public function wshe_fg_rvng_upld(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='185'","myua_trx");
		if($result == 0):
			echo "Upload Failed: You are not authorized.";
			die();
		endif;

		$data = $this->mytrxgr->wh_fg_rcvng_upld();

		if($data['response'] == true){
			return view('mtap/fgpo_rcvng/warehouse_fg_upload_recs',$data);
		}
		else{
			echo $data['result'];
		}
	
	} //end wshe_fg_rvng_upld

	public function wshe_fg_rcvng_save(){

		$this->mytrxgr->mywh_fg_rcvng_save();
	} //end wshe_fg_rcvng_save

	public function wshe_fg_rcvng_itm_recs() { 

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='187'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mytrxgr->fg_view_ent_itm_recs($mpages,50,$txtsearchedrec);
		return view('mtap/fgpo_rcvng/warehouse_fg_rcvng_item_recs',$data);

	} //end wshe_fg_rcvng_itm_recs

}
