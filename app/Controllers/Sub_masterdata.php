<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Mytrx_whcrossing_model;
use App\Models\Mymelibsys_model;
use App\Models\MyDatummodel;
use App\Models\MyDatauaModel;
use App\Models\MyWarehouseoutModel;
use App\Models\MyLibzDBModel;
use App\Models\MySubItemsModel;

use App\Libraries\Fpdf\Mypdf;
class Sub_masterdata extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(0);
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->mysubitems = new MySubItemsModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
   		$this->db = \Config\Database::connect();
	}

	public function index(){

		echo view('templates/myheader');
		echo view('mtap/masterdata/sub_item_main');
		echo view('templates/myfooter');

	} //end	index

    public function sub_item_recs() { 
		$data = $this->mysubitems->sub_items_view_recs();
		return view('mtap/masterdata/sub_item_recs',$data);
	} //end sub_item_recs

    public function sub_item_save(){

		$this->mysubitems->sub_items_entry_save();

	} //end	sub_item_save

	public function sub_item_update(){

		$this->mysubitems->sub_items_update();

	} //end	sub_item_update

	public function get_main_itemc(){
		
		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term    = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		SELECT
		aa.`recid`,
		aa.`ART_CODE`,
		aa.`ART_DESC`

		FROM 
		`mst_article` aa

		WHERE `ART_HIERC4` LIKE '%4766%'

		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) {
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn);
				array_push($autoCompleteResult,array("value" => $row['ART_CODE'],
					"mtkn_rid" => $mtkn_rid


				));
			endforeach;
		}

		$q->freeResult();
		echo json_encode($autoCompleteResult);
		
	} //end get_main_itemc	

	public function get_uom(){
		
		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term    = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
			SELECT `recid`, `ART_UOM` FROM mst_article WHERE `ART_UOM` REGEXP '^[A-Za-z]+$'GROUP BY `ART_UOM`
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) {
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn);
				array_push($autoCompleteResult,array("value" => $row['ART_UOM'],
					"mtkn_rid" => $mtkn_rid


				));
			endforeach;
		}

		$q->freeResult();
		echo json_encode($autoCompleteResult);
		
	} //end get_uom	

}
