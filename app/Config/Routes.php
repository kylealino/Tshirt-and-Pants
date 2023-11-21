<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Mymain');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Mymain::index',['filter' => 'myauthuser']);
$routes->get('dashboard', 'Dashboard::index',['filter' => 'myauthuser']);
$routes->get('mylogin', 'Mylogin::index');
$routes->add('mylogin-auth', 'Mylogin::auth');
$routes->get('melogout', 'Melogout::index');

// cross docking
$routes->add('whcrossing', 'WhCrossing::index',['filter' => 'myauthuser']);
$routes->add('whcrossing-plrecs', 'WhCrossing::whcrossing_pl_recs',['filter' => 'myauthuser']);
$routes->post('whcrossing-recs', 'WhCrossing::whcrossing_ent_recs',['filter' => 'myauthuser']);
$routes->post('whcrossing_recs', 'WhCrossing::whcrossing_ent_recs',['filter' => 'myauthuser']);
$routes->add('whcrossing-sv', 'WhCrossing::agpo_printsv',['filter' => 'myauthuser']);
$routes->add('whcrossing_sv', 'WhCrossing::agpo_printsv',['filter' => 'myauthuser']);
$routes->add('mycrossing-print', 'WhCrossing::mycrossing_print',['filter' => 'myauthuser']);
$routes->add('mycrossing-print-mkg', 'WhCrossing::mycrossing_print_mkg',['filter' => 'myauthuser']);
$routes->add('mycrossing-irrprint', 'WhCrossing::mycrossing_irrprint',['filter' => 'myauthuser']);
$routes->add('mycrossing-irrprintmkg', 'WhCrossing::mycrossing_irrprintmkg',['filter' => 'myauthuser']);
$routes->add('mycrossing-wrrprint', 'WhCrossing::mycrossing_wrrprint',['filter' => 'myauthuser']);
$routes->get('pdf', 'WhCrossing::pdf');
$routes->post('warehouse-alloc-report', 'WhCrossing::mycrossing_alloc_report',['filter' => 'myauthuser']);
$routes->post('warehouse-alloc-report-dl', 'WhCrossing::mycrossing_alloc_report_download',['filter' => 'myauthuser']);
$routes->get('get-cdatrx-list','WhCrossing::cdatrx_vw');
$routes->get('get-drpack-list','WhCrossing::drpacklist_vw');
$routes->get('/whcrossing-sd','WhCrossing::whcrossing_sd',['filter' => 'myauthuser']);
$routes->get('/whcrossing-out','WhCrossing::whcrossing_out',['filter' => 'myauthuser']);
$routes->post('/whcrossing-outpl-recs','WhCrossing::whcrossing_outpl_recs',['filter' => 'myauthuser']);
$routes->post('mycrossing-reversal', 'WhCrossing::whcrossing_reversal_vw');
$routes->post('mycrossing-reversal-recs', 'WhCrossing::whcrossing_reversal_recs');
$routes->post('mycrossing-revert', 'WhCrossing::whcrossing_revert');

//warehouse receiving
$routes->get('/warehouse-rcvng','Warehouse_rcvng::index',['filter' => 'myauthuser']);
$routes->get('/warehouse_rcvng','Warehouse_rcvng::index',['filter' => 'myauthuser']);
$routes->post('/warehouse-rcvng-upld','Warehouse_rcvng::wshe_rvng_upld',['filter' => 'myauthuser']);
$routes->post('/warehouse-rcvng-sv','Warehouse_rcvng::wshe_rvng_save',['filter' => 'myauthuser']);
$routes->post('/warehouse-rcvng-recs','Warehouse_rcvng::whcdrcvng_ent_recs',['filter' => 'myauthuser']);
$routes->post('/warehouse-rcvng-item-recs','Warehouse_rcvng::whcdrcvng_ent_itm_recs',['filter' => 'myauthuser']);
$routes->get('/get-recs','Warehouse_rcvng::get_recs',['filter' => 'myauthuser']);

//warehouse inventory
$routes->get('/warehouse-inv','Warehouse_inv::index',['filter' => 'myauthuser']);
$routes->get('/warehouse_inv','Warehouse_inv::index',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-item-recs','Warehouse_inv::whcdinv_itm_recs',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-item-recs-vw','Warehouse_inv::whcdinv_itm_recs_vw',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-api','Warehouse_inv::whcdinv_items_api2',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-box-content','Warehouse_inv::whcdinv_box_content',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-reports','Warehouse_inv::whcdinv_report_show',['filter' => 'myauthuser']);
$routes->post('/whinv-generate-report','Warehouse_inv::generate_report',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-incoming','Warehouse_inv::whcdinv_incoming',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-outbound','Warehouse_inv::whcdinv_outbound',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-rackbintrans','Warehouse_inv::whcdinv_rackbintrans',['filter' => 'myauthuser']);
$routes->get('/warehouse-inv-getbarcodes','Warehouse_inv::get_barcode_inv',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-save-transfer','Warehouse_inv::whcdinv_save_transfer',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-transfer-recs','Warehouse_inv::whcdinv_transfer_recs',['filter' => 'myauthuser']);
$routes->get('/warehouse-inv-transfer-print','Warehouse_inv::whcdinv_transfer_print',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-rackbintrans-upload','Warehouse_inv::whcdinv_transfer_upload',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-rackbintrans-upload-recs','Warehouse_inv::whcdinv_transfer_upload_recs',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-rackbintrans-upload-sv','Warehouse_inv::whcdinv_transfer_upload_sv',['filter' => 'myauthuser']);
$routes->get('/warehouse-inv-transfer','Warehouse_inv::whcdinv_tranfer_vw',['filter' => 'myauthuser']);
$routes->get('/show','Warehouse_inv::show',['filter' => 'myauthuser']);
$routes->post('/datatables','Warehouse_inv::datatables',['filter' => 'myauthuser']);

//warehouse  box barcode transfer
$routes->get('warehouse-barcode-transfer','Warehouse_barcde_transfr::index',['filter' => 'myauthuser']);
$routes->post('warehouse-barcode-trans-ent','Warehouse_barcde_transfr::whcdbb_barcodetrans',['filter' => 'myauthuser']);
$routes->post('warehouse-barcode-trans-save','Warehouse_barcde_transfr::whcdbb_save_transfer',['filter' => 'myauthuser']);
$routes->post('warehouse-barcode-trans-recs','Warehouse_barcde_transfr::whcdbb_transfer_recs',['filter' => 'myauthuser']);
$routes->get('warehouse-barcode-trans-print','Warehouse_barcde_transfr::whcdbb_transfer_print',['filter' => 'myauthuser']);
$routes->post('warehouse-barcode-trans-upload','Warehouse_barcde_transfr::whcdbb_transfer_upload',['filter' => 'myauthuser']);
$routes->post('warehouse-barcode-trans-upload-recs','Warehouse_barcde_transfr::whcdbb_transfer_upload_recs',['filter' => 'myauthuser']);
$routes->post('warehouse-barcode-trans-upload-sv','Warehouse_barcde_transfr::whcdbb_transfer_upload_sv',['filter' => 'myauthuser']);

//Warehouse Outgoing
$routes->get('/warehouse-out','Warehouse_out::index',['filter' => 'myauthuser']);
$routes->post('/warehouse-out-recs','Warehouse_out::whcdout_ent_recs',['filter' => 'myauthuser']);
$routes->post('/warehouse-out-recs-vw','Warehouse_out::whcdout_ent_recs_vw',['filter' => 'myauthuser']);
$routes->post('/warehouse-inv-box-content','Warehouse_out::whcdinv_box_content',['filter' => 'myauthuser']);
$routes->post('/warehouse-out-upld','Warehouse_out::wshe_out_upld',['filter' => 'myauthuser']);
$routes->post('/warehouse-out-sv','Warehouse_out::wshe_out_save',['filter' => 'myauthuser']);
$routes->add('warehouse-out-hd-update','Warehouse_out::wshe_out_hd_update',['filter' => 'myauthuser']);
$routes->add('warehouse-out-hd-updt','Warehouse_out::wshe_out_hd_updt',['filter' => 'myauthuser']);
$routes->post('/warehouse-out-updt','Warehouse_out::wshe_out_update',['filter' => 'myauthuser']);
$routes->post('/warehouse-out-done','Warehouse_out::wshe_out_done',['filter' => 'myauthuser']);
$routes->add('/warehouse-out-print', 'Warehouse_out::mywhout_print',['filter' => 'myauthuser']);
$routes->add('/warehouse-out-fprint', 'Warehouse_out::mywhout_fprint',['filter' => 'myauthuser']);
$routes->add('/warehouse-out-print-mkg', 'Warehouse_out::mywhout_print_mkg',['filter' => 'myauthuser']);
$routes->add('/warehouse-out-fprint-mkg', 'Warehouse_out::mywhout_fprint_mkg',['filter' => 'myauthuser']);
$routes->add('/warehouse-out-backload', 'Warehouse_out::get_backload_item',['filter' => 'myauthuser']);
$routes->post('/warehouse-out-show', 'Warehouse_out::wshe_out_show',['filter' => 'myauthuser']);
$routes->post('/warehouse-out-revert', 'Warehouse_out::wshe_out_revert',['filter' => 'myauthuser']);
$routes->post('/warehouse-out-report', 'Warehouse_out::wshe_out_report',['filter' => 'myauthuser']);
$routes->post('/warehouse-report-dl', 'Warehouse_out::wshe_report_dl',['filter' => 'myauthuser']);

//GR
$routes->get('good-receive','Mytrx_gr::index',['filter' => 'myauthuser']);
$routes->post('gr-auto-addline','Mytrx_gr::auto_add_lines_gr',['filter' => 'myauthuser']);
$routes->post('gr-save','Mytrx_gr::gr_sv',['filter' => 'myauthuser']);
$routes->post('gr-recs','Mytrx_gr::gr_ent_recs',['filter' => 'myauthuser']);
$routes->post('gr-ent-cancel','Mytrx_gr::gr_ent_cancel',['filter' => 'myauthuser']);
$routes->get('gr-print','Mytrx_gr::gr_print',['filter' => 'myauthuser']);
$routes->post('gr-logfile','Mytrx_gr::grlogfile_vw',['filter' => 'myauthuser']);
$routes->post('gr-logfile-recs','Mytrx_gr::grlogfile_recs',['filter' => 'myauthuser']);
$routes->post('gr-summary','Mytrx_gr::gr_summary_wv',['filter' => 'myauthuser']);
$routes->post('gr-summary-dl','Mytrx_gr::gr_summary_dl',['filter' => 'myauthuser']);
$routes->post('gr-boxbarcode','Mytrx_gr::gr_boxbarcode_vw',['filter' => 'myauthuser']);
$routes->post('gr-boxbarcode-proc','Mytrx_gr::gr_bcode_proc',['filter' => 'myauthuser']);
$routes->post('gr-boxbarcode-dl','Mytrx_gr::gr_bcode_dl',['filter' => 'myauthuser']);
$routes->post('gr-workflow','Mytrx_gr::gr_workflow_vw',['filter' => 'myauthuser']);
$routes->post('gr-workflow-recs','Mytrx_gr::gr_workflow_recs',['filter' => 'myauthuser']);
$routes->post('gr-workflow-aprvd','Mytrx_gr::gr_workflow_aprvd',['filter' => 'myauthuser']);
$routes->post('gr-barcode-gen','Mytrx_gr::gr_barcode_generation',['filter' => 'myauthuser']);
$routes->post('gr-addlines-pullout','Mytrx_gr::auto_add_lines_pullout',['filter' => 'myauthuser']);
$routes->add('try','Mytrx_gr::try',['filter' => 'myauthuser']);

//gr receiving
$routes->get('good-receive-rcvng','Mytrx_gr::gr_rcvng_show',['filter' => 'myauthuser']);
$routes->post('good-receive-rcvng-recs','Mytrx_gr::gr_ent_rcvng_recs',['filter' => 'myauthuser']);
$routes->post('good-receive-rcvng-upld','Mytrx_gr::wshe_gr_rvng_upld',['filter' => 'myauthuser']);
$routes->post('good-receive-rcvng-store','Mytrx_gr::wshe_gr_rcvng_save',['filter' => 'myauthuser']);
$routes->post('good-receive-rcvng-items','Mytrx_gr::wshe_gr_rcvng_itm_recs',['filter' => 'myauthuser']);

//GI Entry
$routes->get('gi-entry','Mytrx_gi::index',['filter' => 'myauthuser']);
$routes->post('gi-entry-recs','Mytrx_gi::gi_ent_recs',['filter' => 'myauthuser']);
$routes->post('gi-entry-upld','Mytrx_gi::gi_ent_upld',['filter' => 'myauthuser']);
$routes->post('gi-entry-store','Mytrx_gi::gi_ent_save',['filter' => 'myauthuser']);
$routes->post('gi-entry-items','Mytrx_gi::gi_ent_itm_recs',['filter' => 'myauthuser']);
$routes->post('gi-approval-vw','Mytrx_gi::gi_approval_vw',['filter' => 'myauthuser']);
$routes->post('gi-approval-recs','Mytrx_gi::gi_approval_recs',['filter' => 'myauthuser']);
$routes->post('gi-approval-sv','Mytrx_gi::gi_approval_sv',['filter' => 'myauthuser']);
$routes->get('gi-print-show','Mytrx_gi::gi_print',['filter' => 'myauthuser']);

//Mysearchdata
$routes->get('/get-plant-list','Mysearchdata::get_userplant_access_dropdown',['filter' => 'myauthuser']);
$routes->get('/get-warehouse-list','Mysearchdata::get_userwrhse_access_dropdown',['filter' => 'myauthuser']);
$routes->get('/get-cdwarehouse-list','Mysearchdata::get_user_cdwrhse_access_dropdown',['filter' => 'myauthuser']); //
$routes->get('/get-warehouse-group','Mysearchdata::myget_warehouse_group_list',['filter' => 'myauthuser']); //
$routes->get('/get-warehouse-sbin','Mysearchdata::myget_warehouse_bin_list',['filter' => 'myauthuser']); //
$routes->get('/get-catg1hd','Mysearchdata::catg_1hd_vw',['filter' => 'myauthuser']);
$routes->get('/get-catg1dt','Mysearchdata::catg_1dt_vw',['filter' => 'myauthuser']); //
$routes->get('/get-catg2','Mysearchdata::catg_2_vw',['filter' => 'myauthuser']); //
$routes->get('/get-catg3','Mysearchdata::catg_3_vw',['filter' => 'myauthuser']); //
$routes->get('/get-catg4','Mysearchdata::catg_4_vw',['filter' => 'myauthuser']); //
$routes->get('/get-article','Mysearchdata::mat_article_gr',['filter' => 'myauthuser']); //
$routes->get('/get-article-asstd','Mysearchdata::mat_article_asstd',['filter' => 'myauthuser']); //
$routes->get('/get-article-reg','Mysearchdata::mat_article_reg',['filter' => 'myauthuser']); //
$routes->get('/get-companies','Mysearchdata::company_search_v',['filter' => 'myauthuser']); //
$routes->get('/get-grcode','Mysearchdata::gr_code',['filter' => 'myauthuser']); //


//crossdocking only
$routes->get('/get-branch-list','Mysearchdata::companybranch_v',['filter' => 'myauthuser']); //crossdocking only

//PO entry GWEMC
$routes->get('get-vendor','Mysearchdata::vendor_po',['filter' => 'myauthuser']); //
$routes->get('get-poclass','Mysearchdata::vendor_poclass',['filter' => 'myauthuser']); //
$routes->get('get-customer','Mysearchdata::vendor_customer',['filter' => 'myauthuser']); //
$routes->get('get-itemc','Mysearchdata::mat_article_fgpo',['filter' => 'myauthuser']); //
$routes->get('get-branch-group','Mysearchdata::companyBranch_bgrp',['filter' => 'myauthuser']); //
$routes->add('me-auto-addlines-po','Mysearchdata::auto_add_lines_po',['filter' => 'myauthuser']); //
$routes->get('get-warehouse-group-v','Mysearchdata::myget_warehouse_group_list_v2',['filter' => 'myauthuser']); //
$routes->get('get-itemc-rm','Mysearchdata::mat_article_rm',['filter' => 'myauthuser']); //

//GWEMC FG PO

$routes->get('me-fg-po-vw', 'Md_purchase::index',['filter' => 'myauthuser']);
$routes->add('me-fg-purchase-save', 'Md_purchase::fgpurchase_save',['filter' => 'myauthuser']);
$routes->add('me-purchase-recs', 'Md_purchase::purchase_recs',['filter' => 'myauthuser']);
$routes->add('me-fg-purchase-view', 'Md_purchase::fgpurchase_vw',['filter' => 'myauthuser']);
$routes->add('me-purchase-print', 'Md_purchase::purchase_print',['filter' => 'myauthuser']);
$routes->add('me-purchase-bar-generate', 'Md_purchase::barcde_gnrtion',['filter' => 'myauthuser']);
$routes->add('me-purchase-appr', 'Md_purchase::purchase_recs_appr',['filter' => 'myauthuser']);
$routes->add('me-purchase-view-appr', 'Md_purchase::purchase_vw_appr',['filter' => 'myauthuser']);
$routes->add('me-purchase-appr-save', 'Md_purchase::purchase_save_appr',['filter' => 'myauthuser']);
$routes->get('me-warehouse-out','Warehouse_out::gw_view_outgoing_sd',['filter' => 'myauthuser']);
$routes->add('me-purchase-barcode-dl', 'Md_purchase::purchase_barcode_dl_proc',['filter' => 'myauthuser']);
$routes->add('me-purchase-print-temp', 'Md_purchase::purchase_print_temp',['filter' => 'myauthuser']);
$routes->get('get-itemc-fg','Mysearchdata::mat_article_fg',['filter' => 'myauthuser']); //
$routes->post('fg-items','Md_purchase::fg_itm_recs',['filter' => 'myauthuser']);
$routes->add('me-fg-purchase-print', 'Md_purchase::fgpurchase_print',['filter' => 'myauthuser']);

//GWEMC-FG PACK
$routes->get('me-fg-packing-vw', 'Fg_packing::index',['filter' => 'myauthuser']);
$routes->add('me-fg-packing-save', 'Fg_packing::fgpack_save',['filter' => 'myauthuser']);
$routes->add('me-fg-packing-recs', 'Fg_packing::fgpack_recs',['filter' => 'myauthuser']);
$routes->add('me-fg-packing-view', 'Fg_packing::fgpack_vw',['filter' => 'myauthuser']);
$routes->add('me-fg-packing-print', 'Fg_packing::fgpack_print',['filter' => 'myauthuser']);
$routes->add('me-fg-packing-bar-generate', 'Fg_packing::barcde_gnrtion',['filter' => 'myauthuser']);
$routes->add('me-fg-packing-appr', 'Fg_packing::fgpack_recs_appr',['filter' => 'myauthuser']);
$routes->add('me-fg-packing-view-appr', 'Fg_packing::fgpack_vw_appr',['filter' => 'myauthuser']);
$routes->add('me-fg-packing-appr-save', 'Fg_packing::fgpack_save_appr',['filter' => 'myauthuser']);
$routes->add('me-fg-packing-barcode-dl', 'Fg_packing::fgpack_barcode_dl_proc',['filter' => 'myauthuser']);
$routes->add('me-fg-packing-print-temp', 'Fg_packing::fgpack_print_temp',['filter' => 'myauthuser']);


//GWEMC RM PO
$routes->get('me-rm-purchase-vw', 'Rm_purchase::index',['filter' => 'myauthuser']);
$routes->add('me-rm-purchase-save', 'Rm_purchase::rmpurchase_save',['filter' => 'myauthuser']);
$routes->add('me-rm-purchase-recs', 'Rm_purchase::rmpurchase_recs',['filter' => 'myauthuser']);
$routes->add('me-rm-purchase-view', 'Rm_purchase::rmpurchase_vw',['filter' => 'myauthuser']);
$routes->add('me-rm-purchase-print', 'Rm_purchase::rmpurchase_print',['filter' => 'myauthuser']);
$routes->add('me-rm-purchase-bar-generate', 'Rm_purchase::barcde_gnrtion',['filter' => 'myauthuser']);
$routes->add('me-rm-purchase-appr', 'Rm_purchase::rmpurchase_recs_appr',['filter' => 'myauthuser']);
$routes->add('me-rm-purchase-view-appr', 'Rm_purchase::rmpurchase_vw_appr',['filter' => 'myauthuser']);
$routes->add('me-rm-purchase-appr-save', 'Rm_purchase::rmpurchase_save_appr',['filter' => 'myauthuser']);
$routes->add('me-rm-purchase-barcode-dl', 'Rm_purchase::rmpurchase_barcode_dl_proc',['filter' => 'myauthuser']);
$routes->add('me-rm-purchase-print-temp', 'Rm_purchase::rmpurchase_print_temp',['filter' => 'myauthuser']);
$routes->post('rm-items','Rm_purchase::rm_itm_recs',['filter' => 'myauthuser']);


//GWEMC-FG PACK
$routes->get('me-rm-request-vw', 'Rm_request::index',['filter' => 'myauthuser']);
$routes->add('me-rm-request-save', 'Rm_request::rmreq_save',['filter' => 'myauthuser']);
$routes->add('me-rm-request-recs', 'Rm_request::rmreq_recs',['filter' => 'myauthuser']);
$routes->add('me-rm-request-view', 'Rm_request::rmreq_vw',['filter' => 'myauthuser']);
$routes->add('me-rm-request-print', 'Rm_request::rmreq_print',['filter' => 'myauthuser']);
$routes->add('me-rm-request-bar-generate', 'Rm_request::barcde_gnrtion',['filter' => 'myauthuser']);
$routes->add('me-rm-request-appr', 'Rm_request::rmreq_recs_appr',['filter' => 'myauthuser']);
$routes->add('me-rm-request-view-appr', 'Rm_request::rmreq_vw_appr',['filter' => 'myauthuser']);
$routes->add('me-rm-request-appr-save', 'Rm_request::rmreq_save_appr',['filter' => 'myauthuser']);
$routes->add('me-rm-request-barcode-dl', 'Rm_request::rmreq_barcode_dl_proc',['filter' => 'myauthuser']);
$routes->add('me-rm-request-print-temp', 'Rm_request::rmreq_print_temp',['filter' => 'myauthuser']);

$routes->add('report', 'Dashboard::reportChart',['filter' => 'myauthuser']);
$routes->get('dashboard-qty', 'Dashboard::dashboard_qty',['filter' => 'myauthuser']);
$routes->get('scroll', 'Dashboard::scroll',['filter' => 'myauthuser']);

//RM RECEIVING
$routes->get('rm-receiving','Rm_rcvng::index',['filter' => 'myauthuser']);
$routes->post('rm-rcvng-recs','Rm_rcvng::rm_ent_rcvng_recs',['filter' => 'myauthuser']);
$routes->post('rm-rcvng-upld','Rm_rcvng::wshe_rm_rvng_upld',['filter' => 'myauthuser']);
$routes->post('rm-rcvng-store','Rm_rcvng::wshe_rm_rcvng_save',['filter' => 'myauthuser']);
$routes->post('rm-rcvng-items','Rm_rcvng::wshe_rm_rcvng_itm_recs',['filter' => 'myauthuser']);

//RM Inventory
$routes->get('rm-inv','Rm_inv::index',['filter' => 'myauthuser']);
$routes->post('rm-inv-item-recs','Rm_inv::rm_itm_recs',['filter' => 'myauthuser']);
$routes->post('rm-inv-api','Rm_inv::rm_items_api2',['filter' => 'myauthuser']);
$routes->post('rm-inv-box-content','Rm_inv::rm_box_content',['filter' => 'myauthuser']);

//RM OUTBOUND
$routes->get('rm-outbound','Rm_outbound::index',['filter' => 'myauthuser']);
$routes->post('rm-out-recs','Rm_outbound::rm_out_recs',['filter' => 'myauthuser']);
$routes->post('rm-out-save','Rm_outbound::rm_out_save',['filter' => 'myauthuser']);
$routes->post('rm-out-items','Rm_outbound::rm_out_itm_recs',['filter' => 'myauthuser']);
$routes->get('rm-outbound-2','Rm_outbound::index_v2',['filter' => 'myauthuser']);
$routes->post('rm-out-vw-process','Rm_outbound::rm_out_vw_process',['filter' => 'myauthuser']);
$routes->post('rm-out-vw-produce','Rm_outbound::rm_out_vw_produce',['filter' => 'myauthuser']);
$routes->post('rm-out-vw-lacking','Rm_outbound::rm_out_vw_lacking',['filter' => 'myauthuser']);
$routes->post('rm-out-req-save','Rm_outbound::rm_out_req_save',['filter' => 'myauthuser']);
$routes->add('rm-out-print', 'Rm_outbound::rm_out_print',['filter' => 'myauthuser']);
$routes->add('fg-out-print', 'Rm_outbound::fg_out_print',['filter' => 'myauthuser']);

// $routes->add('/rm-out-print', 'Rm_outbound::rm_print',['filter' => 'myauthuser']);
// $routes->add('/rm-out-fprint', 'Rm_outbound::rm_fprint',['filter' => 'myauthuser']);
// $routes->add('/rm-out-print-mkg', 'Rm_outbound::rm_print_mkg',['filter' => 'myauthuser']);
// $routes->add('/rm-out-fprint-mkg', 'Rm_outbound::rm_fprint_mkg',['filter' => 'myauthuser']);
// $routes->add('/rm-out-backload', 'Rm_outbound::rm_backload_item',['filter' => 'myauthuser']);
// $routes->post('/rm-out-show', 'Rm_outbound::rm_out_show',['filter' => 'myauthuser']);

//FG PO RECEIVING
$routes->get('fgpo-receiving','FgPo_rcvng::index',['filter' => 'myauthuser']);
$routes->post('fgpo-rcvng-recs','FgPo_rcvng::fg_ent_rcvng_recs',['filter' => 'myauthuser']);
$routes->post('fgpo-rcvng-upld','FgPo_rcvng::wshe_fg_rvng_upld',['filter' => 'myauthuser']);
$routes->post('fgpo-rcvng-store','FgPo_rcvng::wshe_fg_rcvng_save',['filter' => 'myauthuser']);
$routes->post('fgpo-rcvng-items','FgPo_rcvng::wshe_fg_rcvng_itm_recs',['filter' => 'myauthuser']);

//FG PO INVENTORY
$routes->get('fgpo-inv','FgPo_inv::index',['filter' => 'myauthuser']);
$routes->post('fgpo-inv-item-recs','FgPo_inv::fgpo_itm_recs',['filter' => 'myauthuser']);
$routes->post('fgpo-inv-api','FgPo_inv::fgpo_items_api2',['filter' => 'myauthuser']);
$routes->post('fgpo-inv-box-FgPo_inv','Rm_inv::fgpo_box_content',['filter' => 'myauthuser']);
$routes->post('fgpo-report','FgPo_inv::fgpo_report_vw',['filter' => 'myauthuser']);
$routes->post('fgpo-report-recs','FgPo_inv::fgpo_report_recs',['filter' => 'myauthuser']);

//RM request
$routes->get('me-rm-req-vw','Rm_request::index',['filter' => 'myauthuser']);
$routes->add('me-rm-req-save', 'Rm_request::rm_req_save',['filter' => 'myauthuser']);
$routes->get('get-rm-fg-code-list','Rm_request::mat_article_fgpo',['filter' => 'myauthuser']);
$routes->add('me-rm-req-view', 'Rm_request::rm_req_vw',['filter' => 'myauthuser']);
$routes->post('rm-req-items','Rm_request::rm_req_itm_recs',['filter' => 'myauthuser']);
$routes->add('rm-req-recs', 'Rm_request::rm_req_recs',['filter' => 'myauthuser']);
$routes->get('search-rmap-subcon','Rm_request::search_rmap_subcon',['filter' => 'myauthuser']);
$routes->post('me-rm-req-process', 'Rm_request::rm_req_process',['filter' => 'myauthuser']);
$routes->post('me-rm-req-process-save', 'Rm_request::rm_req_process_save',['filter' => 'myauthuser']);
$routes->post('me-rm-rec-process-save', 'Rm_request::rm_rec_process_save',['filter' => 'myauthuser']);
$routes->post('me-rm-req-process-update', 'Rm_request::rm_req_process_update',['filter' => 'myauthuser']);
$routes->get('rm-req-rm-print','Rm_request::rm_req_rm_print',['filter' => 'myauthuser']);
$routes->get('rm-req-fg-print','Rm_request::rm_req_fg_print',['filter' => 'myauthuser']);

//Alloc entry
$routes->get('me-tp-alloc-vw','Allocation::index',['filter' => 'myauthuser']);
$routes->add('me-tp-alloc-save', 'Allocation::tpa_save',['filter' => 'myauthuser']);
$routes->add('me-tp-alloc-update', 'Allocation::tpa_update',['filter' => 'myauthuser']);
$routes->get('get-rm-fg-code-list','Allocation::mat_article_fgpo',['filter' => 'myauthuser']);
$routes->add('me-tp-alloc-view', 'Allocation::rm_req_vw',['filter' => 'myauthuser']);
$routes->post('tp-alloc-items','Allocation::rm_req_itm_recs',['filter' => 'myauthuser']);
$routes->add('tp-alloc-recs', 'Allocation::rm_req_recs',['filter' => 'myauthuser']);
$routes->get('search-tpa-branch','Allocation::search_tpa_branch',['filter' => 'myauthuser']);
$routes->post('tp-alloc-req-view','Allocation::req_vw',['filter' => 'myauthuser']);
$routes->get('tpa-print','Allocation::tpa_print',['filter' => 'myauthuser']);

//Fg pack request
$routes->get('me-fgpack-req-vw','Fgpack_request::index',['filter' => 'myauthuser']);
$routes->add('me-fgpack-req-save', 'Fgpack_request::fgpack_req_save',['filter' => 'myauthuser']);
$routes->get('get-fgpack-fg-code-list','Fgpack_request::mat_article_fgpo',['filter' => 'myauthuser']);
$routes->add('me-fgpack-req-view', 'Fgpack_request::fgpack_req_vw',['filter' => 'myauthuser']);
$routes->post('fgpack-req-items','Fgpack_request::fgpack_req_itm_recs',['filter' => 'myauthuser']);
$routes->add('fgpack-req-recs', 'Fgpack_request::fgpack_req_recs',['filter' => 'myauthuser']);
$routes->add('refresh', 'Fgpack_request::refreshPage/$1');

//FG prod 
$routes->get('me-fg-prod-vw', 'Fg_prod::index',['filter' => 'myauthuser']);
$routes->add('me-fg-prod-view', 'Fg_prod::fg_prod_recs',['filter' => 'myauthuser']);
$routes->add('me-fg-prod-save', 'Fg_prod::fg_prod_save',['filter' => 'myauthuser']);
$routes->post('fg-prod-barcode-gen','Fg_prod::fg_prod_barcode_generation',['filter' => 'myauthuser']);
$routes->get('fg-prod-print','Fg_prod::fg_prod_print',['filter' => 'myauthuser']);
$routes->get('fg-prod-bom-print','Fg_prod::fg_prod_bom_print',['filter' => 'myauthuser']);
$routes->post('fg-prod-boxbarcode-dl','Fg_prod::fg_prod_bcode_dl',['filter' => 'myauthuser']);

//FG PO RECEIVING
$routes->get('fgpo-receiving','FgPo_rcvng::index',['filter' => 'myauthuser']);
$routes->post('fgpo-rcvng-recs','FgPo_rcvng::fg_ent_rcvng_recs',['filter' => 'myauthuser']);
$routes->post('fgpo-rcvng-upld','FgPo_rcvng::wshe_fg_rvng_upld',['filter' => 'myauthuser']);
$routes->post('fgpo-rcvng-store','FgPo_rcvng::wshe_fg_rcvng_save',['filter' => 'myauthuser']);
$routes->post('fgpo-rcvng-items','FgPo_rcvng::wshe_fg_rcvng_itm_recs',['filter' => 'myauthuser']);

//FG PO INVENTORY
$routes->get('fgpo-inv','FgPo_inv::index',['filter' => 'myauthuser']);
$routes->post('fgpo-inv-item-recs','FgPo_inv::fgpo_itm_recs',['filter' => 'myauthuser']);
$routes->post('fgpo-inv-api','FgPo_inv::fgpo_items_api2',['filter' => 'myauthuser']);
$routes->post('fgpo-inv-box-FgPo_inv','Rm_inv::fgpo_box_content',['filter' => 'myauthuser']);

//FG Packed RECEIVING
$routes->get('me-fgp-rcvng-vw','Fgpack_rcvng::index',['filter' => 'myauthuser']);
$routes->post('fgp-rcvng-recs','Fgpack_rcvng::fg_ent_rcvng_recs',['filter' => 'myauthuser']);
$routes->post('fgp-rcvng-upld','Fgpack_rcvng::wshe_fg_rvng_upld',['filter' => 'myauthuser']);
$routes->post('fgp-rcvng-store','Fgpack_rcvng::wshe_fg_rcvng_save',['filter' => 'myauthuser']);
$routes->post('fgp-rcvng-items','Fgpack_rcvng::wshe_fg_rcvng_itm_recs',['filter' => 'myauthuser']);

//FGP INVENTORY
$routes->get('fgp-inv','Fgp_inv::index',['filter' => 'myauthuser']);
$routes->post('fgp-inv-box-content','Fgp_inv::fgpinv_box_content',['filter' => 'myauthuser']);


            
//ALEX CONTROLLER
$routes->get('test-alex','Alex_Controller::index',['filter' => 'myauthuser']);
$routes->post('alex-save','Alex_Controller::alex_saving',['filter' => 'myauthuser']);
//FGP Outgoing
$routes->get('fgp-out','Fgp_out::index',['filter' => 'myauthuser']);
$routes->post('fgp-out-recs','Fgp_out::fgp_out_recs',['filter' => 'myauthuser']);
$routes->post('/fgp-out-upld','Fgp_out::wshe_out_upld',['filter' => 'myauthuser']);
$routes->post('fgp-out-sv','Fgp_out::fgp_out_save',['filter' => 'myauthuser']);
$routes->add('fgp-out-hd-update','Fgp_out::wshe_out_hd_update',['filter' => 'myauthuser']);
$routes->add('fgp-out-hd-updt','Fgp_out::fgp_out_hd_updt',['filter' => 'myauthuser']);
$routes->post('fgp-out-updt','Fgp_out::fgp_out_update',['filter' => 'myauthuser']);
$routes->post('fgp-out-done','Fgp_out::fgp_out_done',['filter' => 'myauthuser']);
$routes->add('fgp-out-print', 'Fgp_out::fgp_out_print',['filter' => 'myauthuser']);
$routes->add('fgp-out-fprint', 'Fgp_out::fgp_out_fprint',['filter' => 'myauthuser']);
$routes->add('fgp-out-print-mkg', 'Fgp_out::fgp_out_print_mkg',['filter' => 'myauthuser']);
$routes->add('/fgp-out-fprint-mkg', 'Fgp_out::mywhout_fprint_mkg',['filter' => 'myauthuser']);
$routes->add('fgp-out-backload', 'Fgp_out::fgp_out_backload_item',['filter' => 'myauthuser']);
$routes->post('fgp-out-show', 'Fgp_out::fgp_out_show',['filter' => 'myauthuser']);
$routes->post('fgp-out-revert', 'Fgp_out::fgp_out_revert',['filter' => 'myauthuser']);
$routes->post('/fgp-out-report', 'Fgp_out::wshe_out_report',['filter' => 'myauthuser']);
$routes->post('/fgp-report-dl', 'Fgp_out::wshe_report_dl',['filter' => 'myauthuser']);

//Item components creation
$routes->get('me-item-comp-vw','Item_comp::index',['filter' => 'myauthuser']);
$routes->get('me-item-comp-vw-2','Item_comp::index_v2',['filter' => 'myauthuser']);
$routes->get('get-fg-code-list','Item_comp::mat_article_fgpo',['filter' => 'myauthuser']);
$routes->get('get-rm-btn-code-list','Item_comp::mat_article_btn',['filter' => 'myauthuser']); 
$routes->get('get-rm-plastic-bag-code-list','Item_comp::mat_article_plastic_bag',['filter' => 'myauthuser']);
$routes->get('get-rm-inside-garter-code-list','Item_comp::mat_article_inside_garter',['filter' => 'myauthuser']);
$routes->get('get-rm-rivets-code-list','Item_comp::mat_article_rivets',['filter' => 'myauthuser']);
$routes->get('get-rm-zipper-code-list','Item_comp::mat_article_zipper',['filter' => 'myauthuser']);
$routes->get('get-rm-fabric-code-list','Item_comp::mat_article_fabric',['filter' => 'myauthuser']);
$routes->get('get-rm-lining-code-list','Item_comp::mat_article_lining',['filter' => 'myauthuser']);
$routes->get('get-rm-leather-patch-code-list','Item_comp::mat_article_leather_patch',['filter' => 'myauthuser']);
$routes->get('get-rm-hangtag-code-list','Item_comp::mat_article_hangtag',['filter' => 'myauthuser']);
$routes->get('get-rm-side-lbl-code-list','Item_comp::mat_article_side_lbl',['filter' => 'myauthuser']); 
$routes->get('get-rm-size-care-lbl-code-list','Item_comp::mat_article_size_care_lbl',['filter' => 'myauthuser']); 
$routes->get('get-rm-kids-lbl-code-list','Item_comp::mat_article_kids_lbl',['filter' => 'myauthuser']);
$routes->get('get-rm-kids-side-lbl-code-list','Item_comp::mat_article_kids_side_lbl',['filter' => 'myauthuser']); 
$routes->get('get-rm-size-lbl-code-list','Item_comp::mat_article_size_lbl',['filter' => 'myauthuser']);
$routes->get('get-rm-barcode-code-list','Item_comp::mat_article_barcode',['filter' => 'myauthuser']);
$routes->get('get-rm-tagpin-code-list','Item_comp::mat_article_tagpin',['filter' => 'myauthuser']);
$routes->get('get-rm-chip-board-code-list','Item_comp::mat_article_chipboard',['filter' => 'myauthuser']);
$routes->add('me-item-comp-save', 'Item_comp::item_comp_save',['filter' => 'myauthuser']);
$routes->post('item-comp-recs','Item_comp::item_comp_recs',['filter' => 'myauthuser']);
$routes->add('me-item-comp-update', 'Item_comp::item_comp_update',['filter' => 'myauthuser']);
$routes->add('me-item-comp-save-2', 'Item_comp::item_comp_save_2',['filter' => 'myauthuser']);
$routes->post('item-comp-recs-2','Item_comp::item_comp_recs_2',['filter' => 'myauthuser']);
$routes->post('item-comp-upld','Item_comp::item_comp_upld',['filter' => 'myauthuser']);
$routes->add('me-item-comp-upld-save-2', 'Item_comp::item_comp_upld_save_2',['filter' => 'myauthuser']);
$routes->get('get-rm-add-line','Item_comp::mat_article_rm',['filter' => 'myauthuser']); 
$routes->add('me-item-comp-update-2', 'Item_comp::item_comp_update_2',['filter' => 'myauthuser']);

//Production Planning
$routes->get('me-prod-plan','Prod_plan::index',['filter' => 'myauthuser']);
$routes->post('prod-plan-upld','Prod_plan::prod_plan_upld',['filter' => 'myauthuser']);
$routes->get('search-prod-plan-branch','Prod_plan::search_prod_plan_branch',['filter' => 'myauthuser']);
$routes->post('me-prod-plan-save','Prod_plan::prod_plan_save',['filter' => 'myauthuser']);
$routes->post('me-prod-plan-view','Prod_plan::prod_plan_vw',['filter' => 'myauthuser']);
$routes->post('prod-plan-items','Prod_plan::prod_plan_itm_recs',['filter' => 'myauthuser']);
$routes->post('prod-plan-delete','Prod_plan::prod_plan_delete',['filter' => 'myauthuser']);

//Standard Capacity
$routes->get('me-standard-cap','Standard_cap::index',['filter' => 'myauthuser']);
$routes->get('search-standard-cap-branch','Standard_cap::search_standard_cap_branch',['filter' => 'myauthuser']);
$routes->post('standard-cap-upld','Standard_cap::standard_cap_upld',['filter' => 'myauthuser']);
$routes->post('me-standard-cap-save','Standard_cap::standard_cap_save',['filter' => 'myauthuser']);
$routes->post('me-standard-cap-view','Standard_cap::standard_cap_vw',['filter' => 'myauthuser']);
$routes->post('me-standard-cap-items','Standard_cap::standard_cap_itm_recs',['filter' => 'myauthuser']);
$routes->post('me-standard-cap-view-list','Standard_cap::standard_cap_vw_list',['filter' => 'myauthuser']);
$routes->post('me-standard-cap-items-list','Standard_cap::standard_cap_itm_recs_list',['filter' => 'myauthuser']);
$routes->post('me-standard-cap-update','Standard_cap::standard_cap_update',['filter' => 'myauthuser']);

//RM Production
$routes->get('rm-prod','Rm_production::index',['filter' => 'myauthuser']);
$routes->post('rm-prod-recs','Rm_production::rm_prod_recs',['filter' => 'myauthuser']);
$routes->post('rm-prod-save','Rm_production::rm_prod_save',['filter' => 'myauthuser']);

//Sub Item Masterdata 
$routes->get('sub-item-masterdata','Sub_masterdata::index',['filter' => 'myauthuser']);
$routes->post('sub-items-recs','Sub_masterdata::sub_item_recs',['filter' => 'myauthuser']);
$routes->post('sub-items-save','Sub_masterdata::sub_item_save',['filter' => 'myauthuser']);
$routes->get('get-main-itemc','Sub_masterdata::get_main_itemc',['filter' => 'myauthuser']);
$routes->get('get-uom','Sub_masterdata::get_uom',['filter' => 'myauthuser']);
$routes->post('sub-items-update','Sub_masterdata::sub_item_update',['filter' => 'myauthuser']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}