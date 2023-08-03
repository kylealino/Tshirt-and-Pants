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
$test = "test";
?>	
<hr class="">
<div class="row">
    <div class="col-lg-6">
		<div class="row mb-3">
			<label class="col-sm-3 form-label" for="stan_cap_trxno">Transaction No.:</label>
			<div class="col-sm-9">
				<input type="text" id="stan_cap_trxno" name="stan_cap_trxno" class="form-control form-control-sm"readonly/>
			</div>
		</div> 
		<div class="row gy-2 mb-3">
			<label class="col-sm-3 form-label" for="branch_name">Branch:</label>
			<div class="col-sm-9">
				<input type="text"  placeholder="Branch Name" id="branch_name" name="branch_name" class="branch_name form-control form-control-sm " required/>
				<input type="hidden"  placeholder="Branch Name" id="branch_code" name="branch_code" class="branch_code form-control form-control-sm " required/>  
			</div>
		</div> 
	</div>
    <div class="col-6">
        <div class="row gy-2 mb-3">
			<label class="col-sm-3 form-label" for="">Category</label>
			<div class="col-sm-9">
				<select id="opt_cat" class="form-control form-control-sm">
					<option value="TSHIRT">Tshirt</option>
					<option value="PANTS">Pants</option>
				</select>
			</div>
		</div> 
		<div class="row gy-2 mb-3">
			<label class="col-sm-3 form-label" for="active_plnt_id">Transaction Type</label>
			<div class="col-sm-9">
				<select id="opt_type" class="form-control form-control-sm">
					<option value="NEW">New</option>
					<option value="UPDATE">Update</option>
				</select>
			</div>
		</div> 
    </div>
</div>

<div class="row"> 
    <div class="col-6">
    <?= form_open('',' id="frmTblItems" ') ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm text-center" id="tbl-items-received">
            <thead class="thead-dark">
                <tr>
                    <th nowrap="nowrap"></th>
                    <th nowrap="nowrap">ITEMCODE</th>
                    <th nowrap="nowrap">STANDARD CAPACITY</th>
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
                        <td nowrap="nowrap"><input type="text" id="ART_CODE<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white text-center" value="<?=$row['ART_CODE'];?>" disabled></td>
                        <td nowrap="nowrap"><input type="text" id="ART_DESC<?=$nporecs;?>" class="form-control form-control-sm mitemcode bg-white text-center" value="<?=$row['CAP_QTY'];?>"></td>

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
            <h6 for=""> <i class="bi bi-info-circle text-dgreen" title="Different packing list in not allowed!"> </i>Kindly check for mistakes before saving, in case of mistake; please reupload the file.</h6>
            <span id="pl-selected" class="text-dgreen fw-bolder fst-italic">  </span>
        </div>
        <div class="form-row">
            <button type="button" class="btn bg-dgreen btn-sm" id="mbtn_mn_Save">Save</button>  
            <?=anchor('me-standard-cap', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-sm" ');?>
        </div>
        <?php 
            endif;
        ?>
    <?= form_close(); ?>
    </div>
</div>

<?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>  
<script>
	$(document).ready(function(){
 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });
	$('#tbl-items-received').DataTable({
		           
		searching:false,
       'order':[],
       "lengthMenu": [[100], [100]],
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
			source: '<?= site_url(); ?>search-standard-cap-branch/',  //mysearchdata/companybranch_v
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
	});

    $("#mbtn_mn_Save").click(function(e){
    try { 

          var stan_cap_trxno = jQuery('#stan_cap_trxno').val();
          var branch_name = jQuery('#branch_name').val();
          var opt_cat = jQuery('#opt_cat').val();
          var opt_type = jQuery('#opt_type').val();

          var rowCount1 = jQuery('#tbl-items-received tr').length;
          var adata1 = [];
          var mdata = '';
          var ninc = 0;

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-items-received tr:eq(' + aa + ')').clone(); 
                var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val();
                var mstdrd = jQuery(clonedRow).find('input[type=text]').eq(1).val();

                mdata = mitemc + 'x|x' + mstdrd;
                adata1.push(mdata);

			}  

          var mparam = {
            stan_cap_trxno:stan_cap_trxno,
            branch_name: branch_name,
            opt_cat:opt_cat,
            opt_type:opt_type,
            adata1: adata1
          };  


      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>me-standard-cap-save',
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
</script>