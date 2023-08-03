<?php namespace App\Controllers;
/*
 * Module      :    Fg_packing.php
 * Type 	   :    Controllers
 * Program Desc:    Fg_packing
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/

use CodeIgniter\Controller;
use App\Models\MyFGPackingModel;

class Fg_packing extends BaseController
{

    public function __construct()
    {
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(0);
        $this->mytrxfgpack=  new MyFGPackingModel();
        $this->request = \Config\Services::request();
    }

    public function index(){
        echo view('templates/myheader');
        echo view('mtap/fgpack/trx-fg-pack');
        echo view('templates/myfooter');
    } //end index

    public function fgpack_save() { 
        $this->mytrxfgpack->fgpack_entry_save();
    } //end fgpack_save

    public function fgpack_vw() { 
        $data = $this->mytrxfgpack->fgpack_rec_view(1,20);
        return view('mtap/fgpack/trx-fg-pack-recs',$data);
    } //end fgpack_vw

    public function fgpack_recs() { 
        $txtsearchedrec = $this->request->getVar('txtsearchedrec');
        $mpages = $this->request->getVar('mpages');
        $mpages = (empty($mpages) ? 0: $mpages);
        $data = $this->mytrxfgpack->fgpack_rec_view($mpages,20,$txtsearchedrec);
        return view('mtap/fgpack/trx-fg-pack-recs',$data);
    } //end fgpack_recs

    public function fgpack_print() { 
        $this->response->setHeader('Content-Type', 'application/pdf');
        return view('mtap/fgpack/trx-fg-pack-print');
    } //end fgpack_print

    public function fgpack_vw_appr() { 
        $data = $this->mytrxfgpack->fgpack_post_view(1,20);
        return view('mtap/fgpack/trx-fg-pack-appr',$data);
    } //end fgpack_vw_appr

    public function fgpack_recs_appr() { 
        $txtsearchedrec = $this->request->getVar('txtsearchedrec');
        $mpages = $this->request->getVar('mpages');
        $mpages = (empty($mpages) ? 0: $mpages);
        $data = $this->mytrxfgpack->fgpack_post_view($mpages,20,$txtsearchedrec);
        return view('mtap/fgpack/trx-fg-pack-appr',$data);
    } //end fgpack_recs_appr

    public function barcde_gnrtion() { 
        $this->mytrxfgpack->fgpack_barcde_gnrtion();
    } //end barcde_gnrtion

    public function fgpack_save_appr() { 
        $this->mytrxfgpack->fgpack_for_approval();
    } //end fgpack_save_appr
    
    public function fgpack_barcode_dl_proc() { 
        $fgpack_trxno = $this->request->getVar('fgpack_trxno');
        $mtkn_wshe = $this->request->getVar('mtkn_wshe');
        $this->mytrxfgpack->download_fgpack_barcode($fgpack_trxno,$mtkn_wshe);
    } //end fgpack_barcode_dl_proc
}