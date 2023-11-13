<?php
$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytrxfgpack = model('App\Models\MyFGPackingModel');
$mydataz = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(0);

?>
<main id="main">


    input first name:
    <input type="text" name="fname" id="fname">
    input last name
    <input type="text" name="lname" id="lname">
    <button id="mbtn_mn_Save" type="submit" class="btn btn-dgreen btn-sm">Save</button>
  <?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>  
</main>    

<script type="text/javascript">

  $("#mbtn_mn_Save").click(function(e){
    try { 
          //__mysys_apps.mepreloader('mepreloaderme',true);
          var fname = jQuery('#fname').val();
          var lname = jQuery('#lname').val();

          var mparam = {
            fname:fname,
            lname:lname
          };  


      $.ajax({ 
        type: "POST",
        url: '<?=site_url();?>alex-save',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { 
            $(this).prop('disabled', false);
           // $.hideLoading();
            jQuery('#memsgtestent_bod').html(data);
            jQuery('#memsgtestent').modal('show');
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
    return false; 
  });
   
</script>