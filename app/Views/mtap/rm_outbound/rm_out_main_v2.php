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
$total_rmap = '';
$total_for_production = '';
$total_lacking = '';
$str="
    SELECT COUNT(d.`is_processed`) as total_rmap
    FROM
    trx_rmap_req_hd d
    JOIN
    (SELECT a.rmap_trxno, SUM(b.item_qty * a.item_qty) AS overall_sum
    FROM trx_rmap_req_dt a
    JOIN mst_item_comp2 b ON a.item_code = b.fg_code
    JOIN trx_rmap_req_hd d ON a.rmap_trxno = d.rmap_trxno
    GROUP BY a.rmap_trxno) AS rmtqty
    ON
    d.rmap_trxno = rmtqty.rmap_trxno
    WHERE d.`is_processed` = '0'
";
$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();
if($q->getNumRows() > 0) {
  $total_rmap = $rw['total_rmap'];
}else{
  $total_rmap = 0;
}

$str="
    SELECT 
    COUNT(`is_processed`) total
    FROM 
    trx_rmap_req_hd
    WHERE
    `is_processed` = '1'
";
$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();
$total_for_production = $rw['total'];

$str="
    SELECT 
    `rmap_trxno` total
    FROM 
    trx_rm_out_lacking
    GROUP BY 
    rmap_trxno

";
$q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $q->getRowArray();
if($q->getNumRows() > 0) {
  $total_lacking = $q->getNumRows();
}else{
  $total_lacking = 0;
}
?>
<style>
    
.thick-border {
  border: 2px solid black;

}
#total_rmap{
	background-color: transparent;
	border: none;
  	outline: none;
    font-size: 50px;
}
</style>
<main id="main">

    <div class="pagetitle">
    <h1>RM Outbound</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">RM Outbound</li>
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
                        <div class="col-lg-4 col-md-12">
                            <div class="row">
                                <div class="container mt-4">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title text-warning">UNPROCESSED RMAP REQUEST</h5>
                                            <p class="card-text">
                                                Total Count: 
                                                <span class="total-count display-3"><?php echo $total_rmap; ?></span>
                                            </p>
                                            </div>
                                            <button type="button" class="btn bg-dgreen btn-md" id="mbtn_process"><i class="bi bi bi-tools"></i> Process </button>
                                            <?=anchor('rm-outbound-2', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-md" ');?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="row">
                                <div class="container mt-4">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title text-danger">LACKING TRANSACTIONS</h5>
                                            <p class="card-text">
                                                Total Count: 
                                                <span class="total-count display-3"><?php echo $total_lacking; ?></span>
                                            </p>
                                            </div>
                                            <button type="button" class="btn bg-dgreen btn-md" id="mbtn_lacking"><i class="bi bi bi-eye"></i> View </button>
                                            <?=anchor('rm-outbound-2', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-md" ');?>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="row">
                                <div class="container mt-4">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title text-success">FOR PRODUCTION</h5>
                                            <p class="card-text">
                                                Total Count: 
                                                <span class="total-count display-3"><?php echo $total_for_production; ?></span>
                                            </p>
                                            </div>
                                            <button type="button" class="btn bg-dgreen btn-md" id="mbtn_produce"><i class="bi bi bi bi-graph-up-arrow"></i> Produce </button>
                                            <?=anchor('rm-outbound-2', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-md" ');?> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- endrow -->

                    <div class="row mb-3">
                      <div class="col-12">
                        <div id="mymodoutrecs">
                            <div class="text-center p-2 rounded-3  mt-2 border-dotted bg-light col-lg-12  p-4">
                            <h5><i class="bi bi-info-circle-fill text-dgreen"></i> Selected processing will display here.</h5> 
                            </div>
                        </div>
                      </div>
                  </div> 

                </div> 
            </div>
        </div>

    </div> 

</main>    
<?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>  
<script type="text/javascript">


$('#mbtn_process').click(function(){ 
      try {   

        __mysys_apps.mepreloader('mepreloaderme',true);
        $.ajax({ // default declaration of ajax parameters
          url: '<?=site_url()?>rm-out-vw-process',
          method:"POST",
          context:document.body,
          contentType: false,
          global: false,
          cache: false,
          processData:false,
          success: function(data)  { //display html using divID
            __mysys_apps.mepreloader('mepreloaderme',false);
            jQuery('#mymodoutrecs').html(data);
            return false;
          },
          error: function() { // display global error on the menu function
            alert('error loading page...');
            
            return false;
          } 
        }); 
      } catch (err) {
        var mtxt = 'There was an error on this page.\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\nClick OK to continue.';
        
        alert(mtxt);
      } //end try
    }); 

    $('#mbtn_produce').click(function(){ 
      try {   

        __mysys_apps.mepreloader('mepreloaderme',true);
        $.ajax({ // default declaration of ajax parameters
          url: '<?=site_url()?>rm-out-vw-produce',
          method:"POST",
          context:document.body,
          contentType: false,
          global: false,
          cache: false,
          processData:false,
          success: function(data)  { //display html using divID
            __mysys_apps.mepreloader('mepreloaderme',false);
            jQuery('#mymodoutrecs').html(data);
            return false;
          },
          error: function() { // display global error on the menu function
            alert('error loading page...');
            
            return false;
          } 
        }); 
      } catch (err) {
        var mtxt = 'There was an error on this page.\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\nClick OK to continue.';
        
        alert(mtxt);
      } //end try
    }); 

    $('#mbtn_lacking').click(function(){ 
      try {   

        __mysys_apps.mepreloader('mepreloaderme',true);
        $.ajax({ // default declaration of ajax parameters
          url: '<?=site_url()?>rm-out-vw-lacking',
          method:"POST",
          context:document.body,
          contentType: false,
          global: false,
          cache: false,
          processData:false,
          success: function(data)  { //display html using divID
            __mysys_apps.mepreloader('mepreloaderme',false);
            jQuery('#mymodoutrecs').html(data);
            return false;
          },
          error: function() { // display global error on the menu function
            alert('error loading page...');
            
            return false;
          } 
        }); 
      } catch (err) {
        var mtxt = 'There was an error on this page.\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\nClick OK to continue.';
        
        alert(mtxt);
      } //end try
    }); 

</script>