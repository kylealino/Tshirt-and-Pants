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
				<button class="btn btn-dgreen btn-sm"  id="submit_btn_whrep" type="submit"> <span class="bi bi-search"> </span> Download</button>
				<button class="btn btn-dgreen-ol btn-sm"  id="refresh-grsum" type="button"> <i class="bi bi-arrow-repeat"> </i> </button>
			</div>
		</div>

		<div class="form-group row">
		
		</div>
	</div> <!-- end col-md-6 -->
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
	
	
</script>
