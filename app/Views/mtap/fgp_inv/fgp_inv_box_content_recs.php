
<?php 
/**
 *	File        : maintenance/mycomp_BU-recs.php
 *  Auhtor      : Arnel L. Oquien
 *  Date Created: Sept 05, 2018
 * 	last update : Sept 05, 2018
 * 	description : Business unit records
 */
 
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$cuserrema = $mylibzdb->mysys_userrema();


?>
<div class="container-fluid"> 
	<div class="row">
			<div class="table-responsive mt-2 text-center">
				<table class="table table-condensed table-hover table-bordered table-sm" id="table-box-content">
						<thead>
							<tr> <!---BUTTON FOR ADDING Product Line---->
							
								<th nowrap="nowrap">FG Request Transaction No.</th>
                                <th nowrap="nowrap">Stock Code</th>
                                 <th nowrap="nowrap">Item Code</th>
                                <th nowrap="nowrap">Description</th>
                                <th nowrap="nowrap">Price</th>
								<th nowrap="nowrap">Cost</th>
								<th nowrap="nowrap">Per</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1; //use for changin the bgcolor of table
								foreach($rlist as $row): 
									
									$bgcolor = ($nn % 2) ? "#EAF3F3" : "#FFF";
									$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
									//giving data for $txt_mtknr
									
								?>
							
								<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
						
									<td nowrap="nowrap"><?=$row['fgreq_trxno'];?></td>
									<td nowrap="nowrap"><?=$row['stock_code'];?></td>
									<td nowrap="nowrap"><?=$row['mat_code'];?></td>
									<td nowrap="nowrap"><?=$row['ART_DESC'];?></td>
									<td nowrap="nowrap"><?=$row['ART_UPRICE'];?></td>
									<td nowrap="nowrap"><?=$row['ART_UCOST'];?></td>
									<td nowrap="nowrap"><?=$row['ART_SKU'];?></td>
								</tr>
								<?php 
								$nn++;
								endforeach;
							else:
								?>
								<tr>
									<td colspan="9">No data was found.</td>
								</tr>
							<?php 
							endif; ?>
						</tbody>
					</table>
			</div>

</div>
</div>
<script type="text/javascript"> 

 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });
$('#table-box-content').DataTable();

   $('#table-box-content_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });

</script>
