<?php 
/**
 *	File        : masterdata/md-customer-profile.php
 *  Auhtor      : Joana Rocacorba
 *  Date Created: May 6, 2022
 * 	last update : May 6, 2022
 * 	description : Customer Records
 */
 
$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

$mytxtsearchrec = $request->getVar('txtsearchedrec');


$data = array();
$mpages = (empty($mylibzsys->oa_nospchar($request->getVar('mpages'))) ? 0 : $mylibzsys->oa_nospchar($request->getVar('mpages')));
$mpages = ($mpages > 0 ? $mpages : 1);
$apages = array();
$mpages = $npage_curr;
$npage_count = $npage_count;
for($aa = 1; $aa <= $npage_count; $aa++) {
	$apages[] = $aa . "xOx" . $aa;
}

?>
<style>
table.memetable, th.memetable, td.memetable {
  border: 1px solid #F6F5F4;
  border-collapse: collapse;
}
thead.memetable, th.memetable, td.memetable {
  padding: 6px;
}
</style>
<?=form_open('me-newapp-cust-save-rec','class="needs-validation-search" id="myfrmsearchrec" ');?>
    <div class="col-md-6 mb-1">
        <div class="input-group input-group-sm">
            <label class="input-group-text fw-bold" for="search">Search:</label>
            <input type="text" id="mytxtsearchrec" class="form-control form-control-sm" name="mytxtsearchrec" placeholder="Search" />
           	<button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
        </div>
    </div>
    <?=form_close();?> <!-- end of ./form -->


<div class="col-md-8">
	<?=$mylibzsys->mypagination($npage_curr,$npage_count,'__myredirected_rsearch','');?>
	</div>

			<div class="table-responsive">
				<div class="col-md-12 col-md-12 col-md-12">
					<table class="table table-striped table-hover table-bordered table-sm" id="tbldata_cust">
						<thead>
							<tr>
								<th class="text-center">
									<?=anchor('me-newapp-cust', '<i class="bi bi-plus-lg"></i>',' class="btn btn-success p-1 pb-0 mebtnpt1 btn-sm" ');?>
								</th>
								<th>Account Number</th>
								<th>Card No</th>
								<th>e-Mail</th>
								<th>Last Name</th>
								<th>First Name</th>
								<th>Middle Name</th>
								<th>Contact Numer</th>
								<th>Birth Date</th>
								<th>Gender</th>
								<th>Civil Status</th>
								<th>Address 1</th>
								<th>Address 2</th>
								<th>Region</th>
								<th>Province</th>
								<th>City / Municipality</th>
								<th>Barangay</th>
								<th>Zip Code</th>
								<th>Acive</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									
									$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									$mtkn_trxno = hash('sha384', $row['CUST_ID'] . $mpw_tkn);
									
								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td class="text-center" nowrap>
										<?=anchor('me-newapp-cust/?mtkn_trxno=' . $mtkn_trxno, '<i class="bi bi-pencil-square"></i>',' class="btn btn-primary p-1 pb-0 mebtnpt1 btn-sm"');?>
										
									</td>
									<td nowrap><?=$row['CUST_ACCTNO'];?></td>
									<td nowrap><?=$row['CUST_NO'];?></td>
									<td nowrap><?=$row['CUST_EMAIL'];?></td>
									<td nowrap><?=$row['CUST_LNAME'];?></td>
									<td nowrap><?=$row['CUST_FNAME'];?></td>
									<td nowrap><?=$row['CUST_MNAME'];?></td>
									<td nowrap><?=$row['CUST_CONTNO'];?></td>
									<td nowrap><?= $mylibzsys->mydate_mmddyyyy($row['CUST_BDATE']);?></td>
									<td nowrap><?=$row['CUST_GENDER'];?></td>
									<td nowrap><?=$row['CUST_CIVILS'];?></td>
									<td nowrap><?=$row['CUST_ADDR1'];?></td>
									<td nowrap><?=$row['CUST_ADDR2'];?></td>
									<td nowrap><?=$row['reg_name'];?></td>
									<td nowrap><?=$row['prov_name'];?></td>
									<td nowrap><?=$row['cssmun_name'];?></td>
									<td nowrap><?=$row['bgy_name'];?></td>
									<td nowrap><?=$row['CUST_ZIP'];?></td>
									<td nowrap><?=$row['CUST_ACTIVE'];?></td>
									
								</tr>
								<?php 
								$nn++;
								endforeach;
							else:
								?>
								<tr>
									<td colspan="18">No data was found.</td>
								</tr>
							<?php 
							endif; ?>
						</tbody>
						
					</table>
				</div>
			</div> <!-- end table-reponsive -->
	
<script type="text/javascript"> 

    function meSetCellPadding () {
        var metable = document.getElementById ("tbldata_cust");
        metable.cellPadding = 6;
        metable.style.border = "1px solid #C0BCB6";
        var tabletd = metable.getElementsByTagName("td");
    }
    meSetCellPadding();

	function __myredirected_rsearch(mobj) { 
		try { 
			__mysys_apps.mepreloader('mepreloaderme',true);
			var txtsearchedrec = jQuery('#mytxtsearchrec').val();
			


            //mytrx_sc/mndt_sc2_recs
			var mparam = { 
				txtsearchedrec: txtsearchedrec,
				mpages: mobj 
			};	
			jQuery.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>me-newapp-cust-save-rec',
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,
				success: function(data)  { //display html using divID
						__mysys_apps.mepreloader('mepreloaderme',false);
						$('#custlist').html(data);
						
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
	
	
	jQuery('#mytxtsearchrec').keypress(function(event) { 
		if(event.which == 13) { 
			event.preventDefault(); 
			try { 
				__mysys_apps.mepreloader('mepreloaderme',true);
				var txtsearchedrec = jQuery('#mytxtsearchrec').val();

				var mparam = {
					txtsearchedrec: txtsearchedrec,
					mpages: 1 
				};	

				jQuery.ajax({ // default declaration of ajax parameters
				type: "POST",
				url: '<?=site_url();?>me-newapp-cust-save-rec',
				context: document.body,
				data: eval(mparam),
				global: false,
				cache: false,
					success: function(data)  { //display html using divID
							jQuery('#custlist').html(data);
							__mysys_apps.mepreloader('mepreloaderme',false);
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
	});	
	

	(function () {
		'use strict'

		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.needs-validation-search')
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


					//start here
					try { 
						__mysys_apps.mepreloader('mepreloaderme',true);
						var txtsearchedrec = jQuery('#mytxtsearchrec').val();

						var mparam = {
							txtsearchedrec: txtsearchedrec,
							mpages: 1 
						};	
						
						jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: '<?=site_url();?>me-newapp-cust-save-rec',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
							success: function(data)  { //display html using divID
									__mysys_apps.mepreloader('mepreloaderme',false);
									jQuery('#custlist').html(data);
									
							},
							error: function() { // display global error on the menu function
								__mysys_apps.mepreloader('mepreloaderme',false);
								alert('error loading page...');
								
							}	
						});			
									
					} catch(err) { 
						__mysys_apps.mepreloader('mepreloaderme',false);
						var mtxt = 'There was an error on this page.\n';
						mtxt += 'Error description: ' + err.message;
						mtxt += '\nClick OK to continue.';
						alert(mtxt);
					}  //end try

					//end here



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
	// function __mndt_cust_urecs(mtkn_trxno) { 
	// 	try { 
	// 		__mysys_apps.mepreloader('mepreloaderme',true);
	// 		//var txtsearchedrec = jQuery('#mytxtsearchrec').val();
			


 //            //mytrx_sc/mndt_sc2_recs
	// 		var mparam = { 
	// 			mtkn_trxno: mtkn_trxno
	// 		};	
	// 		jQuery.ajax({ // default declaration of ajax parameters
	// 		type: "POST",
	// 		url: '<?=site_url();?>md-customer-profile',
	// 		context: document.body,
	// 		data: eval(mparam),
	// 		global: false,
	// 		cache: false,
	// 			success: function(data)  { //display html using divID
	// 					__mysys_apps.mepreloader('mepreloaderme',false);
	// 					$('#myfrms_customer').html(data);
						
	// 					return false;
	// 			},
	// 			error: function() { // display global error on the menu function
	// 				__mysys_apps.mepreloader('mepreloaderme',false);
	// 				alert('error loading page...');
					
	// 				return false;
	// 			}	
	// 		});			
								
	// 	} catch(err) {
	// 		var mtxt = 'There was an error on this page.\n';
	// 		mtxt += 'Error description: ' + err.message;
	// 		mtxt += '\nClick OK to continue.';
	// 		__mysys_apps.mepreloader('mepreloaderme',false);
	// 		alert(mtxt);
	// 		return false;

	// 	}  //end try
	// }	
	
</script>
