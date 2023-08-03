<?php

$defaultDate = date('Y-m-d');

?>
<main id="main">
    <div class="pagetitle">
        <h1>Standard Capacity</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Standard Capacity</li>
            </ol>
            </nav>
    </div>
    <div class="row mb-3 me-form-font">
    <span id="__me_numerate_wshe__" ></span>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-pencil-square"></i>Entry</h3>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <h6>Select file to upload</h6>
                        <div class="input-group input-group-sm ">
                            <input type="file" class="form-control form-control-sm" id="rcv-upld-file" placeholder="Search Transaction/Branch" aria-label="mytxtsearchrec" aria-describedby="basic-addon1">
                            <div class="input-group-prepend" id="basic-addon1">
                                <button type="button" id="btn-upload-wshe-rcv" class="btn btn-dgreen btn-sm m-0 rounded-0 rounded-end" ><i class="bi bi-upload"></i> Upload</button>
                            </div>
                        </div>
                    </div>

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
                    <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i>Records</h3>
                </div>
                <div class="card-body">
                    <div class="pt-2 bg-dgreen mt-2"> 
                        <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                            <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> Transaction</a>
                            <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> list</a>
                        </nav>
                    </div>

                    <div id="stcp-vw" class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">
                    <?php
                    ?> 
                    </div> 
                </div> 
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i>Update entry</h3>
                </div>
                <div class="card-body">
                    <div class="pt-2 bg-dgreen mt-2"> 
                        <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                            <a id="anchor-list-2" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i>Item List</a>
                            <a id="anchor-items-2" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> Records</a>
                        </nav>
                    </div>

                    <div id="stcp-vw-i" class="text-center p-2 rounded-3  mt-3 border-dotted p-4 ">
                    <?php
                    ?> 
                    </div> 
                </div> 
            </div>
        </div>
        
        <!-- <div class="accordion" id="accordionExample">
            <div class="col-lg-12">
                <div class="card">   
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
        </div> -->
    </div> 
</main>
<script>
    __mysys_apps.mepreloader('mepreloaderme',false);
    $(document).ready(function() {
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    var mtkn_whse = '';
    standard_cap_view_recs(mtkn_whse);
    
    __mysys_apps.mepreloader('mepreloaderme',false);

    $('#anchor-list-2').addClass('active');
    $('#anchor-items-2').removeClass('active');
    var mtkn_whsee = '';
    standard_cap_view_item_recs(mtkn_whsee);

    });
 __mysys_apps.mepreloader('mepreloaderme',false);
   
    $('#btn-upload-wshe-rcv').click(function(){ 
      try {   

        var file       = $('#rcv-upld-file').val();
    
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

        __mysys_apps.mepreloader('mepreloaderme',true);
        $.ajax({ // default declaration of ajax parameters
          url: '<?=site_url()?>standard-cap-upld',
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
			source: '<?= site_url(); ?>search-standard-cap-branch/',  //mysearchdata/companybranch_v
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
        standard_cap_view_recs(mtkn_whse);

    });

    function standard_cap_view_recs(mtkn_whse){ 
        var ajaxRequest;

        ajaxRequest = jQuery.ajax({
            url: "<?=site_url();?>me-standard-cap-view",
            type: "post",
            data: {
                mtkn_whse: mtkn_whse
            }
        });

        // Deal with the results of the above ajax call
        __mysys_apps.mepreloader('mepreloaderme',true);
        ajaxRequest.done(function(response, textStatus, jqXHR) {
            jQuery('#stcp-vw').html(response);
            __mysys_apps.mepreloader('mepreloaderme',false);
        });
    };

    __mysys_apps.mepreloader('mepreloaderme',false);

    $('#anchor-list-2').on('click',function(){
        $('#anchor-list-2').addClass('active');
        $('#anchor-items-2').removeClass('active');
        var mtkn_whse = '';
        standard_cap_view_item_recs(mtkn_whse);

    });

    function standard_cap_view_item_recs(mtkn_whse){ 
        var ajaxRequest;

        ajaxRequest = jQuery.ajax({
            url: "<?=site_url();?>me-standard-cap-view-list",
            type: "post",
            data: {
                mtkn_whse: mtkn_whse
            }
        });

        // Deal with the results of the above ajax call
        __mysys_apps.mepreloader('mepreloaderme',true);
        ajaxRequest.done(function(response, textStatus, jqXHR) {
            jQuery('#stcp-vw-i').html(response);
            __mysys_apps.mepreloader('mepreloaderme',false);
        });
    };
    
</script>


