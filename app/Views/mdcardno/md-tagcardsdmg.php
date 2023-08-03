<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$myCardTag = model('App\Models\MyCardTagModel');

?>
<main id="main">

    <div class="pagetitle">
        <h1>Tagging of Card Series - Damage</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Tagging of Card Series - Damage</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->

	<div class="row mb-3 me-form-font">
	    <div class="col-md-12">
	    	<div class="card">
	    		<div class="card-body">
	    			<h6 class="card-title">Tagging of Card Series - Damage</h6>
	    			<?=form_open('me-cards-entry-view','class="needs-validation" id="myfrms_cards" ');?>
	    			<div class="row">
	    				<div class="col-lg-6">
			    			<div class="row mb-3">
					            <label class="col-sm-3 form-label" for="mcustcardno">Search Card No.</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustcardno" name="mcustcardno" class="form-control form-control-sm" value="" required/>
					            </div>
					        </div> <!-- end Card No. -->
					        
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
		
	</div> <!-- end row -->

		<div class="col-md-12">
	    	<div class="card">
	    		<div class="card-body">
	    			<h6 class="card-title">Records</h6>
	    			<div id="custlist">
	    				<ul class="nav nav-tabs nav-tabs-bordered mb-2" id="myTabCust" role="tablist">
	                    	<li class="nav-item" role="presentation">
		                        <button class="nav-link active" id="carddmg-tab" data-bs-toggle="tab" data-bs-target="#carddmg" type="button" role="tab" aria-controls="carddmg" aria-selected="true">Customer Profile</button>
		                    </li>
		                     <li class="nav-item" role="presentation">
		                        <button class="nav-link" id="carddmgtupld-tab" data-bs-toggle="tab" data-bs-target="#carddmgtupld" type="button" role="tab" aria-controls="carddmgtupld" aria-selected="false">Uploading</button>
		                    </li>
		                </ul>
		                <div class="pb-1 tab-content" id="myTabCustContent">
		                	 <div class="tab-pane fade show active" id="carddmg" role="tabpanel" aria-labelledby="carddmg-tab">
			                    <?php
			                        //$data = $myCardTag->rec_view(1,20,'','D');
	                       			//echo view('mdcardno/md-tagcardsdmg-recs',$data);
			                    ?>

		                    </div>
		                    <div class="tab-pane fade" id="carddmgtupld" role="tabpanel" aria-labelledby="profile-tab">
		                    	<?php
		                    		//$data['meType'] = 'D';
	                       			//echo view('mdcardno/md-cards-upld',$data);
			                    ?>	
		                    </div>
		                </div>	
	    			</div>
	    		</div> <!-- end card-body -->
	        </div>
		</div>
	<?php
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>	
</main>    

<script type="text/javascript">

	 __myredirected_rsearch_dmg(1); //load records
    $('#carddmg-tab').on('click',function(){
       __myredirected_rsearch_dmg(1);
    });

    $('#carddmgtupld-tab').on('click',function(){

    	var ajaxRequest;
    	ajaxRequest = jQuery.ajax({
    	    url: "<?=site_url();?>me-tagcardupld-vw",
    	    type: "post",
    	    data: {
    	        meType: 'D'
    	    }
    	});
    	    // Deal with the results of the above ajax call
    	    ajaxRequest.done(function(response, textStatus, jqXHR) {
    	        jQuery('#carddmgtupld').html(response);
    	        // and do it again
    	        //setTimeout(get_if_stats, 5000);
    	    });
   
    });
    
	jQuery('#mytxtsearchrec_dmg').keypress(function(event) { 
		if(event.which == 13) { 
			event.preventDefault(); 
			__myredirected_rsearch_dmg(1);
		}
	});	

	function __myredirected_rsearch_dmg(mobj){ 
		try { 
			__mysys_apps.mepreloader('mepreloaderme',true);
			var txtsearchedrec = jQuery('#mytxtsearchrec_dmg').val();
			


            //mytrx_sc/mndt_sc2_recs
			var mparam = { 
				txtsearchedrec: txtsearchedrec,
				mpages: mobj,
				type :'D' 
			};	
			jQuery.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>me-tagcard-rec',
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,
				success: function(data)  { //display html using divID
						__mysys_apps.mepreloader('mepreloaderme',false);
						$('#carddmg').html(data);
						
						return false;
				},
				error: function() { // display global error on the menu function
					__mysys_apps.mepreloader('mepreloaderme',false);
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
			return false;

		}  //end try
	}
    
	__mysys_apps.mepreloader('mepreloaderme',false);

</script>