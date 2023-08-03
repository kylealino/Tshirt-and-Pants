<?php
namespace App\Models;
use CodeIgniter\Model;

class MyDatumModel extends Model
{
	
    public function __construct()
    {
        parent::__construct();
        //$this->db = \Config\Database::connect();
        // OR $this->db = db_connect();
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(1);
        $this->mylibzdb = model('App\Models\mylibzdbModel');
    }
    
    public function __get_mysysdatetime() { 
    	$str = "select current_date() __mysys_currdate, current_time() __msys_currtime,
    	date_format(current_date(),'%m/%d/%Y') __mysys_currdate2,
    	date_format(now(),'%h:%i %p') __msys_currtime2,
    	MONTH(CURRENT_DATE()) __msys_curmonth,
    	YEAR(CURRENT_DATE()) __msys_curyear,
    	DAY(LAST_DAY(CURRENT_DATE())) __msys_lastday";
    	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    	$rw = $q->getRowArray();
    	$q->freeResult();
    	$adata = array();
    	$adata[] = $rw['__mysys_currdate'];
    	$adata[] = $rw['__msys_currtime'];
    	$adata[] = $rw['__mysys_currdate2'];
    	$adata[] = $rw['__msys_currtime2'];
		$adata[] = $rw['__msys_curmonth'];
		$adata[] = $rw['__msys_curyear'];
		$adata[] = $rw['__msys_lastday'];
    	return $adata;
    }
    public function getTestData() { 
		$dbname = $this->mydbname->medb(1);
		$str = "select * from {$dbname}.mst_article limit 100";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		return $q;
	} //end getTestData
    
	public function lk_Active_DF() { 
		$cuserrema=$this->mylibzdb->mysys_userrema();
		$adata=array();
		if($cuserrema ==='B'){
			$adata[]="D" . "xOx" . "Draft";
		}
		else{
			$adata[]="D" . "xOx" . "Draft";
			$adata[]="F" . "xOx" . "Final";	
		}
		return $adata;
	} 
	public function lk_Active_Civil() { 
		
		$adata=array();
		$adata[]="S" . "xOx" . "Single";
		$adata[]="M" . "xOx" . "Married";
		$adata[]="W" . "xOx" . "Widow";
		
		return $adata;
	} 
	public function lk_Active_Gender() { 
		
		$adata=array();
		$adata[]="1" . "xOx" . "Male";
		$adata[]="2" . "xOx" . "Female";
		
		return $adata;
	}

	public function lk_Card_Tag() { 
		
		$adata=array();
		$adata[]="L" . "xOx" . "Lost";
		$adata[]="D" . "xOx" . "Damage";
		
		return $adata;
	}

	public function get_ctr($dbname,$mfld='') { 
		$str = "
		CREATE TABLE IF NOT EXISTS `{$dbname}`.`myctr` (
		  `CTR_YEAR` varchar(4) DEFAULT '0000',
		  `CTRL_NO01` varchar(15) DEFAULT '00000000',
		  `CTRL_NO02` varchar(15) DEFAULT '00000000',
		  `CTRL_NO03` varchar(15) DEFAULT '00000000',
		  `CTRL_NO04` varchar(15) DEFAULT '00000000',
		  `CTRL_NO05` varchar(15) DEFAULT '00000000',
		  `CTRL_NO06` varchar(15) DEFAULT '00000000',
		  `CTRL_NO07` varchar(15) DEFAULT '00000000',
		  `CTRL_NO08` varchar(15) DEFAULT '00000000',
		  `CTRL_NO09` varchar(15) DEFAULT '00000000',
		  `CTRL_NO10` varchar(15) DEFAULT '00000000',
		  `CTRL_NO11` varchar(15) DEFAULT '00000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";


		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$str = "select year(now()) XSYSYEAR";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$ryear = $q->getRowArray();
		$xsysyear = $ryear['XSYSYEAR'];
		
		$str = "select {$xfield} from {$dbname}.myctr WHERE CTR_YEAR = '$xsysyear' limit 1";
		$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qctr->getNumRows() == 0) {
			$xnumb = '0000000001';
			$str = "insert into {$dbname}.myctr (CTR_YEAR,{$xfield}) values('$xsysyear','$xnumb')";
			$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$str = "select {$xfield} MYFIELD from {$dbname}.myctr WHERE CTR_YEAR = '$xsysyear' limit 1";
			$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '0000000001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$str = "select ('{$xnumb}' + 1) XNUMB";
				$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,10,"0",STR_PAD_LEFT);
				$str = "update {$dbname}.myctr set {$xfield} = '{$xnumb}' WHERE CTR_YEAR = '$xsysyear'";
				$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			}
		}
		return $xsysyear . $xnumb;
	} //end getctr   

	public function get_ctr_5($dbname,$mfld='') { 
		$str = "
		CREATE TABLE if not exists {$dbname}.`myctr` (
		  `CTR_YEAR` varchar(4) DEFAULT '0000',
		  `CTRL_NO01` varchar(15) DEFAULT '00000000',
		  `CTRL_NO02` varchar(15) DEFAULT '00000000',
		  `CTRL_NO03` varchar(15) DEFAULT '00000000',
		  `CTRL_NO04` varchar(15) DEFAULT '00000000',
		  `CTRL_NO05` varchar(15) DEFAULT '00000000',
		  `CTRL_NO06` varchar(15) DEFAULT '00000000',
		  `CTRL_NO07` varchar(15) DEFAULT '00000000',
		  `CTRL_NO08` varchar(15) DEFAULT '00000000',
		  `CTRL_NO09` varchar(15) DEFAULT '00000000',
		  `CTRL_NO10` varchar(15) DEFAULT '00000000',
		  `CTRL_NO11` varchar(15) DEFAULT '00000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$str = "SELECT year(now()) XSYSYEAR";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$ryear = $q->getRowArray();
		$xsysyear = $ryear['XSYSYEAR'];
		
		$str = "SELECT {$xfield} from {$dbname}.myctr WHERE CTR_YEAR = '$xsysyear' limit 1";
		$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qctr->getNumRows() == 0) {
			$xnumb = '0001';
			$str = "insert into {$dbname}.myctr (CTR_YEAR,{$xfield}) values('$xsysyear','$xnumb')";
			$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$str = "SELECT {$xfield} MYFIELD from {$dbname}.myctr WHERE CTR_YEAR = '$xsysyear' limit 1";
			$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == ''){ 
				$xnumb = '00001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$str = "SELECT ('{$xnumb}' + 1) XNUMB";
				$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,5,"0",STR_PAD_LEFT);
				$str = "UPDATE {$dbname}.myctr set {$xfield} = '{$xnumb}' WHERE CTR_YEAR = '$xsysyear'";
				$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			}
		}
		return $xsysyear . $xnumb;
	} //end getctr    


	public function getActiveWarehouse(){

	}
	
	public function lk_Active_Store_or_Mem_np() { 
		$adata=array();
		$adata[]="D" . "xOx" . "Deliveries";	
		return $adata;		
	}

	public function get_ctr_new_dr($class,$supp,$dbname,$mfld='') { 
		$str = "
		CREATE TABLE if not exists {$dbname}.`myctr_stkcode` (
		  `CTR_YEAR` varchar(4) DEFAULT '0000',
		  `CTR_MONTH` varchar(2) DEFAULT '00',
		  `CTR_DAY` varchar(2) DEFAULT '00',
		  `CTRL_NO01` varchar(15) DEFAULT '00000000',
		  `CTRL_NO02` varchar(15) DEFAULT '00000000',
		  `CTRL_NO03` varchar(15) DEFAULT '00000000',
		  `CTRL_NO04` varchar(15) DEFAULT '00000000',
		  `CTRL_NO05` varchar(15) DEFAULT '00000000',
		  `CTRL_NO06` varchar(15) DEFAULT '00000000',
		  `CTRL_NO07` varchar(15) DEFAULT '00000000',
		  `CTRL_NO08` varchar(15) DEFAULT '00000000',
		  `CTRL_NO09` varchar(15) DEFAULT '00000000',
		  `CTRL_NO10` varchar(15) DEFAULT '00000000',
		  `CTRL_NO11` varchar(15) DEFAULT '00000000',
		  `SS_CTR` varchar(15) DEFAULT '000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`,`CTR_MONTH`,`CTR_DAY`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$str = "select date(now()) XSYSDATE";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rdate = $q->getRowArray();
		$xsysdate = $rdate['XSYSDATE'];
		$xsysdate_exp = explode('-', $xsysdate);
		$xsysyear =  $xsysdate_exp[0];
		$xsysmonth = $xsysdate_exp[1];
		$xsysday = $xsysdate_exp[2];
		
		$str = "select {$xfield} from {$dbname}.myctr_stkcode WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1";
		$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qctr->getNumRows() == 0) {
			$xnumb = '0000000001';
			$str = "insert into {$dbname}.myctr_stkcode (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')";
			$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$str = "select {$xfield} MYFIELD from {$dbname}.myctr_stkcode WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1";
			$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '0000000001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$str = "select ('{$xnumb}' + 1) XNUMB";
				$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,10,"0",STR_PAD_LEFT);
				$str = "update {$dbname}.myctr_stkcode set {$xfield} = '{$xnumb}'";
				$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			}
		}
		return  $class. substr($xsysyear, -2, 2) . $xsysmonth . $xsysday . $xnumb;//.$supp
	} //end getctr


	public function get_ctr_new($class,$supp,$dbname,$mfld='') { 
		$str = "
		CREATE TABLE if not exists {$dbname}.`myctr_stkcode` (
		  `CTR_YEAR` varchar(4) DEFAULT '0000',
		  `CTR_MONTH` varchar(2) DEFAULT '00',
		  `CTR_DAY` varchar(2) DEFAULT '00',
		  `CTRL_NO01` varchar(15) DEFAULT '00000000',
		  `CTRL_NO02` varchar(15) DEFAULT '00000000',
		  `CTRL_NO03` varchar(15) DEFAULT '00000000',
		  `CTRL_NO04` varchar(15) DEFAULT '00000000',
		  `CTRL_NO05` varchar(15) DEFAULT '00000000',
		  `CTRL_NO06` varchar(15) DEFAULT '00000000',
		  `CTRL_NO07` varchar(15) DEFAULT '00000000',
		  `CTRL_NO08` varchar(15) DEFAULT '00000000',
		  `CTRL_NO09` varchar(15) DEFAULT '00000000',
		  `CTRL_NO10` varchar(15) DEFAULT '00000000',
		  `CTRL_NO11` varchar(15) DEFAULT '00000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`,`CTR_MONTH`,`CTR_DAY`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$str = "select date(now()) XSYSDATE";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rdate = $q->getRowArray();
		$xsysdate = $rdate['XSYSDATE'];
		$xsysdate_exp = explode('-', $xsysdate);
		$xsysyear =  $xsysdate_exp[0];
		$xsysmonth = $xsysdate_exp[1];
		$xsysday = $xsysdate_exp[2];
		
		$str = "select {$xfield} from {$dbname}.myctr_stkcode WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1";
		$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qctr->getNumRows() == 0) {
			$xnumb = '0000000001';
			$str = "insert into {$dbname}.myctr_stkcode (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')";
			$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$str = "select {$xfield} MYFIELD from {$dbname}.myctr_stkcode WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1";
			$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '0000000001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$str = "select ('{$xnumb}' + 1) XNUMB";
				$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,10,"0",STR_PAD_LEFT);
				$str = "update {$dbname}.myctr_stkcode set {$xfield} = '{$xnumb}'";
				$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			}
		}
		return  substr($xsysyear, -2, 2) . $xsysmonth . $xsysday .$class. $xnumb;//.$supp
	} //end getctr


	public function get_ctr_barcoding($dbname,$mfld='') { 
		$str = "
		CREATE TABLE if not exists {$dbname}.`myctr_barcoding` (
		  `CTR_YEAR` varchar(2) DEFAULT '00',
		  `CTRL_NO01` varchar(8) DEFAULT '00000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$str = "select year(now()) XSYSYEAR";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$ryear = $q->getRowArray();
		$xsysyear = substr($ryear['XSYSYEAR'], -2, 2);
		
		$str = "select {$xfield} from {$dbname}.myctr_barcoding WHERE CTR_YEAR = '$xsysyear' limit 1";
		$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qctr->getNumRows() == 0) {
			$xnumb = '01000001';
			$str = "insert into {$dbname}.myctr_barcoding (CTR_YEAR,{$xfield}) values('$xsysyear','$xnumb')";
			$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$str = "select {$xfield} MYFIELD from {$dbname}.myctr_barcoding WHERE CTR_YEAR = '$xsysyear' limit 1";
			$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '01000001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$str = "select ('{$xnumb}' + 1) XNUMB";
				$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,8,"0",STR_PAD_LEFT);
				$str = "update {$dbname}.myctr_barcoding set {$xfield} = '{$xnumb}'";
				$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			}
		}
		return $xsysyear . $xnumb;
	} //end getctr

	public function lk_Active_GRTYPE($dbname) { 
		$adata = array();
		$str = "SELECT concat(recid,'xOx',trim(`grtype_desc`)) __mdata from {$dbname}.`mst_wshe_gr_type` order by grtype_desc";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['__mdata'];
			endforeach;
		}
		$q->freeResult();
		return $adata;	
	}

	public function lk_Active_GRCLASS($dbname) { 
		$adata = array();
		$str = "select concat(recid,'xOx',trim(`PO_CLS_CODE`)) __mdata from {$dbname}.`mst_po_class` order by PO_CLS_CODE";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$qrw = $q->getResultArray();
			foreach($qrw as $rw): 
				$adata[] = $rw['__mdata'];
			endforeach;
		}
		$q->freeResult();
		return $adata;	
	}

	public function lk_Active_GI_type() { 
		$adata=array();
		$adata[]="OUTxOxOUT";	
		$adata[]="DAMAGExOxDAMAGE";	
		return $adata;		
	}

}  //end main class MyDatumModel
