<?php

namespace App\Controllers;
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
use App\Libraries\Fpdf\Mypdf;
use App\Models\MyLibzDBModel;

class WhCrossing extends BaseController
{

	public function __construct()
	{
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(1);
		$this->mywhcrossing =  new Mytrx_whcrossing_model();
		$this->memelibsys =  new Mymelibsys_model();
		$this->mydataz =  new MyDatummodel();
		$this->mydatazua =  new MyDatauaModel();
		$this->mylibzdb = new MyLibzDBModel();
		$this->sysuaid  = $this->mylibzdb->mysys_user();
		$this->request = \Config\Services::request();
		$this->data['message'] = "Sorry, You Are Not Allowed to Access This Page";
	}
	public function index()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp, $this->sysuaid, "myuatrx_id='188'", "myua_trx");
		if ($result == 1) :
			//echo "string";
			echo view('templates/myheader');
			echo view('Whcrossing/Whcrossing_main');
			echo view('templates/myfooter');
		else :
			echo view('templates/myheader');
			echo view('errors/html/error_404', $this->data);
			echo view('templates/myfooter');
		endif;
	}


	public function whcrossing_pl_recs()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp, $this->sysuaid, "myuatrx_id='190'", "myua_trx");
		if ($result == 0) :
			echo view('errors/html/error_404', $this->data);
			die();
		endif;

		$txtsearchedrec = $this->request->getVar('txtsearchedrec');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1 : $mpages);
		$data    = $this->mywhcrossing->add_poprint_recs($mpages, 10, $txtsearchedrec);
		return view('Whcrossing/Whcrossing_pl_recs', $data);
	}

	// VIEWING RECORDS	
	public function whcrossing_ent_recs()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp, $this->sysuaid, "myuatrx_id='191'", "myua_trx");
		if ($result == 0) :
			echo view('errors/html/error_404', $this->data);
			die();
		endif;

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1 : $mpages);
		$data    = $this->mywhcrossing->view_ent_recs($mpages, 10, $txtsearchedrec);
		return view('Whcrossing/Whcrossing_recs', $data);
	}

	public function agpo_printsv()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp, $this->sysuaid, "myuatrx_id='189'", "myua_trx");
		if ($result == 0) :
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhcrossing->agpoprint_sv();
	}


	public function mycrossing_print()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp, $this->sysuaid, "myuatrx_id='192'", "myua_trx");
		if ($result == 0) :
			echo view('errors/html/error_404', $this->data);
			die();
		endif;

		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('Whcrossing/Whcrossing_print');
	}

	public function mycrossing_print_mkg()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp, $this->sysuaid, "myuatrx_id='192'", "myua_trx");
		if ($result == 0) :
			echo view('errors/html/error_404', $this->data);
			die();
		endif;

		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('Whcrossing/Whcrossing_print-mkg');
	}

	public function mycrossing_irrprint()
	{
		$mtkn   = $this->request->getVar('tkn');
		$result = $this->mydatazua->get_Active_menus($this->db_erp, $this->sysuaid, "myuatrx_id='193'", "myua_trx");
		if ($result == 0) :
			echo view('errors/html/error_404', $this->data);
			die();
		endif;

		$this->response->setHeader('Content-Type', 'application/pdf');
		if ($mtkn == 1) :
			return view('Whcrossing/Whcrossing_irrprint');
		elseif ($mtkn == 3) :
			return view('Whcrossing/Whcrossing_irrprint_plcdcebu');
		elseif ($mtkn == 4) :
			return view('Whcrossing/Whcrossing_irrprint_plcdo');
		elseif ($mtkn == 6) :
			return view('Whcrossing/Whcrossing_irrprint_plsx');
		else :
			die('Invalid token');
		endif;
	}

	public function mycrossing_irrprintmkg()
	{
		$mtkn   = $this->request->getVar('tkn');
		$result = $this->mydatazua->get_Active_menus($this->db_erp, $this->sysuaid, "myuatrx_id='193'", "myua_trx");
		if ($result == 0) :
			echo view('errors/html/error_404', $this->data);
			die();
		endif;

		$this->response->setHeader('Content-Type', 'application/pdf');

		if ($mtkn == 1) :
			return view('Whcrossing/Whcrossing_irrprint-mkg');
		elseif ($mtkn == 3) :
			return view('Whcrossing/Whcrossing_irrprint-mkg_plcdcebu');
		elseif ($mtkn == 4) :
			return view('Whcrossing/Whcrossing_irrprint-mkg_plcdo');
		elseif ($mtkn == 6) :
			return view('Whcrossing/Whcrossing_irrprint-mkg_plsx');
		else :
			die('Invalid token');
		endif;
	}

	public function mycrossing_wrrprint()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp, $this->sysuaid, "myuatrx_id='194'", "myua_trx");
		if ($result == 0) :
			echo view('errors/html/error_404', $this->data);
			die();
		endif;

		$this->response->setHeader('Content-Type', 'application/pdf');
		return view('Whcrossing/Whcrossing_wrrprint');
	}

	public function whcrossing_sd()
	{
		echo view('templates/myheader');
		echo view('Whcrossing/Whcrossing_shipdoc');
		echo view('templates/myfooter');
	}

	public function whcrossing_out()
	{
		echo view('templates/myheader');
		echo view('Whcrossing/Whcrossing_out');
		echo view('templates/myfooter');
	}

	public function whcrossing_outpl_recs()
	{
		$txtsearchedrec = $this->request->getVar('txtsearchedrec');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1 : $mpages);
		$data    = $this->mywhcrossing->add_poprint_recs($mpages, 10, $txtsearchedrec);
		return view('Whcrossing/Whcrossing_outpl_recs', $data);
	}

	public function mycrossing_alloc_report()
	{
		$result = $this->mydatazua->get_Active_menus($this->db_erp, $this->sysuaid, "myuatrx_id='235'", "myua_trx");
		if ($result == 0) :
			echo view('errors/html/error_404', $this->data);
			die();
		endif;
		return view('Whcrossing/Whcrossing_alloc_report');
	}

	public function cdatrx_vw()
	{
		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term    = $this->request->getVar('term');

		$autoCompleteResult = array();

		$str = "
		SELECT
		aa.`agpo_sysctrlno`
		FROM {$this->db_erp}.`trx_agpo_hd_print` aa

		WHERE aa.`agpo_sysctrlno` like '%{$term}%' and aa.`active` = 'Y'
		GROUP BY aa.`agpo_sysctrlno`
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if ($q->getNumRows() > 0) {
			$rrec = $q->getResultArray();
			foreach ($rrec as $row) :
				array_push($autoCompleteResult, array(
					"value" => $row['agpo_sysctrlno'],
					"agpo_sysctrlno" => $row['agpo_sysctrlno']


				));
			endforeach;
		}

		$q->freeResult();
		echo json_encode($autoCompleteResult);
	}

	public function drpacklist_vw()
	{
		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term    = $this->request->getVar('term');

		$autoCompleteResult = array();

		$str = "
		SELECT
		a.`dr_list`
		FROM {$this->db_erp}.`trx_po_hd` a
		JOIN
		{$this->db_erp}.`trx_agpo_hd_print` b
		ON
		a.`po_sysctrlno` = b.`po_sysctrlno`
		GROUP BY a.`dr_list`
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if ($q->getNumRows() > 0) {
			$rrec = $q->getResultArray();
			foreach ($rrec as $row) :
				array_push($autoCompleteResult, array(
					"value" => $row['dr_list'],
					"dr_list" => $row['dr_list']


				));
			endforeach;
		}

		$q->freeResult();
		echo json_encode($autoCompleteResult);
	}


	public function mycrossing_alloc_report_download()
	{
		$this->mywhcrossing->whcrossing_report_download();
	}

	public function whcrossing_reversal_vw(){

		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='233'","myua_trx");
		if($result == 0):
			echo view('errors/html/error_404',$this->data);
			die();
		endif;

		return view('Whcrossing/Whcrossing_reversal');

	}

	public function whcrossing_reversal_recs(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='233'","myua_trx");
		if($result == 0):
		echo view('errors/html/error_404',$this->data);
		die();
		endif;

		$txtsearchedrec = $this->request->getVar('txtsearchedrec');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mywhcrossing->view_reversal_recs($mpages,10,$txtsearchedrec);
		return view('Whcrossing/Whcrossing_reversal_recs',$data);
	}

	public function whcrossing_revert(){
		$result = $this->mydatazua->get_Active_menus($this->db_erp,$this->sysuaid,"myuatrx_id='234'","myua_trx");
		if($result == 0):
			echo "Save Failed: You are not authorized to change the data.";
			die();
		endif;
		$this->mywhcrossing->mywhcrossing_revert();

	}

	
}  //end main class
