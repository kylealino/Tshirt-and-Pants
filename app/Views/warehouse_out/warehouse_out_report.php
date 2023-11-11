<?php 
/**
*  File        : good_receive/gr_summary.php
*  Author      : Arnel L. Oquien
*  Date Created: Nov 25,2022
*  last update : Nov 25,2022
*  description : Good receive entry crossdocking
*/

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mydataz = model('App\Models\MyDatumModel');
$mywhout = model('App\Models\MyWarehouseoutModel');
$db_erp =$mydbname->medb(1);


$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

$txtmonths= '';
$txtyears= '';
$txtdf_tag= '';
$txtpout_rson = '';
?>
<div class="row mt-2">
	<div class="col-lg-9  rounded p-4">
		<div class="row form-group">
			<h5 class="fw-bold">Type</h5>
			<div class="col-sm-3">
				<div class="form-check form-switch">
					<input class="txt-unposted form-check-input" type="radio" name="cb" id="txt-unposted">
					<label class="form-check-label" for="txt-unposted" id="fixedlbl">
						UN-POSTED
					</label>
				</div>
				<div class="form-check form-switch">
					<input class="txt-posted form-check-input" type="radio" name="cb" id="txt-posted">
					<label class="form-check-label" for="txt-posted" id="discountlbl">
						POSTED
					</label>
				</div>
			</div> 
			<div class="col-sm-3">
				<h6 class="card-title p-0">Warehouse:</h6>
				<select id="txt-warehouse" class="form-control form-control-sm" name="txt-warehouse">
					<option value="W1-G1">W1-G1</option>
					<option value="W1-G2">W1-G2</option>
					<option value="W2-G3">W2-G3</option>
					<option value="W2-G4">W2-G4</option>
					<option value="W11-G5">W11-G5</option>
				</select>
			</div>
			<div class="col-sm-3">                         
				<h6 class="card-title p-0">Branch:</h6>
				<input type="text"  placeholder="Branch Name" id="branch-name" name="branch-name" class="branch-name form-control form-control-sm " required/>
			</div> 
		</div>
		<div class="row form-group">
			<h5 class="fw-bold">Select Extraction Report Date</h5>
			<div class="mt-2 col-lg-5 col-md-12">
				<div class="input-group"> 
					<div class="input-group-text p-0 px-2"> <span class="bi bi-calendar text-dgreen"> </span> </div>
				<input type="text" class="form_datetime form-control form-control-sm input-sm" name="fld_report_dtefrom" id="fld_report_dtefrom" placeholder ="From" value="" required autocomplete="off"/>
				</div>
			</div>
			<div class="mt-2 col-lg-5 col-md-12">
				<div class="input-group"> 
					<div class="input-group-text p-0 px-2"> <span class="bi bi-calendar text-dgreen"> </span> </div>
						<input type="text" class="form_datetime form-control form-control-sm input-sm" name="fld_report_dteto" id="fld_report_dteto" value="" placeholder ="To" required autocomplete="off"/>
				</div>
			</div>
			<div class="mt-2 col-lg-2">
			<button class="btn btn-success btn-sm"  id="view_report" type="submit"> <span class="bi bi-eye"> </span> View</button>
				<button class="btn btn-dgreen btn-sm"  id="submit_btn_whrep" type="submit"> <span class="bi bi-search"> </span> Download</button>
				<button class="btn btn-dgreen-ol btn-sm"  id="refresh-grsum" type="button"> <i class="bi bi-arrow-repeat"> </i> </button>
			</div>
		</div>
	</div>
</div>
 <div class="row">
	<div class="col-md-12">
		<div id="view_process_dr" class="container-fluid">
	
		</div>
	</div> <!-- end col-md-12 -->
</div>
<script type="text/javascript">

	$('#refresh-grsum').on('click',function(){
		mywg_gr_summ();
	});
	 
	$('.form_datetime').datepicker({
				showAnim:"fold",
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: false,
                autoclose: true,
                format: 'mm/dd/yyyy'
                });


    $('#submit_btn_whrep').click(function() {

	        try { 
				
				var txt_unposted_value = jQuery('#txt-unposted').prop('checked');;
				var txt_unposted = (txt_unposted_value) ? (1) : (0);
				var txt_posted_value = jQuery('#txt-posted').prop('checked');
				var txt_posted = (txt_posted_value) ? (1) : (0);
				var txt_warehouse = $('#txt-warehouse').val();
				var branch_name = $('#branch-name').val();
               	var fld_report_dteto = $('#fld_report_dteto').val();
				var fld_report_dtefrom = $('#fld_report_dtefrom').val();
                
                if(fld_report_dtefrom > fld_report_dteto){
                	alert('Check your date!');
                	return false;
                }
                if(fld_report_dteto === ''){
                	alert('Date To is required!');
                	return false;
                }
                if(fld_report_dtefrom === ''){
                	alert('Date From is required!');
                	return false;
                }

                 //end for
                var smparam = { 
					txt_unposted:txt_unposted,
					txt_posted:txt_posted,
					txt_warehouse:txt_warehouse,
					branch_name:branch_name,
                   	fld_report_dteto: fld_report_dteto,
					fld_report_dtefrom:fld_report_dtefrom,
                    
                }
                   __mysys_apps.mepreloader('mepreloaderme',true);
                jQuery.ajax({ // default declaration of ajax parameters
                    type: "POST",
                    url: '<?= site_url() ?>warehouse-report-dl',
                    context: document.body,
                    data: eval(smparam),
                    global: false,
                    cache: false,
                    success: function(data)  { //display html using divID 
                           __mysys_apps.mepreloader('mepreloaderme',false);
                       jQuery('#myModSysMsgBod').css({
							display: ''
						});
						jQuery('#view_process_dr').html(data);
                        return false;
                    },
                    error: function(data) { // display global error on the menu function
                        alert('error loading page...');
                        return false;
                    }
                });
            } catch(err) {
                var mtxt = 'There was an error on this page.\n';
                mtxt += 'Error description: ' + err.message;
                mtxt += '\nClick OK to continue.';
                   __mysys_apps.mepreloader('mepreloaderme',false);
                alert(mtxt);
            }  //end try
            return false; 
		});	
	
		jQuery('#branch-name')
		// don't navigate away from the field on tab when selecting an item
		.bind( 'keydown', function( event ) {
			if ( event.keyCode === jQuery.ui.keyCode.TAB &&
				jQuery( this ).data( 'ui-autocomplete' ).menu.active ) { 
				event.preventDefault();
			}
			if( event.keyCode === jQuery.ui.keyCode.TAB ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			minLength: 0,
			source: '<?= site_url(); ?>search-prod-plan-branch/',  //mysearchdata/companybranch_v
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			search: function(oEvent, oUi) {
				var sValue = jQuery(oEvent.target).val();

			},
			select: function( event, ui ) {
				var terms = ui.item.value;
				jQuery('#branch-name').val(terms);
				jQuery(this).autocomplete('search', jQuery.trim(terms));
				return false;
			}
		})
		.click(function() {
			var terms = this.value;
			jQuery(this).autocomplete('search', jQuery.trim(terms));
	});	//end branch-name
</script>
