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
                            <h4 class="text-uppercase ls-1 text-primary text-center py-3 mb-0">Report for Project: {{ $project ?? ''}}</h4>
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
                                                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
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
                                                        <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
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
                                                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
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
                                                        <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
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
                                            <h5 class="h3 mb-0">Monthly Savings</h5>
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
                                                    <img style="width: 40px;" class="mx-auto" src="{{ asset('argon') }}/img/icons/common/factory.svg" />
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
                                                    <img style="width: 40px;" class="mx-auto" src="{{ asset('argon') }}/img/icons/common/road.svg" />
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
                                                    <img style="width: 40px;" class="mx-auto" src="{{ asset('argon') }}/img/icons/common/tree.svg" />
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
                                                    <img style="width: 40px;" class="mx-auto" src="{{ asset('argon') }}/img/icons/common/gas.svg" />
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

    var jsonString = `{!! $geodata ?? '
    ' !!}`;
    var map;
    var dataArray = JSON.parse(jsonString);

    function renderTable() {
        var layerPrefix = 'layer-years-';
        var table = $('#datatable-report').DataTable({
            // paging: false,
            // searching: false,
            ordering: false,
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

    function renderBarChart() {
        // Variables
        var $chart = $('#chart-bar-savings');
        // Methods
        function init($this) {
            // Chart data
            var data = {
                labels: ['January', 'February', 'March', 'April',
                    'May', 'June', 'July', 'August',
                    'September', 'October', 'November', 'December'
                ],
                datasets: [{
                    label: 'Savings',
                    backgroundColor: '#6074DD',
                    data: monthly_savings,
                    borderWidth: 0
                }, {
                    label: 'Export',
                    backgroundColor: '#1B2B4B',
                    data: monthly_exports,
                    borderWidth: 0
                }]
            };
            // Options
            var options = {
                scales: {
                    xAxes: [{
                        stacked: true,
                        ticks: {
                            autoSkip: false
                        }
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                },
                legend: {
                    display: true,
                    position: 'top',
                },
            }
            // Init chart
            var barStackedChart = new Chart($this, {
                type: 'bar',
                data: data,
                options: options
            });
            // Save to jQuery object
            $this.data('chart', barStackedChart);
        };
        // Events
        if ($chart.length) {
            init($chart);
        }
    };

    function renderLineChart() {
        // Variables
        var $chart = $('#chart-report');
        var numOfYears = 25,
            negatives = [],
            positives = [],
            years = [];
        var firstPositive = 26;
        for (var i = 0; i <= numOfYears; i++) {
            if (saved_co2[i] <= 0) {
                negatives.push(saved_co2[i] / 1000)
            } else firstPositive = Math.min(firstPositive, i);
            positives.push(saved_co2[i] / 1000)
            years.push(i);
        }
        // Methods
        function init($this) {
            var salesChart = new Chart($this, {
                type: 'line',
                options: {
                    scales: {
                        yAxes: [{
                            gridLines: {
                                color: '#6074DD',
                                zeroLineColor: '#6074DD',
                                zeroLineBorderDash: [0, 0]
                            },
                            ticks: {}
                        }],
                        xAxes: [{
                            ticks: {
                                callback: function(value, index, values) {
                                    if (value % 5 == 0)
                                        return value;
                                    else return null;
                                }
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
                },
                data: {
                    labels: years,
                    datasets: [{
                            label: 'Negative',
                            data: negatives,
                            borderColor: '#17192B'
                        },
                        {
                            label: 'Positive',
                            data: positives
                        }
                    ],
                }
            });
            // Save to jQuery object
            $this.data('chart', salesChart);
        };
        // Events
        if ($chart.length) {
            init($chart);
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
        renderBarChart();
        renderLineChart();
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
