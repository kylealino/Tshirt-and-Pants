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
$mytrxfgpack = model('App\Models\MyFGPackingModel');
$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$nporecs = 0;
?>
<style>
table.memetable, th.memetable, td.memetable {
  border: 1px solid #F6F5F4;
  border-collapse: collapse;
}
thead.memetable, th.memetable, td.memetable {
  padding: 6px;
}
#rmap_trxno{
	background-color: transparent;
	border: none;
  	outline: none;
}
</style>
	<div class="table-responsive">
		<div class="col-md-12 col-md-12 col-md-12">
			<table class="table table-condensed table-hover table-bordered table-sm " id="tbl_rmap_request">
				<thead>
					<tr>
						<th class="text-center">
						</th>
						<th>RMAP Transaction No.</th>
						<th>Plant</th>
						<th>Request Date</th>
						<th>Request Qty</th>
            <th><i class="bi bi-gear"></i></th>

					</tr>
				</thead>
				<tbody>
					<?php 
					if($rlist !== ''):
						$nn = 1;
						foreach($rlist as $row): 
							$nporecs++;
							$txt_mtknr = hash('sha384', $row['rmap_trxno'] . $mpw_tkn);
							$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
							$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
							$rmap_trxno = $row['rmap_trxno'];
              $is_processed = $row['is_processed'];
							
						?>
						<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
							<td nowrap><?=$nporecs;?></td>
							<td nowrap><input type="hidden" name="rmap_trxno" id="rmap_trxno" class="text-center" value="<?=$row['rmap_trxno'];?>"><?=$row['rmap_trxno'];?></td>
							<td nowrap><?=$row['plnt_id'];?></td>
							<td nowrap><?=$row['request_date'];?></td>
							<td nowrap><input type="hidden" name="rmap_trxno" id="rmap_trxno" size="5" class="text-center" value="<?=$row['total_qty'];;?>"><?=$row['total_qty'];?></td>
              <td nowrap>
              <?php if($is_processed == '0'):?>
                <button class="btn btn-success btn-xs btn_process"  data-rmapno= "<?=$row['rmap_trxno'];?>" value="<?=$rmap_trxno?>"  type="button">Process</button>
              <?php else:?>
                -
              <?php endif;?>  
              </td>
						</tr>
						<?php 
						$nn++;
						endforeach;
						?>
					<?php 
					endif; ?>
				</tbody>
				
			</table>
		</div>
	</div> <!-- end table-reponsive -->

	
<script type="text/javascript"> 

$(document).ready(function(){
  $.extend(true, $.fn.dataTable.defaults, {
        language: {
            search: ""
        }
    });

    $('#tbl_rmap_request').DataTable({
        'order': [0],
        'columnDefs': [{
            "targets": [1], // Enable searching for the first column (index 0)
            "orderable": false
        },
        {
            targets: '_all',
            className: 'dt-head-center'
        }]
    });



    $('#tbl_rmap_request_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });
    var firstCellValue = $('#tbl_rmap_request').DataTable().cell(1, 1).data();
    console.log("Value of cell in the first column, row 2:", firstCellValue);
});

$(".btn_process").click(function(e){
    try { 

          var rmapno = jQuery(this).attr('data-rmapno'); 
          var rowCount1 = jQuery('#tbl_rmap_request tr').length;
          var adata1 = [];
          var mdata = '';

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl_rmap_request tr:eq(' + aa + ')').clone(); 
                var mitemc = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
                var mqty = jQuery(clonedRow).find('input[type=hidden]').eq(1).val();
  
                mdata = mitemc + 'x|x' + mqty;
                adata1.push(mdata);


			}  //end for

          var mparam = {
            rmapno:rmapno,
            adata1: adata1
          };  


      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>rm-out-req-save',
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
    $(this).prop("disabled", true);
    return false; 
  });

  __mysys_apps.mepreloader('mepreloaderme',false);

</script>
