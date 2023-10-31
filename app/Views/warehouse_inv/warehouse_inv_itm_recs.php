<?php

/**
 *  File        : warehouse_inv_itm_recs.php
 *  Author      : Arnel Oquien
 *  Date Created: Dec. 02, 2022
 */

$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytxtsearchrec = $request->getVar('txtsearchedrec');
$mymelibsys =  model('App\Models\Mymelibsys_Model');
$mtkn_whse = $request->getVar('mtkn_whse');

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


<?=form_open('warehouse-inv-item-recs-vw','class="needs-validation-search" id="myfrmsearchrec" ');?>

    <div class="col-md-6 mb-1">
        <div class="input-group input-group-sm">
            <label class="input-group-text fw-bold" for="search">Search:</label>
            <input type="text" id="mytxtsearchrec" class="form-control form-control-sm" name="mytxtsearchrec" placeholder="Search" />
            <button type="submit" class="btn btn-dgreen btn-sm" style="background-color:#167F92; color:#fff;"><i class="bi bi-search"></i></button>
        </div>
    </div>
<?=form_close();?> <!-- end of ./form -->
<div class="col-md-8">
    <?=$mylibzsys->mypagination($npage_curr,$npage_count,'__myredirected_rsearch','');?>
</div>

<input type="hidden" id="txt-wshe" class="txt-wshe" name="txt-wshe" value="<?=$mtkn_whse;?>"  data-id="">
<div class="box box-primary">
	<div class="box-body">
		<div class="row pt-3">
			<div class="col-md-12">
				<div class="table-responsive">
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-inv-items-recs">
		            	<thead class="thead-light">
				          	<tr class="text-dgreen bg-light text-center">
								<th nowrap="nowrap">BOX CONTENT</th>
								<th nowrap="nowrap">STOCK CODE</th>
								<th nowrap="nowrap">ITEM CODE</th>
								<th nowrap="nowrap">ITEM DESC</th>
								<th nowrap="nowrap">PACKAGING</th>
								<th nowrap="nowrap">QTY</th>
								<th nowrap="nowrap">CONVF</th>
								<th nowrap="nowrap">TOTAL PCS</th>
								<!--  <th nowrap="nowrap">UNIT PRICE</th> -->
								<th nowrap="nowrap">TOTAL AMT</th>
								<th nowrap="nowrap">S.TEXT</th>
								<th nowrap="nowrap">PLANT</th>
								<th nowrap="nowrap">WAREHOUSE</th>
								<th nowrap="nowrap">STORAGE BIN</th>
								<th nowrap="nowrap">WAREHOUSE GRP</th>
								<th nowrap="nowrap">BARCODE</th>
								<th nowrap="nowrap">BOX NO</th>
								<th nowrap="nowrap">USER</th>
								<th nowrap="nowrap">ENCD</th>
								<th nowrap="nowrap">TYPE</th>
								<th nowrap="nowrap">TRANSACTION NO</th>
				          	</tr>
		            	</thead>
						<tbody>
							<?php 
								if($rlist != 0):
								$nn = 1;
								foreach($rlist as $row): 
								
							?>
							<tr>
								<td class="text-center" nowrap>
									<button class="btn btn-dgreen" data-mtknr="<?=$row['txt_mtknr']?>" onclick="getBoxcontent(this)"><i class="bi bi-box-seam"></i> View</button>
								</td>
								<td nowrap><?=$row['stock_code']?></td>
								<td nowrap><?=$row['ART_CODE']?></td>
								<td nowrap><?=$row['ART_DESC']?></td>
								<td nowrap><?=$row['BOX']?></td>
								<td nowrap><?=$row['qty']?></td>
								<td nowrap><?=$row['convf']?></td>
								<td nowrap><?=$row['total_pcs_scanned']?></td>
								<td nowrap><?=$row['tamt_scanned']?></td>
								<td nowrap><?=$row['remarks']?></td>
								<td nowrap><?=$row['plnt_code']?></td>
								<td nowrap><?=$row['wshe_code']?></td>
								<td nowrap><?=$row['wshe_bin_name']?></td>
								<td nowrap><?=$row['wshe_grp']?></td>
								<td nowrap><?=$row['barcde']?></td>
								<td nowrap><?=$row['box_no']?></td>
								<td nowrap><?=$row['muser']?></td>
								<td nowrap><?=$row['encd']?></td>
								<td nowrap><?=$row['type']?></td>
								<td nowrap><?=$row['SD_NO']?></td>
								
							</tr>
							<?php
								$nn++;
								endforeach;
							else:
								?>
								<tr>
									<td colspan="9">No data was found.</td>
								</tr>
							<?php endif; ?>
						</tbody>
		          	</table>
	        	</div>
	        	<hr>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

function __myredirected_rsearch(mobj) { 
		try { 
			__mysys_apps.mepreloader('mepreloaderme',true);
			var txtsearchedrec = jQuery('#mytxtsearchrec').val();
			var mtkn_wshe_page = jQuery('#txt-wshe').val();
			var txt_warehouse = jQuery('#txt-warehouse').attr("data-id");
		
            //mytrx_sc/mndt_sc2_recs
            var mparam = { 
            	txtsearchedrec: txtsearchedrec,
				mtkn_wshe_page:mtkn_wshe_page,
				txt_warehouse:txt_warehouse,
            	mpages: mobj 
            };	
			jQuery.ajax({ // default declaration of ajax parameters
				type: "POST",
				url: '<?=site_url();?>warehouse-inv-item-recs-vw',
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
				var mtkn_wshe_page = jQuery('#txt-wshe').val();
				var txt_warehouse = jQuery('#txt-warehouse').attr("data-id");
				var mparam = {
					txtsearchedrec: txtsearchedrec,
					mtkn_wshe_page:mtkn_wshe_page,
					txt_warehouse:txt_warehouse,
					mpages: 1 
				};	

				jQuery.ajax({ // default declaration of ajax parameters
					type: "POST",
					url: '<?=site_url();?>warehouse-inv-item-recs-vw',
					context: document.body,
					data: eval(mparam),
					global: false,
					cache: false,
					success: function(data)  { //display html using divID
						jQuery('#mymodoutentrecs').html(data);
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
						var mtkn_wshe_page = jQuery('#txt-wshe').val();
						var txt_warehouse = jQuery('#txt-warehouse').attr("data-id");
						var mparam = {
							txtsearchedrec: txtsearchedrec,
							mtkn_wshe_page:mtkn_wshe_page,
							txt_warehouse:txt_warehouse,
							mpages: 1 
						};	
						
						jQuery.ajax({ // default declaration of ajax parameters
							type: "POST",
							url: '<?=site_url();?>warehouse-inv-item-recs-vw',
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



function getBoxcontent(button){

    	try { 

		var txtsearchedrec = jQuery('#mytxtsearchrec_verify').val();
		var mtkn_whse      = jQuery('#txt-warehouse').attr("data-id");
		var mtkn_dt = jQuery(button).data('mtknr');
      	var mparam = {
	        mtkn_dt: mtkn_dt,
	        mtkn_whse:mtkn_whse
      	}
      	__mysys_apps.mepreloader('mepreloaderme',true);
 		$.ajax({ // default declaration of ajax parameters
	        type: "POST",
	        url: '<?=site_url()?>warehouse-inv-box-content',
	        context: document.body,
	        data: eval(mparam),
	        global: false,
	        cache: false,
	        success: function(data)  { //display html using divID
	          	 __mysys_apps.mepreloader('mepreloaderme',false);
	   
			   $('#boxcontent_success_bod').html(data);
			   $('#boxcontent_success').modal('show');
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

function __my_item_lookup(){
        jQuery('.mitemcode' ) 
          // don't navigate away from the field on tab when selecting an item
          .bind( 'keydown', function( event ) {
              if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                      jQuery( this ).data( 'autocomplete' ).menu.active ) {
                  event.preventDefault();
              }
              if( event.keyCode === jQuery.ui.keyCode.TAB ) {
                  event.preventDefault();
              }
          })
          .autocomplete({
              minLength: 0,
              source: '<?= site_url(); ?>get-rm-fg-code-list',
              focus: function() {
                  // prevent value inserted on focus
                  return false;
              },
              select: function( event, ui ) {
                  var terms = ui.item.value;
                  
                  jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                  jQuery(this).attr('title', jQuery.trim(ui.item.value));
               
                  this.value = ui.item.value;
              

                  var clonedRow = jQuery(this).parent().parent().clone();
                  var indexRow = jQuery(this).parent().parent().index();
                  var xobjitemrid = jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id'); //ID
                  var xobjitemdesc = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');/*DESC*/
                  var xobjiteminv = jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');/*DESC*/
                  
                  $('#' + xobjitemrid).val(ui.item.mtkn_rid);
                  $('#' + xobjitemdesc).val(ui.item.ART_DESC);
                  $('#' + xobjiteminv).val(ui.item.po_qty);
                  
                 

                  return false;
              }
          })
          .click(function() { 

              //jQuery(this).keydown(); 
              var terms = this.value;
              //jQuery(this).autocomplete('search', '');
              jQuery(this).autocomplete('search', jQuery.trim(terms));
          });  
        }
</script>