<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 28.06.17
 * Time: 17:18
 */?><!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>

<div class="container">

    <div class="form form-horizontal">
        <h3>{!! trans('interface.timer') !!} {!! $ticket->quest->timer !!} {!! trans('interface.minutes') !!}</h3>
{{--
        <div class="form-group">
            <label class="col-md-2 control-label">{{ trans('interface.source') }}</label>
            <div class="col-md-10 form-control-static">
                {!! $ticket->quest->source !!}
            </div>
        </div>
--}}
        <div class="form-group">
            <label class="col-md-2 control-label">{{ trans('interface.task') }}</label>
            <div class="col-md-10 form-control-static">
                {!! $ticket->quest->task !!}
            </div>
        </div>
        <hr />
        <div class="form-group">
            <label class="col-md-2 control-label">{{ trans('interface.answer') }}</label>
            <div class="col-md-10 form-control-static">
                {!! $ticket->answer !!}
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

