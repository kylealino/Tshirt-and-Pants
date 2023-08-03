<?php namespace App\Controllers;
  
use CodeIgniter\Controller;
  
class Melogout extends BaseController
{
    public function index()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/mylogin');
    } 
} //end main Melogout