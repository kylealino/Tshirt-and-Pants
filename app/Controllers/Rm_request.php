<?php namespace App\Controllers;
/*
 * Module      :    Rm_request.php
 * Type 	   :    Controllers
 * Program Desc:    Rm_request
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
use App\Models\MyRMRequestModel;
use App\Libraries\Fpdf\Mypdf;
class Rm_request extends BaseController 
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
		$this->mytrxrmreq = new MyRMRequestModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		    // your db connection
   		$this->db = \Config\Database::connect();
	}

	public function index(){

		echo view('templates/myheader');
		echo view('mtap/rm_request/rm_request_main');
		echo view('templates/myfooter');

	} //end index

	public function rm_req_save() { 

        $this->mytrxrmreq->rm_req_entry_save();

    } //end rm_req_save

	public function rm_req_process() { 

        $data = $this->mytrxrmreq->rm_req_process_view(1,90);
        return view('mtap/rm_request/rm_request_process',$data);

    } //end rm_req_process

	public function rm_req_process_save() { 

        $this->mytrxrmreq->rm_req_process_save();

    } //end rm_req_process_save

	public function mat_article_fgpo(){
		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term    = $this->request->getVar('term');

		$autoCompleteResult = array();

		$str = "
		SELECT 
		a.`recid`,
		a.`ART_DESC`,
		a.`ART_CODE`,
		a.`ART_SKU`,
		a.`ART_SDU`,
		a.`ART_IMG`,
		a.`ART_NCBM`,
		a.`ART_NCONVF`,
		a.`ART_UPRICE`,
		a.`ART_UCOST`,
		a.`ART_BARCODE1`,
		a.`ART_HIERC3`,
		a.`ART_HIERC4`,
		a.`ART_UOM`,
		sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr,
		(SELECT SUM(`item_qty`) FROM mst_item_comp2 WHERE fg_code = a.`ART_CODE`) rm_qty
		FROM
		 `mst_article` a

		 where a.`ART_PRODT` = 'FG' AND (a.`ART_PRODL` = 'GWEMC' OR a.`ART_PRODL` = 'LSG' or a.`ART_PRODL` = '0300') AND a.`ART_CODE` LIKE '%{$term}%'
		LIMIT 100
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
					"rm_qty"=>$row['rm_qty']


				));
			endforeach;
		}

		$q->freeResult();
		echo json_encode($autoCompleteResult);
		
	} //end mat_article_fgpo

	public function rm_req_vw() { 

        $data = $this->mytrxrmreq->rm_req_rec_view(1,90);
        return view('mtap/rm_request/rm_request_recs',$data);

    } //end rm_req_vw

	public function rm_req_itm_recs() { 

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mytrxrmreq->rm_req_view_itm_recs($mpages,50,$txtsearchedrec);
		return view('mtap/rm_request/rm_request_item_recs',$data);

	} //end rm_req_itm_recs

	public function rm_req_recs() { 

        $txtsearchedrec = $this->request->getVar('txtsearchedrec');
        $mpages = $this->request->getVar('mpages');
        $mpages = (empty($mpages) ? 0: $mpages);
        $data = $this->mytrxrmreq->rm_req_rec_view($mpages,20,$txtsearchedrec);
        return view('mtap/rm_request/rm_request_recs',$data);

    } //end rm_req_recs

	public function rm_req_bom_print(){
	
		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('mtap/rm_request/rm_request_bom_print');

	} //end rm_req_bom_print

	public function search_rmap_subcon(){ 

        $term = $this->request->getVar('term');

        $autoCompleteResult = array();

		$str = "
		SELECT 
		a.`SUB_DESC` __mdata
		FROM 
		mst_subcon a
		WHERE  
		a.`SUB_DESC` like '%{$term}%'
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
		
	} //end	search_rmap_subcon

} 
