<?php 
/**
 *  File        : promodamage_main.php
 *  Author      : Arnel Oquien
 *  Date Created: July. 27, 2022
 */


$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$this->db_erp = $mydbname->medb(1);
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$mydatazua =  model('App\Models\MyDatauaModel');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$cuserrema = $mylibzdb->mysys_userrema();
$fgreq_trxno = $request->getVar('fgreq_trxno');
$scanned = '1';

?>


<main id="main">
  <div class="pagetitle">
      <h1>FGP Receiving</h1>
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html">Home</a></li>
              <li class="breadcrumb-item active">FGP Receiving</li>
          </ol>
          </nav>
  </div><!-- End Page Title -->
        <div class="content-inner w-100">
          <!-- Forms Section-->
          <section class="forms"> 
          <div class="container-fluid">
              <div class="row" >
      
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-pencil-square"></i> Entry</h3>
                    </div>
                    <div class="card-body">
                    <div class="py-2 mt-3">
                    <?php if(!empty($fgreq_trxno)):?>
                      <h5 class="text-start text-dgreen fw-bold"> <span > FG REQUEST CODE : </span> <?=$fgreq_trxno;?> </h5>
                      <input type="hidden" name="pono" id="pono" value="<?=$fgreq_trxno?>">
                    </div>
                    <hr class="prettyline shadow">
                    <div class="table-responsive"> 
                        <table class="table table-bordered table-hover table-sm text-center" id="tbl-items-received">
                          <thead class="thead-dark text-dgreen">
                            <tr>
                              <th nowrap="nowrap"><input type="checkbox" id="rcv-chck-all" class="green-cb fs-2  p-2 " style="scale: 1.3;"></th>
                              <th nowrap="nowrap">FG Request Trxno.</th>
                              <th nowrap="nowrap">TPA Trxno.</th>
                              <th nowrap="nowrap">Assorted Series</th>
                              <th nowrap="nowrap">Barcode</th>
                              <th nowrap="nowrap" style="color:red;">QTY SCANNED</th>
                            </tr>
                          </thead>
                          <?php endif;?>
                          <tbody id="tblItems">
                            <?php if(!empty($fgreq_trxno)):

                                $str_itm="
                                SELECT 
                                a.`recid`,
                                a.`tpa_trxno`, 
                                a.`fgreq_trxno`, 
                                a.`stock_code`, 
                                a.`witb_barcde`, 
                                b.`req_pack`
                                FROM
                                fg_prod_barcdng_dt a
                                LEFT JOIN
                                trx_fgpack_req_dt b
                                ON
                                a.`fgreq_trxno` = b.`fgreq_trxno`
                                WHERE 
                                a.`fgreq_trxno` = '{$fgreq_trxno}' and a.`rcv_tag` = '0'
                                GROUP BY a.`witb_barcde`
                                ";
                                $q = $mylibzdb->myoa_sql_exec($str_itm,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                                $rw = $q->getResultArray();
                                foreach ($rw as $data) {
                                  $recid = $data['recid'];
                                  $tpa_trxno = $data['tpa_trxno'];
                                  $fgreq_trxno = $data['fgreq_trxno'];
                                  $stock_code = $data['stock_code'];
                                  $witb_barcde = $data['witb_barcde'];
                                  $req_pack = $data['req_pack'];
                              ?>
  
                              <tr>
                                <td nowrap="nowrap" id="fgreq_trxno"><input class="cb_chk green-cb fs-2" type="checkbox" style="scale: 1.3"  value="<?=$witb_barcde?>"></td>
                                <td nowrap="nowrap" id="fgreq_trxno"><?=$fgreq_trxno;?></td>
                                <td nowrap="nowrap" id="tpa_trxno"><?=$tpa_trxno;?></td>
                                <td nowrap="nowrap" id="stock_code"><?=$stock_code;?></td>
                                <td nowrap="nowrap" id="witb_barcde"><?=$witb_barcde;?></td>
                                <td nowrap="nowrap" id="req_pack"><?=$scanned;?></td>
                              </tr>
                              <?php 
                                
                              }
                          
                          ?>
                        </tbody>
                      </table>
                      <div class="form-row py-3">
                          <button type="button" class="btn bg-dgreen btn-sm" id="btn-central-rcv">Receive</button> 
                          <?=anchor('me-fgp-rcvng-vw', '<i class="bi bi-arrow-repeat"></i>',' class="btn btn-dgreen-ol btn-sm" ');?> 
                      </div>
                      <?php else:?>  

                      <div id="mymodoutrecs">
                     <div class="text-center p-2 rounded-3  mt-4 border-dotted bg-light col-lg-6 offset-lg-3 p-4">
                        <h5><i class="bi bi-info-circle-fill text-dgreen"></i> Processing of FG Packed will display in here.</h5> 
                     </div>
                     
                    </div>
                    <?php 
                  endif;
                  ?>

                    </div>
                    </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                
                </div>


                <!-- Inline Form-->
                <div class="col-lg-12">                           
                  <div class="card ">
                    <div class="card-header">
                      <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> Records</h3>
                    </div>
                    <div class="card-body ">
                    <div class="pt-2  bg-dgreen mt-2"> 
                     <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                      <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top" aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> List</a>
                      <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> Items</a>
                     </nav>
                     </div>
                        
                      <div id="mymodoutentrecs" >
                      <div class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">

                      </div>
                      </div>
                    <!-- car body end -->
                  </div>
                </div>
              
              </div>

                </div>
              </div>
          </section>
          <?php
            echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
            echo $mylibzsys->memypreloader01('mepreloaderme');
            echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
            ?>  
          </main>


<script type="text/javascript">   

$.extend(true, $.fn.dataTable.defaults,{
      language: {
          search: ""
      }
  });

   var tbl_items_scanned = $('#tbl-items-received').DataTable({  
            
        'order':[],
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



   $('#tbl-items-received_filter.dataTables_filter [type=search]').each(function () {
        $(this).attr(`placeholder`, `Search...`);
        $(this).before('<span class="bi bi-search text-dgreen"></span>');
    });


   //check all function
	 $("#rcv-chck-all").click(function () {
	 		var rowcollection = tbl_items_scanned.$(".cb_chk", {"page": "all"});				
					rowcollection.each(function(index,elem){
							$(elem).prop('checked',  $("#rcv-chck-all").prop('checked'));

					});
  	});

__mysys_apps.mepreloader('mepreloaderme',false);

    
//myrcvngcd_view_recs();
function myrcvngcd_view_recs(){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
        url: "<?=site_url();?>fgp-rcvng-recs",
        type: "post",
        data: {

        }
    });

      // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
      ajaxRequest.done(function(response, textStatus, jqXHR) {
          jQuery('#mymodoutentrecs').html(response);
          __mysys_apps.mepreloader('mepreloaderme',false);
          //$.hideLoading();
          // and do it again
          //setTimeout(get_if_stats, 5000);
      });
  };

$('#anchor-list').on('click',function(){
    $('#anchor-list').addClass('active');
    $('#anchor-items').removeClass('active');
    myrcvngcd_view_recs();

});

$("#btn-central-rcv").click(function(e){
  try { 
		      	//	$('#btn-central-rcv').prop("disabled",true);
					__mysys_apps.mepreloader('mepreloaderme',true);
          var pono = jQuery('#pono').val(); 
		          	
		          	var mdata = '';
		          	var item_array = [];
		          	var brcde_list = '';

		          	var rowcollection = tbl_items_scanned.$(".cb_chk:checked", {"page": "all"});
		          	let count = 0;
								rowcollection.each(function(index,elem){
								    var checkbox_value = `'${$(elem).val()}'`;
								    item_array.push(checkbox_value);
						
								});

								if(item_array.length == 0){

								}

								brcde_list = item_array.join();
		            var mparam = {
                  pono:pono,
		              data_array : brcde_list,
		              rowCount : item_array.length
		           
		            }
		           
		            $.ajax({ 
		              type: "POST",
		              url: '<?=site_url();?>fgp-rcvng-store',
		              context: document.body,
		              data: eval(mparam),
		              global: false,
		              cache: false,
		              success: function(data)  { 
		                  __mysys_apps.mepreloader('mepreloaderme',false);
		                  jQuery('#memsgtestent_bod').html(data);
		                  jQuery('#memsgtestent').modal('show');
		                 $('#btn-central-rcv').prop("disabled",true);
		                  return false;
		              },
		              error: function() {
		                alert('error loading page...');
		                __mysys_apps.mepreloader('mepreloaderme',false);
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