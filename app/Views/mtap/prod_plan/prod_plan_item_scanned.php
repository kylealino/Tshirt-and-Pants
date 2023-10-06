<?php 


$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$this->db_erp = $mydbname->medb(1);
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mpw_tkn = $mylibzdb->mpw_tkn();
$cuser  = $mylibzdb->mysys_user();
$defaultDate = date('Y-m-d');
$nporecs = 0;
$branch_name = $request->getVar('branch_name');
$month_cap = $request->getVar('month_cap');
?>	
<hr>
<div class="row">
	<div class="col-lg-6">
		<div class="row mb-3">
			<label class="col-sm-3 form-label" for="prod_plan_trxno">Transaction No.:</label>
			<div class="col-sm-9">
				<input type="text" id="prod_plan_trxno" name="prod_plan_trxno" class="form-control form-control-sm"readonly/>
				<input type="hidden" name="month_cap" id="month_cap" value="<?=$month_cap;?>">
			</div>
		</div> 
		<div class="row gy-2 mb-3">
			<label class="col-sm-3 form-label" for="branch_name">Branch:</label>
			<div class="col-sm-9">
				<input type="text"  placeholder="Branch Name" id="branch_name" name="branch_name" class="branch_name form-control form-control-sm " value="<?=$branch_name;?>" disabled/>
				<input type="hidden"  placeholder="Branch Name" id="branch_code" name="branch_code" class="branch_code form-control form-control-sm " required/>  
			</div>
		</div> 
		<div class="row gy-2 mb-3">
			<label class="col-sm-3 form-label" for="active_plnt_id">Type</label>
			<div class="col-sm-9">
				<select id="opt_df" class="form-control form-control-sm" onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();">
					<option value="TSHIRT">TSHIRT</option>
					<option value="PANTS">PANTS</option>
				</select>
			</div>
		</div> 
	</div>

	<div class="col-lg-6">  
		<div class="row gy-2 mb-3">
			<label class="col-sm-3 form-label" for="txt_request_date">Entry Date</label>
			<div class="col-sm-9">
				<input type="date"  id="txt_request_date" name="txt_request_date" class="txt_request_date form-control form-control-sm " value="<?=$defaultDate;?>" required readonly/>
			</div>
		</div>     
		<div class="row gy-2 mb-3">
			<label class="col-sm-3 form-label" for="user">User:</label>
			<div class="col-sm-9">
				<input type="text" id="user" name="user" class="form-control form-control-sm" value="<?=$cuser;?>" readonly/>
			</div>
		</div>
		<div class="row gy-2">
			<label class="col-sm-3 form-label" for="txt_qty_serve">Total Qty to serve:</label>
			<div class="col-sm-3">
				<input type="text" id="txt_qty_serve" name="txt_qty_serve" class="form-control form-control-sm" onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();" readonly/>
			</div>
			<label class="col-sm-3 form-label" for="txt_amount_serve">Total Amount to serve:</label>
			<div class="col-sm-3">
				<input type="text" id="txt_amount_serve" name="txt_amount_serve" class="form-control form-control-sm" onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();" readonly/>
			</div>
		</div>
	</div>
</div>

<div class="row"> 
<?= form_open('',' id="frmTblItems" ') ?>
<div class="table-responsive">
	<table class="table table-bordered table-hover table-sm text-center" id="tbl-items-received">
		<thead class="thead-dark">
			<tr>
				<th nowrap="nowrap"></th>
				<th nowrap="nowrap">ITEMCODE</th>
				<th nowrap="nowrap">DESCRIPTION</th>
				<th nowrap="nowrap">TARGET</th>
				<th nowrap="nowrap">SRP</th>
				<th nowrap="nowrap">STORE BALANCE</th>
				<th nowrap="nowrap">SALES</th>
				<th nowrap="nowrap">INTRANSIT</th>
				<th nowrap="nowrap">FOR PACKING</th>
				<th nowrap="nowrap" style="color:red;">LACKING/OVER</th>
				<th nowrap="nowrap" style="color:blue;">QTY TO SERVE</th>
				<th nowrap="nowrap" style="color:green;">AMOUNT</th>
				<th nowrap="nowrap" style="color:orange;">LAST MONTH SALES</th>
			</tr>
		</thead>
		<tbody id="tblItems">
			<?php 
				$barcodes_arr = [];
				$barcodes_arr2 = [];
				if($result != ""):

					foreach($result as $row):
						$barcodes_arr[]  = $row['ART_CODE'];
						$nporecs++;
						 $red='';
                ?>	
				<tr>
					<td><?=$nporecs;?></td>
		 			<td nowrap="nowrap"><input type="text" id="ART_CODE<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?=$row['ART_CODE'];?>" disabled></td>
		 			<td nowrap="nowrap"><input type="text" id="ART_DESC<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="50" value="<?=$row['ART_DESC'];?>" disabled></td>
		 			<td nowrap="nowrap"><input type="text" id="STDRD_CAP<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?= number_format($row['cap_target'], 2, '.', ''); ?>" disabled></td>
					<td nowrap="nowrap"><input type="text" id="ART_UPRICE<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?=$row['ART_UPRICE'];?>" disabled></td>
					<td nowrap="nowrap"><input type="text" id="STORE_BAL<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?= round($row['store_balance']); ?>" disabled></td>
					<td nowrap="nowrap"><input type="text" id="SALES<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?=round($row['sales']);?>" disabled></td>
					<td nowrap="nowrap"><input type="text" id="INTRANSIT<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?=$row['INTRANSIT'];?>" disabled></td>
					<td nowrap="nowrap"><input type="text" id="FOR_PCKING<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?=$row['FOR_PCKING'];?>" disabled></td>
					<td nowrap="nowrap"><input type="text" id="LACKING<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" disabled></td>
					<td nowrap="nowrap"><input type="text" id="QTYTOSERVE<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" disabled></td>
					<td nowrap="nowrap"><input type="text" id="AMOUNT<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" disabled></td>
					<td nowrap="nowrap"><input type="text" id="LSALES<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?=$row['sales_prev_month'];?>" disabled></td>
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
	<div class="flex-fill mb-3">
		<h6 for=""> <i class="bi bi-info-circle text-dgreen" title="Different packing list in not allowed!"> </i>Kindly check for mistakes before saving, in case of mistake; please refresh & reupload the file.</h6>
		<span id="pl-selected" class="text-dgreen fw-bolder fst-italic">  </span>
	</div>
	<div class="form-row">
		<button type="button" class="btn bg-dgreen btn-sm" id="mbtn_mn_Save">Save</button>  
		<?=anchor('me-prod-plan', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-sm" ');?>
	</div>
	<?php 
		endif;
	?>
<?= form_close(); ?>
</div>
</div>
</div>


<?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>  
<script type="text/javascript">
	$(document).ready(function(){
 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });
	$('#tbl-items-received').DataTable({
		           
		searching:false,
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
});

	__pack_totals();

	//PARA SA TIMER NG TAMT TOTALS
	var tid = setInterval(myTamtTimer, 30000);
	function myTamtTimer() {
		__pack_totals();
	// do some stuff...
	// no need to recall the function (it's an interval, it'll loop forever)
	}
	jQuery('.meform_date').datepicker({
		format: 'mm/dd/yyyy',
		autoclose: true
	});

	__mysys_apps.mepreloader('mepreloaderme',false);
    $('#btn-upload-wshe-rcv').click(function(){ 
      try {   

        var file       = $('#rcv-upld-file').val();
    
        if($.trim(file) == ''){ 
          jQuery('#myModSysMsgSubBod').css({
            display: ''
          });
          jQuery('#myModSysMsgSubBod').html('Please select file to upload!');
          jQuery('#myModSysMsgSub').modal('show');
          return false;
        }

        my_data = new FormData();
        my_data.append('rcv_file', $('#rcv-upld-file')[0].files[0]);

        __mysys_apps.mepreloader('mepreloaderme',true);
        $.ajax({ // default declaration of ajax parameters
          url: '<?=site_url()?>prod-plan-upld',
          method:"POST",
          context:document.body,
          data: my_data,
          contentType: false,
          global: false,
          cache: false,
          processData:false,
          success: function(data)  { //display html using divID
            __mysys_apps.mepreloader('mepreloaderme',false);
            jQuery('#mymodoutrecs').html(data);
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
    }); 

function __pack_totals() { 
	var opt_df = jQuery('#opt_df').val();

	if (opt_df == 'TSHIRT') {
		try { 
			var rowCount1 = jQuery('#tbl-items-received tr').length;

			var mdata = '';
			var total_qty_serve = 0;
			var total_amount_serve = 0;
			var total_lacking = 0;
			var total= 0;
			var lck = 0;
			var total_lck = 0;
			var total = 0;
			var amount = 0;
			var total_amount = 0;
			var store_bal_zero = 0;

			var month_cap = jQuery('#month_cap').val();

			for(aa = 1; aa < rowCount1 ; aa++) { 
				var clonedRow = jQuery('#tbl-items-received tr:eq(' + aa + ')').clone(); 
				var txt_qty_serve = jQuery('#txt_qty_serve').val();
				var txt_amount_serve = jQuery('#txt_amount_serve').val();
				var STDRD_CAP = jQuery(clonedRow).find('input[type=text]').eq(2).val();
				var SRP = jQuery(clonedRow).find('input[type=text]').eq(3).val();
				var STORE_BAL = jQuery(clonedRow).find('input[type=text]').eq(4).val();
				var SALES = jQuery(clonedRow).find('input[type=text]').eq(5).val();
				var INTRANSIT = jQuery(clonedRow).find('input[type=text]').eq(6).val();
				var FOR_PCKING = jQuery(clonedRow).find('input[type=text]').eq(7).val();
				var LACKING = jQuery(clonedRow).find('input[type=text]').eq(8).attr('id');
				var QTYTOSERVE = jQuery(clonedRow).find('input[type=text]').eq(9).attr('id');
				var AMOUNT = jQuery(clonedRow).find('input[type=text]').eq(10).attr('id');
				var nSTDRD_CAP = parseFloat(STDRD_CAP);
				var STDRD_CAP_TOTAL = parseFloat(STDRD_CAP);
				var SRP_TOTAL = parseFloat(SRP);
				var STORE_BAL_TOTAL = parseFloat(STORE_BAL);
				var SALES_TOTAL = parseFloat(SALES);
				var INTRANSIT_TOTAL = parseFloat(INTRANSIT);
				var FOR_PCKING_TOTAL = parseFloat(FOR_PCKING);
				var LACKING_TOTAL = parseFloat(LACKING);
				var AMOUNT_TOTAL = parseFloat(AMOUNT);
				var QTYTOSERVE_TOTAL = parseFloat(QTYTOSERVE);

				total_lacking = (STORE_BAL_TOTAL  + INTRANSIT_TOTAL + FOR_PCKING_TOTAL)-nSTDRD_CAP;
				total = total + total_lacking;


				$('#' + STORE_BAL).val(store_bal_zero);
				

				$('#' + LACKING).val(total_lacking.toFixed(2));
				
			
				if (total_lacking <= 0 && total_lacking >= -17) {
					lck = 12 * month_cap;
				}
				else if(total_lacking <= -18 && total_lacking >= -29){
					lck = 24 * month_cap;
				}
				else if(total_lacking <= -30 && total_lacking >= -41){
					lck = 36 * month_cap;
				}
				else if(total_lacking <= -42 && total_lacking >= -53){
					lck = 48 * month_cap;
				}
				else if(total_lacking <= -54 && total_lacking >= -65){
					lck = 60 * month_cap;
				}
				else if(total_lacking <= -66 && total_lacking >= -77){
					lck = 72 * month_cap;
				}
				else if(total_lacking <= -78 && total_lacking >= -89){
					lck = 84 * month_cap;
				}
				else if(total_lacking <= -90 && total_lacking >= -101){
					lck = 96 * month_cap;
				}
				else if(total_lacking <= -102 && total_lacking >= -113){
					lck = 108 * month_cap;
				}
				else if(total_lacking <= -114 && total_lacking >= -125){
					lck = 120 * month_cap;
				}
				else if(total_lacking <= -126 && total_lacking >= -137){
					lck = 132 * month_cap;
				}
				else if(total_lacking <= -138 && total_lacking >= -149){
					lck = 144 * month_cap;
				}
				else if(total_lacking <= -150 && total_lacking >= -161){
					lck = 156 * month_cap;
				}
				else if(total_lacking <= -162 && total_lacking >= -173){
					lck = 168 * month_cap;
				}
				else if(total_lacking <= -174 && total_lacking >= -185){
					lck = 180 * month_cap;
				}
				else if(total_lacking <= -186 && total_lacking >= -197){
					lck = 192 * month_cap;
				}
				else if(total_lacking <= -198 && total_lacking >= -209){
					lck = 204 * month_cap;
				}
				else if(total_lacking <= -210 && total_lacking >= -221){
					lck = 216 * month_cap;
				}
				else if(total_lacking <= -222 && total_lacking >= -233){
					lck = 228 * month_cap;
				}
				else if(total_lacking <= -234 && total_lacking >= -245){
					lck = 240 * month_cap;
				}
				else if(total_lacking >= 0){
					lck = 0;
				}

				$('#' + QTYTOSERVE).val(lck);
				total_lck += lck;
				amount = (SRP_TOTAL * lck);

				$('#' + AMOUNT).val(amount.toFixed(2));
				total_amount += amount;
			}
			$('#txt_qty_serve').val(total_lck);
			$('#txt_amount_serve').val(total_amount.toFixed(2));
		} catch(err) {
			var mtxt = 'There was an error on this page.\n';
			mtxt += 'Error description: ' + err.message;
			mtxt += '\nClick OK to continue.';
			alert(mtxt);
			$.hideLoading();
			return false;
		}         
	}
	if (opt_df == 'PANTS') {
		try { 
			var rowCount1 = jQuery('#tbl-items-received tr').length;

			var mdata = '';
			var total_qty_serve = 0;
			var total_amount_serve = 0;
			var total_lacking = 0;
			var total= 0;
			var lck = 0;
			var total_lck = 0;
			var total = 0;
			var amount = 0;
			var total_amount = 0;
			var store_bal_zero = 0;

			var month_cap = jQuery('#month_cap').val();

			for(aa = 1; aa < rowCount1 ; aa++) { 
				var clonedRow = jQuery('#tbl-items-received tr:eq(' + aa + ')').clone(); 
				var txt_qty_serve = jQuery('#txt_qty_serve').val();
				var txt_amount_serve = jQuery('#txt_amount_serve').val();
				var STDRD_CAP = jQuery(clonedRow).find('input[type=text]').eq(2).val();
				var SRP = jQuery(clonedRow).find('input[type=text]').eq(3).val();
				var STORE_BAL = jQuery(clonedRow).find('input[type=text]').eq(4).val();
				var SALES = jQuery(clonedRow).find('input[type=text]').eq(5).val();
				var INTRANSIT = jQuery(clonedRow).find('input[type=text]').eq(6).val();
				var FOR_PCKING = jQuery(clonedRow).find('input[type=text]').eq(7).val();
				var LACKING = jQuery(clonedRow).find('input[type=text]').eq(8).attr('id');
				var QTYTOSERVE = jQuery(clonedRow).find('input[type=text]').eq(9).attr('id');
				var AMOUNT = jQuery(clonedRow).find('input[type=text]').eq(10).attr('id');
				var nSTDRD_CAP = parseFloat(STDRD_CAP);
				var STDRD_CAP_TOTAL = parseFloat(STDRD_CAP);
				var SRP_TOTAL = parseFloat(SRP);
				var STORE_BAL_TOTAL = parseFloat(STORE_BAL);
				var SALES_TOTAL = parseFloat(SALES);
				var INTRANSIT_TOTAL = parseFloat(INTRANSIT);
				var FOR_PCKING_TOTAL = parseFloat(FOR_PCKING);
				var LACKING_TOTAL = parseFloat(LACKING);
				var AMOUNT_TOTAL = parseFloat(AMOUNT);
				var QTYTOSERVE_TOTAL = parseFloat(QTYTOSERVE);

				total_lacking = (STORE_BAL_TOTAL  + INTRANSIT_TOTAL + FOR_PCKING_TOTAL)-nSTDRD_CAP;
				total = total + total_lacking;


				$('#' + STORE_BAL).val(store_bal_zero);
				

				$('#' + LACKING).val(total_lacking.toFixed(2));
				
			
				if (total_lacking <= 0 && total_lacking >= -15) {
					lck = 10 * month_cap;
				}
				else if(total_lacking <= -16 && total_lacking >= -25){
					lck = 20 * month_cap;
				}
				else if(total_lacking <= -26 && total_lacking >= -35){
					lck = 30 * month_cap;
				}
				else if(total_lacking <= -36 && total_lacking >= -45){
					lck = 40 * month_cap;
				}
				else if(total_lacking <= -46 && total_lacking >= -55){
					lck = 50 * month_cap;
				}
				else if(total_lacking <= -56 && total_lacking >= -65){
					lck = 60 * month_cap;
				}
				else if(total_lacking <= -66 && total_lacking >= -75){
					lck = 70 * month_cap;
				}
				else if(total_lacking <= -76 && total_lacking >= -85){
					lck = 80 * month_cap;
				}
				else if(total_lacking <= -86 && total_lacking >= -95){
					lck = 90 * month_cap;
				}
				else if(total_lacking <= -96 && total_lacking >= -105){
					lck = 100 * month_cap;
				}
				else if(total_lacking <= -106 && total_lacking >= -115){
					lck = 110 * month_cap;
				}
				else if(total_lacking <= -116 && total_lacking >= -125){
					lck = 120 * month_cap;
				}
				else if(total_lacking <= -126 && total_lacking >= -135){
					lck = 130 * month_cap;
				}
				else if(total_lacking <= -136 && total_lacking >= -145){
					lck = 140 * month_cap;
				}
				else if(total_lacking <= -146 && total_lacking >= -155){
					lck = 150 * month_cap;
				}
				else if(total_lacking <= -156 && total_lacking >= -165){
					lck = 160 * month_cap;
				}
				else if(total_lacking <= -166 && total_lacking >= -175){
					lck = 170 * month_cap;
				}
				else if(total_lacking <= -176 && total_lacking >= -185){
					lck = 180 * month_cap;
				}
				else if(total_lacking <= -186 && total_lacking >= -195){
					lck = 190 * month_cap;
				}
				else if(total_lacking <= -196 && total_lacking >= -205){
					lck = 200 * month_cap;
				}
				else if(total_lacking >= 0){
					lck = 0;
				}
				$('#' + QTYTOSERVE).val(lck);
				total_lck += lck;
				amount = (SRP_TOTAL * lck);

				$('#' + AMOUNT).val(amount.toFixed(2));
				total_amount += amount;
			}
			$('#txt_qty_serve').val(total_lck);
			$('#txt_amount_serve').val(total_amount.toFixed(2));
		} catch(err) {
			var mtxt = 'There was an error on this page.\n';
			mtxt += 'Error description: ' + err.message;
			mtxt += '\nClick OK to continue.';
			alert(mtxt);
			$.hideLoading();
			return false;
		}               
	}
   
}

$('#btn-central-rcv').on('click',function(){
     	try { 
	      		$('#btn-central-rcv').prop("disabled",true);
				__mysys_apps.mepreloader('mepreloaderme',true);
	          	
	          	var mdata = '';
	          	var item_array = [];
	          	var wshe_tkn = jQuery('#txt-warehouse').attr('data-id'); 
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
	              rowCount : '<?=$count?>',
	              txtWarehousetkn:wshe_tkn
	           

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

jQuery('#branch_name')
		// don't navigate away from the field on tab when selecting an item
		.bind( 'keydown', function( event ) {
			if ( event.keyCode === jQuery.ui.keyCode.TAB &&
				jQuery( this ).data( 'ui-autocomplete' ).menu.active ) { 
				event.preventDefault();
			}
			if( event.keyCode === jQuery.ui.keyCode.TAB ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			minLength: 0,
			source: '<?= site_url(); ?>search-prod-plan-branch/',  //mysearchdata/companybranch_v
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			search: function(oEvent, oUi) {
				var sValue = jQuery(oEvent.target).val();

			},
			select: function( event, ui ) {
				var terms = ui.item.value;
				jQuery('#branch_name').val(terms);
				jQuery(this).autocomplete('search', jQuery.trim(terms));
				return false;
			}
		})
		.click(function() {
			var terms = this.value;
			jQuery(this).autocomplete('search', jQuery.trim(terms));
	});	//end branch_name

	$("#mbtn_mn_Save").click(function(e){
    try { 

          var prod_plan_trxno = jQuery('#prod_plan_trxno').val();
          var branch_name = jQuery('#branch_name').val();
          var opt_df = jQuery('#opt_df').val();
          var txt_request_date = jQuery('#txt_request_date').val();
          var txt_total_qty = jQuery('#txt_total_qty').val();
		  var txt_qty_serve = jQuery('#txt_qty_serve').val();
          var txt_amount_serve = jQuery('#txt_amount_serve').val();

          var rowCount1 = jQuery('#tbl-items-received tr').length;
          var adata1 = [];
          var mdata = '';
          var ninc = 0;

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-items-received tr:eq(' + aa + ')').clone(); 
                var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val();
                var mstdrd = jQuery(clonedRow).find('input[type=text]').eq(2).val();
                var msbal = jQuery(clonedRow).find('input[type=text]').eq(4).val();
                var msales = jQuery(clonedRow).find('input[type=text]').eq(5).val();
                var mtrnst = jQuery(clonedRow).find('input[type=text]').eq(6).val();
				var mfpckng = jQuery(clonedRow).find('input[type=text]').eq(7).val();
                var mlck = jQuery(clonedRow).find('input[type=text]').eq(8).val();
                var mqsrv = jQuery(clonedRow).find('input[type=text]').eq(9).val();
				var mamt = jQuery(clonedRow).find('input[type=text]').eq(10).val();

                mdata = mitemc + 'x|x' + mstdrd + 'x|x' + msbal + 'x|x' + msales + 'x|x' + mtrnst + 'x|x' + mfpckng + 'x|x' + mlck + 'x|x' + mqsrv + 'x|x' + mamt;
                adata1.push(mdata);


			}  //end for

          var mparam = {
            prod_plan_trxno:prod_plan_trxno,
            branch_name: branch_name,
            opt_df:opt_df,
            txt_request_date:txt_request_date,
            txt_total_qty: txt_total_qty,
			txt_qty_serve:txt_qty_serve,
            txt_amount_serve:txt_amount_serve,
            adata1: adata1
          };  


      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-prod-plan-save',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { 
            $(this).prop('disabled', false);
           // $.hideLoading();
            jQuery('#memsgtestent_bod').html(data);
            jQuery('#memsgtestent').modal('show');
            return false;
        },
        error: function() {
          alert('error loading page...');
         // $.hideLoading();
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

  __mysys_apps.mepreloader('mepreloaderme',false);
</script>
