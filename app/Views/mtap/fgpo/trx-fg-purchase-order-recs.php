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
$mytrxrmpurch = model('App\Models\MyRMPurchaseModel');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

?>


<div class="col-md-8">
	</div>
		<div class="col-md-12 col-md-12 col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-condensed text-center" id="tbl-gr-recs">
						<thead>
							<tr>
								<th>
									
								</th>
								<th>PO Transaction No</th>
								<th>Vendor</th>
								<th>Supplier Internal Code</th>
								<th>Remarks</th>
								<th>Ship To</th>
								<th>User</th>
								<th>Date Encoded</th> 
								<th>Print</th>
								<th>Materials<th>

							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									
									$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									$mtkn_potr = hash('sha384', $row['recid'] . $mpw_tkn);
									$dis = ($row['is_bcodegen'] == '1' || $row['is_bcodegen'] == '2' || $row['is_approved'] == '2' || $row['is_approved'] == '0' ? "disabled" : '');
									$dis2 = ($row['is_bcodegen'] == '0' || $row['is_bcodegen'] == '2' || $row['is_approved'] == '2' || $row['is_approved'] == '0' ? "disabled" : '');
									$mtkn_wshe = $row['wshe_id'];
									$po_sysctrlno = $row['po_sysctrlno'];
									
								?>
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
									<td class="text-center" nowrap="nowrap">
										<?=anchor('me-fg-po-vw/?mtkn_trxno=' . $mtkn_potr, '<i class="bi bi bi-eye"></i> View ',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm"');?>
									</td>
									<td nowrap="nowrap"><?=$row['po_sysctrlno'];?></td>
									<td nowrap="nowrap"><?=$row['__vend_name'];?></td>
									<td nowrap="nowrap"><?=$row['__vend_SUPINCODE'];?></td>
									<td nowrap="nowrap"><?=$row['rmks'];?></td>
									<td nowrap="nowrap"><?=$row['__vends_name'];?></td>
									<td nowrap="nowrap"><?=$row['muser'];?></td>
									<td nowrap="nowrap"><?=$row['encd_date'];?></td>
									<td nowrap="nowrap">
									<?php

                                    echo("<button onclick=\"window.open('me-fg-purchase-print?mtkn_potr=$mtkn_potr&mtkn_pono=$po_sysctrlno')\" class=\"btn btn-primary btn-sm\"><i class=\"fa fa-print\"></i> Print list</button>");

									?>	
									</td>

									<td nowrap="nowrap">
										<button title="View items" class="btn btn-dgreen-ol btn-xs fg-btn-view-items"  data-fgpono= "<?=$row['po_sysctrlno'];?>" value="<?=$row['po_sysctrlno'];?>"  type="button" ><i class="bi bi-eye-fill"></i> Items</button>
									</td>
									<td></td>
									
									
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


$(document).ready(function(){
	 $.extend(true, $.fn.dataTable.defaults,{
	      language: {
	          search: ""
	      }
	  });

	  var tbl_pl_items = $('#tbl-gr-recs').DataTable({  
            
	       'order':[7, 'DESC'],
	       'columnDefs': [{
	           "targets":[0],
	           "orderable": false
	       },
			{
			targets:'_all',
			className: 'dt-head-center'
			},
			// 	     {
			// 	 targets: [6,7,8],
			// 	 className: 'dt-body-right'
			// 	}

	       ]
	   });


	   $('#tbl-gr-recs_filter.dataTables_filter [type=search]').each(function () {
	        $(this).attr(`placeholder`, `Search...`);
	        $(this).before('<span class="bi bi-search text-dgreen"></span>');
	    });

	});

	
	function po_bcode_gen(mtkn_potr){
		try { 
            __mysys_apps.mepreloader('mepreloaderme',true);
            
                    var mparam = {
                        mtkn_potr: mtkn_potr

                    }; 
                   //console.log(mparam);
                  jQuery.ajax({ // default declaration of ajax parameters
                    type: "POST",
                    url: '<?=site_url();?>me-rm-purchase-bar-generate',
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
	function __mbtn_po_bdownload(po_sysctrlno,mtkn_wshe){
		try { 
            __mysys_apps.mepreloader('mepreloaderme',true);
            
                    var mparam = {
                        po_sysctrlno: po_sysctrlno,
                        mtkn_wshe:mtkn_wshe

                    }; 
                   //console.log(mparam);
                  jQuery.ajax({ // default declaration of ajax parameters
                    type: "POST",
                    url: '<?=site_url();?>me-rm-purchase-barcode-dl',
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
	function __mbtn_po_print(po_sysctrlno,mtkn_wshe){
		try { 
            __mysys_apps.mepreloader('mepreloaderme',true);
            
                    var mparam = {
                        po_sysctrlno: po_sysctrlno,
                        mtkn_wshe:mtkn_wshe

                    }; 
                   //console.log(mparam);
                  jQuery.ajax({ // default declaration of ajax parameters
                    type: "POST",
                    url: '<?=site_url();?>me-rm-purchase-print-temp',
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
	
	$('.fg-btn-view-items').on('click',function(){
try { 
	// $('html,body').scrollTop(0);
	__mysys_apps.mepreloader('mepreloaderme',true);
	var mtkn_whse = jQuery('#txt-warehouse').attr('data-id'); 
	var fgpono = jQuery(this).attr('data-fgpono'); 
	
	var mtkn_dt = this.value;
	$('#anchor-list').removeClass('active');
	$('#anchor-items').addClass('active');

	var mparam = {
		mtkn_whse:mtkn_whse,
		mtkn_dt: mtkn_dt,
		fgpono:fgpono,
		mpages:1
	};

	$.ajax({ // default declaration of ajax parameters
	type: "POST",
	url: '<?=site_url();?>fg-items',
	context: document.body,
	data: eval(mparam),
	global: false,
	cache: false,
		success: function(data)  { //display html using divID
			__mysys_apps.mepreloader('mepreloaderme',false);
		$('#purchlist').html(data);
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
