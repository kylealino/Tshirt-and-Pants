<?php namespace App\Controllers;
/*
 * Module      :    Rm_purchase.php
 * Type 	   :    Controllers
 * Program Desc:    Rm_purchase
 * Author      :    Kyle P. Alino
 * Date Created:    July 7, 2023
*/

use CodeIgniter\Controller;
use App\Models\MyRMPurchaseModel;

class Rm_purchase extends BaseController
{

    public function __construct()
    {
        $this->mydbname = model('App\Models\MyDBNamesModel');
        $this->db_erp = $this->mydbname->medb(0);
        $this->mytrxrmpurch=  new MyRMPurchaseModel();
        $this->request = \Config\Services::request();
    }

    public function index(){

        echo view('templates/myheader');
        echo view('mtap/rmpo/trx-rm-purchase-order');
        echo view('templates/myfooter');
        
    } //end index

    public function rmpurchase_save() { 

        $this->mytrxrmpurch->rmpurch_entry_save();

    } //end rmpurchase_save

    public function rmpurchase_vw() { 

        $data = $this->mytrxrmpurch->rmpurch_rec_view(1,90);
        return view('mtap/rmpo/trx-rm-purchase-order-recs',$data);

    } //end rmpurchase_vw

    public function rmpurchase_recs() { 

        $data = $this->mytrxrmpurch->rmpurch_rec_view();
        return view('mtap/rmpo/trx-rm-purchase-order-recs',$data);

    } //end rmpurchase_recs

    public function rmpurchase_print() { 

        $this->response->setHeader('Content-Type', 'application/pdf');
        return view('mtap/rmpo/trx-rm-purchase-order-print');

    } //end rmpurchase_print

    public function rm_itm_recs() { 

		$data    = $this->mytrxrmpurch->rm_view_itm_recs();
		return view('mtap/rmpo/trx-rm-purchase-item-recs',$data);

	} //end rm_itm_recs

} 