<?php namespace App\Controllers;
/*
 * Module      :    Md_purchase.php
 * Type 	   :    Controllers
 * Program Desc:    Md_purchase
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/

use CodeIgniter\Controller;
use App\Models\MyFGPurchaseModel;

class Md_purchase extends BaseController
{

    public function __construct()
    {
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(0);
        $this->mytrxpurch=  new MyFGPurchaseModel();
        $this->request = \Config\Services::request();
    }

    public function index(){

        echo view('templates/myheader');
        echo view('mtap/fgpo/trx-fg-purchase-order');
        echo view('templates/myfooter');
        
    } // end index

    public function fgpurchase_save() { 

        $this->mytrxpurch->fgpurch_entry_save();

    } // end fgpurchase_save

    public function fgpurchase_print() { 

        $this->response->setHeader('Content-Type', 'application/pdf');
        return view('mtap/fgpo/trx-fg-purchase-order-print');

    } // end fgpurchase_print

    public function fgpurchase_vw() { 

        $data = $this->mytrxpurch->fgpurch_rec_view(1,90);
        return view('mtap/fgpo/trx-fg-purchase-order-recs',$data);

    } // end fgpurchase_vw

    public function purchase_recs() { 

        $txtsearchedrec = $this->request->getVar('txtsearchedrec');
        $mpages = $this->request->getVar('mpages');
        $mpages = (empty($mpages) ? 0: $mpages);
        $data = $this->mytrxpurch->purch_rec_view($mpages,20,$txtsearchedrec);
        return view('mtap/fgpo/trx-purchase-order-recs',$data);
        
    } // end purchase_recs

    public function purchase_vw_appr() { 

        $data = $this->mytrxpurch->purch_post_view(1,20);
        return view('mtap/fgpo/trx-purchase-order-appr',$data);

    } // end purchase_vw_appr

    public function purchase_recs_appr() { 

        $txtsearchedrec = $this->request->getVar('txtsearchedrec');
        $mpages = $this->request->getVar('mpages');
        $mpages = (empty($mpages) ? 0: $mpages);
        $data = $this->mytrxpurch->purch_post_view($mpages,20,$txtsearchedrec);
        return view('mtap/fgpo/trx-purchase-order-appr',$data);

    } // end purchase_recs_appr

    public function purchase_print() { 

        $this->response->setHeader('Content-Type', 'application/pdf');
        return view('mtap/fgpo/trx-purchase-order-print');

    } // end purchase_print

    public function barcde_gnrtion() { 

        $this->mytrxpurch->po_barcde_gnrtion();

    } // end barcde_gnrtion

    public function purchase_save_appr() { 

        $this->mytrxpurch->po_for_approval();

    } // end purchase_save_appr

    public function purchase_barcode_dl_proc() { 

        $po_sysctrlno = $this->request->getVar('po_sysctrlno');
        $mtkn_wshe = $this->request->getVar('mtkn_wshe');
        $this->mytrxpurch->download_purch_barcode($po_sysctrlno,$mtkn_wshe);

    } // end purchase_barcode_dl_proc

    public function purchase_print_temp() { 
        
        $chtmljs ="";
        $file_name = 'PO-21060320000000001';
        $cfilelnk = site_url() . 'downloads/me/' . $file_name.'.pdf'; 
        $chtmljs .= "
                        <a href=\"{$cfilelnk}\" download=" . $file_name ." class='btn btn-danger btn-sm col-lg-12' onclick='$(this).remove()'> <i class='bi bi-save'></i> PRINT</a>        
                        ";
        echo $chtmljs;
    } // end purchase_print_temp

    public function fg_itm_recs() { 

		$txtsearchedrec = $this->request->getVar('txtsearchedrec_rl');
		$mpages  = $this->request->getVar('mpages');
		$mpages  = (empty($mpages) ? 1: $mpages);
		$data    = $this->mytrxpurch->fg_view_itm_recs($mpages,50,$txtsearchedrec);
		return view('mtap/fgpo/trx-fg-purchase-item-recs',$data);

	} // end fg_itm_recs
  
}