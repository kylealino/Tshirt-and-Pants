<?php 
/**
*  File        : good_receive/gr_logfile.php
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
	<div class="col-lg-9 col-md-12 rounded p-4">
		<div class="row form-group">
			<h5 class="fw-bold">Select GI date</h5>
			<div class="mt-2 col-lg-5 col-md-12">
				<div class="input-group"> 
					<div class="input-group-text p-0 px-2"> <span class="bi bi-calendar text-dgreen"> </span> </div>
				<input type="text" class="form_datetime form-control form-control-sm input-sm" name="fld_wf_dtefrom" id="fld_wf_dtefrom" placeholder ="From" value="" required autocomplete="off" />
				</div>
			</div>
			<div class="mt-2 col-lg-5 col-md-12">
				<div class="input-group"> 
					<div class="input-group-text p-0 px-2"> <span class="bi bi-calendar text-dgreen"> </span> </div>
						<input type="text" class="form_datetime form-control form-control-sm input-sm" name="fld_wf_dteto" id="fld_wf_dteto" value="" placeholder ="To" required autocomplete="off"/>
				</div>
		
			</div>
			<div class="mt-2 col-lg-2 mt-2 col-md-12">
				<button class="btn btn-dgreen btn-sm"  id="submit_btn_wf" type="submit"> <span class="bi bi-search"> </span> Process</button>
				<button class="btn btn-dgreen-ol btn-sm"  id="refresh-wf" type="button"> <i class="bi bi-arrow-repeat"> </i> </button>
				
			</div>
		</div>

		<div class="form-group row">
		
		</div>
	</div> <!-- end col-md-6 -->
</div>
 <div class="row">
	<div class="col-md-12">
		<div id="mymodout-wfrecs" >
	
		</div>
	</div> <!-- end col-md-12 -->
</div>
<script type="text/javascript"> 

	$('#refresh-wf').on('click',function(){
		mywg_gi_wf();
	});

	$('.form_datetime').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: false,
                autoclose: true,
                format: 'mm/dd/yyyy'
                });

    // $(".form_datetime").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
	
    $('#submit_btn_wf').click(function() { 
			try { 	
				var fld_wf_dteto = $('#fld_wf_dteto').val();
				var fld_wf_dtefrom = $('#fld_wf_dtefrom').val();
			  __mysys_apps.mepreloader('mepreloaderme',true);

				var mparam ={
					fld_wf_dteto: fld_wf_dteto,
					fld_wf_dtefrom:fld_wf_dtefrom,
					mpages: 1

				}
				
				jQuery.ajax({ // default declaration of ajax parameters
					url: '<?=site_url()?>gi-approval-recs',
					method:"POST",
					context:document.body,
					data: eval(mparam),
					global: false,
					cache: false,
					success: function(data)  { //display html using divID
						  __mysys_apps.mepreloader('mepreloaderme',false);
						jQuery('#myModSysMsgBod').css({
							display: ''
						});
						jQuery('#mymodout-wfrecs').html(data);
						//jQuery('#myModSysMsg').modal('show');
						return false;
					},
					error: function() { // display global error on the menu function
						alert('error loading page...');
						  __mysys_apps.mepreloader('mepreloaderme',false);
						return false;
					}	
				});	
			} catch (err) {
				var mtxt = 'There was an error on this page.\n';
				mtxt += 'Error description: ' + err.message;
				mtxt += '\nClick OK to continue.';
				jQuery.hideLoading();
				alert(mtxt);
			} //end try
		});	
	
	

   
	
</script>
