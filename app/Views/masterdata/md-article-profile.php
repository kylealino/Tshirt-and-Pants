<?php
/**
 *	File        : masterdata/md-article-profile.php
 *  Auhtor      : Oliver V. Sta Maria
 *  Date Created: Apr 08, 2022
 * 	last update : Apr 08, 2022
 * 	description : Migrate into new UI
 */
 
$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

?>

<div class="row">
    <div class="col-12 col-xl-4 mt-1">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Article Info</h6>
            </div>
            <div class="card-body p-3">
                <label>Article Code</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
                <label>Article Description</label>
                <div class="mb-3">
                    <input type="password" name="mepassword" class="form-control form-control-sm" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                </div>
                <label>Barcode</label>
                <div class="mb-3">
                    <input type="password" name="mepassword" class="form-control form-control-sm" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                </div>
           
              <ul class="list-group">
                <li class="list-group-item border-0 px-0">
                  <div class="form-check form-switch ps-0">
                    <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" checked>
                    <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault">Acive</label>
                  </div>
                </li>
              </ul>
            </div> <!-- end card body -->
        </div> <!-- end article info -->
    </div> <!-- end col-12 -->
    <div class="col-12 col-xl-4 mt-1">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Article Hierarchy</h6>
            </div>
            <div class="card-body p-3">
                <label>Product Line</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
                <label>Product Type</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
                <label>Section</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
                <label>Product Class</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
                <label>Product SubClass</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>

            </div> <!-- end card-body -->
        </div>
    </div> <!-- end article Hierarchy -->

    <div class="col-12 col-xl-4 mt-1">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Pricing/Cost</h6>
            </div>
            <div class="card-body p-3">
                <label>Unit Cost</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
                <label>SRP</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
        <div class="card mt-1">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Stocking</h6>
            </div>
            <div class="card-body p-3">
                <label>Packaging</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
                <label>UoM</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
                <label>Gross Weight</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
                <label>Conversion Factor</label>
                <div class="mb-3">
                    <input type="text" name="meusername" class="form-control form-control-sm" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end article Pricing/Cost -->

</div>