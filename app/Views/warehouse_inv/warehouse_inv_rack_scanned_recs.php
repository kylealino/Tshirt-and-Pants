<?php
     $request = \Config\Services::request();
     $mylibzdb = model('App\Models\MyLibzDBModel');
     $cuser   = $mylibzdb->mysys_user();
     $mpw_tkn = $mylibzdb->mpw_tkn();

?>
     <div class="table-responsive"> 
     <input type="hidden" class="form-control form-control-sm" id="mtkn_tbl_temp" value="<?=$tbltemp;?>">
     <table class="table table-bordered table-hover table-sm text-center"id="tbl-item-transfer">
          <thead>
               <tr class="text-dgreen">
                    <th nowrap="nowrap">BOX BARCODE</th>
                    <th nowrap="nowrap">REMARKS</th>
                    <th nowrap="nowrap">QTY</th>
                    <th nowrap="nowrap">STOCK CODE</th>
                    <th nowrap="nowrap">ITEM CODE</th>
                    <th nowrap="nowrap">ITEM DESCRIPTION</th>
                    <th nowrap="nowrap">PACKAGING</th>
                    <th nowrap="nowrap">CONV FACTOR</th>
                    <th nowrap="nowrap">TOTAL PCS</th>
                    <!-- <th nowrap="nowrap"><i class="fa fa-close"></i></th> -->
               </tr>
          </thead>
          <tbody id="tbody-item-transfer">
              <?php 
                    if($result != ""):

                         foreach($result as $row):
                              $mtkn_rid = hash('sha384', $row['wshe_rcv_rid'] . $mpw_tkn);  
               ?>   
               <tr>
                    <td nowrap="nowrap" data-id="<?=$mtkn_rid?>"><?=$row['barcde']?></td>
                    <td nowrap="nowrap"><?=$row['remarks']?></td>
                    <td nowrap="nowrap"><?=$row['qty']?></td>
                    <td nowrap="nowrap"><?=$row['stock_code']?></td>
                    <td nowrap="nowrap"><?=$row['ART_CODE']?></td>
                    <td nowrap="nowrap"><?=$row['ART_DESC']?></td>
                    <td nowrap="nowrap"><?=$row['ART_SKU']?></td>
                    <td nowrap="nowrap"><?=$row['convf']?></td>
                    <td nowrap="nowrap"><?=$row['total_pcs']?></td>
                    <!-- <td nowrap="nowrap"><button class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button></td> -->
               </tr>
               <?php 
                         endforeach;
                    endif;
               ?>
          </tbody>
     </table>
     </div>
     <hr>
     <?php 
          if($result != ""):
     ?>
     <div class="form-row">
          <button type="submit" class="btn btn-dgreen btn-sm" id="btn-save-transfer">Save</button>  
     </div>
     <?php 
          endif;
     ?>

<script type="text/javascript">
     
  $(document).ready(function(){

   $.extend(true, $.fn.dataTable.defaults,{
        language: {
            search: ""
        }
    });

    var tbl_transfer_item = $('#tbl-item-transfer').DataTable({               
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

     $('#tbl-item-transfer_filter.dataTables_filter [type=search]').each(function (){
          $(this).attr(`placeholder`, `Search...`);
          $(this).before('<span class="bi bi-search text-dgreen"></span>');
      });

     $('#btn-save-transfer').on('click',function(){
          try { 
               $('#btn-save-transfer').prop("disabled",true);
               // $.showLoading({name: 'line-pulse', allowHide: false });
               var myTable = $("#tbody-item-transfer");
               var mdata = '';
               var item_array = [];
               var mtkn_tbl_temp = $('#mtkn_tbl_temp').val();
               var trCount = '<?=$count?>';

               if(trCount <= 0 ){
                 jQuery('#memsgtestent_danger_bod').html('No items to be receive.');
                 jQuery('#memsgtestent_danger').modal('show');
               }

               var trs =  tbl_transfer_item.$('tr', {"page": "all"});

               trs.each(function(index,elem){
                    var $tds = $(this).find('td'),
                         mtkn_rid = $tds.eq(0).attr("data-id"),
                         barcde = $tds.eq(0).text(),
                         remarks = $tds.eq(1).text()

                     if(mtkn_rid != ''){
                        item_array.push(`'${barcde}'`);
                    }
            
               });
               var mwshe_id       = $('#txt-warehouse').attr("data-id");
               var to_rack_name   = $("#txt-ttransfer-rack-upload").val();
               var to_bin_name    = $("#txt-ttransfer-bin-upload").val();
               var from_rack_name = $("#txt-ftransfer-rack-upload").val();
               var from_bin_name  = $("#txt-ftransfer-bin-upload").val();

               if(to_rack_name == from_rack_name){
                 jQuery('#memsgtestent_danger_bod').html('Invalid rack bin');
                 jQuery('#memsgtestent_danger').modal('show');
               }
                if(to_bin_name == from_bin_name){
                 jQuery('#memsgtestent_danger_bod').html('Invalid rack bin');
                 jQuery('#memsgtestent_danger').modal('show');

               }

               var data_arr = item_array.join(',');

               var mparam = {
                     txtWarehousetkn : mwshe_id,
                     to_rack : $("#txt-ttransfer-rack-upload").attr("data-id"),
                     to_bin : $("#txt-ttransfer-bin-upload").attr("data-id"),
                     from_rack : $("#txt-ftransfer-rack-upload").attr("data-id"),
                     from_bin : $("#txt-ftransfer-bin-upload").attr("data-id"),
                     to_rack_name : to_rack_name,
                     to_bin_name : to_bin_name,
                     from_rack_name : from_rack_name,
                     from_bin_name : from_bin_name,
                     tbltemp:mtkn_tbl_temp,
                     trCount:trCount,
                     data_arr : data_arr
               }

               $.ajax({ 
                    type: "POST",
                    url: '<?=site_url();?>/warehouse-inv-rackbintrans-upload-sv',
                    context: document.body,
                    data: eval(mparam),
                    global: false,
                    cache: false,
                    success: function(data)  { 
                         // $.hideLoading();
                         $('#btn-save-transfer').prop("disabled",false);
                         jQuery('#memsgtestent_success_bod').html(data);
                         jQuery('#memsgtestent_success').modal('show');
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

     });

  });

</script>
