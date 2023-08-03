<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(0);

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

$amcustcivils = $mydataz->lk_Active_Civil();
$amcustgendr = $mydataz->lk_Active_Gender();


$mtkn_trxno = $request->getVar('mtkn_trxno');
//echo $mtkn_trxno;
// die();
$CUST_ACCTNO = '';
$mtkn_mntr = '';
$CUST_NO = '';
$CUST_EMAIL = '';
$CUST_LNAME = '';
$CUST_FNAME = '';
$CUST_MNAME = '';
$CUST_CONTNO = '';
$CUST_BDATE = '';
$CUST_GENDER = '';
$CUST_CIVILS = '';
$CUST_ADDR1 = '';
$CUST_ADDR2 = '';
$CUST_REG = '';
$CUST_PROV = '';
$CUST_CITY = '';
$CUST_BGY = '';
$CUST_ZIP = '';
$CUST_BRNCH_AVLD = '';
$CUST_CNTRY = '';
$CUST_ACTIVE = '';
$CUST_CARD_EXP_DATE = '';
$CUST_RGSTRD_DATE = '';
$ISRGSTRD = '';
$MUSER = '';
$ENCD_DATE = '';
$reg_name = '';
$prov_name = '';
$cssmun_name = '';
$bgy_name = '';
$reg_code = '';
$prov_code = '';
$cssmun_code = '';
$bgy_code = '';
$checked = '';
if(!empty($mtkn_trxno)) {
$str = "SELECT
          aa.`CUST_ID`,
          aa.`CUST_ACCTNO`,
          aa.`CUST_NO`,
          aa.`CUST_EMAIL`,
          aa.`CUST_LNAME`,
          aa.`CUST_FNAME`,
          aa.`CUST_MNAME`,
          aa.`CUST_CONTNO`,
          aa.`CUST_BDATE`,
          aa.`CUST_GENDER`,
          aa.`CUST_CIVILS`,
          aa.`CUST_ADDR1`,
          aa.`CUST_ADDR2`,
          aa.`CUST_REG`,
          aa.`CUST_PROV`,
          aa.`CUST_CITY`,
          aa.`CUST_BGY`,
          aa.`CUST_ZIP`,
          aa.`CUST_BRNCH_AVLD`,
          aa.`CUST_CNTRY`,
          aa.`CUST_ACTIVE`,
          aa.`CUST_CARD_EXP_DATE`,
          aa.`CUST_RGSTRD_DATE`,
          aa.`ISRGSTRD`,
          aa.`MUSER`,
          aa.`ENCD_DATE`,
          (SELECT `Name` FROM {$this->db_erp}.`PSGC` WHERE aa.`CUST_REG` = `Correspondence Code` ORDER BY `Correspondence Code` LIMIT 1) reg_name,
          (SELECT `Name` FROM {$this->db_erp}.`PSGC` WHERE aa.`CUST_PROV` = `Correspondence Code` ORDER BY `Correspondence Code` LIMIT 1)  prov_name,
          (SELECT `Name` FROM {$this->db_erp}.`PSGC` WHERE aa.`CUST_CITY` = `Correspondence Code` ORDER BY `Correspondence Code` LIMIT 1)  cssmun_name,
          (SELECT `Name` FROM {$this->db_erp}.`PSGC` WHERE aa.`CUST_BGY` = `Correspondence Code` ORDER BY `Correspondence Code` LIMIT 1)  bgy_name,
          (SELECT 
	      	sha2(concat(SUBSTR(`Correspondence Code`,1,2),'{$mpw_tkn}'),384) 
	      	FROM {$this->db_erp}.`PSGC` 
	      	WHERE aa.`CUST_BGY` = `Correspondence Code` 
	      	ORDER BY `Correspondence Code` 
	      	LIMIT 1) reg_code,

          (SELECT sha2(concat(SUBSTR(`Correspondence Code`,1,4),'{$mpw_tkn}'),384) 
          	FROM {$this->db_erp}.`PSGC` 
          	WHERE aa.`CUST_BGY` = `Correspondence Code` 
          	ORDER BY `Correspondence Code` 
          	LIMIT 1) prov_code,

          (SELECT sha2(concat(SUBSTR(`Correspondence Code`,1,6),'{$mpw_tkn}'),384) 
          	FROM {$this->db_erp}.`PSGC` 
          	WHERE aa.`CUST_BGY` = `Correspondence Code` 
          	ORDER BY `Correspondence Code` 
          	LIMIT 1) cssmun_code,

          (SELECT sha2(concat(SUBSTR(`Correspondence Code`,1,9),'{$mpw_tkn}'),384) 
          	FROM {$this->db_erp}.`PSGC` 
          	WHERE aa.`CUST_BGY` = `Correspondence Code` 
          	ORDER BY `Correspondence Code` 
          	LIMIT 1) bgy_code,
          sha2(concat(aa.`CUST_ID`,'{$mpw_tkn}'),384) mtkn_mntr 
        FROM
          {$this->db_erp}.`mst_nrc_cust` aa
     
        WHERE sha2(concat(aa.`CUST_ID`,'{$mpw_tkn}'),384) = '$mtkn_trxno'";

$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();

$CUST_ACCTNO = $rw['CUST_ACCTNO'];
$mtkn_mntr = $rw['mtkn_mntr'];
$CUST_NO = $rw['CUST_NO'];
$CUST_EMAIL = $rw['CUST_EMAIL'];
$CUST_LNAME = $rw['CUST_LNAME'];
$CUST_FNAME = $rw['CUST_FNAME'];
$CUST_MNAME = $rw['CUST_MNAME'];
$CUST_CONTNO = $rw['CUST_CONTNO'];
$CUST_BDATE = $mylibzsys->mydate_mmddyyyy($rw['CUST_BDATE']);
$CUST_GENDER = $rw['CUST_GENDER'];
$CUST_CIVILS = $rw['CUST_CIVILS'];
$CUST_ADDR1 = $rw['CUST_ADDR1'];
$CUST_ADDR2 = $rw['CUST_ADDR2'];

$reg_name = $rw['reg_name'];
$prov_name = $rw['prov_name'];
$cssmun_name = $rw['cssmun_name'];
$bgy_name = $rw['bgy_name'];

$CUST_ZIP = $rw['CUST_ZIP'];
$CUST_BRNCH_AVLD = $rw['CUST_BRNCH_AVLD'];
$CUST_CNTRY = $rw['CUST_CNTRY'];
$CUST_ACTIVE = $rw['CUST_ACTIVE'];
$CUST_CARD_EXP_DATE = $mylibzsys->mydate_mmddyyyy($rw['CUST_CARD_EXP_DATE']);
$CUST_RGSTRD_DATE = $mylibzsys->mydate_mmddyyyy($rw['CUST_RGSTRD_DATE']);
$ISRGSTRD = $rw['ISRGSTRD'];
$MUSER = $rw['MUSER'];
$ENCD_DATE = $rw['ENCD_DATE'];

$reg_code = $rw['reg_code'];
$prov_code = $rw['prov_code'];
$cssmun_code = $rw['cssmun_code'];
$bgy_code = $rw['bgy_code'];

$CUST_REG = $rw['CUST_REG'];
$CUST_PROV = $rw['CUST_PROV'];
$CUST_CITY = $rw['CUST_CITY'];
$CUST_BGY = $rw['CUST_BGY'];
if($CUST_ACTIVE == 'Y'){
	$checked = "checked";
}
else{
	$checked = "";
}


}

?>
<main id="main">

    <div class="pagetitle">
        <h1>Customer Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Customer's</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->

	<div class="row mb-3 me-form-font">
	    <div class="col-md-12">
	    	<div class="card">
	    		<div class="card-body">
	    			<h6 class="card-title">Customer Profile</h6>
	    			<?=form_open('me-cust-entry-save','class="needs-validation" id="myfrms_customer" ');?>
	    			<div class="row">
	    				<div class="col-lg-6">
			    			<div class="row mb-3">
					            <label class="col-sm-3 form-label" for="mcustacctno">Account Number.</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustacctno" name="mcustacctno" class="form-control form-control-sm" value="<?=$CUST_ACCTNO;?>" readonly required/>
					                <input type="hidden" id="__hmcustacctid" name="__hmcustacctid" class="form-control form-control-sm" value="<?=$mtkn_mntr;?>"/>
					            </div>
					        </div> <!-- end Acct No. -->
					        <div class="row mb-3">
					            <label class="col-sm-3 form-label" for="mcustcardno">Card No.</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustcardno" name="mcustcardno" class="form-control form-control-sm" value="<?=$CUST_NO;?>" readonly required/>
					            </div>
					        </div> <!-- end Card No. -->
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustemail">e-Mail</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustemail" name="mcustemail" class="form-control form-control-sm" value="<?=$CUST_EMAIL;?>" readonly required/>
					            </div>
					        </div> <!-- end Card No. -->			        
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustlname">Last Name</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustlname" name="mcustlname" class="form-control form-control-sm" value="<?=$CUST_LNAME;?>" readonly required/>
					            </div>
					        </div> <!-- end Last Name -->			        

					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustfname">First Name</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustfname" name="mcustfname" class="form-control form-control-sm" value="<?=$CUST_FNAME;?>" readonly required/>
					            </div>
					        </div> <!-- end First Name -->			        
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustmname">Middle Name</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustmname" name="mcustmname" class="form-control form-control-sm" value="<?=$CUST_MNAME;?>" readonly required/>
					            </div>
					        </div> <!-- end Middle Name -->			        
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustcontnum">Contact Numer</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustcontnum" name="mcustcontnum" class="form-control form-control-sm" value="<?=$CUST_CONTNO;?>" readonly required/>
					            </div>
					        </div> <!-- end Contact Numer-->			        
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustbdate">Birth Date</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustbdate" name="mcustbdate" class="form-control form-control-sm meform_date" value="<?=$CUST_BDATE;?>" placeholder="mm/dd/yyyy" readonly required/>
					            </div>
					        </div> <!-- end Birth Date-->	
					        <div class="row gy-2 mb-3">
								<label class="col-sm-3 form-label" for="mcustcivils">Civil Status</label>
								
								<div class="col-sm-9">
									<!-- <select name="mcustcivils" id="mcustcivils" class="form-control form-control-sm">
										<option value="Single">Single</option>
										<option value="Married">Married</option>
										<option value="Widow">Widow</option>
									</select> -->
									<?= $mylibzsys->mypopulist_2($amcustcivils,$CUST_CIVILS,'mcustcivils','class="form-control form-control-sm" readonly','','');?>
								</div>
							</div> <!-- end Civil Status -->
					    </div>
					    <div class="col-lg-6">		        
							<div class="row gy-2 mb-4">
								<label class="col-sm-3 form-label" for="mcustgendr">Gender</label>
								<div class="col-sm-9">
									<!-- <select name="mcustgendr" id="mcustgendr" class="form-control form-control-sm">
										<option value="1">Male</option>
										<option value="2">Female</option>
									</select> -->
									<?= $mylibzsys->mypopulist_2($amcustgendr,$CUST_GENDER,'mcustgendr','class="form-control form-control-sm" readonly ','','');?>
								</div>
							</div> <!-- end Gender -->
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustaddr1">Address 1</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustaddr1" name="mcustaddr1" class="form-control form-control-sm" value="<?=$CUST_ADDR1;?>" placeholder="House No. / Street" readonly required/>
					            </div>
					        </div> <!-- end Address 1 -->			        					
					        <div class="row gy-2 mb-3">
					            <label class="col-sm-3 form-label" for="mcustaddr2">Address 2</label>
					            <div class="col-sm-9">
					                <input type="text" id="mcustaddr2" name="mcustaddr2" class="form-control form-control-sm" value="<?=$CUST_ADDR2;?>" placeholder="Village / Subdivisions" readonly />
					            </div>
					        </div> <!-- end Address 2 --> 
							<div class="row gy-2 mb-3">
								<label class="col-sm-3 form-label" for="region">Region</label>
								<div class="col-sm-9">
									<input data-id-reg="<?=$reg_code;?>" data-id-reg-code="<?=$CUST_REG;?>" type="text" id="region" name="region" class="form-control form-control-sm" value="<?=$reg_name;?>" placeholder="Region" readonly required/>
								</div>
							</div> <!-- end Region -->
							<div class="row gy-2 mb-3">
								<label class="col-sm-3 form-label" for="province">Province</label>
								<div class="col-sm-9">
									<input data-id-prov="<?=$prov_code;?>" data-id-prov-code="<?=$CUST_PROV;?>" type="text" id="province" name="province" class="form-control form-control-sm" value="<?=$prov_name;?>" placeholder="Province" readonly required/>
								</div>
							</div> <!-- end Province -->
							<div class="row gy-2 mb-3">
								<label class="col-sm-3 form-label" for="city">City / Municipality</label>
								<div class="col-sm-9">
									<input data-id-mun="<?=$cssmun_code;?>" data-id-mun-code="<?=$CUST_CITY;?>" type="text" id="city" name="city" class="form-control form-control-sm" value="<?=$cssmun_name;?>" placeholder="City / Municipality" readonly required/>
								</div>
							</div> <!-- end City -->
							<div class="row gy-2 mb-3">
								<label class="col-sm-3 form-label" for="barangay">Barangay</label>
								<div class="col-sm-9">
									<input data-id-bgy-code="<?=$CUST_BGY;?>" type="text" id="barangay" name="barangay" class="form-control form-control-sm" value="<?=$bgy_name;?>" placeholder="Barangay" readonly required/>
									
								</div>
							</div> <!-- end Barangay -->
							<div class="row gy-2 mb-3">
								<label class="col-sm-3 form-label" for="zip_code">Zip Code</label>
								<div class="col-sm-9">
									<input type="text" id="zip_code" name="zip_code" class="form-control form-control-sm" value="<?=$CUST_ZIP;?>" placeholder="Zip Code" readonly required/>
									
								</div>
							</div> <!-- end Barangay -->
							<div class="row gy-2 mb-3">
								<label class="col-sm-3 form-label" for="mcustactive">Active</label>
								<div class="col-sm-9">
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" id="mcustactive"  class="mcustactive" <?=$checked;?>>
									</div>
								</div>
		              		</div> <!-- end Active -->
		              	</div>
	              	</div> <!-- endrow -->
	              	<!-- <div class="row gy-2 mb-3">
              			<div class="col-sm-4">
              				<button id="mbtn_mn_Save" type="submit" class="btn btn-primary btn-sm">Save</button>
              			</div>
              		</div>  --><!-- end Save Records -->
              		<?=form_close();?> <!-- end of ./form -->
	        	</div> <!-- end card-body -->
	        </div>
		</div>
		<div class="col-md-12">
	    	<div class="card">
	    		<div class="card-body">
	    			<h6 class="card-title">Records</h6>
	    			<div id="custlist">
	    				<ul class="nav nav-tabs nav-tabs-bordered mb-2" id="myTabCust" role="tablist">
	                    	<li class="nav-item" role="presentation">
		                        <button class="nav-link active" id="custprof-tab" data-bs-toggle="tab" data-bs-target="#custprof" type="button" role="tab" aria-controls="custprof" aria-selected="true">Customer Profile</button>
		                    </li>
		                     <li class="nav-item" role="presentation">
		                        <button class="nav-link" id="custpurchdtls-tab" data-bs-toggle="tab" data-bs-target="#custpurchdtls" type="button" role="tab" aria-controls="custpurchdtls" aria-selected="false">Purchased Details</button>
		                    </li>
		                    <li class="nav-item" role="presentation">
		                        <button class="nav-link" id="custapprecs-tab" data-bs-toggle="tab" data-bs-target="#custapprecs" type="button" role="tab" aria-controls="custapprecs" aria-selected="false">Application Record</button>
		                    </li>
		                    <li class="nav-item" role="presentation">
		                        <button class="nav-link" id="custblock-tab" data-bs-toggle="tab" data-bs-target="#custblock" type="button" role="tab" aria-controls="custblock" aria-selected="false">Blocking of Lost Card</button>
		                    </li>
		                </ul>
		                <div class="pb-1 tab-content" id="myTabCustContent">
		                	 <div class="tab-pane fade show active" id="custprof" role="tabpanel" aria-labelledby="custprof-tab">
			                    <?php
			                        $data = $mymdcustomer->rec_view(1,20);
	                       			echo view('masterdata/md-customer-profile-recs',$data);
			                    ?>

		                    </div>
		                    <div class="tab-pane fade" id="custpurchdtls" role="tabpanel" aria-labelledby="profile-tab">
		                    ...	
		                    </div>
		                    <div class="tab-pane fade" id="custapprecs" role="tabpanel" aria-labelledby="profile-tab">
		                    ...
		                    </div>
		                    <div class="tab-pane fade" id="custblock" role="tabpanel" aria-labelledby="custblock-tab">...</div>
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

<script type="text/javascript">
	mywg_purchdtls_load('');
    function mywg_purchdtls_load(mtkn_etr) {
        var ajaxRequest;
        
        ajaxRequest = jQuery.ajax({
                url: "<?=site_url();?>me-customer-profile",
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                type: "post",
                data: { mtkn_etr: mtkn_etr}
            });

        // Deal with the results of the above ajax call
        ajaxRequest.done(function (response, textStatus, jqXHR) {
            jQuery('#custpurchdtls').html(response);

            // and do it again
            //setTimeout(get_if_stats, 5000);
        });
    }
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
					var mcustactive = 'N';
					if ($('#mcustactive').is(":checked"))
					{
					  var mcustactive = 'Y';
					}
					// if(jQuery('input.mcustactive').prop("checked")){
	    //            		var mcustactive = 'Y';
		   //          }else{
		   //              var mcustactive = 'N';
		   //          }

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
    // var my_handlers = {

    //     fill_provinces:  function(){

    //         var region_code = $(this).val();
    //         jQuery('#province').ph_locations('fetch_list', [{"region_code": region_code}]);
            
    //     },

    //     fill_cities: function(){

    //         var province_code = $(this).val();
    //         jQuery('#city').ph_locations( 'fetch_list', [{"province_code": province_code}]);
    //     },


    //     fill_barangays: function(){

    //         var city_code = $(this).val();
    //         jQuery('#barangay').ph_locations('fetch_list', [{"city_code": city_code}]);
    //     }
    // };

    // jQuery(function(){
    //     jQuery('#region').on('mouseover', my_handlers.fill_provinces);
    //     jQuery('#province').on('click', my_handlers.fill_cities);
    //     jQuery('#city').on('click', my_handlers.fill_barangays);

    //     jQuery('#region').ph_locations({'location_type': 'regions'});
    //     jQuery('#province').ph_locations({'location_type': 'provinces'});
    //     jQuery('#city').ph_locations({'location_type': 'cities'});
    //     jQuery('#barangay').ph_locations({'location_type': 'barangays'});

    //     jQuery('#region').ph_locations('fetch_list');
    // });
</script>