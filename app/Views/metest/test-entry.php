<?php

$mylibzsys = model('App\Models\MyLibzSysModel');

$request = \Config\Services::request();
$metrxno = $request->getVar('metrxno');
$mevar = hash('sha384','wtr435765677meyoyo');
?>
<main id="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Testing</a></li>
                <li class="breadcrumb-item active">Customer Master Data</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->

	<div class="row mb-3 me-form-font">
	    <div class="col-md-6">
	    	<div class="card">
	    		<div class="card-body">
	    			<?=form_open('me-testing-entry-save','class="needs-validation" id="myfrms_testing" ');?>
	    			<h6 class="card-title">Customer Profile</h6>
			        <div class="row mb-3">
			            <label class="col-sm-3 form-label" for="mcustcardno">Card No.</label>
			            <div class="col-sm-9">
			                <input type="text" id="mcustcardno" name="mcustcardno" class="form-control form-control-sm" value="<?=$metrxno;?>" required />
			            </div>
			        </div> <!-- end Card No. -->
			        <div class="row gy-2 mb-3">
			            <label class="col-sm-3 form-label" for="mcustemail">e-Mail</label>
			            <div class="col-sm-9">
			                <input type="text" id="mcustemail" name="mcustemail" class="form-control form-control-sm" value="" required />
			            </div>
			        </div> <!-- end Card No. -->			        
			        <div class="row gy-2 mb-3">
			            <label class="col-sm-3 form-label" for="mcustlname">Last Name</label>
			            <div class="col-sm-9">
			                <input type="text" id="mcustlname" name="mcustlname" class="form-control form-control-sm" value="" />
			            </div>
			        </div> <!-- end Last Name -->			        

			        <div class="row gy-2 mb-3">
			            <label class="col-sm-3 form-label" for="mcustfname">First Name</label>
			            <div class="col-sm-9">
			                <input type="text" id="mcustfname" name="mcustfname" class="form-control form-control-sm" value="" />
			            </div>
			        </div> <!-- end First Name -->			        
			        <div class="row gy-2 mb-3">
			            <label class="col-sm-3 form-label" for="mcustmname">Middle Name</label>
			            <div class="col-sm-9">
			                <input type="text" id="mcustmname" name="mcustmname" class="form-control form-control-sm" value="" />
			            </div>
			        </div> <!-- end Middle Name -->			        
			        <div class="row gy-2 mb-3">
			            <label class="col-sm-3 form-label" for="mcustcontnum">Contact Numer</label>
			            <div class="col-sm-9">
			                <input type="text" id="mcustcontnum" name="mcustcontnum" class="form-control form-control-sm" value="" />
			            </div>
			        </div> <!-- end Contact Numer-->			        
			        <div class="row gy-2 mb-3">
			            <label class="col-sm-3 form-label" for="mcustbdate">Birth Date</label>
			            <div class="col-sm-9">
			                <input type="text" id="mcustbdate" name="mcustbdate" class="form-control form-control-sm meform_date" value="" placeholder="mm/dd/yyyy" />
			            </div>
			        </div> <!-- end Birth Date-->			        
					<div class="row gy-2 mb-3">
						<label class="col-sm-3 form-label" for="mcustcivils">Civil Status</label>
						<div class="col-sm-9">
							<select name="mcustcivils" id="mcustcivils" class="form-control form-control-sm">
								<option value="Single">Single</option>
								<option value="Married">Married</option>
								<option value="Widow">Widow</option>
							</select>
						</div>
					</div> <!-- end Civil Status -->
					<div class="row gy-2 mb-4">
						<label class="col-sm-3 form-label" for="mcustgendr">Gender</label>
						<div class="col-sm-9">
							<select name="mcustgendr" id="mcustgendr" class="form-control form-control-sm">
								<option value="1">Male</option>
								<option value="2">Female</option>
							</select>
						</div>
					</div> <!-- end Gender -->
			        <div class="row gy-2 mb-3">
			            <label class="col-sm-3 form-label" for="mcustaddr1">Address 1</label>
			            <div class="col-sm-9">
			                <input type="text" id="mcustaddr1" name="mcustaddr1" class="form-control form-control-sm" value="" placeholder="House No. / Street" />
			            </div>
			        </div> <!-- end Address 1 -->			        					
			        <div class="row gy-2 mb-3">
			            <label class="col-sm-3 form-label" for="mcustaddr2">Address 2</label>
			            <div class="col-sm-9">
			                <input type="text" id="mcustaddr2" name="mcustaddr2" class="form-control form-control-sm" value="" placeholder="Village / Subdivisions" />
			            </div>
			        </div> <!-- end Address 2 --> 
					<div class="row gy-2 mb-3">
						<label class="col-sm-3 form-label" for="region">Region</label>
						<div class="col-sm-9">
							<select name="region" id="region"  class="form-control form-control-sm"></select>
						</div>
					</div> <!-- end Region -->
					<div class="row gy-2 mb-3">
						<label class="col-sm-3 form-label" for="province">Province</label>
						<div class="col-sm-9">
							<select name="province" id="province"  class="form-control form-control-sm"></select>
						</div>
					</div> <!-- end Province -->
					<div class="row gy-2 mb-3">
						<label class="col-sm-3 form-label" for="city">City / Municipality</label>
						<div class="col-sm-9">
							<select name="city" id="city"  class="form-control form-control-sm"></select>
						</div>
					</div> <!-- end City -->
					<div class="row gy-2 mb-3">
						<label class="col-sm-3 form-label" for="barangay">Barangay</label>
						<div class="col-sm-9">
							<select name="barangay" id="barangay"  class="form-control form-control-sm"></select>
						</div>
					</div> <!-- end Barangay -->
					<div class="row gy-2 mb-3">
						<label class="col-sm-3 form-label" for="mcustactive">Acive</label>
						<div class="col-sm-9">
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" id="mcustactive" checked>
							</div>
						</div>
              		</div> <!-- end Active -->
              		<div class="row gy-2 mb-3">
              			<div class="col-sm-4">
              				<button type="submit" class="btn btn-default btn-sm"><i class="bi bi-save2"> Save</i></button>
              				<?=anchor('me-testing-entry/?metrxno=' . $mevar, '<i class="bi bi-pencil-square"></i>',' class="btn btn-primary btn-sm" ');?>
              			</div>
              		</div> <!-- end Save Records -->
              		<?=form_close();?> <!-- end of ./form -->
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

            var my_handlers = {

                fill_provinces:  function(){

                    var region_code = $(this).val();
                    jQuery('#province').ph_locations('fetch_list', [{"region_code": region_code}]);
                    
                },

                fill_cities: function(){

                    var province_code = $(this).val();
                    jQuery('#city').ph_locations( 'fetch_list', [{"province_code": province_code}]);
                },


                fill_barangays: function(){

                    var city_code = $(this).val();
                    jQuery('#barangay').ph_locations('fetch_list', [{"city_code": city_code}]);
                }
            };

            jQuery(function(){
                jQuery('#region').on('change', my_handlers.fill_provinces);
                jQuery('#province').on('change', my_handlers.fill_cities);
                jQuery('#city').on('change', my_handlers.fill_barangays);

                jQuery('#region').ph_locations({'location_type': 'regions'});
                jQuery('#province').ph_locations({'location_type': 'provinces'});
                jQuery('#city').ph_locations({'location_type': 'cities'});
                jQuery('#barangay').ph_locations({'location_type': 'barangays'});

                jQuery('#region').ph_locations('fetch_list');
            });



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
					var mcustcardno = jQuery('#mcustcardno').val();
					var mcustemail = jQuery('#mcustemail').val();
					__mysys_apps.mepreloader('mepreloaderme',true);

					var mparam = {
						mcustcardno: mcustcardno,
						mcustemail: mcustemail
					};	
					jQuery.ajax({ // default declaration of ajax parameters
					type: "POST",
					url: '<?=site_url();?>me-testing-entry-save',
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