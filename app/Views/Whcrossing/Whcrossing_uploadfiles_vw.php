<div class="table-responsive col-lg-12">
<table class=" table table-sm table-bordered table-condensed text-center">
	<thead>
		<tr>
			<th><i class="fa fa-cog"></i></th>
			<th>FILE</th>
			<th>MUSER</th>
			<th>ENCD</th>

		</tr>
	</thead>
	<tbody>
			<?php 
			$nn = 0;
			if($rlist !== ''): foreach($rlist as $row):
			$nn++;
			$__pls_doc_img_path = './uploads/promodamage_files/'.$row['file']; 
				;

			?>
		<tr>
			<td><a  onclick ="window.open('<?=site_url().$__pls_doc_img_path;?>')" class="btn btn-info btn-xs" title = "View"><i class="fa fa-eye"></i></a>
				<button class="btn_delImages btn btn-danger btn-xs" hidden title = "Delete" id="btn_delImages<?=$nn;?>" value="<?=$row['mtkn_img_pal'];?>"><i class="fa fa-trash"></i></button>
			</td>
			<td><?=$row['file'];?></td>
			<td><?=$row['muser'];?></td>
			<td><?=$row['encd'];?></td>
		</tr>
		<?php endforeach; else:?>
		<tr>
		<td colspan="4">No data was found.</td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>
<div>
