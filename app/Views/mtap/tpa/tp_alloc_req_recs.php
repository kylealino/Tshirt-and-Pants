<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$cuser   = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
?>
<div class="box box-primary">
	<div class="box-body">
		<div class="row pt-3">
			<div class="col-md-12">
				<div class="table-responsive">
		          	<table class="table table-bordered table-hover table-sm text-center" id="tbl-transfer-verify-items-recs">
		            	<thead class="thead-light">
				          	<tr>
									<th>Transaction No.</th>
									<th>Branch</th>
									<th>Entry Date</th>
									<th>QTS</th>
									<th>Amount</th>
                                    <th>Action</th>
				          	</tr>
		            	</thead>
						<tbody>
							<?php 
							if($rlist !== ''):
								$nn = 1;
								foreach($rlist as $row): 
									$prod_plan_trxno = $row['prod_plan_trxno'];
                                    $is_processed = $row['is_processed'];
								?>
								<tr>
									<td nowrap><?=$row['prod_plan_trxno'];?></td>
									<td nowrap><?=$row['brnch_name'];?></td>
									<td nowrap><?=$row['entry_date'];?></td>
									<td nowrap><?=$row['qty_serve'];?></td>
                                    <td nowrap><?=$row['amount_serve'];?></td>
                                    <?php if($is_processed == 0):?>
                                    <td nowrap>
                                        <?=anchor('me-tp-alloc-vw/?prod_plan_trxno=' . $prod_plan_trxno, 'PROCESS',' class="btn btn-dgreen p-1 pb-0 mebtnpt1 btn-sm"');?>
                                    </td>
                                    <?php else:?>
                                    <td nowrap>
                                        -
                                    </td>
                                    <?php endif;?>
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
	        	<hr>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
 $.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });
	$('#tbl-transfer-verify-items-recs').DataTable({
		           
       'order':[2,'DESC'],
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

   $('#tbl-transfer-verify-items-recs_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });

});
</script>

