<?php
$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');
$cuser   = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
?>

<br>
<div class="box box-primary">
  <div class="box-body">
    <br>
    <div class="row">
      <div class="col-md-3 mb-2">
        <div class="form-group">
          <label>FROM RACK</label>
          <input type="text" name="txt-ftransfer-rack-upload" id="txt-ftransfer-rack-upload" class="form-control form-control-sm frack_lookup" placeholder="FROM RACK" required="required" onkeydown="" data-id=""/>
        </div>
      </div>
      <div class="col-md-3 mb-2">
        <div class="form-group">
          <label>FROM BIN</label>
          <input type="text" id="txt-ftransfer-bin-upload" name="txt-ftransfer-bin-upload" class="form-control form-control-sm fbin_lookup" placeholder="FROM BIN" required="required" data-type="TU" value="" data-id=""/>
        </div>
      </div>
      <div class="col-md-3 mb-2">
        <div class="form-group">
          <label>TARGET RACK</label>
          <input type="text" name="txt-ttransfer-rack-upload" id="txt-ttransfer-rack-upload" class="form-control form-control-sm frack_lookup" placeholder="TARGET RACK" required="required" onkeydown="" value="" data-id=""/>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="form-group">
          <label>TARGET BIN</label>
          <input type="text" id="txt-ttransfer-bin-upload" name="txt-ttransfer-bin-upload" class="form-control form-control-sm fbin_lookup" placeholder="TARGET BIN" data-type="TTU" required="required" value="" data-id=""/>
        </div>
      </div>
      <div class="col-md-3 mb-2">
        <div class="form-group">
        <div class="input-group">
          <input type="file" class="form-control form-control-sm" id="txt-file-upload-transfer-rack">
          <button type="button" class="btn btn-dgreen btn-sm" id="btn-upload-transfer-rack">Upload</button>
        </div>
      </div>
      </div>
    </div>
    <hr class="prettyline">

        <div id="transfer-rack-content">
        </div>

    
    <!-- INSERT TABLE HERE -->
  </div>

<script type="text/javascript">

frack_lookup();
fbin_lookup();

  $('#btn-upload-transfer-rack').click(function() { 
    try {   
        // $.showLoading({name: 'line-pulse', allowHide: false });
        var mwshe_id = jQuery('#txt-warehouse').attr("data-id")
        my_data = new FormData();
        my_data.append('from_rack', $('#txt-ftransfer-rack-upload').attr("data-id"));
        my_data.append('from_bin', $('#txt-ftransfer-bin-upload').attr("data-id"));
        my_data.append('to_rack', $('#txt-ttransfer-rack-upload').attr("data-id"));
        my_data.append('to_bin', $('#txt-ttransfer-bin-upload').attr("data-id"));
        my_data.append('sfrom_rack', $('#txt-ftransfer-rack-upload').val());
        my_data.append('sfrom_bin', $('#txt-ftransfer-bin-upload').val());
        my_data.append('sto_rack', $('#txt-ttransfer-rack-upload').val());
        my_data.append('sto_bin', $('#txt-ttransfer-bin-upload').val());
        my_data.append('mtkn_wshe', mwshe_id);
        my_data.append('transfer_rack_file', $('#txt-file-upload-transfer-rack')[0].files[0]);
        $("#btn-upload-transfer-rack").prop('disabled', true);
        $.ajax({ // default declaration of ajax parameters
            url: '<?=site_url()?>warehouse-inv-rackbintrans-upload-recs',
            method:"POST",
            context:document.body,
            data: my_data,
            contentType: false,
            global: false,
            cache: false,
            processData:false,
            beforeSend: function(){
              $("#btn-upload-transfer-rack").prop('disabled', true);
            },
            success: function(response) { //display html using divID 
              // $.hideLoading();
              if(response){
                $("#transfer-rack-content").html(response); 
              }
              $("#btn-upload-transfer-rack").prop('disabled', false);
            
            return false;
            },
            error: function() { // display global error on the menu function
              alert('error loading page...');
              // $.hideLoading();
              return false;
            }   
        }); 
    } catch (err) {
       var mtxt = 'There was an error on this page.\n';
       mtxt += 'Error description: ' + err.message;
       mtxt += '\nClick OK to continue.';
       // $.hideLoading();
       alert(mtxt);
    } //end try
  });


  $('#btn-download-temp-rack').click(function() { 
        try {   
            window.location.href = '<?=site_url();?>downloads/form_templates/warehouse-transfer-rack-upload-template.xls';
        } catch (err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            // $.hideLoading();
            alert(mtxt);
        } //end try
    }); 

</script>
