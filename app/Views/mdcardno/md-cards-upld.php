<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$txtupld_type = $request->getVar('meType');
$aupldtype = $mydataz->lk_Card_Tag();

?>
<div class="">
	<div class=" bg-light border col-lg-6 offset-lg-3 offset-md-0 mt-1 p-2">
		<div class="mb-3 row">
			 <label class="col-sm-3 form-label" for="mcustcardno">Select type:</label>
			<div class="col-lg-9">
				<?= $mylibzsys->mypopulist_2($aupldtype,$txtupld_type,'txt_type_upld',' class="form-control form-control-sm" required="required" ','','');?>
			</div>
		</div>
		<div class="mb-3 row">
			<label class="col-sm-3 form-label" for="mcustcardno">Select File:</label>
			<div class="col-md-9 ">
				<input type="file" id="__art_item_simpleupld_sub" class="form-control form-control-sm" name="__art_item_simpleupld_sub" value="Browse Valid .CSV File...">
			</div>
		</div>
		<div class ="mb-3 row">
			<div class  ="d-grid gap-2 col-6 mx-auto">
				<input type ="button" id="__mbtn_artm_format_sub" style="margin:0;" class="btn btn-primary btn-sm" name="__mbtn_artm_format_sub" value="Template Excel File...">
				<input type ="button" id="__mbtn_art_simpleupld_sub" style="margin:0;" class="btn  btn-block btn-success btn-sm" name="__mbtn_art_simpleupld_sub" value="Upload/Process">
			</div>
		</div>
	</div>
</div> <!-- end row -->
<div class="modal fade" id="myModSysMsgSub" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header text-center bg-info">
                <h4 class="modal-title text-white">System Message</h4>
                <!--<small class="font-bold">...</small>-->
            </div>
            <div class="modal-body" id="myModSysMsgSubBod">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	
		$('#__mbtn_art_simpleupld_sub').click(function(){ 
			try { 	

				var type       = jQuery('#txt_type_upld').val(); 
				var file       = $('#__art_item_simpleupld_sub').val();
				
				var meurl  = '';
				
				if($.trim(type) == ''){ 
					jQuery('#myModSysMsgSubBod').css({
						display: ''
					});
					jQuery('#myModSysMsgSubBod').html('Select what type of transaction you want to upload!');
					jQuery('#myModSysMsgSub').modal('show');
					return false;
				}
			
				meurl = '<?=site_url()?>me-card-uplds';
			

				if($.trim(file) == ''){ 
					jQuery('#myModSysMsgSubBod').css({
						display: ''
					});
					jQuery('#myModSysMsgSubBod').html('Please select file to upload!');
					jQuery('#myModSysMsgSub').modal('show');
					return false;
				}

				
				my_data = new FormData();
				my_data.append('__art_item_simpleupld_sub', $('#__art_item_simpleupld_sub')[0].files[0]);
				my_data.append('type', type);
		
				$.ajax({ // default declaration of ajax parameters
					url: meurl,
					method:"POST",
					context:document.body,
					data: my_data,
					contentType: false,
					global: false,
					cache: false,
					processData:false,
					success: function(data)  { //display html using divID
						
						jQuery('#myModSysMsgSubBod').css({
							display: ''
						});
						jQuery('#myModSysMsgSubBod').html(data);
						jQuery('#myModSysMsgSub').modal('show');
						return false;
					},
					error: function() { // display global error on the menu function
						alert('error loading page...');
						
						return false;
					}	
				});	
			} catch (err) {
				var mtxt = 'There was an error on this page.\n';
				mtxt += 'Error description: ' + err.message;
				mtxt += '\nClick OK to continue.';
				
				alert(mtxt);
			} //end try
		});	
		
		$('#__mbtn_artm_format_sub').click(function() { 
			try { 	
				window.location.href = '<?=site_url();?>/downloads/form_templates/ssl_uploading_template.xls';
			} catch (err) {
				var mtxt = 'There was an error on this page.\n';
				mtxt += 'Error description: ' + err.message;
				mtxt += '\nClick OK to continue.';
				$.hideLoading();
				alert(mtxt);
			} //end try
		});	

		$('#__mbtn_artm_video_sub').click(function() { 
			try { 	
				window.location.href = '<?=site_url();?>/downloads/videos/material_csv_conversion.ogv';
			} catch (err) {
				var mtxt = 'There was an error on this page.\n';
				mtxt += 'Error description: ' + err.message;
				mtxt += '\nClick OK to continue.';
				$.hideLoading();
				alert(mtxt);
			} //end try
		});	
	
	jQuery('#fld_pbranch_sub')
                // don't navigate away from the field on tab when selecting an item
                .bind( 'keydown', function( event ) {
                    if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                        jQuery( this ).data( 'autocomplete' ).menu.active ) {
                        event.preventDefault();
                }
                if( event.keyCode === jQuery.ui.keyCode.TAB ) {
                    event.preventDefault();
                }
            })
        .autocomplete({
            minLength: 0,
            source: '<?= site_url(); ?>mysearchdata/companybranch_v/',
            focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                search: function(oEvent, oUi) {
                    var sValue = jQuery(oEvent.target).val();
                    //var comp = jQuery('#fld_Company').val();
                    //var comp = jQuery('#fld_Company').attr("data-id");
                    jQuery(this).autocomplete('option', 'source', '<?=site_url();?>mysearchdata/companybranch_v'); 
                    //jQuery(oEvent.target).val('&mcocd=1' + sValue);

                },
                select: function( event, ui ) {
                    var terms = ui.item.value;
                    var mtkn_comp = ui.item.mtkn_comp;
                    var mtknr_rid = ui.item.mtknr_rid;
                    var mtkn_brnch = ui.item.mtkn_brnch;
                    jQuery('#fld_pbranch_sub').val(terms);
                    jQuery('#fld_pbranch_sub_id').val(mtknr_rid);
                    jQuery(this).autocomplete('search', jQuery.trim(terms));
                    return false;
                }
            })
        .click(function() {
                /*var comp = jQuery('#fld_Company').val();
                var comp2 = this.value +'XOX'+comp;
                var terms = comp2.split('XOX');//dto naq 4/25
                */
                var terms = this.value;
                jQuery(this).autocomplete('search', jQuery.trim(terms));

            });
</script>
