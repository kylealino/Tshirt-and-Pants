<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdpts = model('App\Models\MyMDPointsModel');
$mydataz = model('App\Models\MyDatumModel');


?>
<main id="main">

    <div class="pagetitle">
        <h1>Available Points</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Available Points</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->

	<div class="row mb-3 me-form-font">
	    <div class="col-md-12">
	    	<div class="card">
	    		<div class="card-body">
	    			<h6 class="card-title">Available Points</h6>
	    			<?=form_open('me-cards-entry-view','class="needs-validation" id="myfrms_cards" ');?>
	    			<div class="row">
	    				<div class="col-lg-6">
			    			<div class="row mb-3">
					            <label class="col-sm-3 form-label" for="mcustacctno">Account Number.</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustacctno" name="mcustacctno" class="form-control form-control-sm" value="" />
					                <input type="hidden" id="__hmcustacctid" name="__hmcustacctid" class="form-control form-control-sm" value=""/>
					            </div>
					        </div> <!-- end Acct No. -->
					        <div class="row mb-3">
					            <label class="col-sm-3 form-label" for="mcustcardno">Card No.</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustcardno" name="mcustcardno" class="form-control form-control-sm" value="" required/>
					            </div>
					        </div> <!-- end Card No. -->
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustemail">e-Mail</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustemail" name="mcustemail" class="form-control form-control-sm" value=""/>
					            </div>
					        </div> <!-- end Card No. -->			        
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustlname">Last Name</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustlname" name="mcustlname" class="form-control form-control-sm" value=""/>
					            </div>
					        </div> <!-- end Last Name -->			        

					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustfname">First Name</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustfname" name="mcustfname" class="form-control form-control-sm" value=""/>
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
	    			<div id="totptsinqrecs">
	    			<?php
                       $data = $mymdpts->ptsinq_recs(1,20);
                       echo view('mdpts/md-ptsinq-recs',$data);
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
					
					

					__mysys_apps.mepreloader('mepreloaderme',true);

					var mparam = {
						mcustacctno:mcustacctno
						
					};	
					jQuery.ajax({ // default declaration of ajax parameters
					type: "POST",
					url: '<?=site_url();?>me-cust-entry-save',
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