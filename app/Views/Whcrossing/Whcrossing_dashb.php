<?php 
/**
 *	File        : masterdata/myprodt-invent-recs.php
 *  Auhtor      : Oliver V. Sta Maria
 *  Date Created: Sept 17, 2017
 * 	last update : Sept 17, 2017
 * 	description : Product Type Inventory Records
 */
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$cuser = $this->mylibz->mysys_user();
$mpw_tkn = $this->mylibz->mpw_tkn();
$months = $this->mydataz->lk_Active_Months($this->db_erp);
$years = $this->mydataz->lk_Active_Year($this->db_erp);
$txtd1_month= '';
$txtd1_year= '';

?>
<div class="row" >
  <div class="col-lg-6 col-md-12 offset-lg-3 offset-md-0">
    <div class="row gy-2 mb-2">
      <div class="form-label col-lg-3">
        Branch Name:
      </div>
      <div class="col-lg-9">
        <div class="input-group"> 
        <input type="text" class="form-control form-control-sm input-sm" data-mtknid="" id="fld_dmgpbranch" name="fld_dmgpbranch" value="" required/>
        <div class="input-group-prepend">
           <div class="input-group-text form-control-sm" style="border-radius: 0 0.2rem 0.2rem 0;">
            <i class="fa fa-caret-down text-secondary"></i>
           </div>
          </div>
         </div>
      </div>
    </div>

    <div class="row gy-2 mb-2">
      <div class="form-label col-lg-3">
        Month:
      </div>
      <div class="col-lg-9">
        <div class="input-group"> 
        <?=$this->mylibz->mypopulist_2($months,$txtd1_month,'fld_dmgp_month','class="form-control form-control-sm" ','','');?>
     
        </div>
      </div>
    </div>

    <div class="row gy-2 mb-2">
      <div class="form-label col-lg-3">
        Year:
      </div>
      <div class="col-lg-9">
        <div class="input-group"> 
        <?=$this->mylibz->mypopulist_2($years,$txtd1_year,'fld_dmgp_year','class="form-control form-control-sm" ','','');?>
    
      </div>
    </div>
  </div>
    
   <div class="row gy-2 mb-2">
      <div class="col-lg-3 offset-lg-3">
        <button class="btn btn-success btn-sm"  id="submit_btn_dmgp" type="submit"> <i class="fa fa-search"></i> View</button>
      </div>
    </div>
    <div class=" col-lg-8" style="padding: 20px !important">  
      
      <div class="form-group row" id="view_process_dashdmgp">
        
      </div>
    </div>
  </div> 
</div>

	 <!-- Statistics -->
     <div class=" p-3">
       <div class="row">
        <div class="col-lg-3">
         <div class="card bg-light mb-1">
           <div class="card-body ">
             <div class="d-flex align-items-center">
               <div class="icon p-4 mr-2 text-white flex-shrink-0 bg-red "><i class="fa fa-pencil-square-o" style="font-size: 40px;"></i></div>
               <div class="ms-3"><strong class="text-danger text-md d-block lh-1 mb-1" id="draftdmgpromo">1</strong>
                 <small class="text-uppercase  small d-block lh-1 font-weight-bold text-secondary">Draft</small>
               <a  href='#a' class="closeLink small-box-footer draftdmgpromo_vw"><i class="text-gray-500 fa fa-angle-down text-secondary"></i></a></div>

             </div>
           </div>
           </div>
         </div>
         <div class="col-lg-3 col-xl-3">
         <div class="card bg-light mb-1 ">
           <div class="card-body ">
             <div class="d-flex align-items-center">
               <div class="icon p-4 mr-2 text-white flex-shrink-0 bg-green"><i class="fa fa-paper-plane" style="font-size: 40px;"></i></div>
               <div class="ms-3"><strong class="text-success text-md d-block lh-1 mb-1" id="sentdmgpromo">2</strong><small class="text-uppercase small font-weight-bold d-block lh-1 text-secondary">Sent/For Approval</small>
               <a href='#a' class="closeLink small-box-footer sentdmgpromo_vw"><i class="text-gray-500 fa fa-angle-down text-secondary"></i></a></div>
             </div>
           </div>
         </div>
        </div>
        <div class="col-lg-3 col-xl-3">
         <div class="card bg-light mb-1">
           <div class="card-body ">
             <div class="d-flex align-items-center">
               <div class="icon p-4 mr-2 text-white flex-shrink-0 bg-info "><i class="fa fa-check-circle" style="font-size: 40px;"></i></div>
               <div class="ms-3"><strong class="text-info text-md d-block lh-1 mb-1" id="aprdmgpromo">3</strong><small class="text-uppercase  small d-block font-weight-bold lh-1 text-secondary">Approved</small>
               <a href='#a' class="closeLink small-box-footer aprdmgpromo_vw"><i class="text-gray-500 fa fa-angle-down text-secondary"></i></a></div>
             </div>
           </div>
         </div>
       </div>
       <div class="col-lg-3 col-xl-3">
         <div class="card bg-light mb-1">
           <div class="card-body ">
             <div class="d-flex align-items-center">
               <div class="icon p-4 mr-2 text-white flex-shrink-0 bg-warning text-white"><i class="fa fa-times-circle" style="font-size: 40px;"></i></div>
               <div class="ms-3"><strong class="text-warning text-md d-block lh-1 mb-1" id="dprdmgpromo">4</strong><small class="text-uppercase small font-weight-bold d-block lh-1 text-secondary">Disapproved</small>
               <a href='#a' class="closeLink small-box-footer dprdmgpromo_vw"><i class="text-gray-500 fa fa-angle-down text-secondary"></i></a></div>
             </div>
           </div>
         </div>
       </div>
       </div>
   </div>

 


<div class="w-100 mt-4">
		<div id="mymodoutrecs_dmgp">
			<?php
				/*$data = $this->mymdacct->view_post_recs(1,20);
				$this->load->view('masterdata/acct_mod/man_recs/myacct_manrecs-post-recs',$data);*/
			?>
			</div>
	<!-- end col-md-12 -->
</div>
<script type="text/javascript"> 
//info_box_get_total();
//setInterval(function(){ 
	//info_box_get_total();
//}, 
//180000);
 $('#submit_btn_dmgp').on('click',function() {
    
    var fld_dmgpbranch    = jQuery('#fld_dmgpbranch').val();
    var fld_dmgpbranch_id = jQuery('#fld_dmgpbranch').data('mtknid');
    var fld_dmgp_month    = jQuery('#fld_dmgp_month').val();
    var fld_dmgp_year     = jQuery('#fld_dmgp_year').val();

    if(fld_dmgpbranch == ''){
      alert('Branch is required!!');
      return false;
    }



    info_box_get_total(fld_dmgpbranch,fld_dmgpbranch_id,fld_dmgp_month,fld_dmgp_year);
});
info_box_get_total();
function info_box_get_total(fld_dmgpbranch,fld_dmgpbranch_id,fld_dmgp_month,fld_dmgp_year){
     $.showLoading({name: 'line-pulse', allowHide: false });
     var mparam = {
          fld_dmgpbranch:fld_dmgpbranch,
          fld_dmgpbranch_id:fld_dmgpbranch_id,
          fld_dmgp_month:fld_dmgp_month,
          fld_dmgp_year:fld_dmgp_year
        }; 
     $.ajax({
     url: "<?php echo base_url();?>WhCrossing/dashdmgp_recs",
     type: "POST",
      context: document.body,
      data: eval(mparam),
      global: false,
      cache: false,
     success: function(data){
       var result =jQuery.parseJSON(data);
        $("#idmgpromo").html(result.idmgpromo);
        $("#odmgpromo").html(result.odmgpromo);
        $("#edmgpromo").html(result.edmgpromo);
        $("#cdmgpromo").html(result.cdmgpromo);
        $("#draftdmgpromo").html(result.draftdmgpromo);
        $("#sentdmgpromo").html(result.sentdmgpromo);
        $("#aprdmgpromo").html(result.aprdmgpromo);
        $("#dprdmgpromo").html(result.dprdmgpromo);
        $("#redmgpromo").html(result.redmgpromo);
        $.hideLoading();

   },
   error: function() { alert('error loading page'); }
});
}
$('.idmgpromo_vw').on('click',function() {
   dashdmgp_recs_vw('I');
});
$('.odmgpromo_vw').on('click',function() {
    dashdmgp_recs_vw('O');
});
$('.edmgpromo_vw').on('click',function() {
    dashdmgp_recs_vw('E');
});
$('.cdmgpromo_vw').on('click',function() {
    dashdmgp_recs_vw('C');
});

$('.draftdmgpromo_vw').on('click',function() {
   dashdmgp_recs_vw('DR');
});
$('.sentdmgpromo_vw').on('click',function() {
    dashdmgp_recs_vw('SN');
});
$('.aprdmgpromo_vw').on('click',function() {
    dashdmgp_recs_vw('AP');
});
$('.dprdmgpromo_vw').on('click',function() {
    dashdmgp_recs_vw('DS');
});
$('.redmgpromo_vw').on('click',function() {
    dashdmgp_recs_vw('RE');
});

function dashdmgp_recs_vw(mtkn) { 
    var fld_dmgpbranch = jQuery('#fld_dmgpbranch').val();
    var fld_dmgpbranch_id = jQuery('#fld_dmgpbranch').data('mtknid');
    var fld_dmgp_month = jQuery('#fld_dmgp_month').val();
    var fld_dmgp_year = jQuery('#fld_dmgp_year').val();
    
    try { 
        
        $.showLoading({name: 'line-pulse', allowHide: false });
        
        var mparam = {
        	fld_dmgpbranch:fld_dmgpbranch,
          fld_dmgpbranch_id:fld_dmgpbranch_id,
          fld_dmgp_month:fld_dmgp_month,
          fld_dmgp_year:fld_dmgp_year,
          mtkn:mtkn,
          mpages: 1

        }; 

    $.ajax({ // default declaration of ajax parameters
        type: "POST",
        url: '<?=site_url();?>WhCrossing/myacct_vw_dashdmgp',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,

        success: function(data)  { //display html using divID
            $.hideLoading();
            $('#mymodoutrecs_dmgp').html(data);
			       return false;
        },
        error: function() { // display global error on the menu function
            alert('error loading page...');
            $.hideLoading();
            return false;
        }   
    }); 
    } catch(err) {
        var mtxt = 'There was an error on this page.\n';
        mtxt += 'Error description: ' + err.message;
        mtxt += '\nClick OK to continue.';
        alert(mtxt);
        $.hideLoading();
        return false;
    }  //end try            
}

jQuery('#fld_dmgpbranch')
  // don't navigate away from the field on tab when selecting an item
  .bind( 'keydown', function( event ) {
    if ( event.keyCode === jQuery.ui.keyCode.TAB &&
      jQuery( this ).data( 'autocomplete' ).menu.active ) {
      event.preventDefault();
  }
  if( event.keyCode === jQuery.ui.keyCode.TAB ) {
    event.preventDefault();
  }
})
  .autocomplete({
    minLength: 0,
    source: '<?= site_url(); ?>mysearchdata/companybranch_v/',
    focus: function() {
              // prevent value inserted on focus
              return false;
          },
          search: function(oEvent, oUi) {
            var sValue = jQuery(oEvent.target).val();
              //var comp = jQuery('#fld_Company').val();
              //var comp = jQuery('#fld_Company').attr("data-id");
              jQuery(this).autocomplete('option', 'source', '<?=site_url();?>mysearchdata/companybranch_v'); 
              //jQuery(oEvent.target).val('&mcocd=1' + sValue);

          },
          select: function( event, ui ) {
            var terms = ui.item.value;
            var mtkn_comp = ui.item.mtkn_comp;
            var mtknr_rid = ui.item.mtknr_rid;
            jQuery('#fld_dmgpbranch').val(terms);
            jQuery('#fld_dmgpbranch').data('mtknid',mtknr_rid);
            jQuery(this).autocomplete('search', jQuery.trim(terms));
            return false;
          }
      })
  .click(function() {
          /*var comp = jQuery('#fld_Company').val();
          var comp2 = this.value +'XOX'+comp;
          var terms = comp2.split('XOX');//dto naq 4/25
          */
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));

      });


   
	
</script>
