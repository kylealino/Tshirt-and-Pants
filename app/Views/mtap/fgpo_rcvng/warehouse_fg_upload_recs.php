<?php 


$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$this->db_erp = $mydbname->medb(1);
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();


?>	

<div class="py-2 mt-3">
	<h5 class="text-start text-dgreen fw-bold"> <span > RM CODE : </span> <?=$rm_code;?> </h5>
</div>
<hr class="prettyline shadow">
<div class="table-responsive"> 
	<table class="table table-bordered table-hover table-sm text-center" id="tbl-promo">
		<thead class="thead-light">
			<tr>
				<th nowrap="nowrap"></th>
				<th nowrap="nowrap">ITEM / MATERIAL</th>
				<th nowrap="nowrap">DESCRIPTION</th>
				<th nowrap="nowrap">PACKAGING</th>
				<th nowrap="nowrap">CONVF</th>
				<th nowrap="nowrap">QTY SCANNED</th>
				<th nowrap="nowrap">TOTAL PCS</th>
				<th nowrap="nowrap">ACTUAL QTY</th>
				<th nowrap="nowrap">REMAINING QTY</th>
			</tr>
		</thead>
		<tbody id="gwpo-recs">
			<tr style="display: none;">
			<td></td>
			<td nowrap="nowrap"><input type="text" class="form-control form-control-sm mitemcode" ></td> <!--0 ITEMC -->
			<td nowrap="nowrap"><input type="text" class="form-control form-control-sm" style="background-color: #EAEAEA;" readonly></td> 
			<td nowrap="nowrap"><input type="text" class="form-control form-control-sm" style="background-color: #EAEAEA;" readonly></td> 
			<td nowrap="nowrap"><input type="text" class="form-control form-control-sm" style="background-color: #EAEAEA;" readonly></td> 
			<td nowrap="nowrap"><input type="text" class="form-control form-control-sm" style="background-color: #EAEAEA;" readonly></td> 
			<td nowrap="nowrap"><input type="text" class="form-control form-control-sm" style="background-color: #EAEAEA;" readonly></td> 
			<td nowrap="nowrap"><input type="text" class="form-control form-control-sm" ></td> 
			<td nowrap="nowrap"><input type="text" class="form-control form-control-sm" style="background-color: #EAEAEA;" readonly></td> 
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
					var table = $('#tbl-items-received').DataTable();
					var lastFields = table.column(-1).data().toArray();
		          	var rowcollection = tbl_items_scanned.$(".cb_chk:checked", {"page": "all"});
					var rowcollection1 = tbl_items_scanned.$(".rcv_qty:checked", {"page": "all"});
		          	let count = 0;
					rowcollection.each(function(index,elem){
						var checkbox_value = `'${$(elem).val()}'`;
						item_array.push(checkbox_value);
			
					});

					rowcollection.each(function(index,elem){
						var checkbox_value = `'${$(elem).val()}'`;
						item_array.push(checkbox_value);
			
					});

					if(item_array.length == 0){

					}

					brcde_list = item_array.join();
		            var mparam = {
					  lastFields : lastFields,
		              data_array : brcde_list,
		              rowCount : item_array.length,
		              txtWarehousetkn:wshe_tkn
		           

		            }
		           
		            $.ajax({ 
		              type: "POST",
		              url: '<?=site_url();?>rm-rcvng-store',
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
