@extends('layouts.app', [
'parentSection' => 'dashboards',
'elementName' => 'dashboard'
])

@section('content')
@component('layouts.headers.auth')
@component('layouts.headers.breadcrumbs')
@slot('title')
{{ __('Dashboard') }}{{ isset($account) ? " | ".$account : "" }}{{ isset($region) ? " | ".$region : "" }}{{ isset($cluster) ? " | ".$cluster : "" }}
@endslot

{{-- <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>--}}
{{-- <li class="breadcrumb-item active" aria-current="page">{{ __('Default') }}</li> --}}
@endcomponent
@endcomponent

<div class="modal fade" id="delete-form" tabindex="-1" role="dialog" aria-labelledby="delete-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0 mb-0">
                    <div class="card-header bg-white">
                        <div class="text-muted text-left">
                            <h2>Are you sure you want to remove the site?</h2>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <div class="next-buttons">
                            <button type="button" style="width:48%" id="remove-site" class="btn btn-primary">Yes</button>
                            <button type="button" style="width:48%" data-dismiss="modal" class="btn btn-primary">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0 mb-0">
                    <div class="card-header bg-white">
                        <div class="text-muted text-left mb-3">
                            <h2>Create project</h2>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <form method="post" action="{{ route('invitation.store') }}" role="form">
                            @csrf
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter Name') }}" value="{{ old('name') }}" required autofocus>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-default my-4">Create project</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete-next-form" tabindex="-1" role="dialog" aria-labelledby="delete-next-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0 mb-0">
                    <div class="card-header bg-white">
                        <h2 class="text-muted text-left">What next:</h2>
                        <button id="close-button" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body bg-white">
                        <div id="delete-next-response-status" class="alert" role="alert"></div>
                        <div class="next-buttons">
                            <button type="button" style="width:48%" data-dismiss="modal" class="btn btn-primary">Keep browsing</button>
                            <a target="_blank" href="/dashboard" type="submit" style="width:48%" class="btn btn-default">Add more sites</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="next-form" tabindex="-1" role="dialog" aria-labelledby="next-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0 mb-0">
                    <div class="card-header bg-white">
                        <h2 class="text-muted text-left">What next:</h2>
                        <button id="close-button" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body bg-white">
                        <div id="next-response-status" class="alert" role="alert"></div>
                        <div class="next-buttons">
                            <button type="button" style="width:48%" data-dismiss="modal" class="btn btn-primary">Keep browsing</button>
                            <a id="cluster-href" target="_blank" href="/" type="submit" style="width:48%" class="btn btn-default">Go to Project</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cluster-form" tabindex="-1" role="dialog" aria-labelledby="cluster-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0 mb-0">
                    <div class="card-header bg-white">
                        <div class="text-muted text-left">
                            <h2>Add to</h2>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <form method="post" action="{{ route('invitation.store') }}" role="form">
                            @csrf
                            <h5>Existing project</h5>
                            <select class="form-control form-control-sm" id="cluster-select">
                            </select>
                            <h5 style="margin-top: .5rem;">or</h5>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="newClusterCheck" value="new_cluster">
                                <label class="custom-control-label" for="newClusterCheck">
                                    <h5>Create a new project</h5>
                                </label>
                            </div>
                            <div class="form-group{{ $errors->has('new_name') ? ' has-danger' : '' }}">
                                <input style="margin-top: 1rem;" disabled type="text" name="new_name" id="new-name" class="form-control{{ $errors->has('new_name') ? ' is-invalid' : '' }} form-control-sm" placeholder="{{ __('Enter new project name') }}" value="{{ old('new_name') }}" required autofocus>
                            </div>
                            <!-- <div id="cluster-response-status" class="alert" role="alert"></div> -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-default">Add to Project</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total number of sites</h5>
                            <span class="h2 font-weight-bold mb-0" id="total-sites">{{ number_format(count($geodata)) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <div class="icon icon-shape bg-gradient-pm text-white rounded-circle shadow">
                                    <i class="ni ni-chart-pie-35"></i>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding-right: 1.0rem;padding-left: 1.0rem;">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('page.pricing') }}">Upgrade to add more projects</a>
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
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Solar Potential Discovered</h5>
                            <span class="h2 font-weight-bold mb-0" id="potential-card">1</span>
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <div class="icon icon-shape bg-gradient-pm text-white rounded-circle shadow">
                                    <i class="ni ni-atom"></i>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding-right: 1.0rem;padding-left: 1.0rem;">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('page.pricing') }}">Upgrade to view by Weekly, Monthly, Annually</a>
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
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Lifetime savings</h5>
                            <span class="h2 font-weight-bold mb-0" id="savings-card">1</span>
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <div class="icon icon-shape bg-gradient-pm text-white rounded-circle shadow">
                                    <i class="fas fa-pound-sign"></i>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding-right: 1.0rem;padding-left: 1.0rem;">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('page.pricing') }}">Upgrade to view by Weekly, Monthly, Annually</a>
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
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Lifetime CO<sub>2</sub> savings</h5>
                            <span class="h2 font-weight-bold mb-0" id="co2-card">1</span>
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <div class="icon icon-shape bg-gradient-pm text-white rounded-circle shadow">
                                    <i class="fas fa-smog"></i>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding-right: 1.0rem;padding-left: 1.0rem;">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('page.pricing') }}">Upgrade to view by Weekly, Monthly, Annually</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col text-left" style="margin-bottom: 10px;">
            <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="modal" data-target="#modal-form" aria-haspopup="true" aria-expanded="false">
                Create project from active points
            </button>
            <span class="text-nowrap" style="font-size: .75rem">
                Showing
                <span id="selected-count">1</span>
                of <span id="total-count">1</span> entries</span>
        </div>
        <div class="col text-right" style="margin-bottom: 10px;">
            <span class="text-nowrap" style="font-size: .75rem">You are browsing by &nbsp;</span>
            <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Break-even Time
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('page.pricing') }}">Upgrade to enable more filters</a>
            </div>
            {{-- <a href="#" class="btn btn-sm btn-neutral">{{ __('New') }}</a>--}}
            {{-- <a href="#" class="btn btn-sm btn-neutral">{{ __('Filters') }}</a>--}}
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card border-0">
                {{-- <div id="map-custom" class="map-canvas" data-lat="40.748817" data-lng="-73.985428" style="height: 600px;"></div>--}}
                <div id='map' class="map-canvas" style='height: 600px;'></div>
                <nav id="filter-group" class="filter-group">
                    <span style="background-color: #1B2B4B;margin-bottom: 0px;display: block;border-bottom: 1px solid rgba(0, 0, 0, 0.25);padding: 10px;">
                        Break-even</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Disclaimer -->
    <br>
    <h4>DISCLAIMER</h4>
    <h5>The data presented are estimations based on standard, industry-wide assumption; but can differ from actual solar array for the rooftops displayed.</h5>
    <h5>Please consult a professional solar installations company for a customised proposal.</h5>
    <br>
    <button type="button" class="btn btn-sm btn-neutral mr-0" aria-haspopup="true" aria-expanded="false">
        <a href="{{ route('page.faq') }}" target="_blank">FAQ</a>
    </button>
    <!-- <h5><a href="{{ route('page.faq') }}" target="_blank">FAQ</a></h5> -->
    <!-- Footer -->
    @include('layouts.footers.auth')
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css'>
<link rel="stylesheet" href="{{ asset('css') }}/dashboard.css">
@endpush
@push('js')
<script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
<script src="{{ asset('js') }}/mapbox-gl.js"></script>
<script src="{{ asset('argon') }}/vendor/list.js/dist/list.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoicG93ZXJtYXJrZXQiLCJhIjoiY2s3b3ZncDJ0MDkwZTNlbWtoYWY2MTZ6ZCJ9.Ywq8CoJ8OHXlQ4voDr4zow';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/satellite-v9',
        pitch: 45,
        bearing: -17.6,
        antialias: true
    });
    var clicked_geopoint_id, clicked_layer, clicked_popup;
    var layerPrefix = 'layer-years-';
    var yearColors = [
        [5, '#63c54f'],
        [6, '#63c54f'],
        [7, '#63c54f'],
        [8, '#e0b542'],
        [9, '#e0b542'],
        [10, '#cf7f3e'],
        [11, '#cf7f3e'],
        [12, '#bd403a'],
        [13, '#bd403a'],
        [14, '#bd403a']
    ];
    var yearColorMap = new Map(yearColors);
    var symbolCountMap = new Map();
    var totalCount = 0;
    var selectedCount = 0,
        dataArray;
    var filterYears = new Map();
    var cluster_route = `{!! $cluster ?? '' !!}`
    var features = [];

    function renderMap() {
        var jsonString = `{!! $geodata ?? '
        ' !!}`;
        var bounds = new mapboxgl.LngLatBounds();
        var filterGroup = document.getElementById('filter-group');
        if (jsonString.length > 0) {
            dataArray = JSON.parse(jsonString);
            dataArray.sort(function(a, b) {
                return a['breakeven_years'] - b['breakeven_years'];
            });
            var potential = 0;
            var savings = 0;
            var co2 = 0;

            // for (var key in jsonData) {
            for (key = 0; key < dataArray.length; key++) {
                //determining header of point popup
                var header = `
                    <h5 class="h3 mb-0" title="Remove the geopoint from project" data-toggle="tooltip" data-placement="top">Remove from project</h5>
                    <a id="remove_from_cluster" data-toggle="modal" data-target="#delete-form" data-geopoint="${dataArray[key].id}">
                        <img src="{{ asset('argon') }}/img/icons/minus.png" />
                    </a>
                `
                if (cluster_route == "") {
                    header = `
                        <h5 class="h3 mb-0" title="Add this geopoint to a new or existing project" data-toggle="tooltip" data-placement="top">Add to Project</h5>
                        <a id="add_cluster" data-toggle="modal" data-target="#cluster-form" data-geopoint="${dataArray[key].id}">
                            <img src="{{ asset('argon') }}/img/icons/plus.png" />
                        </a>
                    `
                }
                var feature = {
                    type: "Feature",
                    properties: {
                        description: `
                        <div class="card popup-card">
                            <div id="cluster-header" class="card-header" style="display:table;padding-top:0.5rem;padding-bottom:0.5rem;padding-left:1rem;padding-right:0;">
                                ${header}
                            </div>
                            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem; padding-left:1rem; padding-right:1rem;">
                                <p class="card-text">
                                <strong>Break-even:</strong> ${dataArray[key].breakeven_years} years</br>
                                <strong>System Size:</strong> ${numeral(dataArray[key].system_capacity_kWp).format('0,0.0a')} kWp<br/>
                                <strong>System Cost:</strong> £ ${numeral(dataArray[key].system_cost_GBP).format('0,0.0a')}<br/>
                                <strong>Lifetime Savings:</strong> £ ${numeral(dataArray[key].lifetime_gen_GBP).format('0,0.0a')}<br/>
                                <strong>Lifetime CO<sub>2</sub> saving:</strong> ${numeral(dataArray[key].lifetime_co2_saved_kg).format('0,0.0a')} kgs<br/>
                                <strong>Lifetime RoI:</strong> ${numeral(dataArray[key].lifetime_return_on_investment_percent).format('0,0.0a')}%<br/>
                                </p>
                                <a href="{{ route('page.reporting') }}?geopoint_id=${dataArray[key].id}" class="btn btn-primary"
                                target="_blank">Generate Report</a>
                                <a href="{{ route('page.building') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top"
                                target="_blank" title="Upgrade to view detailed building ownership information, and tenancy details for commercial and industrial buildings.">Building Info</a>
                            </div>
                        </div>`,
                        years: dataArray[key].breakeven_years,
                        id: dataArray[key].id,
                        area: dataArray[key].area_sqm,
                        panels: dataArray[key].numpanels,
                        roi: dataArray[key].lifetime_return_on_investment_percent
                    },
                    geometry: {
                        type: dataArray[key].latLon.type,
                        coordinates: dataArray[key].latLon.coordinates
                    }
                };
                features.push(feature);
                potential = potential + dataArray[key].system_capacity_kWp;
                savings = savings + dataArray[key].lifetime_gen_GBP;
                co2 = co2 + dataArray[key].lifetime_co2_saved_kg;
                if (symbolCountMap[dataArray[key].breakeven_years] == undefined)
                    symbolCountMap[dataArray[key].breakeven_years] = 0;
                symbolCountMap[dataArray[key].breakeven_years] += 1;
            }
            features.forEach(function(feature) {
                bounds.extend(feature.geometry.coordinates);
            });
            if (potential / 1000000 >= 1) {
                potential = potential / 1000000;
                $('#potential-card').text(numeral(potential).format('0,0.0a') + " GWp");
            } else if (potential / 1000 >= 1) {
                potential = potential / 1000;
                $('#potential-card').text(numeral(potential).format('0,0.0a') + " MWp");
            } else
                $('#potential-card').text(numeral(potential).format('0,0.0a') + " kWp");
            $('#savings-card').text('£ ' + numeral(savings).format('(0.00a)'));
            $('#co2-card').text(numeral(co2).format('0,0.0a') + " kgs");
            totalCount = dataArray.length;
            selectedCount = totalCount;
            $('#total-count').text(numeral(dataArray.length).format('0,0'));
            $('#selected-count').text(numeral(dataArray.length).format('0,0'));
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
                    clusterRadius: 50
                });
                features.forEach(function(feature) {
                    var symbol = feature.properties['years'];
                    var layerID = layerPrefix + symbol;
                    filterYears[symbol] = true;
                    // Add a layer for this symbol type if it hasn't been added already.
                    if (!map.getLayer(layerID)) {
                        map.addLayer({
                            'id': layerID,
                            'type': 'symbol',
                            'source': 'places',
                            'layout': {
                                'icon-image': 'marker-icon',
                                'icon-allow-overlap': true,
                                "icon-size": ['interpolate', ['linear'],
                                    ['zoom'], 10, 0.1, 15, 1
                                ]
                            },
                            'filter': ['==', 'years', symbol],
                            'paint': {
                                'icon-color': yearColorMap.get(symbol) ?? '#6D0000'
                            }
                        });

                        // Add checkbox and label elements for the layer.
                        var input = document.createElement('input');
                        input.type = 'checkbox';
                        input.id = layerID;
                        input.checked = true;
                        filterGroup.appendChild(input);

                        var label = document.createElement('label');
                        label.setAttribute('for', layerID);
                        label.setAttribute('style', 'margin-bottom:0px;background-color:' + (yearColorMap.get(symbol) ?? '#6D0000'));
                        label.textContent = symbol + ' Years';
                        filterGroup.appendChild(label);

                        // When the checkbox changes, update the visibility of the layer.
                        input.addEventListener('change', function(e) {
                            map.setLayoutProperty(
                                layerID,
                                'visibility',
                                e.target.checked ? 'visible' : 'none'
                            );
                            filterYears[symbol] = e.target.checked ? true : false;
                            if (e.target.checked)
                                selectedCount = selectedCount + symbolCountMap[symbol];
                            else
                                selectedCount = selectedCount - symbolCountMap[symbol];
                            $('#selected-count').text(numeral(selectedCount).format('0,0'));
                        });
                        map.on('click', layerID, function(e) {
                            if (e.originalEvent.cancelBubble) {
                                return;
                            }
                            clicked_layer = layerID;
                            var coordinates = e.features[0].geometry.coordinates.slice();
                            var description = e.features[0].properties.description;

                            // Ensure that if the map is zoomed out such that multiple
                            // copies of the feature are visible, the popup appears
                            // over the copy being pointed to.
                            while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                                coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                            }

                            var popup = new mapboxgl.Popup()
                                .setLngLat(coordinates)
                                .setHTML(description)
                                .setMaxWidth("500px")
                                .on('open', function(e) {
                                    clicked_popup = e.target
                                })
                                .addTo(map);
                            $('[data-toggle="tooltip"]').tooltip();
                            e.originalEvent.cancelBubble = true;
                        });
                        // Change the cursor to a pointer when the mouse is over the layer.
                        map.on('mouseenter', layerID, function() {
                            map.getCanvas().style.cursor = 'pointer';
                        });

                        // Change it back to a pointer when it leaves.
                        map.on('mouseleave', layerID, function() {
                            map.getCanvas().style.cursor = '';
                        });
                    }
                });
                map.addLayer({
                    id: 'clusters',
                    type: 'circle',
                    source: 'places',
                    filter: ['has', 'point_count'],
                    paint: {
                        // Use step expressions (https://docs.mapbox.com/mapbox-gl-js/style-spec/#expressions-step)
                        // with three steps to implement three types of circles:
                        //   * Blue, 20px circles when point count is less than 100
                        //   * Yellow, 30px circles when point count is between 100 and 750
                        //   * Pink, 40px circles when point count is greater than or equal to 750
                        'circle-color': '#51bbd6',
                        // 'circle-color': [
                        //     'step',
                        //     ['get', 'point_count'],
                        //     '#51bbd6',
                        //     100,
                        //     '#f1f075',
                        //     750,
                        //     '#f28cb1'
                        // ],
                        'circle-radius': [
                            'step',
                            ['get', 'point_count'],
                            20,
                            100,
                            30,
                            750,
                            40
                        ]
                    }
                });
                map.addLayer({
                    id: 'cluster-count',
                    type: 'symbol',
                    source: 'places',
                    filter: ['has', 'point_count'],
                    layout: {
                        'text-field': '{point_count_abbreviated}',
                        'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                        'text-size': 12
                    }
                });

                var layers = map.getStyle().layers;

                var labelLayerId;
                for (var i = 0; i < layers.length; i++) {
                    if (layers[i].type === 'symbol' && layers[i].layout['text-field']) {
                        labelLayerId = layers[i].id;
                        break;
                    }
                }
                map.fitBounds(bounds);
            });
        });
    }

    function getClusters() {
        $.ajax({
            type: 'GET',
            url: '/clusters',
        }).done(function(data) {
            clusters = data.data;
            cluster_select = $('#cluster-select')
            cluster_select.find('option').remove()
            for (var i = 0; i < clusters.length; i++) {
                cluster_select.append($('<option>').val(clusters[i].id).text(clusters[i].name))
            }
        })
    }
    $(document).ready(function() {
        renderMap();
        $('[data-toggle="tooltip"]').tooltip();
        getClusters();
        $('#map').on('click', '#add_cluster', function(event) {
            clicked_geopoint_id = event.currentTarget.getAttribute("data-geopoint")
        });
        $('#map').on('click', '#remove_from_cluster', function(event) {
            clicked_geopoint_id = event.currentTarget.getAttribute("data-geopoint")
        });
        $('#remove-site').on('click', function(event) {
            var formData = {
                geopoint_id: clicked_geopoint_id,
                '_token': $('input[name=_token]').val(),
                cluster_name: cluster_route
            }
            $.ajax({
                type: 'POST',
                url: '/remove/geopoint',
                data: formData,
                dataType: 'json',
                encode: true
            }).done(function(data) {
                $('#delete-form').modal('hide')
                $('#delete-next-form').modal('show')
                var break_even_years
                features = features.filter(function(feature) {
                    if (feature.properties.id == clicked_geopoint_id) {
                        break_even_years = feature.properties.years
                    }
                    return feature.properties.id != clicked_geopoint_id
                })
                symbolCountMap[break_even_years] -= 1
                totalCount -= 1
                selectedCount -= 1
                $('#total-count').text(numeral(totalCount).format('0,0'));
                $('#selected-count').text(numeral(selectedCount).format('0,0'));
                $('#total-sites').text(totalCount)
                map.getSource('places').setData({
                    'type': 'FeatureCollection',
                    'features': features
                })
                clicked_popup.remove()
                $('#delete-next-response-status').text(data.message).css('display', 'block').addClass('alert-success').removeClass('alert-danger').delay(3000).fadeOut();
            }).fail(function(data) {
                $('#delete-form').modal('hide')
                $('#delete-next-form').modal('show')
                $('#delete-next-response-status').text(data.responseJSON.message).css('display', 'block').addClass('alert-danger').removeClass('alert-success').delay(3000).fadeOut();
            });
        })
        $('#modal-form').submit(function(event) {
            event.preventDefault();
            var visiblePoints = [];
            for (var i = 0; i < dataArray.length; i++) {
                if (filterYears[dataArray[i].breakeven_years]) {
                    visiblePoints.push(dataArray[i].id);
                }
            }
            var formData = {
                'name': $('input[name=name]').val(),
                '_token': $('input[name=_token]').val(),
                'geopoints': JSON.stringify(visiblePoints)
            };
            $.ajax({
                type: 'POST',
                url: '/clusters',
                data: formData,
                dataType: 'json',
                encode: true
            }).done(function(data) {
                $('#modal-form').modal('hide')
                $('#next-form').modal('show')
                getClusters()
                $('#next-response-status').text(data.message).css('display', 'block').addClass('alert-success').removeClass('alert-danger').delay(3000).fadeOut();
                $('#cluster-href').attr('href', data.cluster_link)
            }).fail(function(data) {
                $('#modal-form').modal('hide')
                $('#next-form').modal('show')
                $('#next-response-status').text(data.responseJSON.message).css('display', 'block').addClass('alert-danger').removeClass('alert-success').delay(3000).fadeOut();
            });
        });
        $('#newClusterCheck').change(function(event) {
            $('input[name=new_name]').prop('disabled', !event.target.checked)
            $('#cluster-select').prop('disabled', event.target.checked)
        })
        $('#cluster-form').submit(function(event) {
            event.preventDefault();
            var formData = {
                geopoint_id: clicked_geopoint_id,
                '_token': $('input[name=_token]').val()
            }
            if ($('#newClusterCheck').is(":checked")) {
                formData['new_name'] = $('input[name=new_name]').val()
            } else {
                formData['cluster_id'] = $('#cluster-select').children("option:selected").val()
            }
            $.ajax({
                type: 'POST',
                url: '/add/geopoint',
                data: formData,
                dataType: 'json',
                encode: true
            }).done(function(data) {
                $('#cluster-form').modal('hide')
                $('#next-form').modal('show')
                getClusters()
                $('#next-response-status').text(data.message).css('display', 'block').addClass('alert-success').removeClass('alert-danger').delay(3000).fadeOut();
                $('#cluster-href').attr('href', data.cluster_link)
            }).fail(function(data) {
                $('#cluster-form').modal('hide')
                $('#next-form').modal('show')
                $('#next-response-status').text(data.responseJSON.message).css('display', 'block').addClass('alert-danger').removeClass('alert-success').delay(3000).fadeOut();
            });
        });
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
        border-radius: .375rem;
        overflow: hidden;
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
