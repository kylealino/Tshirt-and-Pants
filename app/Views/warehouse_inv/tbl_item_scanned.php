<?php 


$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$this->db_erp = $mydbname->medb(1);
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();


?>	
  <hr class="prettyline">
<div class="container-fluid">
<div class="row"> 
<?= form_open('',' id="frmTblItems" ') ?>
	<table class="table table-bordered table-striped table-hover table-sm text-center" id="tbl-items-received">
		<thead class="thead-dark">
			<tr>
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
				<th nowrap="nowrap">STORAGE #</th>
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
						$mtkn_wshe_sbin = hash('sha384', $row['wshe_sbin_id'] . $mpw_tkn);  
						$mtkn_barcdng_dt_id = hash('sha384', $row['wshe_barcdng_dt_id'] . $mpw_tkn); 
						$barcodes_arr[]  = $row['witb_barcde'];
						 $red='';
                      	if($row['qty_scanned'] > 1){
                           $red = "style=\"color:red;\"";
                      	}
			?>	
				<tr>
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
		 			<td nowrap="nowrap" data-id="<?=$mtkn_wshe_sbin?>" ><?=$row['wshe_bin_name']?></td>
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
	<hr>
	<?php 
		if($result != ""):
	?>
	<div class="form-row">
		<button type="button" class="btn bg-dgreen btn-sm" id="btn-central-rcv">Receive</button>  
	</div>
	<?php 
		endif;
	?>
<?= form_close(); ?>
</div>
</div>



<script type="text/javascript">
 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });
  	var tbl_items_scanned = $("#tbl-items-received").DataTable({



	    "drawCallback": function () {
	      $('.paginate_button').addClass('btn btn-default btn-sm');
	      $('.current').css({"background-color":"#337ab7","color":"#fff"});
	      $('.dataTables_filter input').addClass('form-control form-control-sm');
	      // $('.dataTables_length select').addClass(' form-control-sm');
	      $('.first').html("<i class='bi bi-chevron-double-left'></i>");
	      $('.previous').html("<i class='bi bi-chevron-left'></i>");
	      $('.next').html("<i class='bi bi-chevron-right'></i>");
	      $('.last').html("<i class='bi bi-chevron-double-right'></i>");
	      $('.dataTables_paginate').css("padding-top","5px");
	    },
	    "columnDefs": [
	      { "width": "170px", "targets": 0 },
	      { "width": "370px", "targets": 1 },
	      { "width": "100px", "targets": 2 },
	      { "width": "120px", "targets": 3 },
	      { "width": "150px", "targets": 4 },
	      { "width": "120px", "targets": 5 },
	      { "width": "120px", "targets": 6 },
	      { "width": "120px", "targets": 7 },
	      { "width": "120px", "targets": 8 },
	      { "width": "120px", "targets": 9 },
	      { "width": "120px", "targets": 10 },
	      { "width": "120px", "targets": 11 },
	      { "width": "80px", "targets": 12 },
	      { "width": "120px", "targets": 13 },
	      
	    ],
	    "pagingType": "full_numbers",
	    "dom": '<lfi<"table-responsive"rt><"pagination">p>'
  	});

   $('[type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });
$('#btn-central-rcv').on('click',function(){
     	try { 
	      		$('#btn-central-rcv').prop("disabled",true);
				__mysys_apps.mepreloader('mepreloaderme',true);
	          	
	          	var mdata = '';
	          	var item_array = [];
	          	var brcde_list = `<?=$barcodes?>`;
	          	// var trs =  tbl_items_scanned.$('tr', {"page": "all"});

	          	// trs.each(function(index,elem){
	           //  	var $tds = $(this).find('td'),

		          //       item_id = $tds.eq(0).attr("data-id"),
		          //       barcdng_id = $tds.eq(0).attr("data-barcdng-id"),
		          //       qty = $tds.eq(4).text();

		          //     	mdata = barcdng_id+'x|x'+qty;
		          // 	    item_array.push(mdata);

	          	// });
 				
	            var mparam = {

	              data_array : brcde_list,
	              rowCount : '<?=$count?>'
	           

	            }
	           
	            $.ajax({ 
	              type: "POST",
	              url: '<?=site_url();?>warehouse-rcvng-sv',
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
