@extends('errors.minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found'))

<img src="{{ asset('svg/404.svg') }}" id="bg-404"/>

<style>
#bg-404{
    position: absolute;
}
</style>
