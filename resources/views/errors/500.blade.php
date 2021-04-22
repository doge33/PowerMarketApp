@extends('errors.minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))

<img src="{{ asset('svg/503.svg') }}" id="bg-500"/>

<style>
    #bg-500{
        position: absolute;
    }
</style>
