<?php 

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$this->db_erp = $mydbname->medb(1);
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mymdcustomer = model('App\Models\MyMDCustomerModel');
$mydataz = model('App\Models\MyDatumModel');
$mydatazua =  model('App\Models\MyDatauaModel');
$mymelibsys =  model('App\Models\Mymelibsys_Model');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$cuserrema = $mylibzdb->mysys_userrema();


?>
<style>
table.memetable, th.memetable, td.memetable {
  border: 1px solid #F6F5F4;
  border-collapse: collapse;
}
thead.memetable, th.memetable, td.memetable {
  padding: 6px;
}
.no-border {
  border: none;
  background-color: transparent;
  text-align: center;
}
</style>
<main id="main">

<div class="content-inner w-100 ">
  <!-- Forms Section-->
  <section class="forms"> 
    <div class="container-fluid">
      <div class="row" >
        <div class="col-lg-12">                           
          <div class="card p-10">
            <div class="card-header">
            <h3 class="h4 mb-0"> <i class="bi bi-list-ul"></i> RM Inventory Record</h3>
          </div>
            <div class="card-body mt-4">
              <div class="table-responsive">
                <div class="col-md-12 col-md-12 col-md-12">
                <table class="table table-bordered table-hover table-sm text-center" id="tbl-items-received">
                    <thead>
                          <tr>
                          <th nowrap="nowrap">ITEM CODE</th>
                          <th nowrap="nowrap">ITEM DESC</th>
                          <th nowrap="nowrap">UOM</th>
                          <th nowrap="nowrap">INBOUND</th>
                          <th nowrap="nowrap">DEMAND</th>
                          <th nowrap="nowrap">OUTBOUND</th>
                          <th nowrap="nowrap">BALANCE</th>
                          </tr>
                        </thead>
                          <tbody id="tblItems">
                            <?php 
                              if($rlist !== ''):
                                $nn = 1;
                                foreach($rlist as $row): 
          
                                $bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
                                $on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
                                
                              ?>

                                <tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
                                <td nowrap><?=$row['ART_CODE'];?></td>
                                <td nowrap><?=$row['ART_DESC'];?></td>
                                <td nowrap><?=$row['ART_UOM'];?></td>
                                <td nowrap><?=$row['po_rcv_qty'];?></td>
                                <td nowrap><?=$row['req_qty'];?></td>
                                <td nowrap><?=$row['balance_qty'];?></td> 
                                <td nowrap><?=$row['po_qty'];?></td> 
                                </tr>
                              <?php 
                                
                                $nn++;
                              endforeach;
      
                          ?>
                        </tbody>
                        <?php 
                  endif;
                  ?>
                      </table>
                </div>
              </div> <!-- end table-reponsive -->
            </div>
            <!-- car body end -->
            </div>
          </div>
        </div>
        <div class="accordion" id="accordionExample">
          <div class="col-lg-12">
            <div class="card">   
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <h3 class="h4 mb-0"> <i class="bi bi-journals"></i> Filtered Viewing</h3>
                  </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <div id="my-logfile-vw"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php
echo $mylibzsys->memypreloader01('mepreloaderme');
echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
echo $mylibzsys->memsgbox1('memsgtestent_success','<i class="bi bi-check-circle"></i> System Alert','...','bg-psuccess');
echo $mylibzsys->memsgbox2('boxcontent_success','<i class="bi bi-ui-radios"></i> Box content','...','bg-psuccess','modal-xl');
?>  
</main>


<script type="text/javascript"  >   

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

    $('#tbl-items-received tbody').on('click', 'button', function () {
        var data = tbl_items_scanned.row($(this).parents('tr')).data();
        getBoxcontent(data[2]);
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

function getBoxcontent(mtkn_dt){

  try { 

    var mparam = {
	        mtkn_dt: mtkn_dt,
    }
      __mysys_apps.mepreloader('mepreloaderme',true);
    $.ajax({ // default declaration of ajax parameters
        type: "POST",
        url: '<?=site_url()?>fgp-inv-box-content',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { //display html using divID
            __mysys_apps.mepreloader('mepreloaderme',false);

      $('#boxcontent_success_bod').html(data);
      $('#boxcontent_success').modal('show');
            return false;
        },
        error: function() { // display global error on the menu function
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
      __mysys_apps.mepreloader('mepreloaderme',false);
      return false;
  }  //end try
}

  

</script>