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
			<table class="table table-condensed table-hover table-bordered table-sm " id="tbl_rmap_lacking">
				<thead>
					<tr>
						<th class="text-center">
						</th>
						<th>RMAP Transaction No.</th>
						<th>FG Code</th>
						<th>RM Code</th>
                        <th>Inventory Remaining</th>
						<th>Lacking Qty</th>

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

							
						?>
						<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
							<td nowrap><?=$nporecs;?></td>
							<td nowrap><input type="text" name="rmap_trxno" id="rmap_trxno" class="text-center" value="<?=$row['rmap_trxno'];?>"></td>
							<td nowrap><?=$row['fg_code'];?></td>
							<td nowrap><?=$row['rm_code'];?></td>
                            <td nowrap><?=$row['rm_inv'];?></td>
							<td nowrap><?=$row['total_qty'];?></td>
                            
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
	$('#tbl_rmap_lacking').DataTable({
		           
       'order':[3,'DESC'],
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

   $('#tbl_rmap_lacking_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });

});

$(".btn_process").click(function(e){
    try { 

          var rmapno = jQuery(this).attr('data-rmapno'); 
          var rowCount1 = jQuery('#tbl_rmap_lacking tr').length;
          var adata1 = [];
          var mdata = '';

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl_rmap_lacking tr:eq(' + aa + ')').clone(); 
                var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val();
                var mqty = jQuery(clonedRow).find('input[type=text]').eq(1).val();
  
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
