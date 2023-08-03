
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

$data = array();
$mpages = (empty($mylibzsys->oa_nospchar($request->getVar('mpages'))) ? 0 : $mylibzsys->oa_nospchar($request->getVar('mpages')));
$mpages = ($mpages > 0 ? $mpages : 1);
$apages = array();
$mpages = $npage_curr;
$npage_count = $npage_count;
for($aa = 1; $aa <= $npage_count; $aa++) {
	$apages[] = $aa . "xOx" . $aa;
}
$txtsearchrec2 = $mylibzsys->oa_nospchar($request->getVar('txtsearchedrec'));

?>

    <?=form_open('whcrossing-plrecs','class="needs-validation-search d-flex justify-content-end align-items-center" id="myfrmsearchrec2" ');?>
    	<div class=" flex-fill">
    		<label for=""> <i class="bi bi-info-circle text-dgreen" title="Different packing list in not allowed!"> </i>	 DR/Packing List selected:</label>
    		<span id="pl-selected" class="text-dgreen fw-bolder fst-italic">  </span>
    	</div>
    	<div class="col-md-2 mb-1 mt-2 mx-1">
    		<div class="input-group"> 
    			<div class="input-group-text"> 	<input type="checkbox" class="green-cb" id="agpo-mkg" style="scale: 1.3;"></div>
    			<input type="text" name="MKG" class="form-control form-control-sm" placeholder="Check if MKG" readonly>
    		</div>
    	</div>
       <div class="col-md-6 mb-1 mt-2 ">
           <div class="input-group input-group-sm">
               <label class="input-group-text fw-bold " for="search"><?=anchor('whcrossing', '<i class="bi bi-arrow-repeat"></i>',' class="text-dgreen" ');?></label>
               <input type="text" id="mytxtsearchrec2" class="form-control form-control-sm" name="mytxtsearchrec2" placeholder="Search" value="<?=$txtsearchrec2?>" />
              	<button type="submit" class="btn btn-dgreen btn-sm "><i class="bi bi-search"></i></button>
              	   
           </div>
       </div>
    <?=form_close();?> <!-- end of ./form -->
			<div class="table-responsive mt-2 text-center">
					<table id = "mbtl_poprint_ent" class="table table-striped table-bordered table-condensed table-sm">
						<thead>
							<tr class="text-dgreen"> <!---BUTTON FOR ADDING Product Line---->
								<th nowrap="nowrap"> <i class=" bi bi-gear-fill"> </i> </th>
	                            <th>PO #</th>
	                            <th>Assorted</th>
	                            <th>Vendor</th>
	                            <th>DR/Packing List</th>
	                            <th>PO Date</th>
	                            <th>User</th>
                            
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
									$txt_mtknr = hash('sha384', $row['recid'] . $mpw_tkn);
									$txtpodate = $mylibzsys->mydate_mmddyyyy($row['trx_date']);
								?>
								<!---EDIT START BUTTON--->
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td class="">
										<div class="checkbox checkbox-single menomargin-tb">
											<input type="checkbox" class = "__Chckitemexist fs-2 green-cb" id = "__Chckitemexist_<?=$nn;?>" data-drlist="<?=$row['dr_list'];?>" name="__sto_data" value="<?=$txt_mtknr;?>" style="transform: scale(1.3);">
											<input type="hidden" name="mtkn_rid" value="<?=$txt_mtknr;?>" >
										</div>
									</td>
									<td nowrap="nowrap"><?=$row['po_sysctrlno'];?></td>
									<td nowrap="nowrap"><?=$row['asstd_tag'];?></td>
									<td nowrap="nowrap"><?=$row['__vend_name'];?></td>
									<td nowrap="nowrap"><?=$row['dr_list'];?></td>
									<td nowrap="nowrap"><?=$txtpodate;?></td>
									<td nowrap="nowrap"><?=$row['muser'];?></td>
								</tr>
								<?php 
								$nn++;
								endforeach;
							else:
								?>
								<tr>
									<td colspan="6">No data was found.</td>
								</tr>
							<?php 
							endif; ?>
						</tbody>
					</table>
			</div>

			<div class="mt-2 d-flex justify-content-center align-items-center">
				<?=$mylibzsys->mypagination($npage_curr,$npage_count,'__myredirected_rsearch2','');?>
			</div>	
			<div class="form-group">
                <div class="col-md-12">
                    <button class="btn btn-dgreen btn-sm" id="mbtn_PO_Save" type="submit"> <i class="bi bi-save"></i> Save</button>&nbsp;
                    <a href="<?=site_url()?>whcrossing" class="btn btn-dgreen btn-sm"  type="button"> <i class="bi bi-plus-circle"></i> New Trx</a>&nbsp;
                </div>
            </div>
<script type="text/javascript"> 





	function __myredirected_rsearch2(mobj) { 
		
		try { 
			//$('html,body').scrollTop(0);
			__mysys_apps.mepreloader('mepreloaderme',true);
			var txtsearchedrec = $('#mytxtsearchrec2').val();
			var mparam = {
				txtsearchedrec: txtsearchedrec,
				mpages: mobj 
			};	
			$.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>whcrossing-plrecs',
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,
				success: function(data)  { //display html using divID
						__mysys_apps.mepreloader('mepreloaderme',false);
						$('#mymodoutrecs').html(data);
						
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
	$('#mytxtsearchrec2').keypress(function(event) { 
		if(event.which == 13) { 
			event.preventDefault(); 
			try { 
				$('html,body').scrollTop(0);
				__mysys_apps.mepreloader('mepreloaderme',true);
				var txtsearchedrec = $('#mytxtsearchrec2').val();
				var mparam = {
					txtsearchedrec: txtsearchedrec,
					mpages: 1 
				};	
				$.ajax({ // default declaration of ajax parameters
				type: "POST",
				url: '<?=site_url();?>whcrossing-plrecs',
				context: document.body,
				data: eval(mparam),
				global: false,
				cache: false,
					success: function(data)  { //display html using divID
							__mysys_apps.mepreloader('mepreloaderme',false);
							$('#mymodoutrecs').html(data);
							
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
							var txtsearchedrec = jQuery('#mytxtsearchrec2').val();

							var mparam = {
								txtsearchedrec: txtsearchedrec,
								mpages: 1 
							};	
							
							jQuery.ajax({ // default declaration of ajax parameters
							type: "POST",
							url: '<?=site_url();?>whcrossing-plrecs',
							context: document.body,
							data: eval(mparam),
							global: false,
							cache: false,
								success: function(data)  { //display html using divID
										__mysys_apps.mepreloader('mepreloaderme',false);
										jQuery('#mymodoutrecs').html(data);
										
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


	 $("#chkall").click(function () {

    $('.__Chckitemexist:enabled').prop('checked', this.checked);
   	// __compute_qty();
     
  	});
  	
  	$('.__Chckitemexist').on('change',function(){
  			const drList = [];
  			$('.__Chckitemexist:checkbox:checked').each(function(cb,elem){

  				//$('#pl-selected').html($(elem).attr('data-drlist'));
  				drList.push($(elem).attr('data-drlist'));
  				//console.log($(elem).attr('data-drlist'));
  			});

  			if(drList.length==0){//null label
  				$('#pl-selected').html('');
  			};

  			if(__mysys_apps.sameInArray(drList)){
  				$('#pl-selected').html(drList[0]);
  			}
  			else{
  				$(this).prop('checked',false);
  			}
  	
  			//alert($(this).attr('data-drlist'));	
  	});




	$('#mbtn_PO_Save').click(function() { 
		try { 
			$('html,body').scrollTop(0);
			__mysys_apps.mepreloader('mepreloaderme',true);
				var isMKG = $('#agpo-mkg').is(':checked')?'Y':'N';
		
				var rowCount1 = jQuery('#mbtl_poprint_ent tr').length;
	            var adata1 = [];
	            var mdata  = '';
	            var ninc   = 0;
	           $('#mbtl_poprint_ent tr').each(function(){
                 	$(this).find('td input:checked').each(function(){ 
                 	  	var clonedRow     = $(this).parent().parent().parent().clone();
                        var ischck = jQuery(clonedRow).find('input[type=checkbox]').eq(0).is(":checked");
                        var mdat8  = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
                        if(ischck){
                           	mdata = mdat8;
                            adata1.push(mdata);
                            ninc++;
						}
                    });
                });
	           if(ninc == 0){
	           	alert('Please check atleast one PO Number!');
	           	__mysys_apps.mepreloader('mepreloaderme',false);
	           	return false;
	           }
				var mparam = {
					 adata1: adata1,
					 isMKG:isMKG
				};	
			$.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>whcrossing_sv',
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,
				success: function(data)  { //display html using divID
						__mysys_apps.mepreloader('mepreloaderme',false);
						jQuery('#myModalSysMsgBod').html(data);
						jQuery('#myModSysMsg').modal('show');
						
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
	});
  	

   
  
	
</script>
