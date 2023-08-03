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

</style>
	<div class="table-responsive">
		<div class="col-md-12 col-md-12 col-md-12">
			<table class="table table-condensed table-hover table-bordered table-sm " id="tbl_sub_items">
				<thead>
					<tr>
            <th class="text-center">
            </th>
            <th>Main Itemcode</th>
						<th>Sub Itemcode</th>
						<th>Item Barcode</th>
						<th>Convf</th>
            <th>Uom</th>
            <th>SRP</th>
					</tr>
				</thead>
				<tbody>
            <?php 
                if($rlist != ""):
                  $nn = 1;
                foreach($rlist as $row): 
                  $sub_itemc = $row['sub_itemc'];
              ?>
            <tr>
                <td class="text-center" nowrap>
								  <?=anchor('sub-item-masterdata/?sub_itemc=' . $sub_itemc, '<i class="bi bi bi-eye"></i> View ',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm"');?>
							  </td>
                <td nowrap><?=$row['main_itemc']?></td>
                <td nowrap><?=$row['sub_itemc']?></td>
                <td nowrap><?=$row['barcode']?></td>
                <td nowrap><?=$row['convf']?></td>
                <td nowrap><?=$row['uom']?></td>
                <td nowrap><?=$row['srp']?></td>
            </tr>
            <?php
		            $nn++;
								endforeach;
                endif;
              ?>
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
	$('#tbl_sub_items').DataTable({
		           
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

   $('#tbl_sub_items_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });

});
</script>
