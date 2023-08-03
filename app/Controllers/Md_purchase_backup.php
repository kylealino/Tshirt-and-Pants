<?php namespace App\Controllers;
  
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

    public function index()
    {
        echo view('templates/myheader');
        echo view('mtap/fgpo/trx-purchase-order');
        echo view('templates/myfooter');
        
    } 

    public function purchase_save() { 
        $this->mytrxpurch->purch_entry_save();
    }  //end test_entry_save


    public function purchase_vw() { 
        $data = $this->mytrxpurch->purch_rec_view(1,20);
        return view('mtap/fgpo/trx-purchase-order-recs',$data);
    } 
    public function purchase_recs() { 
        $txtsearchedrec = $this->request->getVar('txtsearchedrec');
        $mpages = $this->request->getVar('mpages');
        $mpages = (empty($mpages) ? 0: $mpages);
        $data = $this->mytrxpurch->purch_rec_view($mpages,20,$txtsearchedrec);
        return view('mtap/fgpo/trx-purchase-order-recs',$data);
    }
    //APPROVAL
    public function purchase_vw_appr() { 
        $data = $this->mytrxpurch->purch_post_view(1,20);
        return view('mtap/fgpo/trx-purchase-order-appr',$data);
    } 
    public function purchase_recs_appr() { 
        $txtsearchedrec = $this->request->getVar('txtsearchedrec');
        $mpages = $this->request->getVar('mpages');
        $mpages = (empty($mpages) ? 0: $mpages);
        $data = $this->mytrxpurch->purch_post_view($mpages,20,$txtsearchedrec);
        return view('mtap/fgpo/trx-purchase-order-appr',$data);
    }  
    public function purchase_print() { 
        $this->response->setHeader('Content-Type', 'application/pdf');
        return view('mtap/fgpo/trx-purchase-order-print');
    } 
    public function barcde_gnrtion() { 
        $this->mytrxpurch->po_barcde_gnrtion();
    } 
    public function purchase_save_appr() { 
        $this->mytrxpurch->po_for_approval();
    }
    public function purchase_barcode_dl_proc() { 
        $po_sysctrlno = $this->request->getVar('po_sysctrlno');
        $mtkn_wshe = $this->request->getVar('mtkn_wshe');
        $this->mytrxpurch->download_purch_barcode($po_sysctrlno,$mtkn_wshe);
    }
     public function purchase_print_temp() { 
        $chtmljs ="";
        $file_name = 'PO-21060320000000001';
        $cfilelnk = site_url() . 'downloads/me/' . $file_name.'.pdf'; 
        $chtmljs .= "
                        <a href=\"{$cfilelnk}\" download=" . $file_name ." class='btn btn-danger btn-sm col-lg-12' onclick='$(this).remove()'> <i class='bi bi-save'></i> PRINT</a>        
                        ";
        echo $chtmljs;
    }

  
} //end Md_customer 