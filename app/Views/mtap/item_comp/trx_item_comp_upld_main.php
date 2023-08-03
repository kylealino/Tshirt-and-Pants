<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mydataz = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(0);

$nporecs = 0;
$fg_code = $request->getVar('fg_code');
$ucomp_date = $request->getVar('ucomp_date');
$iucomp_trxno = '';
?>
<main>
<div class="row"> 
<?= form_open('',' id="frmTblItems" ') ?>

<div class="col-lg-3 mb-3">
    <div class="col-sm-12">
        <h6 class="card-title p-0">Generated Transaction No.:</h6>
        <input type="text" id="iucomp_trxno" name="iucomp_trxno" class="form-control form-control-sm" value="<?=$iucomp_trxno;?>" disabled/>
    </div>
</div>
<div class="table-responsive">
    <input type="hidden" name="fg_code" id="fg_code" value="<?=$fg_code;?>">
    <input type="hidden" name="ucomp_date" id="ucomp_date" value="<?=$ucomp_date;?>">
	<table class="table table-bordered table-hover table-sm text-center" id="tbl-items-received">
		<thead class="thead-dark">
			<tr>
				<th nowrap="nowrap"></th>
				<th nowrap="nowrap">ITEMCODE</th>
				<th nowrap="nowrap">DESCRIPTION</th>
                <th nowrap="nowrap">QTY</th>
				<th nowrap="nowrap">COST</th>
				<th nowrap="nowrap">TOTAL COST</th>
				<th nowrap="nowrap">UOM</th>
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
		 			<td nowrap="nowrap"><input type="text" id="ART_QTY<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?=$row['ART_QTY'];?>" disabled></td>
					<td nowrap="nowrap"><input type="text" id="ART_UPRICE<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?=$row['ART_UPRICE'];?>" disabled></td>
					<td nowrap="nowrap"><input type="text" id="TCOST<?=$nporecs;?>" name="TCOST" class="TCOST form-control form-control-sm mitemcode bg-white" size="20" disabled></td>
					<td nowrap="nowrap"><input type="text" id="ART_UOM<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white" size="20" value="<?=$row['ART_UOM'];?>" disabled></td>
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
		<button type="button" class="btn bg-dgreen btn-sm" id="mbtn_upld_Save">Save</button>  
		<?=anchor('me-prod-plan', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-sm" ');?>
	</div>
	<?php 
		endif;
	?>
<?= form_close(); ?>
</div>
</main>
<?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
?>  
<script>
   __mysys_apps.mepreloader('mepreloaderme',false);
$(document).ready(function(){

    __pack_totals();
});



function __pack_totals() { 

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
        var total_cost = 0;

        for(aa = 1; aa < rowCount1 ; aa++) { 
            var clonedRow = jQuery('#tbl-items-received tr:eq(' + aa + ')').clone(); 

            var IQTY = jQuery(clonedRow).find('input[type=text]').eq(2).val();
            var ICOST = jQuery(clonedRow).find('input[type=text]').eq(3).val();
            var ITCOST = jQuery(clonedRow).find('input[type=text]').eq(4).attr('id');


            var IQTY_TOTAL = parseFloat(IQTY);
            var ICOST_TOTAL = parseFloat(ICOST);

            total_cost = (IQTY_TOTAL * ICOST_TOTAL);
            console.log(total_cost);
            $('#' + ITCOST).val(total_cost);

        }

    } catch(err) {
        var mtxt = 'There was an error on this page.\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\nClick OK to continue.';
        alert(mtxt);
        $.hideLoading();
        return false;
    }         
	
}
$("#mbtn_upld_Save").click(function(e){
    try { 

          var iucomp_trxno = jQuery('#iucomp_trxno').val();
          var fg_code = jQuery('#fg_code').val();
          var ucomp_date = jQuery('#ucomp_date').val();
          var rowCount1 = jQuery('#tbl-items-received tr').length;
          var adata1 = [];
          var mdata = '';

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-items-received tr:eq(' + aa + ')').clone(); 
                var m1 = jQuery(clonedRow).find('input[type=text]').eq(0).val(); 
                var m2 = jQuery(clonedRow).find('input[type=text]').eq(1).val();
                var m3 = jQuery(clonedRow).find('input[type=text]').eq(2).val(); 
                var m4 = jQuery(clonedRow).find('input[type=text]').eq(3).val();
                var m5 = jQuery(clonedRow).find('input[type=text]').eq(4).val(); 
                var m6 = jQuery(clonedRow).find('input[type=text]').eq(5).val();


                mdata = m1 + 'x|x' + m2 + 'x|x' + m3 + 'x|x' + m4 + 'x|x' + m5 + 'x|x' + m6;
                adata1.push(mdata);
            } 

          var mparam = {
            fg_code:fg_code,
            ucomp_date:ucomp_date,
            adata1: adata1
          };  

          console.log(adata1);

      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-item-comp-upld-save-2',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { 
            $(this).prop('disabled', false);
            jQuery('#memsgtestent_bod').html(data);
            jQuery('#memsgtestent').modal('show');
            return false;
        },
        error: function() {
          alert('error loading page...');
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