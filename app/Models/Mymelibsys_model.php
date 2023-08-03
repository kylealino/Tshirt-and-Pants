<?php 
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Files\File;
class mymelibsys_model extends Model { 
	public function __construct()
	{
		parent::__construct();
		   $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(1);
        $this->db_temp = $this->mydbname->medb(3);
        $this->mylibzdb = model('App\Models\MyLibzDBModel');
        $this->mylibzsys = model('App\Models\MyLibzSysModel');
        $this->mydataz = model('App\Models\MyDatumModel');
        $this->dbx = $this->mylibzdb->dbx;
        $this->request = \Config\Services::request();
	}
	
public function getCompany_data($_trns_comp = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$str = "SELECT `recid`,`COMP_CODE`,`COMP_NAME` FROM {$this->db_erp}.`mst_company` WHERE (`COMP_NAME` = '{$_trns_comp}')";
	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
	if($q->getNumRows() > 0):
		return $q->getRowArray();
	else:
		echo "<div class=\"alert alert-danger\"><strong>Info.<br/></strong><strong>Error:</strong> Invalid Company [NOT FOUND]!!!</div>";
		die();
	endif;
	$q->freeResult();
}

public function getCompanyBranch_data($compRID = '',$_trns_brnch = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$str = "SELECT `recid`, `BRNCH_CODE`,`BRNCH_NAME`,`BRNCH_CODEX`,`BRNCH_OCODE3`,`BRNCH_GROUP` FROM {$this->db_erp}.`mst_companyBranch` WHERE `COMP_ID` = '{$compRID}' and `BRNCH_NAME` = '{$_trns_brnch}'";
	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);

	if($q->getNumRows() > 0):
		return $q->getRowArray();
	else:
		echo "<div class=\"alert alert-danger\"><strong>Info.<br/></strong><strong>Error:</strong> Invalid Branch [NOT FOUND]!!!</div>";
			die();
	endif; 
	$q->freeResult();

}

public function getVendor_data($_trns_vend = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$str = "SELECT `recid`,`VEND_CODE`,`VEND_NAME` FROM {$this->db_erp}.`mst_vendor` WHERE (`VEND_NAME` = '{$_trns_vend}')";
	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
	if($q->getNumRows() > 0):
		return $q->getRowArray();
	else:
		echo "<div class=\"alert alert-danger\"><strong>Info.<br/></strong><strong>Error:</strong> Invalid Vendor [NOT FOUND]!!!</div>";
		die();
	endif;
	$q->freeResult();
}

//file type imagee and pdf only
public function file_type_check($file_types,$file_count){
	 $allowed_mime_type_arr = array('application/pdf','image/gif','image/jpeg','image/pjpeg','image/png','image/x-png');
	 
	for( $j = 0; $j < $file_count; $j++ )
	   { 
	   	$file_type = $file_types[$j];
   		if(!in_array($file_type, $allowed_mime_type_arr)){
              	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Please select only <strong>pdf/gif/jpg/png </strong> file.</div>";
            
                die();
            }
	
	}
}



public function mychecklist($mearray){

	if(!empty($mearray)):
		foreach ($mearray as $key){
			$ddata = explode("xOx",$key);
		echo "<div class=\"row form-group\">
				<div class=\"col-lg-1 col-sm-12\">
					<input type=\"checkbox\" name = \"reasons\"id =\"_trns_chckbox$ddata[0]\" value=\"$ddata[1]\" style=\"transform:scale(1.5)\" />
				</div>
				<div class=\"col-lg-9 col-sm-12 \" >
					$ddata[1]
				</div>
			</div>";
			
		}
	endif;

}


 public function resizeImage($filename,$source_path){
      $config_manip = array(
           'image_library' => 'ImageMagick',
          'library_path' => '/usr/bin',
          'source_image' => $source_path,
          'maintain_ratio' => TRUE,
         'quality' => '60%' //reduce quality by 40%
   
      );


      $this->load->library('image_lib');
      $this->image_lib->initialize($config_manip);
      if (!$this->image_lib->resize()) {
          echo $this->image_lib->display_errors();
      }


      $this->image_lib->clear();
   }

// check the files uploaded if it is in image format
 public function file_type_image($file_types,$file_count){
 	$allowed_mime_type_arr = array('image/gif','image/jpeg','image/pjpeg','image/png','image/x-png');
 	for( $j = 0; $j < $file_count; $j++ )
	   { 
	   	$file_type = $file_types[$j];
   		if(!in_array($file_type, $allowed_mime_type_arr)){
              	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Error</strong> Please select only <strong>gif/jpg/png </strong> file.</div>";
            
                die();
            }
	
	}
}

public function melastsubstring($delimiter = "",$string = ""){
	if(!empty($string)):
		$last_index_of_i = strripos($string, $delimiter);
		if($last_index_of_i == 0):
		   $last_index_of_i = strlen($string);
		endif;
		return substr($string,0,$last_index_of_i);
	endif;
}
public function mefirtsubstring($delimiter = "",$string = ""){
	if(!empty($string)):
		$first_index_of_i = stripos($string, $delimiter );

		if($first_index_of_i):
			$first_index_of_i += strlen($delimiter);
		else:
			$first_index_of_i = 0;
		endif;
		
		return  substr($string,$first_index_of_i);//$first_index_of_i;
	endif;
}

public function meslicestring($stringcount = "",$string = ""){
	if(!empty($string)):
		$len = strlen($string);
		return substr($string,$len-$stringcount);
	endif;
}

//arn03182021
public function getPaymentmethodbyID($id){
	$str = "select `AP_PYMTD_DESC` __mdata from {$this->db_erp}.`mst_AP_pymtd` WHERE recid = '{$id}' order by `AP_PYMTD_DESC`";
	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	if($q->getNumRows() > 0) { 
			$rw    = $q->getRowArray();
			$adata = $rw['__mdata'];
		}
	$q->freeResult();
	return $adata;
}

// ADD DATE SHOULD BE 





public function getDateInterval($d1,$d2){
	$d1       = new DateTime($d1);
	$d2       = new DateTime($d2);
	$interval = $d2->diff($d1);
	return $interval->format('%a');

	}
	// ADD DATE SHOULD BE END

public function rest_passto_default(){
	$cuser       = $this->mylibzdb->mysys_user();
	$mpw_tkn     = $this->mylibz->mpw_tkn();
	$mtkn_userID = $this->input->get_post('mtkn_userID');
	$new_defPass = $this->input->get_post('new_defPass');

	$str = "SELECT myusername,myuserpass FROM  {$this->db_erp}.`myusers` where sha2(concat(recid,'{$mpw_tkn}'),384) = '{$mtkn_userID}'";
	$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	if($qry->getNumRows() > 0){
	    $row = $qry->getRowArray();
		$username = $row['myusername'];
		$oldPass  = $row['myuserpass'];

	    //backup old password
		$str = "SELECT myusername,myuserpass FROM {$this->db_erp}.`myusers_logs` where myusername = '{$username}'";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qry->getNumRows() > 0)
		{
			//update
			$str ="
			UPDATE {$this->db_erp}.`myusers_logs`
			SET`myuserpass` = '{$oldPass}'
			WHERE `myusername` = '{$username}'";
			$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		}
		else
		{
			//insert
			$str ="
			INSERT INTO {$this->db_erp}.`myusers_logs`(`myusername`,`myuserpass`,`muser`)
			VALUES('{$username}','{$oldPass}','{$cuser}')";
			$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		}
	    //update to default password
    	$str = "UPDATE {$this->db_erp}.myusers SET myuserpass =  MD5('$new_defPass') 
		where myusername = '{$username}'";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		echo "<div class=\"alert alert-success\"><strong>System Info.<br/></strong>Password successfully changed!!!</div>";
	}
	else{

		echo "<div class=\"alert alert-danger\"><strong>System Info.<br/></strong>User not found!!!</div>";
	}

}

public function rest_validity_update(){
	$cuser       = $this->mylibzdb->mysys_user();
	$mpw_tkn     = $this->mylibz->mpw_tkn();
	$mtkn_userID = $this->input->get_post('mtkn_userID');
	$new_validity = $this->mylibz->mydate_yyyymmdd($this->input->get_post('new_validity'));

	$str = "SELECT myusername,myuserpass FROM  {$this->db_erp}.`myusers` where sha2(concat(recid,'{$mpw_tkn}'),384) = '{$mtkn_userID}'";
	$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	if($qry->getNumRows() > 0){
	    $row = $qry->getRowArray();
		$username = $row['myusername'];
		$oldPass  = $row['myuserpass'];

	    //update to default password
    	$str = "UPDATE {$this->db_erp}.myusers SET myuservalie = '$new_validity' 
		where myusername = '{$username}'";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		echo "<div class=\"alert alert-success\"><strong>System Info.<br/></strong>Account validity changed successfully !!!</div>";
	}
	else{

		echo "<div class=\"alert alert-danger\"><strong>System Info.<br/></strong>User not found!</div>";
	}

}

public function restore_pass(){
	$cuser       = $this->mylibzdb->mysys_user();
	$mpw_tkn     = $this->mylibz->mpw_tkn();
	$mtkn_userID = $this->input->get_post('mtkn_userID');
	$username    = $this->input->get_post('username');

	$str = "SELECT myuserpass FROM {$this->db_erp}.`myusers_logs` where myusername = '{$username}'";
	$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
	if($qry->getNumRows() > 0)
	{
		$row     = $qry->getRowArray();
		$oldPass = $row['myuserpass'];

		//update
		$str ="
		UPDATE {$this->db_erp}.`myusers`
		SET`myuserpass` = '{$oldPass}'
		WHERE `myusername` = '{$username}' 
		AND sha2(concat(recid,'{$mpw_tkn}'),384) = '{$mtkn_userID}'";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		echo "<div class=\"alert alert-success col\"><strong>System Info.<br/></strong>Password successfully restored!!!</div>";
	}
	else
	{

		echo "<div class=\"alert alert-danger col\"><strong>System Info.<br/></strong>Records not found for this user!</div>";
	}

}

public function getCompanyBranch_data_byname($_trns_brnch = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$str = "SELECT `recid`, `BRNCH_CODE`,`BRNCH_NAME`,`BRNCH_CODEX`,`BRNCH_OCODE3`,`BRNCH_GROUP`,CONCAT('mst_ssl',LCASE(`BRNCH_CODE`)) `ssl_tablename`  FROM {$this->db_erp}.`mst_companyBranch` WHERE  `BRNCH_NAME` = '{$_trns_brnch}'";
	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
		
	if($q->getNumRows() > 0):
		return $q->getRowArray();
	else:
		echo "<div class=\"alert alert-danger\"><strong>Info.<br/></strong><strong>Error:</strong> Invalid Branch [NOT FOUND]!!!</div>";
			die();
	endif; 
	$q->freeResult();

}

public function getCompanyBranch_data_bytkn($mtkn_rid = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$mpw_tkn = $this->mylibz->mpw_tkn();
	$str = "SELECT `recid`, `BRNCH_CODE`,`BRNCH_NAME`,`BRNCH_CODEX`,`BRNCH_OCODE3`,`BRNCH_GROUP`,CONCAT('mst_ssl',LCASE(`BRNCH_CODE`)) `ssl_tablename`  
	FROM {$this->db_erp}.`mst_companyBranch` WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) ='{$mtkn_rid}'";
	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
		//var_dump($str);
	if($q->getNumRows() > 0):
		return $q->getRowArray();
	else:
		echo "<div class=\"alert alert-danger\"><strong>Info.<br/></strong><strong>Error:</strong> Invalid Branch [NOT FOUND]!!!</div>";
			die();
	endif; 
	$q->freeResult();

}



public function getTablename($value,$type = 1){
	$cuser       = $this->mylibzdb->mysys_user();
	//$type = 1 Branch ID
	//$type = 2 Branch Name
	$data = "";
	$str_filter = '';
	switch ($type){
		case 1:
		$str_filter = "WHERE branchID = '{$value}' ";
			break;
		default:
		$str_filter = "WHERE branchName = '{$value}' ";
			break;
	}

	$str = "SELECT `tableName`,`tableInv` FROM {$this->db_erp}.`mst_branch_tablename` {$str_filter} ";
	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);

	if($q->getNumRows() > 0):
		$row = $q->getRowArray();
		$data = $row;
	endif; 
	return $data;
	$q->freeResult();

}




public function get_new_file_path($filepath){

	$mpathdn = _XMYAPP_PATH_;
	$filePath = $mpathdn.$filepath;
	$finalPath = site_url();

	if(strpos(site_url(), 'mynlinks') !== false){
	//check file exist 
		if(!file_exists($filePath)){
			$finalPath = str_replace('mynlinks', 'myn8v8links', site_url());
		}
	}
	else{
		if(!file_exists($filePath)){
			$finalPath = str_replace('myn8v8links', 'mynlinks', site_url());
		}
	}
			

return $finalPath;

} 


public function clean_skandir($dir){
return array_values (array_diff(scandir ($dir),array("..', '.")));
}

public function pre_r($array){
	echo "<pre>";
	print_r($array);
	echo "</pre>";


}


public function getTable($strName="",$dbname = "ap2_branch"){
	//strName  = 'salesout_mo'
	//strName  = 'lb_dtld'
	if(!empty($dbname)){
	$cuser = $this->mylibzdb->mysys_user();
	$data  = '';
	$str = "SELECT CONCAT ('trx_E',cb.`brnch_ocode2`) tbl  FROM {$this->db_erp}.`myua_branch` ua
			JOIN {$this->db_erp}.`mst_companyBranch` cb ON  ua.`myuabranch` = cb.`recid`
			WHERE myusername ='{$cuser}' AND ISACTIVE ='Y' GROUP BY ua.`myuabranch` 
			ORDER BY cb.`BRNCH_NAME` LIMIT 1";

	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);

	if($q->getNumRows() > 0):
		$row = $q->getRowArray();
		$data = $row['tbl']."_".$strName;

		
		//check table if exist
		$strqry ="SELECT table_name
		FROM information_schema.`tables` 
		WHERE table_schema = '{$dbname}'
		AND table_name = '{$data}'";

		$q2   = $this->mylibzdb->myoa_sql_exec($strqry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
		if($q2->getNumRows() == 0):
			echo "<div class=\"alert alert-danger\"><strong>Error:</strong> Table not found!!!</div>";
			die();
		endif;
		$q2->freeResult();

	endif; 
	return $data;
	$q->freeResult();
	}

}



public  function saveFileByUrl ($source, $destination){
    if (function_exists('curl_version')) {
        $ch   = curl_init($source);
        $fp     = fopen($destination, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
    else{
        file_put_contents($destination, file_get_contents($source));
    }
}

public function meuserTkn($module = ''){
$cuser     = $this->mylibzdb->mysys_user();
$mpw_tkn   = $this->mylibz->mpw_tkn();
$cuserrema = $this->mylibzdb->mysys_userrema();
$meusertkn = '';
$prlink    = 'haha';

$str = "SELECT `myuser_prlnk`,SHA2(CONCAT('{$cuser}','melang'),384) meusern from {$this->db_erp}.`myusers` where `myusername` = '$cuser'";
$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__ . chr(13) . chr(10) . 'User:  ' . $cuser);
if($q->getNumRows() > 0){ 
	$rw = $q->getRowArray();
	$meusern = $rw['meusern'];
  
	$str = "SELECT metkn FROM {$this->db_erp}.`accz_token` WHERE `metkn_date` BETWEEN DATE_ADD(NOW(),INTERVAL -3 DAY) AND NOW() limit 1";
	$qtkn = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__ . chr(13) . chr(10) . 'User:  ' . $cuser);
	$meusetkn = '';
	if($qtkn->getNumRows() > 0){ 
		$rwtkn = $qtkn->getRowArray();
		$meusertkn = $rwtkn['metkn'];
	}
	$qtkn->freeResult();
	
	if($rw['myuser_prlnk'] != 'localhost'){ 
		$prlink = "http://{$rw['myuser_prlnk']}/mynlinks/mylinkakz?meusern={$meusern}&meusertkn={$meusertkn}&memodule={$module}";
	
	}
	$q->freeResult();


}

return $prlink;
}

public function checkMKGTag($txt_mo){

	$__mkg_tag ='N';

	$str_qry = "
			SELECT IF(`mo_type`='M','Y','N') __mkg_tag
			FROM {$this->db_erp}.`trx_manrecs_mo_hd` bbb
            WHERE bbb.`motrx_no`= '{$txt_mo}'
          ";

    //var_dump($str_qry);
    $q = $this->mylibzdb->myoa_sql_exec($str_qry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    //$q = $this->dblinks->query($str_qry);
    if($q->getNumRows()>0){
    	$rdr = $q->getRowArray();
        $__mkg_tag = $rdr['__mkg_tag'];
    }
    else{ //shipdoc ni smc
        $str_qry = "SELECT `mkg_tag` __mkg_tag
        FROM {$this->db_erp}.`trx_crpl` aaa
        WHERE aaa.`crpl_code`= '{$txt_mo}' ";
		$q = $this->mylibzdb->myoa_sql_exec($str_qry,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        //$q = $this->dblinks->query($str_qry);
        if($q->getNumRows()>0){
        	$rdr = $q->getRowArray();
            $__mkg_tag = $rdr['__mkg_tag'];
        }
    }

  $q->freeResult();
  return $__mkg_tag;

}

public function mychecklist2($mearray){

	if(!empty($mearray)):
		foreach ($mearray as $key){
			$ddata = explode("xOx",$key);
		echo "<div class=\"input-group mb-3\">
				<div class=\"input-group-text\" style=\"border-radius: 0.2rem 0 0 0.2rem\">
					<input type=\"checkbox\" name = \"reasons\"id =\"_trns_chckbox$ddata[0]\" value=\"$ddata[1]\"/>
				</div>
				<input class=\"form-control form-control-sm\" type=\"text\" value=\"$ddata[1]\">
			</div>";
			
		}
	endif;


}

//get plant and warehouse using tkn
public function getPlantWarehouse_data_bytkn($mtkn_rid = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$mpw_tkn = $this->mylibzdb->mpw_tkn();
	$str = "SELECT `recid` whID,plnt_id 
	FROM {$this->db_erp}.`mst_wshe` WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) ='{$mtkn_rid}'";
	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
		//var_dump($str);
	if($q->getNumRows() > 0):
		return $q->getRowArray();
	else:
		echo "<div class=\"alert alert-danger\"><strong>Info.<br/></strong><strong>Error:</strong> Invalid Warehouse [NOT FOUND]!!!</div>";
			die();
	endif; 
	$q->freeResult();

}

//crossdocking only
public function getCDPlantWarehouse_data_bytkn($mtkn_rid = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$mpw_tkn = $this->mylibzdb->mpw_tkn();
	$str = "SELECT `recid` whID,`plnt_id` plntID,`wshe_code`  
	FROM {$this->db_erp}.`mst_wshe` WHERE sha2(concat(`recid`,'{$mpw_tkn}'),384) ='{$mtkn_rid}' AND `is_crossdocking` = 'Y' ";
	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);

	if($q->getNumRows() > 0):
		return $q->getRowArray();
	else:
		echo "<div class=\"alert bg-pdanger mt-2 text-center fw-bold\"><i class=\"bi bi-exclamation-circle-fill\"></i> Invalid Warehouse [NOT FOUND]!</div>";
			die();
	endif; 
	$q->freeResult();

}

public function getCDActivePlantWarehouse($whseID = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$mpw_tkn = $this->mylibzdb->mpw_tkn();

	$str_filter = "";
	if(!empty($whseID)){
		$str_filter = "AND aa.`recid` = '{$whseID}'";
	}

	$str = "SELECT
	SHA2(CONCAT(aa.`recid`,'{$mpw_tkn}'),384) mtkn_whse,
	TRIM(aa.`wshe_code`) wshe_code,
	SHA2(CONCAT(aa.`plnt_id`,'{$mpw_tkn}'),384) mtkn_plant,
	TRIM(pl.`plnt_code`) plant_code
	FROM {$this->db_erp}.`mst_wshe` aa
	JOIN {$this->db_erp}.`myua_whse` bb ON(aa.`wshe_code`= bb.`myuawhse`)
	JOIN {$this->db_erp}.`mst_plant` pl ON(aa.`plnt_id`= pl.`recid`)
	WHERE bb.`myusername`='{$cuser}' 
	AND bb.`ISACTIVE`='Y' 
	{$str_filter}
	AND aa.`is_crossdocking` = 'Y'  LIMIT 1";

	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
		//var_dump($str);
	if($q->getNumRows() > 0):
		return $q->getRowArray();
	else:
		echo "<div class=\"alert bg-pdanger mt-2 text-center fw-bold\"><i class=\"bi bi-exclamation-circle-fill\"></i> Please assign plant and warehouse!</div>";
		die();
	endif; 
	$q->freeResult();

}

public function getBranch($brnch_id = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$mpw_tkn = $this->mylibzdb->mpw_tkn();

	$str_filter = "";
	if(!empty($brnch_id)){
		$str_filter = "AND aa.`recid` = '{$brnch_id}'";
	}

	$str = "SELECT
	SHA2(CONCAT(aa.`recid`,'{$mpw_tkn}'),384) mtkn_brnch,
=
	FROM {$this->db_erp}.`mst_companyBranch` aa
	WHERE {$str_filter}";

	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
		//var_dump($str);

	return $q->getRowArray();

	$q->freeResult();

}

public function getUserWHAccess($po_alias = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$str_access = "";
	$str ="SELECT  CONCAT('AND (',GROUP_CONCAT(' {$po_alias} =  ',wh.`recid` SEPARATOR ' OR'),')') cdwh_access 
			FROM {$this->db_erp}.`myua_whse`  ua
			JOIN {$this->db_erp}.`mst_wshe`  wh ON ua.`myuawhse` = wh.`wshe_code`  AND wh.`is_crossdocking` ='Y'
			WHERE ua.`myusername` = '{$cuser}' AND ua.`ISACTIVE` = 'Y' ";

	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
	if($q->getNumRows() > 0):
		$row = $q->getRowArray();
		$str_access = $row['cdwh_access'];
	endif;
	$q->freeResult();
	return $str_access;

}


public function getWhGroupByname($name = '',$whseID = '',$plantID = ''){
	$cuser   = $this->mylibzdb->mysys_user();
	$str_wshe = (!empty($whseID))?"AND `wshe_id` = '{$whseID}'":"";
	$str_plant = (!empty($plantID))?"AND `plnt_id` = '{$plantID}'":"";
	$str ="  SELECT `recid` FROM {$this->db_erp}.`mst_wshe_grp` WHERE `wshe_grp` = '{$name}' {$str_wshe} {$str_plant} ";

	$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
	if($q->getNumRows() > 0):
		return $q->getRowArray();
	else:
		 echo "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Invalid warehouse rack.</div>";
          die();
	endif;
	$q->freeResult();	

}


	public function getWhGrpDetailsByTkn($tkn_warehousegrp_id ='',$showarn = ''){
		$mpw_tkn  = $this->mylibzdb->mpw_tkn();

		$str_wrhse = " SELECT recid,wshe_id,wshe_grp,plnt_id FROM {$this->db_erp}.`mst_wshe_grp` where sha2(concat(`recid`,'{$mpw_tkn}'),384) = '{$tkn_warehousegrp_id}' ";
		$qry = $this->mylibzdb->myoa_sql_exec($str_wrhse,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qry->getNumRows()){
		
			return $qry->getRowArray();
		}
		else{
			if($showarn == 'Y'){
			echo "<div class=\"text-center text-uppercase\"><strong>You do not have access to the warehouse</strong><div>";
			}
		}
		$qry->freeResult();
	}




	public function getWhSbinByname($name = '',$grp = '',$whseID = '',$plantID = ''){
		$cuser   = $this->mylibzdb->mysys_user();
		$str_grp = (!empty($grp))?"AND `wshegrp_id` = '{$grp}'":"";
		$str_wshe = (!empty($whseID))?"AND `wshe_id` = '{$whseID}'":"";
		$str_plant = (!empty($plantID))?"AND `plnt_id` = '{$plantID}'":"";
		$str ="  SELECT `recid` FROM {$this->db_erp}.`mst_wshe_bin` WHERE `wshe_bin_name` = '{$name}' {$str_grp} {$str_wshe} {$str_plant} ";

		$q   = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__. chr(13) . chr(10) . 'User: ' . $cuser);
		if($q->getNumRows() > 0):
			return $q->getRowArray();
		else:
			 echo "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Invalid warehouse bin.</div>";
	          die();
		endif;
		$q->freeResult();	

	}



		public function getWhBinDetailsByTkn($tkn_bingrp_id ='',$showarn = ''){
		$mpw_tkn  = $this->mylibzdb->mpw_tkn();

		$str_wrhse = " SELECT recid,wshe_bin_name,wshe_id,wshegrp_id,plnt_id FROM {$this->db_erp}.`mst_wshe_bin` where sha2(concat(`recid`,'{$mpw_tkn}'),384) = '{$tkn_bingrp_id}' ";
		$qry = $this->mylibzdb->myoa_sql_exec($str_wrhse,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qry->getNumRows()){
		
			return $qry->getRowArray();
		}
		else{
			if($showarn == 'Y'){
			echo "<div class=\"text-center text-uppercase\"><strong>Invalid Bin.</strong><div>";
			die();
			}

		}
		$qry->freeResult();
	}

	public function checkDaterange($from_date, $to_date){
		
		$datediff = strtotime($to_date) - strtotime($from_date);
		$days = round($datediff / (60 * 60 * 24));

		if($days < 0){

		     echo  "<div class=\"alert alert-danger\"><strong>ERROR</strong><br>Invalid date range.</div>";
		     die();
		   
		}

	}

	public function upd_logs_pullout_gr($dbname,$cmoduletag='',$pullouttrx='',$grtrx='',$ptyp='',$field_upd='',$sstr='') { 
		$cuser = $this->mylibzdb->mysys_user();
		$pullouttrx = $this->dbx->escapeString($pullouttrx);
		$grtrx = $this->dbx->escapeString($grtrx);
		$cmoduletag = $this->dbx->escapeString($cmoduletag);
		$str = "select now() __xcurdatetime";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rw = $q->getRowArray();
		$xcurdatetime = $rw['__xcurdatetime'];
		$q->freeResult();
		$sstr = $this->dbx->escapeString($sstr);
		//$cka = substr($cka,0,60);
		//$ckmb = substr($ckmb,0,60);
		
		$str = "
		insert into {$dbname}.trx_pullout_ulogs (
		`LOG_USER`,
		`LOG_MODULE`,
		`LOG_PULLOUTTRX`,
		`LOG_GRTRX`,
		`LOG_PTYPE`,
		`LOG_FLD_UPD`,
		`LOG_KA_REMK`,
		`LOG_IPADDR`
		) 
		values(
		'$cuser',
		'{$cmoduletag}',
		'$pullouttrx',
		'$grtrx',
		'$ptyp',
		'$field_upd',
		'$sstr',
		'" . $this->mylibzdb->get_ip_address() . "') 
		";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		return array($cmoduletag,$xcurdatetime);
	}


	public function upd_logs_tpd_pullout_gr($dbname,$cmoduletag='',$pullouttrx='',$grtrx='',$ptyp='',$sstr='') { 
		$cuser = $this->mylibzdb->mysys_user();
		$pullouttrx = $this->dbx->escapeString($pullouttrx);
		$grtrx = $this->dbx->escapeString($grtrx);
		$cmoduletag = $this->dbx->escapeString($cmoduletag);
		$str = "select now() __xcurdatetime";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rw = $q->getRowArray();
		$xcurdatetime = $rw['__xcurdatetime'];
		$q->freeResult();
		$sstr = $this->dbx->escapeString($sstr);
		//$cka = substr($cka,0,60);
		//$ckmb = substr($ckmb,0,60);
		
		$str = "
		insert into {$dbname}.trx_tpd_ulogs (
		`LOG_USER`,
		`LOG_MODULE`,
		`LOG_PULLOUTTRX`,
		`LOG_GRTRX`,
		`LOG_PTYPE`,
		`LOG_KA_REMK`,
		`LOG_IPADDR`
		) 
		values(
		'$cuser',
		'{$cmoduletag}',
		'$pullouttrx',
		'$grtrx',
		'$ptyp',
		'$sstr',
		'" . $this->mylibzdb->get_ip_address() . "') 
		";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		return array($cmoduletag,$xcurdatetime);
	}


	public function warning_msg($border_left,$text_color,$msg){

		echo "<div class=\"d-flex align-items-center border bg-white rounded mt-2 p-2 mb-2 shadow-sm\" style=\" border-left: 6px solid $border_left !important\">
			<div class=\"flex-shrink-1 px-1\">
				 <span class=\"bi bi-exclamation-circle $text_color fs-4\"></span>
			</div>
			<div class=\"flex-grow-1 ms-3\">
				<div class=\"fw-bold\"> Info</div>
				<div>$msg</div>
			</div>
		</div>";
	}

	public function searchFilter($arr,$search=''){
		$str = " ";
		if(count($arr) > 0){
			$i = 0;

			$str = " AND ( ";
			foreach($arr as $item){
				$str.= ($i === 0 )?" {$item} LIKE '%{$search}%' " : "OR {$item} LIKE '%{$search}%' ";
				$i++;
			}
			$str .= " ) ";
		}

		return $str;
	}

	public function getEachDate($from = "",$to ="") {

	$str ="SELECT DATE(selected_date) as 's_date' FROM 
	(SELECT ADDDATE('2020-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date FROM
	(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
	(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
	(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
	(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
	(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
	WHERE selected_date BETWEEN '{$from}' AND '{$to}'";
	$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);


	if($q->getNumRows() == 0 ){
		$this->warning_msg("#dc3545","text-danger","Invalid date format");
		die();
		
	}
	return $q->getResultArray();

}

}
