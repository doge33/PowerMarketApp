@extends('layouts.app')

@section('content')
<script src="{{ asset('js') }}/mapbox-gl.js"></script>
<div class="modal fade" id="delete-form" tabindex="-1" role="dialog" aria-labelledby="delete-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0 mb-0">
                    <div class="card-header bg-white">
                        <div class="text-muted text-left">
                            <h2>Are you sure you want to remove the project?</h2>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <div class="next-buttons">
                            <button type="button" style="width:48%" id="delete-yes" class="btn btn-primary">Yes</button>
                            <button type="button" style="width:48%" data-dismiss="modal" class="btn btn-primary">No</button>
                        </div>
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
                        <div class="next-buttons" style="text-align:center;">
                            <button type="button" style="width:48%" data-dismiss="modal" class="btn btn-primary">Keep browsing</button>
                            <!-- <a target="_blank" href="/dashboard" type="submit" style="width:48%" class="btn btn-default">Add more sites</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header bg-primary">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">
                        {{ __('Dashboard') }}
                    </h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal for adding clusters -->
<div class="modal fade" id="cluster-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
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
                            <div id="response-status" class="alert" role="alert"></div>
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
<!-- Page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12 orgname">
            <span class="h1">{{ $org_name }}</span>
        </div>
    </div>
    @if(!auth()->user()->isMember())
    <div class="row">
        <div class="col-12">
            <p class="h2">Users:</p>
        </div>
        @foreach($members as $member)
        <div class="user">
            <img src="{{ $member->picture ? asset('/storage/'.$member->picture) : asset('argon').'/img/theme/team-4.jpg'}}" title="{{ $member->name }}" data-toggle="tooltip" class="rounded-circle border-secondary">
        </div>
        @endforeach
        <div class="user">
            <a data-toggle="modal" data-target="#modal-form">
                <img src="{{ asset('svg') }}/add-button.svg" class="rounded-circle border-secondary add-button" data-toggle="tooltip" data-placement="top" title="Invite User">
            </a>
            <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <div class="card bg-secondary shadow border-0 mb-0">
                                <div class="card-header bg-white">
                                    <div class="text-muted text-left mb-3">
                                        <h2>Invite User</h2>
                                    </div>
                                </div>
                                <div class="card-body bg-white">
                                    <form method="post" action="{{ route('invitation.store') }}" role="form">
                                        @csrf
                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                            <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter Name') }}" value="{{ old('name') }}" required autofocus>

                                            @include('alerts.feedback', ['field' => 'name'])
                                        </div>
                                        <h4>Admin</h4>
                                        <div class="form-group{{ $errors->has('is_admin') ? ' has-danger' : '' }}">
                                            <label class="custom-toggle custom-toggle-success">
                                                <input type="checkbox" name="is_admin" value="1" checked>
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="NO" data-label-on="YES"></span>
                                            </label>
                                            @include('alerts.feedback', ['field' => 'is_admin'])
                                        </div>
                                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                            <input type="email" name="email" id="input-email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter Email') }}" value="{{ old('email') }}" required>

                                            @include('alerts.feedback', ['field' => 'email'])
                                        </div>
                                        <h4>Accounts</h4>
                                        @foreach($accounts as $account)
                                        <div class="custom-control custom-checkbox mb-3">
                                            <input class="custom-control-input" name="accounts[]" value="{{ $account->id }}" id="customCheck{{ $account->id }}" type="checkbox">
                                            <label class="custom-control-label" for="customCheck{{ $account->id }}">{{ $account->name }}</label>
                                        </div>
                                        @endforeach
                                        @include('alerts.feedback', ['field' => 'accounts'])
                                        <div class="text-left">
                                            <button type="submit" class="btn btn-default my-4">Invite</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @include('alerts.success')
    <div class="row pt-5">
        <div class="col-12 pb-3">
            <p class="h2">Accounts:</p>
        </div>
        @foreach($accounts as $account)
        <div class="col-lg-4 col-sm-6 col-12">
            <div class="face"></div>
            <div class="card account" id="{{ isset($account) ? $account->id : '' }}">
                <!-- Card header -->
                <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0 account-header">{{ isset($account) ? $account->name : '' }}</h5>
                    <a href="/dashboard/{{ $account->name }}" target="_blank"><img id="icon-{{ $account->id }}-black" src="{{ asset('svg') }}/map.svg" class="map-icon-black"  style="width:22px" data-toggle="tooltip" data-placement="top" title="Explore Map"></a>
                    <a href="/dashboard/{{ $account->name }}" target="_blank"><img id="icon-{{ $account->id }}-white" src="{{ asset('svg') }}/map-white.svg" class="map-icon-white"  style="width:22px" data-toggle="tooltip" data-placement="top" title="Explore Map"></a>
                </div>
                <!-- Card body -->
                <div class="card-body" style="height:300px;">
                    <div id="map-account-{{ $account->id }}" class="map-border" style="width: 100%; height: 250px;"></div>
                </div>
                <script>
                    mapboxgl.accessToken = 'pk.eyJ1IjoicG93ZXJtYXJrZXQiLCJhIjoiY2s3b3ZncDJ0MDkwZTNlbWtoYWY2MTZ6ZCJ9.Ywq8CoJ8OHXlQ4voDr4zow';
                    var lat = '{!! $account->lat ?? '
                    ' !!}';
                    var lon = '{!! $account->lon ?? '
                    ' !!}';
                    var id = '{!! $account->id ?? '
                    ' !!}';
                    var map = new mapboxgl.Map({
                        container: 'map-account-' + id,
                        style: 'mapbox://styles/mapbox/satellite-streets-v11',
                        bearing: -17.6,
                        antialias: true,
                        zoom: 10,
                        center: [lon, lat]
                    });
                    var marker = new mapboxgl.Marker({
                            color: '#F6A22B'
                        })
                        .setLngLat([lon, lat])
                        .addTo(map);
                </script>
            </div>
        </div>
        @endforeach
    </div>
    @foreach($accounts as $account)
    <div class="row" id="account-{{ $account->id }}" style="display: none;">
        <div class="col-12 pb-3">
            <p class="h2">Data Sets:</p>
        </div>
        @foreach($account->regions as $region)
        <div class="col-lg-4 col-sm-6 col-12">
            <div class="card region" id="{{ $region->id }}">
                <!-- Card header -->
                <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0 account-header">{{ $region->name }}</h5>
                    <a href="/dashboard/{{ $account->name }}/{{ $region->name }}" target="_blank"><img src="{{ asset('svg') }}/map.svg" class="map-icon-black" style="width:22px" data-toggle="tooltip" data-placement="top" title="Explore Map"></a>
                </div>
                <!-- Card body -->
                <div class="card-body" style="height:300px;">
                    <div id="map-region-{{ $region->id }}" class="map-border" style="height: 250px;"></div>
                </div>
                <script>
                    mapboxgl.accessToken = 'pk.eyJ1IjoicG93ZXJtYXJrZXQiLCJhIjoiY2s3b3ZncDJ0MDkwZTNlbWtoYWY2MTZ6ZCJ9.Ywq8CoJ8OHXlQ4voDr4zow';
                    var lat = '{!! $region->lat ?? '
                    ' !!}';
                    var lon = '{!! $region->lon ?? '
                    ' !!}';
                    var id = '{!! $region->id ?? '
                    ' !!}';
                    var map = new mapboxgl.Map({
                        container: 'map-region-' + id,
                        style: 'mapbox://styles/mapbox/satellite-streets-v11',
                        bearing: -17.6,
                        antialias: true,
                        zoom: 10,
                        center: [lon, lat],
                        attributionControl: false
                    });
                    var marker = new mapboxgl.Marker({
                            color: '#F6A22B'
                        })
                        .setLngLat([lon, lat])
                        .addTo(map);
                </script>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
    <!-- projects that are created by me -->
    @if(!auth()->user()->isMember())
    <div class="row" id="cluster-row">
        <div class="col-12 pb-3">
            <p class="h2">Projects Created by Me</p>
        </div>
        @if(isset($clusters))
        @foreach($my_clusters as $cluster)
        <div class="col-lg-4 col-sm-6 col-12">
            <div class="card cluster" id="{{ $cluster->id }}">
                <!-- Card header -->
                <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0 account-header">{{ $cluster->name }}</h5>
                    <a class="delete" data-target="#delete-form" data-toggle="modal" data-id="{{$cluster->id}}"><i class="fa fa-trash-alt map-icon-black card-icons" style="font-size:20px;color:#191B2F;" data-toggle="tooltip" data-placement="top" title="Delete Project"></i></a>
                    <a href="/projects/{{ $cluster->name }}" target="_blank"><img src="{{ asset('svg') }}/map.svg" class="map-icon-black report-icon card-icons"  style="width:20px" data-toggle="tooltip" data-placement="top" title="Explore Map"/></a>
                    <a href="/reporting/project/{{ $cluster->name }}" target="_blank"><i class="ni ni-chart-bar-32 map-icon-black report-icon card-icons" style="font-size:20px" data-toggle="tooltip" data-placement="top" title="View Report"></i></a>

                    <a class="share-button" data-toggle="modal" data-target="#share-form" target="_blank" ><i class="ni ni-curved-next map-icon-black report-icon" style="font-size:20px" data-toggle="tooltip" data-placement="top" title="Share Project"></i></a>

                        <div class="modal fade " id="share-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-0">
                                        <div class="card bg-secondary shadow border-0 mb-0">
                                            <div class="card-header bg-white">
                                                <div class="text-muted text-left mb-3">
                                                    <h2>Share with users from your organization</h2>
                                                </div>
                                            </div>
                                            <div class="card-body bg-white">

                                                <form method="post" action="/share/clusters/{cluser_id}" role="form">
                                                    @csrf
                                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                        <!-- <label class="form-control-label" for="input-user">{{ __('Select user') }}</label> -->
                                                        <input list="org-members" type="text"  autocomplete="off" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Select a user') }}" value="{{ old('name') }}" required autofocus>

                                                        <datalist id="org-members">
                                                            @foreach($members as $member)
                                                                <option data-user="{{$member->id}}" value={{$member->name}}> </option>
                                                            @endforeach
                                                        </datalist>

                                                        @include('alerts.feedback', ['field' => 'name'])
                                                    </div>


                                                    <!-- <h4>User can edit?</h4>
                                                    <div class="form-group{{ $errors->has('is_admin') ? ' has-danger' : '' }}">
                                                        <label class="custom-toggle custom-toggle-success">
                                                            <input type="checkbox" name="is_admin" value="1" checked>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="NO" data-label-on="YES"></span>
                                                        </label>
                                                        @include('alerts.feedback', ['field' => 'is_admin'])
                                                    </div> -->
                                                    <!-- @include('alerts.feedback', ['field' => 'accounts']) -->
                                                    <div class="text-left">
                                                        <button type="submit" class="btn btn-default my-4">Share</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- Card body -->
                <div class="card-body add-cluster" style="height:300px;max-width:100%;">
                    <img style="height: 250px;max-width:100%;object-fit:cover;border-radius:.375rem;" src="https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v11/static/pin-s+F6A22B({{$cluster->lon}},{{$cluster->lat}})/{{$cluster->lon}},{{$cluster->lat}},10,0,0/800x300?access_token=pk.eyJ1IjoicG93ZXJtYXJrZXQiLCJhIjoiY2s3b3ZncDJ0MDkwZTNlbWtoYWY2MTZ6ZCJ9.Ywq8CoJ8OHXlQ4voDr4zow">
                </div>
            </div>
        </div>
        @endforeach
        @endif
        <div class="col-lg-4 col-sm-6 col-12">
            <div class="card add-cluster">
                <a data-toggle="modal" data-target="#cluster-form" target="_blank" class="add-button"><img src="{{ asset('svg') }}/add-button.svg" class="rounded-circle border-secondary" style="width:4em" data-toggle="tooltip" data-placement="top" title="Add Project"></a>
            </div>
        </div>
    </div>
    @endif
                        <!-- projects that are shared with me  -->
    @if(!auth()->user()->isMember())
    <div class="row" id="cluster-row">
        <div class="col-12 pb-3">
            <p class="h2">Projects Shared with Me</p>
        </div>
        @if(isset($clusters))
        @foreach($clusters as $cluster)
        <div class="col-lg-4 col-sm-6 col-12">
            <div class="card cluster" id="{{ $cluster->id }}">
                <!-- Card header -->
                <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0 account-header">{{ $cluster->name }}</h5>
                    <a class="delete" data-target="#delete-form" data-toggle="modal" data-id="{{$cluster->id}}"><i class="fa fa-trash-alt map-icon-black card-icons" style="font-size:20px;color:#191B2F;" data-toggle="tooltip" data-placement="top" title="Delete Project"></i></a>
                    <a href="/projects/{{ $cluster->name }}" target="_blank"><img src="{{ asset('svg') }}/map.svg" class="map-icon-black report-icon card-icons"  style="width:20px" data-toggle="tooltip" data-placement="top" title="Explore Map"/></a>
                    <a href="/reporting/project/{{ $cluster->name }}" target="_blank"><i class="ni ni-chart-bar-32 map-icon-black report-icon card-icons" style="font-size:20px" data-toggle="tooltip" data-placement="top" title="View Report"></i></a>

                    <a class="share-button" data-toggle="modal" data-target="#share-form" target="_blank" ><i class="ni ni-curved-next map-icon-black report-icon" style="font-size:20px" data-toggle="tooltip" data-placement="top" title="Share Project"></i></a>

                        <div class="modal fade " id="share-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-0">
                                        <div class="card bg-secondary shadow border-0 mb-0">
                                            <div class="card-header bg-white">
                                                <div class="text-muted text-left mb-3">
                                                    <h2>Share with users from your organization</h2>
                                                </div>
                                            </div>
                                            <div class="card-body bg-white">

                                                <form method="post" action="/share/clusters/{cluser_id}" role="form">
                                                    @csrf
                                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                        <!-- <label class="form-control-label" for="input-user">{{ __('Select user') }}</label> -->
                                                        <input list="org-members" type="text"  autocomplete="off" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Select a user') }}" value="{{ old('name') }}" required autofocus>

                                                        <datalist id="org-members">
                                                            @foreach($members as $member)
                                                                <option data-user="{{$member->id}}" value={{$member->name}}> </option>
                                                            @endforeach
                                                        </datalist>

                                                        @include('alerts.feedback', ['field' => 'name'])
                                                    </div>


                                                    <!-- <h4>User can edit?</h4>
                                                    <div class="form-group{{ $errors->has('is_admin') ? ' has-danger' : '' }}">
                                                        <label class="custom-toggle custom-toggle-success">
                                                            <input type="checkbox" name="is_admin" value="1" checked>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="NO" data-label-on="YES"></span>
                                                        </label>
                                                        @include('alerts.feedback', ['field' => 'is_admin'])
                                                    </div> -->
                                                    <!-- @include('alerts.feedback', ['field' => 'accounts']) -->
                                                    <div class="text-left">
                                                        <button type="submit" class="btn btn-default my-4">Share</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- Card body -->
                <div class="card-body add-cluster" style="height:300px;max-width:100%;">
                    <img style="height: 250px;max-width:100%;object-fit:cover;border-radius:.375rem;" src="https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v11/static/pin-s+F6A22B({{$cluster->lon}},{{$cluster->lat}})/{{$cluster->lon}},{{$cluster->lat}},10,0,0/800x300?access_token=pk.eyJ1IjoicG93ZXJtYXJrZXQiLCJhIjoiY2s3b3ZncDJ0MDkwZTNlbWtoYWY2MTZ6ZCJ9.Ywq8CoJ8OHXlQ4voDr4zow">
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    @endif


</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('css') }}/report.css" />
<link rel="stylesheet" href="{{ asset('css') }}/mapbox-gl.css" />
@endpush
@push('js')
<script src="{{ asset('js') }}/mapbox-gl.js"></script>
<script>
    var active_account = 0;
    var clicked_project;
    var errors_empty = '{!! $errors->isEmpty() !!}';
    $(document).ready(function() {
        if (errors_empty != 1) {
            $('#modal-form').modal('show');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $("#cluster-row").on('click', '.delete', function(event){
            clicked_project = event.currentTarget.getAttribute("data-id")
        })
        $("#delete-yes").click(function(event) {
            var formData = {
                '_token': $('input[name=_token]').val()
            }
            $.ajax({
                type: 'DELETE',
                url: '/clusters/' + clicked_project,
                data: formData,
                dataType: 'json',
                encode: true
            }).done(function(data) {
                $('#delete-form').modal('hide')
                $('#delete-next-form').modal('show')
                $('#delete-next-response-status').text(data.message).css('display', 'block').addClass('alert-success').removeClass('alert-danger').delay(3000).fadeOut();
                $(`#${clicked_project}`).parent().remove()
            }).fail(function(data) {
                $('#delete-form').modal('hide')
                $('#delete-next-form').modal('show')
                $('#delete-next-response-status').text(data.responseJSON.message).css('display', 'block').addClass('alert-danger').removeClass('alert-success').delay(3000).fadeOut();
            });
        })
        $(".card.account").click(function(event) {
            if (active_account == event.currentTarget.id) return;
            //setting styles for previous active account
            $("[id=account-" + active_account + "]").css('display', 'none'); //don't show the datasets for the previous selected account
            var account_card = $("#" + active_account + ".card.account");
            account_card.css('color', 'black');
            var face = account_card.prev();
            face.css('display', 'none');
            $('#icon-' + active_account + '-black').css('display', 'inline-block')
            $('#icon-' + active_account + '-white').css('display', 'none')
            //setting styles for new active account
            active_account = event.currentTarget.id;
            $("[id=account-" + active_account + "]").css({
                'display': 'flex'
            });
            account_card = $("#" + active_account + ".card.account");
            account_card.css('color', 'white');
            face = account_card.prev();
            face.css('display', 'flex');
            $('#icon-' + active_account + '-black').css('display', 'none')
            $('#icon-' + active_account + '-white').css('display', 'inline-block')
        });
        $(".card.account").first().click();
        window.dispatchEvent(new Event('resize'));
        $('#cluster-form').submit(function(event) {
            event.preventDefault();
            var visiblePoints = [];
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
                $('#response-status').text(data.message).css('display', 'block').addClass('alert-success').removeClass('alert-danger').delay(2000).fadeOut();
                $('#cluster-row').children().last().before(`
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="card cluster" id="${data.cluster.id}">
                            <!-- Card header -->
                            <div class="card-header">
                                <!-- Title -->
                                <h5 class="h3 mb-0 account-header">${data.cluster.name}</h5>
                                <a class="delete" data-target="#delete-form" data-toggle="modal" data-id="${data.cluster.id}"><i class="fa fa-trash-alt map-icon-black" style="font-size:22px;color:#191B2F;"></i></a>
                                <a href="/projects/${data.cluster.name}" target="_blank"><img src="{{ asset('svg') }}/map.svg" class="map-icon-black report-icon" /></a>
                                <a href="/reporting/project/${data.cluster.name}" target="_blank"><i class="ni ni-chart-pie-35 map-icon-black report-icon"></i></a>
                            </div>
                            <!-- Card body -->
                            <div class="card-body add-cluster" style="height:300px;max-width:100%;">
                                <img style="height: 250px;max-width:100%;object-fit:cover;border-radius:.375rem;" src="https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v11/static/pin-s+F6A22B(${data.cluster.lon},${data.cluster.lat})/${data.cluster.lon},${data.cluster.lat},10,0,0/800x300?access_token=pk.eyJ1IjoicG93ZXJtYXJrZXQiLCJhIjoiY2s3b3ZncDJ0MDkwZTNlbWtoYWY2MTZ6ZCJ9.Ywq8CoJ8OHXlQ4voDr4zow">
                            </div>
                        </div>
                    </div>
                `)
            }).fail(function(data) {
                $('#response-status').text(data.responseJSON.message).css('display', 'block').addClass('alert-danger').removeClass('alert-success').delay(2000).fadeOut();
            });
        });
        //grab project id when share button is clicked
        var projectId;
        var selectedMember;
        $('a[class="share-button"]').on('click',function(){
            projectId = $(this).closest(".card.cluster").attr('id');
        })
        //grab selected member's id
        $("input[list='org-members']").on('change', function(){

            var currentMember = $(this).val();
            var memberList = $(this)
                .siblings("#org-members")
                .find('option'); //all the options in the datalist
            //compare current selected member with each of the options, find the one that matches and grabs the user id
            [...memberList].forEach(member => member.value === currentMember ? selectedMember = member.getAttribute('data-user'):'')

        })
        //share request
        $('#share-form').submit(function(event) {
            event.preventDefault();

            var formData = {
                'cluster_id': projectId,
                'co_owners': selectedMember,

            };
            $.ajax({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/share/clusters/' + projectId,
                data: formData,
                dataType: 'json',
                encode: true
            }).done(function(data) {
                $('#share-form').modal('hide')
                $('#delete-next-form').modal('show')
                $('#delete-next-response-status').text(data.message).css('display', 'block').addClass('alert-success').removeClass('alert-danger').delay(3000).fadeOut();
            }).fail(function(data) {
                $('#response-status').text(data.responseJSON.message).css('display', 'block').addClass('alert-danger').removeClass('alert-success').delay(2000).fadeOut();
            });
        });
    });
</script>
@endpush
