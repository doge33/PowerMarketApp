@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
<div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
  <div class="container">
    <div class="header-body text-center mb-7">
      <!-- <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-6 col-md-8 px-5">
      <h1 class="text-white">{{ __('Detailed Report') }}</h1>
    </div>
  </div> -->
</div>
</div>
<div class="separator separator-bottom separator-skew zindex-100">
  <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
  </svg>
</div>
</div>
<!-- Page content -->
<div class="cluster-container mt--8 pb-5">
  <div class="cluster-container mt--8 pb-5">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="pricing card-group mb-3">
          <div class="card card-pricing border-0 mb-4">
            <div class="card-header bg-transparent">
              <h4 class="text-uppercase ls-1 text-primary text-center py-3 mb-0">Report for Project:</h4>
              <h2 class="text-uppercase ls-1 text-primary text-center py-3 mb-0">{{ $project ?? ''}}</h2>
            </div>
            <div class="card-body px-lg-10">
              <div class="row justify-content-center">
                <img class="pb-6 pt-4 col-7 col-sm-5 col-md-4 col-lg-3 col-xl-2" src="{{ asset('argon') }}/img/icons/common/powermarket.png" style="width: 100%; height: auto;" class="" alt="...">
              </div>
              <div class="row">
                <div class="col-xl-3 col-md-6">
                  <div class="card card-stats">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-muted mb-0">Total Size of System</h5>
                          <span class="h2 font-weight-bold mb-0" id="co2-card">{{ isset($size) ? number_format($size) : '' }} kWp</span>
                        </div>
                        <div class="col-auto">
                          <div class="row">
                            <div class="icon icon-shape bg-gradient-pm text-white rounded-circle shadow">
                              <i class="fas fa-bolt"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6">
                  <div class="card card-stats">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-muted mb-0">Total System Cost</h5>
                          <span class="h2 font-weight-bold mb-0" id="savings-card">&#163;{{ isset($cost) ? number_format($cost) : ''}}</span>
                        </div>
                        <div class="col-auto">
                          <div class="row">
                            <div class="icon icon-shape bg-gradient-pm text-white rounded-circle shadow">
                              <i class="fas fa-pound-sign"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6">
                  <div class="card card-stats">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-muted mb-0">Total Lifetime Savings</h5>
                          <span class="h2 font-weight-bold mb-0">&#163;{{ isset($savings) ? number_format($savings) : ''}}</span>
                        </div>
                        <div class="col-auto">
                          <div class="row">
                            <div class="icon icon-shape bg-gradient-pm text-white rounded-circle shadow">
                              <i class="fas fa-piggy-bank"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6">
                  <div class="card card-stats">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-muted mb-0">Average Breakeven Time</h5>
                          <span class="h2 font-weight-bold mb-0" id="potential-card">{{ number_format($breakeven, 2) ?? ''}} years</span>
                        </div>
                        <div class="col-auto">
                          <div class="row">
                            <div class="icon icon-shape bg-gradient-pm text-white rounded-circle shadow">
                              <i class="far fa-calendar-check"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-sm-12">
                  <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                      <!-- Title -->
                      <h5 class="h3 mb-0">Monthly Savings (£)</h5>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                      <div class="chart">
                        <!-- Chart wrapper -->
                        <canvas id="chart-bar-savings"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                  <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                      <!-- Title -->
                      <h5 class="h3 mb-0 net">Net Annual CO<sub>2</sub> Savings
                        <img src="{{ asset('svg') }}/info.svg" data-toggle="tooltip" title="After taking CO2 emissions from solar manufacturing into account." />
                      </h5>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                      <div class="chart">
                        <!-- Chart wrapper -->
                        <canvas id="chart-report" class="chart-canvas"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 col-sm-12">
                  <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                      <!-- Title -->
                      <h5 class="h3 mb-0">Monthly Generation (kWh)</h5>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                      <div class="chart">
                        <!-- Chart wrapper -->
                        <canvas id="chart-line-gen"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                  <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                      <!-- Title -->
                      <h5 class="h3 mb-0 net">Net Annual Generation (kWh)
                        <img src="{{ asset('svg') }}/info.svg" data-toggle="tooltip" title="After taking CO2 emissions from solar manufacturing into account." />
                      </h5>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                      <div class="chart">
                        <!-- Chart wrapper -->
                        <canvas id="chart-report-generation" class="chart-canvas"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 col-12">
                  <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                      <!-- Title -->
                      <h5 class="h3 mb-0">Environmental Impact</h5>
                    </div>
                    <!-- Card body -->
                    <div class="card-body" style="height:500px;">
                      <div class="row h-25 align-items-center">
                        <div class="col-md-2 col-sm-2 col-4">
                          <img style="width: 2rem;" class="mx-auto" src="{{ asset('argon') }}/img/icons/common/factory.svg" />
                        </div>
                        <div class="col-md-7 col-sm-7 col-8 pl-0">
                          <b class="h5">Tonnes of carbon eliminated per year</b>
                        </div>
                        <div class="col-md-3 col-sm-3 col-12 text-right">
                          <strong>{{ isset($tons) ? number_format($tons, 2) : '' }}</strong>
                        </div>
                      </div>
                      <div class="row h-25 align-items-center">
                        <div class="col-md-2 col-sm-2 col-4">
                          <img style="width: 2rem;" class="mx-auto" src="{{ asset('argon') }}/img/icons/common/road.svg" />
                        </div>
                        <div class="col-md-7 col-sm-7 col-8 pl-0">
                          <b class="h5">Cars taken off the road per year</b>
                        </div>
                        <div class="col-md-3 col-sm-3 col-12 text-right">
                          <strong>{{ isset($cars) ? number_format($cars, 2) : '' }}</strong>
                        </div>
                      </div>
                      <div class="row h-25 align-items-center">
                        <div class="col-md-2 col-sm-2 col-4">
                          <img style="width: 2rem;" class="mx-auto" src="{{ asset('argon') }}/img/icons/common/tree.svg" />
                        </div>
                        <div class="col-md-7 col-sm-7 col-8 pl-0">
                          <b class="h5">Equivalent of new trees planted</b>
                        </div>
                        <div class="col-md-3 col-sm-3 col-12 text-right">
                          <strong>{{ isset($trees)  ? number_format($trees) : ''}}</strong>
                        </div>
                      </div>
                      <div class="row h-25 align-items-center">
                        <div class="col-md-2 col-sm-2 col-4">
                          <img style="width: 2rem;" class="mx-auto" src="{{ asset('argon') }}/img/icons/common/gas.svg" />
                        </div>
                        <div class="col-md-7 col-sm-7 col-8 pl-0">
                          <b class="h5">Litres of petrol/gas saved</b>
                        </div>
                        <div class="col-md-3 col-sm-3 col-12 text-right">
                          <strong>{{ isset($oil) ? number_format($oil) : ''}}</strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-12">
                  <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                      <!-- Title -->
                      <h5 class="h3 mb-0">Location Details</h5>
                    </div>
                    <!-- Card body -->
                    <div class="card-body" style="height:500px;">
                      <div id="map" style="width: 100%; height: 450px;" class="map-border"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="mb-0">Location Data</h3>
                      <p class="text-sm mb-0">
                      </p>
                    </div>
                    <!-- <div class="showing">
                    <h5>Showing <b id="count"></b> selected rooftops</h5>
                  </div> -->
                  <div class="table-responsive" style="padding-top:10px;">
                    <table class="table" id="datatable-report">
                      <thead class="thead-light">
                        <tr>
                          <th>Id</th>
                          <th>System Size (kWp)</th>
                          <th>System Cost (£)</th>
                          <th>Annual Generation (kWh)</th>
                          <th>Annual Savings (£)</th>
                          <th>Lifetime Savings (£) </th>
                          <th>Breakeven (Years)</th>
                          <th>ROI (%)</th>
                          <th>Annual CO<sub>2</sub> Savings (kgs)</th>
                          <th>Lifetime CO<sub>2</sub> Savings (kgs) </th>
                          <th>Address</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <div class="card-body">
                    <h5>Disclaimer: The data presented are estimations based on standard, industry-wide assumption; but can differ from actual solar array for the rooftops displayed. Please consult a professional solar installations company for a cutomised proposal.</h5>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-center">
              <a href="{{ route('page.cluster_pdf', $project ?? '') }}"><button type="button" class="btn btn-primary mb-3">Download Report</button></a>
            </div>
          </div>
        </div>
        <!-- <div class="card-footer">
        <a href="mailto: abhinav.jain@powermarket.net" target="_blank" class="text-light">Upgrade to generate your own</a>
      </div> -->
    </div>

  </div>
</div>
</div>


</div>
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
<link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />
<link rel="stylesheet" href="{{ asset('css') }}/report.css" />

@endpush
@push('js')
<script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
<script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
<script src="{{ asset('argon') }}/vendor/list.js/dist/list.min.js"></script>
<script src="{{ asset('js') }}/numeral.min.js"></script>
<script>
var monthly_savings = JSON.parse('{!! $monthly_savings ?? '
' !!}');
var monthly_exports = JSON.parse('{!! $monthly_exports ?? '
' !!}');
var saved_co2 = JSON.parse('{!! $saved_co2 ?? '
' !!} ');
var monthly_gen_captive = JSON.parse('{!! $monthly_gen_captive ?? '
' !!}');
var monthly_gen_exports = JSON.parse('{!! $monthly_gen_exports ?? '
' !!}');
var yearly_gen_captive = JSON.parse('{!! $yearly_gen_captive ?? '
' !!}');
var yearly_gen_exports = JSON.parse('{!! $yearly_gen_exports ?? '
' !!}');
console.log("m-gen-cap: ", monthly_gen_captive, "m-gen-exp: ", monthly_gen_exports, "y-gen-cap: ", yearly_gen_captive, 'yearly_gen_exports: ', yearly_gen_exports, "saved_co2: ", saved_co2  );

var jsonString = `{!! $geodata ?? '
' !!}`;
var map;
var dataArray = JSON.parse(jsonString);

function renderTable() {
  var layerPrefix = 'layer-years-';
  var table = $('#datatable-report').DataTable({
    // paging: false,
    // searching: false,
    // ordering: false,
    info: false,
    language: {
      paginate: {
        previous: "<i class='fas fa-angle-left'>",
        next: "<i class='fas fa-angle-right'>"
      }
    },
  });
  table.clear();
  table.select.info(false);
  table.select.style('single');
  table.buttons().container().appendTo($('.dataTables_length:eq(0)', table.table().container()));
  $('.dt-buttons .btn').removeClass('btn-secondary').addClass('btn-sm btn-default');

  if (jsonString.length > 0) {
    $('#count').append(dataArray.length);
    for (key = 0; key < dataArray.length; key++) {
      $('#datatable-report').dataTable().fnAddData([
        dataArray[key].id,
        numeral(dataArray[key].system_capacity_kWp).format('0,0.0a'),
        numeral(dataArray[key].system_cost_GBP).format('0,0.0a'),
        numeral(dataArray[key].annual_gen_kWh).format('0,0.0a'),
        numeral(dataArray[key].annual_gen_GBP).format('0,0.0a'),
        numeral(dataArray[key].lifetime_gen_GBP).format('0,0.0a'),
        dataArray[key].breakeven_years,
        numeral(dataArray[key].lifetime_return_on_investment_percent).format('0,0.0a'),
        numeral(dataArray[key].annual_co2_saved_kg).format('0,0.0a'),
        numeral(dataArray[key].lifetime_co2_saved_kg).format('0,0.0a'),
        dataArray[key].address
      ]);
    }
  }
  table.on('select', function(e, dt, type, indexes) {
    if (type === 'row') {
      var data = table.rows(indexes).data();
      var id = data[0][0];
      map.setFilter('places', ['==', 'id', id]);
    }
  });
  table.on('deselect', function(e, dt, type, indexes) {
    if (type === 'row') {
      map.setFilter('places', null);
    }
  });
  var column = table.column(0)
  column.visible(false)
}

// function renderBarChart_sav() {
//
//     var $chart_sav = $('#chart-bar-savings');
//
//     function init($this) {
//
//         var data = {
//             labels: ['January', 'February', 'March', 'April',
//                 'May', 'June', 'July', 'August',
//                 'September', 'October', 'November', 'December'
//             ],
//             datasets: [{
//                 label: 'Savings',
//                 backgroundColor: '#6074DD',
//                 data: monthly_savings,
//                 borderWidth: 0
//             }, {
//                 label: 'Export',
//                 backgroundColor: '#1B2B4B',
//                 data: monthly_exports,
//                 borderWidth: 0
//             }]
//         };
//
//         var options = {
//             scales: {
//                 xAxes: [{
//                     stacked: true,
//                     ticks: {
//                         autoSkip: false
//                     }
//                 }],
//                 yAxes: [{
//                     stacked: true
//                 }]
//             },
//             legend: {
//                 display: true,
//                 position: 'top',
//             },
//         }
//
//         var barStackedChart = new Chart($this, {
//             type: 'bar',
//             data: data,
//             options: options
//         });
//
//         $this.data('chart', barStackedChart);
//     };
//
//     if ($chart_sav.length) {
//         init($chart_sav);
//     }
// };

//TOP LEFT GRAPH
   function renderLineChart_sav() {
       // Variables
       var $chart_monthly_sav= $('#chart-bar-savings');
       // Methods
       function init($this) {
           var yearlyChart = new Chart($this, {
               type: 'line',
               options: {
                   scales: {
                       yAxes: [{
                           gridLines: {
                               color: '#6074DD',
                               zeroLineColor: '#6074DD',
                               zeroLineBorderDash: [0, 0]
                           },
                           ticks: {},
                           stacked: true
                       }],
                       xAxes: [{
                           // stacked:true,
                           ticks: {
                               autoSkip: false,
                               // callback: function(value, index, values) {
                               //     if (value % 5 == 0) //only show the year value every 5 years
                               //         return value;
                               //     else return null;
                               // }
                           }
                       }]
                   },
                   tooltips: {
                       callbacks: {
                           labelColor: function(tooltipItem, data) {
                               if (tooltipItem.datasetIndex == 0) {
                                   return {
                                       backgroundColor: 'RGBA(218, 182, 87, 1.00)'
                                   }
                               } else {
                                   return {
                                       backgroundColor: 'RGBA(197, 130, 75, 1.00)'
                                   }
                               }
                           }
                       }
                       // filter: function(tooltipItem, data) {
                       //     var dataIndex = tooltipItem.datasetIndex;
                       //     var label = data.labels[tooltipItem.index];
                       //     if (dataIndex == 0) {
                       //         return true;
                       //     } else if (label < firstPositive) {
                       //         return false;
                       //     } else return true;
                       // }
                   },
                   legend: {
                       display: true,
                       position: 'top'
                   },
               },
               data: {
               labels: ['January', 'February', 'March', 'April', //x axis lables
                       'May', 'June', 'July', 'August',
                       'September', 'October', 'November', 'December'
                   ],
                   datasets: [
                       {
                           label: 'Savings',
                           backgroundColor: 'RGBA(218, 182, 87, 1.00)',
                           data: monthly_savings,
                           borderWidth: 0,
                           borderColor: 'RGBA(218, 182, 87, 1.00)',
                           //fill: false
                       },
                       {
                           label: 'Export',
                           backgroundColor: "RGBA(197, 130, 75, 1.00)",
                           data: monthly_exports,
                           borderColor: "RGBA(197, 130, 75, 1.00)",
                           //borderColor: '#6074DD',
                           borderWidth: 0,
                           //fill: false
                       }
                   ],
               }
           });
           // Save to jQuery object
           $this.data('chart', yearlyChart);
       };
       // Events
       if ($chart_monthly_sav.length) {
           init($chart_monthly_sav);
       }
   };

// function renderBarChart_gen() {
//
//   var $chart_gen = $('#chart-bar-gen');
//
//   function init($this) {
//
//     var data ={
//       labels: ['January', 'February', 'March', 'April',
//       'May', 'June', 'July', 'August',
//       'September', 'October', 'November', 'December'
//     ],
//     datasets: [{
//       label: 'Captive',
//       backgroundColor: '#6074DD',
//       data: monthly_gen_captive,
//       borderWidth: 0
//     }, {
//       label: 'Export',
//       backgroundColor: '#1B2B4B',
//       data: monthly_gen_exports,
//       borderWidth: 0
//     }]
//   }
//
//   var options= {
//     scales: {
//       xAxes: [{
//         stacked: true,
//         ticks: {
//           autoSkip: false
//         }
//       }],
//       yAxes: [{
//         stacked: true
//       }]
//     },
//     legend: {
//       display: true,
//       position: 'top',
//     },
//   }
//
//   var barStackedChart = new Chart($this, {
//     type: 'bar',
//     data: data,
//     options: options
//   });
//
//       $this.data('chart', barStackedChart);
//     };
//     if ($chart_gen.length) {
//       init($chart_gen);
//     }
//   };

//BOTTOM LEFT GRAPH
   function renderLineChart_gen() {
       // Variables
       var $chart_monthly_gen= $('#chart-line-gen');
       // Methods
       function init($this) {
           var monthlyChart = new Chart($this, {
               type: 'line',
               options: {
                   scales: {
                       yAxes: [{
                           stacked: true,
                           gridLines: {
                               color: '#6074DD',
                               zeroLineColor: '#6074DD',
                               zeroLineBorderDash: [0, 0]
                           },
                           ticks: {}
                       }],
                       xAxes: [{
                           ticks: {
                               autoSkip:false
                           }
                       }]
                   },
                   tooltips: {
                       callbacks: {
                           labelColor: function(tooltipItem, data) {
                               if (tooltipItem.datasetIndex == 0) {
                                   return {
                                       backgroundColor: '#17192B'
                                   }
                               } else {
                                   return {
                                       backgroundColor: '#6074DD'
                                   }
                               }
                           }
                       }
                       // filter: function(tooltipItem, data) {
                       //     var dataIndex = tooltipItem.datasetIndex;
                       //     var label = data.labels[tooltipItem.index];
                       //     if (dataIndex == 0) {
                       //         return true;
                       //     } else if (label < firstPositive) {
                       //         return false;
                       //     } else return true;
                       // }
                   },
                   legend: {
                       display: true,
                       position: 'top'
                   },
               },
               data: {
               labels: ['January', 'February', 'March', 'April', //x axis lables
                       'May', 'June', 'July', 'August',
                       'September', 'October', 'November', 'December'
                   ],
                   datasets: [
                       {
                           label: 'Captive',
                           backgroundColor: 'RGBA(98, 118, 214, 1.00)',
                           data: monthly_gen_captive,
                           borderWidth: 0,
                           borderColor: 'RGBA(98, 118, 214, 1.00) ',
                           //fill: false
                       },
                       {
                           label: 'Export',
                           backgroundColor: "RGBA(30, 43, 73, 1)",
                           data: monthly_gen_exports,
                           borderColor: 'RGBA(30, 43, 73, 1.00)',
                           borderWidth: 0,
                           //fill: false
                       }
                   ],
               }
           });
           // Save to jQuery object
           $this.data('chart', monthlyChart);
       };
       // Events
       if ($chart_monthly_gen.length) {
           init($chart_monthly_gen);
       }
   };

// function renderLineChart_co2() {
//
//     var $chart = $('#chart-report');
//     var ctx = document.getElementById('chart-report').getContext("2d");
//
//
//
//     var gradientFill = ctx.createLinearGradient(500, 0, 100, 0);
//     gradientFill.addColorStop(0, "#7CC25F");
//     gradientFill.addColorStop(1, "RGBA(176, 73, 64, 1.00)");
//
//     var numOfYears = 25,
//         negatives = [],
//         positives = [],
//         years = [];
//     var firstPositive = 26;
//     for (var i = 0; i <= numOfYears; i++) {
//         if (saved_co2[i] <= 0) {
//             negatives.push(saved_co2[i] / 1000)
//         } else firstPositive = Math.min(firstPositive, i);
//         positives.push(saved_co2[i] / 1000)
//         years.push(i);
//     }
//
//     function init($this) {
//         var salesChart = new Chart($this, {
//             type: 'line',
//             options: {
//                 scales: {
//                     yAxes: [{
//                         gridLines: {
//                             color: '#6074DD',
//                             zeroLineColor: '#2c2c2c',
//                             zeroLineWidth: 1.5,
//                             zeroLineBorderDash: [0, 0]
//                         },
//                         ticks: {}
//                     }],
//                     xAxes: [{
//                         ticks: {
//                             callback: function(value, index, values) {
//                                 if (value % 5 == 0)
//                                     return value;
//                                 else return null;
//                             }
//                         }
//                     }]
//                 },
//                 tooltips: {
//                     callbacks: {
//                         labelColor: function(tooltipItem, data) {
//                             if (tooltipItem.datasetIndex == 0) {
//                                 return {
//                                     backgroundColor: '#17192B'
//                                 }
//                             } else {
//                                 return {
//                                     backgroundColor: '#6074DD'
//                                 }
//                             }
//                         }
//                     },
//                     filter: function(tooltipItem, data) {
//                         var dataIndex = tooltipItem.datasetIndex;
//                         var label = data.labels[tooltipItem.index];
//                         if (dataIndex == 0) {
//                             return true;
//                         } else if (label < firstPositive) {
//                             return false;
//                         } else return true;
//                     }
//                 }
//             },
//             data: {
//                 labels: years,
//                 datasets: [{
//                         label: 'Negative',
//                         data: negatives,
//                         borderColor: '#6276D6',
//                         backgroundColor: gradientFill,
//                         fill: true
//                     },
//                     {
//                         label: 'Positive',
//                         data: positives,
//                         borderColor: '#6276D6',
//                         backgroundColor: gradientFill,
//                         fill: true
//                     }
//                 ],
//             }
//         });
//
//         $this.data('chart', salesChart);
//     };
//
//     if ($chart.length) {
//         init($chart);
//     }
//
// };

//TOP RIGHT GRAPH
  function renderBarChart_co2() {
      // Variables
       var numOfYears = 25;
          negatives = [], // for Y-axis
          positives = [];  // for Y-axis
      var firstPositive = 26;
      var $chart = $('#chart-report');
      var years = [];
      var yearly_sav = []
      for (var i = 0; i <= numOfYears; i++) {
          if (saved_co2[i] <= 0) {
              negatives.push(saved_co2[i] / 1000);
              positives.push(0);
          } else {
              firstPositive = Math.min(firstPositive, i)//choose the lower one outta two; will remain the index i of the first positive value we run into.
              positives.push(saved_co2[i] / 1000)
          };
          yearly_sav.push(saved_co2[i]/1000)
          years.push(i);
      }
      //console.log("negatives: ", negatives);
      //console.log("positives: ", positives);
      // Methods
      function init($this) {
          // Chart data
          var data ={
              labels: years,
              datasets: [{
                  label: 'Negative', //1st data in bar chart
                  backgroundColor: '#bd403a',
                  data: negatives,
                },
                {
                  label: 'Positive', //1st data in bar chart
                  backgroundColor: '#63C54F',
                  data: positives,
                  //barThickness: 'flex'
                }
              ]
          };
          // Options
          var options= {
              scales: {
                  xAxes: [{
                      ticks: {
                          callback: function(value, index, values) {
                              //console.log("value: ", value);
                              if (value % 5 == 0) {
                                  return value;
                              }
                              else return null;
                          }
                      }
                  }],
                  yAxes: [{
                      gridLines: {
                          color: '#6074DD',
                          zeroLineColor: '#6074DD',
                          zeroLineBorderDash: [0, 0]
                      },
                      ticks: { }
                  }],
               },
              tooltips: {
                  callbacks: {
                      labelColor: function(tooltipItem, data) {
                          if (tooltipItem.datasetIndex == 0) {
                              return {
                                  backgroundColor: '#bd403a'
                              }
                          } else {
                              return {
                                  backgroundColor: '#63C54F'
                              }
                          }
                      },
                      title: function(tooltipItem, data){
                          return 'Year ' + tooltipItem[0].index
                      }
                  },
                  filter: function(tooltipItem, data) {
                      var dataIndex = tooltipItem.datasetIndex;
                      var label = data.labels[tooltipItem.index];
                      if (dataIndex == 0) {
                          return true;
                      } else if (label < firstPositive) {
                          return false;
                      } else return true;
                  }
              }
          }
          // Init chart      //prepare data, prepare options to init chart
          var barChart = new Chart($this, {
              type: 'bar',
              data: data,
              options: options
          });
          // Save to jQuery object
          $this.data('chart', barChart);
      }
          if ($chart.length) {
              init($chart);
          }
  };

//   function renderLineChart_gen() {
//
//     var $chart_yearly_gen= $('#chart-report-generation');
//     var numOfYears = 25;
//     var firstPositive = 25;
//     var years = [];
//     for (var i = 1; i <= numOfYears; i++) {
//       years.push(i);
//     }
//
//     function init($this) {
//       var yearlyChart = new Chart($this, {
//         type: 'line',
//         options: {
//           legend: {
//             display: true,
//             position: 'top',
//           },
//           scales: {
//             yAxes: [{
//               gridLines: {
//                 color: '#6074DD',
//                 zeroLineColor: '#2c2c2c',
//                 zeroLineWidth: 1.5,
//                 zeroLineBorderDash: [0, 0]
//               },
//               ticks: {}
//             }],
//             xAxes: [{
//               ticks: {
//                 callback: function(value, index, values) {
//                   if (value % 5 == 0)
//                   return value;
//                   else return null;
//                 }
//               }
//             }]
//           },
//           tooltips: {
//             callbacks: {
//               labelColor: function(tooltipItem, data) {
//                 if (tooltipItem.datasetIndex == 0) {
//                   return {
//                     backgroundColor: '#6074DD'
//                   }
//                 } else {
//                   return {
//                     backgroundColor: "#17192B"
//                   }
//                 }
//               }
//             }
//           }
//         },
//
//       data: {
//         labels: years,
//         datasets: [
//           {
//             label: 'Captive',
//             backgroundColor: "#6074DD",
//             data: yearly_gen_captive,
//             borderColor: '#6074DD',
//             fill: false,
//             borderWidth: 0
//           },
//           {
//             label: 'Export',
//             backgroundColor: "#67BAB5",
//             data: yearly_gen_exports,
//             borderColor: '#67BAB5',
//
//             borderWidth: 0
//           }
//         ],
//       }
//
//
//     });
//
//     $this.data('chart', yearlyChart);
//   };
//
//   if ($chart_yearly_gen.length) {
//     init($chart_yearly_gen);
//   }
// };

//BOTTOM RIGHT GRAPH
     function renderBarChart_gen() {
        // Variables
        var $chart_yearly_gen= $('#chart-report-generation');
        var numOfYears = 25;
        var firstPositive = 25;
        var years = [];
        for (var i = 1; i <= numOfYears; i++) {
            years.push(i);
        }
        // Methods
        function init($this) {
            var yearlyChart = new Chart($this, {
                type: 'bar',
                options: {
                    scales: {
                        yAxes: [{
                            gridLines: {
                                color: '#6074DD',
                                zeroLineColor: '#6074DD',
                                zeroLineBorderDash: [0, 0]
                            },
                            ticks: {},
                            stacked: true
                        }],
                        xAxes: [{
                            stacked: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    if (value % 5 == 0) //only show the year value every 5 years
                                        return value;
                                    else return null;
                                },
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            labelColor: function(tooltipItem, data) {
                                if (tooltipItem.datasetIndex == 0) {
                                    return {
                                        backgroundColor: '#6276D6'
                                    }
                                } else {
                                    return {
                                        backgroundColor: "#1E2B49"
                                    }
                                }
                            },
                            title: function(tooltipItem, data){
                                var year = tooltipItem[0].index + 1
                                return 'Year ' + year
                            }
                        }
                        // filter: function(tooltipItem, data) {
                        //     var dataIndex = tooltipItem.datasetIndex;
                        //     var label = data.labels[tooltipItem.index];
                        //     if (dataIndex == 0) {
                        //         return true;
                        //     } else if (label < firstPositive) {
                        //         return false;
                        //     } else return true;
                        // }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    },
                },

                data: {
                    labels: years,
                    datasets: [
                        {
                            label: 'Captive',
                            backgroundColor: '#6276D6',
                            data: yearly_gen_captive,
                            borderColor: 'RGB(224, 187, 228, 0.2)',
                            borderWidth: 0
                             //fill: false
                        },
                        {
                            label: 'Export',
                            backgroundColor: "#1E2B49",
                            data: yearly_gen_exports,
                            borderColor: '#6074DD',
                            borderWidth: 0
                             //fill: false
                        }
                    ],
                }
            });

            // Save to jQuery object
            $this.data('chart', yearlyChart);
        };
        // Events
        if ($chart_yearly_gen.length) {
            init($chart_yearly_gen);
        }
    };

    function renderMap() {
        var features = []
        mapboxgl.accessToken = 'pk.eyJ1IjoicG93ZXJtYXJrZXQiLCJhIjoiY2s3b3ZncDJ0MDkwZTNlbWtoYWY2MTZ6ZCJ9.Ywq8CoJ8OHXlQ4voDr4zow';
        map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/satellite-streets-v11',
            bearing: -17.6,
            antialias: true,
            pitch: 45
        });
        var bounds = new mapboxgl.LngLatBounds();
        for (key = 0; key < dataArray.length; key++) {
            var feature = {
            type: 'Feature',
            properties: {
                id: dataArray[key].id
            },
            geometry: {
                type: dataArray[key].latLon.type,
                coordinates: dataArray[key].latLon.coordinates
            }
            }
            features.push(feature)
            bounds.extend(feature.geometry.coordinates);
    }
    map.on('load', function() {
        map.loadImage('../../svg/map-marker-alt-solid.png', function(error, image) {
        if (error) throw error;
        map.addImage('marker-icon', image, {
            'sdf': true
        });
        map.addSource('places', {
            'type': 'geojson',
            'data': {
            'type': 'FeatureCollection',
            'features': features
            },
            cluster: true,
            clusterMaxZoom: 12, // Max zoom to cluster points on
            clusterRadius: 60
        });
        map.addLayer({
            'id': 'places',
            'type': 'symbol',
            'source': 'places',
            'layout': {
            'icon-image': 'marker-icon',
            'icon-allow-overlap': true,
            "icon-size": ['interpolate', ['linear'],
            ['zoom'], 10, 0.1, 15, 1
            ]
        },
        'paint': {
            'icon-color': '#F6A22B'
        }
        })
    })
    })

    map.fitBounds(bounds)
    }

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        renderLineChart_sav();
        renderLineChart_gen();
        renderBarChart_co2();
        renderBarChart_gen();
        renderMap();
        renderTable();
    });
</script>
<style>
.dataTable tr {
  cursor: pointer;
}

.filter-group {
  font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
  font-weight: 600;
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 1;
  border-radius: 3px;
  width: 120px;
  color: #fff;
}

.filter-group input[type='checkbox']:first-child+label {
  border-radius: 3px 3px 0 0;
}

.filter-group label:last-child {
  border-radius: 0 0 3px 3px;
  border: none;
}

.filter-group input[type='checkbox'] {
  display: none;
}

.filter-group input[type='checkbox']+label {
  background-color: #3386c0;
  display: block;
  cursor: pointer;
  padding: 10px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.25);
}

.filter-group input[type='checkbox']+label {
  background-color: #3386c0;
  text-transform: capitalize;
}

.filter-group input[type='checkbox']+label:hover,
.filter-group input[type='checkbox']:checked+label {
  background-color: #4ea0da;
}

.filter-group input[type='checkbox']:checked+label:before {
  content: '✔';
  margin-right: 5px;
}

.mapboxgl-popup-close-button {
  font-size: 22px;
}

.mapboxgl-popup-content {
  background-color: #ffffff;
}
</style>
@endpush
