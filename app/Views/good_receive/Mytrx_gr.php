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
use App\Models\Mytrx_gr_Model;
use App\Libraries\Fpdf\Mypdf;
class Mytrx_gr extends BaseController 
{ 
	
	public function __construct()
	{ 
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(1);
		$this->mywhrcdout =  new MyWarehouseoutModel();
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->mytrxgr = new Mytrx_gr_Model();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
		    // your db connection
   		$this->db = \Config\Database::connect();
	}

	public function index(){
	$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='206'","myua_trx");
		if($result == 1):
		 //echo "string";
		echo view('templates/myheader');
		echo view('good_receive/gr_main');
		echo view('templates/myfooter');
		else:
    	echo view('templates/myheader');
		echo view('errors/html/error_404',$this->data);
		echo view('templates/myfooter');
		endif;
	
	
	}	


	
	public function auto_add_lines_gr(){ 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$total_line = $this->request->getVar('total_line');
		$itemcode = $this->request->getVar('itemcode');
		$itemdesc = $this->request->getVar('itemdesc');
		$sku = $this->request->getVar('sku');
		$ucost = $this->request->getVar('ucost');
		$uprice = $this->request->getVar('uprice');
		$__rid = $this->request->getVar('__rid');
		
		for($xx = 0; $xx < $total_line; ++$xx) {
			
				$chtml = "
					<script>
						my_add_line_item('$itemcode','$itemdesc','$sku','$ucost','$uprice','$__rid');
					</script>
					

					";
				echo $chtml;
		}
	}  //end auto_add_lines

	public function gr_sv(){ 
		$trxno = $this->request->getVar('trxno_id');
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$str_tag ='';
		$fld_refno = $this->request->getVar('fld_refno');
		$fld_ptyp   = 'T';

		//ALAMIN KUNG TRADE NON TRADE
		$str = "SELECT `potrx_no`,`po_type` FROM {$this->db_erp}.`trx_manrecs_po_hd` 
		WHERE `potrx_no` = '{$fld_refno}' AND `post_tag` = 'Y'";
		$q7 = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q7->getNumRows() > 0) { 
			$rw7        = $q7->row_array();
			$fld_ptyp   = $rw7['po_type'];
		}
		//var_dump($fld_ptyp);

		if(!empty($trxno)){ 
			//EDIT ACCESS
			$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='208'","myua_trx");
			if($result != 1){
				echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong>It appears that you don't have permission to access this page.</br><strong>Note:</strong>If you think you should be able to view this page, please contact your administrator.</div>";
				die();
			}

			//WHEN TRANSACTIONS IS POSTED IT IS UNEDITABLE
			$str = "select aa.post_tag from {$this->db_erp}.`trx_wshe_gr_hd` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$trxno' and aa.post_tag ='Y'";
			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				if($q->getNumRows() > 0){
					echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong><strong>Status</strong> Transactions already posted!!!</br>Note:Posted Transactions is uneditable.</div>";
				die();
			}

		}else{
			//ADD SAVE ACCESS
			$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='207'","myua_trx");
			if($result != 1){
				echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong>It appears that you don't have permission to access this page.</br><strong>Note:</strong>If you think you should be able to view this page, please contact your administrator.</div>";
				die();
			}

		}

		if($fld_ptyp == 'T'){
			$this->mytrxgr->grsave();
		}
		else{
			$this->mytrxgr->save_nontrade();
		}

	}

	public function gr_ent_recs(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='209'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;

		$data    = $this->mytrxgr->view_recs();
		if($data['response']):
		return view('good_receive/gr_recs',$data);
		else:
		$dta['msg'] = 'No records found!';
		return view('components/no-records',$dta);
		endif;
	}

	public function gr_ent_cancel(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='217'","myua_trx");
		if($result == 0):
			echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Info.<br/></strong>It appears that you don't have permission to access this page.</br><strong>Note:</strong>If you think you should be able to view this page, please contact your administrator.</div>";
			die();
		endif;
		$this->mytrxgr->cancel_recs();
	}

	public function gr_print(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='218'","myua_trx");
		if($result == 0):
		echo view('errors/html/error_404',$this->data);
		die();
		endif;

		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('good_receive/gr_print');

	
	}

	public function grlogfile_vw(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='212'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('good_receive/gr_logfile');
	}

	public function grlogfile_recs(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='212'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		$data = $this->mytrxgr->view_grlogfile_recs();
		 return view('good_receive/gr_logfile_recs',$data);
	}

	public function gr_summary_wv(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='214'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('good_receive/gr_summary');
	}

	public function  gr_summary_dl(){
		$this->mytrxgr->gr_rpt_summ_download();
	}

	public function gr_boxbarcode_vw(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='215'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('good_receive/gr_boxbarcode');
	}

	public function gr_bcode_proc(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='215'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		$data = $this->mytrxgr->view_bcode_recs();
		return view('good_receive/gr_boxbarcode_recs',$data);
		
	}

	public function gr_bcode_dl(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='216'","myua_trx");
		if($result == 0):
			echo $this->memelibsys->warning_msg("#dc3545","text-danger","It appears that you don't have permission to access download");
			die();
		endif;
		$this->mytrxgr->download_gr_barcode();
	}

	public function gr_workflow_vw(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='210'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		return view('good_receive/gr_workflow');
	}

	public function gr_workflow_recs(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='210'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;
		$data = $this->mytrxgr->view_wf_recs();
		return view('good_receive/gr_workflow_recs',$data);
		
	}
	
	public function gr_workflow_aprvd(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='211'","myua_trx");
		if($result == 0):
			echo $this->memelibsys->warning_msg("#dc3545","text-danger","It appears that you don't have permission to post");
			die();
		endif;
		$this->mytrxgr->gr_approving();
	}

	public function gr_barcode_generation(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='219'","myua_trx");
		if($result == 0):
			echo $this->memelibsys->warning_msg("#dc3545","text-danger","It appears that you don't have permission to generate barcode.");
			die();
		endif;
		$this->mytrxgr->gr_barcde_gnrtion();
	}
	

	public function try(){
		$count = $this->db->table('warehouse_inv_rcv')->countAll();
		echo $count;
		//$this->memelibsys->upd_logs_tpd_pullout_gr('ap2','asdasd');

	}

public function auto_add_lines_pullout() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$po_type   = 'T';
		$fld_refno = $this->request->getVar('fld_refno');
		$fld_grtyp = $this->request->getVar('fld_grtyp');
		$itemdesc = '';
		$sku = '';
		$ucost = '';
		$uprice = '';
		$__rid = '';
		$TQTY= '';
		$adatar1 = array();
		$_items = '';

		if(empty($fld_refno)){
			$this->memelibsys->warning_msg("#dc3545","text-danger","GR Ref no is empty.");
				die();
		}

		//ALAMIN KUNG TRADE NON TRADE
		$str = "SELECT potrx_no,po_type FROM {$this->db_erp}.`trx_manrecs_po_hd` 
		WHERE `potrx_no` = '{$fld_refno}' AND `post_tag` = 'Y'";
		$q7 = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q7->getNumRows() > 0) { 
			$rw7        = $q7->row_array();
			$po_type   = $rw7['po_type'];
		}

		$str = " SELECT `recid` FROM {$this->db_erp}.`trx_wshe_gr_hd` WHERE `ref_no` = '{$fld_refno}'";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

		if($q->getNumRows() > 0) { 
			$this->memelibsys->warning_msg("#dc3545","text-danger","Ref. No. Already Exist!");
			die();
		}
		///validation sa gr na hindi pa na despatch
		$str = " SELECT * FROM {$this->db_erp}.`trx_pd_hd` WHERE `PulloutNo` = '{$fld_refno}' AND PD_Tag = 'Y'";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

		if($q->getNumRows() == 0) { 
			$this->memelibsys->warning_msg("#dc3545","text-danger","Ref. No. not yet dispatch!");
			die();
		}
		
		

		//ALAMIN KUNG TRADE NON TRADE
		// $str = "SELECT potrx_no,po_type FROM {$this->db_erp}.`trx_manrecs_po_hd` 
		// WHERE `potrx_no` = '{$fld_refno}' AND `post_tag` = 'Y'";
		// $q7 = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		// $rw7 		= $q7->row_array();
		// $po_type 	= $rw7['po_type'];
		if($po_type == 'T'){
			$str_typ = "SUM(bb.`qty_encd`)";
		}
		else{
			$str_typ = "SUM(bb.`qty_encd`)";
		}
		$str = "
		SELECT 
		bb.`nremarks`,
		IFNULL(cc.`ART_CODE`,bb.`mat_code`) ART_CODE,
		IFNULL(cc.`ART_DESC`,'') ART_DESC,
		IFNULL(cc.`ART_SKU`,'') ART_SKU,
		bb.`ucost`,
		bb.`uprice`,
		sha2(concat(cc.`recid`,'{$mpw_tkn}'),384) __rid,
		{$str_typ} TQTY
		FROM
		{$this->db_erp}.`trx_manrecs_po_hd` aa
		JOIN
		{$this->db_erp}.`trx_manrecs_po_dt` bb
		ON (aa.`recid` =bb.`mrhd_rid`)
		LEFT JOIN
		{$this->db_erp}.`mst_article` cc
		ON (bb.`mat_rid` =cc.`recid`)
	  	WHERE aa.`flag` = 'R'
		AND (aa.`po_rsons_id`='5')
		AND  aa.`potrx_no` = '{$fld_refno}'
		GROUP BY bb.`mat_code`
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		if($q->getNumRows() == 0) { 
			$this->memelibsys->warning_msg("#dc3545","text-danger","No Records Found!");
			die();
		}
		$rrec = $q->getResultArray();
		foreach($rrec as $rw):
			$_particulars = $rw['nremarks'];
			$itemcode     = $rw['ART_CODE'];
			$itemdesc     = $rw['ART_DESC'];
			$sku          = $rw['ART_SKU'];
			$ucost        = $rw['ucost'];
			$uprice       = $rw['uprice'];
			$__rid        = $rw['__rid'];
			$TQTY         = $rw['TQTY'];
			$TQTY         = $rw['TQTY'];
			
			$_items = $itemcode . 'x|x' . $itemdesc . 'x|x' . $sku . 'x|x' . $ucost . 'x|x' . $uprice . 'x|x' . $__rid . 'x|x' . $TQTY . 'x|x' . $_particulars . 'x|x';
			//$medata = explode("x|x",$_items);
			array_push($adatar1,$_items);
			
		endforeach;
		//var_dump($fld_grtyp);
		//die();
		for($xx = 0; $xx < count($adatar1); ++$xx) {
			$xdata = explode("x|x",$adatar1[$xx]);
			$count = count($adatar1);
			
			$itemcode     = $xdata[0];
			$itemdesc     = $xdata[1];
			$sku          = $xdata[2];
			$ucost        = $xdata[3];
			$uprice       = $xdata[4];
			$__rid        = $xdata[5];
			$TQTY         = $xdata[6];
			$_particulars = $xdata[7];
			if(!empty($fld_refno)){
				$chtml = "
					<script>
						$('#btn_refno').off('click');
            $('#btn_additms').prop('disabled',true);
						my_add_line_item('$itemcode','$itemdesc','$sku','$ucost','$uprice','$__rid','$TQTY','$_particulars','$fld_grtyp','$po_type');
						
		            </script>
					";
				echo $chtml;
			}
			
		}
		

	}  //end auto_add_lines

}  //end main class
