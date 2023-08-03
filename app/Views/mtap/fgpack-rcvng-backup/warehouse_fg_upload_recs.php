<?php 


$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$this->db_erp = $mydbname->medb(1);
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();


?>	
<hr class="prettyline shadow">
<div class="py-2 mt-3">
	<h5 class="text-end text-dgreen fw-bold"> <span > FG CODE : </span> <?=$fg_code;?> </h5>
</div>
<div class="table-responsive"> 
	<table class="table table-bordered table-hover table-sm text-center" id="tbl-items-received">
		<thead class="thead-dark text-dgreen">
			<tr>
				<th nowrap="nowrap" class="p-2"> <input type="checkbox" id="rcv-chck-all" class="green-cb fs-2  p-2 " style="scale: 1.3;"> </th>
				<th nowrap="nowrap">ITEM / MATERIAL</th>
				<th nowrap="nowrap">DESCRIPTION</th>
				<th nowrap="nowrap">PACKAGING</th>
				<th nowrap="nowrap">CONVF</th>
				<th nowrap="nowrap" style="color:red;">QTY SCANNED</th>
				<th nowrap="nowrap">TOT PCS</th>
				<th nowrap="nowrap">PRICE</th>
				<th nowrap="nowrap">TAMT</th>
				<th nowrap="nowrap">PLANT</th>
				<th nowrap="nowrap">WAREHOUSE</th>
				<th nowrap="nowrap">S.Text</th>
				<th nowrap="nowrap">BOX BARCODE</th>
				<th nowrap="nowrap">TYPE</th>
				<th nowrap="nowrap">STOCK CODE</th>
			</tr>
		</thead>
		<tbody id="tblItems">
			<?php 
				$barcodes_arr = [];
				if($result != ""):

					foreach($result as $row):
						$mtkn_mat = hash('sha384', $row['mat_rid'] . $mpw_tkn);  
						$mtkn_plnt = hash('sha384', $row['plnt_id'] . $mpw_tkn);  
						$mtkn_wshe = hash('sha384', $row['wshe_id'] . $mpw_tkn);  
						$mtkn_barcdng_dt_id = hash('sha384', $row['wshe_barcdng_dt_id'] . $mpw_tkn); 
						$barcodes_arr[]  = $row['witb_barcde'];
						 $red='';
          	if($row['qty_scanned'] > 1){
               $red = "style=\"color:red;\"";
          	}
			?>	
				<tr>
					<td nowrap="nowrap"> <input class="cb_chk green-cb fs-2" type="checkbox" style="scale: 1.3"  value="<?=$row['witb_barcde']?>"> </td>
		 			<td nowrap="nowrap" data-id="<?=$mtkn_mat?>" data-barcdng-id="<?=$mtkn_barcdng_dt_id?>"><?=$row['mat_code']?></td>
		 			<td nowrap="nowrap"><?=$row['ART_DESC']?></td>
		 			<td nowrap="nowrap"><small><?=$row['ART_UOM']?></small></td>
		 			<td nowrap="nowrap"><?=$row['convf']?></td>
		 			<td nowrap="nowrap" <?=$red?> ><?=$row['qty_scanned']?></td>
		 			<td nowrap="nowrap" data-cbm="<?=$row['cbm']?>"><?=$row['convf']?></td>
		 			<td nowrap="nowrap"><?=$row['price']?></td>
		 			<td nowrap="nowrap"><?=$row['tamt']?></td>
		 			<td nowrap="nowrap" data-id="<?=$mtkn_plnt?>" ><?=$row['plnt_code']?></td>
		 			<td nowrap="nowrap" data-id="<?=$mtkn_wshe?>" ><?=$row['wshe_code']?></td>
		 			<td nowrap="nowrap" ><?=$row['remarks']?></td> <!-- stext -->
		 			<td nowrap="nowrap" data-irb="<?=$row['irb_barcde']?>" data-witb ="<?=$row['witb_barcde']?>" data-srb ="<?=$row['srb_barcde']?>" data-wob="<?=$row['wob_barcde']?>" data-pob="<?=$row['pob_barcde']?>" data-dmg="<?=$row['dmg_barcde']?>" data-bcsrs="<?=$row['barcde_series']?>" ><?=$row['barcde']?></td>
		 			<td nowrap="nowrap"><?=$row['barc_type']?></td>
		 			<td nowrap="nowrap"><?=$row['stock_code']?></td>
		 		</tr>
			<?php 
					endforeach;
				endif;
			$barcode_filter = array_filter($barcodes_arr);
			$barcode_str = implode("','", $barcode_filter);
			$barcode_str = preg_replace('#[\r\n]#', '', $barcode_str);
			$barcodes = "'".$barcode_str."'";
			?>
		</tbody>
	</table>
	
	<?php 
		if($result != ""):
	?>
	<div class="form-row py-3">
		<button type="button" class="btn bg-dgreen btn-sm" id="btn-central-rcv">Receive</button>  
	</div>
	<?php 
		endif;
	?>
</div>
<script type="text/javascript">
 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });

   var tbl_items_scanned = $('#tbl-items-received').DataTable({  
            
        'order':[],
        'columnDefs': [{
            "targets":[0],
            "orderable": false
        },
 		{
 		targets:'_all',
 		className: 'dt-head-center'
 		},
        ]
    });



   $('#tbl-items-received_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });


   //check all function
	 $("#rcv-chck-all").click(function () {
	 		var rowcollection = tbl_items_scanned.$(".cb_chk", {"page": "all"});				
					rowcollection.each(function(index,elem){
							$(elem).prop('checked',  $("#rcv-chck-all").prop('checked'));

					});
  	});

	$('#btn-central-rcv').on('click',function(){
	     	try { 
		      	//	$('#btn-central-rcv').prop("disabled",true);
					__mysys_apps.mepreloader('mepreloaderme',true);
		          	
		          	var mdata = '';
		          	var item_array = [];
		          	var wshe_tkn = jQuery('#txt-warehouse').attr('data-id'); 
		          	var brcde_list = '';

		          	var rowcollection = tbl_items_scanned.$(".cb_chk:checked", {"page": "all"});
		          	let count = 0;
								rowcollection.each(function(index,elem){
								    var checkbox_value = `'${$(elem).val()}'`;
								    item_array.push(checkbox_value);
						
								});

								if(item_array.length == 0){

								}

								brcde_list = item_array.join();
		            var mparam = {
		              data_array : brcde_list,
		              rowCount : item_array.length,
		              txtWarehousetkn:wshe_tkn
		           

		            }
		           
		            $.ajax({ 
		              type: "POST",
		              url: '<?=site_url();?>fg-rcvng-store',
		              context: document.body,
		              data: eval(mparam),
		              global: false,
		              cache: false,
		              success: function(data)  { 
		                  __mysys_apps.mepreloader('mepreloaderme',false);
		                  jQuery('#memsgtestent_success_bod').html(data);
		                  jQuery('#memsgtestent_success').modal('show');
		                 // $('#btn-central-rcv').prop("disabled",false);
		                  return false;
		              },
		              error: function() {
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
		      	}  //end try
			      return false; 

			});



</script>
