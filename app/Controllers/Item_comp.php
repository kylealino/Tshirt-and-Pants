<?php namespace App\Controllers;
/*
 * Module      :    Item_comp.php
 * Type 	   :    Controllers
 * Program Desc:    Item_comp
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
use App\Models\MyItemCompModel;
use App\Libraries\Fpdf\Mypdf;
class Item_comp extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(0);
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->myitemcomp = new MyItemCompModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
   		$this->db = \Config\Database::connect();
	}

	public function index(){

		echo view('templates/myheader');
		echo view('mtap/item_comp/trx_item_comp_main');
		echo view('templates/myfooter');

	} //end index	

	public function index_v2(){

		echo view('templates/myheader');
		echo view('mtap/item_comp/trx_item_comp_main_v2');
		echo view('templates/myfooter');

	} //end index_v2	

	public function upload(){

		echo view('templates/myheader');
		echo view('mtap/item_comp/trx_item_comp_upld_main');
		echo view('templates/myfooter');

	} //end upload	

	public function item_comp_upld(){

		$data = $this->myitemcomp->item_comp_upld();
		return view('mtap/item_comp/trx_item_comp_upld_main',$data);

	} //end item_comp_upld	

    public function item_comp_save(){

        $this->myitemcomp->item_comp_entry_save();

    } //end item_comp_save	

	public function item_comp_save_2(){

        $this->myitemcomp->item_comp_entry_save_2();

    } //end item_comp_save_2	

	public function item_comp_update_2(){

        $this->myitemcomp->item_comp_update_2();

    } //end item_comp_update_2	

	public function item_comp_upld_save_2(){

        $this->myitemcomp->item_comp_upld_entry_save_2();

    } //end item_comp_upld_save_2	

	public function item_comp_update(){

        $this->myitemcomp->item_comp_entry_update();

    } //end item_comp_update	

	public function item_comp_recs(){

		$data    = $this->myitemcomp->view_recs();
		if($data['response']):
		return view('mtap/item_comp/trx_item_comp_recs',$data);
		else:
		$dta['msg'] = 'No records found!';
		return view('components/no-records',$dta);
		endif;

	} //end item_comp_recs	

	public function item_comp_recs_2(){

		$data    = $this->myitemcomp->view_recs_2();
		return view('mtap/item_comp/trx_item_comp_recs_v2',$data);

	} //end item_comp_recs_2	

	public function mat_article_fgpo(){
		
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

		WHERE `ART_CODE` like '%{$term}%' AND  ART_PRODT = 'FG' AND (ART_HIERC1 = 'TSHIRT' OR ART_HIERC1 = 'PANTS')
		LIMIT 10
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) {
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn);
				array_push($autoCompleteResult,array("value" => $row['ART_DESC'],
					"mtkn_rid" => $mtkn_rid,
					"ART_CODE"=>$row['ART_CODE']


				));
			endforeach;
		}

		$q->freeResult();
		echo json_encode($autoCompleteResult);
		
	} //end mat_article_fgpo	

	public function mat_article_rm() { 

		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM,ART_UPRICE
		FROM mst_article 
		WHERE (ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UPRICE" => $row['ART_UPRICE']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_rm	

	public function mat_article_btn() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM,ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%buttons%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_btn	

    public function mat_article_plastic_bag() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM,ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%PLASTIC%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_plastic_bag	

    public function mat_article_inside_garter() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM,ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%GARTER%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_inside_garter	

    public function mat_article_rivets() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM,ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%REVITS%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_rivets

    public function mat_article_zipper() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%zipper%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_zipper

    public function mat_article_fabric() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM,ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%FABRIC%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_fabric

    public function mat_article_lining() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%lining%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_lining 

    public function mat_article_leather_patch() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%leather%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_leather_patch

    public function mat_article_hangtag() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%HANGTAG%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_hangtag 

    public function mat_article_side_lbl() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%side label%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_side_lbl 

    public function mat_article_size_care_lbl() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%size care%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_size_care_lbl

    public function mat_article_kids_lbl() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%kids label%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_kids_lbl 

    public function mat_article_kids_side_lbl() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%kids side%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_kids_side_lbl

    public function mat_article_size_lbl() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%SIZE 3BLK%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_size_lbl

    public function mat_article_barcode() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%barcode%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_barcode

    public function mat_article_tagpin() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%tagpin%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_tagpin

    public function mat_article_chipboard() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM, ART_UCOST
		FROM mst_article 
		WHERE (ART_DESC LIKE '%chipboard%' AND ART_PRODT LIKE '%RAW-MAT%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_chipboard

}
