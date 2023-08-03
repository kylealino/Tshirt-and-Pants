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

$mytxtsearchrec_dmg = $request->getVar('txtsearchedrec');


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
<?=form_open('me-tagcard-rec','class="needs-validation-search" id="myfrmsearchrec" ');?>
    <div class="col-md-6 mb-1">
        <div class="input-group input-group-sm">
            <label class="input-group-text fw-bold" for="search">Search:</label>
            <input type="text" id="mytxtsearchrec_dmg" class="form-control form-control-sm" name="mytxtsearchrec_dmg" placeholder="Search" value="<?=$mytxtsearchrec_dmg?>" />
           	<button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
        </div>
    </div>
    <?=form_close();?> <!-- end of ./form -->


<div class="col-md-8">
	<?=$mylibzsys->mypagination($npage_curr,$npage_count,'__myredirected_rsearch_dmg','');?>
	</div>

			<div class="table-responsive">
				<div class="col-md-12 col-md-12 col-md-12">
					<table class="table table-striped table-hover table-bordered table-sm" id="tbldata_cust">
						<thead>
							<tr>
								<th class="text-center">
								</th>
								<th>Card No</th>
								<th>Card Tag</th>
								<th>Encd</th>
								<th>Muser</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									
									$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									$mtkn_trxno =  $row['mtkn_mntr'];
									
								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td class="text-center" nowrap>
									<button title="Tag as Lost" class="btn btn-primary p-1 pb-0 mebtnpt1 btn-sm" onclick="manual_tag('<?=$row['CUST_NO']?>','D')" >
											<i class="bi bi bi-pencil-square"></i>
										</button>	
										
									</td>
									<td nowrap><?=$row['CUST_NO'];?></td>
									<td nowrap><?=$row['cardTag'];?></td>
									<td nowrap><?=$row['encd'];?></td>
									<td nowrap><?=$row['muser'];?></td>
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


	function manual_tag(cardno,type){

		try { 	
			meurl = '<?=site_url()?>me-card-manualtag';
			my_data = new FormData();
			my_data.append('type', type);
			my_data.append('cardno', cardno);
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

	}
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
          			__myredirected_rsearch_dmg(1);

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
	
	
</script>
