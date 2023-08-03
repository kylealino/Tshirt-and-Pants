<?php 
/**
 *	File        : model/Mymd_prodt_invent_model.php
 *  Auhtor      : Oliver V. Sta Maria
 *  Date Created: Sept 17, 2017
 * 	last update : Sept 17, 2017
 * 	description : Data Model handling for Product Type Iventory Master Data
 */
 namespace App\Models;
 use CodeIgniter\Model;
 use CodeIgniter\Files\File;

class Mytrx_whcrossing_model extends Model 
{ 
	public function __construct()
	{
		parent::__construct();
		$this->mydbname  = model('App\Models\MyDBNamesModel');
		$this->db_erp    = $this->mydbname->medb(1);
		$this->db_temp   = $this->mydbname->medb(3);
		$this->mylibzdb  = model('App\Models\MyLibzDBModel');
		$this->mylibzsys = model('App\Models\MyLibzSysModel');
		$this->mymelibzsys = model('App\Models\Mymelibsys_model');
		$this->mydataz   = model('App\Models\MyDatumModel');
		$this->dbx       = $this->mylibzdb->dbx;
		$this->request   = \Config\Services::request();
		
	}
	
	public function view_ent_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$search_cb = $this->request->getVar('search_cb');

		
		//IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
		$str_vwrecs = "AND a.`muser` = '$cuser'";
		$str_access = $this->mymelibzsys->getUserWHAccess('aa.`wshe_id`');
	
		$str_optn = '';
		if(!empty($msearchrec)){ 
			$msearchrec = $this->dbx->escapeString($msearchrec);
			if($search_cb == 'N'):
			$str_optn = "
				AND
					(a.`agpo_sysctrlno` LIKE '%{$msearchrec}%' OR b.`VEND_NAME` LIKE '%{$msearchrec}%' OR aa.`dr_list` LIKE '%{$msearchrec}%' OR aa.`po_sysctrlno` LIKE '%{$msearchrec}%' OR  w.`wshe_code`  LIKE '%{$msearchrec}%' )";
		else:
			$str_optn = "
				AND
					(a.`agpo_sysctrlno` = '{$msearchrec}' OR b.`VEND_NAME` = '{$msearchrec}' OR aa.`dr_list` = '{$msearchrec}' OR aa.`po_sysctrlno` = '{$msearchrec}' OR  w.`wshe_code`  = '{$msearchrec}' )";
		endif;
		}

		$strqry = "
			SELECT
			  aa.`po_sysctrlno` __poref,
			  b.`VEND_NAME` AS `__vend_name`,
			  b.`VEND_ICODE` AS `__vend_SUPINCODE`, 
			  c.`CUST_NAME` AS `__vends_name`, 
			  aa.`dr_list`,
			  a.`recid`,
			  a.`agpo_sysctrlno`,
			  a.`po_sysctrlno`,
			  a.`po_id`,
			  a.`muser`,
			  a.`encd_date`,
			  a.`done`,
			  a.`is_print`,
			  w.`wshe_code`,
			  aa.`wshe_id`,
			  aa.`plnt_id`,
			  a.`mkg_tag`
			FROM {$this->db_erp}.`trx_agpo_hd_print` a
			LEFT JOIN {$this->db_erp}.`trx_po_hd` aa
			ON (a.`po_id` =  aa.`recid`)
			LEFT JOIN {$this->db_erp}.`mst_vendor` b
			ON (aa.`vend_rid` = b.`recid` )
			LEFT JOIN {$this->db_erp}.`mst_customer` c
			ON (aa.`vends_rid` = c.`recid`)
			LEFT JOIN {$this->db_erp}.`mst_wshe` w
			ON (aa.`wshe_id` = w.`recid`)
			WHERE a.`done` = '0' AND a.`active` = 'Y'
			{$str_optn}
			{$str_access}
			GROUP BY aa.`po_sysctrlno`";
		
		$str = "
		select count(*) __nrecs from ({$strqry}) oa
		";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rw = $qry->getRowArray();
		$npagelimit = ($npagelimit > 0 ? $npagelimit : 30);
		$nstart = ($npagelimit * ($npages - 1));


		$npage_count = ceil(($rw['__nrecs'] + 0) / $npagelimit);
		$data['npage_count'] = $npage_count;
		$data['npage_curr'] = $npages;
		$str = "
		SELECT * from ({$strqry}) oa ORDER BY `recid` DESC limit {$nstart},{$npagelimit} ";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

		if($qry->getNumRows() > 0) { 
		 $data['rlist'] = $qry->getResultArray();
		 $data['txtsearchedrec_rl'] = $msearchrec;
		 $data['search_cb'] = $search_cb;
		 
		} else { 
		 $data = array();
		 $data['npage_count'] = 1;
		 $data['npage_curr'] = 1;
		 $data['rlist'] = '';
		 $data['search_cb'] = $search_cb;
		 $data['txtsearchedrec_rl'] = $msearchrec;
		}
		return $data;
	} //endfunc
	

	public function add_poprint_recs($npages = 1,$npagelimit = 20,$msearchrec='') { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		//$cusergrp = $this->mylibzdb->mysys_usergrp();
		
		$str_optn = '';
		if(!empty($msearchrec)) { 
			$msearchrec = $this->dbx->escapeString($msearchrec);
			$str_optn = "
				AND
					(a.`po_sysctrlno` LIKE '%{$msearchrec}%' OR b.`VEND_NAME` LIKE '%{$msearchrec}%' OR a.`dr_list` LIKE '%{$msearchrec}%')
			";
		}

		//get cd warehouse access
		$str_access = $this->mymelibzsys->getUserWHAccess('a.`wshe_id`');
		$startDate = '2022-10-11'; //oct 11,2022 onwards
		$strqry = "
			SELECT 
			a.`recid`,
            a.`po_sysctrlno`,
            a.`trx_date`,
            a.`asstd_tag`,
            a.`muser`,
            a.`dr_list`,
            b.`VEND_NAME` AS `__vend_name`,
            b.`VEND_ICODE` AS `__vend_SUPINCODE`, 
	        c.`CUST_NAME` AS `__vends_name`
	        FROM {$this->db_erp}.`trx_po_hd` a
	        LEFT JOIN {$this->db_erp}.`mst_vendor` b ON a.`vend_rid` = b.`recid`
	        LEFT JOIN {$this->db_erp}.`mst_customer` c ON a.`vends_rid` = c.`recid`
	        WHERE a.`agpo_print` = '0' AND `is_bcodegen` = '1' AND a.`is_approved` = '1' AND a.`is_cancel` = 'N'
	        AND DATE(a.`trx_date`) >= '{$startDate}'
	        {$str_access}
	        {$str_optn}
	        ORDER BY 
	            a.`recid` DESC";
	   
		$str = "
		select count(*) __nrecs from ({$strqry}) oa
		";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rw = $qry->getRowArray();
		$npagelimit = ($npagelimit > 0 ? $npagelimit : 30);
		$nstart = ($npagelimit * ($npages - 1));


		$npage_count = ceil(($rw['__nrecs'] + 0) / $npagelimit);
		$data['npage_count'] = $npage_count;
		$data['npage_curr'] = $npages;
		$str = "
		SELECT * from ({$strqry}) oa ORDER BY `recid` DESC limit {$nstart},{$npagelimit} ";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

		if($qry->getNumRows() > 0) { 
		$data['rlist'] = $qry->getResultArray();
		} else { 
		$data = array();
		$data['npage_count'] = 1;
		$data['npage_curr'] = 1;
		$data['rlist'] = '';
		}
		return $data;
	} //endfunc

	public function agpoprint_sv(){ 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$str_poprint = '';
		$adata1 = $this->request->getVar('adata1');
		$isMKG = $this->request->getVar('isMKG');
		if(count($adata1) > 0) {
			$ctrl_no = $this->mydataz->get_ctr_5($this->db_erp,'CTRL_AGPO');
			$fld_agpo_sysctrlno = 'CDAOR'. $ctrl_no;
			$fld_irr_sysctrlno = 'IRIOR'. $ctrl_no;
			for($aa = 0; $aa < count($adata1); $aa++) { 
					$po_mtkn = $adata1[$aa];
					$str_poprint .= "sha2(concat(a.`recid`,'{$mpw_tkn}'),384) = '{$po_mtkn}' or ";
					
			}
			if(strlen($str_poprint) > 0) {
				$str_poprint = " and (" . substr($str_poprint,0,strlen($str_poprint) - 3) . ")";

				
			}
		}

		$strq = "
		INSERT INTO {$this->db_erp}.`trx_agpo_hd_print`
		  (`agpo_sysctrlno`,
		  `irr_sysctrlno`,
		  `po_sysctrlno`,
		  `mkg_tag`,
		  `po_id`,
		  `muser`)
		SELECT
		  '$fld_agpo_sysctrlno',
		  '$fld_irr_sysctrlno',
		  a.`po_sysctrlno`,
		  '{$isMKG}',
		  a.`recid`,
		  '$cuser'
		FROM {$this->db_erp}.`trx_po_hd` a
		WHERE a.`agpo_print` = '0' {$str_poprint}
		";
		$this->mylibzdb->myoa_sql_exec($strq,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$this->mylibzdb->user_logs_activity_module($this->db_erp,'PO_PRINT_ADDED_INSERT','',$fld_agpo_sysctrlno,$strq,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		//var_dump($str);
		//die();
		$str = "
		UPDATE {$this->db_erp}.`trx_po_hd` a
		SET a.`agpo_print` = '1'
		WHERE a.`agpo_print` = '0' {$str_poprint}
		";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$this->mylibzdb->user_logs_activity_module($this->db_erp,'PO_PRINT_ADDED','',$fld_agpo_sysctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

		

		if(!empty($fld_agpo_sysctrlno)){
			//RUN THIS QEY FOR ADJUSTMENT TIMEOUT
			$str = "
			SELECT `po_sysctrlno` FROM {$this->db_erp}.`trx_agpo_hd_print` WHERE `po_sysctrlno` IN (SELECT `po_sysctrlno` FROM {$this->db_erp}.`trx_po_hd` WHERE agpo_print ='0')
			";
			$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			if($qry->getNumRows() > 0) { 
				$str = "
				UPDATE {$this->db_erp}.`trx_po_hd` a,{$this->db_erp}.`trx_agpo_hd_print` b
				SET agpo_print ='1'
				WHERE a.`agpo_print` ='0'
				AND a.`po_sysctrlno` =  b.`po_sysctrlno`
				";
				$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				$this->mylibzdb->user_logs_activity_module($this->db_erp,'PO_PRINT_ADDED_ADJUSTMENT','',$fld_agpo_sysctrlno,$str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

			}
			
			
			//PARA SA ENCODING PANGCHECK NG DUPLICATE IETMCODE
			echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Success.<br/></strong><strong>PO Number:[$fld_agpo_sysctrlno]</strong>.<br/>Data Recorded Successfully!!!</div>
			
			";
			die();
		}
	}//endfunc

	public function whcrossing_report_download()
	{
		$cuser          = $this->mylibzdb->mysys_user();
		$mpw_tkn        = $this->mylibzdb->mpw_tkn();

		$fld_dl_dteto   = $this->request->getVar('fld_dl_dteto');
		$fld_dl_dteto   = $this->mylibzsys->mydate_yyyymmdd($fld_dl_dteto);
		$fld_dl_dtefrom = $this->request->getVar('fld_dl_dtefrom');
		$fld_dl_dtefrom = $this->mylibzsys->mydate_yyyymmdd($fld_dl_dtefrom);
		$fld_dl_trxno = $this->request->getVar('fld_dl_trxno');
		$fld_dl_packlist = $this->request->getVar('fld_dl_packlist');

		$__flag         = "C";
		$str_optn       = "";
		$fld_dlsupp_q   = "";
		$fld_dlbranch_q = "";
		$chtmlhd        = "";
		$chtmljs        = "";
		$chtml          = "";
		$cmsexp         = "";
		$cmsgt          = "";
		$chtml2         = "";
		$cmsft          = "";
		$date           = date("F j, Y, g:i A");
		$str_date = "";
		$qry_dl_trxno = "";
		$qry_dl_packlist = "";
		$cfilelnk='';
		$file_name = 'allocation_report'.'_'.$cuser.'_'.date('Ymd').$this->mylibzsys->random_string(15);
        $mpathdn   = ROOTPATH;
        $_csv_path = '/public/downloads/me/';
        $filepath = $mpathdn.$_csv_path.$file_name.'.csv';
        $cfilelnk = site_url() . 'downloads/me/' . $file_name.'.csv'; 
		if (!empty($fld_dl_trxno)) {
			$str_date  .= " AND a.`agpo_sysctrlno` = '$fld_dl_trxno' ";
		}
		if (!empty($fld_dl_packlist)) {
			$str_date .= " AND b.`dr_list` = '$fld_dl_packlist' ";
		}
		if ((!empty($fld_dl_dtefrom) && !empty($fld_dl_dteto)) && (($fld_dl_dtefrom != '--') && ($fld_dl_dteto != '--'))) {
			$str_date .= " AND (SUBSTRING_INDEX(a.`encd_date`,' ',1) >= DATE('{$fld_dl_dtefrom}') AND  SUBSTRING_INDEX(a.`encd_date`,' ',1) <= DATE('{$fld_dl_dteto}'))";
		}
		if (((!empty($fld_dl_trxno) && !empty($fld_dl_packlist)) && (($fld_dl_dteto != '--') && ($fld_dl_dtefrom != '--'))) || (!empty($fld_dl_trxno) || !empty($fld_dl_packlist)) || ((!empty($fld_dl_trxno)) && (($fld_dl_dteto != '--') && ($fld_dl_dtefrom != '--'))) || (!empty($fld_dl_trxno)) || ((empty($fld_dl_trxno) && empty($fld_dl_packlist)) && (($fld_dl_dteto != '--') && ($fld_dl_dtefrom != '--'))) ){

			$strqry = "	
					SELECT *
					INTO OUTFILE '{$filepath}'
					FIELDS TERMINATED BY '\t'
					LINES TERMINATED BY '\r\n'
					FROM(
						SELECT 
						'Allocation Guide Trx. No',
						'STOCK_CODE',
						'BOX_ITEM_CODE',
						'BOX_ITEM_DESC',
						'PACKAGING',
						'QTY',
						'CONVF',
						'TOTAL_PCS',
						'TOTAL_AMOUNT',
						'BARCODE',
						'PLANT',
						'WAREHOUSE',
						'RACK',
						'BIN',
						'STEXT',
						'DATE_ENCODED'

						UNION ALL

						SELECT a.`agpo_sysctrlno`, c.`stock_code`, d.`ART_CODE`, d.`ART_DESC`, d.`ART_SKU`, e.`qty`, c.`convf`, c.`total_pcs`,
						(SELECT
						SUM(artt.`ART_UPRICE`*item.`qty`) AS `total_amount`
						FROM
						   `wshe_barcdng_item` item
						JOIN
						   `wshe_barcdng_dt` inv
						ON
							item.`dt_id` = inv.`recid` AND item.`mat_rid` = c.`recid` AND item.`header` = inv.`header`
						JOIN
						   `mst_article` artt
						ON
							item.`mat_rid` = artt.`recid`
						WHERE
							c.`recid` = inv.`recid`
						) AS `po_tamt`,
						c.`irb_barcde`, f.`plnt_code`, g.`wshe_code`,i.`wshe_grp`, h.`wshe_bin_name`,b.`dr_list`, c.`encd`
						

						FROM
						{$this->db_erp}.`trx_agpo_hd_print` a
						JOIN
						{$this->db_erp}.`trx_po_hd` b
						ON 
						a.`po_sysctrlno` = b.`po_sysctrlno`
						JOIN 
						{$this->db_erp}.`wshe_barcdng_dt` c
						ON
						b.`po_sysctrlno` = c.`header` AND c.`to_plnt_id` = b.`plnt_id` AND c.`to_wshe_id` = b.`wshe_id`
						JOIN 
						{$this->db_erp}.`mst_article` d
						ON
						c.`mat_rid` = d.`recid`
						JOIN 
						{$this->db_erp}.`trx_po_dt` e
						ON
						a.`po_sysctrlno` = e.`po_sysctrlno`
						JOIN 
						{$this->db_erp}.`mst_plant` f
						ON
						b.`plnt_id` = f.`recid`
						JOIN
						{$this->db_erp}.`mst_wshe` g
						ON
						b.`wshe_id` = g.`recid`
						LEFT JOIN 
						{$this->db_erp}.`mst_wshe_bin` h
						ON
						c.`to_wshe_id` = h.`wshe_id` AND c.`to_plnt_id` = h.`plnt_id` AND c.`to_wshe_sbin_id` = h.`recid`
						LEFT JOIN 
						{$this->db_erp}.`mst_wshe_grp` i
						ON 
						c.`to_wshe_id` = i.`wshe_id` AND c.`to_plnt_id` = i.`plnt_id` AND c.`to_wshe_grp_id` = i.`recid`
						WHERE a.`active` = 'Y'
						{$str_date}
						GROUP BY c.`irb_barcde`
						)oa
						";


			$q = $this->mylibzdb->myoa_sql_exec($strqry, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

			$chtmljs .= "
                    <script type=\"text/javascript\">
                        //window.parent.document.getElementById('myscrloading').innerHTML = '';
                        alloc_report_dl();
                        function alloc_report_dl(){
                            window.location = '{$cfilelnk}';
                        }
                        
                        jQuery('#lnktoprint').click(function() { 
                            jQuery('#__mtoexport_drtd').css({display:'none'});
                            //jQuery('#__mtoprint').css({display:'none'});
                            window.print();         
                        });
                    </script>
                    
                    ";
        	echo $chtmljs;
			
		}
	}
	
	public function view_reversal_recs($npages = 1,$npagelimit = 20,$msearchrec=''){ 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();

		
		//IF USERGROUP IS EQUAL SA THEN ALL DATA WILL VIEW ELSE PER USER
		$str_vwrecs = "AND a.`muser` = '$cuser'";
		$str_access = $this->mymelibzsys->getUserWHAccess('aa.`wshe_id`');
	
		$str_optn = '';
		if(!empty($msearchrec)){ 
			$msearchrec = $this->dbx->escapeString($msearchrec);
		
			$str_optn = "
				AND (a.`agpo_sysctrlno` LIKE '%{$msearchrec}%' OR a.`po_sysctrlno` LIKE '%{$msearchrec}%')";
		}

		$strqry = "
			SELECT
			  GROUP_CONCAT(a.`po_sysctrlno` SEPARATOR ' , ') __poref,
			  a.`recid`,
			  a.`agpo_sysctrlno`
			FROM {$this->db_erp}.`trx_agpo_hd_print` a
			WHERE a.`done` = '0' AND a.`active` = 'Y'
			{$str_optn}
			GROUP BY a.`agpo_sysctrlno`";
		
		$str = "
		select count(*) __nrecs from ({$strqry}) oa
		";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rw = $qry->getRowArray();
		$npagelimit = ($npagelimit > 0 ? $npagelimit : 30);
		$nstart = ($npagelimit * ($npages - 1));


		$npage_count = ceil(($rw['__nrecs'] + 0) / $npagelimit);
		$data['npage_count'] = $npage_count;
		$data['npage_curr'] = $npages;
		$str = "
		SELECT * from ({$strqry}) oa ORDER BY `recid` DESC limit {$nstart},{$npagelimit} ";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

		if($qry->getNumRows() > 0) { 
		 $data['rlist'] = $qry->getResultArray();
		 $data['txtsearchedrec_rl'] = $msearchrec;
		
		 
		} else { 
		 $data = array();
		 $data['npage_count'] = 1;
		 $data['npage_curr'] = 1;
		 $data['rlist'] = '';
		
		 $data['txtsearchedrec_rl'] = $msearchrec;
		}
		return $data;
	} //endfunc


	 public function mywhcrossing_revert(){
		 $cuser = $this->mylibzdb->mysys_user();
		 $mpw_tkn = $this->mylibzdb->mpw_tkn();

		 $mtkn_trxno      =  $this->request->getVar('mtkn_trxno'); 

		 
		// get header data 
		$hdCode = $this->get_headerData($mtkn_trxno);

		
		$str_up = "
		UPDATE  {$this->db_erp}.`trx_agpo_hd_print` hd, {$this->db_erp}.`trx_po_hd` po
		SET  
		hd.`active` = 'N',
		po.`agpo_print` = 0
		WHERE hd.`po_sysctrlno` = po.`po_sysctrlno` AND hd.`agpo_sysctrlno` = '{$hdCode}' ";

		$this->mylibzdb->myoa_sql_exec($str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

		/***************** AUDIT LOGS *************************/
		$this->mylibzdb->user_logs_activity_module($this->db_erp,'REVERT_CDAG_OUT','REVERT',$hdCode,$str_up,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		/***************** AUDIT LOGS *************************/

		 echo  "<strong>{$hdCode}</strong> reverted successfully!";

	 }

	 public function get_headerData($mktn_hd = ''){
		 $cuser = $this->mylibzdb->mysys_user();
		 $mpw_tkn = $this->mylibzdb->mpw_tkn();
		 $data  = [];
		 $str = "
		 SELECT 
		   hd.`agpo_sysctrlno`
		 FROM
			 {$this->db_erp}.`trx_agpo_hd_print` hd
		 WHERE
			SHA2(CONCAT(hd.`recid`,'{$mpw_tkn}'),384) = '{$mktn_hd}'";

		 $q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

		 if($q->getNumRows() ==  0){
			 echo "No records found";
			 die();
		 }
		 $row = $q->getRowArray();
		 return $row['agpo_sysctrlno'];

	 }


}  //end main class
