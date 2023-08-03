<?php
$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');
$cuser   = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
?>

  <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-body">
            <div class="row pt-3">
              <div class="col-md-12">
        
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm text-center" id="tbl-transfer-recs">
                      <thead class="thead-light">
                        <tr>
                          <th nowrap="nowrap">TRANSFER CODE</th>
                          <th nowrap="nowrap">PLANT CODE</th>
                          <th nowrap="nowrap">WSHE CODE</th>
                          <th nowrap="nowrap">TRANSFER FROM (RACK - BIN)</th>
                          <th nowrap="nowrap">TRASFER TO (RACK - BIN)</th>
                          <th nowrap="nowrap">USER</th>
                          <th nowrap="nowrap">ENCD</th>
                          <th nowrap="nowrap"><i class="bi bi-gear"></i></th>
                          
                        </tr>
                      </thead>
                      <tbody id="tbody-transfer-recs">
                          <?php 
                            if($rlist != ""):
                              $nn = 1;
                              foreach($rlist as $row):
                              $bgcolor = ($nn % 2) ? "#EAF3F3" : "#FFF";
                              $on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";  
                                  $mtkn_trans_rid = hash('sha384', $row['recid'] . $mpw_tkn);  
                          ?>
                          <tr bgcolor="<?= $bgcolor ?>" <?=$on_mouse?> >
                            <td nowrap="nowrap"><?=$row['header']?></td>
                            <td nowrap="nowrap"><?=$row['plnt_code']?></td>
                            <td nowrap="nowrap"><?=$row['wshe_code']?></td>
                            <td nowrap="nowrap"><?=$row['transfer_from']?></td>
                            <td nowrap="nowrap"><?=$row['transfer_to']?></td>
                            <td nowrap="nowrap"><?=$row['muser']?></td>
                            <td nowrap="nowrap"><?=$row['encd']?></td>
                            <td>
                              <button onclick="window.open('<?= site_url() ?>warehouse-inv-transfer-print?transfer_code=<?=$row['header']?>')" class="btn btn-sm btn-dgreen"><i class="bi bi-printer"> View/Print</i></button>
                            </td>
                          </tr>
                          <?php
                                $nn++;
                              endforeach;
                            else:
                          ?>
                          <tr>
                            <td nowrap="nowrap" colspan="7">No data was found.</td>
                          </tr>
                          <?php 
                            endif;
                          ?>
                      </tbody>
                    </table>
                  </div>
                  <hr>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> 
    <!-- INSERT TABLE HERE -->


<script type="text/javascript">
  $(document).ready(function(){

   $.extend(true, $.fn.dataTable.defaults,{
        language: {
            search: ""
        }
    });

    var tbl_pl_items = $('#tbl-transfer-recs').DataTable({               
         'order':[],
         'columnDefs': [{
             "targets":[1,2,5,7],
             "orderable": false
         },
         {
          targets:'_all' ,
          className: 'dt-head-center'
       }
         ]
     });

     $('#tbl-transfer-recs_filter.dataTables_filter [type=search]').each(function (){
          $(this).attr(`placeholder`, `Search...`);
          $(this).before('<span class="bi bi-search text-dgreen"></span>');
      });

  });

</script>
