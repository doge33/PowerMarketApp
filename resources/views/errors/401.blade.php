@extends('errors.minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized'))

<img src="{{ asset('svg/404.svg') }}" id="bg-401"/>

<style>
    #bg-401{
        position: absolute;
    }
</style>
