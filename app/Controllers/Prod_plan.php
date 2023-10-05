<?php namespace App\Controllers;
/*
 * Module      :    Prod_plan.php
 * Type 	   :    Controllers
 * Program Desc:    Prod_plan
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
use App\Models\MyProdPlanModel;

use App\Libraries\Fpdf\Mypdf;
class Prod_plan extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(0);
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->myprodplan = new MyProdPlanModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
   		$this->db = \Config\Database::connect();
	}

	public function index(){

		echo view('templates/myheader');
		echo view('mtap/prod_plan/prod_plan_main');
		echo view('templates/myfooter');

	} //end	index

	public function prod_plan_upld(){

		$data = $this->myprodplan->prod_plan_upld();
		return view('mtap/prod_plan/prod_plan_item_scanned',$data);

	} //end	prod_plan_upld

	public function prod_plan_save(){

		$this->myprodplan->prod_plan_entry_save();

	} //end	prod_plan_save

	public function prod_plan_delete(){

		$this->myprodplan->prod_plan_entry_delete();

	} //end	prod_plan_delete

	public function prod_plan_vw() { 

        $data = $this->myprodplan->prod_plan_rec_view(1,90);
        return view('mtap/prod_plan/prod_plan_recs',$data);

    } //end	prod_plan_vw

	public function prod_plan_itm_recs() { 

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->myprodplan->prod_plan_view_itm_recs($mpages,50,$txtsearchedrec);
		return view('mtap/prod_plan/prod_plan_item_recs',$data);

	} //end	prod_plan_itm_recs

	public function search_prod_plan_branch(){ 

        $term = $this->request->getVar('term');

        $autoCompleteResult = array();

		$str = "
		SELECT 
		a.`BRNCH_NAME` __mdata
		FROM 
		mst_companyBranch a
		WHERE  
		a.`BRNCH_NAME` like '%{$term}%'
		ORDER
		by BRNCH_NAME limit 15 
		";

        
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array("value" => $row['__mdata']
				));
				
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
		
	} //end	search_prod_plan_branch

}
