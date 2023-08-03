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
                GR Code:
            </div>
            <div class="mt-2 col-lg-8">
                <input type="text" class="form-control form-control-sm input-sm active_grcode_id" data-id="" id="fld_bb_grcode" name="fld_bb_grcode" value="" required/>
                <input type="hidden" class="form-control form-control-sm " id="mtkn_bb_active_grcode_id" name="mtkn_bb_active_grcode_id" value="" required/>
            </div>
                <div class="mt-2 col-lg-3">
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
            </div>
            <div class="mt-2 col-lg-3">
                GR Date range:
            </div>
            <div class="mt-2 col-lg-4">
                <div class="input-group"> 
                    <div class="input-group-text p-0 px-2"> <span class="bi bi-calendar text-dgreen"> </span> </div>
                    <input type="text" class="form_datetime form-control form-control-sm input-sm" data-id="" id="fld_bb_grdtfrm" name="fld_bb_grdtfrm" value="" required placeholder="From" />
                </div>
            </div>
            <div class="mt-2 col-lg-4">
                <div class="input-group"> 
                    <div class="input-group-text p-0 px-2"> <span class="bi bi-calendar text-dgreen"> </span> </div>
                    <input type="text" class="form_datetime form-control form-control-sm input-sm" data-id="" id="fld_bb_grdtto" name="fld_bb_grdtto" value="" required placeholder="To"/>
                </div>
               
            </div>
        </div>
        <div class="mt-2 col-md-4 offset-lg-3">
            <button class="btn btn-success btn-sm  mx-1"  id="submit_btn_grdate" type="submit">Process</button>
            <button class="btn btn-dgreen-ol btn-sm"  id="refresh-grcode" type="button"> <i class="bi bi-arrow-repeat"> </i> </button>
        </div>
		
	</div> <!-- end col-md-6 -->
	
</div>
<div class="row">
	<div id="mymodoutrecs_bb" ></div>
</div>
<script type="text/javascript"> 
    active_plant();
    active_wshe();

     $('#refresh-grcode').on('click',function(){
        mywg_gr_barcde();
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
	
    $('#submit_btn_grdate').click(function() { 
			try { 	
				var fld_bb_grdtfrm = $('#fld_bb_grdtfrm').val();
				var fld_bb_grdtto = $('#fld_bb_grdtto').val();

				var fld_bb_grcode = $('#fld_bb_grcode').val();
				var fld_bb_plant = $('#fld_bb_plant').attr('data-id');
				var fld_bb_wshe = $('#fld_bb_wshe').attr('data-id');
				
				   __mysys_apps.mepreloader('mepreloaderme',true);
				
				var mparam ={
					fld_bb_grdtfrm: fld_bb_grdtfrm,
					fld_bb_grdtto:fld_bb_grdtto,
					fld_bb_grcode: fld_bb_grcode,
					fld_bb_plant: fld_bb_plant,
					fld_bb_wshe: fld_bb_wshe,
					mpages: 1

				}
				
				jQuery.ajax({ // default declaration of ajax parameters
					url: '<?=site_url()?>gr-boxbarcode-proc',
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
						jQuery('#mymodoutrecs_bb').html(data);
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


    jQuery('.active_grcode_id' ) 
                
                // don't navigate away from the field on tab when selecting an item
                .bind( 'keypress', function( event ) {
                    
                        if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                        jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                            event.preventDefault();
                        }
                        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
                            event.preventDefault();
                        }
                    
                        if( event.keyCode === jQuery.ui.keyCode.BACKSPACE) {
                            return false;
                        }
                        var regex = new RegExp("^[a-zA-Z0-9\b]+$");
                        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                        if (!regex.test(key)) {
                           event.preventDefault();
                           return false;
                        }
                    
                    
                })

               
                .autocomplete({
                    minLength: 0,
                    source: '<?=site_url();?>get-grcode/',
                    focus: function() {
                // prevent value inserted on focus
                return false;
                },
                search: function(oEvent, oUi) { 
                    var sValue = jQuery(oEvent.target).val();
                    var wshe = jQuery('#fld_bb_wshe').attr("data-id");
                    jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-grcode/?mtkn_wshe=' + wshe); 
                },
                select: function( event, ui ) {

                    var terms = ui.item.value;
                    jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
                    jQuery('#' + this.id).attr('title', jQuery.trim(terms));
                    jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));
                    var wshe_id = ui.item.mtkn_rid;
                    jQuery('#fld_bb_grcode').attr("data-id",wshe_id);
                    jQuery('#mtkn_bb_active_grcode_id').val(wshe_id);
                    

                    this.value = ui.item.value; 
                   return false;
                }
                })
                .click(function() { 
                //jQuery(this).keydown(); 
                var terms = this.value.split('|');
                //jQuery(this).autocomplete('search', '');
                jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
                });
                // *****//end plant	
	

   
	
</script>
