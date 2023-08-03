<?php
/**
 *	File        : accounting/gjv-entry.php
 *  Auhtor      : Oliver V. Sta Maria
 *  Date Created: Apr 15, 2022
 * 	last update : Apr 15, 2022
 * 	description : General Journal Voucher Entry
 */
 
$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mydataz = model('App\Models\MyDatumModel');
$mydbname = model('App\Models\MyDBNamesModel');
$db_erp = $mydbname->medb(0);

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

$cuserrema=$mylibzdb->mysys_userrema();
$mtkn_trxno = $request->getVar('mtkn_trxno');
$adftag = $mydataz->lk_Active_DF($db_erp);
$txtdf_tag= '';
$txtgltrx_no = '';
$txtcomp = '';
$txtarea_code = '';
$txtsupplier = '';
$txtpono = '';
$txtgldate = '';
$txtrems = '';
$mmnhd_rid ='';
$nmnrecs = 0;
$txtsubtdeb='';
$txtsubtcre='';
$rr_file_upld = '';
$COMP_NAME = '';
$BRNCH_NAME = '';
$VEND_NAME = '';
$entTyp = '';
$entTyprid = '';

$txtpotobrnc='';
$txtimsno ='';
$txtgldate = $mylibzsys->mydate_mmddyyyy(date('Y-m-d'));
$str = "SELECT COUNT(*) __nmyrecs FROM {$db_erp}.`trx_manrecs_gl_hd` ";
$qq = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
$rw = $qq->getRowArray();
$nmyrecs = $rw['__nmyrecs'];
$dis3 ='';

if(!empty($mtkn_trxno)) { 
    $str = "SELECT aa.*,
    bb.`COMP_NAME`,
    cc.`BRNCH_NAME`,
    dd.`VEND_NAME`,
    ee.`recid` AS ETrid,
    ee.`entyp_desc`,
    SHA2(CONCAT(aa.`recid`,'{$mpw_tkn}'),384) mtkn_trxtr 
    FROM {$db_erp}.`trx_manrecs_gl_hd` aa
    LEFT JOIN {$db_erp}.`mst_company` AS bb ON aa.`comprid` = bb.`recid`
    LEFT JOIN {$db_erp}.`mst_companyBranch` AS cc ON aa.`brnchrid` = cc.`recid`
    LEFT JOIN {$db_erp}.`mst_vendor` AS dd ON aa.`suprid` = dd.`recid`
    LEFT JOIN {$db_erp}.`mst_gj_entyp` AS ee ON aa.`enttyp` = ee.`recid`
    WHERE SHA2(CONCAT(aa.`recid`,'{$mpw_tkn}'),384) = '{$mtkn_trxno}' ";
    $qq = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
    $rw = $qq->getRowArray();
    $mmnhd_rid = $rw['mtkn_trxtr'];
    $txtgltrx_no = $rw['gltrx_no'];
    $COMP_NAME = $rw['COMP_NAME'];
    $BRNCH_NAME = $rw['BRNCH_NAME'];
    $VEND_NAME = $rw['VEND_NAME'];
    $rr_file_upld = $rw['file_upld'];
    $entTyp = $rw['entyp_desc'];
    $entTyprid = $rw['ETrid'];
    $txtdf_tag= $rw['df_tag'];
    $txtgldate = $mylibzsys->mydate_mmddyyyy($rw['gl_date']);
    $txtrems = $rw['rems'];
    $txtsubtdeb= number_format($rw['hd_subtdeb'],2,'.','');
    $txtsubtcre= number_format($rw['hd_subtcre'],2,'.','');
    $dis3 = (($rw['post_tag'] == 'Y') ? "disabled" : '');
}
$str_style='';

?>
<main id="main" class="main metblentry-font">
<?=form_open('myaccounting/gjv_save','class="row g-3 needs-validation" id="myfrmsrec_artm" ');?>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="row mb-3">
                <div class="col-sm-3">
                    <span class="fw-bold">GV Trx. No.</span>
                </div>
                <div class="col-sm-9">
                    <input type="text" id="txtgltrx_no" name="txtgltrx_no" class="form-control form-control-sm meinput-sm-pad" value="<?=$txtgltrx_no;?>" readonly />
                    <input type="hidden" name="__hmtkn_trxnoid" id="__hmtkn_trxnoid" value="<?= $mmnhd_rid;?>" />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <span class="fw-bold">Company</span>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm meinput-sm-pad" id="fld_Company" name="fld_Company" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <span class="fw-bold">Branch</span>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm input-sm meinput-sm-pad" id="_brnch" value="<?= $BRNCH_NAME ?>" required/>
                    <input type="hidden" id="h_brnch" value=""/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <span class="fw-bold">Upload Attachment</span>
                </div>
                <div class="col-sm-9">
                    <input data-id="__fl_upld" accept="image/gif,image/jpeg,image/png,application/pdf" class="form-control form-control-sm" size="5" id="__fl_upld" type="file" multiple name="__fl_upld[]">
                </div>
            </div>

        </div> <!-- end col-6 -->
        <div class="col-md-6">
            <div class="row mb-3">
                <div class="col-sm-3">
                    <span>Trx. Date:</span>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm meinput-sm-pad form_datetime" data-id="" id="fld_gldate" name="fld_gldate" value="<?=$txtgldate;?>" disabled/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <span>Supplier:</span>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm meinput-sm-pad" id="fld_supnme" value="<?= $VEND_NAME ?>"/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <span>Entry Type:</span>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm meinput-sm-pad" id="_entTyp" value="<?= $entTyp ?>"/>
                    <input type="hidden" id="_entTyprid" value="<?= $entTyprid ?>"/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <span>Remarks:</span>
                </div>
                <div class="col-sm-9">
                    <textarea type="text" class="form-control form-control-sm meinput-sm-pad" name="fld_rems" id="fld_rems"><?=$txtrems;?></textarea>
                </div>
            </div>

        </div> <!-- end col-6 2nd screen -->
    </div>

    <!-- table entries -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tbl_GLData" class="metblentry-font">
                    <thead>
                        <th></th>
                        <th class="text-center" nowrap>
                            <button type="button" class="btn btn-primary btn-sm p-1 pb-0 mebtnpt1" onclick="javascript:my_add_line_item();" >
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </th>
                        <th>Account Name</th>
                        <th>Ref. No.</th>
                        <th>Ref Date</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Particulars</th>
                    </thead>
                    <tbody id="contentArea">
                        <?php

                        $str = "
                        SELECT
                        SHA2(CONCAT(a.`recid`,'{$mpw_tkn}'),384) mtkn_mndttr,
                        SHA2(CONCAT(b.`GLPA_CODE`,'{$mpw_tkn}'),384) mtkn_glmtr,
                        a.`mrhd_rid`,
                        a.`gltrx_no`,
                        a.`gl_acct_code`,
                        a.`tdebit`,
                        a.`tcredit`,
                        a.`cost_cntr_id`,
                        a.`paticulars`,
                        a.`muser`,
                        a.`encd`,
                        b.`GLPA_DESC`,
                        a.`refno`,
                        a.`refdte`
                        FROM
                        {$db_erp}.`trx_manrecs_gl_dt` a
                        JOIN 
                        {$db_erp}.`mst_GL_ParticularAcct` b
                        ON
                        a.`gl_acct_code` = b.`GLPA_CODE`
                        WHERE
                        SHA2(CONCAT(a.`mrhd_rid`,'{$mpw_tkn}'),384) = '{$mmnhd_rid}'
                        ORDER BY 
                        a.`recid`
                        ";

                        $qdt = $mylibzdb->myoa_sql_exec($str, 'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__ . chr(13) . chr(10) . 'Line Number: ' . __LINE__);

                        foreach($qdt->getResultArray() as $rdt) { 
                            $nmnrecs++;
                            $refdte = $mylibzsys->mydate_mmddyyyy($rdt['refdte']);

                            ?>
                            <tr>
                                <td><?=$nmnrecs;?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm" type="button" onclick="javascript:__mn_items_drecs('<?=$rdt['mtkn_mndttr'];?>','<?=$mmnhd_rid;?>');" <?=$dis3;?>>
                                        <i class="bi bi-x-square-fill" ></i>
                                    </button>
                                    <input type="hidden" id="macctid_<?=$nmnrecs;?>" value="<?=$rdt['mtkn_glmtr'];?>"/>
                                    <input type="hidden" id="mid_<?=$nmnrecs;?>" value="<?=$rdt['mtkn_mndttr'];?>"/>
                                </td>
                                <td><input type="text" id="fld_acct_<?=$nmnrecs;?>" size="50" class="macctname" value="<?=$rdt['GLPA_DESC'];?>"/></td>
                                <td><input type="text" id="fld_refno_<?=$nmnrecs;?>" size="50" value="<?= $rdt['refno']; ?>"/></td>
                                <td><input type="text" id="fld_refdte_<?=$nmnrecs;?>" size="50" class="form_datetime" value="<?= $refdte; ?>"/></td>
                                <td><input type="text" id="fld_debit_<?=$nmnrecs;?>" size="15" value="<?=$rdt['tdebit'];?>" onkeypress="return __meNumbersOnly(event)" onmouseover="javascript:__tamt_compute_totals();" onkeyup="javascript:__tamt_compute_totals();" onmouseout="javascript:__tamt_compute_totals();" onclick="javascript:__tamt_compute_totals();" onblur="javascript:__tamt_compute_totals();"/></td>
                                <td><input type="text" id="fld_credit_<?=$nmnrecs;?>" size="15" value="<?=$rdt['tcredit'];?>" onkeypress="return __meNumbersOnly(event)" onmouseover="javascript:__tamt_compute_totals();" onkeyup="javascript:__tamt_compute_totals();" onmouseout="javascript:__tamt_compute_totals();" onclick="javascript:__tamt_compute_totals();" onblur="javascript:__tamt_compute_totals();"/></td>
                                <td><input type="text" id="fld_partic" size="40" class="fform_cust fform_cust-sm" value="<?=$rdt['paticulars'];?>" /></td>
                            </tr>
                            <?php 
                        }
                        $qdt->freeResult();
                        ?>
                        <tr style="display:none;">
                            <td></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm p-1 pb-0 mebtnpt1" onclick="javascript:confirmalert(this);">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                                <input type="hidden" value=""/>
                                <input type="hidden" value=""/>
                                <input type="hidden" value=""/>
                            </td>
                            <td><input type="text" size="50" class="macctname" value=""/></td>
                            <td><input type="text" id="fld_refno_" value=""/></td>
                            <td><input type="text" id="fld_refdte_" class="form_datetime" value=""/></td>
                            <td><input type="text" size="15" value="0" onkeypress="return __meNumbersOnly(event)" onmouseover="javascript:__tamt_compute_totals();" onmouseout="javascript:__tamt_compute_totals();" onkeyup="javascript:__tamt_compute_totals();" onclick="javascript:__tamt_compute_totals();" onblur="javascript:__tamt_compute_totals();" /></td>
                            <td><input type="text" size="15" value="0" onkeypress="return __meNumbersOnly(event)" onmouseover="javascript:__tamt_compute_totals();" onmouseout="javascript:__tamt_compute_totals();" onkeyup="javascript:__tamt_compute_totals();" onclick="javascript:__tamt_compute_totals();" onblur="javascript:__tamt_compute_totals();" /></td>
                            <td><input type="text" size="40" value="" /></td>
                        </tr>                
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5"></th>
                            <th>
                                <input type="text" size="10"  class="form-control form-control-sm input-sm" name="fld_subtdeb" id="fld_subtdeb" value="<?=$txtsubtdeb;?>" required readonly/>
                            </th>
                            <th>
                                <input type="text" size="10" class="form-control form-control-sm input-sm" name="fld_subtcre" id="fld_subtcre" value="<?=$txtsubtcre?>" required readonly/>
                            </th>
                            <th></th>
                        </tr>   
                    </tfoot>
                </table>
            </div>
        </div>
    </div> <!-- end table entries -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <button class="btn btn-secondary btn-sm metblentry-font" id="mbtn_mn_Save" type="submit">Save</button>&nbsp;
            <button class="btn btn-info btn-sm metblentry-font" id="mbtn_mn_NTRX" type="button">New Trx</button>&nbsp;
        </div>
    </div>

<?=form_close();?> <!-- end of ./form -->

<?php 
    //echo $mylibzsys->memsgbox_yesno1('memsggjv','Message GJV','Are You Sure!!!');
    echo $mylibzsys->memypreloader01('mepreloaderme');
    //echo $mylibzsys->memsgbox1('memsggjv2','Alert Message','...');
    
?>
</main>

<script>
    __mysys_apps.mepreloader('mepreloaderme',false);

    function meSetCellPadding () {
        var metable = document.getElementById ("tbl_GLData");
        metable.cellPadding = 3;
        metable.style.border = "1px solid #F6F5F4";
        var tabletd = metable.getElementsByTagName("td");
        //for(var i=0; i<tabletd.length; i++) {
        //    var td = tabletd[i];
        //    td.style.borderColor ="#F6F5F4";
        //}

    }
    meSetCellPadding();


    //jQuery('#memedate').datepicker({
    //    format: 'mm/dd/yyyy',
    //    autoclose: true
    //});

    //jQuery('.iamdate').click(function() {
    //    jQuery('#memedate').datepicker('show');
    //});


    function __meNumbersOnly(e) {
        var code = (e.which) ? e.which : e.keyCode;
        if(!((code > 47 && code < 58) || code == 46)) { 
            e.preventDefault();
        }
    } //end __meNumbersOnly

    function confirmalert(smuid){
        var userselection = confirm("Are you sure you want to remove this item permanently?");
        if (userselection == true) {
            alert("Item deleted!");
            nullvalue(smuid);
        }
        else {
            alert("Item is not deleted!");
        }    
    }  //end confirmalert

    function nullvalue(muid) {

        jQuery(muid).parent().parent().remove();
        jQuery( '#tbl_GLData tr').each(function(i) { 
            jQuery(this).find('td').eq(0).html(i);
        });
        __tamt_compute_totals();
    }  //end nullvalue


    // FOR COMPANY 
    jQuery('#fld_Company').bind( 'keydown', function( event ) {
        if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'autocomplete' ).menu.active ) {
            event.preventDefault();
    }
    if( event.keyCode === jQuery.ui.keyCode.TAB ) {
        event.preventDefault();
    }})
    .autocomplete({
        minLength: 0,
        source: '<?= site_url(); ?>my-search/company/',
        focus: function() {

            return false;
        },
        search: function(oEvent, oUi) {
            var sValue = jQuery(oEvent.target).val();

            //$(this).autocomplete('option', 'source', '<?=site_url();?>myaccounting/company/';
        },
        select: function( event, ui ) {
            var terms = ui.item.value;
            this.value = ui.item.value;
            jQuery('#fld_Company').val(terms);

            return false;
        }
    })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
    });

    // FOR ENTRY TYPE
    jQuery('#_entTyp').bind( 'keydown', function( event ) {
        if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'autocomplete' ).menu.active ) {
            event.preventDefault();
    }
    if( event.keyCode === jQuery.ui.keyCode.TAB ) {
        event.preventDefault();
    }})
    .autocomplete({
        minLength: 0,
        source: '<?= site_url(); ?>my-search/gj-entyp/',
        focus: function() {

            return false;
        },
        search: function(oEvent, oUi) {
            var sValue = jQuery(oEvent.target).val();
        },
        select: function( event, ui ) {
            var terms = ui.item.value;
            this.value = ui.item.value;
            jQuery('#_entTyp').val(terms);
            jQuery('#_entTyprid').val(ui.item.mtkn_rid);

            return false;
        }
    })
    .click(function() {
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));
    });

    // For Branch
    jQuery('#_brnch').bind( 'keydown', function( event ) {
        if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'autocomplete' ).menu.active ) {
            event.preventDefault();
    }
    if( event.keyCode === jQuery.ui.keyCode.TAB ) {
        event.preventDefault();
    }})
    .autocomplete({
        minLength: 0,
        source: '<?= site_url(); ?>mysearchdata/companybranch/?getComp=' + jQuery('#fld_Company').val(),
        focus: function() {

            return false;
        },
        search: function(oEvent, oUi) {
            var sValue = jQuery(oEvent.target).val();
            var compData = jQuery('#fld_Company').val();

            jQuery(this).autocomplete('option', 'source', '<?=site_url();?>mysearchdata/companybranch/?getComp=' + compData);
        },
        select: function( event, ui ) {
            newid = this.id;
            var terms = ui.item.value;
            jQuery('#_brnch').val(terms);
            jQuery('#h_brnch').val(ui.item.mtkn_rid);
            jQuery('#me_area_code').html(' [' + ui.item.__brnch_code + ']');
            jQuery(this).autocomplete('search', jQuery.trim(terms));
            return false;
        }
    })
    .click(function() { 
        var terms = this.value;
        jQuery(this).autocomplete('search', jQuery.trim(terms));    
    });

    // FOR SUPPLIER
    jQuery('#fld_supnme' ) 
    .bind( 'keydown', function( event ) {
        if ( event.keyCode === jQuery.ui.keyCode.TAB && 
            jQuery( this ).data( 'autocomplete' ).menu.active ) {
            event.preventDefault();
    }
    if( event.keyCode === jQuery.ui.keyCode.TAB ) {
        event.preventDefault();
    }})
    .autocomplete({
        minLength: 0,
        source: '<?=site_url();?>my-search/vendor1/',
        focus: function() {

            return false;
        },
        search: function(oEvent, oUi) { 
            var sValue = jQuery(oEvent.target).val();

        },
        select: function( event, ui ) {

            var terms = ui.item.value;
            this.value = ui.item.value;
            jQuery('#fld_supnme').val(terms);

            return false;
        }
    })
    .click(function() { 

        var terms = this.value.split('|');

        jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
    });

    function __do_makeid()
    {
        var text = '';
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for( var i=0; i < 7; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }
    function my_add_line_item() { 
        try {
            var rowCount = jQuery('#tbl_GLData tr').length;
            var mid = __do_makeid() + (rowCount + 1);
            var clonedRow = jQuery('#tbl_GLData tr:eq(' + (rowCount - 2) + ')').clone(); 
            jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id','macctid_' + mid);
            jQuery(clonedRow).find('input[type=hidden]').eq(1).attr('id','mid_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(0).attr('id','fld_acct' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(1).attr('id','fld_refno_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(2).attr('id','fld_refdte_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(3).attr('id','fld_debit' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(4).attr('id','fld_credit' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(5).attr('id','fld_costcntr' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(6).attr('id','fld_partic' + mid);

            jQuery('#tbl_GLData tr').eq(rowCount - 2).before(clonedRow);
            jQuery(clonedRow).css({'display':''});

            //jQuery('.form_datetime').datepicker({
            //    todayBtn: "linked",
            //    keyboardNavigation: false,
            //    forceParse: false,
            //    calendarWeeks: false,
            //    autoclose: true,
            //    format: 'mm/dd/yyyy'
            //});

            //jQuery(".form_datetime").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});

            jQuery('.to_number').keypress(function(evt) { 
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode != 46 && charCode > 31 
                    && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            });
            
            __my_item_lookup();
            __my_branch_lookup();
            __tamt_compute_totals();
            var xobjArtItem= jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
            jQuery('#' + xobjArtItem).focus();
            jQuery( '#tbl_GLData tr').each(function(i) { 
                jQuery(this).find('td').eq(0).html(i);
            });
        } catch(err) { 
            var mtxt = 'There was an error on this page.\\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\\nClick OK to continue.';
            alert(mtxt);
            return false;
        }  //end try 
    }

    function deleteRow(cobj,mruid) {
        jQuery(cobj).parent().parent().remove();
    }

    function __my_item_lookup() {  
        jQuery('.macctname' ) 
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
            if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                event.preventDefault();
            }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
            event.preventDefault();
        }})
        .autocomplete({
            minLength: 0,
            source: '<?= site_url(); ?>my-search/glparticularAcct_v/',
            focus: function() {
            // prevent value inserted on focus
            return false;
        },
        search: function(oEvent, oUi) { 
            var sValue = jQuery(oEvent.target).val();
        },
        select: function( event, ui ) {
            var terms = ui.item.value;

            jQuery(this).attr('alt', jQuery.trim(ui.item._glpacode));
            jQuery(this).attr('title', jQuery.trim(ui.item._glpacode));

            this.value = ui.item._glpadesc;

            var clonedRow = jQuery(this).parent().parent().clone();
            var indexRow = jQuery(this).parent().parent().index();

            var xobjglpid = jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id');

            jQuery('#' + xobjglpid).val(ui.item.mtkn_rid);

            return false;
        }})
        .click(function() { 
            var terms = this.value.split('=>');
            jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
        });        
    } 

    function __my_branch_lookup() {  
        jQuery('.mbranch' ) 
        .bind( 'keydown', function( event ) {
            if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
            event.preventDefault();
        }})

        .autocomplete({
            minLength: 0,
            source: '<?= site_url(); ?>mysearchdata/companybranch_v/',
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            search: function(oEvent, oUi) { 
                var sValue = jQuery(oEvent.target).val();
            },
            select: function( event, ui ) {
                var terms = ui.item.value;

                jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                jQuery(this).attr('title', jQuery.trim(ui.item.value));

                this.value = ui.item.value;

                var clonedRow = jQuery(this).parent().parent().clone();
                var indexRow = jQuery(this).parent().parent().index();
                var xobjbranchid = jQuery(clonedRow).find('input[type=hidden]').eq(2).attr('id');
                jQuery('#' + xobjbranchid).val(ui.item.mtkn_brnch);

                return false;
            }})
        .click(function() { 
            //jQuery(this).keydown(); 
            var terms = this.value.split('=>');
            jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
        });        
    }

    function __tamt_compute_totals() { 
        try { 
            var rowCount1 = jQuery('#tbl_GLData tr').length - 1;
            var adata1 = [];
            var adata2 = [];
            var mdata = '';
            var nudeb = 0;
            var nucred = 0;
            var ntcredit = 0.00;
            var ntdebit = 0.00;
            for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl_GLData tr:eq(' + aa + ')').clone(); 
                var mdat2 = jQuery(clonedRow).find('input[type=text]').eq(3).val();
                var mdat3 = jQuery(clonedRow).find('input[type=text]').eq(4).val();

                var xdebitId = jQuery(clonedRow).find('input[type=text]').eq(3).attr('id');
                var xdebitIdh = jQuery(clonedRow).find('input[type=hidden]').eq(3).attr('id');

                var xcreditId = jQuery(clonedRow).find('input[type=text]').eq(4).attr('id');
                var xcreditIdh = jQuery(clonedRow).find('input[type=hidden]').eq(4).attr('id');


                if(jQuery.trim(xdebitId) == '') { 
                    nudeb = 0;
                }
                else { 
                    nudeb = mdat2; //xdebitId;
                }

                if(jQuery.trim(xcreditId) == '') { 
                    nucred = 0;
                } else { 
                    nucred = mdat3; //xcreditId;
                }

                if(jQuery('#' + xdebitIdh).val()==''){
                    var ndebit = parseFloat(nudeb);
                }
                else{

                    var ndebit = parseFloat(nudeb);
                }

                if(jQuery('#' + xcreditIdh).val()==''){
                    var ncredit = parseFloat(nucred);
                }
                else{
                    var ncredit = parseFloat(nucred);
                }

                // //TOTAL AMT COST
                // if(!isNaN(ndebit) || ndebit > 0) { 
                //     $('#' + xdebitId).val(parseFloat(ndebit)); //__mysys_apps.oa_addCommas(ndebit.toFixed(2)));
                // }

                // //TOTAL QTY COST
                // if(!isNaN(ncredit) || ncredit > 0) { 
                //     $('#' + xcreditId).val(parseFloat(ncredit)); //__mysys_apps.oa_addCommas(ncredit.toFixed(2)));
                // }

                ntdebit = parseFloat(ntdebit + ndebit);
                ntcredit = parseFloat(ntcredit + ncredit);

            }  //end for 
            if (!isNaN(ntdebit) || ntdebit < 0){
                jQuery('#fld_subtdeb').val(__mysys_apps.oa_addCommas(ntdebit.toFixed(2)));
            }
            if (!isNaN(ntcredit) || ntcredit < 0){
                jQuery('#fld_subtcre').val(__mysys_apps.oa_addCommas(ntcredit.toFixed(2)));
            }

        } catch(err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            alert(mtxt);
        }  //end try

    } //__tamt_compute_totals

    __my_item_lookup();
    __tamt_compute_totals();
    __my_branch_lookup();
    my_add_line_item();

    (function () {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                //form.classList.add('was-validated') 

                try {
                    event.preventDefault();
                    event.stopPropagation();
                    jQuery('#memsggjv').modal('show');
                      
                } catch(err) {
                    var mtxt = 'There was an error on this page.\n';
                    mtxt += 'Error description: ' + err.message;
                    mtxt += '\nClick OK to continue.';
                    alert(mtxt);
                    //jQuery.hideLoading();
                    return false;
                }  //end try                    
            }, false)
        })
    })();

    jQuery('#memsggjv_yes').click(function() { 
        try {
            var myModal = new bootstrap.Modal(document.getElementById('mepreloaderme'), {
                    keyboard: false
                    });
            myModal.show();
            my_data = new FormData();
            my_data.append('fld_txtgltrx_no',jQuery('#txtgltrx_no').val());
            my_data.append('trxno_id',jQuery('#__hmtkn_trxnoid').val());
            my_data.append('fld_Company',jQuery('#fld_Company').val());
            my_data.append('fld_branch',jQuery('#_brnch').val());
            my_data.append('fld_supnme',jQuery('#fld_supnme').val());
            my_data.append('fld_gldate',jQuery('#fld_gldate').val());
            my_data.append('fld_enttyp',jQuery('#_entTyprid').val());
            my_data.append('fld_rems',jQuery('#fld_rems').val());

            my_data.append('fld_subtdeb',jQuery('#fld_subtdeb').val());
            my_data.append('fld_subtcre',jQuery('#fld_subtcre').val());

            var rowCount1 = jQuery('#tbl_GLData tr').length - 1;
            var adata1 = [];
            var adata2 = [];
            var mdata = '';
            var mdat ='';
            var sep = '';

            for(aa = 1; aa < rowCount1; aa++) { 
                var clonedRow = jQuery('#tbl_GLData tr:eq(' + aa + ')').clone();
                var fld_acct = jQuery(clonedRow).find('input[type=text]').eq(0).val();
                var fld_refno = jQuery(clonedRow).find('input[type=text]').eq(1).val();
                var fld_refdte = jQuery(clonedRow).find('input[type=text]').eq(2).val();
                var fld_debit = jQuery(clonedRow).find('input[type=text]').eq(3).val();
                var fld_credit = jQuery(clonedRow).find('input[type=text]').eq(4).val();
                var fld_partic = jQuery(clonedRow).find('input[type=text]').eq(5).val();
                var fld_cost_cntr_rid = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
                var fld_mndt_rid = jQuery(clonedRow).find('input[type=hidden]').eq(1).val();

                mdata = fld_acct + 'x|x' + fld_refno + 'x|x' + fld_refdte + 'x|x' + fld_debit + 'x|x' + fld_credit + 'x|x' + fld_partic + 'x|x' + fld_mndt_rid + 'x|x' + fld_cost_cntr_rid + 'sepsep' + sep;
                adata1.push(mdata);
                mdat = jQuery(clonedRow).find('input[type=hidden]').eq(0).val() + 'sepsep' + sep; //icode
                adata2.push(mdat);                        

            }
            my_data.append('adata1',adata1);
            my_data.append('adata2',adata2);

            var __fl_upld       = '__fl_upld';

            var __fl_upld    = jQuery('#'+__fl_upld);
            var filesCount   = 0;
            jQuery.each(__fl_upld, function(i,__fl_upld){
                if(__fl_upld.files.length > 0 ){
                    jQuery.each(__fl_upld.files, function(k,file){
                        my_data.append('__fl_upld[]', file);
                        filesCount++;
                    });
                }
            });

            if(filesCount == 0) {
                //alert('Please select attachment before save.');
                
                myModal.hide();
                jQuery('#memsggjv2_bod').html('Please select attachment before SAVE!!!');
                jQuery('#memsggjv2').modal('show');
                return false;
            }

            jQuery.ajax({ 
                type: "POST",
                url: '<?= site_url() ?>mytrx_gl/gl_sv',
                context: document.body,
                data: my_data,
                global: false,
                cache: false,
                processData: false,
                contentType: false,
                success: function(data)  {

                    myModal.hide();
                    jQuery('#myModSysMsgBod').html(data);
                    jQuery('#myModSysMsg').modal('show');

                    try { 
                        
                        var txtsearchedrec = '';
                        var mparam = {
                            txtsearchedrec: txtsearchedrec,
                            mpages: 1 
                        }; 
                        jQuery.ajax({ 
                            type: "POST",
                            url: '<?=site_url()?>mytrx_gl/mndt_invent_gl_recs',
                            context: document.body,
                            data: eval(mparam),
                            global: false,
                            cache: false,
                            success: function(data) {
                                myModal.hide();
                                jQuery('#mymodoutrecs').html(data);

                                return false;
                            },
                            error: function() {
                                myModal.hide();
                                alert('error loading page...');
                                return false;
                            } 
                        }); 

                    } catch(err) {
                        var mtxt = 'There was an error on this page.\n';
                        mtxt += 'Error description: ' + err.message;
                        mtxt += '\nClick OK to continue.';
                        alert(mtxt);
                        myModal.hide();
                        return false;

                    } 
                    return false;
                },
                error: function(data) {
                    alert('error loading page...');
                    return false;
                }
            });

            
        } catch(err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            alert(mtxt);
            //jQuery.hideLoading();
            return false;
        }  //end try                
    });


</script>