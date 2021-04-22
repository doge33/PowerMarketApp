@extends('errors.minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Page Expired'))

<img src="{{ asset('svg/404.svg') }}" id="bg-419"/>

<style>
    #bg-419{
        position: absolute;
    }
</style>
