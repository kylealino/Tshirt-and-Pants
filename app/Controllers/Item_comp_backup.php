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
use App\Models\MyWarehouseoutModel;
use App\Models\MyLibzDBModel;
use App\Models\MyFGPackingModel;
use App\Libraries\Fpdf\Mypdf;
class Item_comp extends BaseController 
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
		$this->mytrxgr = new MyFGPackingModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		    // your db connection
   		$this->db = \Config\Database::connect();
	}

	public function index(){
		echo view('templates/myheader');
		echo view('mtap/item_comp/trx_item_comp_main');
		echo view('templates/myfooter');
	}	

	// public function mat_article_fgpo() { 
	// 	$cuser = $this->mylibzdb->mysys_user();
	// 	$mpw_tkn = $this->mylibzdb->mpw_tkn();
	// 	$this->db_erp = $this->mydbname->medb(0);
	// 	$filter  = $this->request->getVar('filter');
	// 	$filter2 = $this->request->getVar('filter2');
	// 	$ischck_mkg = $this->request->getVar('ischck_mkg');
	// 	$term = $this->request->getVar('term');
	// 	$autoCompleteResult = array();
	// 	$comp_usr = $this->myusermod->ua_comp_code($this->db_erp,$cuser);
	// 	$str_comp='';
	// 	$str_filter = '';
	// 	$str_filter2 = '';
	// 	if(count($comp_usr) > 0) { 

	// 		$str_comp = "";
	// 		for($xx = 0; $xx < count($comp_usr); $xx++) { 
	// 			$mart_comp = $comp_usr[$xx];
	// 			$str_comp .= "SUBSTR(ART_COMP,1,INSTR(ART_COMP,'~')-1)= '$mart_comp' or ";
    //         } //end for 
    //         $str_comp = "and (" . substr($str_comp,0,strlen($str_comp) - 3) . ")";

    //     }
    //     $fld_pbranch = $this->request->getVar('pbranchid');//GET id
    //     $str_branch ="";
    //     $BRNCH_MAT_FLAG ='';
    //     if(!empty($fld_pbranch)){
    //     	$str = "select recid,BRNCH_NAME,BRNCH_CODE,BRNCH_OCODE2,BRNCH_MAT_FLAG
    //     	from {$this->db_erp}.`mst_companyBranch` aa where `recid` = '$fld_pbranch'";
    //     	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    //     	//$this->mylibzdb->user_logs_activity_module($this->db_erp,'COMPANY','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			
    //     	$rw = $q->getRowArray();
    //     	$BRNCH_MAT_FLAG = $rw['BRNCH_MAT_FLAG'];
    //     	$fld_branch_recid = $rw['recid'];
    //     	$str_branch ="AND kk.`brnchID` = '$fld_branch_recid' ";

    //     	$q->freeResult();
	// 		//END BRANCH
    //     }

    //   //  if(!empty($str_comp)){
    //     $result = $this->myusermod->get_Active_menus($this->db_erp,$cuser,"myuamd_id='145'","myua_md");
    //     if($result == 1){
    //     	$str = "
    //     	select recid,ART_DESC,trim(ART_CODE) __mdata,
    //     	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
    //     	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
    //     	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
    //     	from {$this->db_erp}.`mst_article` where ART_PRODT = 'FG' AND (ART_HIERC1 = 'TSHIRT' OR ART_HIERC1 = 'PANTS') AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%')  order BY ART_DESC limit 50 
    //     	";
    //     }
    //     elseif($BRNCH_MAT_FLAG == 'G'){
    //     	$str = "
    //     	select 
    //     	a.recid,
    //     	a.ART_DESC,
    //     	trim(a.ART_CODE) __mdata,
    //     	a.ART_SKU,
    //     	a.ART_SDU,
    //     	a.ART_IMG,
    //     	a.ART_NCBM,
    //     	a.ART_NCONVF,
    //     	a.ART_UOM,
    //     	a.ART_BARCODE1,
    //     	a.ART_HIERC3,
    //     	a.ART_HIERC4,
    //     	IFNULL(kk.art_uprice,a.ART_UPRICE) ART_UPRICE,
    //     	IFNULL(kk.art_cost,a.ART_UCOST) ART_UCOST,
    //     	sha2(concat(a.recid,'{$mpw_tkn}'),384) mtkn_prdltr 
    //     	from {$this->db_erp}.`mst_article`  a
    //     	LEFT JOIN `mst_article_per_branch` kk
    //     	ON (a.`recid` = kk.`artID` {$str_branch})
    //     	where a.ART_PRODT = 'FG' AND a.ART_ISDISABLE = '0' AND (a.ART_CODE like '%$term%' or a.ART_DESC like '%$term%' or a.ART_BARCODE1 like '%$term%') order BY a.ART_DESC limit 50 
    //     	";
    //     }
    //     else{
    //     	$str = "
    //     	select recid,ART_DESC,trim(ART_CODE) __mdata,
    //     	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
    //     	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
    //     	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
    //     	from {$this->db_erp}.`mst_article` where ART_PRODT = 'FG' AND ART_ISDISABLE = '0' AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') order BY ART_DESC limit 50 
    //     	";
    //     }			
    //     $q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    //     if($q->resultID->num_rows > 0) { 
    //     	$rrec = $q->getResultArray();
    //     	foreach($rrec as $row):
    //     		$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
    //     		array_push($autoCompleteResult,array(
    //     			"mtkn_rid" => $mtkn_rid,
    //     			"value" => $row['__mdata'],
    //     			"ART_DESC" => $row['ART_DESC'],  
    //     			"ART_SKU" => $row['ART_SKU'], 
    //     			"ART_SDU" => $row['ART_SDU'], 
    //     			"ART_IMG" => $row['ART_IMG'],
    //     			"ART_UOM"   => $row['ART_UOM'],
    //     			"ART_NCONVF" => $row['ART_NCONVF'],
    //     			"ART_UPRICE" => $row['ART_UPRICE'],
    //     			"ART_UCOST" => $row['ART_UCOST'],  
    //     			"ART_CODE" => $row['__mdata'],
    //     			"ART_NCBM" => $row['ART_NCBM'],
    //     			"ART_MATRID" => $row['recid'],
    //     			"ART_BARCODE1" => $row['ART_BARCODE1'],
    //     			"ART_HIERC3"     => $row['ART_HIERC3'],
    //     			"ART_HIERC4" => $row['ART_HIERC4'],
        			

    //     		));
    //     	endforeach;
    //     }
    //     $q->freeResult();
        
    //     echo json_encode($autoCompleteResult);
    //   //  }
    // } //end mat_article

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
		
	}// end companybranch

	public function mat_article_btn() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT ART_CODE, ART_DESC, ART_UOM
		FROM mst_article 
		WHERE (ART_DESC LIKE '%buttons%' AND ART_PRODT LIKE '%Rm%')
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array(
					"value" => $row['ART_CODE'], 
					"ART_DESC" => $row['ART_DESC'],
					"ART_UOM" => $row['ART_UOM']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article	



}  //end main class
