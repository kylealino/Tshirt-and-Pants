<?php

$cuserfullname = session()->get('__xsys_myusererpfullname__');

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Novo PH ERP System</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <!-- <link href="<?=base_url('assets/img/favicon.png');?>" rel="icon"> -->
  <link rel="shortcut icon" href="<?=base_url('assets-login/img/novo.ico');?>">
  <link href="<?=base_url('assets/img/apple-touch-icon.png');?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <script src="<?=base_url('assets/vendor/jquery/jquery.min.js')?>"></script>
  <script src="<?=base_url('assets/vendor/jquery-ui/jquery-ui.min.js')?>"></script>
  <link href="<?=base_url('assets/vendor/jquery-ui/jquery-ui.min.css');?>" rel="stylesheet" />

  <link href="<?=base_url('assets/vendor/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet">
  <link href="<?=base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css');?>" rel="stylesheet">
  <link href="<?=base_url('assets/vendor/boxicons/css/boxicons.min.css');?>" rel="stylesheet">
  <link href="<?=base_url('assets/vendor/quill/quill.snow.css');?>" rel="stylesheet">
  <link href="<?=base_url('assets/vendor/quill/quill.bubble.css');?>" rel="stylesheet">
  <link href="<?=base_url('assets/vendor/remixicon/remixicon.css');?>" rel="stylesheet">
  <link href="<?=base_url('assets/vendor/simple-datatables/style.css');?>" rel="stylesheet">
  <link href="<?=base_url('assets/css/mepreloader.css');?>" rel="stylesheet">
  <link href="<?=base_url('assets/vendor/bootstrap-datepicker/css/datepicker.css');?>" rel="stylesheet" />
  <link  href="<?=base_url('assets/vendor/datatable/dataTables.bootstrap5.min.css');?>" rel="stylesheet">
  <script src="<?=base_url('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js')?>"></script>
  

  <!-- Template Main CSS File -->
  <link href="<?=base_url('assets/css/style.css');?>" rel="stylesheet">
  <link href="<?=base_url('assets/css/me-custom.css');?>" rel="stylesheet">
  <script src="<?=base_url('assets/js/mysysapps.js')?>"></script>
  <script type="text/javascript"  src="<?=base_url('assets/vendor/datatable/jquery.dataTables.min.js');?>"></script>
    <script type="text/javascript"  src="<?=base_url('assets/vendor/datatable/dataTables.bootstrap5.min.js');?>"></script>

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="<?=site_url();?>" class="logo d-flex align-items-center">
        <img src="<?=base_url('assets-login/img/novo.png');?>" alt="Novo PH">
        <span class="d-none d-lg-block">Novo PH</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="<?=base_url('assets/img/messages-1.jpg');?>" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="<?=base_url('assets/img/messages-2.jpg');?>" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="<?=base_url('assets/img/messages-3.jpg');?>" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="<?=base_url('assets/img/profile-img.jpg');?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?=$cuserfullname;?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?=$cuserfullname;?></h6>
              <span>System User</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?=site_url();?>melogout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="<?=site_url();?>dashboard">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

<!--       <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Card Number</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav" id="sidebar-nav-tag">
          <li>
            <a href="<?=site_url();?>me-card-vw"> 
              <i class="bi bi-circle"></i><span>Monitoring Card Series</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav-tcards" data-bs-toggle="collapse" href="#">
              <i cclass="bi bi-journal-text"></i><span>Tagging of Card Series</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav-tcards" class="nav-content collapse " data-bs-parent="#sidebar-nav-tag">
              <li>
                <a href="<?=site_url();?>me-tagcardlost-vw">
                  <i class="bi bi-circle"></i><span>Lost Card</span>
                </a>
              </li>
              <li>
                <a href="<?=site_url();?>me-tagcardsdmg-vw">
                  <i class="bi bi-circle"></i><span>Damaged Card</span>
                </a>
              </li>
            </ul>
          </li>
          
        </ul>
      </li> -->
      <!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Transaction</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav" id="sidebar-nav-app">
 <!--          <li>
            <a href="<?=site_url();?>me-newapp-cust">
              <i class="bi bi-circle"></i><span>New</span>
            </a> 
          </li>
          <li>
            <a href="<?=site_url()?>gjv-entry">
              <i class="bi bi-circle"></i><span>Renewal</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url()?>gjv-entry">
              <i class="bi bi-circle"></i><span>Replacement</span>
            </a>
          </li> -->
          <li>
            <a href="<?=site_url()?>whcrossing">
              <i class="bi bi-circle"></i><span>Cross Docking Allocation Guide </span>
            </a>
            <a href="<?=site_url()?>warehouse-rcvng">
              <i class="bi bi-circle"></i><span>Warehouse Receiving</span>
            </a>
            <a href="<?=site_url()?>warehouse-inv">
              <i class="bi bi-circle"></i><span>Warehouse Inventory</span>
            </a>

            <a href="<?=site_url()?>warehouse-inv-transfer">
              <i class="bi bi-circle"></i><span>Warehouse Transfer</span>
            </a>
            <a href="<?=site_url()?>good-receive">
              <i class="bi bi-circle"></i><span>GR Entry</span>
            </a>
            <a href="<?=site_url()?>good-receive-rcvng">
              <i class="bi bi-circle"></i><span>GR Receiving</span>
            </a>
            <a href="<?=site_url()?>gi-entry">
              <i class="bi bi-circle"></i><span>GI Entry</span>
            </a>
            <a href="<?=site_url()?>manual-sd-entry">
              <i class="bi bi-circle"></i><span>Manual SD Entry</span>
            </a>
          </li>  
        </ul>
      </li><!-- End Tables Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Outgoing</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?=site_url();?>warehouse-out">
              <i class="bi bi-circle"></i><span>Cross Docking Outgoing</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>whcrossing-sd">
              <i class="bi bi-circle"></i><span>Shipping Document</span>
            </a>
          </li>
            <li>
            <a href="<?=site_url();?>me-points-inq">
              <i class="bi bi-circle"></i><span>Shipping Document Trip</span>
            </a>
          </li>
          <!-- <li>
            <a href="charts-echarts.html">
              <i class="bi bi-circle"></i><span>ECharts</span>
            </a>
          </li> -->
        </ul>
      </li><!-- End Charts Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#maitenance-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear"></i><span>Maintenance</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="maitenance-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?=site_url();?>me-item-comp-vw">
              <i class="bi bi-circle"></i><span>Item Components</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>me-item-comp-vw-2">
              <i class="bi bi-circle"></i><span>Item Components v2</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>me-standard-cap">
              <i class="bi bi-circle"></i><span>Standard Capacity</span>
            </a>
          </li>
        </ul>
      </li> <!-- End Maintenance Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#planning-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-check"></i><span>Planning</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="planning-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?=site_url();?>me-prod-plan">
              <i class="bi bi-circle"></i><span>Production Planning</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>me-tp-alloc-vw">
              <i class="bi bi-circle"></i><span>TPA Entry</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>me-fgpack-req-vw">
              <i class="bi bi-circle"></i><span>FGP Request</span>
            </a>
          </li>
        </ul>
      </li> <!-- End Planning Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#replenishment-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-activity"></i><span>Stock Replenishment</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="replenishment-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?=site_url();?>me-fg-po-vw">
              <i class="bi bi-circle"></i><span>FG PO Entry</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>fgpo-receiving">
              <i class="bi bi-circle"></i><span>FG Receiving</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>fgpo-inv">
              <i class="bi bi-circle"></i><span>FG Inventory</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>me-rm-purchase-vw">
              <i class="bi bi-circle"></i><span>RM PO Entry</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>rm-receiving">
              <i class="bi bi-circle"></i><span>RM Receiving</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>rm-inv">
              <i class="bi bi-circle"></i><span>RM Inventory</span>
            </a>
          </li>
        </ul>
      </li> <!-- End Replenishment Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#wip-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-inboxes"></i><span>Work in Process</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="wip-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?=site_url();?>me-rm-req-vw">
              <i class="bi bi-circle"></i><span>RMAP Request</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>rm-outbound">
              <i class="bi bi-circle"></i><span>RM Outbound</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>rm-outbound-2">
              <i class="bi bi-circle"></i><span>RM Outbound v2</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>rm-prod">
              <i class="bi bi-circle"></i><span>RM Production</span>
            </a>
          </li>
        </ul>
      </li> <!-- End WIP Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#packing-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-truck"></i><span>Packing and Shipping</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="packing-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?=site_url();?>me-fg-prod-vw">
              <i class="bi bi-circle"></i><span>FG Production Entry</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>me-fgp-rcvng-vw">
              <i class="bi bi-circle"></i><span>FG Packed Receiving</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>fgp-inv">
              <i class="bi bi-circle"></i><span>FG Packed Inventory</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>fgp-out">
              <i class="bi bi-circle"></i><span>FG Packed Outgoing</span>
            </a>
          </li>
          <li>
            <a href="<?=site_url();?>alex-route">
              <i class="bi bi-circle"></i><span>GO to alex</span>
            </a>
          </li>
        </ul>
      </li> <!-- End Packing Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#masterdata-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-clipboard-data"></i><span>Masterdata</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="masterdata-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?=site_url();?>sub-item-masterdata">
              <i class="bi bi-circle"></i><span>Sub Item Masterdata</span>
            </a>
          </li>

        </ul>
      </li> <!-- End Packing Nav -->
    </ul>

  </aside><!-- End Sidebar-->
