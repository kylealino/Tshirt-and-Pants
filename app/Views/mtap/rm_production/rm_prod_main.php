<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytrxfgpack = model('App\Models\MyFGPackingModel');
$mydataz = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(0);

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$branch_name = '';
$req_date = '';
$total_qty='';
$item_qty='';
$rmap_trxno = $request->getVar('rmap_trxno');
$nporecs = 0;
$txtactive_plnt_id = "";
$process_date = date('Y-m-d');
$request_date = '';
$fgreq_trxno='';

if(!empty($rmap_trxno)) {
  $str = "
    SELECT
    a.`request_date`,
    SUM(b.`item_qty`) item_qty,
    b.`fgreq_trxno`
    FROM
    trx_rmap_req_hd a
    JOIN
    trx_rmap_req_dt b
    ON
    a.`rmap_trxno` = b.`rmap_trxno`
    WHERE
    a.`rmap_trxno` = '$rmap_trxno'
    GROUP BY a.`rmap_trxno`
    ";

$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();
$request_date = $rw['request_date'];
$item_qty = $rw['item_qty'];
$fgreq_trxno = $rw['fgreq_trxno'];
}

?>
<style>
    
.thick-border {
  border: 2px solid black;
}
</style>
<main id="main">

    <div class="pagetitle">
    <h1>RM Production</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">RM Production</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-pencil-square"></i> Entry</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-3">
                                <label class="col-sm-3 form-label" for="fgreq_trxno">FG Transaction No.:</label>
                                <div class="col-sm-9">
                                    <input type="text" id="fgreq_trxno" name="fgreq_trxno" class="form-control form-control-sm" value="<?=$fgreq_trxno;?>" readonly/>
                                </div>
                            </div> <!-- end Acct No. -->
                            <div class="row gy-2 mb-3">
                                <label class="col-sm-3 form-label" for="txt_request_date">Request Date</label>
                                <div class="col-sm-9">
                                    <input type="date"  id="txt_request_date" name="txt_request_date" class="txt_request_date form-control form-control-sm " value="<?=$request_date;?>" readonly/>
                                </div>
                            </div>  
                            <div class="row gy-2 mb-3">
                                <label class="col-sm-3 form-label" for="txt_process_date">Process Date</label>
                                <div class="col-sm-9">
                                    <input type="date"  id="txt_process_date" name="txt_process_date" class="txt_process_date form-control form-control-sm " value="<?=$process_date;?>" readonly/>
                                </div>
                            </div>  
                        </div>
                        <div class="col-lg-6">  
                            <div class="row gy-2 mb-3">
                                <label class="col-sm-3 form-label" for="rmap_trxno">RMAP Transaction No.:</label>
                                <div class="col-sm-9">
                                    <input type="text" id="rmap_trxno" name="rmap_trxno" class="form-control form-control-sm" value="<?=$rmap_trxno;?>" readonly/>
                                </div>
                            </div> 
                            <div class="row gy-2 mb-3">
                                <label class="col-sm-3 form-label" for="req_qty">Request Qty.:</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="req_qty" name="req_qty" class="form-control form-control-sm" value="<?=$item_qty;?>" readonly/>
                                    </div>
                                </div> 
                            <div class="row gy-2 mb-3">
                                <label class="col-sm-3 form-label" for="release_qty">Release Qty.:</label>
                                <div class="col-sm-9">
                                    <input type="text"  id="release_qty" name="release_qty" class="release_qty form-control form-control-sm " onmouseover="javascript:__pack_totals();" onmouseout="javascript:__pack_totals();" onclick="javascript:__pack_totals();" readonly/>
                                </div>
                            </div>  
                        </div>
                    </div> <!-- endrow -->

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class=" table-responsive">
                                <table class="table table-bordered table-hover table-sm text-center" id="tbl-rm-recs">
                                    <thead class="thead-light">
                                        <tr>
                                            <th nowrap="nowrap" style="color:red;">Itemcode</th>
                                            <th nowrap="nowrap">Item Description</th>
                                            <th nowrap="nowrap">Request Qty</th>
                                            <th nowrap="nowrap">Release Qty</th>
                                            <th nowrap="nowrap">Inventory Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gwpo-recs">
                                        <?php
                                        if (!empty($rmap_trxno)):
                                        $nn=1;

                                        $str = "
                                        SELECT
                                          a.`item_code`,
                                          a.`item_qty`,
                                          b.`ART_DESC`,
                                          a.`produce_rmng`,
                                          COALESCE(c.`po_qty`, 0) AS minv
                                        FROM 
                                        trx_rmap_req_dt a
                                        JOIN
                                        mst_article b
                                        ON
                                        a.`item_code` = b.`ART_CODE`
                                        LEFT JOIN
                                        rm_inv_rcv c
                                        ON
                                        a.`item_code` = c.`mat_code`
                                        WHERE 
                                        a.`rmap_trxno` = '$rmap_trxno' and a.`produce_rmng` != '0'

                                        ";

                                        $q =  $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                                        $rrec = $q->getResultArray();
                                        foreach($rrec as $rdt){

                                        ?>
                                        <tr>
                                            <td nowrap="nowrap"><input type="text" id="fabric_code" class="form-control text-center form-control-sm mitemcode bg-white" size="10" value="<?=$rdt['item_code'];?>" disabled></td>
                                            <td nowrap="nowrap"><input type="text" id="fabric_desc" class="form-control text-center form-control-sm bg-white" size="30" value="<?=$rdt['ART_DESC'];?>" disabled></td>
                                            <td nowrap="nowrap"><input type="text" id="fabric_qty" class="form-control text-center form-control-sm bg-white" size="10" value="<?=$rdt['item_qty'];?>" disabled></td>
                                            <td nowrap="nowrap"><input type="text" id="fabric_qty" class="form-control text-center form-control-sm bg-white thick-border"size="10" value="<?=$rdt['produce_rmng'];?>"></td>
                                            <td nowrap="nowrap"><input type="text" id="fabric_qty" class="form-control text-center form-control-sm bg-white "size="10" value="<?=$rdt['minv'];?>"></td>
                                        </tr>
                                        <?php 
                                        } 
                                        endif;
                                        ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <?php if(!empty($rmap_trxno)): ?>
                            <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
                            <?=anchor('rm-production', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-sm" ');?>
                            <?php endif?>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div> 

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Records</h3>
                </div>
                <div class="card-body">
                    <div class="pt-2 bg-dgreen mt-2"> 
                        <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                            <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item  p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> List</a>
                            <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> Items</a>
                        </nav>
                    </div>

                    <div id="prodlist" class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">
                        <?php
                        ?> 
                    </div> 
                </div> 
            </div>
        </div>
    </div> 

</main>    
<?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>  
<script type="text/javascript">

$(document).ready(function() {
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    var mtkn_whse = '';
    rm_prod_view_recs(mtkn_whse);

    __pack_totals();
    });

$('#anchor-list').on('click',function(){
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    var mtkn_whse = '';
    rm_prod_view_recs(mtkn_whse);

});

function rm_prod_view_recs(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>rm-prod-recs",
        type: "post",
        data: {
            mtkn_whse: mtkn_whse
        }
    });

    // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#prodlist').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
      });
  };

  function __pack_totals() { 

    try { 
        var rowCount1 = jQuery('#tbl-rm-recs tr').length;
    var adata1 = [];
    var adata2 = [];
    var mdata = '';
    var ninc = 0;
    var total = 0;
    for(aa = 1; aa < rowCount1; aa++) { 
        var clonedRow = jQuery('#tbl-rm-recs tr:eq(' + aa + ')').clone(); 
        var qty = jQuery(clonedRow).find('input[type=text]').eq(3).val();
        var QTY_TOTAL = parseFloat(qty);

        total = total + QTY_TOTAL;
    
    } 

    $('#release_qty').val(total);
    } catch(err) {
    var mtxt = 'There was an error on this page.\n';
    mtxt += 'Error description: ' + err.message;
    mtxt += '\nClick OK to continue.';
    alert(mtxt);
    $.hideLoading();
    return false;
    }  //end try            
}

$("#mbtn_mn_Save").click(function(e){
    try { 

          var fgreq_trxno = jQuery('#fgreq_trxno').val();
          var rmap_trxno = jQuery('#rmap_trxno').val();
          var rowCount1 = jQuery('#tbl-rm-recs tr').length;
          var adata1 = [];
          var mdata = '';

          for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl-rm-recs tr:eq(' + aa + ')').clone(); 
                var mitemc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); 
                var mreqqty = jQuery(clonedRow).find('input[type=text]').eq(2).val();
                var mrelease = jQuery(clonedRow).find('input[type=text]').eq(3).val(); 
                var minv = jQuery(clonedRow).find('input[type=text]').eq(4).val();

                mdata = mitemc + 'x|x' + mrelease + 'x|x' + mreqqty + 'x|x' + minv;
                adata1.push(mdata);
            } 

          var mparam = {

            rmap_trxno:rmap_trxno,
            fgreq_trxno:fgreq_trxno,
            adata1: adata1

          };  

          console.log(adata1);

      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>rm-prod-save',
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