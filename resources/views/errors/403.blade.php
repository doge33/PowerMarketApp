@extends('errors.minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __('Forbidden'))


<img src="{{ asset('svg/403.svg') }}" id="bg-403"/>

<style>
    #bg-403{
        position: absolute;
    }
</style>
