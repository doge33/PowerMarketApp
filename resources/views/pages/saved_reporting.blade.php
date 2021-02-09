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
<div class="container mt--8 pb-5">
    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="pricing card-group flex-column flex-md-row mb-3">
                    <div class="card card-pricing border-0 text-center mb-4">
                        <div class="card-header bg-transparent">
                            <h4 class="text-uppercase ls-1 text-primary py-3 mb-0">Sample Report</h4>
                        </div>
                        <div class="card-body px-lg-7">
                            <img src="{{ asset('argon') }}/img/theme/PMD-1.png" width="100%" class="" alt="...">

                        </div>
                        <a href="{{ route('page.pricing') }}"><button type="button" class="btn btn-primary mb-3">Upgrade to Download Reports</button></a>
                        <br>

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
