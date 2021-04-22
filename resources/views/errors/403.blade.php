@extends('errors.minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __('Forbidden'))

@if($exception ?? '')
    @section('message', __($exception ->getMessage() ?: 'Forbidden'))
@else
    @section('message', __('Forbidden'))
@endif

<img src="{{ asset('svg/404.svg') }}" id="bg-403"/>

<style>
    #bg-403{
        position: absolute;
    }
</style>
