  
<?php 
/**
 *  File        : warehouse_inv_main.php
 *  Author      : Arnel Oquien
 *  Date Created: Dec. 02, 2022
 */
use App\Models\Mymelibsys_model;
$mymelibsys = new Mymelibsys_model();
$mylibzsys = model('App\Models\MyLibzSysModel');

?>
  <main id="main" class="main">
  <?php $active_warehouse_data = $mymelibsys->getCDActivePlantWarehouse();


  ?>
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <!-- Sales Card -->
            <div class="col">
              <div class="card info-card sales-card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Inbound <span>| Today</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="inbound-qty">Loading...</h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Sales Card -->
            <!-- Revenue Card -->
            <div class="col">
              <div class="card info-card sales-card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Outbound <span>| Today</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-basket3"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="outbound-qty">Loading...</h6>
                      <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Points Spent This Month Card -->
            <div class="col">
              <div class="card info-card customers-card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Shipdoc Trip <span>| Today</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="shipdoc-qty">Loading...</h6>
                      <!-- <span class="text-danger small pt-1 fw-bold">15%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Spent This Month Card -->
            <!-- Points Spent Last Month Card -->
            <div class="col">
              <div class="card info-card customers-card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Receive Del. <span>| Today</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-truck"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="rcvdel-qty">Loading...</h6>
                      <!-- <span class="text-danger small pt-1 fw-bold">5%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Spent Last Month Card -->
          </div> <!-- end row -->
        </div> <!-- end col-lg-12 -->
      </div> <!-- end row -->

      <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">
            <!--Filter -->
            <div class="col-12">
            <div class="card">
                <div class="card-body">
                      <h5 class="card-title">Plant & warehouse</span></h5>
                  <div class="row">
                  <div class="mt-2 col-lg-6 col-md-6 col-sm-12">
                    <label for="branch-name">Plant</label>
                    <div class="input-group"> 
                    <input type="text" id="txt-plant" name="txt-plant" class="form-control form-control-sm" value="<?=$active_warehouse_data['plant_code']?>" data-id="<?=$active_warehouse_data['mtkn_plant']?>" >
                    <div class="input-group-text px-1 py-0"> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                    </div>
                  </div>
                   <div class="mt-2 col-lg-6 col-md-6 col-sm-12">
                    <label for="branch-name">Warehouse</label>
                    <div class="input-group">
                    <input type="text" id="db-txt-warehouse" name="db-txt-warehouse" class="form-control form-control-sm" value="<?=$active_warehouse_data['wshe_code']?>"  data-id="<?=$active_warehouse_data['mtkn_whse']?>" >
                    <div class="input-group-text px-1 py-0"> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                    </div>
                  </div>
                  <div class="mt-2 col-lg-6 col-md-6 col-sm-12">
                    <label for="branch-name">From</label>
                    <div class="input-group"> 
                    <input type="date" id="db-date-from" name="db-date-from" class="form-control form-control-sm">
                    </div>
                  </div>
                  <div class="mt-2 col-lg-6 col-md-6 col-sm-12">
                    <label for="branch-name">To</label>
                    <div class="input-group"> 
                    <input type="date" id="db-date-to" name="db-date-to" class="form-control form-control-sm">
                    </div>
                  </div>
                  <div class="mt-2 col-4">
                    <button class="btn btn-dgreen btn-sm " id="btn-report"> <i class="bi bi-search"> </i></button>
                  </div>
                 </div>
                </div>
              </div>
            </div><!-- End Reports -->
            <!-- Reports -->
            <div class="col-12">
              <div class="card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Reports <span>/This Month</span></h5>
                  <!-- Line Chart -->
                  <div id="reportsChart"></div>

                  <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        get_reportData();
                        get_dbQty();
                    });
                  </script>
                  <!-- End Line Chart -->

                </div>
              </div>
            </div><!-- End Reports -->

            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Recent Sales <span>| Today</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#">#2457</a></th>
                        <td>Brandon Jacob</td>
                        <td><a href="#" class="text-primary">At praesentium minu</a></td>
                        <td>$64</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2147</a></th>
                        <td>Bridie Kessler</td>
                        <td><a href="#" class="text-primary">Blanditiis dolor omnis similique</a></td>
                        <td>$47</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2049</a></th>
                        <td>Ashleigh Langosh</td>
                        <td><a href="#" class="text-primary">At recusandae consectetur</a></td>
                        <td>$147</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Angus Grady</td>
                        <td><a href="#" class="text-primar">Ut voluptatem id earum et</a></td>
                        <td>$67</td>
                        <td><span class="badge bg-danger">Rejected</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Raheem Lehner</td>
                        <td><a href="#" class="text-primary">Sunt similique distinctio</a></td>
                        <td>$165</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

            <!-- Top Selling -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body pb-0">
                  <h5 class="card-title">Top Selling <span>| Today</span></h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Preview</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Sold</th>
                        <th scope="col">Revenue</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-1.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Ut inventore ipsa voluptas nulla</a></td>
                        <td>$64</td>
                        <td class="fw-bold">124</td>
                        <td>$5,828</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-2.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Exercitationem similique doloremque</a></td>
                        <td>$46</td>
                        <td class="fw-bold">98</td>
                        <td>$4,508</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-3.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Doloribus nisi exercitationem</a></td>
                        <td>$59</td>
                        <td class="fw-bold">74</td>
                        <td>$4,366</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-4.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Officiis quaerat sint rerum error</a></td>
                        <td>$32</td>
                        <td class="fw-bold">63</td>
                        <td>$2,016</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-5.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Sit unde debitis delectus repellendus</a></td>
                        <td>$79</td>
                        <td class="fw-bold">41</td>
                        <td>$3,239</td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Top Selling -->

            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Top Age Matrix</h5>
                  <!-- Line Chart -->
                  <div id="lineChart"></div>
                  <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    new ApexCharts(document.querySelector("#lineChart"), {
                      series: [{
                      name: "Age Bracket",
                        data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
                      }],
                      chart: {
                        height: 350,
                        type: 'line',
                        zoom: {
                          enabled: false
                        }
                      },
                      dataLabels: {
                        enabled: false
                      },
                      stroke: {
                        curve: 'straight'
                      },
                      grid: {
                        row: {
                          colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                          opacity: 0.5
                        },
                      },
                      xaxis: {
                        categories: ['20', '25', '30', '35', '40', '45', '50', '55', '60'],
                      }
                    }).render();
                  });
                  </script>
                  <!-- End Line Chart -->
                </div> <!-- end card-body -->
              </div>  <!-- end card -->
            </div> <!-- end col-12 age matrix -->


          </div> <!-- end row -->
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

          <!-- Recent Activity -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Recent Activity <span>| Today</span></h5>

              <div class="activity">

                <div class="activity-item d-flex">
                  <div class="activite-label">32 min</div>
                  <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                  <div class="activity-content">
                    Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">56 min</div>
                  <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                  <div class="activity-content">
                    Voluptatem blanditiis blanditiis eveniet
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2 hrs</div>
                  <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                  <div class="activity-content">
                    Voluptates corrupti molestias voluptatem
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">1 day</div>
                  <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                  <div class="activity-content">
                    Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati voluptatem</a> tempore
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2 days</div>
                  <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                  <div class="activity-content">
                    Est sit eum reiciendis exercitationem
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">4 weeks</div>
                  <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                  <div class="activity-content">
                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                  </div>
                </div><!-- End activity item-->

              </div>

            </div>
          </div><!-- End Recent Activity -->

          <!-- Budget Report -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Card Top Geolocation</h5>
              <!-- Bar Chart -->
              <canvas id="barChart" style="max-height: 400px;"></canvas>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#barChart'), {
                    type: 'bar',
                    data: {
                      labels: ['NCR', 'Bulacan', 'Laguna', 'Cavite', 'Rizal', 'Quezon', 'Pangasinan'],
                      datasets: [{
                        label: 'Bar Chart',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        backgroundColor: [
                          'rgba(255, 99, 132, 0.2)',
                          'rgba(255, 159, 64, 0.2)',
                          'rgba(255, 205, 86, 0.2)',
                          'rgba(75, 192, 192, 0.2)',
                          'rgba(54, 162, 235, 0.2)',
                          'rgba(153, 102, 255, 0.2)',
                          'rgba(201, 203, 207, 0.2)'
                        ],
                        borderColor: [
                          'rgb(255, 99, 132)',
                          'rgb(255, 159, 64)',
                          'rgb(255, 205, 86)',
                          'rgb(75, 192, 192)',
                          'rgb(54, 162, 235)',
                          'rgb(153, 102, 255)',
                          'rgb(201, 203, 207)'
                        ],
                        borderWidth: 1
                      }]
                    },
                    options: {
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      }
                    }
                  });
                });
              </script>
              <!-- End Bar CHart -->
            </div>
          </div><!-- End Budget Report -->

          <!-- Website Traffic -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Cards Stats <span>| Today</span></h5>

              <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      top: '5%',
                      left: 'center'
                    },
                    series: [{
                      name: 'Access From',
                      type: 'pie',
                      radius: ['40%', '70%'],
                      avoidLabelOverlap: false,
                      label: {
                        show: false,
                        position: 'center'
                      },
                      emphasis: {
                        label: {
                          show: true,
                          fontSize: '18',
                          fontWeight: 'bold'
                        }
                      },
                      labelLine: {
                        show: false
                      },
                      data: [{
                          value: 1048,
                          name: 'Remaining'
                        },
                        {
                          value: 735,
                          name: 'Allocated'
                        },
                        {
                          value: 580,
                          name: 'Renewal'
                        },
                        {
                          value: 484,
                          name: 'Replace'
                        },
                        {
                          value: 300,
                          name: 'Lost'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Website Traffic -->

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Customer Visit</h5>

              <!-- Pie Chart -->
              <div id="pieChart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new ApexCharts(document.querySelector("#pieChart"), {
                    series: [40, 60],
                    chart: {
                      height: 350,
                      type: 'pie',
                      toolbar: {
                        show: true
                      }
                    },
                    labels: ['New Customer Visit', 'Old Customer Visit']
                  }).render();
                });
              </script>
              <!-- End Pie Chart -->
            </div>
          </div>


          <!-- News & Updates Traffic -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

              <div class="news">
                <div class="post-item clearfix">
                  <img src="assets/img/news-1.jpg" alt="">
                  <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
                  <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-2.jpg" alt="">
                  <h4><a href="#">Quidem autem et impedit</a></h4>
                  <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-3.jpg" alt="">
                  <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
                  <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-4.jpg" alt="">
                  <h4><a href="#">Laborum corporis quo dara net para</a></h4>
                  <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-5.jpg" alt="">
                  <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
                  <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos eius...</p>
                </div>

              </div><!-- End sidebar recent posts-->

            </div>
          </div><!-- End News & Updates -->

        </div><!-- End Right side columns -->

      </div>
    </section>

    <?php
      echo $mylibzsys->memypreloader01('mepreloaderme');
      echo $mylibzsys->memsgbox1('mmsgmodal_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    ?>  
  </main><!-- End #main -->

  <script type="text/javascript">
    let chartReport = '';
        function get_dbQty(){
      __mysys_apps.mepreloader('mepreloaderme',true);
      
      $.ajax({ // default declaration of ajax parameters
        type: "GET",
        url: '<?=site_url()?>dashboard-qty',
        context: document.body,
        data: '',
        global: false,
        cache: false,
        success: function(data)  { //display html using divID
         __mysys_apps.mepreloader('mepreloaderme',false);
         
        $('#rcvdel-qty').html(data.rcvdel_qty);
        $('#shipdoc-qty').html(data.shipdoc_qty);
        $('#outbound-qty').html(data.outbound_qty);
        $('#inbound-qty').html(data.inbound_qty);

        return false;
      },
      error: function() { // display global error on the menu function
        alert('error loading page...');
        __mysys_apps.mepreloader('mepreloaderme',false);
        return false;
      } 
      });

    }
    function get_reportData(){
      __mysys_apps.mepreloader('mepreloaderme',true);
      let mparam = {
          mtkn_whse:$('#db-txt-warehouse').attr("data-id"),
          db_from: $('#db-date-from').val(),
          db_to:$('#db-date-to').val()
      }
      
      $.ajax({ // default declaration of ajax parameters
        type: "POST",
        url: '<?=site_url()?>report',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { //display html using divID
         __mysys_apps.mepreloader('mepreloaderme',false);
         
   
         var options = {
              series: [{
                name: 'Inbound',
                data: data.inbound,
            }, {
               name: 'Outbound',
               data: data.outbound
            }],
              chart: {
              height: 350,
              type: 'area',
              toolbar: {
              show: false
              },
            },
            fill: {
              type: "gradient",
              gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.3,
                opacityTo: 0.4,
                stops: [0, 90, 100]
              }
            },
              markers: {
                size: 4
              },
            dataLabels: {
              enabled: false
            },
            stroke: {
              curve: 'smooth',
              width: 2
            },
            xaxis: {
              type: 'date',
              categories: data.dates
            },
            tooltip: {
              x: {
                format: 'dd/MM/yy HH:mm'
              },
            },
            };

            chartReport = new ApexCharts(document.querySelector("#reportsChart"), options);
            chartReport.render();

        return false;
      },
      error: function() { // display global error on the menu function
        alert('error loading page...');
        __mysys_apps.mepreloader('mepreloaderme',false);
        return false;
      } 
      });

    }


    //report
    $('#btn-report').on('click',function(){
      __mysys_apps.mepreloader('mepreloaderme',true);

      let mparam = {
          mtkn_whse: $('#db-txt-warehouse').attr("data-id"),
          db_from: $('#db-date-from').val(),
          db_to: $('#db-date-to').val()
      }
      var days = __mysys_apps.daysdifference($('#db-date-from').val(), $('#db-date-to').val());  

      if(days > 30){
        jQuery('#mmsgmodal_danger_bod').html('Date range limit to 30 days only');
        jQuery('#mmsgmodal_danger').modal('show');
        __mysys_apps.mepreloader('mepreloaderme',false);
        return false;
      }

      $.ajax({ // default declaration of ajax parameters
        type: "POST",
        url: '<?=site_url()?>report',
        context: document.body,
        data: eval(mparam),
        global: false,
        cache: false,
        success: function(data)  { //display html using divID
         __mysys_apps.mepreloader('mepreloaderme',false);
          chartReport.updateOptions({
          series: [{
                name: 'Inbound',
                data: data.inbound,
            }, {
               name: 'Outbound',
               data: data.outbound
            }],
            xaxis: {
              type: 'date',
              categories: data.dates
            }

          })

        return false;
      },
      error: function() { // display global error on the menu function
        alert('error loading page...');
        __mysys_apps.mepreloader('mepreloaderme',false);
        return false;
      } 
      });
    });
    jQuery('#txt-plant' ) 
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
            if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
            event.preventDefault();
        }
        // if( event.keyCode === jQuery.ui.keyCode.BACKSPACE) {
        //             return false;
        // }
        var regex = new RegExp("^[a-zA-Z0-9\b]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
        })
        .autocomplete({
            minLength: 0,
            source: '<?=site_url();?>get-plant-list/',
            focus: function() {
        // prevent value inserted on focus
        return false;
        },
        search: function(oEvent, oUi) { 
            var sValue = jQuery(oEvent.target).val();
        //jQuery(oEvent.target).val('&mcocd=1' + sValue);
        //alert(sValue);
        },
        select: function( event, ui ) {

            var terms = ui.item.value;
            jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
            jQuery('#' + this.id).attr('title', jQuery.trim(terms));
            var plant_id = ui.item.mtkn_rid;
            jQuery('#txt-plant').attr("data-id",plant_id);
            //vw_brnchname(ui.item.mtkn_rid);
            this.value = ui.item.value;
            
            // null warehouse 
            jQuery('#db-txt-warehouse').attr('alt', '');
            jQuery('#db-txt-warehouse').attr('title', '');
            jQuery('#db-txt-warehouse').attr('data-id', '');
            jQuery('#db-txt-warehouse').val('');


           return false;
        }
        })
        .click(function() { 
        //jQuery(this).keydown(); 
        var terms = this.value.split('|');
        //jQuery(this).autocomplete('search', '');
        jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
        });
        // *****//end plant

    jQuery('#db-txt-warehouse' ) 
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keypress', function( event ) {
            if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
                event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
            event.preventDefault();
        }

        var regex = new RegExp("^[a-zA-Z0-9\b]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
        })
       
        .autocomplete({
            minLength: 0,
            source: '<?=site_url();?>get-cdwarehouse-list/',
            focus: function() {
        // prevent value inserted on focus
        return false;
        },
        search: function(oEvent, oUi) { 
            var sValue = jQuery(oEvent.target).val();
            var plant = jQuery('#txt-plant').attr("data-id");
            jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-cdwarehouse-list/?mtkn_plnt=' + plant); 
        },
        select: function( event, ui ) {

            var terms = ui.item.value;
            jQuery('#' + this.id).attr('alt', jQuery.trim(terms));
            jQuery('#' + this.id).attr('title', jQuery.trim(terms));
            jQuery(this).attr('data-id', jQuery.trim(ui.item.mtkn_rid));
            var wshe_id = ui.item.mtkn_rid;
            jQuery('#db-txt-warehouse').attr("data-id",wshe_id);

  
            this.value = ui.item.value; 

           return false;
        }
        })
        .click(function() { 
        //jQuery(this).keydown(); 
        var terms = this.value.split('|');
        //jQuery(this).autocomplete('search', '');
        jQuery(this).autocomplete('search', jQuery.trim(terms[0]));
        });

  </script>