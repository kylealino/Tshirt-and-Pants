<?php namespace App\Controllers;
/*
 * Module      :    Fgpack_request.php
 * Type 	   :    Controllers
 * Program Desc:    Fgpack_request
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
use App\Models\MyFGRequestModel;
use App\Libraries\Fpdf\Mypdf;
class Fgpack_request extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(0);
		$this->mywhrcdout =  new MyWarehouseoutModel();
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->mytrxrmreq = new MyFGRequestModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		    // your db connection
   		$this->db = \Config\Database::connect();

	}

	public function index(){

		echo view('templates/myheader');
		echo view('mtap/fg_request/fg_request_main');
		echo view('templates/myfooter');

	} //end index
	
	public function refreshPage($url) {

		return redirect()->to(urldecode($url));

	} //end refreshPage

	public function fgpack_req_save() { 

        $this->mytrxrmreq->fgpack_req_entry_save();

    } //end fgpack_req_save

	public function mat_article_fgpo(){

		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term    = $this->request->getVar('term');

		$autoCompleteResult = array();

		$str = "
		SELECT
		aa.`recid`,
		aa.`ART_CODE`,
		aa.`ART_DESC`,
		aa.`ART_UPRICE`,
		(SELECT SUM(po_qty) FROM rm_inv_rcv WHERE mat_code = aa.`ART_CODE`) as po_qty

		FROM 
		`mst_article` aa
		LEFT JOIN
		`rm_inv_rcv` bb
		ON
		aa.`ART_CODE` =  bb.`mat_code`

		WHERE ART_PRODT = 'FG' AND (ART_HIERC1 = 'TSHIRT' OR ART_HIERC1 = 'PANTS')
		LIMIT 50
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) {
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn);
				array_push($autoCompleteResult,array("value" => $row['ART_CODE'],
					"mtkn_rid" => $mtkn_rid,
					"ART_CODE"=> $row['ART_CODE'],
					"ART_DESC"=>$row['ART_DESC'],
					"ART_UPRICE"=>$row['ART_UPRICE'],
					"po_qty"=>$row['po_qty']


				));
			endforeach;
		}

		$q->freeResult();
		echo json_encode($autoCompleteResult);
		
	} //end mat_article_fgpo

	public function fgpack_req_vw() { 

        $data = $this->mytrxrmreq->rm_req_rec_view(1,20);
        return view('mtap/fg_request/fg_request_recs',$data);

    } //end fgpack_req_vw

	public function fgpack_req_itm_recs() { 

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mytrxrmreq->rm_req_view_itm_recs($mpages,50,$txtsearchedrec);
		return view('mtap/fg_request/fg_request_item_recs',$data);

	} //end fgpack_req_itm_recs

	public function fgpack_req_recs() { 

        $txtsearchedrec = $this->request->getVar('txtsearchedrec');
        $mpages = $this->request->getVar('mpages');
        $mpages = (empty($mpages) ? 0: $mpages);
        $data = $this->mytrxrmreq->rm_req_rec_view($mpages,20,$txtsearchedrec);
        return view('mtap/fg_request/fg_request_recs',$data);

    } //end fgpack_req_recs

}  
