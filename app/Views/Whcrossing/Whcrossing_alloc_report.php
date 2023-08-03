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
$mtkn_active_cdatrx = '';
$mtkn_active_drlist = '';
$txtmonths= '';
$txtyears= '';
$txtdf_tag= '';
$txtpout_rson = '';
?>
<div class="row mt-2">
	<div class="col-lg-9  rounded p-4">
		<div class="row form-group">
            <div class="mt-2 col-lg-3">
                Transaction No:
            </div>
            <div class="mt-2 col-lg-8">
                <input type="text" class="form-control form-control-sm input-sm fld_dl_trxno" data-id="" id="fld_dl_trxno" name="fld_dl_trxno" value="" autocomplete="off" required/>
            </div>
                <div class="mt-2 col-lg-3">
                Packing List:
            </div>
            <div class="mt-2 col-lg-8">
                <input type="text" class="form-control form-control-sm input-sm fld_dl_packinglist" data-id="" data-mtkn="<?=$mtkn_active_cdatrx;?>" id="fld_dl_packinglist" name="fld_dl_packinglist" value="" autocomplete="off" required/>
            </div>
            <div class="mt-2 col-lg-3">
                Date range:
            </div>
            <div class="mt-2 col-lg-4">
                <div class="input-group"> 
                    <div class="input-group-text p-0 px-2"> <span class="bi bi-calendar text-dgreen"> </span> </div>
                    <input type="text" class="form_datetime form-control form-control-sm input-sm" data-id="<?=$mtkn_active_drlist;?>" id="fld_log_dtefrom" name="fld_log_dtefrom" value="" required placeholder="From" autocomplete="off" />
                </div>
            </div>
            <div class="mt-2 col-lg-4">
                <div class="input-group"> 
                    <div class="input-group-text p-0 px-2"> <span class="bi bi-calendar text-dgreen"> </span> </div>
                    <input type="text" class="form_datetime form-control form-control-sm input-sm" data-id="" id="fld_log_dteto" name="fld_log_dteto" value="" autocomplete="off" required placeholder="To"/>
                </div>
               
            </div>
            <div class="mt-2 col-lg-3">
            </div>
			<div class="mt-2 col-lg-2">
				<button class="btn btn-dgreen btn-sm"  id="submit_btn_grlog" type="submit"> <span class="bi bi-search"> </span> Process</button>
				<button class="btn btn-dgreen-ol btn-sm"  id="refresh-log" type="button"> <i class="bi bi-arrow-repeat"> </i> </button>
			</div>
		</div>
	
		<div class="form-group row">
		
		</div>
	</div> <!-- end col-md-6 -->
</div>
 <div class="row">
	<div id="mymodout-logrecs" class="container-fluid"></div>
</div>
<script type="text/javascript"> 

	$('#refresh-log').on('click',function(){
		mywg_wh_report();
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
	
	
    $('#submit_btn_grlog').click(function() { 
			try { 	

        var fld_dl_trxno = $('#fld_dl_trxno').val();
        var fld_dl_packlist = $('#fld_dl_packinglist').val();
				var fld_dl_dteto = $('#fld_log_dteto').val();
				var fld_dl_dtefrom = $('#fld_log_dtefrom').val();
			  __mysys_apps.mepreloader('mepreloaderme',true);

				var mparam = {
          fld_dl_trxno: fld_dl_trxno,
          fld_dl_packlist: fld_dl_packlist,
					fld_dl_dtefrom:fld_dl_dtefrom,
					fld_dl_dteto: fld_dl_dteto,
					mpages: 1

				}
				
				jQuery.ajax({ // default declaration of ajax parameters
					url: '<?=site_url()?>warehouse-alloc-report-dl',
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
						jQuery('#mymodout-logrecs').html(data);
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
	
	

    jQuery('.fld_dl_trxno')
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
          source: '<?= site_url(); ?>get-cdatrx-list',
          focus: function() {

                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#fld_dl_trxno').val(terms);
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;
                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));
          
    });

    jQuery('.fld_dl_packinglist')
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
          source: '<?= site_url(); ?>get-drpack-list',
          focus: function() {

                return false;
              },
              search: function(oEvent, oUi) { 
                var sValue = jQuery(oEvent.target).val();
                var cdatrx = jQuery('#fld_dl_trxno').val();

                if(jQuery(oEvent.target).attr("data-type") == 'bb'){
                    cdatrx = jQuery('#fld_dl_trxno').attr("data-id");
                }
                jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-drpack-list?mtkn_cdatrx=' + cdatrx); 
                },
                select: function( event, ui ) {

                    var terms = ui.item.value;
                    jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
                    jQuery('#' + this.id).attr('title', jQuery.trim(terms));
                    jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));
                    var packlist = ui.item.mtkn_rid;
                    jQuery('#' + this.id).attr("data-id",packlist);
                    //jQuery('#mtkn_active_wshe_id').val(wshe_id);

                    this.value = ui.item.value; 
                    return false;
                }
            })
        .click(function() {
            var terms = this.value.split('|');
            jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
          
    });
   
	
</script>
