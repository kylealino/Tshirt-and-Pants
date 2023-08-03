<form>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="row mb-3">
                <div class="col-sm-4">
                    <span class="fs-6 fw-bold">Email</span>
                </div>
                <div class="col-sm-8">
                    <input type="email" class="form-control form-control-sm meinput-sm-pad" id="inputEmail3">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4">
                    <span class="fs-6 fw-bold">Company</span>
                </div>
                <div class="col-sm-8">
                    <input type="text" class="form-control form-control-sm meinput-sm-pad" id="fld_Company" name="fld_Company">
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="row mb-3">
                <div class="col-sm-4">
                    <span>Date Transaction:</span>
                </div>
                <div class="col-sm-8">
                    <input type="email" class="form-control form-control-sm" id="inputEmail3a">
                </div>
            </div>
        </div>
    </div>
</form>

<section class="container">
  <h2 class="py-2">Datepicker in Bootstrap 5</h2>
  <form class="row">
    <div class="col-md-6">
        <div class="row mb-2">
            <label for="date" class="col-md-2 col-form-label">Date</label>
            <div class="col-md-8">
                <div class="input-group date" id="datepicker">
                    <input type="text" class="form-control form-control-sm meinput-sm-pad" id="memedate"/>
                    <span class="input-group-append">
                        <span class="input-group-text bg-light d-block iamdate">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <label for="date" class="col-1 col-form-label">Date</label>
        <div class="col-auto">
        <div class="input-group date datepicker" id="datepicker">
            <input type="text" class="form-control" id="date"/>
            <span class="input-group-append">
            <span class="input-group-text bg-light d-block">
                <i class="fa fa-calendar"></i>
            </span>
            </span>
        </div>
        </div>
    </div>

  </form>
</section>

<script>

//jQuery('.datepicker').datepicker();
//jQuery('#memedate').datepicker({
//                    format: 'mm/dd/yyyy',
//                    autoclose: true
//                });
//jQuery('.iamdate').click(function() {
//    jQuery('#memedate').datepicker('show');
//});

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

</script>