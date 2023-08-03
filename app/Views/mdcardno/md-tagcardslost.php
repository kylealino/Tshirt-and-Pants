<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mymd = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$myCardTag = model('App\Models\MyCardTagModel');





 // $this->response->download($images_path, null);
?>
<main id="main">

    <div class="pagetitle">
        <h1>Tagging of Card Series - Lost</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Tagging of Card Series - Lost</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->

	<div class="row mb-3 me-form-font">
	    <div class="col-md-12">
	    	<div class="card">
	    		<div class="card-body">
	    			<h6 class="card-title">Tagging of Card Series - Lost</h6>
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

			<div class="col-md-12">
		    	<div class="card">
		    		<div class="card-body">
		    			<h6 class="card-title">Records</h6>

		    			<?php 


		    			 ?>
		    			<div id="custlist">
		    				<ul class="nav nav-tabs nav-tabs-bordered mb-2" id="myTabCust" role="tablist">
		                    	<li class="nav-item" role="presentation">
			                        <button class="nav-link active" id="cardtaglost-tab" data-bs-toggle="tab" data-bs-target="#cardtaglost" type="button" role="tab" aria-controls="cardtaglost" aria-selected="true">Customer Profile</button>
			                    </li>
			                     <li class="nav-item" role="presentation">
			                        <button class="nav-link" id="cardlostupld-tab" data-bs-toggle="tab" data-bs-target="#cardlostupld" type="button" role="tab" aria-controls="cardlostupld" aria-selected="false">Uploading</button>
			                    </li>
			                </ul>
			                <div class="pb-1 tab-content" id="myTabCustContent">
			                	 <div class="tab-pane fade show active" id="cardtaglost" role="tabpanel" aria-labelledby="cardtaglost-tab">
				                    <?php
				                        //$data = $myCardTag->rec_view(1,20,'','L');
		                       			//echo view('mdcardno/md-tagcardslost-recs',$data);
				                    ?>

			                    </div>
			                    <div class="tab-pane fade" id="cardlostupld" role="tabpanel" aria-labelledby="profile-tab">
			                    	<?php


			                    		//$data['meType'] = 'L';
		                       			//echo view('mdcardno/md-cards-upld',$data);
				                    ?>	
			                    </div>
			                </div>	
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

<script type="text/javascript" defer>
    
    __myredirected_rsearch_lost(1); //load records
    $('#cardtaglost-tab').on('click',function(){
       __myredirected_rsearch_lost(1);
    });

    $('#cardlostupld-tab').on('click',function(){

    	var ajaxRequest;
    	ajaxRequest = jQuery.ajax({
    	    url: "<?=site_url();?>me-tagcardupld-vw",
    	    type: "post",
    	    data: {
    	        meType: 'L'
    	    }
    	});
    	    // Deal with the results of the above ajax call
    	    ajaxRequest.done(function(response, textStatus, jqXHR) {
    	        jQuery('#cardlostupld').html(response);
    	        // and do it again
    	        //setTimeout(get_if_stats, 5000);
    	    });
   
    });

	jQuery('#mytxtsearchrec_lost').keypress(function(event){ 
		if(event.which == 13) { 
			event.preventDefault(); 
			__myredirected_rsearch_lost(1);
			
		}
	});	

	function __myredirected_rsearch_lost(mobj){ 
		try { 
			__mysys_apps.mepreloader('mepreloaderme',true);
			var txtsearchedrec = jQuery('#mytxtsearchrec_lost').val();
			
            //mytrx_sc/mndt_sc2_recs
			var mparam = { 
				txtsearchedrec: txtsearchedrec,
				mpages: mobj,
				type:'L'  
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
						$('#cardtaglost').html(data);
						
						return false;
				},
				error: function() { // display global error on the menu function
					__mysys_apps.mepreloader('mepreloaderme',false);
					alert('error loading page...');
					
					return false;
				}	
			});			
								
		} catch(err){
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