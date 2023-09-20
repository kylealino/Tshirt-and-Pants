<?php namespace App\Controllers;
/*
 * Module      :    Mysearchdata.php
 * Type 	   :    Controller
 * Program Desc:    Mysearchdata
 * Author      :    Arnel A. Oquien
 * Date Created:    Sep. 23, 2022
*/


use CodeIgniter\Controller;
use App\Models\Mytrx_whcrossing_model;
use App\Models\Mymelibsys_model;
use App\Models\MyDatummodel;
use App\Models\MyDatauaModel;
use App\Models\MyLibzDBModel;
use App\Models\MyLibzSysModel;


class Mysearchdata extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname     = model('App\Models\MyDBNamesModel');
		$this->db_erp       = $this->mydbname->medb(1);
		$this->mywhcrossing = new Mytrx_whcrossing_model();
		$this->mymelibzsys   = new Mymelibsys_model();
		$this->mydataz      = new MyDatummodel();
		$this->mylibzdb     = new MyLibzDBModel();
		$this->mylibzsys    = new MyLibzSysModel();
		$this->mydataua    = new MyDatauaModel();
		

		$this->request = \Config\Services::request();
	}
	
	public function get_userplant_access_dropdown(){
		$cuser              = $this->mylibzdb->mysys_user();
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		$term               = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		SELECT
			aa.	recid,trim(aa.`plnt_code`) __mdata, aa.`plnt_code` plnt_code  
		FROM {$this->db_erp}.`mst_plant` aa
		JOIN  {$this->db_erp}.`myua_plant` bb ON(aa.`plnt_code`= bb.`myuaplant`)
		WHERE bb.`myusername`='{$cuser}' AND bb.`ISACTIVE`='Y'  ORDER BY plnt_code
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
				array_push($autoCompleteResult,array(
					"value" => $row['__mdata'], 
					"mtkn_rid" => $mtkn_rid,
					"plnt_code" => $row["plnt_code"] ));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
		}

		public function get_userwrhse_access_dropdown(){
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term = $this->request->getPost('term');
		$mtkn_plnt  = urldecode($this->request->getVar('mtkn_plnt'));
		$autoCompleteResult = array();
		$mplt_id = '';
		$str_plant ='';
		if(!empty($mtkn_plnt)){
			$str = "select aa.recid from {$this->db_erp}.mst_plant aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_plnt'";
			$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			if($q->getNumRows() == 0) { 
			} else { 
				$rw = $q->getRowArray();
				$mplt_id = $rw['recid'];
				$str_plant ="AND aa.`plnt_id` = '$mplt_id'";
			}
			$q->freeResult();
			
		}
		$str = "
		SELECT
			aa.	recid,trim(aa.`wshe_code`) __mdata, aa.`wshe_code` wshe_code  
		FROM {$this->db_erp}.`mst_wshe` aa
		JOIN  {$this->db_erp}.`myua_whse` bb ON(aa.`wshe_code`=bb.`myuawhse`)
		WHERE bb.`myusername`='{$cuser}' AND bb.`ISACTIVE`='Y' {$str_plant} ORDER BY recid limit 50 
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
				array_push($autoCompleteResult,array(
					"value" => $row['__mdata'], 
					"mtkn_rid" => $mtkn_rid
		));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
	}

		public function get_user_cdwrhse_access_dropdown(){
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term = $this->request->getPost('term');
		$mtkn_plnt  = urldecode($this->request->getVar('mtkn_plnt'));
		$autoCompleteResult = array();
		$mplt_id = '';
		$str_plant ='';
		if(!empty($mtkn_plnt)){
			$str = "select aa.recid from {$this->db_erp}.mst_plant aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_plnt'";
			$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			if($q->getNumRows() == 0) { 
			} else { 
				$rw = $q->getRowArray();
				$mplt_id = $rw['recid'];
				$str_plant ="AND aa.`plnt_id` = '$mplt_id'";
			}
			$q->freeResult();
			
		}
		$str = "
		SELECT
			aa.	recid,trim(aa.`wshe_code`) __mdata, aa.`wshe_code` wshe_code  
		FROM {$this->db_erp}.`mst_wshe` aa
		JOIN  {$this->db_erp}.`myua_whse` bb ON(aa.`wshe_code`=bb.`myuawhse`)
		WHERE bb.`myusername`='{$cuser}' AND bb.`ISACTIVE`='Y' AND aa.`is_crossdocking` = 'Y' {$str_plant} ORDER BY recid limit 50 
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
				array_push($autoCompleteResult,array(
					"value" => $row['__mdata'], 
					"mtkn_rid" => $mtkn_rid
		));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
	}

		// public function companybranch_v(){ 
		// 	$cuser = $this->mylibzdb->mysys_user();
		// 	$mpw_tkn = $this->mylibzdb->mpw_tkn();
			
		// 	$mtkn_grparea = $this->request->getVar('mtkn_grparea');
		// 	$str_grparea = '';
		// 	if(!empty($mtkn_grparea)){
		// 		$fld_itmgrparea_f = explode("=>",$mtkn_grparea);
		// 		$fld_itmgrp_s = $fld_itmgrparea_f[0];
		// 		$fld_itmarea_s = $fld_itmgrparea_f[1];
		// 		$str_grparea = " AND (a.`BRNCH_GROUP` = '$fld_itmgrp_s') AND (a.`BRNCH_AREA` = '$fld_itmarea_s') ";
		// 	}
			
		// 	$aua_branch = $this->mydataua->ua_brnch($this->db_erp,$cuser);
		// 	$str_branch = " a.`recid` = '__MEBRNCH__' ";

		// 	if(count($aua_branch) > 0) { 
		// 		$str_branch = "";
		// 		for($xx = 0; $xx < count($aua_branch); $xx++) { 
		// 			$mbranch = $aua_branch[$xx];
		// 			$str_branch .= " a.`recid` = '$mbranch' or ";
	 //            } //end for 
	 //            $str_branch = "(" . substr($str_branch,0,strlen($str_branch) - 3) . ")";
	 //        }



	 //        $mtkn_compid = $this->request->getVar('mtkn_compid');
	 //        $term = $this->request->getVar('term');
	 //       	//$terms = explode('XOX', $term);
	 //        $autoCompleteResult = array();
	 //        if(!empty($mtkn_compid)){
	 //        	$str = "
	 //        	SELECT a.`recid` Rbrnch,
	 //        	a.`BRNCH_CODE`,
	 //        	a.`BRNCH_NAME` __mdata,
	 //        	b.`COMP_NAME`,
	 //        	b.`recid` Rcomp,
	 //        	a.`BRNCH_CPRSN`
	 //        	from {$this->db_erp}.mst_companyBranch a
	 //        	LEFT JOIN {$this->db_erp}.mst_company b
	 //        	ON (a.`COMP_CODE` = b.`COMP_CODE`)
	 //        	where {$str_branch} {$str_grparea} AND sha2(concat(b.`recid`,'{$mpw_tkn}'),384) = '{$mtkn_compid}' AND (a.`BRNCH_CODE` like '%{$term}%' or a.`BRNCH_NAME` like '%{$term}%') 
	 //        	order by BRNCH_NAME limit 15 
	 //        	";

	 //        }
	 //        else{
	 //        	$str = "
	 //        	SELECT a.`recid` Rbrnch,a.`BRNCH_CODE`,
	 //        	a.`BRNCH_NAME` __mdata,
	 //        	b.`COMP_NAME`,
	 //        	a.`BRNCH_CPRSN`
	 //        	from {$this->db_erp}.mst_companyBranch a
	 //        	LEFT JOIN {$this->db_erp}.mst_company b
	 //        	ON (a.`COMP_CODE` = b.`COMP_CODE`)
	 //        	where {$str_branch} {$str_grparea} AND (a.`BRNCH_CODE` like '%{$term}%' or a.`BRNCH_NAME` like '%{$term}%') 
	 //        	order by BRNCH_NAME limit 15 
	 //        	";
	 //        }


		// 	$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
		// 	if($q->getNumRows() > 0) { 
		// 		$rrec = $q->getResultArray();
		// 		foreach($rrec as $row):
		// 			$mtkn_rid = hash('sha384', $row['BRNCH_CODE'] . $mpw_tkn);
		// 			$mtknr_rid = hash('sha384', $row['Rbrnch'] . $mpw_tkn);  
		// 			array_push($autoCompleteResult,array("value" => $row['__mdata'], 
		// 				"mtkn_rid" => $mtkn_rid,
		// 				"mtknr_rid" => $mtknr_rid,
		// 				"mtkn_brnch" => $row['Rbrnch'],
		// 				"mtkn_comp" => $row['COMP_NAME'],
		// 				"contact_person" => $row['BRNCH_CPRSN'],
		// 			));
					
		// 		endforeach;
		// 	}
		// 	$q->freeResult();
		// 	echo json_encode($autoCompleteResult);
			
		// }//end

	public function companybranch_v(){
		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term    = $this->request->getVar('term');

	            $autoCompleteResult = array();
	            if(substr($cuser,0,3) == 'SMC'){
	                $str = "
	                SELECT
	                aa.`recid`,
	                bb.`recid` as comp_id,
	                aa.`BRNCH_CODE`,
	                aa.`BRNCH_NAME` as __mdata,
	                bb.`COMP_NAME`,
	                CONCAT(bb.`COMP_ADDR1`,' ',bb.`COMP_ADDR2`,' ',bb.`COMP_ADDR3`) ADDRESS,
	                CONCAT(aa.`BRNCH_ADDR1`,' ',aa.`BRNCH_ADDR2`,' ',aa.`BRNCH_ADDR3`) BADDRESS,
	                CONCAT(TRIM(bb.`COMP_CPRSN`)) COMP_CPRSN,
	                CONCAT(TRIM(bb.`COMP_CPRSN_DESGN`)) COMP_CPRSN_DESGN,
	                CONCAT(TRIM(bb.`COMP_CPRSN_TELNO`)) COMP_CPRSN_TELNO
	                FROM {$this->db_erp}.`mst_companyBranch` aa
	                JOIN
	                {$this->db_erp}.`mst_company` bb
	                ON
	                (aa.`COMP_ID`= bb.`recid`)
	                JOIN  {$this->db_erp}.`myua_branch` cc ON(aa.`recid`=cc.`myuabranch`)
	                WHERE (aa.`BRNCH_SMC_STAT` = '0') AND cc.`myusername`='{$cuser}' AND cc.`ISACTIVE`='Y' AND aa.`BRNCH_NAME` LIKE '%{$term}%'
	                ";
	            }
	            else{
	                $str = "
	                SELECT
	                aa.`recid`,
	                bb.`recid` as comp_id,
	                aa.`BRNCH_CODE`,
	                aa.`BRNCH_NAME` as __mdata,
	                bb.`COMP_NAME`,
	                CONCAT(bb.`COMP_ADDR1`,' ',bb.`COMP_ADDR2`,' ',bb.`COMP_ADDR3`) ADDRESS,
	                CONCAT(aa.`BRNCH_ADDR1`,' ',aa.`BRNCH_ADDR2`,' ',aa.`BRNCH_ADDR3`) BADDRESS,
	                CONCAT(TRIM(bb.`COMP_CPRSN`)) COMP_CPRSN,
	                CONCAT(TRIM(bb.`COMP_CPRSN_DESGN`)) COMP_CPRSN_DESGN,
	                CONCAT(TRIM(bb.`COMP_CPRSN_TELNO`)) COMP_CPRSN_TELNO
	                FROM {$this->db_erp}.`mst_companyBranch` aa
	                JOIN
	                {$this->db_erp}.`mst_company` bb
	                ON
	                (aa.`COMP_ID`= bb.`recid`)
	                JOIN  {$this->db_erp}.`myua_branch` cc ON(aa.`recid`=cc.`myuabranch`)
	                WHERE cc.`myusername`='{$cuser}' AND cc.`ISACTIVE`='Y' AND aa.`BRNCH_NAME` LIKE '%{$term}%'
	                ";
	            }

	            $q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	            if($q->getNumRows() > 0) {
	                $rrec = $q->getResultArray();
	                foreach($rrec as $row):
	                    $mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn);
	                    $mtkn_ridb = hash('sha384', $row['comp_id'] . $mpw_tkn);
	                    array_push($autoCompleteResult,array("value" => $row['__mdata'],
	                        "mtkn_rid" => $mtkn_rid,
	                        "mtkn_ridb" => $mtkn_ridb,
	                        "COMP_NAME" => $row['COMP_NAME'],
	                        "ADDRESS"   => $row['ADDRESS'],
	                        "BADDRESS"   => $row['BADDRESS'],
	                        "COMP_CPRSN" => $row['COMP_CPRSN'],
	                        "COMP_CPRSN_DESGN" => $row['COMP_CPRSN_DESGN'],
	                        "COMP_CPRSN_TELNO" => $row['COMP_CPRSN_TELNO']
	                    ));
	                endforeach;
	            }
	            $q->freeResult();
	            echo json_encode($autoCompleteResult);
	        }


	public function myget_warehouse_group_list(){

			$cuser      = $this->mylibzdb->mysys_user();
			$mpw_tkn    = $this->mylibzdb->mpw_tkn();
			$term       = $this->request->getVar('term');
			$mtkn_uid   = $this->request->getVar('mtkn_uid');
			$str_pwshe  = '';

			if(!empty($mtkn_uid)){
				$wshe_data      = $this->mymelibzsys->getCDPlantWarehouse_data_bytkn($mtkn_uid);
				$active_plnt_id = $wshe_data['plntID'];
				$active_wshe_id = $wshe_data['whID'];
				$str_pwshe      = "AND `plnt_id` = '{$active_plnt_id}' AND `wshe_id` = '{$active_wshe_id}'";
			}


			$autoCompleteResult = array();

			$str = "
			SELECT
			`recid`,
			`wshe_grp` AS `__mdata`
			FROM
			{$this->db_erp}.`mst_wshe_grp`
			WHERE
			(`wshe_grp` LIKE '%{$term}%')
			{$str_pwshe}
			LIMIT 50
			";
			$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			if($q->getNumRows() > 0) { 
				$rrec = $q->getResultArray();
				foreach($rrec as $row):
					$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
					array_push($autoCompleteResult,array("value" => $row['__mdata'], 
						"mtkn_rid" => $mtkn_rid));
				endforeach;
			}
			$q->freeResult();
			echo json_encode($autoCompleteResult);
	}//end

	public function myget_warehouse_bin_list(){

		$cuser         = $this->mylibzdb->mysys_user();
		$mpw_tkn       = $this->mylibzdb->mpw_tkn();

		$term          = $this->request->getVar('term');
		$mtkn_uid      = $this->request->getVar('mtkn_uid');
		$mtkn_wshe_grp = $this->request->getVar('mtkn_wshe_grp');
		$str_pwshe     = '';
		if(!empty($mtkn_uid)){
			$wshe_data         = $this->mymelibzsys->getWhGrpDetailsByTkn($mtkn_wshe_grp);
			$active_plnt_id    = $wshe_data['plnt_id'];
			$active_wshe_id    = $wshe_data['wshe_id'];
			$active_wshegrp_id = $wshe_data['recid'];
			$str_pwshe         = " AND `plnt_id` = '{$active_plnt_id}' AND `wshe_id` = '{$active_wshe_id}' AND `wshegrp_id` = '{$active_wshegrp_id}'";
		}

		$wshe_grp = '';
		// if(!empty($mtkn_wshe_grp)){

		// 	$wshe_grp = "AND `wshegrp_id`,'{$mpw_tkn}'),384) = '{$mtkn_wshe_grp}' ";

		// }


		$autoCompleteResult = array();

		$str = "
		SELECT
		`recid`,
		`wshe_bin_name` AS `__mdata`
		FROM
		{$this->db_erp}.`mst_wshe_bin`
		WHERE
		(`wshe_bin_name` LIKE '%{$term}%')
		{$str_pwshe}	
		LIMIT 50
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
				array_push($autoCompleteResult,array("value" => $row['__mdata'], 
					"mtkn_rid" => $mtkn_rid));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
	}//end

	public function catg_1hd_vw() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();

		$term = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		select `recid`,`MAT_CATG1_CODE` __mdata from {$this->db_erp}.`mst_mat_catg1_hd` where (`MAT_CATG1_CODE` like '%$term%') order by `MAT_CATG1_CODE` limit 5 
		";

	$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
	if($q->getNumRows() > 0) { 
		$rrec = $q->getResultArray();
		foreach($rrec as $row):
	//$mtkn_rid = hash('sha384', $row['COMP_CODE'] . $mpw_tkn); 
			$mtkn_recid = hash('sha384', $row['recid'] . $mpw_tkn);
			array_push($autoCompleteResult,array("value" => $row['__mdata'], 
				"mtkn_recid" => $mtkn_recid ));

		endforeach;
	}
	$q->freeResult();
	echo json_encode($autoCompleteResult);
	}  //end search

	public function catg_1dt_vw() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();

		$term = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		select `recid`,`MAT_CATG1_CODE` __mdata from {$this->db_erp}.`mst_mat_catg1_dt` where (`MAT_CATG1_CODE` like '%$term%') order by `MAT_CATG1_CODE` limit 5 
		";

	$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
	if($q->getNumRows() > 0) { 
		$rrec = $q->getResultArray();
		foreach($rrec as $row):
	//$mtkn_rid = hash('sha384', $row['COMP_CODE'] . $mpw_tkn); 
			$mtkn_recid = hash('sha384', $row['recid'] . $mpw_tkn);
			array_push($autoCompleteResult,array("value" => $row['__mdata'], 
				"mtkn_recid" => $mtkn_recid ));

		endforeach;
	}
	$q->freeResult();
	echo json_encode($autoCompleteResult);
	}  //end search

	public function catg_2_vw() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();

		$term = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		select `recid`,`MAT_CATG2_CODE` __mdata from {$this->db_erp}.`mst_mat_catg2_hd` where (`MAT_CATG2_CODE` like '%$term%') order by `MAT_CATG2_CODE` limit 5 
		";

	$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
	if($q->getNumRows() > 0) { 
		$rrec = $q->getResultArray();
		foreach($rrec as $row):
	//$mtkn_rid = hash('sha384', $row['COMP_CODE'] . $mpw_tkn); 
			$mtkn_recid = hash('sha384', $row['recid'] . $mpw_tkn);
			array_push($autoCompleteResult,array("value" => $row['__mdata'], 
				"mtkn_recid" => $mtkn_recid ));

		endforeach;
	}
	$q->freeResult();
	echo json_encode($autoCompleteResult);
	}  //end search

	public function catg_3_vw() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();

		$term = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		select `recid`,`MAT_CATG3_CODE` __mdata from {$this->db_erp}.`mst_mat_catg3_hd` where (`MAT_CATG3_CODE` like '%$term%') order by `MAT_CATG3_CODE` limit 5 
		";

	$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
	if($q->getNumRows() > 0) { 
		$rrec = $q->getResultArray();
		foreach($rrec as $row):
	//$mtkn_rid = hash('sha384', $row['COMP_CODE'] . $mpw_tkn); 
			$mtkn_recid = hash('sha384', $row['recid'] . $mpw_tkn);
			array_push($autoCompleteResult,array("value" => $row['__mdata'], 
				"mtkn_recid" => $mtkn_recid ));

		endforeach;
	}
	$q->freeResult();
	echo json_encode($autoCompleteResult);
	}  //end search


	public function catg_4_vw() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		
		$term = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		select `recid`,`MAT_CATG4_CODE` __mdata from {$this->db_erp}.`mst_mat_catg4_hd` where (`MAT_CATG4_CODE` like '%{$term}%') order by `MAT_CATG4_CODE` limit 5 
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				//$mtkn_rid = hash('sha384', $row['COMP_CODE'] . $mpw_tkn); 
				$mtkn_recid = hash('sha384', $row['recid'] . $mpw_tkn);
				array_push($autoCompleteResult,array("value" => $row['__mdata'], 
					"mtkn_recid" => $mtkn_recid ));
				
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
	}  //end search
	public function vendor_po() { 
        $cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$import_id = $this->request->getVar('mtkn_rec');
		$term = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		select recid,trim(VEND_NAME) __mdata ,  concat(VEND_ADDR1,' ',VEND_ADDR2,' ',VEND_ADDR3) _address , concat(VEND_CPRSN) cont_prsn , concat(VEND_CPRSN_DESGN) cp_desig , concat(VEND_CPRSN_TELNO) cp_no, VEND_TERMS_CODE _terms  
			from {$this->db_erp}.mst_vendor where VEND_CLS_ID='{$import_id}' and  (VEND_CODE like '%{$term}%' or VEND_NAME like '%{$term}%') order BY VEND_NAME limit 50 ";			
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->resultID->num_rows > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
				array_push($autoCompleteResult,array("value" => $row['__mdata'], 
					"mtkn_rid" => $mtkn_rid,
					"_address" => $row["_address"],
					"_terms" => $row["_terms"],
					"cont_prsn" => $row["cont_prsn"] , "cp_desig" => $row["cp_desig"] , "cp_no" => $row["cp_no"]  ));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
	}  //end vendorpo
	public function vendor_poclass() { 
        $cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		
		$term = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		select `recid`,`PO_CLS_CODE` __mdata from {$this->db_erp}.`mst_po_class` where (`PO_CLS_CODE` like '%{$term}%') order by `PO_CLS_CODE` limit 5 
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				//$mtkn_rid = hash('sha384', $row['COMP_CODE'] . $mpw_tkn); 
				$mtkn_recid = hash('sha384', $row['recid'] . $mpw_tkn);
				array_push($autoCompleteResult,array("value" => $row['__mdata'], 
					"mtkn_rrec" => $row['recid'],
					"mtkn_recid" => $mtkn_recid ));
				
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
	}  //end vendorpo
	public function vendor_customer() { 
        $cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$import_id = $this->request->getVar('mtkn_rec');
		$term = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		select recid,trim(CUST_NAME) __mdata ,  concat(CUST_ADDR1,' ',CUST_ADDR2,' ',CUST_ADDR3) _address , concat(CUST_CPRSN) cont_prsn , concat(CUST_CPRSN_DESGN) cp_desig , concat(CUST_CPRSN_TELNO) cp_no, CUST_TINNO 
		from {$this->db_erp}.mst_customer where (CUST_CODE like '%{$term}%' or CUST_NAME like '%{$term}%') order BY CUST_NAME limit 50";			
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->resultID->num_rows > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
				array_push($autoCompleteResult,array("value" => $row['__mdata'], 
					"mtkn_rid" => $mtkn_rid,
					"_address" => $row["_address"], 
					"CUST_TINNO" => $row["CUST_TINNO"], 
					"cont_prsn" => $row["cont_prsn"] , "cp_desig" => $row["cp_desig"] , "cp_no" => $row["cp_no"]  ));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
	}  //end vendorpo
	public function mat_article() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$this->db_erp = $this->mydbname->medb(0);
		$filter  = $this->request->getVar('filter');
		$filter2 = $this->request->getVar('filter2');
		$ischck_mkg = $this->request->getVar('ischck_mkg');
		$term = $this->request->getVar('term');
		$autoCompleteResult = array();
		$comp_usr = $this->myusermod->ua_comp_code($this->db_erp,$cuser);
		$str_comp='';
		$str_filter = '';
		$str_filter2 = '';
		if(count($comp_usr) > 0) { 

			$str_comp = "";
			for($xx = 0; $xx < count($comp_usr); $xx++) { 
				$mart_comp = $comp_usr[$xx];
				$str_comp .= "SUBSTR(ART_COMP,1,INSTR(ART_COMP,'~')-1)= '$mart_comp' or ";
            } //end for 
            $str_comp = "and (" . substr($str_comp,0,strlen($str_comp) - 3) . ")";

        }
		die();
        $fld_pbranch = $this->request->getVar('pbranchid');//GET id
        $str_branch ="";
        $BRNCH_MAT_FLAG ='';
        if(!empty($fld_pbranch)){
        	$str = "select recid,BRNCH_NAME,BRNCH_CODE,BRNCH_OCODE2,BRNCH_MAT_FLAG
        	from {$this->db_erp}.`mst_companyBranch` aa where `recid` = '$fld_pbranch'";
        	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        	//$this->mylibzdb->user_logs_activity_module($this->db_erp,'COMPANY','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        	$rw = $q->getRowArray();
        	$BRNCH_MAT_FLAG = $rw['BRNCH_MAT_FLAG'];
        	$fld_branch_recid = $rw['recid'];
        	$str_branch ="AND kk.`brnchID` = '$fld_branch_recid' ";

        	$q->freeResult();
			//END BRANCH
        }
                //if filter id not empty
        if(!empty($filter)):
        	$str_filter = "AND ART_HIERC2 = '{$filter}'";
        endif;
        		//if filter id not empty
        if(!empty($filter2)):
        	$str_filter2 = "AND ART_DESC_CODE = '{$filter2}'";
        endif;
        
        if($ischck_mkg == 'Y'){
        	$str_mkg = "AND ART_CODE like 'MKG%'";
        }
        elseif($ischck_mkg == 'N'){
        	$str_mkg = "AND !(ART_CODE like 'MKG%')";
        }
        else{
        	$str_mkg = "";
        }
      //  if(!empty($str_comp)){
        $result = $this->myusermod->get_Active_menus($this->db_erp,$cuser,"myuamd_id='145'","myua_md");
        if($result == 1){
        	$str = "
        	select recid,ART_DESC,trim(ART_CODE) __mdata,
        	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
        	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
        	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
        	from {$this->db_erp}.`mst_article` where (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') order BY ART_DESC limit 50 
        	";
        }
        elseif($BRNCH_MAT_FLAG == 'G'){
        	$str = "
        	select 
        	a.recid,
        	a.ART_DESC,
        	trim(a.ART_CODE) __mdata,
        	a.ART_SKU,
        	a.ART_SDU,
        	a.ART_IMG,
        	a.ART_NCBM,
        	a.ART_NCONVF,
        	a.ART_UOM,
        	a.ART_BARCODE1,
        	a.ART_HIERC3,
        	a.ART_HIERC4,
        	IFNULL(kk.art_uprice,a.ART_UPRICE) ART_UPRICE,
        	IFNULL(kk.art_cost,a.ART_UCOST) ART_UCOST,
        	sha2(concat(a.recid,'{$mpw_tkn}'),384) mtkn_prdltr 
        	from {$this->db_erp}.`mst_article`  a
        	LEFT JOIN `mst_article_per_branch` kk
        	ON (a.`recid` = kk.`artID` {$str_branch})
        	where a.ART_PRODT = 'FG' AND a.ART_ISDISABLE = '0' AND (a.ART_CODE like '%$term%' or a.ART_DESC like '%$term%' or a.ART_BARCODE1 like '%$term%') order BY a.ART_DESC limit 50 
        	";
        }
        else{
        	$str = "
        	select recid,ART_DESC,trim(ART_CODE) __mdata,
        	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
        	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
        	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
        	from {$this->db_erp}.`mst_article` where ART_PRODT = 'FG' AND ART_ISDISABLE = '0' AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') {$str_mkg} {$str_filter} {$str_filter2} order BY ART_DESC limit 50 
        	";
        }			
        $q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        if($q->resultID->num_rows > 0) { 
        	$rrec = $q->getResultArray();
        	foreach($rrec as $row):
        		$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
        		array_push($autoCompleteResult,array(
        			"mtkn_rid" => $mtkn_rid,
        			"value" => $row['__mdata'],
        			"ART_DESC" => $row['ART_DESC'],  
        			"ART_SKU" => $row['ART_SKU'], 
        			"ART_SDU" => $row['ART_SDU'], 
        			"ART_IMG" => $row['ART_IMG'],
        			"ART_UOM"   => $row['ART_UOM'],
        			"ART_NCONVF" => $row['ART_NCONVF'],
        			"ART_UPRICE" => $row['ART_UPRICE'],
        			"ART_UCOST" => $row['ART_UCOST'],  
        			"ART_CODE" => $row['__mdata'],
        			"ART_NCBM" => $row['ART_NCBM'],
        			"ART_MATRID" => $row['recid'],
        			"ART_BARCODE1" => $row['ART_BARCODE1'],
        			"ART_HIERC3"     => $row['ART_HIERC3'],
        			"ART_HIERC4" => $row['ART_HIERC4'],
        			

        		));
        	endforeach;
        }
        $q->freeResult();
        
        echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article

	public function mat_article_gr() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$this->db_erp = $this->mydbname->medb(0);
		$filter  = $this->request->getVar('filter');
		$filter2 = $this->request->getVar('filter2');
		$ischck_mkg = $this->request->getVar('ischck_mkg');
		$term = $this->request->getVar('term');
		$autoCompleteResult = array();
		$comp_usr = $this->myusermod->ua_comp_code($this->db_erp,$cuser);
		$str_comp='';
		$str_filter = '';
		$str_filter2 = '';
		if(count($comp_usr) > 0) { 

			$str_comp = "";
			for($xx = 0; $xx < count($comp_usr); $xx++) { 
				$mart_comp = $comp_usr[$xx];
				$str_comp .= "SUBSTR(ART_COMP,1,INSTR(ART_COMP,'~')-1)= '$mart_comp' or ";
            } //end for 
            $str_comp = "and (" . substr($str_comp,0,strlen($str_comp) - 3) . ")";

        }
        $fld_pbranch = $this->request->getVar('pbranchid');//GET id
        $str_branch ="";
        $BRNCH_MAT_FLAG ='';
        if(!empty($fld_pbranch)){
        	$str = "select recid,BRNCH_NAME,BRNCH_CODE,BRNCH_OCODE2,BRNCH_MAT_FLAG
        	from {$this->db_erp}.`mst_companyBranch` aa where `recid` = '$fld_pbranch'";
        	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        	//$this->mylibzdb->user_logs_activity_module($this->db_erp,'COMPANY','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			
        	$rw = $q->getRowArray();
        	$BRNCH_MAT_FLAG = $rw['BRNCH_MAT_FLAG'];
        	$fld_branch_recid = $rw['recid'];
        	$str_branch ="AND kk.`brnchID` = '$fld_branch_recid' ";

        	$q->freeResult();
			//END BRANCH
        }

      //  if(!empty($str_comp)){
        $result = $this->myusermod->get_Active_menus($this->db_erp,$cuser,"myuamd_id='145'","myua_md");
        if($result == 1){
        	$str = "
        	select recid,ART_DESC,trim(ART_CODE) __mdata,
        	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
        	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
        	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
        	from {$this->db_erp}.`mst_article` where (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%')  order BY ART_DESC limit 50 
        	";
        }
        elseif($BRNCH_MAT_FLAG == 'G'){
        	$str = "
        	select 
        	a.recid,
        	a.ART_DESC,
        	trim(a.ART_CODE) __mdata,
        	a.ART_SKU,
        	a.ART_SDU,
        	a.ART_IMG,
        	a.ART_NCBM,
        	a.ART_NCONVF,
        	a.ART_UOM,
        	a.ART_BARCODE1,
        	a.ART_HIERC3,
        	a.ART_HIERC4,
        	IFNULL(kk.art_uprice,a.ART_UPRICE) ART_UPRICE,
        	IFNULL(kk.art_cost,a.ART_UCOST) ART_UCOST,
        	sha2(concat(a.recid,'{$mpw_tkn}'),384) mtkn_prdltr 
        	from {$this->db_erp}.`mst_article`  a
        	LEFT JOIN `mst_article_per_branch` kk
        	ON (a.`recid` = kk.`artID` {$str_branch})
        	where a.ART_PRODT = 'FG' AND a.ART_ISDISABLE = '0' AND (a.ART_CODE like '%$term%' or a.ART_DESC like '%$term%' or a.ART_BARCODE1 like '%$term%') order BY a.ART_DESC limit 50 
        	";
        }
        else{
        	$str = "
        	select recid,ART_DESC,trim(ART_CODE) __mdata,
        	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
        	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
        	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
        	from {$this->db_erp}.`mst_article` where ART_PRODT = 'FG' AND ART_ISDISABLE = '0' AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') order BY ART_DESC limit 50 
        	";
        }			
        $q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        if($q->resultID->num_rows > 0) { 
        	$rrec = $q->getResultArray();
        	foreach($rrec as $row):
        		$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
        		array_push($autoCompleteResult,array(
        			"mtkn_rid" => $mtkn_rid,
        			"value" => $row['__mdata'],
        			"ART_DESC" => $row['ART_DESC'],  
        			"ART_SKU" => $row['ART_SKU'], 
        			"ART_SDU" => $row['ART_SDU'], 
        			"ART_IMG" => $row['ART_IMG'],
        			"ART_UOM"   => $row['ART_UOM'],
        			"ART_NCONVF" => $row['ART_NCONVF'],
        			"ART_UPRICE" => $row['ART_UPRICE'],
        			"ART_UCOST" => $row['ART_UCOST'],  
        			"ART_CODE" => $row['__mdata'],
        			"ART_NCBM" => $row['ART_NCBM'],
        			"ART_MATRID" => $row['recid'],
        			"ART_BARCODE1" => $row['ART_BARCODE1'],
        			"ART_HIERC3"     => $row['ART_HIERC3'],
        			"ART_HIERC4" => $row['ART_HIERC4'],
        			

        		));
        	endforeach;
        }
        $q->freeResult();
        
        echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_gr

	public function mat_article_fgpo() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$this->db_erp = $this->mydbname->medb(0);
		$filter  = $this->request->getVar('filter');
		$filter2 = $this->request->getVar('filter2');
		$ischck_mkg = $this->request->getVar('ischck_mkg');
		$term = $this->request->getVar('term');
		$autoCompleteResult = array();
		$comp_usr = $this->myusermod->ua_comp_code($this->db_erp,$cuser);
		$str_comp='';
		$str_filter = '';
		$str_filter2 = '';
		if(count($comp_usr) > 0) { 

			$str_comp = "";
			for($xx = 0; $xx < count($comp_usr); $xx++) { 
				$mart_comp = $comp_usr[$xx];
				$str_comp .= "SUBSTR(ART_COMP,1,INSTR(ART_COMP,'~')-1)= '$mart_comp' or ";
            } //end for 
            $str_comp = "and (" . substr($str_comp,0,strlen($str_comp) - 3) . ")";

        }
        $fld_pbranch = $this->request->getVar('pbranchid');//GET id
        $str_branch ="";
        $BRNCH_MAT_FLAG ='';
        if(!empty($fld_pbranch)){
        	$str = "select recid,BRNCH_NAME,BRNCH_CODE,BRNCH_OCODE2,BRNCH_MAT_FLAG
        	from {$this->db_erp}.`mst_companyBranch` aa where `recid` = '$fld_pbranch'";
        	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        	//$this->mylibzdb->user_logs_activity_module($this->db_erp,'COMPANY','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			
        	$rw = $q->getRowArray();
        	$BRNCH_MAT_FLAG = $rw['BRNCH_MAT_FLAG'];
        	$fld_branch_recid = $rw['recid'];
        	$str_branch ="AND kk.`brnchID` = '$fld_branch_recid' ";

        	$q->freeResult();
			//END BRANCH
        }

      //  if(!empty($str_comp)){
        $result = $this->myusermod->get_Active_menus($this->db_erp,$cuser,"myuamd_id='145'","myua_md");
        if($result == 1){
        	$str = "
        	select recid,ART_DESC,trim(ART_CODE) __mdata,
        	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
        	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
        	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
        	from {$this->db_erp}.`mst_article` where ART_PRODT = 'FG' AND (ART_HIERC1 = 'TSHIRT' OR ART_HIERC1 = 'PANTS') AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%')  order BY ART_DESC limit 50 
        	";
        }
        elseif($BRNCH_MAT_FLAG == 'G'){
        	$str = "
        	select 
        	a.recid,
        	a.ART_DESC,
        	trim(a.ART_CODE) __mdata,
        	a.ART_SKU,
        	a.ART_SDU,
        	a.ART_IMG,
        	a.ART_NCBM,
        	a.ART_NCONVF,
        	a.ART_UOM,
        	a.ART_BARCODE1,
        	a.ART_HIERC3,
        	a.ART_HIERC4,
        	IFNULL(kk.art_uprice,a.ART_UPRICE) ART_UPRICE,
        	IFNULL(kk.art_cost,a.ART_UCOST) ART_UCOST,
        	sha2(concat(a.recid,'{$mpw_tkn}'),384) mtkn_prdltr 
        	from {$this->db_erp}.`mst_article`  a
        	LEFT JOIN `mst_article_per_branch` kk
        	ON (a.`recid` = kk.`artID` {$str_branch})
        	where a.ART_PRODT = 'FG' AND a.ART_ISDISABLE = '0' AND (a.ART_CODE like '%$term%' or a.ART_DESC like '%$term%' or a.ART_BARCODE1 like '%$term%') order BY a.ART_DESC limit 50 
        	";
        }
        else{
        	$str = "
        	select recid,ART_DESC,trim(ART_CODE) __mdata,
        	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
        	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
        	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
        	from {$this->db_erp}.`mst_article` where ART_PRODT = 'FG' AND ART_ISDISABLE = '0' AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') order BY ART_DESC limit 50 
        	";
        }			
        $q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        if($q->resultID->num_rows > 0) { 
        	$rrec = $q->getResultArray();
        	foreach($rrec as $row):
        		$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
        		array_push($autoCompleteResult,array(
        			"mtkn_rid" => $mtkn_rid,
        			"value" => $row['__mdata'],
        			"ART_DESC" => $row['ART_DESC'],  
        			"ART_SKU" => $row['ART_SKU'], 
        			"ART_SDU" => $row['ART_SDU'], 
        			"ART_IMG" => $row['ART_IMG'],
        			"ART_UOM"   => $row['ART_UOM'],
        			"ART_NCONVF" => $row['ART_NCONVF'],
        			"ART_UPRICE" => $row['ART_UPRICE'],
        			"ART_UCOST" => $row['ART_UCOST'],  
        			"ART_CODE" => $row['__mdata'],
        			"ART_NCBM" => $row['ART_NCBM'],
        			"ART_MATRID" => $row['recid'],
        			"ART_BARCODE1" => $row['ART_BARCODE1'],
        			"ART_HIERC3"     => $row['ART_HIERC3'],
        			"ART_HIERC4" => $row['ART_HIERC4'],
        			

        		));
        	endforeach;
        }
        $q->freeResult();
        
        echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article_fgpo

	public function mat_article_fg() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$this->db_erp = $this->mydbname->medb(0);
		$filter  = $this->request->getVar('filter');
		$filter2 = $this->request->getVar('filter2');
		$ischck_mkg = $this->request->getVar('ischck_mkg');
		$term = $this->request->getVar('term');
		$autoCompleteResult = array();
		$comp_usr = $this->myusermod->ua_comp_code($this->db_erp,$cuser);


		$str = "
		select 
		a.`recid`,
		a.`ART_DESC`,
		a.`ART_CODE` __mdata,
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
		sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
		FROM
		 `mst_article` a where a.`ART_PRODT` = 'FG' AND (a.`ART_PRODL` = 'TSHIRT' OR a.`ART_PRODL` = 'PANTS' OR a.`ART_PRODL` = 'GWEMC') AND a.`ART_CODE` LIKE '%{$term}%'
		LIMIT 100
		";
		
        $q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        if($q->resultID->num_rows > 0) { 
        	$rrec = $q->getResultArray();
        	foreach($rrec as $row):
        		$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
        		array_push($autoCompleteResult,array(
        			"mtkn_rid" => $mtkn_rid,
        			"value" => $row['__mdata'],
        			"ART_DESC" => $row['ART_DESC'],  
        			"ART_SKU" => $row['ART_SKU'], 
        			"ART_SDU" => $row['ART_SDU'], 
        			"ART_IMG" => $row['ART_IMG'],
        			"ART_UOM"   => $row['ART_UOM'],
        			"ART_NCONVF" => $row['ART_NCONVF'],
        			"ART_UPRICE" => $row['ART_UPRICE'],
        			"ART_UCOST" => $row['ART_UCOST'],  
        			"ART_CODE" => $row['__mdata'],
        			"ART_NCBM" => $row['ART_NCBM'],
        			"ART_MATRID" => $row['recid'],
        			"ART_BARCODE1" => $row['ART_BARCODE1'],
        			"ART_HIERC3"     => $row['ART_HIERC3'],
        			"ART_HIERC4" => $row['ART_HIERC4'],
        			

        		));
        	endforeach;
        }
        $q->freeResult();
        
        echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article



    public function companyBranch_bgrp() { 
        $cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		
		$term = $this->request->getVar('term');
		$autoCompleteResult = array();

		$str = "
		SELECT a.`branch_group` __mdata
		FROM {$this->db_erp}.mst_wshe_branch_grp_bin a
		WHERE !(a.`branch_group` = '')
		AND (a.`branch_group` like '%{$term}%')  GROUP BY a.`branch_group` ASC
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				array_push($autoCompleteResult,array("value" => $row['__mdata']));
				
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
	}  //end companyBranch_bgrp

	public function auto_add_lines_po() { 
        $cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		
		$term = $this->request->getVar('term');

		$wshe_id = $this->request->getVar('wshe_id');
		$itemcode = $this->request->getVar('itemcode');
		$itemdesc = $this->request->getVar('itemdesc');
		$sku = $this->request->getVar('sku');
		$convf = $this->request->getVar('convf');
		$ucost = $this->request->getVar('ucost');
		$__rid = $this->request->getVar('__rid');
		$cbm = $this->request->getVar('cbm');
		$txt_itmgrparea_s = $this->request->getVar('txt_itmgrparea_s');

		$qtyBox = 0;
		if(!empty($txt_itmgrparea_s)){
			$str_grp= "AND a.`branch_group` = '$txt_itmgrparea_s'";
		}
		$fld_wshe_code ='';
		$fld_wshe_id ='';
		$adatar1 = array();
		$_items = '';

		$str = "
		select recid,wshe_code
		from {$this->db_erp}.mst_wshe where sha2(concat(recid,'{$mpw_tkn}'),384) ='$wshe_id'
		";
		//var_dump($str);
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
		if($q->resultID->num_rows == 0) { 
			echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Failed</strong> Invalid Warehouse!!!.</div>";
			die();
		}
		$rw = $q->getRowArray();
        $fld_wshe_code = $rw['wshe_code'];
		$fld_wshe_id = $rw['recid'];
        $q->freeResult();

        $str = "
		SELECT 
		sha2(concat(a.`plnt_id`,'{$mpw_tkn}'),384) plnt_id,
		sha2(concat(a.`wshe_id`,'{$mpw_tkn}'),384) wshe_id,
		sha2(concat(b.`recid`,'{$mpw_tkn}'),384)  __grpid,
		sha2(concat(c.`recid`,'{$mpw_tkn}'),384) __binid,
		a.`wshe_bin_name`,
		a.`wshe_grp_name`,
		e.`plnt_code`,
		d.`wshe_code`
		FROM {$this->db_erp}.`mst_wshe_branch_grp_bin`  a
		JOIN {$this->db_erp}.`mst_plant`  e
		ON (a.`plnt_id` = e.`recid`)
		JOIN {$this->db_erp}.`mst_wshe`  d
		ON (a.`wshe_id` = d.`recid`)
		JOIN {$this->db_erp}.`mst_wshe_grp`  b
		ON (a.`wshe_grp_name` = b.`wshe_grp` AND b.`wshe_id` = '$fld_wshe_id')
		JOIN {$this->db_erp}.`mst_wshe_bin`  c
		ON (a.`wshe_bin_name` = c.`wshe_bin_name` AND c.`wshegrp_id` = b.`recid` AND c.`wshe_id` = '$fld_wshe_id')
		WHERE a.`wshe_id` = '$fld_wshe_id' {$str_grp}
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
		if($q->getNumRows() == 0) { 
			echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Failed</strong> No Records Found!!!.</div>";
			die();
		}
		$rrec = $q->getResultArray();
		foreach($rrec as $rw):
			$wshe_bin_name = $rw['wshe_bin_name'];
			$wshe_grp_name = $rw['wshe_grp_name'];
			$wshe_bin = $rw['__binid'];
			$wshe_grp = $rw['__grpid'];
			$plant_id = $rw['plnt_id'];
			$wshe_id = $rw['wshe_id'];
			$plant_code = $rw['plnt_code'];
			$wshe_code = $rw['wshe_code'];
			
			$_items = $wshe_bin_name . 'x|x' . $wshe_grp_name . 'x|x' . $wshe_bin . 'x|x' . $wshe_grp . 'x|x' . $plant_id . 'x|x' . $wshe_id . 'x|x' . $plant_code . 'x|x' . $wshe_code . 'x|x';
			//$medata = explode("x|x",$_items);
			array_push($adatar1,$_items);
		endforeach;
		
		for($xx = 0; $xx < count($adatar1); ++$xx) {
			$xdata = explode("x|x",$adatar1[$xx]);
			$count = count($adatar1);
			//$xdata = $adatar1[$xx];
			$wshe_bin_name = $xdata[0];
			$wshe_grp_name = $xdata[1];
			$wshe_bin = $xdata[2];
			$wshe_grp = $xdata[3];
			if(!empty($wshe_bin_name)){
				$chtml = "
					<script>
						my_add_line_item_gwpo('$wshe_bin_name','$wshe_grp_name','$wshe_bin','$wshe_grp','$plant_id','$wshe_id','$plant_code','$wshe_code','$itemcode','$itemdesc','$sku','$convf','$ucost','$__rid','$cbm','$qtyBox');
					</script>
					

					";
				echo $chtml;
			}
			
		}

	}  //end vendorpo
	public function getCDPlantWarehouse_data_bytkn_v2($mtkn_rid = ''){
		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$str = "SELECT `recid` whID,`plnt_id` plntID,`wshe_code`  
		FROM {$this->db_erp}.`mst_wshe` WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) ='{$mtkn_rid}' AND `is_crossdocking` = 'N' ";
		$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);

		if($q->getNumRows() > 0):
			return $q->getRowArray();
		else:
			echo "<div class=\"alert bg-pdanger mt-2 text-center fw-bold\"><i class=\"bi bi-exclamation-circle-fill\"></i> Invalid Warehouse [NOT FOUND]!</div>";
				die();
		endif; 
		$q->freeResult();

	}
	public function myget_warehouse_group_list_v2(){

			$cuser      = $this->mylibzdb->mysys_user();
			$mpw_tkn    = $this->mylibzdb->mpw_tkn();
			$term       = $this->request->getVar('term');
			$mtkn_uid   = $this->request->getVar('mtkn_uid');
			$str_pwshe  = '';

			if(!empty($mtkn_uid)){
				$wshe_data      = $this->getCDPlantWarehouse_data_bytkn_v2($mtkn_uid);
				$active_plnt_id = $wshe_data['plntID'];
				$active_wshe_id = $wshe_data['whID'];
				$str_pwshe      = "AND `plnt_id` = '{$active_plnt_id}' AND `wshe_id` = '{$active_wshe_id}'";
			}


			$autoCompleteResult = array();

			$str = "
			SELECT
			`recid`,
			`wshe_grp` AS `__mdata`
			FROM
			{$this->db_erp}.`mst_wshe_grp`
			WHERE
			(`wshe_grp` LIKE '%{$term}%')
			{$str_pwshe}
			LIMIT 50
			";
			$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			if($q->getNumRows() > 0) { 
				$rrec = $q->getResultArray();
				foreach($rrec as $row):
					$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
					array_push($autoCompleteResult,array("value" => $row['__mdata'], 
						"mtkn_rid" => $mtkn_rid));
				endforeach;
			}
			$q->freeResult();
			echo json_encode($autoCompleteResult);
	}//end
	// public function mat_article_rm_backup() { 
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
    //             //if filter id not empty
    //     if(!empty($filter)):
    //     	$str_filter = "AND ART_HIERC2 = '{$filter}'";
    //     endif;
    //     		//if filter id not empty
    //     if(!empty($filter2)):
    //     	$str_filter2 = "AND ART_DESC_CODE = '{$filter2}'";
    //     endif;
        
    //     if($ischck_mkg == 'Y'){
    //     	$str_mkg = "AND ART_CODE like 'MKG%'";
    //     }
    //     elseif($ischck_mkg == 'N'){
    //     	$str_mkg = "AND !(ART_CODE like 'MKG%')";
    //     }
    //     else{
    //     	$str_mkg = "";
    //     }
    //   //  if(!empty($str_comp)){
    //     $result = $this->myusermod->get_Active_menus($this->db_erp,$cuser,"myuamd_id='145'","myua_md");
    //     if($result == 1){
    //     	$str = "
    //     	select recid,ART_DESC,trim(ART_CODE) __mdata,
    //     	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
    //     	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
    //     	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
    //     	from {$this->db_erp}.`mst_article` where ART_PRODT = 'RM' AND ART_ISDISABLE = '0' AND (ART_HIERC1 = 'TSHIRT' OR ART_HIERC1 = 'PANTS') AND ART_HIERC2 = 'CLOTHING' AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') order BY ART_DESC limit 50 
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
    //     	where a.ART_PRODT = 'RM' AND a.ART_ISDISABLE = '0' AND (a.ART_CODE like '%$term%' or a.ART_DESC like '%$term%' or a.ART_BARCODE1 like '%$term%') order BY a.ART_DESC limit 50 
    //     	";
    //     }
    //     else{
    //     	$str = "
    //     	select recid,ART_DESC,trim(ART_CODE) __mdata,
    //     	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
    //     	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
    //     	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
    //     	from {$this->db_erp}.`mst_article` where ART_PRODT = 'RM' AND ART_ISDISABLE = '0' AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') {$str_mkg} {$str_filter} {$str_filter2} order BY ART_DESC limit 50 
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
    // } 

	public function mat_article_rm() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$this->db_erp = $this->mydbname->medb(0);
		$filter  = $this->request->getVar('filter');
		$filter2 = $this->request->getVar('filter2');
		$ischck_mkg = $this->request->getVar('ischck_mkg');
		$term = $this->request->getVar('term');
		$autoCompleteResult = array();
		$comp_usr = $this->myusermod->ua_comp_code($this->db_erp,$cuser);
		$str_comp='';
		$str_filter = '';
		$str_filter2 = '';
		if(count($comp_usr) > 0) { 

			$str_comp = "";
			for($xx = 0; $xx < count($comp_usr); $xx++) { 
				$mart_comp = $comp_usr[$xx];
				$str_comp .= "SUBSTR(ART_COMP,1,INSTR(ART_COMP,'~')-1)= '$mart_comp' or ";
            } //end for 
            $str_comp = "and (" . substr($str_comp,0,strlen($str_comp) - 3) . ")";

        }
        $fld_pbranch = $this->request->getVar('pbranchid');//GET id
        $str_branch ="";
        $BRNCH_MAT_FLAG ='';
        if(!empty($fld_pbranch)){
        	$str = "select recid,BRNCH_NAME,BRNCH_CODE,BRNCH_OCODE2,BRNCH_MAT_FLAG
        	from {$this->db_erp}.`mst_companyBranch` aa where `recid` = '$fld_pbranch'";
        	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        	//$this->mylibzdb->user_logs_activity_module($this->db_erp,'COMPANY','',$cuser,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

        	$rw = $q->getRowArray();
        	$BRNCH_MAT_FLAG = $rw['BRNCH_MAT_FLAG'];
        	$fld_branch_recid = $rw['recid'];
        	$str_branch ="AND kk.`brnchID` = '$fld_branch_recid' ";

        	$q->freeResult();
			//END BRANCH
        }
                //if filter id not empty
        if(!empty($filter)):
        	$str_filter = "AND ART_HIERC2 = '{$filter}'";
        endif;
        		//if filter id not empty
        if(!empty($filter2)):
        	$str_filter2 = "AND ART_DESC_CODE = '{$filter2}'";
        endif;
        
        if($ischck_mkg == 'Y'){
        	$str_mkg = "AND ART_CODE like 'MKG%'";
        }
        elseif($ischck_mkg == 'N'){
        	$str_mkg = "AND !(ART_CODE like 'MKG%')";
        }
        else{
        	$str_mkg = "";
        }
      //  if(!empty($str_comp)){

		$str = "
		select recid,ART_DESC,trim(ART_CODE) __mdata,
		ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
		ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
		sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
		from {$this->db_erp}.`mst_article` where ART_PRODT = 'RAW-MAT' AND ART_ISDISABLE = '0' AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') {$str_mkg} {$str_filter} {$str_filter2} order BY ART_DESC limit 50 
		";
		
        $q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        if($q->resultID->num_rows > 0) { 
        	$rrec = $q->getResultArray();
        	foreach($rrec as $row):
        		$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
        		array_push($autoCompleteResult,array(
        			"mtkn_rid" => $mtkn_rid,
        			"value" => $row['__mdata'],
        			"ART_DESC" => $row['ART_DESC'],  
        			"ART_SKU" => $row['ART_SKU'], 
        			"ART_SDU" => $row['ART_SDU'], 
        			"ART_IMG" => $row['ART_IMG'],
        			"ART_UOM"   => $row['ART_UOM'],
        			"ART_NCONVF" => $row['ART_NCONVF'],
        			"ART_UPRICE" => $row['ART_UPRICE'],
        			"ART_UCOST" => $row['ART_UCOST'],  
        			"ART_CODE" => $row['__mdata'],
        			"ART_NCBM" => $row['ART_NCBM'],
        			"ART_MATRID" => $row['recid'],
        			"ART_BARCODE1" => $row['ART_BARCODE1'],
        			"ART_HIERC3"     => $row['ART_HIERC3'],
        			"ART_HIERC4" => $row['ART_HIERC4'],
        			

        		));
        	endforeach;
        }
        $q->freeResult();
        
        echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article

	// public function mat_article(){ 
	// 		$cuser = $this->mylibzdb->mysys_user();
	// 		$mpw_tkn = $this->mylibzdb->mpw_tkn();

	// 		$filter  =$this->request->getVar('filter');
	// 		$filter2 =$this->request->getVar('filter2');
	// 		$filter3 =$this->request->getVar('filter3'); //assorted
	// 		$term =$this->request->getVar('term');
	// 		$autoCompleteResult = array();
	// 		$comp_usr = $this->mydataua->ua_comp_code($this->db_erp,$cuser);
	// 		$str_comp='';
	// 		$str_filter = '';
	// 		$str_filter2 = '';
	// 		$str_filter3 = '';
	// 		if(count($comp_usr) > 0) { 

	// 			$str_comp = "";
	// 			for($xx = 0; $xx < count($comp_usr); $xx++) { 
	// 				$mart_comp = $comp_usr[$xx];
	// 				$str_comp .= "SUBSTR(ART_COMP,1,INSTR(ART_COMP,'~')-1)= '$mart_comp' or ";
	//             } //end for 
	//             $str_comp = "and (" . substr($str_comp,0,strlen($str_comp) - 3) . ")";

	//         }

	//                 //if filter id not empty
	//         if(!empty($filter)):
	//         	$str_filter = "AND ART_HIERC2 = '{$filter}'";
	//         endif;
	//         //if filter id not empty
	//         if(!empty($filter2)):
	//         	$str_filter2 = "AND ART_DESC_CODE = '{$filter2}'";
	//         endif;
	//         //if filter id not empty
	//         if(!empty($filter3)):
	//         	$str_filter3 = "AND ART_CODE LIKE '%ASSTD%'";
	//         endif;

	//       //  if(!empty($str_comp)){
	//         $result = $this->mydataua->get_Active_menus($this->db_erp,$cuser,"myuamd_id='145'","myua_md");
	//         if($result == 1){
	//         	$str = "
	//         	select recid,ART_DESC,trim(ART_CODE) __mdata,
	//         	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
	//         	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
	//         	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
	//         	from {$this->db_erp}.`mst_article` where ART_ISDISABLE = '0' AND (ART_HIERC1 = 'TSHIRT' OR ART_HIERC1 = 'PANTS') AND ART_HIERC2 = 'CLOTHING' AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') order BY ART_DESC limit 50 
	//         	";
	//         }
	//         else{
	//         	$str = "
	//         	select recid,ART_DESC,trim(ART_CODE) __mdata,
	//         	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
	//         	ART_BARCODE1,ART_HIERC3,ART_HIERC4,ART_UOM,
	//         	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
	//         	from {$this->db_erp}.`mst_article` where ART_ISDISABLE = '0' AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') {$str_filter} {$str_filter2} {$str_filter3} order BY ART_DESC limit 50 
	//         	";
	//         }			
	//         $q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	//         if($q->getNumRows() > 0) { 
	//         	$rrec = $q->getResultArray();
	//         	foreach($rrec as $row):
	//         		$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
	//         		array_push($autoCompleteResult,array(
	//         			"mtkn_rid" => $mtkn_rid,
	//         			"value" => $row['__mdata'],
	//         			"ART_DESC" => $row['ART_DESC'],  
	//         			"ART_SKU" => $row['ART_SKU'], 
	//         			"ART_SDU" => $row['ART_SDU'], 
	//         			"ART_IMG" => $row['ART_IMG'],
	//         			"ART_UOM"   => $row['ART_UOM'],
	//         			"ART_NCONVF" => $row['ART_NCONVF'],
	//         			"ART_UPRICE" => $row['ART_UPRICE'],
	//         			"ART_UCOST" => $row['ART_UCOST'],  
	//         			"ART_CODE" => $row['__mdata'],
	//         			"ART_NCBM" => $row['ART_NCBM'],
	//         			"ART_MATRID" => $row['recid'],
	// 					"ART_BARCODE1" => $row['ART_BARCODE1'],
	// 					"ART_HIERC3"  => $row['ART_HIERC3'],
	// 					"ART_HIERC4" => $row['ART_HIERC4'],
	//         		));
	//         	endforeach;
	//         }
	//         $q->freeResult();
	        
	//         echo json_encode($autoCompleteResult);
	//       //  }
	//     } 


    		public function mat_article_asstd(){ 
    			
    		$cuser = $this->mylibzdb->mysys_user();
    		$mpw_tkn = $this->mylibzdb->mpw_tkn();

    		$term               = $this->request->getVar('term');
    		$autoCompleteResult = array();
    		$comp_usr           = $this->mydataua->ua_comp_code($this->db_erp,$cuser);
    		$str_comp           ='';
    		if(count($comp_usr) > 0) { 

    			$str_comp = "";
    			for($xx = 0; $xx < count($comp_usr); $xx++) { 
    				$mart_comp = $comp_usr[$xx];
    				$str_comp .= "SUBSTR(ART_COMP,1,INSTR(ART_COMP,'~')-1)= '$mart_comp' or ";
                } //end for 
                $str_comp = "and (" . substr($str_comp,0,strlen($str_comp) - 3) . ")";

            }

          	$result = $this->mydataua->get_Active_menus($this->db_erp,$cuser,"myuamd_id='145'","myua_md");
    		if($result == 1){
    			$str = "
    	        select recid,ART_DESC,trim(ART_CODE) __mdata,
    	        ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
    	        sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
    	        from {$this->db_erp}.`mst_article` where (ART_HIERC1 = 'TSHIRT' OR ART_HIERC1 = 'PANTS') AND ART_HIERC2 = 'CLOTHING' AND (`ART_CODE` like '%ASSTD%') AND (ART_CODE like '%$term%' or ART_DESC like '%$term%' or ART_BARCODE1 like '%$term%') order BY ART_DESC limit 50 
    	        ";
    		}
    		else{
    			$str = "
    	        select recid,ART_DESC,trim(ART_CODE) __mdata,
    	        ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
    	        sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
    	        from {$this->db_erp}.`mst_article` where (`ART_CODE` like '%ASSTD%') AND (ART_CODE like '%$term%' or ART_DESC like '%$term%' or ART_BARCODE1 like '%$term%') order BY ART_DESC limit 50 
    	        ";
    		}
            
           $q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() > 0) { 
            	$rrec = $q->getResultArray();
            	foreach($rrec as $row):
            		$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
            		array_push($autoCompleteResult,array(
    					"mtkn_rid"   => $mtkn_rid,
    					"value"      => $row['__mdata'],
    					"ART_DESC"   => $row['ART_DESC'],  
    					"ART_SKU"    => $row['ART_SKU'], 
    					"ART_SDU"    => $row['ART_SDU'], 
    					"ART_IMG"    => $row['ART_IMG'],
    					"ART_NCONVF" => $row['ART_NCONVF'],
    					"ART_UPRICE" => $row['ART_UPRICE'],
    					"ART_UCOST"  => $row['ART_UCOST'],  
    					"ART_CODE"   => $row['__mdata'],
    					"ART_NCBM"   => $row['ART_NCBM'],
            			

            		));
            	endforeach;
            }
            $q->freeResult();
            
            echo json_encode($autoCompleteResult);
          //  }
        }

        public function mat_article_reg() { 
        	$cuser = $this->mylibzdb->mysys_user();
        	$mpw_tkn = $this->mylibzdb->mpw_tkn();


        	$term = $this->request->getVar('term');
        	$autoCompleteResult = array();
        	$comp_usr = $this->mydataua->ua_comp_code($this->db_erp,$cuser);
        	$str_comp='';
        	if(count($comp_usr) > 0) { 

        		$str_comp = "";
        		for($xx = 0; $xx < count($comp_usr); $xx++) { 
        			$mart_comp = $comp_usr[$xx];
        			$str_comp .= "SUBSTR(ART_COMP,1,INSTR(ART_COMP,'~')-1)= '$mart_comp' or ";
                } //end for 
                $str_comp = "and (" . substr($str_comp,0,strlen($str_comp) - 3) . ")";

            }

            $result = $this->mydataua->get_Active_menus($this->db_erp,$cuser,"myuamd_id='145'","myua_md");
            if($result == 1){
            	$str = "
            	select recid,ART_DESC,trim(ART_CODE) __mdata,
            	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
            	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
            	from {$this->db_erp}.`mst_article` where (ART_HIERC1 = 'TSHIRT' OR ART_HIERC1 = 'PANTS') AND ART_HIERC2 = 'CLOTHING' AND !(`ART_CODE` like '%ASSTD%') AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') order BY ART_DESC limit 50 
            	";
            }
            else{
            	$str = "
            	select recid,ART_DESC,trim(ART_CODE) __mdata,
            	ART_SKU,ART_SDU,ART_IMG,ART_NCBM,ART_NCONVF,ART_UPRICE,ART_UCOST,
            	sha2(concat(recid,'{$mpw_tkn}'),384) mtkn_prdltr 
            	from {$this->db_erp}.`mst_article` where !(`ART_CODE` like '%ASSTD%') AND (ART_CODE like '%{$term}%' or ART_DESC like '%{$term}%' or ART_BARCODE1 like '%{$term}%') order BY ART_DESC limit 50 
            	";
            }

            $q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($q->getNumRows() > 0) { 
            	$rrec = $q->getResultArray();
            	foreach($rrec as $row):
            		$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
            		array_push($autoCompleteResult,array(
            			"mtkn_rid" => $mtkn_rid,
            			"value" => $row['__mdata'],
            			"ART_DESC" => $row['ART_DESC'],  
            			"ART_SKU" => $row['ART_SKU'], 
            			"ART_SDU" => $row['ART_SDU'], 
            			"ART_IMG" => $row['ART_IMG'],
            			"ART_NCONVF" => $row['ART_NCONVF'],
            			"ART_UPRICE" => $row['ART_UPRICE'],
            			"ART_UCOST" => $row['ART_UCOST'],  
            			"ART_CODE" => $row['__mdata'],
            			"ART_NCBM" => $row['ART_NCBM'],
            			"ART_MATRID" => $row['recid'],



            		));
            	endforeach;
            }
            $q->freeResult();

            echo json_encode($autoCompleteResult);
          //  }
        }


    	public function company_search_v() { 
    		$cuser = $this->mylibzdb->mysys_user();
    		$mpw_tkn = $this->mylibzdb->mpw_tkn();
    		
    		$aua_comp = $this->mydataua->ua_comp($this->db_erp,$cuser);
    		$str_comp = " recid = '__MECOMP__' ";

    		if(count($aua_comp) > 0) { 
    			$str_comp = "";
    			for($xx = 0; $xx < count($aua_comp); $xx++) { 
    				$mcomp = $aua_comp[$xx];
    				$str_comp .= " recid = '$mcomp' or ";
                } //end for 
                $str_comp = "(" . substr($str_comp,0,strlen($str_comp) - 3) . ")";
            }



            $term = $this->request->getVar('term');
            $autoCompleteResult = array();
            $str = "
            select recid,COMP_CODE,COMP_NAME __mdata,COMP_ADDR1,COMP_TINNO from {$this->db_erp}.mst_company
            ";

    		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);// AND {$str_comp} 
    		if($q->getNumRows() > 0) { 
    			$rrec = $q->getResultArray();
    			foreach($rrec as $row):
    				$mtkn_rid = hash('sha384', $row['COMP_CODE'] . $mpw_tkn); 
    				$mtkn_recid = hash('sha384', $row['recid'] . $mpw_tkn);
    				array_push($autoCompleteResult,array("value" => $row['__mdata'], 
    					"mtkn_rid" => $mtkn_rid,
    					"COMP_ADDR1" => $row['COMP_ADDR1'],
    					"COMP_TINNO" => $row['COMP_TINNO'],
    					"mtkn_recid" => $mtkn_recid ));
    				
    			endforeach;
    		}
    		$q->freeResult();
    		echo json_encode($autoCompleteResult);
    	}  //end company_search

    	public function gr_code() { 
    		$cuser = $this->mylibzdb->mysys_user();
    		$mpw_tkn = $this->mylibzdb->mpw_tkn();
    		$mtkn_wshe  = urldecode($this->request->getVar('mtkn_wshe'));
    		$term = $this->request->getVar('term');

    		$autoCompleteResult = array();
    		$mplt_id = '';
    		$str_wshe= '';
    		$str = "select aa.recid from {$this->db_erp}.`mst_wshe` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_wshe'";
    		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    		if($q->getNumRows() == 0) { 
    		} else { 
    			$rw = $q->getRowArray();
    			$mwshe_id = $rw['recid'];
    			$str_wshe= "AND (wshe_id = '$mwshe_id')";
    		}
    		$q->freeResult();
    		
    		$str = "
    		SELECT recid,trim(`grtrx_no`) __mdata 
    		from {$this->db_erp}.`trx_wshe_gr_hd` where (grtrx_no like '%{$term}%' or grtrx_no like '%{$term}%') AND cd_tag = 'Y' AND is_bcodegen = 'Y' {$str_wshe} order BY recid limit 50 
    		";
    		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    		if($q->getNumRows() > 0) { 
    			$rrec = $q->getResultArray();
    			foreach($rrec as $row):
    				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
    				array_push($autoCompleteResult,array("value" => $row['__mdata'], 
    					"mtkn_rid" => $mtkn_rid));
    			endforeach;
    		}
    		$q->freeResult();
    		echo json_encode($autoCompleteResult);
    	}  //end po_wshe

}  //end main class
