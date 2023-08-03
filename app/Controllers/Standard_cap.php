<?php namespace App\Controllers;
/*
 * Module      :    Standard_cap.php
 * Type 	   :    Controllers
 * Program Desc:    Standard_cap
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/

use CodeIgniter\Controller;
use App\Models\Mymelibsys_model;
use App\Models\MyDatummodel;
use App\Models\MyDatauaModel;
use App\Models\MyLibzDBModel;
use App\Models\MyStandardCapModel;

use App\Libraries\Fpdf\Mypdf;
class Standard_cap extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(0);
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->mystandcap = new MyStandardCapModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
   		$this->db = \Config\Database::connect();
	}//end contruct

	public function index(){

		echo view('templates/myheader');
		echo view('mtap/standard_cap/standard_cap_main');
		echo view('templates/myfooter');

	} //end index

    public function search_standard_cap_branch(){ 

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
		
	} //end search_standard_cap_branch

	public function standard_cap_upld(){

		$data = $this->mystandcap->standard_cap_upld();
		return view('mtap/standard_cap/standard_cap_item_scanned',$data);

	} //end standard_cap_upld

	public function standard_cap_save(){

		$this->mystandcap->standard_cap_entry_save();

	} //end standard_cap_save

	public function standard_cap_vw() { 

        $data = $this->mystandcap->standard_cap_rec_view(1,90);
        return view('mtap/standard_cap/standard_cap_recs',$data);

    } //end standard_cap_vw

	public function standard_cap_itm_recs() { 

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mystandcap->standard_cap_view_itm_recs($mpages,50,$txtsearchedrec);
		return view('mtap/standard_cap/standard_cap_item_recs',$data);

	} //end standard_cap_itm_recs

	public function standard_cap_vw_list() { 

        $data = $this->mystandcap->standard_cap_rec_view_list(1,90);
        return view('mtap/standard_cap/standard_cap_recs_list',$data);

    } //end standard_cap_vw_list

	public function standard_cap_itm_recs_list() { 

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mystandcap->standard_cap_view_itm_recs_list($mpages,50,$txtsearchedrec);
		return view('mtap/standard_cap/standard_cap_item_recs_list',$data);

	} //end standard_cap_itm_recs_list

	public function standard_cap_update(){

		$this->mystandcap->standard_cap_update();

	} //end standard_cap_update

} 
