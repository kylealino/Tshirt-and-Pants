<?php

namespace App\Controllers;

class Mymain extends BaseController
{
    public function index()
    {
        echo view('templates/myheader');
        echo view('mebod');
        echo view('templates/myfooter');
    } 

    public function me_test_spinner() {
        echo view('templates/myheader');
        //echo view('metestspinner');
        echo view('templates/myfooter');
    }
}  //end main Mymain
