
<?php 
/**
 *	File        : maintenance/mycomp_BU-recs.php
 *  Auhtor      : Arnel L. Oquien
 *  Date Created: Sept 05, 2018
 * 	last update : Sept 05, 2018
 * 	description : Business unit records
 */
 
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$cuserrema = $mylibzdb->mysys_userrema();

$data = array();
$mpages = (empty($mylibzsys->oa_nospchar($request->getVar('mpages'))) ? 0 : $mylibzsys->oa_nospchar($request->getVar('mpages')));
$mpages = ($mpages > 0 ? $mpages : 1);
$apages = array();
$mpages = $npage_curr;
$npage_count = $npage_count;
for($aa = 1; $aa <= $npage_count; $aa++) {
	$apages[] = $aa . "xOx" . $aa;
}
$txtsearchrec = $request->getVar('txtsearchedrec_rl');

$search_cb = $request->getVar('search_cb');
$ch_checked = ($search_cb == 'Y')?'checked':'';

?>


    <?=form_open('whcrossing-recs','class="needs-validation-search d-flex justify-content-end"   id="myfrmsearchrec_rl" ');?>
    <div class="col-md-6 mb-1 mt-2">
     	<div class="form-check form-switch">
        		<input class="form-check-input " type="checkbox" id="search_cb" <?=$ch_checked?> >
        		<label class="form-check-label" for="search_cb">Perform a specific search  </label>
        	</div>
        <div class="input-group input-group-sm">
   
            <label class="input-group-text fw-bold " for="search"><?=anchor('whcrossing', '<i class="bi bi-arrow-repeat"></i>',' class="text-dgreen" ');?></label>
            <input type="text" id="mytxtsearchrec_rl" class="form-control form-control-sm" name="mytxtsearchrec_rl" placeholder="Search" value="<?=$txtsearchrec?>" />
           	<button type="submit" class="btn btn-dgreen btn-sm "><i class="bi bi-search"></i></button>
           	   
        </div>
    </div>
    <?=form_close();?> <!-- end of ./form -->
			<div class="table-responsive mt-2 text-center">
				<table class="table table-condensed table-hover table-bordered table-sm">
						<thead>
							<tr> <!---BUTTON FOR ADDING Product Line---->
								<th class="text-center">
									<?=anchor('whcrossing', '<i class="bi bi-plus text-white fs-5"></i>',' class="btn btn-sm" ');?>
								</th>
								<!---BUTTON FOR ADDING Product Line end ---->
								<th>Allocation Guide Trx. No</th>
								<th>Ref. PO Number</th>
                                <th>Supplier</th>
                                <th>Ship To</th>
                                <th>Whse/Grp</th>
                                <th>DR/Packing List</th>
                                <th>User</th>
                                <th>Date Encoded</th>
                                <th> <i class="bi bi-printer"></i> Print</th>
                            
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1; //use for changin the bgcolor of table
								foreach($rlist as $row): 
									$bgcolor = ($nn % 2) ? "#EAF3F3" : "#FFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									//giving data for $txt_mtknr
									$txt_mtknr = hash('sha384', $row['agpo_sysctrlno'] . $mpw_tkn);
									$txt_wshetkn = hash('sha384', $row['wshe_id'] . $mpw_tkn);
								
									
								?>
								<!---EDIT START BUTTON--->
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<!-- <td class="text-center" nowrap="nowrap">
										<?=anchor('mypoprint/po_print/?txt_mtknr=' . $txt_mtknr, '<i class="bi bi-edit"></i>',' class="btn btn-primary" title="Edit"');?>
										<button class="btn btn-danger" type="button" title="Delete" onclick="javascript:__busunit_drecs('<?=$txt_mtknr;?>');"><i class="bi bi-trash"></i></button>
									</td> -->
									<td nowrap="nowrap"><?=$nn;?></td>	
									<!---DELETE END BUTTON--->
									<td nowrap="nowrap"><?=$row['agpo_sysctrlno'];?></td>
									<td style="width: 300px;"><?=$row['__poref'];?></td>
									<td nowrap="nowrap"><?=$row['__vend_name'];?></td>
									<td nowrap="nowrap"><?=$row['__vends_name'];?></td>
									<td nowrap="nowrap"><?=$row['wshe_code'];?></td>
									<td nowrap="nowrap"><?=$row['dr_list'];?></td>
									<td nowrap="nowrap"><?=$row['muser'];?></td>
									<td nowrap="nowrap"><?=$row['encd_date'];?></td>
									<td class="text-center" nowrap="nowrap">
									<?php if($row['mkg_tag'] == "N"): ?>
									<button onclick="window.open('<?= site_url() ?>mycrossing-print?mtkn_potr=<?=$txt_mtknr?>&txt_wshetkn=<?=$txt_wshetkn?>')" class="btn btn-dgreen btn-sm  btn-sm"><i class="bi bi-printer"></i> AG</button>
									<button onclick="window.open('<?= site_url() ?>mycrossing-irrprint?mtkn_potr=<?=$txt_mtknr?>&txt_wshetkn=<?=$txt_wshetkn?>&tkn=<?=$row['plnt_id']?>')" class="btn btn-dgreen btn-sm  btn-sm"><i class="bi bi-printer"></i> IRR</button>
									<?php else:  ?>
									<button onclick="window.open('<?= site_url() ?>mycrossing-print-mkg?mtkn_potr=<?=$txt_mtknr?>&txt_wshetkn=<?=$txt_wshetkn?>')" class="btn btn-warning btn-sm  btn-sm"><i class="bi bi-printer"></i> AG</button>
									<button onclick="window.open('<?= site_url() ?>mycrossing-irrprintmkg?mtkn_potr=<?=$txt_mtknr?>&txt_wshetkn=<?=$txt_wshetkn?>&tkn=<?=$row['plnt_id']?>')" class="btn btn-warning btn-sm  btn-sm"><i class="bi bi-printer"></i> IRR</button>
									<?php endif;  ?>
									<button onclick="window.open('<?= site_url() ?>mycrossing-wrrprint?mtkn_potr=<?=$txt_mtknr?>&txt_wshetkn=<?=$txt_wshetkn?>')" class="btn btn-dgreen btn-sm  btn-sm"><i class="bi bi-printer"></i> WRR</button>
									</td>
								</tr>
								<?php 
								$nn++;
								endforeach;
							else:
								?>
								<tr>
									<td colspan="9">No data was found.</td>
								</tr>
							<?php 
							endif; ?>
						</tbody>
					</table>
			</div>
<div class=" d-flex justify-content-center mt-2" >
	<?=$mylibzsys->mypagination($npage_curr,$npage_count,'__myredirected_rsearch_rl','');?>

</div>	

<script type="text/javascript"> 

	function __myredirected_rsearch_rl(mobj) { 
		
		try { 
			//$('html,body').scrollTop(0);
			__mysys_apps.mepreloader('mepreloaderme',true);
			var txtsearchedrec_rl = $('#mytxtsearchrec_rl').val();
			var search_cb = ($('#search_cb').prop('checked'))?'Y':'N';
			

			var mparam = {
				txtsearchedrec_rl: txtsearchedrec_rl,
				search_cb:search_cb,
				mpages: mobj 
			};	
			$.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>whcrossing_recs',
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,
				success: function(data)  { //display html using divID
							__mysys_apps.mepreloader('mepreloaderme',false);
						$('#mymodoutentrecs').html(data);
						
						return false;
				},
				error: function() { // display global error on the menu function
					alert('error loading page...');
						__mysys_apps.mepreloader('mepreloaderme',false);
					return false;
				}	
			});			
								
		} catch(err) {
			var mtxt = 'There was an error on this page.\n';
			mtxt += 'Error description: ' + err.message;
			mtxt += '\nClick OK to continue.';
			alert(mtxt);
				__mysys_apps.mepreloader('mepreloaderme',false);
			return false;

		}  //end try
	}	
	


	//trigger when enterkey press in search
	$('#mytxtsearchrec_rl').keypress(function(event) { 
		if(event.which == 13) { 
			event.preventDefault(); 
			try { 
				// $('html,body').scrollTop(0);
					__mysys_apps.mepreloader('mepreloaderme',true);
				var txtsearchedrec_rl = $('#mytxtsearchrec_rl').val();
				var search_cb = ($('#search_cb').prop('checked'))?'Y':'N';

				var mparam = {
					txtsearchedrec_rl: txtsearchedrec_rl,
					search_cb:search_cb,
					mpages: 1 
				};	
				$.ajax({ // default declaration of ajax parameters
				type: "POST",
				url: '<?=site_url();?>whcrossing_recs',
				context: document.body,
				data: eval(mparam),
				global: false,
				cache: false,
					success: function(data)  { //display html using divID
								__mysys_apps.mepreloader('mepreloaderme',false);
							$('#mymodoutentrecs').html(data);
							
							return false;
					},
					error: function() { // display global error on the menu function
						alert('error loading page...');
							__mysys_apps.mepreloader('mepreloaderme',false);
						return false;
					}	
				});	
			} catch(err) {
				var mtxt = 'There was an error on this page.\n';
				mtxt += 'Error description: ' + err.message;
				mtxt += '\nClick OK to continue.';
				alert(mtxt);
					__mysys_apps.mepreloader('mepreloaderme',false);
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
							var txtsearchedrec = jQuery('#mytxtsearchrec_rl').val();
							var search_cb = ($('#search_cb').prop('checked'))?'Y':'N';

						
							var mparam = {
								txtsearchedrec_rl: txtsearchedrec,
								mpages: 1 ,
								search_cb:search_cb
							};	
		
							jQuery.ajax({ // default declaration of ajax parameters
							type: "POST",
							url: '<?=site_url();?>whcrossing_recs',
							context: document.body,
							data: eval(mparam),
							global: false,
							cache: false,
								success: function(data)  { //display html using divID
									__mysys_apps.mepreloader('mepreloaderme',false);
									jQuery('#mymodoutentrecs').html(data);
										
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






	
</script>
