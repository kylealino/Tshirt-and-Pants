<?php 

/*
Arnel Oquien
September 13, 2022
*/
namespace App\Models;
use CodeIgniter\Model;
class MyDatauaModel extends Model { 
	public function __construct()
	{
		parent::__construct();
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(1);
		$this->mylibzdb = model('App\Models\mylibzdbModel');
	} 
	///////USERMANAGEMENT//////////////////////////////////////////////////////////////////////////////////////////
	public function ua_plant($dbname,$uname){
		$adata = array();
		$str = "select myuaplant from {$this->db_erp}.`myua_plant` where myusername ='$uname' AND ISACTIVE='Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['myuaplant'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function ua_whse($dbname,$uname){
		$adata = array();
		$str = "select myuawhse,wh.`recid` from {$this->db_erp}.`myua_whse` ua
		        JOIN 
               {$this->db_erp}.`mst_wshe` wh 
               ON
               (ua.`myuawhse`=wh.`wshe_code`)
               where 
               myusername ='$uname' AND ISACTIVE='Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['recid'];

			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
		public function ua_whse_t($dbname,$uname){
		$adata = array();
		$str = "select myuawhse,wh.`recid` from {$this->db_erp}.`myua_whse` ua
		        JOIN 
               {$this->db_erp}.`mst_wshe` wh 
               ON
               (ua.`myuawhse`=wh.`wshe_code`)
               where 
               myusername ='$uname' AND ISACTIVE='Y' ORDER BY myuawhse";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['recid'];

			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
    
	public function ua_whsebin($dbname,$uname){
		$adata = array();
		$str = "select myuawhsebin from {$this->db_erp}.`myua_whsebin` where myusername ='$uname' AND ISACTIVE='Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['myuawhsebin'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function ua_comp($dbname,$uname){
		$adata = array();
		$str = "select myuacomp from {$this->db_erp}.`myua_company` where myusername ='$uname' AND ISACTIVE='Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['myuacomp'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function ua_brnch($dbname,$uname){
		$adata = array();
		$str = "select myuabranch from {$this->db_erp}.`myua_branch` where myusername ='$uname' AND ISACTIVE='Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		//var_dump($str);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['myuabranch'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function ua_supp($dbname,$uname){
		$adata = array();
		$str = "select myuasupp_id from {$this->db_erp}.`myua_supp` where myusername ='$uname' AND ISACTIVE='Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['myuasupp_id'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	////////////////////////////////////////////COUNT////////////////////////////////////////
	//----------------PLANT-----------------------//
	public function get_Active_plant_all($uid){
    	$adata = array();
		$str = "SELECT plnt_code,plnt_name FROM {$this->db_erp}.`mst_plant`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['plnt_code'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_plant($uid){
		$adata = "";//array();
		$str = "SELECT myuaplant,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_plant` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_plant_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(plnt_code) COUNT_M FROM {$this->db_erp}.`mst_plant`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//----------------WAREHOUSE-----------------------//
	public function get_Active_whse_all($uid){
    	$adata = array();
		$str = "SELECT wshe_code,wshe_name FROM {$this->db_erp}.`mst_wshe`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['wshe_code'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_whse($uid){
		$adata = "";//array();
		$str = "SELECT myuawhse,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_whse` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_whse_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(wshe_code) COUNT_M FROM {$this->db_erp}.`mst_wshe`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//----------------WAREHOUSE BIN-----------------------//
	public function get_Active_whsebin_all($uid){
    	$adata = array();
		$str = "SELECT recid,wshe_bin_name FROM {$this->db_erp}.`mst_wshe_bin`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_whsebin($uid){
		$adata = "";//array();
		$str = "SELECT myuawhsebin,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_whsebin` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_whsebin_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(wshe_bin_name) COUNT_M FROM {$this->db_erp}.`mst_wshe_bin`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//----------------COMPANY-----------------------//
	public function get_Active_comp_all($uid){
    	$adata = array();
		$str = "SELECT recid,COMP_CODE FROM {$this->db_erp}.`mst_company`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_comp($uid){
		$adata = "";//array();
		$str = "SELECT myuacomp,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_company` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_comp_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(COMP_CODE) COUNT_M FROM {$this->db_erp}.`mst_company`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//----------------BRANCH-----------------------//
	public function get_Active_brnch_all($uid){
    	$adata = array();
		$str = "SELECT recid,BRNCH_CODE FROM {$this->db_erp}.`mst_companyBranch`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//----------------ITO GINAGAMIT----------------------//
	public function get_Active_branch_all($uid){
    	$adata = array();
		$str = "SELECT recid,BRNCH_CODE FROM {$this->db_erp}.`mst_companyBranch`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_brnch($uid){
		$adata = "";//array();
		$str = "SELECT myuabranch,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_branch` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_brnch_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(BRNCH_CODE) COUNT_M FROM {$this->db_erp}.`mst_companyBranch`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//----------------MD-----------------------//
	public function get_Active_md_all($uid){
    	$adata = array();
		$str = "SELECT recid,md_name FROM {$this->db_erp}.`menu_md`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_md($uid){
		$adata = "";//array();
		$str = "SELECT myuamd_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_md` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_md_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(md_name) COUNT_M FROM {$this->db_erp}.`menu_md`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//----------------TRX-----------------------//
	public function get_Active_trx_all($uid){
    	$adata = array();
		$str = "SELECT recid,trx_name FROM {$this->db_erp}.`menu_trx`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_trx($uid){
		$adata = "";//array();
		$str = "SELECT myuatrx_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_trx` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_trx_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(trx_name) COUNT_M FROM {$this->db_erp}.`menu_trx`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//---------------AP-----------------------//
	public function get_Active_ap_all($uid){
    	$adata = array();
		$str = "SELECT recid,ap_name FROM {$this->db_erp}.`menu_ap`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_ap($uid){
		$adata = "";//array();
		$str = "SELECT myuaap_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_ap` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_ap_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(ap_name) COUNT_M FROM {$this->db_erp}.`menu_ap`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//---------------AR-----------------------//
	public function get_Active_ar_all($uid){
    	$adata = array();
		$str = "SELECT recid,ar_name FROM {$this->db_erp}.`menu_ar`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_ar($uid){
		$adata = "";//array();
		$str = "SELECT myuaar_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_ar` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_ar_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(ar_name) COUNT_M FROM {$this->db_erp}.`menu_ar`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//---------------CRJ-----------------------//
	public function get_Active_crj_all($uid){
    	$adata = array();
		$str = "SELECT recid,crj_name FROM {$this->db_erp}.`menu_crj`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_crj($uid){
		$adata = "";//array();
		$str = "SELECT myuacrj_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_crj` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_crj_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(crj_name) COUNT_M FROM {$this->db_erp}.`menu_crj`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//---------------CDJ-----------------------//
	public function get_Active_cdj_all($uid){
    	$adata = array();
		$str = "SELECT recid,cdj_name FROM {$this->db_erp}.`menu_cdj`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_cdj($uid){
		$adata = "";//array();
		$str = "SELECT myuacdj_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_cdj` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_cdj_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(cdj_name) COUNT_M FROM {$this->db_erp}.`menu_cdj`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//---------------ACCT-----------------------//
	public function get_Active_acct_all($uid){
    	$adata = array();
		$str = "SELECT recid,acct_name FROM {$this->db_erp}.`menu_acct`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_acct($uid){
		$adata = "";//array();
		$str = "SELECT myuaacct_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_acct` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_acct_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(acct_name) COUNT_M FROM {$this->db_erp}.`menu_acct`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//---------------Maintenance-----------------------//
	public function get_Active_main_all($uid){
    	$adata = array();
		$str = "SELECT recid,main_name FROM {$this->db_erp}.`menu_main`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_main($uid){
		$adata = "";//array();
		$str = "SELECT myuamain_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_main` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_main_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(main_name) COUNT_M FROM {$this->db_erp}.`menu_main`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//---------------NStore-----------------------//
	public function get_Active_nstore_all($uid){
    	$adata = array();
		$str = "SELECT recid,nstore_name FROM {$this->db_erp}.`menu_nstore`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_nstore($uid){
		$adata = "";//array();
		$str = "SELECT myuanstore_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_nstore` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_nstore_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(nstore_name) COUNT_M FROM {$this->db_erp}.`menu_nstore`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//----------------COMPANY ITEM-----------------------//
	public function get_Active_comp_itm_all($uid){
    	$adata = array();
		$str = "SELECT recid,COMP_CODE FROM {$this->db_erp}.`mst_company`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_comp_itm($uid){
		$adata = "";//array();
		$str = "SELECT myuacomp_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_company_itm` WHERE myuaitm_id = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_comp_itm_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(COMP_CODE) COUNT_M FROM {$this->db_erp}.`mst_company`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}

	//---------------NP-----------------------//
	public function get_Active_np_all($uid){
    	$adata = array();
		$str = "SELECT recid,np_name FROM {$this->db_erp}.`menu_np`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//HO---
	public function get_Active_ho_all($uid){
    	$adata = array();
		$str = "SELECT recid,ho_name FROM {$this->db_erp}.`menu_ho`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_np($uid){
		$adata = "";//array();
		$str = "SELECT myuanp_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_np` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}

		public function get_Active_ho($uid){
		$adata = "";//array();
		$str = "SELECT myuaho_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_ho` WHERE myusername = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_np_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(np_name) COUNT_M FROM {$this->db_erp}.`menu_np`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_ho_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(ho_name) COUNT_M FROM {$this->db_erp}.`menu_ho`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//----------------SUPPILER-----------------------//
	public function get_Active_supp_all($uid){
    	$adata = array();
		$str = "SELECT recid,VEND_CODE FROM {$this->db_erp}.`mst_vendor`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $all): 
				$adata[] = $all['recid'].",".$uid.",";
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_supp($uid){
		$adata = "";//array();
		$str = "SELECT myuasupp_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_supp` WHERE myuasupp_id = '$uid' AND ISACTIVE = 'Y'";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_supp_sys($uid){
		$adata = "";//array();
		$str = "SELECT recid,count(VEND_CODE) COUNT_M FROM {$this->db_erp}.`mst_vendor`";
        $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata .= $rw['COUNT_M'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	//////////////////////////END FOR COUNT/////////////////////
	//////////////////////////VIEW////////////////////////////
	public function get_user_role_plant($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_plant($uid);
    	$actual_cnt = $this->get_Active_plant_sys($uid);
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_all[]" name ="mbtn_rolsel_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
       		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
       		<label class="switch2">
    		<input id="mbtn_rolsel_all[]" name ="mbtn_rolsel_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	
    	$str = "SELECT plnt_code,plnt_name FROM {$this->db_erp}.`mst_plant`";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$p_code = $rww['plnt_code'];
    			$p_name = $rww['plnt_name'];
    			$str = "SELECT myuaplant,myusername,ISACTIVE FROM {$this->db_erp}.`myua_plant` WHERE myuaplant ='$p_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['plnt_code'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel" name ="mbtn_rolsel" value ="'.$uid.",".$p_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel" name ="mbtn_rolsel" value ="'.$uid.",".$p_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_rolsel" name ="mbtn_rolsel" value ="'.$uid.",".$p_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	$chtml .='';
    	echo $chtml;

    }
    public function get_user_role_whse($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_whse($uid);
    	$actual_cnt = $this->get_Active_whse_sys($uid);
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_whse_all[]" name ="mbtn_rolsel_whse_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
       		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
       		<label class="switch2">
    		<input id="mbtn_rolsel_whse_all[]" name ="mbtn_rolsel_whse_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	
    	$str = "SELECT wshe_code,wshe_name FROM {$this->db_erp}.`mst_wshe`";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$w_code = $rww['wshe_code'];
    			$w_name = $rww['wshe_name'];
    			$str = "SELECT myuawhse,myusername,ISACTIVE FROM {$this->db_erp}.`myua_whse` WHERE myuawhse='$w_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['wshe_code'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_whse" name ="mbtn_rolsel_whse" value ="'.$uid.",".$w_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_whse" name ="mbtn_rolsel_whse" value ="'.$uid.",".$w_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_rolsel_whse" name ="mbtn_rolsel_whse" value ="'.$uid.",".$w_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	$chtml .='';
    	echo $chtml;

    }
    public function get_user_role_whsebin($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_whsebin($uid);
    	$actual_cnt = $this->get_Active_whsebin_sys($uid);

    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_whsebin_all[]" name ="mbtn_rolsel_whsebin_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
       		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
       		<label class="switch2">
    		<input id="mbtn_rolsel_whsebin_all[]" name ="mbtn_rolsel_whsebin_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	
    	$str = "SELECT a.recid,a.wshe_bin_name,(SELECT b.wshe_grp FROM {$this->db_erp}.`mst_wshe_grp` b WHERE b.recid=a.wshegrp_id) wshegrp_name FROM {$this->db_erp}.`mst_wshe_bin` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$w_code = $rww['recid'];
    			$w_name = $rww['wshe_bin_name'];
    			$whse_name = $rww['wshegrp_name'];
    			$str = "SELECT myuawhsebin,myusername,ISACTIVE FROM {$this->db_erp}.`myua_whsebin` WHERE myuawhsebin='$w_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['wshegrp_name']."~".$rww['wshe_bin_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_whsebin" name ="mbtn_rolsel_whsebin" value ="'.$uid.",".$w_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_whsebin" name ="mbtn_rolsel_whsebin" value ="'.$uid.",".$w_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_rolsel_whsebin" name ="mbtn_rolsel_whsebin" value ="'.$uid.",".$w_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	$chtml .='';
    	echo $chtml;

    }
    public function get_user_role_company($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_comp($uid);//je
    	$actual_cnt = $this->get_Active_comp_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_comp_all[]" name ="mbtn_rolsel_comp_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_comp_all[]" name ="mbtn_rolsel_comp_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,COMP_CODE,COMP_NAME FROM {$this->db_erp}.`mst_company` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$comp_code = $rww['recid'];
    			$comp_name = $rww['COMP_CODE'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuacomp,myusername,ISACTIVE FROM {$this->db_erp}.`myua_company` WHERE myuacomp='$comp_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['COMP_CODE'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_comp" name ="mbtn_rolsel_comp" value ="'.$uid.",".$comp_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_comp" name ="mbtn_rolsel_comp" value ="'.$uid.",".$comp_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_rolsel_comp" name ="mbtn_rolsel_comp" value ="'.$uid.",".$comp_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }
    public function get_user_role_md($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_md($uid);//je
    	$actual_cnt = $this->get_Active_md_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_md_all[]" name ="mbtn_md_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_md_all[]" name ="mbtn_md_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,md_name FROM {$this->db_erp}.`menu_md` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['md_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuamd_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_md` WHERE myuamd_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['md_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_md" name ="mbtn_md" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_md" name ="mbtn_md" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_md" name ="mbtn_md" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }
    public function get_user_role_trx($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_trx($uid);//je
    	$actual_cnt = $this->get_Active_trx_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_trx_all[]" name ="mbtn_trx_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_trx_all[]" name ="mbtn_trx_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,trx_name FROM {$this->db_erp}.`menu_trx` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['trx_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuatrx_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_trx` WHERE myuatrx_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['trx_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_trx" name ="mbtn_trx" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_trx" name ="mbtn_trx" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_trx" name ="mbtn_trx" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }
    public function get_user_role_ap($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_ap($uid);//je
    	$actual_cnt = $this->get_Active_ap_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_ap_all[]" name ="mbtn_ap_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_ap_all[]" name ="mbtn_ap_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,ap_name FROM {$this->db_erp}.`menu_ap` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['ap_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuaap_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_ap` WHERE myuaap_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['ap_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_ap" name ="mbtn_ap" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_ap" name ="mbtn_ap" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_ap" name ="mbtn_ap" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }
    public function get_user_role_ar($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_ar($uid);//je
    	$actual_cnt = $this->get_Active_ar_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_ar_all[]" name ="mbtn_ar_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_ar_all[]" name ="mbtn_ar_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,ar_name FROM {$this->db_erp}.`menu_ar` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['ar_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuaar_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_ar` WHERE myuaar_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['ar_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_ar" name ="mbtn_ar" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_ar" name ="mbtn_ar" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_ar" name ="mbtn_ar" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }
    public function get_user_role_crj($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_crj($uid);//je
    	$actual_cnt = $this->get_Active_crj_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_crj_all[]" name ="mbtn_crj_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_crj_all[]" name ="mbtn_crj_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,crj_name FROM {$this->db_erp}.`menu_crj` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['crj_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuacrj_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_crj` WHERE myuacrj_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['crj_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_crj" name ="mbtn_crj" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_crj" name ="mbtn_crj" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_crj" name ="mbtn_crj" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }	
    public function get_user_role_cdj($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_cdj($uid);//je
    	$actual_cnt = $this->get_Active_cdj_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_cdj_all[]" name ="mbtn_cdj_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_cdj_all[]" name ="mbtn_cdj_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,cdj_name FROM {$this->db_erp}.`menu_cdj` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['cdj_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuacdj_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_cdj` WHERE myuacdj_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['cdj_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_cdj" name ="mbtn_cdj" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_cdj" name ="mbtn_cdj" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_cdj" name ="mbtn_cdj" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }
    public function get_user_role_acct($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_acct($uid);//je
    	$actual_cnt = $this->get_Active_acct_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_acct_all[]" name ="mbtn_acct_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_acct_all[]" name ="mbtn_acct_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,acct_name FROM {$this->db_erp}.`menu_acct` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['acct_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuaacct_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_acct` WHERE myuaacct_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['acct_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_acct" name ="mbtn_acct" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_acct" name ="mbtn_acct" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_acct" name ="mbtn_acct" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }//endfunc
    public function get_user_role_main($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_main($uid);//je
    	$actual_cnt = $this->get_Active_main_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_main_all[]" name ="mbtn_main_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_main_all[]" name ="mbtn_main_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,main_name FROM {$this->db_erp}.`menu_main` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['main_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuamain_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_main` WHERE myuamain_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['main_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_main" name ="mbtn_main" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_main" name ="mbtn_main" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_main" name ="mbtn_main" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }
    public function get_user_role_nstore($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_nstore($uid);//je
    	$actual_cnt = $this->get_Active_nstore_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_nstore_all[]" name ="mbtn_nstore_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_nstore_all[]" name ="mbtn_nstore_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,nstore_name FROM {$this->db_erp}.`menu_nstore` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['nstore_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuanstore_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_nstore` WHERE myuanstore_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['nstore_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_nstore" name ="mbtn_nstore" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_nstore" name ="mbtn_nstore" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_nstore" name ="mbtn_nstore" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }//nstore
    public function get_user_role_np($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_np($uid);//je
    	$actual_cnt = $this->get_Active_np_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_np_all[]" name ="mbtn_np_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_np_all[]" name ="mbtn_np_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,np_name FROM {$this->db_erp}.`menu_np` a ORDER BY np_name";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['np_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuanp_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_np` WHERE myuanp_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['np_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_np" name ="mbtn_np" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_np" name ="mbtn_np" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_np" name ="mbtn_np" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }

    //////////////////////////////END VIEW/////////////////////////////////////
    //////////////////////////////ALLL///////////////////////////////////////
        public function get_ua_plnt_all(){
		$adata = array();
		$str = "select concat(pl.`recid` ,'xOx',trim(pl.`plnt_code`)) _mdata  from {$this->db_erp}.`mst_plant` pl 
         order by pl.`recid` ";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['_mdata'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
		public function get_ua_whse($dbname,$uname,$plnt_uid=''){
		$adata = array();
		$str = "select myuawhse,wh.`recid`,concat(wh.`recid` ,'xOx',trim(myuawhse)) _mdata from {$this->db_erp}.`myua_whse` ua
		        JOIN 
               {$this->db_erp}.`mst_wshe` wh 
               ON
               (ua.`myuawhse`=wh.`wshe_code`)
               where 
               myusername ='$uname' and wh.`plnt_id`='$plnt_uid' AND ISACTIVE='Y' AND wh.`is_crossdocking` = 'Y' order by ua.`myuawhse` ";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['_mdata'];

			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
		
		public function get_ua_plnt($dbname,$uname){
		$adata = array();
		$str = "select myuaplant,pl.recid,concat(pl.`recid` ,'xOx',trim(myuaplant)) _mdata  from {$this->db_erp}.`myua_plant` ua
		JOIN
		{$this->db_erp}.`mst_plant` pl 
         ON
           (ua.`myuaplant`=pl.`plnt_code`)

		where myusername ='$uname' AND ISACTIVE='Y' order by pl.`recid` ";
		var_dump($str);
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['_mdata'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
		public function ua_plant_tuser($dbname,$uname){
		$adata = array();
		$str = "select myuaplant,pl.recid from {$this->db_erp}.`myua_plant` ua
       join {$this->db_erp}.`mst_plant` pl
       on(ua.`myuaplant`=pl.`plnt_code`)
		 where myusername ='$uname' AND ISACTIVE='Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['recid'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}

	//returns id instead of plant code
		public function ua_plant_rid($dbname,$uname){
		$adata = array();
		$str = "select myuaplant,pl.`recid` from {$this->db_erp}.`myua_plant` ua 
        JOIN 
               {$this->db_erp}.`mst_plant` pl 
               ON
               (ua.`myuaplant`=pl.`plnt_code`)
		where myusername ='$uname' AND ISACTIVE='Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['recid'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
    		

	public function get_user_role_branch($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_brnch($uid);//je
    	$actual_cnt = $this->get_Active_brnch_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_branch_all[]" name ="mbtn_rolsel_branch_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_branch_all[]" name ="mbtn_rolsel_branch_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,BRNCH_CODE,BRNCH_NAME FROM {$this->db_erp}.`mst_companyBranch` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$brnch_code = $rww['recid'];
    			$brnch_name = $rww['BRNCH_CODE'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuabranch,myusername,ISACTIVE FROM {$this->db_erp}.`myua_branch` WHERE myuabranch='$brnch_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['BRNCH_NAME'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_branch" name ="mbtn_rolsel_branch" value ="'.$uid.",".$brnch_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_branch" name ="mbtn_rolsel_branch" value ="'.$uid.",".$brnch_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_rolsel_branch" name ="mbtn_rolsel_branch" value ="'.$uid.",".$brnch_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }	
    
    	// get the users of the plant
		public function ua_branch_users($dbname,$uname){
		$adata = array();
		$str = "SELECT
		`myusername`
		FROM `ap2`.`myua_branch` aa  WHERE myuabranch IN (SELECT
		`myuabranch`

		FROM `ap2`.`myua_branch` WHERE  myusername='{$uname}' AND `ISACTIVE`='Y') AND  aa.`ISACTIVE`='Y'  ";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['myusername'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}

		public function ua_comp_code($dbname,$uname){
		$adata = array();
		$str = "select myuacomp,bb.COMP_CODE from {$this->db_erp}.`myua_company` aa
        JOIN {$this->db_erp}.mst_company bb ON(aa.myuacomp=bb.recid)
		 where myusername ='$uname' AND ISACTIVE='Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['COMP_CODE'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_Active_menus($dbname,$cuser,$field='',$tblname='') { 
		
		$adata = '';
		$str = "select recid from {$dbname}.`$tblname` WHERE myusername='$cuser' AND ISACTIVE='Y' AND $field";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$adata=$q->getNumRows();
		/*if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['__mdata'];
			endforeach;
		}
		$q->freeResult();*/
		return $adata;
	}
	//ITEM MASTER
	public function get_user_role_company_itm($uid){//itemcode

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_comp_itm($uid);//je
    	$actual_cnt = $this->get_Active_comp_itm_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_comp_itm_all[]" name ="mbtn_rolsel_comp_itm_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_comp_itm_all[]" name ="mbtn_rolsel_comp_itm_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,COMP_CODE,COMP_NAME FROM {$this->db_erp}.`mst_company` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$comp_code = $rww['recid'];
    			$comp_name = $rww['COMP_CODE'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuacomp_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_company_itm` WHERE myuacomp_id='$comp_code' AND myuaitm_id ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['COMP_CODE'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_comp_itm" name ="mbtn_rolsel_comp_itm" value ="'.$uid.",".$comp_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_comp_itm" name ="mbtn_rolsel_comp_itm" value ="'.$uid.",".$comp_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_rolsel_comp_itm" name ="mbtn_rolsel_comp_itm" value ="'.$uid.",".$comp_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }//endfunc
    public function get_user_role_supp($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_supp($uid);//je
    	$actual_cnt = $this->get_Active_supp_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_supp_all[]" name ="mbtn_rolsel_supp_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_supp_all[]" name ="mbtn_rolsel_supp_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,VEND_CODE,VEND_NAME FROM {$this->db_erp}.`mst_vendor` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$brnch_code = $rww['recid'];
    			$brnch_name = $rww['VEND_CODE'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuasupp_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_supp` WHERE myuasupp_id='$brnch_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['VEND_NAME'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_supp" name ="mbtn_rolsel_supp" value ="'.$uid.",".$brnch_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_supp" name ="mbtn_rolsel_supp" value ="'.$uid.",".$brnch_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_rolsel_supp" name ="mbtn_rolsel_supp" value ="'.$uid.",".$brnch_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }
    ////////////////////////////////////////////////////SMENUS?/////////////////////////
	public function get_ua_whse_2($dbname,$uname){
		$adata = array();
		$str = "select myuawhse,wh.`recid`,concat(wh.`recid` ,'xOx',trim(myuawhse)) _mdata from {$this->db_erp}.`myua_whse` ua
		        JOIN 
               {$this->db_erp}.`mst_wshe` wh 
               ON
               (ua.`myuawhse`=wh.`wshe_code`)
               where 
               myusername ='$uname' AND ISACTIVE='Y' order by ua.`myuawhse` ";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['_mdata'];

			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_ua_plnt_2($dbname,$uname){
		$adata = array();
		$str = "select myuaplant,pl.recid,concat(pl.`recid` ,'xOx',trim(myuaplant)) _mdata  from {$this->db_erp}.`myua_plant` ua
		JOIN
		{$this->db_erp}.`mst_plant` pl 
         ON
           (ua.`myuaplant`=pl.`plnt_code`)

		where myusername ='$uname' AND ISACTIVE='Y' order by pl.`recid` ";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['_mdata'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}
	public function get_cat1_vw($table,$code,$desc,$chkallId,$clasname){

    $cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$uid = '';
    	$datas = $this->get_Active_whse($uid);
    	$actual_cnt = $this->get_Active_whse_sys($uid);
    	$isallchck = '';
    	$chtml .='';
    

    	$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
    		<input id="'.$chkallId.'" name ="mbtn_rolsel_whse_all" style="transform:scale(1.5)"value ="all"  type="checkbox">
    		</div></div>
    		<hr>
    		';

    	$str = "SELECT {$code},{$desc} FROM {$this->db_erp}.`{$table}`";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->result();
    		foreach ($res as $rww):
    			$M_code = $rww->$code;
    			$M_name = $rww->$desc;
   
    			if(!empty($M_code) || $M_code != null){
    			$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" >
    			 <a><strong>'.$M_code.'</strong></a> 
    			</div>
    			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
    				<input class ="checklist '.$clasname.'" name ="mbtn_rolsel_whse" style="transform:scale(1.5)" value ="'.$M_code.'"type="checkbox">
    			</div></div>
    			<hr>
    			';
    			}
    			
    		endforeach;
    	}
    	$chtml .='';
    	echo $chtml;

    }

    	public function ua_brnch_filter($dbname,$uname,$col){
		$adata = array();
		$str = "SELECT REPLACE(GROUP_CONCAT($col , '= ',myuabranch),',', ' OR ') mdata from {$this->db_erp}.`myua_branch` where myusername ='$uname' AND ISACTIVE='Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		//var_dump($str);
		if($q->getNumRows() > 0){ 
			$qrw   = $q->row_array();
			$adata = 'AND('. $qrw['mdata'] . ')';

		}
		else{
			$adata = '';
		}
		$q->freeResult();
		return $adata;
	}

	  public function get_user_role_ho($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_ho($uid);//je
    	$actual_cnt = $this->get_Active_ho_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_ho_all[]" name ="mbtn_ho_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_ho_all[]" name ="mbtn_ho_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,ho_name FROM {$this->db_erp}.`menu_ho` a ORDER BY ho_name";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$menus_code = $rww['recid'];
    			$menus_name = $rww['ho_name'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuaho_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_ho` WHERE myuaho_id='$menus_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['ho_name'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_ho" name ="mbtn_ho" value ="'.$uid.",".$menus_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_ho" name ="mbtn_ho" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_ho" name ="mbtn_ho" value ="'.$uid.",".$menus_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }

    //costcenter 
    public function get_user_role_cost($uid){

    	$cuser = $this->mylibzdb->mysys_user();//181
    	$chtml ="";
    	$datas = $this->get_Active_cost($uid);//je
    	$actual_cnt = $this->get_Active_cost_sys($uid);
    	
    	$chtml .='';
    	if($actual_cnt == $datas){
    		$chtml .='
    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_oust_all[]" name ="mbtn_rolsel_cost_all[]" value ="'.$uid.'"  type="checkbox" checked>
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}else{
    		$chtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
            <a><strong>ALL</strong></a> 
       		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    		<label class="switch2">
    		<input id="mbtn_rolsel_cost_all[]" name ="mbtn_rolsel_cost_all[]" value ="'.$uid.'"  type="checkbox">
    		<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    		</label>
    		</div></div>
    		';
    	}
    	//$chtml='<div class="custom-padd">';
    	$str = "SELECT recid,COSTCNTR_CODE,COSTCNTR_DESC FROM {$this->db_erp}.`mst_costcnters` a";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

    	if($q->getNumRows()>0){
    		$res =$q->getResultArray();
    		foreach ($res as $rww):
    			$brnch_code = $rww['recid'];
    			$brnch_name = $rww['COSTCNTR_CODE'];
    			//$whse_name = $rww['wshe_name'];
    			$str = "SELECT myuacost_id,myusername,ISACTIVE FROM {$this->db_erp}.`myua_cost` WHERE myuacost_id='$brnch_code' AND myusername ='$uid'";
    			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    			$chtml .='
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row">
    			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
    			 <a><strong>'.$rww['COSTCNTR_DESC'].'</strong></a> 
    			</div>
    			';
    			if($q->getNumRows()>0){
    				$res =$q->getResultArray();
    				foreach ($res as $rw) {
    					$isactive =$rw["ISACTIVE"];
    					if($isactive==='Y'){
    						$chtml .='
    						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_cost" name ="mbtn_rolsel_cost" value ="'.$uid.",".$brnch_code.'"  type="checkbox" checked>
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';
    					}
    					else{
    						$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    						<label class="switch2">
    						<input id="mbtn_rolsel_cost" name ="mbtn_rolsel_cost" value ="'.$uid.",".$brnch_code.'"  type="checkbox">
    						<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    						</label></div></div>
    						';		
    					}
    				}
    			}
    			else{
    				$chtml .='<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    				<label class="switch2">
    				<input id="mbtn_rolsel_cost" name ="mbtn_rolsel_cost" value ="'.$uid.",".$brnch_code.'"  type="checkbox">
    				<span class="slider2 round"><i class="fa fa-check text-white togg" aria-hidden="true"></i>&nbsp;<i class="fa fa-times text-danger togg" aria-hidden="true"></i></span>
    				</label></div></div>

    				';	
    			}
    			
    		endforeach;
    	}
    	//$chtml .='</div>';
    	echo $chtml;

    }

    	//----------------CUST-----------------------//
    	public function get_Active_cost_all($uid){
        	$adata = array();
    		$str = "SELECT recid,COSTCNTR_CODE FROM {$this->db_erp}.`mst_costcnters`";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    		if($q->getNumRows() > 0) { 
    			$qrw = $q->getResultArray();
    			foreach($qrw as $all): 
    				$adata[] = $all['recid'].",".$uid.",";
    			endforeach;
    		}
    		$q->freeResult();
    		return $adata;
    	}
    	public function get_Active_cost($uid){
    		$adata = "";//array();
    		$str = "SELECT myuacost_id,count(myusername) COUNT_M FROM {$this->db_erp}.`myua_cost` WHERE myuacost_id = '$uid' AND ISACTIVE = 'Y'";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    		if($q->getNumRows() > 0) { 
    			$qrw = $q->getResultArray();
    			foreach($qrw as $rw): 
    				$adata .= $rw['COUNT_M'];
    			endforeach;
    		}
    		$q->freeResult();
    		return $adata;
    	}
    	public function get_Active_cost_sys($uid){
    		$adata = "";//array();
    		$str = "SELECT recid,count(COSTCNTR_CODE) COUNT_M FROM {$this->db_erp}.`mst_costcnters`";
            $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    		if($q->getNumRows() > 0) { 
    			$qrw = $q->getResultArray();
    			foreach($qrw as $rw): 
    				$adata .= $rw['COUNT_M'];
    			endforeach;
    		}
    		$q->freeResult();
    		return $adata;
    	}
    	//////////////////////////END FOR  //costcenter /////////////////////
    	//////////////////////////VIEW////////////////////////////

    		// jhick -- 20220217
	public function ua_cost($dbname,$uname){
		$adata = array();
		// $str = "select myuacust_id from {$this->db_erp}.`myua_cust` where myusername ='$uname' AND ISACTIVE='Y'";
		$str = "SELECT bb.`myuacost_id` FROM {$this->db_erp}.`mst_costcnters` AS aa LEFT JOIN `myua_cost` AS bb ON aa.`recid` = bb.`myuacost_id` WHERE bb.`myusername` = '{$uname}' AND bb.`ISACTIVE` = 'Y'";
		//$q = $this->db->query($str);
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['myuacost_id'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	}



		public function ua_plant_id($dbname,$uname){
			$adata = array();
			//$str = "select myuaplant from {$this->db_erp}.`myua_plant` where myusername ='$uname' AND ISACTIVE='Y'";
			
			$str = "select myuaplant,a.`recid` from {$this->db_erp}.`myua_plant` ua
			        JOIN 
	               {$this->db_erp}.`mst_plant` a 
	               ON
	               (ua.`myuaplant`=a.`plnt_code`)
	               where 
	               myusername ='$uname' AND ISACTIVE='Y'";
			//$q = $this->db->query($str);
			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			if($q->getNumRows() > 0) { 
				$qrw = $q->getResultArray();
				foreach($qrw as $rw): 
					$adata[] = $rw['recid'];
				endforeach;
			}
			$q->freeResult();
			return $adata;
		}
	    		
  
}
	
