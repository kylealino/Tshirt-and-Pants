<?php

$mymdarticle = model('App\Models\MyMDArticleModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
//var_dump($mymdarticle);

?>

<main id="main">

    <div class="pagetitle">
        <h1>Sales</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url();?>">Sales</a></li>
                <li class="breadcrumb-item active">Sales Out Details</li>
            </ol>
            </nav>
    </div><!-- End Page Title -->

    <div class="row mb-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
            <div class="card">
                <ul class="nav nav-tabs nav-tabs-bordered" id="myTabArticle" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="sales-daily-itemized-tab" data-bs-toggle="tab" data-bs-target="#sales-daily-itemized" type="button" role="tab" aria-controls="sales-daily-itemized" aria-selected="true">Daily Itemized</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#artcprofile" type="button" role="tab" aria-controls="artcprofile" aria-selected="false">Monthly Itemized</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabArticleContent">
                    <div class="tab-pane fade show active" id="sales-daily-itemized" role="tabpanel" aria-labelledby="sales-daily-itemized-tab">
                    </div>
                    <div class="tab-pane fade" id="artcprofile" role="tabpanel" aria-labelledby="profile-tab">

                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                </div>
            </div>
        </div>
    </div>

    <?php
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgsalesoutdetl','System Alert','...');
    ?>

</div>  <!-- end main -->

<script type="text/javascript"> 
    //mywg_art_profile_load('');
    mywg_salesout_scr_load();
    function mywg_art_profile_load(mtkn_etr) {
        var ajaxRequest;
        
        ajaxRequest = jQuery.ajax({
                url: "<?=site_url();?>md-article/profile",
                type: "post",
                data: { mtkn_etr: mtkn_etr}
            });

        // Deal with the results of the above ajax call
        ajaxRequest.done(function (response, textStatus, jqXHR) {
            jQuery('#artcprofile').html(response);

            // and do it again
            //setTimeout(get_if_stats, 5000);
        });
    } 

    function mywg_salesout_scr_load(mtkn_etr) {
        var ajaxRequest;
        
        ajaxRequest = jQuery.ajax({
                url: "<?=site_url();?>sales-out-details-tab-daily",
                type: "post",
                data: { mtkn_etr: mtkn_etr}
            });

        // Deal with the results of the above ajax call
        ajaxRequest.done(function (response, textStatus, jqXHR) {
            jQuery('#sales-daily-itemized').html(response);

            // and do it again
            //setTimeout(get_if_stats, 5000);
        });
    }

    __mysys_apps.mepreloader('mepreloaderme',false);
</script>