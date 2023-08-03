<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdrpts = model('App\Models\MyMDReportsModel');
$mydataz = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(0);

?>
<main id="main">

    <div class="pagetitle">
        <h1>Total Unredeemed Points</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Total Unredeemed Points</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->

	<div class="row mb-3 me-form-font">
	    <div class="col-md-12">
	    	<div class="card">
	    		<div class="card-body">
	    			<h6 class="card-title">Total Unredeemed Points</h6>
	    			<?=form_open('me-newapp-cust-save','class="needs-validation" id="myfrms_unredempt" ');?>
	    			<div class="row">
	    				<div class="col-lg-12">
			    			<div class="row mb-3">
					            <label class="col-sm-3 form-label" for="mcustacctno">Branch</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustacctno" name="mcustacctno" class="form-control form-control-sm" value="" required/>
					                <input type="hidden" id="__hmcustacctid" name="__hmcustacctid" class="form-control form-control-sm" value=""/>
					            </div>
					        </div> <!-- end Acct No. -->
					        <div class="row mb-3">
					            <label class="col-sm-3 form-label" for="mcustacctno">Branch Area</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustacctno" name="mcustacctno" class="form-control form-control-sm" value="" required/>
					                <input type="hidden" id="__hmcustacctid" name="__hmcustacctid" class="form-control form-control-sm" value=""/>
					            </div>
					        </div> 
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustbdate">Date From</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustbdate" name="mcustbdate" class="form-control form-control-sm meform_date" value="" placeholder="mm/dd/yyyy" required/>
					            </div>
					        </div>
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustbdate">Date To</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustbdate" name="mcustbdate" class="form-control form-control-sm meform_date" value="" placeholder="mm/dd/yyyy" required/>
					            </div>
					        </div> 
					    </div>
					
	              	</div> <!-- endrow -->
	              	<div class="row gy-2 mb-3">
              			<div class="col-sm-4">
              				<button id="mbtn_mn_Sch" type="submit" class="btn btn-primary btn-sm">Search</button>
              			</div>
              		</div> <!-- end Save Records -->
              		<?=form_close();?> <!-- end of ./form -->
	        	</div> <!-- end card-body -->
	        </div>
		</div>
		<div class="col-md-12">
	    	<div class="card">
	    		<div class="card-body">
	    			<h6 class="card-title">Records</h6>
	    			<div id="totunredemptrecs">
	    				<?php
                       $data = $mymdrpts->totunredempt_rec_view(1,20);
                       echo view('mdrpts/md-tot-unredempt-recs',$data);
                    ?>	
	    			</div>
	    		</div> <!-- end card-body -->
	        </div>
		</div>
	</div> <!-- end row -->
	<?php
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>	
</main>    

<script type="text/javascript">

    jQuery('.meform_date').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true
    });
	
	jQuery('#region')
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
            source: '<?= site_url(); ?>mget-reg',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) {
                var sValue = jQuery(oEvent.target).val();
                
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                this.value = ui.item.value;
                var mtkn_rid = ui.item.mtkn_rid;
                var mtkn_code = ui.item.mtkn_code;
                jQuery('#province').val('');
				jQuery('#city').val('');
				jQuery('#barangay').val('');
                //var mcustbdate = jQuery('#mcustbdate').val();
                //console.log(mcustbdate);
                jQuery('#region').val(terms);
                jQuery('#region').attr("data-id-reg",mtkn_rid);
                jQuery('#region').attr("data-id-reg-code",mtkn_code);
                jQuery('#province').focus();

                return false;
            }
        })
    .click(function() {
        var terms = this.value;
		jQuery(this).autocomplete('search', jQuery.trim(terms));

	});

	jQuery('#province')
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
            source: '<?= site_url(); ?>mget-prov',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
        
            search: function(oEvent, oUi) {
                var sValue = jQuery(oEvent.target).val();
                //var comp = jQuery('#fld_Company').val();
                var mtkn_regrid = jQuery('#region').attr("data-id-reg");
                jQuery(this).autocomplete('option', 'source', '<?=site_url();?>mget-prov/?mtkn_regrid=' + mtkn_regrid); 
                //jQuery(oEvent.target).val('&mcocd=1' + sValue);
               
            },
            select: function( event, ui ) {
            	jQuery('#city').val('');
				jQuery('#barangay').val('');
                var terms = ui.item.value;
                //var mtkn_comp = ui.item.mtkn_comp;
                var mtkn_rid = ui.item.mtkn_rid;
                var mtkn_code = ui.item.mtkn_code;
                jQuery('#province').val(terms);
                jQuery('#province').attr("data-id-prov",mtkn_rid);
                jQuery('#province').attr("data-id-prov-code",mtkn_code);
                //jQuery('#region').val(mtkn_comp);
                jQuery(this).autocomplete('search', jQuery.trim(terms));
                jQuery('#city').focus();
                return false;
            }
        })
    .click(function() {
        
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //province

    jQuery('#city')
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
            source: '<?= site_url(); ?>mget-mun',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
        
            search: function(oEvent, oUi) {
                var sValue = jQuery(oEvent.target).val();
                //var comp = jQuery('#fld_Company').val();
                var mtkn_regrid = jQuery('#region').attr("data-id-reg");
                var mtkn_provrid = jQuery('#province').attr("data-id-prov");
                jQuery(this).autocomplete('option', 'source', '<?=site_url();?>mget-mun/?mtkn_regrid=' + mtkn_regrid + '&mtkn_provrid=' + mtkn_provrid); 
                
               
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                var mtkn_rid = ui.item.mtkn_rid;
                var mtkn_code = ui.item.mtkn_code;
                jQuery('#city').val(terms);
                jQuery('#city').attr("data-id-mun",mtkn_rid);
                jQuery('#city').attr("data-id-mun-code",mtkn_code);
                //jQuery('#region').val(mtkn_comp);
                jQuery(this).autocomplete('search', jQuery.trim(terms));
                jQuery('#barangay').val('');
                jQuery('#barangay').focus();
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //city

    jQuery('#barangay')
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
            source: '<?= site_url(); ?>mget-bgy',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
        
            search: function(oEvent, oUi) {
                var sValue = jQuery(oEvent.target).val();
                //var comp = jQuery('#fld_Company').val();
                var mtkn_regrid = jQuery('#region').attr("data-id-reg");
                var mtkn_provrid = jQuery('#province').attr("data-id-prov");
                var mtkn_munrid = jQuery('#city').attr("data-id-mun");
                jQuery(this).autocomplete('option', 'source', '<?=site_url();?>mget-bgy/?mtkn_regrid=' + mtkn_regrid + '&mtkn_provrid=' + mtkn_provrid + '&mtkn_munrid=' + mtkn_munrid); 
                
               
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                var mtkn_code = ui.item.mtkn_code;
                jQuery('#barangay').val(terms);
                jQuery('#barangay').attr("data-id-bgy-code",mtkn_code);
                
                //jQuery('#region').val(mtkn_comp);
                jQuery(this).autocomplete('search', jQuery.trim(terms));
                
                return false;
            }
        })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
      
    }); //barangay

    (function () {
		'use strict'

		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.needs-validation')
		// Loop over them and prevent submission
		Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}
				form.classList.add('was-validated') 

				try {
					event.preventDefault();
          			event.stopPropagation();
					//jQuery('html,body').scrollTop(0);
					//jQuery.showLoading({name: 'line-pulse', allowHide: false });
					
					var mcustacctno = jQuery('#mcustacctno').val();
					var __hmcustacctid = jQuery('#__hmcustacctid').val();
					var mcustcardno = jQuery('#mcustcardno').val();
					var mcustemail = jQuery('#mcustemail').val();
					var mcustlname = jQuery('#mcustlname').val();
					var mcustfname = jQuery('#mcustfname').val();
					var mcustmname = jQuery('#mcustmname').val();
					var mcustcontnum = jQuery('#mcustcontnum').val();
					var mcustbdate = jQuery('#mcustbdate').val();
					var mcustgendr =  jQuery('#mcustgendr').val();
					var mcustcivils = jQuery('#mcustcivils').val();
					var mcustaddr1 = jQuery('#mcustaddr1').val();
					var mcustaddr2 = jQuery('#mcustaddr2').val();
					// var region = jQuery('#region').val();
					// var province = jQuery('#province').val();
					// var city = jQuery('#city').val();
					// var barangay = jQuery('#barangay').val();

					var region = jQuery('#region').attr("data-id-reg-code");
					var province = jQuery('#province').attr("data-id-prov-code");
					var city = jQuery('#city').attr("data-id-mun-code");
					var barangay = jQuery('#barangay').attr("data-id-bgy-code");

					var zip_code = jQuery('#zip_code').val();
					var mcustactive = jQuery('#mcustactive').val();

					if(jQuery('input.mcustactive').prop("checked")){
	               		var mcustactive = 'Y';
		            }else{
		                var mcustactive = 'N';
		            }

					__mysys_apps.mepreloader('mepreloaderme',true);

					var mparam = {
						mcustacctno:mcustacctno,
						__hmcustacctid:__hmcustacctid,
						mcustcardno: mcustcardno,
						mcustemail: mcustemail,
						mcustlname: mcustlname,
						mcustfname: mcustfname,
						mcustmname: mcustmname,
						mcustcontnum: mcustcontnum,
						mcustbdate: mcustbdate,
						mcustgendr:mcustgendr,
						mcustcivils: mcustcivils,
						mcustaddr1: mcustaddr1,
						mcustaddr2: mcustaddr2,
						region: region,
						province: province,
						city: city,
						barangay: barangay,
						zip_code:zip_code,
						mcustactive: mcustactive
					};	
					jQuery.ajax({ // default declaration of ajax parameters
					type: "POST",
					url: '<?=site_url();?>me-newapp-cust-save',
					context: document.body,
					data: eval(mparam),
					global: false,
					cache: false,
						success: function(data)  { //display html using divID
								__mysys_apps.mepreloader('mepreloaderme',false);
			                    jQuery('#memsgtestent_bod').html(data);
			                    jQuery('#memsgtestent').modal('show');
								
								return false;
						},
						error: function() { // display global error on the menu function 
							__mysys_apps.mepreloader('mepreloaderme',false);
							alert('error loading page...');
							return false;
						}	
					});	
				} catch(err) { 
					__mysys_apps.mepreloader('mepreloaderme',false);
					var mtxt = 'There was an error on this page.\n';
					mtxt += 'Error description: ' + err.message;
					mtxt += '\nClick OK to continue.';
					alert(mtxt);
					return false;
				}  //end try					
			}, false)
		})
	})();

	__mysys_apps.mepreloader('mepreloaderme',false);
   
</script>