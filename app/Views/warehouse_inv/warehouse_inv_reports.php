
<div class="row gx-3 mt-3">
    <div class="mb-3 col-lg-3 col-sm-12">
        <div class="border p-3 rounded bg-light ">
            <div class="form-group mb-2">
                <label>TYPE:</label>
                <select id="opt-type" class="form-control form-control-sm">
                    <option value="stockcodelist">STOCK CODE LIST</option>
                    <option value="summary">SUMMARY</option>
                    <option value="in">IN</option>
                    <option value="out">OUT</option>
                </select>
            </div>
            <div class="form-group mb-2">
                <label>WAREHOUSE:</label>
                <input type="text" data-id="" id="txt-report-wshe" name="txt-report-wshe"  class="form-control form-control-sm txt-report-wshe">
            </div>
                 <div class="form-group mb-2">
                    <label>FILTER:</label>
                    <select id="opt-filter" class="form-control form-control-sm">
                        <option value="0">NONE</option>
                        <option value="lessqtyzero">EXCLUDE 0 QTY</option>
                    </select>
                </div>
        </div>
    </div>
    <div class="mb-3 col-lg-3 col-sm-12">  
        <div class="border p-3 rounded bg-light">
            <div class ="form-group mb-2">
                <label>BRANCH</label>
                <input type ="text" id="fld_area_code" name="fld_area_code" class="form-control form-control-sm " placeholder="FROM BRANCH" required="required" value="" data-id=""/>
            </div> 
            <div class="form-group mb-2">
              <label>FROM RACK</label>
              <input type="text" name="txt-rtransfer-rack" id="txt-rtransfer-rack" class="form-control form-control-sm frack_lookup" placeholder="FROM RACK" required="required" onkeydown="" data-type="R" data-id=""/>
            </div>
            <div class="form-group mb-2">
              <label>FROM BIN</label>
              <input type="text" id="txt-rtransfer-bin" name="txt-rtransfer-bin" class="form-control form-control-sm fbin_lookup" placeholder="FROM BIN" required="required" value="" data-type="R" data-id=""/>
            </div>
        </div>
    </div>
    <div class="mb-3 col-lg-3 col-sm-12">  
        <div class="border p-3 rounded bg-light"> 
            <div class="form-group mb-2">
                <label>ITEM CATEGORY 1:</label>
                <input type="text" id="txt-cat-one" class="form-control form-control-sm txt-cat-one">
            </div>
            <div class="form-group mb-2">
                <label>ITEM CATEGORY 1 DETAILS:</label>
                <input type="text" id="txt-cat-one-details" class="form-control form-control-sm txt-cat-one-details">
            </div>
            <div class="form-group mb-2">
                <label>ITEM CATEGORY 2:</label>
                <input type="text" id="txt-cat-two" class="form-control form-control-sm txt-cat-two">
            </div>
            <div class="form-group mb-2">
                <label>ITEM CATEGORY 3:</label>
                <input type="text" id="txt-cat-three" class="form-control form-control-sm txt-cat-three">
            </div>
              <div class="form-group mb-2">
                    <label>ITEM CATEGORY 4:</label>
                    <input type="text" id="txt-cat-four" class="form-control form-control-sm txt-cat-four">
            </div>
        </div>
    </div>
    <div class="mb-3 col-lg-3 col-sm-12">  
        <div class="border p-3 rounded bg-light">
            <div class="form-group mb-2">
                <label>FROM DATE:</label>
                <input type="date" id="txt-date-from" max="9999-12-31" class="form-control form-control-sm">
            </div>
            <div class="form-group mb-2">
                <label>TO DATE:</label>
                <input type="date" id="txt-date-to" max="9999-12-31" class="form-control form-control-sm">
            </div>
           <div class="form-group mt-3 ">
                <button class="btn btn-success btn-sm col-lg-12" id="btn-generate-report">GENERATE</button>
            </div>
            <div id="dl-button" class="form-group mt-3 ">
                
            </div>
        </div>
    </div>
</div>
        

<script type="text/javascript" >

 frack_lookup();
 fbin_lookup();
report_wshe_lookup();
function report_wshe_lookup() { 

    $('.txt-report-wshe' ) 
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
            source: '<?= site_url(); ?>get-cdwarehouse-list?mtkn_plnt=',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) { 
                var sValue = jQuery(oEvent.target).val();
                
                //$(this).autocomplete('option', 'source', '<?=site_url();?>get-cdwarehouse-list?mtkn_plnt=');
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                jQuery(this).attr('title', jQuery.trim(ui.item.value));
                jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));
                var mtkn_rid = ui.item.mtkn_rid;
                this.value = ui.item.value;
                jQuery('#txt-report-wshe').val(terms);
                jQuery('#txt-report-wshe').attr("data-id",mtkn_rid);
              
               //empty rack 
                jQuery('#txt-rtransfer-rack').val('');
                jQuery('#txt-rtransfer-rack').attr("data-id",'');
                jQuery('#txt-rtransfer-rack').attr('alt', '');
                jQuery('#txt-rtransfer-rack').attr('title','');
               
                //console.log(mtkn_rid);
                return false;
            }
        })
        .click(function() { 
            //jQuery(this).keydown(); 
            var terms = this.value;
            //jQuery(this).autocomplete('search', '');
            jQuery(this).autocomplete('search', jQuery.trim(terms));
        });         
    
}//end __my_wshe_lkup



$('#btn-generate-report').click(function (e){
   // $(this).prop("disabled",true);
    //$.showLoading({name: 'line-pulse', allowHide: false });
    
        
    var report_type = $('#opt-type').val();
    var warehouse   = $('#txt-report-wshe').attr("data-id");
    var filter      = $('#opt-filter').val();
    var cat_one     = $('#txt-cat-one').val();
    var cat_one_dt  = $('#txt-cat-one-details').val();
    var cat_two     = $('#txt-cat-two').val();
    var cat_three   = $('#txt-cat-three').val();
    var cat_four    = $('#txt-cat-four').val();
    var from_date   = $('#txt-date-from').val();
    var to_date     = $('#txt-date-to').val();
    var __rack      = $('#txt-rtransfer-rack').val();
    var __bin       = $('#txt-rtransfer-bin').val();
    var __fld_area_code = $('#fld_area_code').val();


    var mparam = {
        'report_type' : report_type,
        'warehouse' : warehouse,
        'filter' : filter,
        'cat_one' : cat_one,
        'cat_one_dt' : cat_one_dt,
        'cat_two' : cat_two,
        'cat_three' : cat_three,
        'cat_four' : cat_four,
        'from_date' : from_date,
        'to_date' : to_date,
        '__rack': __rack,
        '__bin': __bin,
        '__fld_area_code':__fld_area_code
    }
    __mysys_apps.mepreloader('mepreloaderme',true);
    $.ajax({
        type: "POST",
        url: '<?=site_url()?>whinv-generate-report',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(response)  { //display html using divID
           // $.hideLoading();
             __mysys_apps.mepreloader('mepreloaderme',false);
            $('#btn-generate-report').prop("disabled",false);

            $('#dl-button').html(response);
            
            // var res = jQuery.parseJSON(response);

            // if(res.result){
            //     window.open(res.data);
            // }
            // else{
            //   jQuery('#myModSysMsgBod').html(res.data);
            //   jQuery('#myModSysMsg').modal('show');
            // }

            
            return false;
        },
        error: function() { // display global error on the menu function
            alert('error loading page...');
           // $.hideLoading();
             __mysys_apps.mepreloader('mepreloaderme',false);
            return false;
        } 

    });

});


cat_one_lookup();
function cat_one_lookup() { 

    $('.txt-cat-one' ) 
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
            source: '<?= site_url(); ?>get-catg1hd',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) { 
                var sValue = jQuery(oEvent.target).val();
                
                $(this).autocomplete('option', 'source', '<?=site_url();?>get-catg1hd');
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                jQuery(this).attr('title', jQuery.trim(ui.item.value));
                jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_recid));

                this.value = ui.item.value;

                return false;
            }
        })
        .click(function() { 
            //jQuery(this).keydown(); 
            var terms = this.value;
            //jQuery(this).autocomplete('search', '');
            jQuery(this).autocomplete('search', jQuery.trim(terms));
        });         
    
}//end __my_wshe_lkup

cat_one_dt_lookup();
function cat_one_dt_lookup() { 

    $('.txt-cat-one-details' ) 
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
            source: '<?= site_url(); ?>get-catg1dt',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) { 
                var sValue = jQuery(oEvent.target).val();
                
                $(this).autocomplete('option', 'source', '<?=site_url();?>get-catg1dt');
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                jQuery(this).attr('title', jQuery.trim(ui.item.value));
                jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_recid));

                this.value = ui.item.value;

                return false;
            }
        })
        .click(function() { 
            //jQuery(this).keydown(); 
            var terms = this.value;
            //jQuery(this).autocomplete('search', '');
            jQuery(this).autocomplete('search', jQuery.trim(terms));
        });         
    
}//end __my_wshe_lkup


cat_two_lookup();
function cat_two_lookup() { 

    $('.txt-cat-two' ) 
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
            source: '<?= site_url(); ?>get-catg2',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) { 
                var sValue = jQuery(oEvent.target).val();
                
                $(this).autocomplete('option', 'source', '<?=site_url();?>get-catg2');
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                jQuery(this).attr('title', jQuery.trim(ui.item.value));
                jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_recid));

                this.value = ui.item.value;

                return false;
            }
        })
        .click(function() { 
            //jQuery(this).keydown(); 
            var terms = this.value;
            //jQuery(this).autocomplete('search', '');
            jQuery(this).autocomplete('search', jQuery.trim(terms));
        });         
    
}//end __my_wshe_lkup

cat_three_lookup();
function cat_three_lookup() { 

    $('.txt-cat-three' ) 
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
            source: '<?= site_url(); ?>get-catg3',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) { 
                var sValue = jQuery(oEvent.target).val();
                
                $(this).autocomplete('option', 'source', '<?=site_url();?>get-catg3');
            },
            select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                jQuery(this).attr('title', jQuery.trim(ui.item.value));
                jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_recid));

                this.value = ui.item.value;

                return false;
            }
        })
        .click(function() { 
            //jQuery(this).keydown(); 
            var terms = this.value;
            //jQuery(this).autocomplete('search', '');
            jQuery(this).autocomplete('search', jQuery.trim(terms));
        });         
    
}//end __my_wshe_lkup


jQuery('#fld_area_code')
  // don't navigate away from the field on tab when selecting an item
      .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
              jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
              event.preventDefault();
      }
      if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
      }
  })
      .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-branch-list',
          focus: function() {
              // prevent value inserted on focus
              return false;
          },
          search: function(oEvent, oUi) {
              var sValue = jQuery(oEvent.target).val();
              //var comp = jQuery('#fld_Company_dr').val();
              var comp = jQuery('#fld_Company_dr').attr("data-id");
              jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-branch-list'); 
              //jQuery(oEvent.target).val('&mcocd=1' + sValue);
             
          },
          select: function( event, ui ) {
              var terms = ui.item.value;
              var mtkn_comp = ui.item.mtkn_comp;
              jQuery('#fld_area_code').val(terms);
              //jQuery('#fld_Company_dr').val(mtkn_comp);
              jQuery(this).autocomplete('search', jQuery.trim(terms));
              return false;
          }
      })
      .click(function() {
          /*var comp = jQuery('#fld_Company_dr').val();
          var comp2 = this.value +'XOX'+comp;
          var terms = comp2.split('XOX');//dto naq 4/25
          */
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));
        
      });


 jQuery('#txt-cat-four')
        // don't navigate away from the field on tab when selecting an item
            .bind( 'keydown', function( event ) {
                if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                    jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                    event.preventDefault();
            }
            if( event.keyCode === jQuery.ui.keyCode.TAB ) {
                event.preventDefault();
            }
        })
            .autocomplete({
                minLength: 0,
                source: '<?= site_url(); ?>get-catg4',
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                search: function(oEvent, oUi) {
                    var sValue = jQuery(oEvent.target).val();
                    //var comp = jQuery('#fld_Company_dr').val();
                    var comp = jQuery('#fld_Company_dr').attr("data-id");
                    jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-catg4'); 
                    //jQuery(oEvent.target).val('&mcocd=1' + sValue);
                   
                },
                select: function( event, ui ) {
                    var terms = ui.item.value;
                    var mtkn_comp = ui.item.mtkn_comp;
                    jQuery('#txt-cat-four').val(terms);
                    //jQuery('#fld_Company_dr').val(mtkn_comp);
                    jQuery(this).autocomplete('search', jQuery.trim(terms));
                    return false;
                }
            })
            .click(function() {
                /*var comp = jQuery('#fld_Company_dr').val();
                var comp2 = this.value +'XOX'+comp;
                var terms = comp2.split('XOX');//dto naq 4/25
                */
                var terms = this.value;
                jQuery(this).autocomplete('search', jQuery.trim(terms));
              
    });
</script>
