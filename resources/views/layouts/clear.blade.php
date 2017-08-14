<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 29.06.17
 * Time: 17:52
 */?><!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
<style>
    img {
        width: 100%;
    }
</style>
{!! $content !!}
</body>