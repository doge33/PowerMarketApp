@extends('errors.minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@if($exception ?? '')
    @section('message', __($exception ->getMessage() ?: 'Service Unavailable'))
@else
    @section('message', __('Service Unavailable'))
@endif


<img src="{{ asset('svg/503.svg') }}" id="bg-503"/>

<style>
    #bg-503{
        position: absolute;
    }
</style>
