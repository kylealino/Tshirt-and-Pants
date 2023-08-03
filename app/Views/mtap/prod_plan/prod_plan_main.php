<?php

$defaultDate = date('Y-m-d');

?>
<main id="main">
    <div class="pagetitle">
        <h1>Production Planning</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Production Planning</li>
            </ol>
            </nav>
    </div>
    <div class="row mb-3 me-form-font">
    <span id="__me_numerate_wshe__" ></span>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-pencil-square"></i> Entry</h3>
                </div>
                <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-3">
                        <div class="col-sm-12">
                            <h6 class="card-title p-0">Select branch:</h6>
                            <input type="text"  placeholder="Branch Name" id="branch_name" name="branch_name" class="branch_name form-control form-control-sm " required/>
                            <input type="hidden"  placeholder="Branch Name" id="branch_code" name="branch_code" class="branch_code form-control form-control-sm " required/>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="col-sm-12">
                            <h6 class="card-title p-0">Sales Coverage:</h6>
                            <select id="date_range" class="form-control form-control-sm" name="date_range">
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                                <option value="2020">2020</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="col-sm-12">
                            <h6 class="card-title p-0">Month Capacity:</h6>
                            <select id="month_cap" class="form-control form-control-sm" name="month_cap">
                                <option value="1">1 Month</option>
                                <option value="2">2 Months</option>
                                <option value="3">3 Months</option>
                                <option value="4">4 Months</option>
                                <option value="5">5 Months</option>
                                <option value="6">6 Months</option>
                                <option value="7">7 Months</option>
                                <option value="8">8 Months</option>
                                <option value="9">9 Months</option>
                                <option value="10">10 Months</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="col-sm-12">
                            <h6 class="card-title p-0">Select File to Upload:</h6>
                            <div class="input-group input-group-sm ">
                            <input type="file" class="form-control form-control-sm" id="rcv-upld-file" placeholder="Search Transaction/Branch" aria-label="mytxtsearchrec" aria-describedby="basic-addon1">
                            <div class="input-group-prepend" id="basic-addon1">
                                <button type="button" id="btn-upload-wshe-rcv" class="btn btn-dgreen btn-sm m-0 rounded-0 rounded-end" ><i class="bi bi-upload"></i> Upload</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div> 
                <div class="row">
                    <div id="mymodoutrecs">
                     <div class="text-center p-2 rounded-3  mt-2 border-dotted bg-light col-lg-12  p-4">
                        <h5><i class="bi bi-info-circle-fill text-dgreen"></i> Uploaded csv file will display in here.</h5> 
                     </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Records</h3>
                </div>
                <div class="card-body">
                    <div class="pt-2 bg-dgreen mt-2"> 
                        <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                            <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> List</a>
                            <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> Items</a>
                        </nav>
                    </div>

                    <div id="prod-vw" class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">
                    <?php
                    ?> 
                    </div> 
                </div> 
            </div>
        </div>
        
        <div class="accordion" id="accordionExample">
            <div class="col-lg-12">
                <div class="card">
                <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-flag"></i> Reports</h3>
                </div>   
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <h3 class="h4 mb-0"> <i class="bi bi-journals"></i> Reports</h3>
                    </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div id=""></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</main>
<script>
    $(document).ready(function() {
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    var mtkn_whse = '';
    prod_plan_view_recs(mtkn_whse);
    });

    __mysys_apps.mepreloader('mepreloaderme',false);
    $('#btn-upload-wshe-rcv').click(function(){ 
      try {   

        var file       = $('#rcv-upld-file').val();
        var branch_name_val = document.getElementById('branch_name');
        var branch_name = branch_name_val.value;
        var date_range_val = document.getElementById('date_range');month_cap
        var date_range = date_range_val.value;
        var month_cap_val = document.getElementById('month_cap');
        var month_cap = month_cap_val.value;
        if($.trim(file) == ''){ 
          jQuery('#myModSysMsgSubBod').css({
            display: ''
          });
          jQuery('#myModSysMsgSubBod').html('Please select file to upload!');
          jQuery('#myModSysMsgSub').modal('show');
          return false;
        }

        my_data = new FormData();
        my_data.append('rcv_file', $('#rcv-upld-file')[0].files[0]);
        my_data.append('branch_name', branch_name);
        my_data.append('date_range', date_range);
        my_data.append('month_cap', month_cap);

        __mysys_apps.mepreloader('mepreloaderme',true);
        $.ajax({ // default declaration of ajax parameters
          url: '<?=site_url()?>prod-plan-upld',
          method:"POST",
          context:document.body,
          data: my_data,
          contentType: false,
          global: false,
          cache: false,
          processData:false,
          success: function(data)  { //display html using divID
            __mysys_apps.mepreloader('mepreloaderme',false);
            jQuery('#mymodoutrecs').html(data);
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
      $(this).prop("disabled", true);
    }); 

    jQuery('#branch_name')
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
			source: '<?= site_url(); ?>search-prod-plan-branch/',  //mysearchdata/companybranch_v
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			search: function(oEvent, oUi) {
				var sValue = jQuery(oEvent.target).val();

			},
			select: function( event, ui ) {
				var terms = ui.item.value;
				jQuery('#branch_name').val(terms);
				jQuery(this).autocomplete('search', jQuery.trim(terms));
				return false;
			}
		})
		.click(function() {
			var terms = this.value;
			jQuery(this).autocomplete('search', jQuery.trim(terms));
	});	//end branch_name

    __mysys_apps.mepreloader('mepreloaderme',false);

    $('#anchor-list').on('click',function(){
        $('#anchor-list').addClass('active');
        $('#anchor-items').removeClass('active');
        var mtkn_whse = '';
        prod_plan_view_recs(mtkn_whse);

    });

    function prod_plan_view_recs(mtkn_whse){ 
        var ajaxRequest;

        ajaxRequest = jQuery.ajax({
            url: "<?=site_url();?>me-prod-plan-view",
            type: "post",
            data: {
                mtkn_whse: mtkn_whse
            }
        });

        // Deal with the results of the above ajax call
        __mysys_apps.mepreloader('mepreloaderme',true);
        ajaxRequest.done(function(response, textStatus, jqXHR) {
            jQuery('#prod-vw').html(response);
            __mysys_apps.mepreloader('mepreloaderme',false);
        });
    };

    
</script>


