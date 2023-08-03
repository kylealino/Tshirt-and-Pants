<?php

/**
 *  File        : warehouse_inv_itm_recs.php
 *  Author      : Arnel Oquien
 *  Date Created: Dec. 02, 2022
 */
?>


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
		          	</table>
	        	</div>
	        	<hr>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

$(document).ready(function () {	


 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });


var table_inv = $('#tbl-inv-items-recs').DataTable({
	// // processing:true,
	serverSide:true,
	 processing:true,
    ajax: {
          url:  '<?=site_url()?>warehouse-inv-api',
          type: "POST",
          data: {mtkn_whse: jQuery('#txt-warehouse').attr("data-id")},
          dataSrc: 'data',

      },

       columnDefs: [
		{
		targets: '_all',
		className: 'dt-head-center',
		createdCell:  function (td, cellData, rowData, row, col){
			$(td).attr('nowrap', 'nowrap'); 
			if(rowData[5] == 0){ //row 5 is QTY
				$(td).addClass('p-2');	
			}
			
		}
		},
		{
		target: 8,
		visible: false,
		searchable: false,
		},
		{
             targets:[0,18,10,11,4,3,5,16],
             orderable: false
         },
   		 ],
          "initComplete": function(settings, json) {
    		    __mysys_apps.mepreloader('mepreloaderme',false);
  			}

    });

	$('#tbl-inv-items-recs tbody').on('click', 'button', function () {
        var data = table_inv.row($(this).parents('tr')).data();
        getBoxcontent(data[20]);
    });

   $('#tbl-inv-items-recs_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });

  function reload(){
 	$('#tbl-inv-items-recs').DataTable().ajax.reload();
	}

});



function getBoxcontent(mtkn_dt){

    	try { 

		var txtsearchedrec = jQuery('#mytxtsearchrec_verify').val();
		var mtkn_whse      = jQuery('#txt-warehouse').attr("data-id");
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

</script>