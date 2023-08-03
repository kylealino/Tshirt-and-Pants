<?php 
/**
*  File        : good_receive/gr_boxbarcode.php
*  Author      : Arnel L. Oquien
*  Date Created: Nov 25,2022
*  last update : Nov 25,2022
*  description : Good receive entry crossdocking
*/

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$db_erp =$mydbname->medb(1);
$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

?>
<div class="row" style="padding: 10px 0px 0px 0px !important;">
	<div class="col-md-8">
		<div class="row form-group">
            <div class="mt-2 col-lg-3">
                Transaction number:
            </div>
            <div class="mt-2 col-lg-8">
                <input type="text" class="form-control form-control-sm input-sm " data-id="" id="fld_trxno_ag" name="fld_trxno_ag" value="" required/>
            </div>
       <!--       <div class="mt-2 col-lg-3">
                Plant Code:
            </div>
            <div class="mt-2 col-lg-8">
                <input type="text" class="form-control form-control-sm input-sm active_plnt_id" data-id="" id="fld_bb_plant" name="fld_bb_plant" value="" required/>
            </div>
                <div class="mt-2 col-lg-3">
                Warehouse Code:
            </div>
            <div class="mt-2 col-lg-8">
                <input type="text" class="form-control form-control-sm input-sm active_wshe_id" data-id="" data-type="bb" id="fld_bb_wshe" name="fld_bb_wshe" value="" required/>
            </div> -->

        </div>
        <div class="mt-2 col-md-4 offset-lg-3">
            <button class="btn btn-success btn-sm  mx-1"  id="submit_btn_reversal" type="submit">Process</button>
            <button class="btn btn-dgreen-ol btn-sm"  id="refresh-grcode" type="button"> <i class="bi bi-arrow-repeat"> </i> </button>
        </div>
		
	</div> <!-- end col-md-6 -->
	
</div>
<div class="row">
	<div id="myreversal-recs" ></div>
</div>
<script type="text/javascript"> 


     $('#refresh-grcode').on('click',function(){
        reversal_view();
    });
     
	$('.form_datetime').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: false,
                autoclose: true,
                format: 'mm/dd/yyyy'
                });

  //  $(".form_datetime").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
	
    $('#submit_btn_reversal').click(function() { 
			try { 	
				var fld_from_ag = $('#fld_from_ag').val();
				var fld_to_ag = $('#fld_to_ag').val();
				var fld_trxno_ag = $('#fld_trxno_ag').val();

				
				   __mysys_apps.mepreloader('mepreloaderme',true);
				
				var mparam ={
					fld_from_ag: fld_from_ag,
					fld_to_ag:fld_to_ag,
					txtsearchedrec: fld_trxno_ag,
					mpages: 1

				}
				
				jQuery.ajax({ // default declaration of ajax parameters
					url: '<?=site_url()?>mycrossing-reversal-recs',
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
						jQuery('#myreversal-recs').html(data);
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
				 __mysys_apps.mepreloader('mepreloaderme',false);
				alert(mtxt);
			} //end try
		});	


   

</script>
