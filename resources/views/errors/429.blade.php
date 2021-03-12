@extends('errors.minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests'))

<img src="{{ asset('svg/404.svg') }}" id="bg-429"/>

<style>
    #bg-429{
        position: absolute;
    }
</style>
